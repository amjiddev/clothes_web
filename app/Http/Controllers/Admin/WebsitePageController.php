<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsitePageController extends Controller
{
    /**
     * Home Page Management
     */
    public function home()
    {
        $pageTitle = 'Home Page';
        $pageDescription = 'Manage the home page content including hero section, featured products, and banner';
        $sections = [
            'hero_section',
            'featured_collection',
            'new_arrivals',
            'best_sellers',
            'testimonials',
            'newsletter_section'
        ];

        return view('admin.website-management.home', compact(
            'pageTitle',
            'pageDescription',
            'sections'
        ));
    }

    /**
     * Shop Page Management
     */
    public function shop()
    {
        $pageTitle = 'Shop Page';
        $pageDescription = 'Manage the shop page including product display, filters, and sorting options';
        $sections = [
            'page_header',
            'filters_section',
            'products_grid',
            'pagination',
            'sidebar'
        ];

        return view('admin.website-management.shop', compact(
            'pageTitle',
            'pageDescription',
            'sections'
        ));
    }

    /**
     * Categories Page Management
     */
    public function categories()
    {
        $pageTitle = 'Categories Page';
        $pageDescription = 'Manage the categories page and category display options';
        $sections = [
            'page_header',
            'categories_grid',
            'featured_categories',
            'category_descriptions'
        ];

        return view('admin.website-management.categories', compact(
            'pageTitle',
            'pageDescription',
            'sections'
        ));
    }

    /**
     * Tailoring Service Page Management
     */
    public function tailoringService()
    {
        $pageTitle = 'Tailoring Service Page';
        $pageDescription = 'Manage the tailoring services page including service descriptions and pricing';
        $sections = [
            'service_type_1',
            'service_type_2',
            'service_type_3',
            'pricing_table',
            'process_timeline',
            'testimonials'
        ];

        return view('admin.website-management.tailoring-service', compact(
            'pageTitle',
            'pageDescription',
            'sections'
        ));
    }

    /**
     * About Us Page Management
     */
    public function aboutUs()
    {
        $pageTitle = 'About Us Page';
        $pageDescription = 'Manage the about us page content including company story and team information';
        $sections = [
            'company_story',
            'mission_vision',
            'team_members',
            'achievements',
            'values'
        ];

        return view('admin.website-management.about-us', compact(
            'pageTitle',
            'pageDescription',
            'sections'
        ));
    }

    /**
     * Contact Page Management
     */
    public function contact()
    {
        $pageTitle = 'Contact Page';
        $pageDescription = 'Manage the contact page including contact information and contact form';
        $sections = [
            'page_header',
            'contact_information',
            'contact_form',
            'map_section',
            'social_links'
        ];

        return view('admin.website-management.contact', compact(
            'pageTitle',
            'pageDescription',
            'sections'
        ));
    }
}
