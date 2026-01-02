<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainerController extends Controller
{
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
     */
    public function update(Request $request, string $id)
    {
        $trainer = Trainer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $id,
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
            // Delete old image if exists
            if ($trainer->image && file_exists(public_path($trainer->image))) {
                unlink(public_path($trainer->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/trainers'), $imageName);
            $data['image'] = 'images/trainers/' . $imageName;
        }

        $trainer->update($data);
        return response()->json($trainer);
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
