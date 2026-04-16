<?php

namespace App\Enums;

enum Modality: string
{
    case ON_SITE = 'on_site';
    case REMOTE = 'remote';
    case HYBRID = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::ON_SITE => 'On-site',
            self::REMOTE => 'Remote',
            self::HYBRID => 'Hybrid',
        };
    }
}
