<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class DonationSettings extends Settings
{
    public bool $enabled;

    public bool $payment_enabled;

    public ?string $intro;

    public ?string $bank_details;

    public string $suggested_amounts;

    public string $currency;

    public static function group(): string
    {
        return 'donation';
    }
}
