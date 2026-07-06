<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ThemeSettings extends Settings
{
    public string $site_name;

    public string $primary_color;

    public string $secondary_color;

    public string $accent_color;

    public ?string $logo_path;

    public ?string $favicon_path;

    public static function group(): string
    {
        return 'theme';
    }
}
