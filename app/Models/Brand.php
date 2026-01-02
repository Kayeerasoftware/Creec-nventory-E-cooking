<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'part_brands');
    }

    public function specificAppliances()
    {
        return $this->hasMany(SpecificAppliance::class);
    }
}
