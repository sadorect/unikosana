<?php

namespace App\Support;

use App\Mail\SystemMail;
use App\Settings\SiteSettings;
use Illuminate\Support\Facades\Mail;

class Notifier
{
    /**
     * The address that receives administrative notifications.
     */
    public static function adminEmail(): string
    {
        return app(SiteSettings::class)->contact_email
            ?: config('mail.from.address');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function toAdmin(string $subject, string $view, array $payload = []): void
    {
        Mail::to(static::adminEmail())->send(new SystemMail($subject, $view, $payload));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function toUser(?string $email, string $subject, string $view, array $payload = []): void
    {
        if (! $email) {
            return;
        }

        Mail::to($email)->send(new SystemMail($subject, $view, $payload));
    }
}
