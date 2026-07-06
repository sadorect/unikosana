<?php

namespace Tests\Feature;

use App\Settings\DonationSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_donate_page_shows_bank_details_in_info_mode(): void
    {
        $this->get('/donate')
            ->assertOk()
            ->assertSee('bank transfer')
            ->assertDontSee('Donate securely');
    }

    public function test_online_form_appears_when_payment_is_fully_configured(): void
    {
        config()->set('services.stripe.key', 'pk_test_x');
        config()->set('services.stripe.secret', 'sk_test_x');

        $settings = app(DonationSettings::class);
        $settings->payment_enabled = true;
        $settings->save();

        $this->get('/donate')
            ->assertOk()
            ->assertSee('Donate securely');
    }

    public function test_donate_page_is_hidden_when_disabled(): void
    {
        $settings = app(DonationSettings::class);
        $settings->enabled = false;
        $settings->save();

        $this->get('/donate')->assertNotFound();
    }
}
