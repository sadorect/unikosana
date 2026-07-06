<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Theme
        $this->migrator->add('theme.site_name', 'Unikosa North America');
        $this->migrator->add('theme.primary_color', '#0f766e');
        $this->migrator->add('theme.secondary_color', '#134e4a');
        $this->migrator->add('theme.accent_color', '#f59e0b');
        $this->migrator->add('theme.logo_path', null);
        $this->migrator->add('theme.favicon_path', null);

        // Site / contact
        $this->migrator->add('site.contact_email', 'northamerica@unikosa.org');
        $this->migrator->add('site.contact_phone', null);
        $this->migrator->add('site.address', null);
        $this->migrator->add('site.facebook_url', null);
        $this->migrator->add('site.instagram_url', null);
        $this->migrator->add('site.twitter_url', null);
        $this->migrator->add('site.youtube_url', null);
        $this->migrator->add('site.whatsapp_url', null);
        $this->migrator->add('site.map_embed', null);

        // Home
        $this->migrator->add('home.hero_heading', 'Welcome to Unikosa North America');
        $this->migrator->add('home.hero_subheading', 'Uniting our community across the continent.');
        $this->migrator->add('home.hero_image_path', null);
        $this->migrator->add('home.intro_text', 'The North America Unit of Unikosa serves members across the United States and Canada, documenting our history, celebrating our achievements, and building community.');
        $this->migrator->add('home.mission', 'To unite, support, and empower Unikosa members throughout North America.');
        $this->migrator->add('home.vision', 'A thriving, connected community that upholds the values and legacy of Unikosa.');

        // About
        $this->migrator->add('about.history', 'The North America Unit of Unikosa was established to bring together members residing across the continent.');
        $this->migrator->add('about.mission', 'To unite, support, and empower Unikosa members throughout North America.');
        $this->migrator->add('about.vision', 'A thriving, connected community that upholds the values and legacy of Unikosa.');
        $this->migrator->add('about.objectives', "Promote unity among members.\nDocument and preserve our history.\nSupport members professionally and socially.\nOrganize impactful events and programs.");
        $this->migrator->add('about.org_structure', null);
        $this->migrator->add('about.org_structure_image_path', null);
        $this->migrator->add('about.constitution_pdf_path', null);

        // Security
        $this->migrator->add('security.captcha_enabled', true);
        $this->migrator->add('security.captcha_difficulty', 'default');
    }
};
