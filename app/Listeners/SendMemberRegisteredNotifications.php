<?php

namespace App\Listeners;

use App\Events\MemberRegistered;
use App\Support\Notifier;

class SendMemberRegisteredNotifications
{
    public function handle(MemberRegistered $event): void
    {
        $member = $event->member;

        Notifier::toAdmin('New membership registration', 'emails.admin-alert', [
            'heading' => 'New membership registration',
            'intro' => 'A new member has registered and is awaiting approval.',
            'rows' => [
                'Name' => $member->full_name,
                'Email' => $member->contact_email,
                'Country' => $member->country,
                'State/Province' => $member->state_province ?: '—',
            ],
            'actionUrl' => route('filament.admin.resources.members.index'),
            'actionLabel' => 'Review members',
        ]);

        Notifier::toUser($member->contact_email, 'Welcome to Unikosa North America', 'emails.member-welcome', [
            'name' => $member->full_name,
            'url' => route('member.dashboard'),
        ]);
    }
}
