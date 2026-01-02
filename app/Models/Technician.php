<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialty',
        'email',
        'phone',
        'license',
        'location',
        'experience',
        'rate',
        'skills',
        'certifications',
        'image',
    ];

    protected $casts = [
        'skills' => 'array',
        'certifications' => 'array',
        'experience' => 'integer',
        'rate' => 'decimal:2',
    ];
}
