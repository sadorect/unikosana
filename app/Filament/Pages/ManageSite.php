<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSite extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Contact & Social';

    protected static ?int $navigationSort = 2;

    protected static string $settings = SiteSettings::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_email')->email(),
                        TextInput::make('contact_phone')->tel(),
                        Textarea::make('address')->columnSpanFull(),
                        Textarea::make('map_embed')
                            ->label('Google Map embed (iframe HTML)')
                            ->columnSpanFull(),
                    ]),
                Section::make('Social Links')
                    ->columns(2)
                    ->schema([
                        TextInput::make('facebook_url')->url()->prefixIcon('heroicon-o-link'),
                        TextInput::make('instagram_url')->url()->prefixIcon('heroicon-o-link'),
                        TextInput::make('twitter_url')->label('X / Twitter URL')->url()->prefixIcon('heroicon-o-link'),
                        TextInput::make('youtube_url')->url()->prefixIcon('heroicon-o-link'),
                        TextInput::make('whatsapp_url')->url()->prefixIcon('heroicon-o-link'),
                    ]),
            ]);
    }
}
