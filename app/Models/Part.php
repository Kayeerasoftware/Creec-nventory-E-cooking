<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = ['part_number', 'name', 'appliance_id', 'location', 'description', 'availability', 'comments', 'image_path', 'price'];

    public function appliance()
    {
        return $this->belongsTo(Appliance::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'part_brands');
    }

    public function specificAppliances()
    {
        return $this->belongsToMany(SpecificAppliance::class, 'part_specific_appliances');
    }
}
