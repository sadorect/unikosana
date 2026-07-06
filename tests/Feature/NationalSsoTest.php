<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NationalSsoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sso_redirect_is_hidden_when_not_configured(): void
    {
        config()->set('services.national.client_id', null);
        config()->set('services.national.client_secret', null);

        $this->get('/auth/national/redirect')->assertNotFound();
    }

    public function test_sso_redirect_points_at_national_authorize_endpoint(): void
    {
        config()->set('services.national.base_url', 'https://unikosa.sadorect.com');
        config()->set('services.national.client_id', 'test-client');
        config()->set('services.national.client_secret', 'test-secret');
        config()->set('services.national.authorize_path', '/oauth/authorize');
        config()->set('services.national.redirect', 'https://unikosana.sadorect.com/auth/national/callback');

        $response = $this->get('/auth/national/redirect');

        $response->assertRedirect();
        $location = $response->headers->get('Location');

        $this->assertStringContainsString('https://unikosa.sadorect.com/oauth/authorize', $location);
        $this->assertStringContainsString('client_id=test-client', $location);
        $this->assertStringContainsString('response_type=code', $location);
        $this->assertStringContainsString('state=', $location);
        $this->assertNotNull(session('national_oauth_state'));
    }

    public function test_callback_rejects_mismatched_state(): void
    {
        config()->set('services.national.client_id', 'test-client');
        config()->set('services.national.client_secret', 'test-secret');

        $this->withSession(['national_oauth_state' => 'expected-state'])
            ->get('/auth/national/callback?code=abc&state=WRONG')
            ->assertRedirect(route('member.login'));
    }

    public function test_successful_callback_creates_user_and_member_and_signs_in(): void
    {
        Role::findOrCreate('member', 'web');

        config()->set('services.national.base_url', 'https://unikosa.sadorect.com');
        config()->set('services.national.client_id', 'test-client');
        config()->set('services.national.client_secret', 'test-secret');
        config()->set('services.national.token_path', '/oauth/token');
        config()->set('services.national.user_path', '/api/user');

        Http::fake([
            'https://unikosa.sadorect.com/oauth/token' => Http::response(['access_token' => 'abc123', 'token_type' => 'Bearer'], 200),
            'https://unikosa.sadorect.com/api/user' => Http::response([
                'id' => 987,
                'name' => 'National Member',
                'email' => 'national.member@example.com',
                'country' => 'Canada',
            ], 200),
        ]);

        $response = $this->withSession(['national_oauth_state' => 'good-state'])
            ->get('/auth/national/callback?code=the-code&state=good-state');

        $response->assertRedirect(route('member.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'national.member@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('987', $user->national_id);
        $this->assertTrue($user->hasRole('member'));

        $member = Member::where('user_id', $user->id)->first();
        $this->assertNotNull($member);
        $this->assertSame('Canada', $member->country);
        $this->assertSame(\App\Enums\MemberStatus::Approved, $member->status);
    }
}
