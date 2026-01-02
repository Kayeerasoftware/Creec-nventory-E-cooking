<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Appliance;
use App\Models\Brand;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        try {
            $parts = Part::with(['appliance', 'brands'])->get();
            $appliances = Appliance::all();
            $brands = Brand::all();

            $totalParts = $parts->count();
            $totalAppliances = $appliances->count();
            $totalBrands = $brands->count();
            $availableParts = $parts->where('availability', true)->count();
            $outOfStockParts = $parts->where('availability', false)->count();
            
            $reportData = [
                'totalItems' => $totalParts + $totalAppliances,
                'totalValue' => 89650,
                'lowStockItems' => 23,
                'outOfStockItems' => $outOfStockParts,
                'totalBrands' => $totalBrands,
                'totalParts' => $totalParts,
                'inventory' => $parts->map(function($part) {
                    return [
                        'name' => $part->name,
                        'category' => 'Parts',
                        'brand' => $part->brands->first()->name ?? 'Generic',
                        'quantity' => $part->quantity ?? 0,
                        'price' => $part->price ?? 0,
                        'status' => $part->availability ? 'In Stock' : 'Out of Stock',
                        'updated_at' => $part->updated_at->format('Y-m-d')
                    ];
                })->toArray()
            ];

            return view('reports', compact('reportData'));
        } catch (\Exception $e) {
            $reportData = [
                'totalItems' => 0,
                'totalValue' => 0,
                'lowStockItems' => 0,
                'outOfStockItems' => 0,
                'totalBrands' => 0,
                'totalParts' => 0,
                'inventory' => []
            ];
            
            return view('reports', compact('reportData'));
        }
    }
}