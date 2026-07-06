@php $rows ??= []; $intro ??= null; $actionUrl ??= null; $actionLabel ??= null; @endphp

<x-mail::message>
# {{ $heading }}

@if ($intro)
{{ $intro }}
@endif

@if (count($rows))
<x-mail::table>
| Field | Value |
|:----- |:----- |
@foreach ($rows as $label => $value)
| {{ $label }} | {{ $value }} |
@endforeach
</x-mail::table>
@endif

@if ($actionUrl)
<x-mail::button :url="$actionUrl">{{ $actionLabel ?? 'View in dashboard' }}</x-mail::button>
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
