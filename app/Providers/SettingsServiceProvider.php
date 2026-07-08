<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;


class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //  try {
        //     DB::connection()->getPdo(); // ensure DB reachable
        // } catch (\Throwable $e) {
        //     return;
        // }

        // migrate ke time ya console commands mein error na aaye
        if (!Schema::hasTable('general_settings')) {
            return;
        }

        try {
            // Saare settings ek baar mein cache se le lo (super fast)
            $settings = Cache::remember('app_settings_all', 3600, function () {
                return \App\Models\GeneralSetting::pluck('value', 'key')->toArray();
            });

            // Helper function
            $get = fn($key) => $settings[$key] ?? null;

            // === SMTP Settings (Database → Override .env) ===
            if ($host = $get('smtp_host'))          Config::set('mail.mailers.smtp.host', $host);
            if ($port = $get('smtp_port'))          Config::set('mail.mailers.smtp.port', (int)$port);
            if ($username = $get('smtp_username'))  Config::set('mail.mailers.smtp.username', $username);
            if ($password = $get('smtp_password'))  Config::set('mail.mailers.smtp.password', $password);
            if ($encryption = $get('smtp_encryption')) Config::set('mail.mailers.smtp.encryption', $encryption);

            // === From Email ===
            if ($fromEmail = $get('smtp_from_email')) {
                Config::set('mail.from.address', $fromEmail);
            }

            // === From Name & APP_NAME → Sirf site_name use karo ===
            if ($siteName = $get('site_name')) {
                Config::set('mail.from.name', $siteName);   // Email ka "From Name"
                Config::set('app.name', $siteName);         // APP_NAME (title, emails, etc.)
            }
        } catch (\Exception $e) {
            Log::warning('SettingsServiceProvider: Failed to load settings', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
