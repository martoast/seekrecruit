<?php

namespace App\Enums;

enum PositionStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case DRAFT = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::CLOSED => 'Closed',
            self::DRAFT => 'Draft',
        };
    }
}
