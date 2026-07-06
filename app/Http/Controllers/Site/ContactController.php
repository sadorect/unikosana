<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Settings\SecuritySettings;
use App\Support\Notifier;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show(SecuritySettings $security)
    {
        return view('site.contact', [
            'captchaEnabled' => $security->captcha_enabled,
            'captchaSrc' => $security->captcha_enabled
                ? captcha_src($security->captcha_difficulty ?: 'default')
                : null,
        ]);
    }

    public function submit(Request $request, SecuritySettings $security)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];

        if ($security->captcha_enabled) {
            $rules['captcha'] = ['required', 'captcha'];
        }

        $validated = $request->validate($rules, [
            'captcha.captcha' => 'The captcha code is incorrect. Please try again.',
        ]);

        $message = ContactMessage::create(collect($validated)->except('captcha')->toArray());

        Notifier::toAdmin('New contact message', 'emails.admin-alert', [
            'heading' => 'New contact message',
            'intro' => 'A visitor submitted the contact form.',
            'rows' => [
                'Name' => $message->name,
                'Email' => $message->email,
                'Phone' => $message->phone ?: '—',
                'Subject' => $message->subject,
                'Message' => $message->message,
            ],
        ]);

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you! Your message has been sent. We will get back to you soon.');
    }
}
