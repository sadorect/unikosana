<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SecuritySettings extends Settings
{
    public bool $captcha_enabled;

    public string $captcha_difficulty;

    public bool $admin_captcha_enabled;

    public static function group(): string
    {
        return 'security';
    }
}
