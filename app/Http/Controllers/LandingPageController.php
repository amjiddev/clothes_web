<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\City;
use App\Models\Faq;
use App\Models\LandingPage;
use App\Models\OemContent;
use App\Models\Offer;
use App\Models\Product;
use App\Models\RepairService;
use App\Models\RepairServiceSubPage;
use App\Traits\UploadImageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LandingPageController extends Controller
{
    use UploadImageTrait;

    public function index()
    {
        try {


            return view('pages.landing-page.index');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // If user is not authorized
            abort(403, 'You do not have permission to view this page.');
        } catch (\Throwable $e) {
            // Log any other errors
            Log::error('LandingPage index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Redirect back with a friendly error message
            return redirect()->back()->withErrors(['general_error' => 'Unable to load landing page data. Please try again later.']);
        }
    }

    public function landingPage()
    {
        
        return view('frontend.pages.home');
    }
}
