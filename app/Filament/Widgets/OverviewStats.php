<?php

namespace App\Filament\Widgets;

use App\Enums\EventStatus;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Member;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Members', Member::count())
                ->description('In the directory')
                ->icon('heroicon-o-identification')
                ->color('primary'),
            Stat::make('Upcoming Events', Event::where('status', '!=', EventStatus::Completed->value)->count())
                ->description('Scheduled')
                ->icon('heroicon-o-calendar-days')
                ->color('info'),
            Stat::make('Published Posts', Post::whereNotNull('published_at')->count())
                ->description('News & announcements')
                ->icon('heroicon-o-newspaper')
                ->color('success'),
            Stat::make('Unread Messages', ContactMessage::where('is_read', false)->count())
                ->description('Contact submissions')
                ->icon('heroicon-o-envelope')
                ->color('warning'),
        ];
    }
}
