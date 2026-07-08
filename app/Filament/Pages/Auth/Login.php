<?php

namespace App\Filament\Pages\Auth;

use App\Settings\SecuritySettings;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema(array_filter([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                $this->captchaRequired() ? $this->getCaptchaFormComponent() : null,
            ]))
            ->statePath('data');
    }

    protected function getCaptchaFormComponent(): Group
    {
        return Group::make([
            View::make('filament.forms.captcha')
                ->viewData(['difficulty' => $this->captchaDifficulty()]),
            TextInput::make('captcha')
                ->label('Security code')
                ->required()
                ->autocomplete('off')
                ->extraInputAttributes(['inputmode' => 'text']),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        // Validate the captcha before we touch credentials or the login
        // rate limiter, so a wrong code never counts as a login attempt.
        if ($this->captchaRequired()) {
            $data = $this->form->getState();

            if (! captcha_check($data['captcha'] ?? '')) {
                throw ValidationException::withMessages([
                    'data.captcha' => 'The security code is incorrect. Please try again.',
                ]);
            }
        }

        return parent::authenticate();
    }

    protected function captchaRequired(): bool
    {
        try {
            return app(SecuritySettings::class)->admin_captcha_enabled;
        } catch (\Throwable $e) {
            // Settings not migrated yet — fail safe by requiring the captcha.
            return true;
        }
    }

    protected function captchaDifficulty(): string
    {
        try {
            return app(SecuritySettings::class)->captcha_difficulty ?: 'default';
        } catch (\Throwable $e) {
            return 'default';
        }
    }
}
