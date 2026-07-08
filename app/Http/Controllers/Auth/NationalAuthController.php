<?php

namespace App\Http\Controllers\Auth;

use App\Enums\MemberStatus;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NationalAuthController extends Controller
{
    /**
     * Redirect the user to the national Passport authorization screen.
     */
    public function redirect(Request $request)
    {
        abort_unless($this->configured(), 404);

        $state = Str::random(40);
        $request->session()->put('national_oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('services.national.client_id'),
            'redirect_uri' => $this->redirectUri(),
            'response_type' => 'code',
            'scope' => config('services.national.scope'),
            'state' => $state,
        ]);

        return redirect($this->url('authorize_path') . '?' . $query);
    }

    /**
     * Handle the OAuth callback: exchange the code, fetch the profile, sign in.
     */
    public function callback(Request $request)
    {
        abort_unless($this->configured(), 404);

        $expected = $request->session()->pull('national_oauth_state');
        if (! $expected || ! hash_equals($expected, (string) $request->query('state'))) {
            return redirect()->route('member.login')->withErrors(['email' => 'Login session expired. Please try again.']);
        }

        if ($request->has('error') || ! $request->filled('code')) {
            return redirect()->route('member.login')->withErrors(['email' => 'National sign-in was cancelled or failed.']);
        }

        $tokenResponse = Http::asForm()->post($this->url('token_path'), [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.national.client_id'),
            'client_secret' => config('services.national.client_secret'),
            'redirect_uri' => $this->redirectUri(),
            'code' => $request->query('code'),
        ]);

        if ($tokenResponse->failed()) {
            return redirect()->route('member.login')->withErrors(['email' => 'Could not verify your national account. Please try again.']);
        }

        $accessToken = $tokenResponse->json('access_token');

        $profile = Http::withToken($accessToken)
            ->acceptJson()
            ->get($this->url('user_path'));

        if ($profile->failed() || ! $profile->json('email')) {
            return redirect()->route('member.login')->withErrors(['email' => 'Could not load your national profile.']);
        }

        $profileData = $profile->json();

        // Keep the local record in sync with the national account first, so the
        // member profile reflects reality even when access is subsequently denied.
        $user = $this->upsertUser($profileData);

        // Honour the national account status: only members in good standing may
        // sign in. A suspended/rejected/pending national account is turned away.
        if ($this->mapStatus($profileData['status'] ?? null) !== MemberStatus::Approved) {
            return redirect()->route('member.login')->withErrors([
                'email' => 'Your national membership is not active. Please contact Unikosa.',
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('member.dashboard'));
    }

    /**
     * Map a national account status string onto a local member status.
     * Anything other than an explicit "approved" is treated as not-in-good-standing.
     */
    protected function mapStatus(?string $status): MemberStatus
    {
        return match (strtolower((string) $status)) {
            'approved' => MemberStatus::Approved,
            'pending' => MemberStatus::Pending,
            default => MemberStatus::Rejected,
        };
    }

    protected function upsertUser(array $profile): User
    {
        $email = strtolower($profile['email']);
        $nationalId = (string) ($profile['id'] ?? '');

        $user = User::where('national_id', $nationalId)->when($nationalId === '', fn ($q) => $q->whereRaw('1 = 0'))->first()
            ?? User::where('email', $email)->first();

        if (! $user) {
            $user = new User();
            $user->password = bcrypt(Str::random(40));
        }

        $user->national_id = $nationalId ?: $user->national_id;
        $user->name = $profile['name'] ?? $user->name ?? $email;
        $user->email = $email;
        $user->save();

        if (! $user->hasAnyRole(['member', 'content_admin', 'site_manager', 'super_admin'])) {
            $user->assignRole('member');
        }

        $status = $this->mapStatus($profile['status'] ?? null);
        $avatarUrl = $profile['avatar'] ?? null;

        // Ensure a linked member profile exists and stays in sync with the
        // national account on every sign-in (status + avatar are authoritative
        // from the national side; locally editable fields are left untouched).
        if ($member = $user->member) {
            $member->update([
                'status' => $status,
                'avatar_url' => $avatarUrl,
            ]);
        } else {
            Member::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'contact_email' => $user->email,
                'country' => $profile['country'] ?? 'United States',
                'avatar_url' => $avatarUrl,
                'is_public' => false,
                'status' => $status,
            ]);
        }

        return $user;
    }

    protected function configured(): bool
    {
        return filled(config('services.national.client_id'))
            && filled(config('services.national.client_secret'));
    }

    protected function redirectUri(): string
    {
        return config('services.national.redirect') ?: route('national.callback');
    }

    protected function url(string $pathKey): string
    {
        return rtrim(config('services.national.base_url'), '/') . config('services.national.' . $pathKey);
    }
}
