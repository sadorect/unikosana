<?php

namespace App\Filament\Pages;

use App\Settings\ThemeSettings;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageTheme extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Theme & Branding';

    protected static ?int $navigationSort = 1;

    protected static string $settings = ThemeSettings::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identity')
                    ->schema([
                        TextInput::make('site_name')
                            ->required(),
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->visibility('public'),
                        FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->visibility('public'),
                    ]),
                Section::make('Colors')
                    ->columns(3)
                    ->schema([
                        ColorPicker::make('primary_color')->required(),
                        ColorPicker::make('secondary_color')->required(),
                        ColorPicker::make('accent_color')->required(),
                    ]),
            ]);
    }
}
