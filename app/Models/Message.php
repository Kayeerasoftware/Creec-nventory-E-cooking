<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'sender',
        'sender_name',
        'receiver_type',
        'receiver_name',
        'message',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'reply_to',
        'status',
        'reaction',
        'is_deleted',
        'is_pinned'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender()
    {
        return match($this->sender_type) {
            'user' => $this->belongsTo(User::class, 'sender_id'),
            'technician' => $this->belongsTo(Technician::class, 'sender_id'),
            'trainer' => $this->belongsTo(Trainer::class, 'sender_id'),
            'admin' => $this->belongsTo(User::class, 'sender_id'),
            default => null
        };
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }
}