<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('donation.enabled', true);
        $this->migrator->add('donation.payment_enabled', false);
        $this->migrator->add('donation.intro', 'Your generous support helps us organize events, support members, and grow our community across North America.');
        $this->migrator->add('donation.bank_details', "Bank: Example Bank\nAccount Name: Unikosa North America\nAccount Number: 000000000\nRouting Number: 000000000\n\nFor questions about giving, please contact us.");
        $this->migrator->add('donation.suggested_amounts', '25,50,100,250');
        $this->migrator->add('donation.currency', 'usd');
    }
};
