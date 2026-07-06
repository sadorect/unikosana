<?php

namespace App\Events;

use App\Models\Donation;
use Illuminate\Foundation\Events\Dispatchable;

class DonationCompleted
{
    use Dispatchable;

    public function __construct(public Donation $donation)
    {
    }
}
