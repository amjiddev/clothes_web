<?php

namespace App\Http\Controllers\Tailor;

use App\Http\Controllers\Controller;
use App\Models\CustomerMeasurement;
use App\Models\StitchingOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    /**
     * Display measurements for orders assigned to tailor
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all measurements related to stitching orders assigned to this tailor
        $query = CustomerMeasurement::whereHas('stitchingOrders', function ($q) use ($user) {
            $q->where('tailor_id', $user->id);
        })->with(['customer', 'stitchingOrders']);

        // Search by customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by measurement type
        if ($request->has('type') && $request->type !== '') {
            $query->where('measurement_type', $request->type);
        }

        $measurements = $query->paginate(15)->appends($request->query());

        $measurementTypes = [
            'custom' => 'Custom',
            'standard' => 'Standard',
            'ready_made' => 'Ready Made',
        ];

        return view('tailor.measurements.index', [
            'measurements' => $measurements,
            'measurementTypes' => $measurementTypes,
            'currentType' => $request->get('type'),
            'searchQuery' => $request->get('search'),
        ]);
    }

    /**
     * Display a specific measurement with details
     */
    public function show($id)
    {
        $user = Auth::user();

        $measurement = CustomerMeasurement::where('id', $id)
            ->whereHas('stitchingOrders', function ($q) use ($user) {
                $q->where('tailor_id', $user->id);
            })
            ->with(['customer', 'stitchingOrders'])
            ->firstOrFail();

        return view('tailor.measurements.show', ['measurement' => $measurement]);
    }
}
