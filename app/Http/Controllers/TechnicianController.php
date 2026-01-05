<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Display the technicians page.
     */
    public function page()
    {
        $user = auth()->user();
        return view('technicians', compact('user'));
    }

    /**
     * Display a listing of all technicians.
     */
    public function index()
    {
        $technicians = Technician::all();
        return response()->json($technicians);
    }

    /**
     * Store a newly created technician in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'email' => 'required|email|unique:technicians,email',
            'phone' => 'required|string|max:255',
            'license' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'rate' => 'nullable|numeric|min:0',
            'skills' => 'nullable|array',
            'certifications' => 'nullable|array',
            'image' => 'nullable|string|max:255',
        ]);

        $technician = Technician::create($validated);
        return response()->json($technician, 201);
    }

    /**
     * Display the specified technician.
     */
    public function show(Technician $technician)
    {
        return response()->json($technician);
    }

    /**
     * Update the specified technician in storage.
     * Role-based field restrictions apply.
     */
    public function update(Request $request, Technician $technician)
    {
        $user = auth()->guard('web')->user() ?? auth()->guard('technician')->user() ?? auth()->guard('trainer')->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $role = $user->role ?? 'technician';
        $allowedFields = $this->getAllowedFields($role);
        $validated = $request->validate($this->getValidationRules($allowedFields, $technician->id));

        if ($role === 'technician' && (!isset($user->technician_id) || $user->id !== $technician->id)) {
            return response()->json(['success' => false, 'message' => 'You can only update your own profile'], 403);
        }

        $technician->update($validated);
        return response()->json(['success' => true, 'message' => 'Technician updated successfully', 'technician' => $technician]);
    }

    /**
     * Get allowed fields based on user role
     */
    private function getAllowedFields($role)
    {
        $fields = [
            'admin' => [
                'title', 'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth',
                'nationality', 'id_number', 'email', 'phone_1', 'phone_2', 'whatsapp',
                'emergency_contact', 'emergency_phone', 'country', 'region', 'district',
                'sub_county', 'parish', 'village', 'postal_code', 'specialty', 'sub_specialty',
                'license_number', 'license_expiry', 'experience', 'hourly_rate', 'daily_rate',
                'status', 'employment_type', 'start_date', 'skills', 'certifications',
                'training', 'languages', 'own_tools', 'has_vehicle', 'vehicle_type',
                'equipment_list', 'service_areas', 'previous_employer', 'previous_position',
                'years_at_previous', 'reference_name', 'reference_phone', 'notes',
                'medical_conditions'
            ],
            'trainer' => [
                'phone_1', 'phone_2', 'whatsapp', 'emergency_contact', 'emergency_phone',
                'village', 'postal_code', 'skills', 'certifications', 'training',
                'languages', 'equipment_list', 'service_areas', 'notes'
            ],
            'technician' => [
                'phone_1', 'phone_2', 'whatsapp', 'emergency_contact', 'emergency_phone',
                'village', 'postal_code', 'skills', 'certifications', 'training'
            ]
        ];

        return $fields[$role] ?? [];
    }

    /**
     * Get validation rules for allowed fields
     */
    private function getValidationRules($allowedFields, $technicianId)
    {
        $allRules = [
            'title' => 'nullable|string|max:10',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:technicians,email,' . $technicianId,
            'phone_1' => 'nullable|string|max:255',
            'phone_2' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'sub_county' => 'nullable|string|max:255',
            'parish' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'sub_specialty' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'license_expiry' => 'nullable|date',
            'experience' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:Available,Busy,Unavailable,On Leave',
            'employment_type' => 'nullable|in:Full-Time,Part-Time,Contract,Freelance',
            'start_date' => 'nullable|date',
            'skills' => 'nullable|string',
            'certifications' => 'nullable|string',
            'training' => 'nullable|string',
            'languages' => 'nullable|string',
            'own_tools' => 'nullable|in:Yes,No,Partial',
            'has_vehicle' => 'nullable|in:Yes,No',
            'vehicle_type' => 'nullable|string|max:255',
            'equipment_list' => 'nullable|string',
            'service_areas' => 'nullable|string',
            'previous_employer' => 'nullable|string|max:255',
            'previous_position' => 'nullable|string|max:255',
            'years_at_previous' => 'nullable|numeric|min:0',
            'reference_name' => 'nullable|string|max:255',
            'reference_phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
        ];

        return array_intersect_key($allRules, array_flip($allowedFields));
    }

    /**
     * Remove the specified technician from storage.
     */
    public function destroy(Technician $technician)
    {
        $technician->delete();
        return response()->json(null, 204);
    }

    /**
     * Get technician statistics.
     */
    public function statistics()
    {
        $technicians = Technician::all();
        return response()->json([
            'total_technicians' => $technicians->count(),
            'available' => $technicians->where('status', 'Available')->count(),
            'busy' => $technicians->where('status', 'Busy')->count(),
            'unavailable' => $technicians->where('status', 'Unavailable')->count(),
            'on_leave' => $technicians->where('status', 'On Leave')->count(),
        ]);
    }

    /**
     * Show technician profile page
     */
    public function profile()
    {
        return view('technician-profile');
    }

    /**
     * Update technician profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $technician = auth()->guard('technician')->user();
            
            if (!$technician) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:technicians,email,' . $technician->id,
                'phone' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'cohort_number' => 'nullable|string|max:255',
                'place_of_work' => 'nullable|string|max:255',
                'venue' => 'nullable|string|max:255',
                'training_dates' => 'nullable|string|max:255',
                'age' => 'nullable|integer',
                'nationality' => 'nullable|string|max:255',
                'specialty' => 'nullable|string|max:255',
                'password' => 'nullable|min:6|confirmed',
                'profile_photo' => 'nullable|image|max:2048'
            ]);

            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profiles', 'public');
                $validated['image'] = $path;
            }
            unset($validated['profile_photo']);

            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $technician->update($validated);

            return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
