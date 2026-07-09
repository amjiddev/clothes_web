<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\CustomerMeasurement;
use App\Models\User;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->user()->isSuperAdmin() || auth()->user()->isReceptionist()) {
            $measurements = CustomerMeasurement::with('user')->latest()->paginate(15);
        } else {
            $measurements = auth()->user()->measurements()->paginate(15);
        }

        return view('admin.measurements.index', compact('measurements'));
    }

    public function create()
    {
        $users = null;
        if (auth()->user()->isSuperAdmin() || auth()->user()->isReceptionist()) {
            $users = User::role('customer')->get();
        }
        return view('admin.measurements.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => auth()->user()->isSuperAdmin() || auth()->user()->isReceptionist() 
                        ? 'required|exists:users,id' 
                        : 'nullable',
            'title' => 'nullable|string|max:255',
            'chest' => 'nullable|numeric|min:0',
            'waist' => 'nullable|numeric|min:0',
            'hips' => 'nullable|numeric|min:0',
            'shoulder' => 'nullable|numeric|min:0',
            'sleeve_length' => 'nullable|numeric|min:0',
            'torso_length' => 'nullable|numeric|min:0',
            'inseam' => 'nullable|numeric|min:0',
            'neck' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        $validated['user_id'] = auth()->user()->isSuperAdmin() || auth()->user()->isReceptionist() 
                               ? $validated['user_id'] 
                               : auth()->id();

        CustomerMeasurement::create($validated);

        return redirect()->route('measurements.index')
                        ->with('success', 'Measurement created successfully.');
    }

    public function show(CustomerMeasurement $measurement)
    {
        if ($measurement->user_id !== auth()->id() && !auth()->user()->isSuperAdmin() && !auth()->user()->isReceptionist()) {
            abort(403);
        }

        $measurement->load('user');
        return view('admin.measurements.show', compact('measurement'));
    }

    public function edit(CustomerMeasurement $measurement)
    {
        if ($measurement->user_id !== auth()->id() && !auth()->user()->isSuperAdmin() && !auth()->user()->isReceptionist()) {
            abort(403);
        }

        return view('admin.measurements.edit', compact('measurement'));
    }

    public function update(Request $request, CustomerMeasurement $measurement)
    {
        if ($measurement->user_id !== auth()->id() && !auth()->user()->isSuperAdmin() && !auth()->user()->isReceptionist()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'chest' => 'nullable|numeric|min:0',
            'waist' => 'nullable|numeric|min:0',
            'hips' => 'nullable|numeric|min:0',
            'shoulder' => 'nullable|numeric|min:0',
            'sleeve_length' => 'nullable|numeric|min:0',
            'torso_length' => 'nullable|numeric|min:0',
            'inseam' => 'nullable|numeric|min:0',
            'neck' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        $measurement->update($validated);

        return redirect()->route('measurements.index')
                        ->with('success', 'Measurement updated successfully.');
    }

    public function destroy(CustomerMeasurement $measurement)
    {
        if ($measurement->user_id !== auth()->id() && !auth()->user()->isSuperAdmin() && !auth()->user()->isReceptionist()) {
            abort(403);
        }

        $measurement->delete();

        return redirect()->route('measurements.index')
                        ->with('success', 'Measurement deleted successfully.');
    }
}
