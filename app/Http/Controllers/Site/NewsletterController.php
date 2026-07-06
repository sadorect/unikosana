<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Support\Notifier;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $subscriber = Subscriber::firstOrNew(['email' => strtolower($data['email'])]);
        $subscriber->name = $data['name'] ?? $subscriber->name;
        $wasInactive = ! $subscriber->exists || ! $subscriber->getOriginal('is_active');
        $subscriber->is_active = true;
        $subscriber->unsubscribed_at = null;
        $subscriber->save();

        if ($wasInactive) {
            Notifier::toUser($subscriber->email, 'Welcome to our newsletter', 'emails.newsletter-welcome', [
                'unsubscribeUrl' => route('newsletter.unsubscribe', $subscriber->token),
            ]);
        }

        return back()->with('newsletter_success', 'Thanks for subscribing! You are on the list.');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = Subscriber::where('token', $token)->first();

        if ($subscriber) {
            $subscriber->update(['is_active' => false, 'unsubscribed_at' => now()]);
        }

        return view('site.newsletter-unsubscribed', [
            'email' => $subscriber?->email,
        ]);
    }
}
