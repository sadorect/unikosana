<?php

namespace App\Events;

use App\Models\Member;
use Illuminate\Foundation\Events\Dispatchable;

class MemberRegistered
{
    use Dispatchable;

    public function __construct(public Member $member)
    {
    }
}
