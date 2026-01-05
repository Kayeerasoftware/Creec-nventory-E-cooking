<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Appliance;
use App\Models\Part;
use Illuminate\Http\Request;

class DataPopulateController extends Controller
{
    public function populate()
    {
        // Check if data already exists
        if (Brand::count() > 0 && Appliance::count() > 0 && Part::count() > 0) {
            return response()->json(['message' => 'Data already exists']);
        }

        // Create Brands
        $brands = ['Instant Pot', 'Tefal', 'Saachi', 'Hisense', 'Prestige', 'Philips', 'Ninja', 'Hoffmans', 'Midea', 'SPJ'];
        $brandModels = [];
        foreach ($brands as $brandName) {
            $brandModels[] = Brand::firstOrCreate(['name' => $brandName]);
        }

        // Create Appliances
        $appliances = [
            ['name' => 'Electric Pressure Cooker', 'color' => 'bg-primary', 'icon' => 'fa-kitchen-set', 'model' => 'EPC-5000', 'power' => '1000W', 'sku' => 'EPC-001', 'status' => 'Available'],
            ['name' => 'Air Fryer', 'color' => 'bg-warning', 'icon' => 'fa-fan', 'model' => 'AF-3000', 'power' => '1500W', 'sku' => 'AF-001', 'status' => 'Available'],
            ['name' => 'Induction Cooker', 'color' => 'bg-danger', 'icon' => 'fa-fire-burner', 'model' => 'IC-2000', 'power' => '2000W', 'sku' => 'IC-001', 'status' => 'Available'],
        ];
        
        $applianceModels = [];
        foreach ($appliances as $app) {
            $app['brand_id'] = $brandModels[array_rand($brandModels)]->id;
            $applianceModels[] = Appliance::create($app);
        }

        // Create Parts
        $parts = [
            ['part_number' => 'EPC-LID-001', 'name' => 'Pressure Lid Assembly', 'price' => 45.99, 'availability' => true],
            ['part_number' => 'EPC-SEAL-001', 'name' => 'Silicone Sealing Ring', 'price' => 12.99, 'availability' => true],
            ['part_number' => 'EPC-VALVE-001', 'name' => 'Steam Release Valve', 'price' => 8.99, 'availability' => true],
            ['part_number' => 'AF-BASKET-001', 'name' => 'Frying Basket', 'price' => 25.99, 'availability' => true],
            ['part_number' => 'AF-HEATING-001', 'name' => 'Heating Element', 'price' => 35.99, 'availability' => true],
            ['part_number' => 'IC-COIL-001', 'name' => 'Induction Coil', 'price' => 55.99, 'availability' => true],
            ['part_number' => 'IC-GLASS-001', 'name' => 'Glass Top Panel', 'price' => 42.99, 'availability' => true],
        ];

        foreach ($parts as $part) {
            $part['appliance_id'] = $applianceModels[array_rand($applianceModels)]->id;
            $partModel = Part::create($part);
            $partModel->brands()->attach($brandModels[array_rand($brandModels)]->id);
        }

        return response()->json(['message' => 'Data populated successfully']);
    }
}
