<?php

namespace App\Listeners;

use App\Events\DonationCompleted;
use App\Support\Notifier;

class SendDonationNotifications
{
    public function handle(DonationCompleted $event): void
    {
        $donation = $event->donation;

        Notifier::toUser($donation->email, 'Your donation receipt', 'emails.donation-receipt', [
            'name' => $donation->name,
            'amount' => $donation->formatted_amount,
        ]);

        Notifier::toAdmin('New donation received', 'emails.admin-alert', [
            'heading' => 'New donation received',
            'rows' => [
                'Donor' => $donation->name ?: 'Anonymous',
                'Email' => $donation->email ?: '—',
                'Amount' => $donation->formatted_amount,
                'Status' => ucfirst($donation->status),
            ],
        ]);
    }
}
