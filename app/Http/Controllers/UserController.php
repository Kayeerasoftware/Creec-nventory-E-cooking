<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Part;
use App\Models\Appliance;
use App\Models\Brand;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index()
    {
        try {
            $user = auth()->user() ?? (object)['name' => 'Guest', 'email' => 'guest@example.com', 'role' => 'guest', 'profile_picture' => null];
            $userRole = $user->role ?? 'guest';
            
            // Load profile picture
            if (auth()->check()) {
                if ($user->technician_id) {
                    $tech = Technician::find($user->technician_id);
                    $user->profile_picture = $tech ? ($tech->profile_photo ?? $tech->image) : null;
                } elseif ($user->trainer_id) {
                    $trainer = \App\Models\Trainer::find($user->trainer_id);
                    $user->profile_picture = $trainer ? $trainer->image : null;
                }
            }
            
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

            return view('users', compact('appliances', 'brands', 'parts', 'statistics', 'chartData', 'overviewStats', 'technicians', 'technicianStatistics', 'userRole', 'user'));
        } catch (\Exception $e) {
            \Log::error('Error in UserController@index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error loading page: ' . $e->getMessage());
        }
    }

    public function apiIndex()
    {
        try {
            $users = User::select('id', 'name', 'email', 'role', 'created_at')->get();
            return response()->json([
                'success' => true,
                'data' => $users,
                'count' => $users->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load users',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Only admins can create users');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|in:admin,manager,user,trainer,technician',
                'trainer_id' => 'nullable|exists:trainers,id',
                'technician_id' => 'nullable|exists:technicians,id'
            ]);

            $validated['password'] = Hash::make($validated['password']);
            
            $user = User::create($validated);
            
            return response()->json($user, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized access');
        }
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        try {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Only admins can update users');
            }

            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'role' => 'required|in:admin,manager,user,trainer,technician',
                'password' => 'nullable|string|min:6',
                'trainer_id' => 'nullable|exists:trainers,id',
                'technician_id' => 'nullable|exists:technicians,id'
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);
            
            return response()->json($user);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admins can delete users');
        }

        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Cannot delete yourself'], 400);
        }

        $user->delete();
        
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function showProfile()
    {
        return view('profile');
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate(['profile_picture' => 'required|image|max:2048']);
        
        // Detect which guard is authenticated
        if (auth('trainer')->check()) {
            $user = auth('trainer')->user();
        } elseif (auth('technician')->check()) {
            $user = auth('technician')->user();
        } else {
            $user = auth()->user();
        }
        
        if (!$user) {
            return redirect('/login')->with('error', 'Please login first');
        }
        
        if ($user->profile_picture) {
            \Storage::delete('public/' . $user->profile_picture);
        }
        
        $path = $request->file('profile_picture')->store('profiles', 'public');
        $user->update(['profile_picture' => $path]);
        
        return redirect('/profile')->with('success', 'Profile picture updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        // Detect which guard is authenticated
        if (auth('trainer')->check()) {
            $user = auth('trainer')->user();
        } elseif (auth('technician')->check()) {
            $user = auth('technician')->user();
        } else {
            $user = auth()->user();
        }
        
        if (!$user) {
            return redirect('/login')->with('error', 'Please login first');
        }

        // Get all fillable fields from the model
        $fillable = $user->getFillable();
        $data = [];
        
        // Only update fields that are in the request and fillable
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $fillable) && $key !== '_token' && $value !== null && $value !== '') {
                $data[$key] = $value;
            }
        }

        // Handle password update if provided
        if ($request->filled('password')) {
            if ($request->password === $request->password_confirmation) {
                $data['password'] = \Hash::make($request->password);
            } else {
                return redirect('/profile')->with('error', 'Passwords do not match!');
            }
        }

        $user->update($data);
        
        return redirect('/profile')->with('success', 'Profile updated successfully!');
    }
}
