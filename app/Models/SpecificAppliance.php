<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificAppliance extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'appliance_id', 'brand_id'];

    public function appliance()
    {
        return $this->belongsTo(Appliance::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'part_specific_appliances');
    }
}
