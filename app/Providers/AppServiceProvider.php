<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Core\KTBootstrap;
use App\Models\Category;
use App\Models\ServingCity;
use App\Models\State;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Update defaultStringLength
        Builder::defaultStringLength(191);

        Paginator::useBootstrapFive();

        KTBootstrap::init();

        if (app()->environment('production')) {
            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/starterkit/metronic/laravel/livewire/update', $handle);
            });
        }
    }
}
