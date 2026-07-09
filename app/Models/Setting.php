<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Find setting by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value
     */
    public static function setValue($key, $value, $type = 'string', $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
    }

    /**
     * Get all settings grouped by group
     */
    public static function getGrouped()
    {
        return self::all()->groupBy('group');
    }

    /**
     * Get settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Get shop settings
     */
    public static function getShopSettings()
    {
        return self::where('group', 'shop')->get()->keyBy('key');
    }

    /**
     * Get payment settings
     */
    public static function getPaymentSettings()
    {
        return self::where('group', 'payment')->get()->keyBy('key');
    }

    /**
     * Get email settings
     */
    public static function getEmailSettings()
    {
        return self::where('group', 'email')->get()->keyBy('key');
    }

    /**
     * Get website settings
     */
    public static function getWebsiteSettings()
    {
        return self::where('group', 'website')->get()->keyBy('key');
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl()
    {
        $logo = self::getValue('shop_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Get favicon URL
     */
    public static function getFaviconUrl()
    {
        $favicon = self::getValue('shop_favicon');
        return $favicon ? asset('storage/' . $favicon) : null;
    }

    /**
     * Get shop name
     */
    public static function getShopName()
    {
        return self::getValue('shop_name', 'Clothes Store');
    }

    /**
     * Get shop email
     */
    public static function getShopEmail()
    {
        return self::getValue('shop_email', 'info@clothesstore.com');
    }

    /**
     * Get shop phone
     */
    public static function getShopPhone()
    {
        return self::getValue('shop_phone', '+1 (555) 000-0000');
    }

    /**
     * Get shop address
     */
    public static function getShopAddress()
    {
        return self::getValue('shop_address', '123 Fashion Street, City, State 12345');
    }

    /**
     * Get WhatsApp number
     */
    public static function getWhatsAppNumber()
    {
        return self::getValue('whatsapp_number', '+1 (555) 000-0000');
    }

    /**
     * Get primary color
     */
    public static function getPrimaryColor()
    {
        return self::getValue('primary_color', '#0d6efd');
    }

    /**
     * Get secondary color
     */
    public static function getSecondaryColor()
    {
        return self::getValue('secondary_color', '#6c757d');
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        \Cache::forget('settings');
        \Cache::forget('settings_grouped');
    }
}
