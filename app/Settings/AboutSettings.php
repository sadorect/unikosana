<?php

namespace App\Settings;

use Illuminate\Support\Facades\Storage;
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

    public function orgStructureImageUrl(): ?string
    {
        return $this->org_structure_image_path
            ? Storage::disk(config('filesystems.media_disk'))->url($this->org_structure_image_path)
            : null;
    }

    public function constitutionPdfUrl(): ?string
    {
        return $this->constitution_pdf_path
            ? Storage::disk(config('filesystems.media_disk'))->url($this->constitution_pdf_path)
            : null;
    }
}
