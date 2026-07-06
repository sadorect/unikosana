<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ResourceCategory: string implements HasLabel
{
    case Constitution = 'constitution';
    case Report = 'report';
    case Minutes = 'minutes';
    case Brochure = 'brochure';
    case Newsletter = 'newsletter';
    case Form = 'form';

    public function getLabel(): string
    {
        return match ($this) {
            self::Constitution => 'Constitution',
            self::Report => 'Report',
            self::Minutes => 'Meeting Minutes',
            self::Brochure => 'Event Brochure',
            self::Newsletter => 'Newsletter',
            self::Form => 'Membership Form',
        };
    }
}
