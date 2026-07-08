<?php

namespace App\Settings;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelSettings\Settings;

class HomeSettings extends Settings
{
    public string $hero_heading;

    public ?string $hero_subheading;

    public ?string $hero_image_path;

    public ?string $intro_text;

    public ?string $mission;

    public ?string $vision;

    public static function group(): string
    {
        return 'home';
    }

    public function heroImageUrl(): ?string
    {
        return $this->hero_image_path
            ? Storage::disk(config('filesystems.media_disk'))->url($this->hero_image_path)
            : null;
    }
}
