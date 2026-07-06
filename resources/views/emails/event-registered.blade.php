<x-mail::message>
# You're registered!

Hi {{ $name }},

Thank you for registering for **{{ $event->title }}**. We look forward to seeing you.

<x-mail::table>
| | |
|:--- |:--- |
| **Date** | {{ $event->date->format('l, F j, Y') }} |
| **Venue** | {{ $event->venue ?: 'To be announced' }} |
@if ($guests)
| **Guests** | {{ $guests }} |
@endif
</x-mail::table>

<x-mail::button :url="$url">View event details</x-mail::button>

See you there,<br>
{{ config('app.name') }}
</x-mail::message>
