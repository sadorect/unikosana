<?php

namespace App\Http\Controllers\Site;

use App\Enums\EventStatus;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Support\Notifier;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $upcoming = Event::upcoming()->get();
        $recent = Event::completed()->limit(6)->get();

        return view('site.events.index', compact('upcoming', 'recent'));
    }

    public function show(Event $event)
    {
        $event->load('media');

        return view('site.events.show', compact('event'));
    }

    public function register(Request $request, Event $event)
    {
        abort_unless($event->registration_enabled, 404);

        if ($event->isFull()) {
            return back()->with('reg_error', 'Sorry, this event has reached capacity.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'guests' => ['nullable', 'integer', 'min:0', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $registration = $event->registrations()->create($data);

        Notifier::toUser($registration->email, 'Registration confirmed: ' . $event->title, 'emails.event-registered', [
            'name' => $registration->name,
            'event' => $event,
            'guests' => $registration->guests,
            'url' => route('events.show', $event),
        ]);

        Notifier::toAdmin('New event registration', 'emails.admin-alert', [
            'heading' => 'New registration: ' . $event->title,
            'rows' => [
                'Name' => $registration->name,
                'Email' => $registration->email,
                'Phone' => $registration->phone ?: '—',
                'Guests' => $registration->guests,
            ],
            'actionUrl' => route('filament.admin.resources.events.edit', $event),
            'actionLabel' => 'View event',
        ]);

        return back()->with('reg_success', 'You are registered! We look forward to seeing you.');
    }

    public function archive()
    {
        $eventsByYear = Event::where('status', EventStatus::Completed->value)
            ->orderByDesc('date')
            ->get()
            ->groupBy(fn (Event $event) => $event->date->format('Y'));

        return view('site.events.archive', compact('eventsByYear'));
    }

    public function ics(Event $event)
    {
        $ics = $this->buildCalendar([$event]);

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $event->slug . '.ics"',
        ]);
    }

    public function feed()
    {
        $events = Event::upcoming()->get();

        return response($this->buildCalendar($events->all()), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="unikosa-na-events.ics"',
        ]);
    }

    /**
     * @param  array<int, Event>  $events
     */
    protected function buildCalendar(array $events): string
    {
        $escape = fn (?string $v) => addcslashes((string) $v, ",;\\\n");
        $lines = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//Unikosa North America//Events//EN', 'CALSCALE:GREGORIAN'];

        foreach ($events as $event) {
            $start = $event->date->copy();
            if ($event->time) {
                $start->setTimeFromTimeString((string) $event->time);
            }
            $end = $start->copy()->addHours(2);

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:event-' . $event->id . '@unikosa-na';
            $lines[] = 'DTSTAMP:' . now()->utc()->format('Ymd\THis\Z');
            $lines[] = 'DTSTART:' . $start->utc()->format('Ymd\THis\Z');
            $lines[] = 'DTEND:' . $end->utc()->format('Ymd\THis\Z');
            $lines[] = 'SUMMARY:' . $escape($event->title);
            if ($event->venue) {
                $lines[] = 'LOCATION:' . $escape($event->venue);
            }
            $lines[] = 'DESCRIPTION:' . $escape(\Illuminate\Support\Str::limit(strip_tags((string) $event->description), 300));
            $lines[] = 'URL:' . route('events.show', $event);
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return implode("\r\n", $lines);
    }
}
