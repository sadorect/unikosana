<?php

namespace App\Filament\Pages;

use App\Settings\SecuritySettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSecurity extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Security & Captcha';

    protected static ?int $navigationSort = 5;

    protected static string $settings = SecuritySettings::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Spam Protection')
                    ->schema([
                        Toggle::make('captcha_enabled')
                            ->label('Require image captcha on public forms'),
                        Toggle::make('admin_captcha_enabled')
                            ->label('Require image captcha on the admin login')
                            ->helperText('Adds an extra challenge to the admin panel sign-in, on top of login rate limiting.'),
                        Select::make('captcha_difficulty')
                            ->options([
                                'default' => 'Default',
                                'math' => 'Math challenge',
                                'flat' => 'Flat (easy)',
                                'mini' => 'Mini',
                                'inverse' => 'Inverse (hard)',
                            ])
                            ->default('default')
                            ->required(),
                    ]),
            ]);
    }
}
