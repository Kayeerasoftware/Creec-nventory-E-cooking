<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Trainer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'specialty',
        'email',
        'phone',
        'experience',
        'qualifications',
        'image',
        'location',
        'last_seen',
        'password',
        'profile_picture',
    ];

    public function getProfilePictureAttribute($value)
    {
        return $value ?? $this->image;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'experience' => 'integer',
        'password' => 'hashed',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    protected static function booted()
    {
        static::created(function ($trainer) {
            User::create([
                'name' => $trainer->name,
                'email' => $trainer->email,
                'password' => Hash::make('trainer123'),
                'role' => 'trainer',
                'trainer_id' => $trainer->id,
            ]);
        });
    }
}
