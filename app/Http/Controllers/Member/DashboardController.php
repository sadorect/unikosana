<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $member = $this->memberFor($request);

        return view('member.dashboard', compact('member'));
    }

    public function update(Request $request)
    {
        $member = $this->memberFor($request);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'state_province' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'school' => ['nullable', 'string', 'max:255'],
            'graduation_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'biography' => ['nullable', 'string', 'max:2000'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        $data['is_public'] = $request->boolean('is_public');
        $member->update($data);

        if ($request->hasFile('photo')) {
            $request->validate(['photo' => ['image', 'max:4096']]);
            $member->clearMediaCollection('photo');
            $member->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return redirect()->route('member.dashboard')->with('success', 'Your profile has been updated.');
    }

    protected function memberFor(Request $request): Member
    {
        $member = $request->user()->member;

        abort_if($member === null, 403, 'No member profile is linked to this account.');

        return $member;
    }
}
