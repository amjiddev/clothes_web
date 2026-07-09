<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Shop Settings
        Setting::create([
            'key' => 'shop_name',
            'value' => 'Clothes Store',
            'type' => 'string',
            'group' => 'shop',
            'description' => 'Your shop name',
        ]);

        Setting::create([
            'key' => 'shop_email',
            'value' => 'info@clothesstore.com',
            'type' => 'string',
            'group' => 'shop',
            'description' => 'Main contact email',
        ]);

        Setting::create([
            'key' => 'shop_phone',
            'value' => '+1 (555) 123-4567',
            'type' => 'string',
            'group' => 'shop',
            'description' => 'Shop phone number',
        ]);

        Setting::create([
            'key' => 'shop_address',
            'value' => '123 Fashion Street, Style City, SC 12345, USA',
            'type' => 'string',
            'group' => 'shop',
            'description' => 'Physical address',
        ]);

        Setting::create([
            'key' => 'whatsapp_number',
            'value' => '+1 (555) 123-4567',
            'type' => 'string',
            'group' => 'shop',
            'description' => 'WhatsApp number for customer support',
        ]);

        // Payment Settings
        Setting::create([
            'key' => 'payment_currency',
            'value' => 'USD',
            'type' => 'string',
            'group' => 'payment',
            'description' => 'Default currency for transactions',
        ]);

        Setting::create([
            'key' => 'payment_tax_percentage',
            'value' => '0',
            'type' => 'string',
            'group' => 'payment',
            'description' => 'Tax percentage applied to orders',
        ]);

        Setting::create([
            'key' => 'payment_stripe_key',
            'value' => '',
            'type' => 'string',
            'group' => 'payment',
            'description' => 'Stripe publishable key',
        ]);

        Setting::create([
            'key' => 'payment_stripe_secret',
            'value' => '',
            'type' => 'string',
            'group' => 'payment',
            'description' => 'Stripe secret key',
        ]);

        Setting::create([
            'key' => 'payment_paypal_client_id',
            'value' => '',
            'type' => 'string',
            'group' => 'payment',
            'description' => 'PayPal client ID',
        ]);

        Setting::create([
            'key' => 'payment_paypal_secret',
            'value' => '',
            'type' => 'string',
            'group' => 'payment',
            'description' => 'PayPal secret key',
        ]);

        // Email Settings
        Setting::create([
            'key' => 'email_from_address',
            'value' => 'noreply@clothesstore.com',
            'type' => 'string',
            'group' => 'email',
            'description' => 'Email from address for system emails',
        ]);

        Setting::create([
            'key' => 'email_from_name',
            'value' => 'Clothes Store',
            'type' => 'string',
            'group' => 'email',
            'description' => 'Email from name',
        ]);

        Setting::create([
            'key' => 'email_smtp_host',
            'value' => '',
            'type' => 'string',
            'group' => 'email',
            'description' => 'SMTP host',
        ]);

        Setting::create([
            'key' => 'email_smtp_port',
            'value' => '587',
            'type' => 'string',
            'group' => 'email',
            'description' => 'SMTP port',
        ]);

        Setting::create([
            'key' => 'email_smtp_username',
            'value' => '',
            'type' => 'string',
            'group' => 'email',
            'description' => 'SMTP username',
        ]);

        Setting::create([
            'key' => 'email_smtp_password',
            'value' => '',
            'type' => 'string',
            'group' => 'email',
            'description' => 'SMTP password',
        ]);

        Setting::create([
            'key' => 'email_smtp_encryption',
            'value' => 'tls',
            'type' => 'string',
            'group' => 'email',
            'description' => 'SMTP encryption type',
        ]);

        Setting::create([
            'key' => 'email_notifications_enabled',
            'value' => '1',
            'type' => 'boolean',
            'group' => 'email',
            'description' => 'Enable email notifications',
        ]);

        Setting::create([
            'key' => 'email_order_notifications',
            'value' => '1',
            'type' => 'boolean',
            'group' => 'email',
            'description' => 'Send order confirmation emails',
        ]);

        Setting::create([
            'key' => 'email_user_notifications',
            'value' => '1',
            'type' => 'boolean',
            'group' => 'email',
            'description' => 'Send user registration emails',
        ]);

        // Website Colors
        Setting::create([
            'key' => 'primary_color',
            'value' => '#0d6efd',
            'type' => 'string',
            'group' => 'website',
            'description' => 'Primary brand color',
        ]);

        Setting::create([
            'key' => 'secondary_color',
            'value' => '#6c757d',
            'type' => 'string',
            'group' => 'website',
            'description' => 'Secondary brand color',
        ]);

        Setting::create([
            'key' => 'accent_color',
            'value' => '#28a745',
            'type' => 'string',
            'group' => 'website',
            'description' => 'Accent color for success states',
        ]);

        Setting::create([
            'key' => 'text_color',
            'value' => '#1a1a1a',
            'type' => 'string',
            'group' => 'website',
            'description' => 'Main text color',
        ]);

        Setting::create([
            'key' => 'background_color',
            'value' => '#ffffff',
            'type' => 'string',
            'group' => 'website',
            'description' => 'Main background color',
        ]);
    }
}
