<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PostType: string implements HasLabel, HasColor
{
    case News = 'news';
    case Article = 'article';
    case Announcement = 'announcement';
    case PressRelease = 'press_release';

    public function getLabel(): string
    {
        return match ($this) {
            self::News => 'News',
            self::Article => 'Article',
            self::Announcement => 'Announcement',
            self::PressRelease => 'Press Release',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::News => 'info',
            self::Article => 'gray',
            self::Announcement => 'warning',
            self::PressRelease => 'success',
        };
    }
}
