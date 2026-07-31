<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    /**
     * Get setting value
     */
    public static function get($key, $default = null)
    {
        return Setting::getValue($key, $default);
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $type = 'string', $group = 'general')
    {
        return Setting::setValue($key, $value, $type, $group);
    }

    // Shop Settings
    public static function shopName()
    {
        return self::get('shop_name', 'Clothes Store');
    }

    public static function shopEmail()
    {
        return self::get('shop_email', 'info@clothesstore.com');
    }

    public static function shopPhone()
    {
        return self::get('shop_phone', '+1 (555) 000-0000');
    }

    public static function shopAddress()
    {
        return self::get('shop_address', '123 Fashion Street, City, State 12345');
    }

    public static function whatsappNumber()
    {
        return self::get('whatsapp_number', '');
    }

    public static function shopLogo()
    {
        $logo = self::get('shop_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    public static function shopFavicon()
    {
        $favicon = self::get('shop_favicon');
        return $favicon ? asset('storage/' . $favicon) : null;
    }

    // Payment Settings
    public static function paymentCurrency()
    {
        return self::get('payment_currency', 'USD');
    }

    public static function paymentTaxPercentage()
    {
        return self::get('payment_tax_percentage', 0);
    }

    public static function stripeKey()
    {
        return self::get('payment_stripe_key', '');
    }

    public static function stripeSecret()
    {
        return self::get('payment_stripe_secret', '');
    }

    public static function paypalClientId()
    {
        return self::get('payment_paypal_client_id', '');
    }

    public static function paypalSecret()
    {
        return self::get('payment_paypal_secret', '');
    }

    // Email Settings
    public static function emailFromAddress()
    {
        return self::get('email_from_address', 'noreply@clothesstore.com');
    }

    public static function emailFromName()
    {
        return self::get('email_from_name', 'Clothes Store');
    }

    public static function emailNotificationsEnabled()
    {
        return self::get('email_notifications_enabled', false);
    }

    public static function emailOrderNotifications()
    {
        return self::get('email_order_notifications', false);
    }

    public static function emailUserNotifications()
    {
        return self::get('email_user_notifications', false);
    }

    // Website Colors
    public static function primaryColor()
    {
        return self::get('primary_color', '#0d6efd');
    }

    public static function secondaryColor()
    {
        return self::get('secondary_color', '#6c757d');
    }

    public static function accentColor()
    {
        return self::get('accent_color', '#28a745');
    }

    public static function textColor()
    {
        return self::get('text_color', '#1a1a1a');
    }

    public static function backgroundColor()
    {
        return self::get('background_color', '#ffffff');
    }

    /**
     * Get all settings
     */
    public static function all()
    {
        return Setting::all()->keyBy('key');
    }

    /**
     * Get settings by group
     */
    public static function byGroup($group)
    {
        return Setting::getByGroup($group);
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Setting::clearCache();
    }

    /**
     * Get CSS custom properties for colors
     */
    public static function getCssVariables()
    {
        return [
            '--primary-color' => self::primaryColor(),
            '--secondary-color' => self::secondaryColor(),
            '--accent-color' => self::accentColor(),
            '--text-color' => self::textColor(),
            '--background-color' => self::backgroundColor(),
        ];
    }

    /**
     * Generate CSS with custom colors
     */
    public static function generateColorsCss()
    {
        $colors = self::getCssVariables();
        $css = ':root {' . PHP_EOL;
        
        foreach ($colors as $key => $value) {
            $css .= "  $key: $value;" . PHP_EOL;
        }
        
        $css .= '}';
        
        return $css;
    }
}
