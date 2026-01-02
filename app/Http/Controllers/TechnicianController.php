<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
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
     */
    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'specialty' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:technicians,email,' . $technician->id,
            'phone' => 'sometimes|required|string|max:255',
            'license' => 'nullable|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'rate' => 'nullable|numeric|min:0',
            'skills' => 'nullable|array',
            'certifications' => 'nullable|array',
            'image' => 'nullable|string|max:255',
        ]);

        $technician->update($validated);
        return response()->json($technician);
    }

    /**
     * Remove the specified technician from storage.
     */
    public function destroy(Technician $technician)
    {
        $technician->delete();
        return response()->json(null, 204);
    }
}
