<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainerController extends Controller
{
    /**
     * Display the trainers page.
     */
    public function page()
    {
        $user = auth()->user();
        return view('trainers', compact('user'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainers = Trainer::all();
        return response()->json($trainers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email',
            'phone' => 'required|string|max:20',
            'experience' => 'required|integer|min:0',
            'qualifications' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/trainers'), $imageName);
            $data['image'] = 'images/trainers/' . $imageName;
        }

        $trainer = Trainer::create($data);
        return response()->json($trainer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $trainer = Trainer::findOrFail($id);
        return response()->json($trainer);
    }

    /**
     * Update the specified resource in storage.
     * Role-based field restrictions apply.
     */
    public function update(Request $request, string $id)
    {
        $trainer = Trainer::findOrFail($id);
        $user = auth()->user();
        $role = $user->role;

        // Define allowed fields per role
        $allowedFields = $this->getAllowedFields($role);

        // Filter request data to only allowed fields
        $data = $request->only($allowedFields);

        // Trainers can only update their own record
        if ($role === 'trainer' && $user->trainer_id !== $trainer->id) {
            return response()->json(['error' => 'You can only update your own profile'], 403);
        }

        // Validate only the fields being updated
        $rules = $this->getValidationRules($allowedFields, $id);
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($trainer->image && file_exists(public_path($trainer->image))) {
                unlink(public_path($trainer->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/trainers'), $imageName);
            $data['image'] = 'images/trainers/' . $imageName;
        }

        $trainer->update($data);
        return response()->json([
            'message' => 'Trainer updated successfully',
            'trainer' => $trainer
        ]);
    }

    /**
     * Get allowed fields based on user role
     */
    private function getAllowedFields($role)
    {
        $fields = [
            'admin' => [
                'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth',
                'nationality', 'id_number', 'email', 'phone', 'whatsapp',
                'emergency_contact', 'emergency_phone', 'country', 'region', 'district',
                'sub_county', 'village', 'postal_code', 'specialty', 'experience',
                'license_number', 'hourly_rate', 'daily_rate', 'status', 'skills',
                'qualifications', 'certifications', 'languages', 'notes', 'image'
            ],
            'trainer' => [
                'phone', 'whatsapp', 'emergency_contact', 'emergency_phone',
                'village', 'postal_code', 'skills', 'qualifications',
                'certifications', 'languages', 'notes', 'image'
            ]
        ];

        return $fields[$role] ?? [];
    }

    /**
     * Get validation rules for allowed fields
     */
    private function getValidationRules($allowedFields, $trainerId)
    {
        $allRules = [
            'first_name' => 'sometimes|required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|in:Male,Female',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'email' => 'sometimes|required|email|unique:trainers,email,' . $trainerId,
            'phone' => 'sometimes|required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'country' => 'sometimes|required|string|max:255',
            'region' => 'sometimes|required|string|max:255',
            'district' => 'sometimes|required|string|max:255',
            'sub_county' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'specialty' => 'sometimes|required|string|max:255',
            'experience' => 'sometimes|required|integer|min:0',
            'license_number' => 'nullable|string|max:255',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:Active,Inactive,On Leave',
            'skills' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'certifications' => 'nullable|string',
            'languages' => 'nullable|string',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        return array_intersect_key($allRules, array_flip($allowedFields));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $trainer = Trainer::findOrFail($id);

        if ($trainer->image && file_exists(public_path($trainer->image))) {
            unlink(public_path($trainer->image));
        }

        $trainer->delete();
        return response()->json(['message' => 'Trainer deleted successfully']);
    }

    public function statistics()
    {
        $trainers = Trainer::all();
        return response()->json([
            'total_trainers' => $trainers->count(),
            'total_trainings' => $trainers->sum('trainings_count'),
            'total_students' => $trainers->sum('students_count'),
            'total_sessions' => $trainers->sum('sessions_count'),
            'average_rating' => $trainers->avg('rating'),
        ]);
    }
}
