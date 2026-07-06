<x-mail::message>
# Welcome to Unikosa North America!

Hi {{ $name }},

Thank you for registering. Your membership is **awaiting approval** by our team — we'll let you know as soon as it's active.

In the meantime, you can log in to your member portal to review and update your profile.

<x-mail::button :url="$url">Go to my portal</x-mail::button>

Warm regards,<br>
{{ config('app.name') }}
</x-mail::message>
