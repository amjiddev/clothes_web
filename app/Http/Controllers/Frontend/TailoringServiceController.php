<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomerMeasurement;
use App\Models\StitchingOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TailoringServiceController extends Controller
{
    /**
     * Show the tailoring service page
     */
    public function show()
    {
        $userMeasurements = collect();
        if (Auth::check()) {
            $userMeasurements = Auth::user()->measurements;
        }

        return view('frontend.tailoring-service', compact('userMeasurements'));
    }

    /**
     * Store the tailoring service request
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to request tailoring service');
        }

        // Validate the request
        $validated = $request->validate([
            'service_option' => 'required|in:cloth_only,cloth_stitching,stitching_only',
            'garment_type' => 'required|string|max:255',
            
            // Measurement fields
            'chest' => 'required|numeric|min:20|max:200',
            'shoulder' => 'required|numeric|min:20|max:100',
            'sleeve_length' => 'required|numeric|min:10|max:100',
            'shirt_length' => 'required|numeric|min:40|max:150',
            'neck' => 'required|numeric|min:10|max:50',
            'waist' => 'required|numeric|min:20|max:200',
            'trouser_length' => 'required|numeric|min:60|max:120',
            'bottom' => 'required|numeric|min:10|max:50',
            
            // Other fields
            'special_instructions' => 'nullable|string|max:500',
            'design_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'measurement_title' => 'nullable|string|max:255',
        ]);

        try {
            // Store or use measurements
            $measurement = new CustomerMeasurement();
            $measurement->user_id = Auth::id();
            $measurement->title = $validated['measurement_title'] ?? 'Tailoring Service - ' . now()->format('Y-m-d');
            $measurement->chest = $validated['chest'];
            $measurement->shoulder = $validated['shoulder'];
            $measurement->sleeve_length = $validated['sleeve_length'];
            $measurement->torso_length = $validated['shirt_length'];
            $measurement->neck = $validated['neck'];
            $measurement->waist = $validated['waist'];
            $measurement->inseam = $validated['trouser_length'];
            $measurement->notes = "Service: {$validated['service_option']} - Garment: {$validated['garment_type']}";
            $measurement->save();

            // Handle design image upload
            $designImagePath = null;
            if ($request->hasFile('design_image')) {
                $designImagePath = $request->file('design_image')->store('designs', 'public');
            }

            // Create or get order
            $order = Order::where('user_id', Auth::id())
                         ->where('status', 'pending')
                         ->first();

            if (!$order) {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . time(),
                    'status' => 'pending',
                    'total_amount' => 0,
                ]);
            }

            // Create stitching order
            $stitchingOrder = StitchingOrder::create([
                'order_id' => $order->id,
                'measurement_id' => $measurement->id,
                'garment_type' => $validated['garment_type'],
                'service_option' => $validated['service_option'],
                'fabric_details' => $request->input('fabric_details'),
                'design_image' => $designImagePath,
                'special_instructions' => $validated['special_instructions'],
                'additional_instructions' => $validated['special_instructions'],
                'estimated_cost' => $this->calculateEstimatedCost($validated['service_option']),
                'stitching_status' => 'pending',
                'service_request_date' => now(),
            ]);

            return redirect()->route('tailoring.success')
                           ->with('success', 'Tailoring service request submitted successfully!')
                           ->with('order_id', $stitchingOrder->id);
        } catch (\Exception $e) {
            return back()->with('error', 'Error submitting tailoring request: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('frontend.tailoring-success');
    }

    /**
     * Calculate estimated cost based on service option
     */
    private function calculateEstimatedCost($serviceOption)
    {
        $costs = [
            'cloth_only' => 500,
            'cloth_stitching' => 1500,
            'stitching_only' => 300,
        ];

        return $costs[$serviceOption] ?? 500;
    }

    /**
     * Get user measurements via AJAX
     */
    public function getMeasurements()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $measurements = Auth::user()->measurements;
        return response()->json($measurements);
    }

    /**
     * Load measurement via AJAX
     */
    public function loadMeasurement($measurementId)
    {
        $measurement = CustomerMeasurement::find($measurementId);

        if (!$measurement || $measurement->user_id !== Auth::id()) {
            return response()->json(['error' => 'Measurement not found'], 404);
        }

        return response()->json([
            'chest' => $measurement->chest,
            'shoulder' => $measurement->shoulder,
            'sleeve_length' => $measurement->sleeve_length,
            'shirt_length' => $measurement->torso_length,
            'neck' => $measurement->neck,
            'waist' => $measurement->waist,
            'trouser_length' => $measurement->inseam,
            'bottom' => 0, // Not stored, user needs to provide
        ]);
    }
}
