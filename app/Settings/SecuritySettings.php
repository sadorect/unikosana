<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SecuritySettings extends Settings
{
    public bool $captcha_enabled;

    public string $captcha_difficulty;

    public static function group(): string
    {
        return 'security';
    }
}
