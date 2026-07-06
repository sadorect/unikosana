<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Settings\DonationSettings;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class DonationController extends Controller
{
    public function show(DonationSettings $settings)
    {
        abort_unless($settings->enabled, 404);

        $amounts = collect(explode(',', $settings->suggested_amounts))
            ->map(fn ($a) => (int) trim($a))
            ->filter()
            ->values();

        return view('site.donate', [
            'settings' => $settings,
            'amounts' => $amounts,
            'paymentReady' => $this->paymentReady($settings),
        ]);
    }

    public function checkout(Request $request, DonationSettings $settings)
    {
        abort_unless($settings->enabled, 404);
        abort_unless($this->paymentReady($settings), 403, 'Online payment is not available right now.');

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:100000'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $cents = (int) round($data['amount'] * 100);

        $donation = Donation::create([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'amount' => $cents,
            'currency' => $settings->currency,
            'status' => 'pending',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'mode' => 'payment',
            'customer_email' => $data['email'] ?? null,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => $settings->currency,
                    'unit_amount' => $cents,
                    'product_data' => ['name' => 'Donation to Unikosa North America'],
                ],
            ]],
            'success_url' => route('donate.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('donate.cancel'),
            'metadata' => ['donation_id' => $donation->id],
        ]);

        $donation->update(['reference' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request, DonationSettings $settings)
    {
        $sessionId = $request->query('session_id');

        if ($sessionId && $this->paymentReady($settings)) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve($sessionId);

            $donation = Donation::where('reference', $sessionId)->first();
            if ($donation && $session->payment_status === 'paid') {
                $donation->update(['status' => 'paid']);
                event(new \App\Events\DonationCompleted($donation));
            }
        }

        return view('site.donate-success');
    }

    public function cancel()
    {
        return view('site.donate-cancel');
    }

    protected function paymentReady(DonationSettings $settings): bool
    {
        return $settings->payment_enabled
            && filled(config('services.stripe.secret'))
            && filled(config('services.stripe.key'));
    }
}
