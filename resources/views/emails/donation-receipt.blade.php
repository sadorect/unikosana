<x-mail::message>
# Thank you for your donation

@if ($name)Hi {{ $name }},@endif

We gratefully acknowledge your generous donation of **{{ $amount }}**. Your support directly funds our events, programs, and member services across North America.

This email serves as your receipt.

Thank you,<br>
{{ config('app.name') }}
</x-mail::message>
