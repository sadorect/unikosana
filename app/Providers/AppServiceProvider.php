<?php

namespace App\Providers;

use App\Settings\SiteSettings;
use App\Settings\ThemeSettings;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
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
        // Force HTTPS URL generation in production so links, form actions, and
        // redirects never downgrade to http behind a TLS-terminating proxy.
        if ($this->app->environment('production') || str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Make branding + contact settings available to every public view
        // (layout, partials, and page content sections alike).
        try {
            View::share('theme', app(ThemeSettings::class));
            View::share('site', app(SiteSettings::class));
        } catch (\Throwable $e) {
            // Settings table not migrated yet (e.g. during install/migrate) — ignore.
        }

        // Audit trail for authentication: log failed attempts and lockouts so
        // brute-force activity against the admin/member logins is visible.
        Event::listen(Failed::class, function (Failed $event): void {
            Log::channel(config('logging.default'))->warning('Failed login attempt', [
                'guard' => $event->guard,
                'email' => $event->credentials['email'] ?? null,
                'ip' => request()->ip(),
            ]);
        });

        Event::listen(Lockout::class, function (Lockout $event): void {
            Log::channel(config('logging.default'))->warning('Login lockout triggered (rate limit hit)', [
                'ip' => $event->request->ip(),
                'email' => $event->request->input('email'),
            ]);
        });
    }
}
