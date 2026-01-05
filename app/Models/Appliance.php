<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appliance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'icon',
        'model',
        'power',
        'sku',
        'status',
        'brand_id',
        'description',
        'price',
        'voltage',
        'frequency',
        'capacity',
        'weight',
        'dimensions',
        'cost_price',
        'quantity',
        'warranty',
        'location',
        'features',
        'certifications',
        'energy_rating',
        'country_origin',
        'supplier_name',
        'supplier_contact',
        'last_maintenance',
        'next_maintenance',
        'maintenance_notes',
        'image',
        'image_path',
        'manual',
        'notes'
    ];

    protected $casts = [
        'image' => 'string',
    ];

    protected $appends = ['image_url'];

    public function setImageAttribute($value)
    {
        if ($value && (str_contains($value, 'Temp') || str_contains($value, '.tmp'))) {
            return;
        }
        $this->attributes['image'] = $value;
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        // If it's already a full URL, return as is
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        
        // If it's a temp path (contains 'Temp'), return null
        if (str_contains($this->image, 'Temp') || str_contains($this->image, 'tmp')) {
            return null;
        }
        
        // Otherwise, construct the storage URL
        return asset('storage/' . $this->image);
    }

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public function specificAppliances()
    {
        return $this->hasMany(SpecificAppliance::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
