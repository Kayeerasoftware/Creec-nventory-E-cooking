<?php

namespace App\Http\Controllers;

use App\Models\Appliance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplianceController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            $appliance = Appliance::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'brand_id' => 'nullable|exists:brands,id',
                'model' => 'nullable|string|max:255',
                'power' => 'nullable|string|max:100',
                'status' => 'nullable|in:Available,In Use,Maintenance,Discontinued',
                'price' => 'nullable|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = array_filter($validated, fn($value) => !is_null($value) && $value !== '');

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
            return response()->json([
                'success' => false, 
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Appliance update error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to update appliance'
            ], 500);
        }
    }
}
