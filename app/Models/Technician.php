<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Technician extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'specialty', 'email', 'phone', 'license', 'location', 'experience', 'rate',
        'skills', 'certifications', 'image', 'password', 'cohort_number', 'venue',
        'training_dates', 'certificate', 'gender', 'age', 'company', 'last_seen', 'status',
        'place_of_work', 'first_name', 'middle_name', 'last_name', 'date_of_birth',
        'nationality', 'id_number', 'phone_1', 'phone_2', 'whatsapp', 'emergency_contact',
        'emergency_phone', 'country', 'region', 'district', 'sub_county', 'parish', 'village',
        'postal_code', 'sub_specialty', 'license_number', 'license_expiry', 'hourly_rate',
        'daily_rate', 'employment_type', 'start_date', 'training', 'languages', 'own_tools',
        'has_vehicle', 'vehicle_type', 'equipment_list', 'service_areas', 'previous_employer',
        'previous_position', 'years_at_previous', 'reference_name', 'reference_phone',
        'notes', 'medical_conditions', 'jobs_completed', 'rating', 'response_time', 'title',
        'profile_photo', 'profile_picture'
    ];

    public function getProfilePictureAttribute()
    {
        return $this->profile_photo ?? $this->image;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'skills' => 'array',
        'certifications' => 'array',
        'experience' => 'integer',
        'rate' => 'decimal:2',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
