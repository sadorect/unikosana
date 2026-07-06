<?php

namespace App\Settings;

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
}
