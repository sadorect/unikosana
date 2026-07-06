<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AboutSettings extends Settings
{
    public ?string $history;

    public ?string $mission;

    public ?string $vision;

    public ?string $objectives;

    public ?string $org_structure;

    public ?string $org_structure_image_path;

    public ?string $constitution_pdf_path;

    public static function group(): string
    {
        return 'about';
    }
}
