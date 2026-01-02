<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function startChat(Request $request): JsonResponse
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
        ]);

        $chat = Chat::firstOrCreate(['user_name' => $request->user_name]);

        return response()->json(['chat_id' => $chat->id]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'sender' => 'required|in:user,support',
            'message' => 'nullable|string',
            'type' => 'required|in:text,image,video,audio,document',
            'file' => 'nullable|file|max:10240', // 10MB max
            'reply_to' => 'nullable|exists:messages,id',
        ]);

        $data = $request->only(['chat_id', 'sender', 'message', 'type', 'reply_to']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('chat_files', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $message = Message::create($data);

        return response()->json($message->load('replyTo'));
    }

    public function getMessages(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
        ]);

        $messages = Message::where('chat_id', $request->chat_id)
            ->where('is_deleted', false)
            ->with('replyTo')
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    public function updateMessageStatus(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
            'status' => 'required|in:sent,delivered,read',
        ]);

        $message = Message::find($request->message_id);
        $message->update(['status' => $request->status]);

        return response()->json($message);
    }

    public function reactToMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
            'reaction' => 'required|string|max:10',
        ]);

        $message = Message::find($request->message_id);
        $message->update(['reaction' => $request->reaction]);

        return response()->json($message);
    }

    public function deleteMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
        ]);

        $message = Message::find($request->message_id);
        $message->update(['is_deleted' => true]);

        return response()->json(['success' => true]);
    }

    public function typingIndicator(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'is_typing' => 'required|boolean',
            'sender' => 'required|in:user,support',
        ]);

        // For simplicity, just return success. In real app, broadcast to other users
        return response()->json(['success' => true]);
    }

    public function pinMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
        ]);

        $message = Message::find($request->message_id);
        $message->update(['is_pinned' => !$message->is_pinned]);

        return response()->json($message);
    }

    public function forwardMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
            'chat_id' => 'required|exists:chats,id',
        ]);

        $originalMessage = Message::find($request->message_id);
        $forwardedMessage = Message::create([
            'chat_id' => $request->chat_id,
            'sender' => 'user', // or support
            'message' => $originalMessage->message,
            'type' => $originalMessage->type,
            'file_path' => $originalMessage->file_path,
            'file_name' => $originalMessage->file_name,
            'file_size' => $originalMessage->file_size,
            'forwarded_from' => $originalMessage->id,
        ]);

        return response()->json($forwardedMessage->load('forwardedFrom'));
    }

    public function searchMessages(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'query' => 'required|string|min:1',
        ]);

        $messages = Message::where('chat_id', $request->chat_id)
            ->where('is_deleted', false)
            ->where('message', 'like', '%' . $request->query . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($messages);
    }

    public function sendLocation(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender' => 'user',
            'type' => 'location',
            'location_lat' => $request->lat,
            'location_lng' => $request->lng,
        ]);

        return response()->json($message);
    }

    public function sendContact(Request $request): JsonResponse
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'name' => 'required|string',
            'phone' => 'required|string',
        ]);

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender' => 'user',
            'type' => 'contact',
            'contact_name' => $request->name,
            'contact_phone' => $request->phone,
        ]);

        return response()->json($message);
    }
}
