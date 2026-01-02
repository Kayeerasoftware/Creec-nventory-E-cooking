<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'name',
        'specialty',
        'email',
        'phone',
        'experience',
        'qualifications',
        'image',
        'location'
    ];

    protected $casts = [
        'experience' => 'integer',
    ];
}
