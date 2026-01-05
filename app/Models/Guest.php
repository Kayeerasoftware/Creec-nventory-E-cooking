<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'location',
        'session_id',
        'last_seen'
    ];
    
    protected $casts = [
        'last_seen' => 'datetime'
    ];
}
