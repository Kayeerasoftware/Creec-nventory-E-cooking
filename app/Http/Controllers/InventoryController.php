<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Appliance;
use App\Models\Brand;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index()
    {
        $appliances = Appliance::all();
        $brands = Brand::all();
        $parts = Part::with(['appliance', 'brands', 'specificAppliances'])->get();
        $technicians = Technician::all();
        $trainers = \App\Models\Trainer::all();

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

        $trainerStatistics = [
            'total' => $trainers->count(),
            'active' => $trainers->where('status', 'Active')->count(),
            'inactive' => $trainers->where('status', 'Inactive')->count(),
            'on_leave' => $trainers->where('status', 'On Leave')->count(),
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

        $view = 'welcome';
        if (auth('technician')->check()) {
            if (request()->is('technicians')) {
                $view = 'technician';
            } elseif (request()->is('technician/home')) {
                $view = 'technician-home';
            }
        } elseif (auth('trainer')->check()) {
            if (request()->is('trainers')) {
                $view = 'trainer';
            } elseif (request()->is('trainer/home')) {
                $view = 'trainer-home';
            }
        } elseif (auth()->check() && auth()->user()->role === 'admin') {
            $view = 'admin';
        }

        return view($view, compact('appliances', 'brands', 'parts', 'statistics', 'chartData', 'overviewStats', 'technicians', 'technicianStatistics', 'trainers', 'trainerStatistics'));
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
                'id' => $part->id,
                'name' => $part->name,
                'image' => $part->image_path ? asset('storage/' . $part->image_path) : null,
                'applianceType' => $part->appliance->name,
                'appliance_id' => $part->appliance_id,
                'badgeClass' => $part->appliance->color ?? 'bg-secondary',
                'location' => $part->location ?? 'Not specified',
                'brands' => $part->brands->pluck('name')->toArray(),
                'appliances' => $part->specificAppliances->pluck('name')->toArray(),
                'description' => $part->description ?? 'No description',
                'availability' => $part->availability,
                'comments' => $part->comments ?? '',
                'price' => $part->price ?? 0,
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
                'brand_id' => $appliance->brand_id,
                'model' => $appliance->model,
                'power' => $appliance->power,
                'sku' => $appliance->sku,
                'status' => $appliance->status,
                'description' => $appliance->description,
                'icon' => $appliance->icon,
                'color' => $appliance->color,
                'price' => $appliance->price,
                'quantity' => $appliance->quantity,
                'image' => $appliance->image,
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

    // Part CRUD methods
    public function storePart(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'part_number' => 'required|string|unique:parts',
            'name' => 'required|string',
            'appliance_id' => 'nullable|exists:appliances,id',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'availability' => 'nullable|boolean',
            'comments' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'brands' => 'nullable|array',
            'brands.*' => 'exists:brands,id',
            'specific_appliances' => 'nullable|array',
            'specific_appliances.*' => 'exists:specific_appliances,id',
        ]);

        // Set defaults for required fields
        if (!isset($validated['location'])) $validated['location'] = '';
        if (!isset($validated['description'])) $validated['description'] = '';
        if (!isset($validated['availability'])) $validated['availability'] = 0;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('parts', 'public');
        }

        $brands = $validated['brands'] ?? [];
        $specificAppliances = $validated['specific_appliances'] ?? [];
        unset($validated['brands'], $validated['specific_appliances']);

        $part = Part::create($validated);

        if (!empty($brands)) {
            $part->brands()->attach($brands);
        }

        if (!empty($specificAppliances)) {
            $part->specificAppliances()->attach($specificAppliances);
        }

        return response()->json($part->load(['appliance', 'brands', 'specificAppliances']), 201);
    }

    public function updatePart(Request $request, $id): JsonResponse
    {
        $part = Part::findOrFail($id);

        $validated = $request->validate([
            'part_number' => 'required|string|unique:parts,part_number,' . $id,
            'name' => 'required|string',
            'appliance_id' => 'nullable|exists:appliances,id',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'availability' => 'nullable|boolean',
            'comments' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'brands' => 'nullable|array',
            'brands.*' => 'exists:brands,id',
            'specific_appliances' => 'nullable|array',
            'specific_appliances.*' => 'exists:specific_appliances,id',
        ]);

        // Keep existing price if not provided
        if (!isset($validated['price'])) {
            $validated['price'] = $part->price;
        }

        if ($request->hasFile('image')) {
            if ($part->image_path) {
                \Storage::disk('public')->delete($part->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('parts', 'public');
        }

        $brands = $validated['brands'] ?? null;
        $specificAppliances = $validated['specific_appliances'] ?? null;
        unset($validated['brands'], $validated['specific_appliances']);

        $part->update($validated);

        if ($brands !== null) {
            $part->brands()->sync($brands);
        }

        if ($specificAppliances !== null) {
            $part->specificAppliances()->sync($specificAppliances);
        }

        return response()->json($part->load(['appliance', 'brands', 'specificAppliances']));
    }

    public function deletePart($id): JsonResponse
    {
        $part = Part::findOrFail($id);
        $part->delete();

        return response()->json(['message' => 'Part deleted successfully']);
    }

    public function showPart($id): JsonResponse
    {
        $part = Part::with(['appliance', 'brands', 'specificAppliances'])->findOrFail($id);
        
        return response()->json([
            'id' => $part->id,
            'part_number' => $part->part_number,
            'name' => $part->name,
            'appliance_id' => $part->appliance_id,
            'appliance' => $part->appliance,
            'location' => $part->location,
            'description' => $part->description,
            'availability' => $part->availability,
            'comments' => $part->comments,
            'price' => $part->price,
            'image_path' => $part->image_path,
            'brands' => $part->brands,
            'specificAppliances' => $part->specificAppliances,
        ]);
    }

    // Appliance CRUD methods
    public function storeAppliance(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'brand_id' => 'nullable|exists:brands,id',
                'model' => 'nullable|string|max:255',
                'sku' => 'nullable|string|max:100',
                'status' => 'nullable|in:Available,In Use,Maintenance,Discontinued',
                'description' => 'nullable|string',
                'power' => 'nullable|string|max:100',
                'price' => 'nullable|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = array_filter($validated, fn($value, $key) => 
                !is_null($value) && $value !== '' && $key !== 'image', 
                ARRAY_FILTER_USE_BOTH
            );

            if (!isset($data['name']) || empty($data['name'])) {
                $data['name'] = 'Unnamed Appliance';
            }

            if (!isset($data['status'])) {
                $data['status'] = 'Available';
            }

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('appliances', 'public');
            }

            $appliance = Appliance::create($data);

            return response()->json([
                'success' => true,
                'appliance' => $appliance->load('brand'),
                'message' => 'Appliance created successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating appliance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create appliance'
            ], 500);
        }
    }

    public function updateAppliance(Request $request, $id): JsonResponse
    {
        try {
            $appliance = Appliance::findOrFail($id);

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'brand_id' => 'nullable|exists:brands,id',
                'model' => 'nullable|string|max:255',
                'sku' => 'nullable|string|max:100',
                'status' => 'nullable|in:Available,In Use,Maintenance,Discontinued',
                'description' => 'nullable|string',
                'power' => 'nullable|string|max:100',
                'price' => 'nullable|numeric|min:0',
                'quantity' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = array_filter($validated, fn($value, $key) => 
                !is_null($value) && $value !== '' && $key !== 'image', 
                ARRAY_FILTER_USE_BOTH
            );

            if ($request->hasFile('image')) {
                if ($appliance->image && Storage::disk('public')->exists($appliance->image)) {
                    Storage::disk('public')->delete($appliance->image);
                }
                $data['image'] = $request->file('image')->store('appliances', 'public');
            }

            $appliance->update($data);

            return response()->json([
                'success' => true,
                'appliance' => $appliance->fresh()->load('brand'),
                'message' => 'Appliance updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error updating appliance: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating appliance: ' . $e->getMessage() . ' | Line: ' . $e->getLine() . ' | File: ' . $e->getFile());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAppliance($id): JsonResponse
    {
        $appliance = Appliance::findOrFail($id);
        $appliance->delete();

        return response()->json(['message' => 'Appliance deleted successfully']);
    }

    public function showAppliance($id): JsonResponse
    {
        $appliance = Appliance::with('brand')->findOrFail($id);
        
        return response()->json([
            'id' => $appliance->id,
            'name' => $appliance->name,
            'brand' => $appliance->brand ? $appliance->brand->name : null,
            'brand_id' => $appliance->brand_id,
            'model' => $appliance->model,
            'power' => $appliance->power,
            'sku' => $appliance->sku,
            'status' => $appliance->status,
            'description' => $appliance->description,
            'icon' => $appliance->icon,
            'color' => $appliance->color,
            'price' => $appliance->price,
            'quantity' => $appliance->quantity,
            'created_at' => $appliance->created_at,
            'updated_at' => $appliance->updated_at,
        ]);
    }
}
