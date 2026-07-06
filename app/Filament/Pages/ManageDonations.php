<?php

namespace App\Filament\Pages;

use App\Settings\DonationSettings;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageDonations extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Donations';

    protected static ?int $navigationSort = 6;

    protected static string $settings = DonationSettings::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Donation Page')
                    ->schema([
                        Toggle::make('enabled')
                            ->label('Show the public donation page'),
                        Textarea::make('intro')
                            ->rows(3),
                        Textarea::make('bank_details')
                            ->label('Bank / transfer details')
                            ->rows(6),
                    ]),
                Section::make('Online Payments (Stripe)')
                    ->description('Turn this on once Stripe API keys are configured in the server environment (STRIPE_KEY, STRIPE_SECRET). Until then, only the bank details show.')
                    ->schema([
                        Toggle::make('payment_enabled')
                            ->label('Enable online card payments'),
                        TextInput::make('suggested_amounts')
                            ->label('Suggested amounts (comma separated)')
                            ->helperText('e.g. 25,50,100,250'),
                        TextInput::make('currency')
                            ->maxLength(3)
                            ->helperText('ISO currency code, e.g. usd, cad'),
                    ]),
            ]);
    }
}
