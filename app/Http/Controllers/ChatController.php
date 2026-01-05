<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Technician;
use App\Models\Trainer;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function getSupportUsers()
    {
        try {
            $technicians = Technician::select('id', 'name', 'specialty', 'status', 'phone', 'last_seen', 'location', 'image', 'profile_picture')
                ->get()
                ->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'type' => 'technician',
                    'specialty' => $t->specialty ?? 'Technician',
                    'status' => $t->status ?? 'Available',
                    'phone' => $t->phone,
                    'location' => $t->location ?? 'N/A',
                    'image' => $t->profile_picture ?? $t->image,
                    'is_online' => $t->last_seen && $t->last_seen->diffInMinutes(now()) < 5
                ]);

            $trainers = Trainer::select('id', 'name', 'specialty', 'phone', 'last_seen', 'location', 'image')
                ->get()
                ->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'type' => 'trainer',
                    'specialty' => $t->specialty ?? 'Trainer',
                    'status' => 'Active',
                    'phone' => $t->phone,
                    'location' => $t->location ?? 'N/A',
                    'image' => $t->image,
                    'is_online' => $t->last_seen && $t->last_seen->diffInMinutes(now()) < 5
                ]);

            $admins = User::where('role', 'admin')
                ->select('id', 'name', 'role', 'email', 'last_seen', 'profile_picture')
                ->get()
                ->map(fn($u) => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'type' => 'admin',
                    'specialty' => 'System Administrator',
                    'status' => 'Available',
                    'phone' => $u->email,
                    'location' => 'Head Office',
                    'image' => $u->profile_picture,
                    'is_online' => $u->last_seen && $u->last_seen->diffInMinutes(now()) < 5
                ]);

            return response()->json([
                'success' => true,
                'technicians' => $technicians,
                'trainers' => $trainers,
                'admins' => $admins
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getSupportUsers: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getOrCreateChat(Request $request)
    {
        $sessionId = $request->header('X-Guest-Session');
        $guest = null;
        
        if ($sessionId) {
            $guest = Guest::where('session_id', $sessionId)->first();
        }
        
        // Normalize support type to singular
        $supportType = rtrim($request->support_type, 's'); // Remove trailing 's'
        
        // Get current user info if not a guest
        $currentUserId = null;
        $currentUserType = null;
        
        if (!$guest && auth()->check()) {
            $currentUser = auth()->user();
            $currentUserType = $currentUser->role;
            $currentUserId = $currentUser->id;
        }
        
        // Create unique chat identifier - ALWAYS in consistent order
        if ($guest) {
            $userName = 'guest_' . $guest->id . '_' . $supportType . '_' . $request->support_id;
        } elseif ($currentUserId && $currentUserType) {
            // Create consistent identifier by sorting user types alphabetically
            $user1Type = $currentUserType;
            $user1Id = $currentUserId;
            $user2Type = $supportType;
            $user2Id = $request->support_id;
            
            // Sort to ensure same chat regardless of who initiates
            $users = [
                ['type' => $user1Type, 'id' => $user1Id],
                ['type' => $user2Type, 'id' => $user2Id]
            ];
            usort($users, function($a, $b) {
                if ($a['type'] === $b['type']) {
                    return $a['id'] <=> $b['id'];
                }
                return $a['type'] <=> $b['type'];
            });
            
            $userName = $users[0]['type'] . '_' . $users[0]['id'] . '_' . $users[1]['type'] . '_' . $users[1]['id'];
        } else {
            $userName = 'user_0_' . $supportType . '_' . $request->support_id;
        }
        
        $chat = Chat::firstOrCreate(
            ['user_name' => $userName],
            [
                'user_name' => $userName,
                'support_type' => $supportType,
                'support_id' => $request->support_id
            ]
        );
        
        // Update support info if not set
        if (!$chat->support_type || !$chat->support_id) {
            $chat->update([
                'support_type' => $supportType,
                'support_id' => $request->support_id
            ]);
        }

        return response()->json($chat);
    }

    public function getMessages($chatId)
    {
        $messages = ChatMessage::where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function getCurrentUser()
    {
        if (auth()->check()) {
            $user = auth()->user();
            return response()->json([
                'is_authenticated' => true,
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'type' => $user->role
            ]);
        }

        return response()->json([
            'is_authenticated' => false,
            'name' => 'Guest User',
            'role' => 'guest',
            'type' => 'guest'
        ]);
    }

    public function sendMessage(Request $request)
    {
        $sessionId = $request->header('X-Guest-Session');
        $guest = null;
        
        if ($sessionId) {
            $guest = Guest::where('session_id', $sessionId)->first();
            if ($guest) {
                $guest->update(['last_seen' => now()]);
            }
        }
        
        $senderType = $guest ? 'guest' : ($request->sender_type ?? 'user');
        $senderId = $guest ? $guest->id : ($request->sender_id ?? 0);

        $message = ChatMessage::create([
            'chat_id' => $request->chat_id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $request->message,
            'message_type' => $request->message_type ?? 'text',
            'file_path' => $request->file_path ?? null,
            'file_name' => $request->file_name ?? null,
            'file_type' => $request->file_type ?? null,
            'file_size' => $request->file_size ?? null,
            'is_read' => false
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function markAsRead(Request $request)
    {
        $sessionId = $request->header('X-Guest-Session');
        
        if ($sessionId) {
            // Guest is marking support user messages as read
            $guest = Guest::where('session_id', $sessionId)->first();
            if ($guest) {
                ChatMessage::where('chat_id', $request->chat_id)
                    ->where('sender_type', '!=', 'guest')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        } else {
            // Logged-in user is marking messages as read
            if (auth()->check()) {
                $currentUser = auth()->user();
                $currentUserType = $currentUser->role;
                $currentUserId = $currentUser->id;
                
                // Mark all messages NOT sent by current user as read
                ChatMessage::where('chat_id', $request->chat_id)
                    ->where(function($q) use ($currentUserType, $currentUserId) {
                        $q->where('sender_type', '!=', $currentUserType)
                          ->orWhere(function($subQ) use ($currentUserType, $currentUserId) {
                              $subQ->where('sender_type', $currentUserType)
                                   ->where('sender_id', '!=', $currentUserId);
                          });
                    })
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        return response()->json(['success' => true]);
    }

    private function getCurrentUserInfo()
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // If user has trainer profile, get trainer details
            if ($user->role === 'trainer' && $user->trainer_id) {
                $trainer = $user->trainer;
                return [
                    'name' => $trainer->name ?? $user->name,
                    'type' => 'trainer',
                    'phone' => $trainer->phone ?? null,
                    'location' => $trainer->location ?? null
                ];
            }
            
            // If user has technician profile, get technician details
            if ($user->role === 'technician' && $user->technician_id) {
                $technician = $user->technician;
                return [
                    'name' => $technician->name ?? $user->name,
                    'type' => 'technician',
                    'phone' => $technician->phone ?? null,
                    'location' => $technician->location ?? null
                ];
            }
            
            // Default user info
            return [
                'name' => $user->name,
                'type' => $user->role,
                'phone' => null,
                'location' => null
            ];
        }

        $sessionId = request()->header('X-Guest-Session');
        if ($sessionId) {
            $guest = Guest::where('session_id', $sessionId)->first();
            if ($guest) {
                return ['name' => $guest->name, 'type' => 'guest', 'phone' => $guest->phone, 'location' => $guest->location];
            }
        }

        return ['name' => 'Guest User', 'type' => 'guest', 'phone' => null, 'location' => null];
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        $file = $request->file('file');
        $path = $file->store('chat_files', 'public');

        return response()->json([
            'path' => $path,
            'url' => Storage::url($path),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType()
        ]);
    }

    public function registerGuest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255'
        ]);

        $sessionId = Str::random(32);

        $guest = Guest::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'location' => $request->location,
            'session_id' => $sessionId
        ]);

        return response()->json([
            'success' => true,
            'guest' => $guest,
            'session_id' => $sessionId
        ]);
    }

    public function getGuestContacts(Request $request)
    {
        try {
            // Get current authenticated user
            if (!auth()->check()) {
                return response()->json([]);
            }
            
            $currentUser = auth()->user();
            $userType = $currentUser->role;
            
            // Get chats for this specific support user only
            $guestChats = Chat::where('user_name', 'like', 'guest_%')
                ->where('support_type', $userType)
                ->where('support_id', $currentUser->id)
                ->with(['messages' => function($q) {
                    $q->latest()->limit(1);
                }])
                ->get()
                ->groupBy(function($chat) {
                    if (preg_match('/guest_(\d+)/', $chat->user_name, $matches)) {
                        return $matches[1];
                    }
                    return null;
                })
                ->filter(function($chats, $guestId) {
                    return $guestId !== null;
                })
                ->map(function($chats, $guestId) {
                    $guest = Guest::find($guestId);
                    if (!$guest) return null;
                    
                    $latestChat = $chats->sortByDesc('updated_at')->first();
                    $lastMessage = $latestChat->messages->first();
                    $isOnline = $guest->last_seen && $guest->last_seen->diffInMinutes(now()) < 5;
                    
                    $unreadCount = ChatMessage::where('chat_id', $latestChat->id)
                        ->where('sender_type', 'guest')
                        ->where('is_read', false)
                        ->count();
                    
                    return [
                        'id' => $guest->id,
                        'name' => $guest->name,
                        'type' => 'guest',
                        'specialty' => $guest->location,
                        'phone' => $guest->phone,
                        'is_online' => $isOnline,
                        'chat_id' => $latestChat->id,
                        'last_message' => $lastMessage ? $lastMessage->message : null,
                        'last_message_time' => $lastMessage ? $lastMessage->created_at : null,
                        'last_seen' => $guest->last_seen,
                        'unread_count' => $unreadCount
                    ];
                })
                ->filter()
                ->values();

            return response()->json($guestChats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUnreadCounts(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'technicians' => 0,
                'trainers' => 0,
                'admins' => 0,
                'guests' => 0
            ]);
        }
        
        $currentUser = auth()->user();
        $currentUserType = $currentUser->role;
        $currentUserId = $currentUser->id;
        
        // Get all chats involving current user
        $userPattern = $currentUserType . '_' . $currentUserId;
        $chatIds = Chat::where('user_name', 'like', '%' . $userPattern . '%')->pluck('id');
        
        // Count chats with unread messages by sender type
        $counts = [
            'technicians' => 0,
            'trainers' => 0,
            'admins' => 0,
            'guests' => 0
        ];
        
        // For each sender type, count distinct chats with unread messages
        $techniciansCount = ChatMessage::whereIn('chat_id', $chatIds)
            ->where('sender_type', 'technician')
            ->where('is_read', false)
            ->when($currentUserType === 'technician', function($q) use ($currentUserId) {
                return $q->where('sender_id', '!=', $currentUserId);
            })
            ->distinct()
            ->count('chat_id');
        
        $trainersCount = ChatMessage::whereIn('chat_id', $chatIds)
            ->where('sender_type', 'trainer')
            ->where('is_read', false)
            ->when($currentUserType === 'trainer', function($q) use ($currentUserId) {
                return $q->where('sender_id', '!=', $currentUserId);
            })
            ->distinct()
            ->count('chat_id');
        
        $adminsCount = ChatMessage::whereIn('chat_id', $chatIds)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->when($currentUserType === 'admin', function($q) use ($currentUserId) {
                return $q->where('sender_id', '!=', $currentUserId);
            })
            ->distinct()
            ->count('chat_id');
        
        $guestsCount = ChatMessage::whereIn('chat_id', $chatIds)
            ->where('sender_type', 'guest')
            ->where('is_read', false)
            ->distinct()
            ->count('chat_id');
        
        return response()->json([
            'technicians' => $techniciansCount,
            'trainers' => $trainersCount,
            'admins' => $adminsCount,
            'guests' => $guestsCount
        ]);
    }

    public function updateLastSeen(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user->update(['last_seen' => now()]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function getGuestBySession(Request $request)
    {
        $guest = Guest::where('session_id', $request->session_id)->first();
        
        if ($guest) {
            return response()->json([
                'success' => true,
                'guest' => $guest
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }
}
