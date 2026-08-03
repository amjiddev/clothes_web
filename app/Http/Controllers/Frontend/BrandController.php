<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display all brands
     */
    public function index()
    {
        $brands = Brand::where('is_active', true)
                      ->orderBy('sort_order')
                      ->get();

        return view('frontend.brands', compact('brands'));
    }


}
