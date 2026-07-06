<?php

namespace App\Http\Controllers\Member;

use App\Enums\MemberStatus;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('member.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'country' => ['required', 'string', 'max:255'],
            'state_province' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'school' => ['nullable', 'string', 'max:255'],
            'graduation_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'biography' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole('member');

        $member = Member::create([
            'user_id' => $user->id,
            'full_name' => $data['name'],
            'contact_email' => $user->email,
            'country' => $data['country'],
            'state_province' => $data['state_province'] ?? null,
            'occupation' => $data['occupation'] ?? null,
            'school' => $data['school'] ?? null,
            'graduation_year' => $data['graduation_year'] ?? null,
            'contact_phone' => $data['contact_phone'] ?? null,
            'biography' => $data['biography'] ?? null,
            'is_public' => false,
            'status' => MemberStatus::Pending,
        ]);

        event(new \App\Events\MemberRegistered($member));

        Auth::login($user);

        return redirect()->route('member.dashboard')
            ->with('success', 'Welcome! Your membership registration has been received and is awaiting approval.');
    }
}
