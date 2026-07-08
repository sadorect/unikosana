<?php

namespace App\Filament\Pages;

use App\Settings\AboutSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageAbout extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'About Page';

    protected static ?int $navigationSort = 4;

    protected static string $settings = AboutSettings::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Narrative')
                    ->schema([
                        Textarea::make('history')->rows(5),
                        Textarea::make('mission')->rows(3),
                        Textarea::make('vision')->rows(3),
                        Textarea::make('objectives')
                            ->rows(5)
                            ->helperText('One objective per line.'),
                    ]),
                Section::make('Organizational Structure')
                    ->schema([
                        Textarea::make('org_structure')->rows(4),
                        FileUpload::make('org_structure_image_path')
                            ->label('Org chart image')
                            ->image()
                            ->disk(config('filesystems.media_disk'))
                            ->directory('about')
                            ->visibility('public'),
                    ]),
                Section::make('Governing Documents')
                    ->schema([
                        FileUpload::make('constitution_pdf_path')
                            ->label('Constitution (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk(config('filesystems.media_disk'))
                            ->directory('about')
                            ->visibility('public'),
                    ]),
            ]);
    }
}
