<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['chat_id', 'sender', 'message', 'type', 'file_path', 'file_name', 'file_size', 'status', 'reply_to', 'reaction', 'is_deleted', 'is_pinned', 'forwarded_from', 'location_lat', 'location_lng', 'contact_name', 'contact_phone', 'gif_url', 'poll_options', 'poll_votes'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to');
    }

    public function forwardedFrom()
    {
        return $this->belongsTo(Message::class, 'forwarded_from');
    }

    public function forwardedMessages()
    {
        return $this->hasMany(Message::class, 'forwarded_from');
    }
}
