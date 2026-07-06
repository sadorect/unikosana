<?php

namespace App\Providers;

use App\Settings\SiteSettings;
use App\Settings\ThemeSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Make branding + contact settings available to every public view
        // (layout, partials, and page content sections alike).
        try {
            View::share('theme', app(ThemeSettings::class));
            View::share('site', app(SiteSettings::class));
        } catch (\Throwable $e) {
            // Settings table not migrated yet (e.g. during install/migrate) — ignore.
        }
    }
}
