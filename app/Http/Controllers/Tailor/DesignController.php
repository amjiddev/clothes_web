<?php

namespace App\Http\Controllers\Tailor;

use App\Http\Controllers\Controller;
use App\Models\StitchingOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    /**
     * Display design gallery for assigned orders
     */
    public function gallery(Request $request)
    {
        $user = Auth::user();

        // Get all stitching orders with design images
        $query = StitchingOrder::where('tailor_id', $user->id)
            ->whereNotNull('design_image')
            ->with(['order.customer', 'measurement']);

        // Filter by garment type
        if ($request->has('garment_type') && $request->garment_type !== '') {
            $query->where('garment_type', $request->garment_type);
        }

        $orders = $query->paginate(12)->appends($request->query());

        $garmentTypes = [
            'shirt' => 'Shirt',
            'pants' => 'Pants',
            'kurta' => 'Kurta',
            'shalwar' => 'Shalwar',
            'dupatta' => 'Dupatta',
            'blazer' => 'Blazer',
            'waistcoat' => 'Waistcoat',
            'sherwani' => 'Sherwani',
            'other' => 'Other',
        ];

        return view('tailor.designs.gallery', [
            'orders' => $orders,
            'garmentTypes' => $garmentTypes,
            'currentGarmentType' => $request->get('garment_type'),
        ]);
    }

    /**
     * Display a specific design with details
     */
    public function show($id)
    {
        $user = Auth::user();

        $order = StitchingOrder::where('tailor_id', $user->id)
            ->with(['order.customer', 'measurement'])
            ->findOrFail($id);

        return view('tailor.designs.show', ['order' => $order]);
    }


}
