<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EventStatus: string implements HasLabel, HasColor
{
    case Upcoming = 'upcoming';
    case Ongoing = 'ongoing';
    case Completed = 'completed';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Upcoming => 'info',
            self::Ongoing => 'warning',
            self::Completed => 'success',
        };
    }
}
