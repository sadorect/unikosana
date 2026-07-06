<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::public();

        if ($search = $request->string('q')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('occupation', 'like', "%{$search}%")
                    ->orWhere('school', 'like', "%{$search}%");
            });
        }

        if ($country = $request->string('country')->toString()) {
            $query->where('country', $country);
        }

        if ($state = $request->string('state')->toString()) {
            $query->where('state_province', $state);
        }

        if ($year = $request->integer('year')) {
            $query->where('graduation_year', $year);
        }

        $members = $query->orderBy('full_name')->paginate(12)->withQueryString();

        $countries = Member::public()->distinct()->orderBy('country')->pluck('country')->filter();
        $states = Member::public()->distinct()->orderBy('state_province')->pluck('state_province')->filter();
        $years = Member::public()->distinct()->orderByDesc('graduation_year')->pluck('graduation_year')->filter();

        return view('site.members', compact('members', 'countries', 'states', 'years'));
    }
}
