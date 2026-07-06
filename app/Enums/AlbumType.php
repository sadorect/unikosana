<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AlbumType: string implements HasLabel
{
    case Photo = 'photo';
    case Video = 'video';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }
}
