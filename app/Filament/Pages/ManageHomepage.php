<?php

namespace App\Filament\Pages;

use App\Settings\HomeSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageHomepage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Homepage Content';

    protected static ?int $navigationSort = 3;

    protected static string $settings = HomeSettings::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Hero')
                    ->schema([
                        TextInput::make('hero_heading')->required(),
                        TextInput::make('hero_subheading'),
                        FileUpload::make('hero_image_path')
                            ->label('Hero background image')
                            ->image()
                            ->disk(config('filesystems.media_disk'))
                            ->directory('home')
                            ->visibility('public'),
                    ]),
                Section::make('Introduction')
                    ->schema([
                        Textarea::make('intro_text')->rows(4),
                    ]),
                Section::make('Mission & Vision')
                    ->columns(2)
                    ->schema([
                        Textarea::make('mission')->rows(4),
                        Textarea::make('vision')->rows(4),
                    ]),
            ]);
    }
}
