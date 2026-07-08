<?php

namespace App\Settings;

use Illuminate\Support\Facades\Storage;
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

    public function logoUrl(): ?string
    {
        return $this->logo_path
            ? Storage::disk(config('filesystems.media_disk'))->url($this->logo_path)
            : null;
    }

    public function faviconUrl(): ?string
    {
        return $this->favicon_path
            ? Storage::disk(config('filesystems.media_disk'))->url($this->favicon_path)
            : null;
    }
}
