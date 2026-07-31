<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings dashboard
     */
    public function index()
    {
        $shopSettings = Setting::getByGroup('shop');
        $paymentSettings = Setting::getByGroup('payment');
        $emailSettings = Setting::getByGroup('email');
        $websiteSettings = Setting::getByGroup('website');

        $settings = [
            'shop' => $shopSettings->keyBy('key'),
            'payment' => $paymentSettings->keyBy('key'),
            'email' => $emailSettings->keyBy('key'),
            'website' => $websiteSettings->keyBy('key'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update shop settings
     */
    public function updateShop(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_email' => 'required|email',
            'shop_phone' => 'required|string|max:20',
            'shop_address' => 'required|string|max:500',
            'whatsapp_number' => 'nullable|string|max:20',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'shop_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
        ]);

        // Handle logo upload
        if ($request->hasFile('shop_logo')) {
            $logoPath = $request->file('shop_logo')->store('settings/logo', 'public');
            Setting::setValue('shop_logo', $logoPath, 'string', 'shop');
        }

        // Handle favicon upload
        if ($request->hasFile('shop_favicon')) {
            $faviconPath = $request->file('shop_favicon')->store('settings/favicon', 'public');
            Setting::setValue('shop_favicon', $faviconPath, 'string', 'shop');
        }

        // Save other settings
        Setting::setValue('shop_name', $validated['shop_name'], 'string', 'shop');
        Setting::setValue('shop_email', $validated['shop_email'], 'string', 'shop');
        Setting::setValue('shop_phone', $validated['shop_phone'], 'string', 'shop');
        Setting::setValue('shop_address', $validated['shop_address'], 'string', 'shop');
        Setting::setValue('whatsapp_number', $validated['whatsapp_number'] ?? '', 'string', 'shop');

        // Clear cache
        Setting::clearCache();

        return redirect()->back()->with('success', 'Shop settings updated successfully');
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'payment_stripe_key' => 'nullable|string',
            'payment_stripe_secret' => 'nullable|string',
            'payment_paypal_client_id' => 'nullable|string',
            'payment_paypal_secret' => 'nullable|string',
            'payment_methods' => 'nullable|array',
            'payment_tax_percentage' => 'nullable|numeric|min:0|max:100',
            'payment_currency' => 'nullable|string|max:3',
        ]);

        Setting::setValue('payment_stripe_key', $validated['payment_stripe_key'] ?? '', 'string', 'payment');
        Setting::setValue('payment_stripe_secret', $validated['payment_stripe_secret'] ?? '', 'string', 'payment');
        Setting::setValue('payment_paypal_client_id', $validated['payment_paypal_client_id'] ?? '', 'string', 'payment');
        Setting::setValue('payment_paypal_secret', $validated['payment_paypal_secret'] ?? '', 'string', 'payment');
        Setting::setValue('payment_methods', json_encode($validated['payment_methods'] ?? []), 'json', 'payment');
        Setting::setValue('payment_tax_percentage', $validated['payment_tax_percentage'] ?? 0, 'string', 'payment');
        Setting::setValue('payment_currency', $validated['payment_currency'] ?? 'USD', 'string', 'payment');

        Setting::clearCache();

        return redirect()->back()->with('success', 'Payment settings updated successfully');
    }

    /**
     * Update email settings
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'email_from_address' => 'required|email',
            'email_from_name' => 'required|string|max:255',
            'email_smtp_host' => 'nullable|string',
            'email_smtp_port' => 'nullable|integer',
            'email_smtp_username' => 'nullable|string',
            'email_smtp_password' => 'nullable|string',
            'email_smtp_encryption' => 'nullable|in:tls,ssl',
            'email_notifications_enabled' => 'boolean',
            'email_order_notifications' => 'boolean',
            'email_user_notifications' => 'boolean',
        ]);

        Setting::setValue('email_from_address', $validated['email_from_address'], 'string', 'email');
        Setting::setValue('email_from_name', $validated['email_from_name'], 'string', 'email');
        Setting::setValue('email_smtp_host', $validated['email_smtp_host'] ?? '', 'string', 'email');
        Setting::setValue('email_smtp_port', $validated['email_smtp_port'] ?? 587, 'string', 'email');
        Setting::setValue('email_smtp_username', $validated['email_smtp_username'] ?? '', 'string', 'email');
        Setting::setValue('email_smtp_password', $validated['email_smtp_password'] ?? '', 'string', 'email');
        Setting::setValue('email_smtp_encryption', $validated['email_smtp_encryption'] ?? 'tls', 'string', 'email');
        Setting::setValue('email_notifications_enabled', $validated['email_notifications_enabled'] ? '1' : '0', 'boolean', 'email');
        Setting::setValue('email_order_notifications', $validated['email_order_notifications'] ? '1' : '0', 'boolean', 'email');
        Setting::setValue('email_user_notifications', $validated['email_user_notifications'] ? '1' : '0', 'boolean', 'email');

        Setting::clearCache();

        return redirect()->back()->with('success', 'Email settings updated successfully');
    }

    /**
     * Update website colors
     */
    public function updateColors(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Setting::setValue('primary_color', $validated['primary_color'], 'string', 'website');
        Setting::setValue('secondary_color', $validated['secondary_color'], 'string', 'website');
        Setting::setValue('accent_color', $validated['accent_color'] ?? '#28a745', 'string', 'website');
        Setting::setValue('text_color', $validated['text_color'] ?? '#1a1a1a', 'string', 'website');
        Setting::setValue('background_color', $validated['background_color'] ?? '#ffffff', 'string', 'website');

        Setting::clearCache();

        return redirect()->back()->with('success', 'Website colors updated successfully');
    }

    /**
     * Show individual settings tab via AJAX
     */
    public function getTab($tab)
    {
        $settings = [];

        switch ($tab) {
            case 'shop':
                $settings = Setting::getByGroup('shop')->keyBy('key');
                break;
            case 'payment':
                $settings = Setting::getByGroup('payment')->keyBy('key');
                break;
            case 'email':
                $settings = Setting::getByGroup('email')->keyBy('key');
                break;
            case 'website':
                $settings = Setting::getByGroup('website')->keyBy('key');
                break;
        }

        return response()->json(['settings' => $settings]);
    }
}
