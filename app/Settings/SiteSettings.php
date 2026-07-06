<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public ?string $contact_email;

    public ?string $contact_phone;

    public ?string $address;

    public ?string $facebook_url;

    public ?string $instagram_url;

    public ?string $twitter_url;

    public ?string $youtube_url;

    public ?string $whatsapp_url;

    public ?string $map_embed;

    public static function group(): string
    {
        return 'site';
    }
}
