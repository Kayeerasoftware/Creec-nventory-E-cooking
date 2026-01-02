<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Appliance;
use App\Models\Brand;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function index()
    {
        $appliances = Appliance::all();
        $brands = Brand::all();
        $parts = Part::with(['appliance', 'brands', 'specificAppliances'])->get();
        $technicians = Technician::all();

        $statistics = [
            'total_parts' => $parts->count(),
            'available_parts' => $parts->where('availability', true)->count(),
            'epc_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Electric Pressure Cooker';
            })->count(),
            'air_fryer_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Air Fryer';
            })->count(),
            'induction_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Induction Cooker';
            })->count(),
            'available_epc_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Electric Pressure Cooker' && $part->availability;
            })->count(),
            'available_air_fryer_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Air Fryer' && $part->availability;
            })->count(),
            'available_induction_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Induction Cooker' && $part->availability;
            })->count(),
        ];

        $technicianStatistics = [
            'total' => $technicians->count(),
            'available' => $technicians->where('status', 'Available')->count(),
            'busy' => $technicians->where('status', 'Busy')->count(),
            'unavailable' => $technicians->where('status', 'Unavailable')->count(),
        ];

        $applianceCounts = [];
        foreach ($appliances as $appliance) {
            $applianceCounts[$appliance->name] = $parts->filter(function ($part) use ($appliance) {
                return $part->appliance && $part->appliance->id === $appliance->id;
            })->count();
        }

        $brandCounts = [];
        foreach ($brands as $brand) {
            $brandCounts[$brand->name] = $parts->filter(function ($part) use ($brand) {
                return $part->brands->contains('id', $brand->id);
            })->count();
        }

        $availabilityByAppliance = [];
        foreach ($appliances as $appliance) {
            $applianceParts = $parts->filter(function ($part) use ($appliance) {
                return $part->appliance && $part->appliance->id === $appliance->id;
            });
            $availabilityByAppliance[$appliance->name] = [
                'Available' => $applianceParts->where('availability', true)->count(),
                'Not Available' => $applianceParts->where('availability', false)->count(),
            ];
        }

        $availabilityByBrand = [];
        foreach ($brands as $brand) {
            $brandParts = $parts->filter(function ($part) use ($brand) {
                return $part->brands->contains('id', $brand->id);
            });
            $availabilityByBrand[$brand->name] = [
                'Available' => $brandParts->where('availability', true)->count(),
                'Not Available' => $brandParts->where('availability', false)->count(),
            ];
        }

        $chartData = [
            'appliances' => $applianceCounts,
            'brands' => $brandCounts,
            'availability' => [
                'Available' => $parts->where('availability', true)->count(),
                'Not Available' => $parts->where('availability', false)->count(),
            ],
            'availabilityByAppliance' => $availabilityByAppliance,
            'availabilityByBrand' => $availabilityByBrand,
        ];

        $overviewStats = [
            'total_brands' => $brands->count(),
            'total_appliances' => $appliances->count(),
            'out_of_stock' => $parts->where('availability', false)->count(),
            'stock_percentage' => $parts->count() > 0 ? round(($parts->where('availability', true)->count() / $parts->count()) * 100) : 0,
        ];

        return view('welcome', compact('appliances', 'brands', 'parts', 'statistics', 'chartData', 'overviewStats', 'technicians', 'technicianStatistics'));
    }

    public function apiParts(Request $request): JsonResponse
    {
        $query = Part::with(['appliance', 'brands', 'specificAppliances']);

        if ($request->has('appliance') && $request->appliance) {
            $query->whereHas('appliance', function ($q) use ($request) {
                $q->where('name', $request->appliance);
            });
        }

        if ($request->has('brand') && $request->brand) {
            $query->whereHas('brands', function ($q) use ($request) {
                $q->where('name', $request->brand);
            });
        }

        if ($request->has('availability') && $request->availability !== '') {
            $query->where('availability', $request->boolean('availability'));
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('part_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('brands', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('specificAppliances', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $parts = $query->get();

        return response()->json($parts->map(function ($part) {
            return [
                'partNumber' => $part->part_number,
                'name' => $part->name,
                'image' => $part->image_path ? asset('storage/' . $part->image_path) : null,
                'applianceType' => $part->appliance->name,
                'badgeClass' => $part->appliance->color ?? 'bg-secondary',
                'location' => $part->location,
                'brands' => $part->brands->pluck('name')->toArray(),
                'appliances' => $part->specificAppliances->pluck('name')->toArray(),
                'description' => $part->description,
                'availability' => $part->availability,
                'comments' => $part->comments,
            ];
        }));
    }

    public function apiAppliances(Request $request): JsonResponse
    {
        $appliances = Appliance::with('brand')->get();

        return response()->json($appliances->map(function ($appliance) {
            return [
                'id' => $appliance->id,
                'name' => $appliance->name,
                'brand' => $appliance->brand ? $appliance->brand->name : null,
                'model' => $appliance->model,
                'power' => $appliance->power,
                'sku' => $appliance->sku,
                'status' => $appliance->status,
                'description' => $appliance->description,
                'icon' => $appliance->icon,
                'color' => $appliance->color,
                'price' => $appliance->price,
                'created_at' => $appliance->created_at,
                'updated_at' => $appliance->updated_at,
            ];
        }));
    }

    public function apiStatistics(): JsonResponse
    {
        $appliances = Appliance::all();
        $brands = Brand::all();
        $parts = Part::with(['appliance', 'brands', 'specificAppliances'])->get();

        $statistics = [
            'total_parts' => $parts->count(),
            'available_parts' => $parts->where('availability', true)->count(),
            'epc_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Electric Pressure Cooker';
            })->count(),
            'air_fryer_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Air Fryer';
            })->count(),
            'induction_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Induction Cooker';
            })->count(),
            'available_epc_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Electric Pressure Cooker' && $part->availability;
            })->count(),
            'available_air_fryer_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Air Fryer' && $part->availability;
            })->count(),
            'available_induction_parts' => $parts->filter(function ($part) {
                return $part->appliance && $part->appliance->name === 'Induction Cooker' && $part->availability;
            })->count(),
        ];

        $overviewStats = [
            'total_brands' => $brands->count(),
            'total_appliances' => $appliances->count(),
            'out_of_stock' => $parts->where('availability', false)->count(),
            'stock_percentage' => $parts->count() > 0 ? round(($parts->where('availability', true)->count() / $parts->count()) * 100) : 0,
        ];

        return response()->json([
            'statistics' => $statistics,
            'overviewStats' => $overviewStats,
        ]);
    }
}
