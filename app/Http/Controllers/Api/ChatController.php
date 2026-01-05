<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Technician;
use App\Models\Trainer;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function getCurrentUser()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $type = 'user';
            $profilePicture = null;
            
            // Determine user type and get profile picture
            if ($user->role === 'admin') {
                $type = 'admin';
            } elseif ($user->technician_id) {
                $type = 'technician';
                $technician = Technician::find($user->technician_id);
                if ($technician) {
                    $pic = $technician->profile_photo ?? $technician->image;
                    if ($pic) {
                        $profilePicture = asset('storage/' . $pic);
                    }
                }
            } elseif ($user->trainer_id) {
                $type = 'trainer';
                $trainer = Trainer::find($user->trainer_id);
                if ($trainer && $trainer->image) {
                    $profilePicture = asset('storage/' . $trainer->image);
                }
            }
            
            // Check for user's own profile picture
            if (!$profilePicture && $user->profile_picture) {
                $profilePicture = asset('storage/' . $user->profile_picture);
            }
            
            return response()->json([
                'is_authenticated' => true,
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'type' => $type,
                'profile_picture' => $profilePicture
            ]);
        }
        
        return response()->json(['is_authenticated' => false]);
    }
    public function getSupportUsers()
    {
        return response()->json([
            'technicians' => Technician::select('id', 'name', 'specialty', 'status', 'phone', 'profile_photo', 'image')
                ->get()->map(function($tech) {
                    $profilePic = $tech->profile_photo ?? $tech->image;
                    return [
                        'id' => $tech->id,
                        'name' => $tech->name ?: 'Technician',
                        'type' => 'technician',
                        'specialty' => $tech->specialty ?? 'Technician',
                        'status' => $tech->status ?? 'Available',
                        'phone' => $tech->phone,
                        'profile_picture' => $profilePic ? asset('storage/' . $profilePic) : null,
                        'is_online' => true
                    ];
                }),
            'trainers' => Trainer::select('id', 'name', 'specialty', 'status', 'phone', 'image')
                ->get()->map(function($trainer) {
                    return [
                        'id' => $trainer->id,
                        'name' => $trainer->name,
                        'type' => 'trainer',
                        'specialty' => $trainer->specialty ?? 'Trainer',
                        'status' => $trainer->status ?? 'Active',
                        'phone' => $trainer->phone,
                        'profile_picture' => $trainer->image ? asset('storage/' . $trainer->image) : null,
                        'is_online' => true
                    ];
                }),
            'admins' => collect([
                [
                    'id' => 1,
                    'name' => 'System Admin',
                    'type' => 'admin',
                    'specialty' => 'System Administration',
                    'status' => 'Available',
                    'phone' => '+256700000000',
                    'profile_picture' => null,
                    'is_online' => true
                ]
            ])
        ]);
    }

    public function getOrCreateChat(Request $request)
    {
        $sessionId = $request->header('X-Guest-Session');
        $guest = null;
        
        if ($sessionId) {
            $guest = Guest::where('session_id', $sessionId)->first();
        }
        
        $userName = $guest ? 'guest_' . $guest->id : ($request->support_type . '_' . $request->support_id);
        
        $chat = Chat::firstOrCreate(
            ['user_name' => $userName],
            ['user_name' => $userName]
        );

        return response()->json($chat);
    }

    public function getMessages(Chat $chat)
    {
        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $sessionId = $request->header('X-Guest-Session');
        $guest = null;
        
        if ($sessionId) {
            $guest = Guest::where('session_id', $sessionId)->first();
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
            'file_size' => $request->file_size ?? null
        ]);

        Chat::where('id', $request->chat_id)->update(['last_message_at' => now()]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function uploadFile(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['success' => false, 'message' => 'No file uploaded']);
        }

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('chat-uploads', $fileName, 'public');

        return response()->json([
            'success' => true,
            'file_path' => Storage::url($filePath),
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize()
        ]);
    }

    public function markAsRead(Request $request)
    {
        ChatMessage::where('chat_id', $request->chat_id)
            ->where('sender_type', '!=', 'user')
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
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
    
    public function getGuestContacts()
    {
        return response()->json([]);
    }
    
    public function getUnreadCounts()
    {
        return response()->json([
            'technicians' => 0,
            'trainers' => 0,
            'admins' => 0,
            'guests' => 0
        ]);
    }
    
    public function updateLastSeen()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->last_seen = now();
            $user->save();
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false]);
    }
}