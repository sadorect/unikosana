<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Require the image captcha on the admin panel login by default.
        $this->migrator->add('security.admin_captcha_enabled', true);
    }

    public function down(): void
    {
        $this->migrator->delete('security.admin_captcha_enabled');
    }
};
