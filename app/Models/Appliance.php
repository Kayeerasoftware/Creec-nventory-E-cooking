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
        'price'
    ];

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
