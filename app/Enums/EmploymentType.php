<?php

namespace App\Enums;

enum EmploymentType: string
{
    case FULL_TIME = 'full_time';
    case PART_TIME = 'part_time';
    case INTERNSHIP = 'internship';
    case CONTRACT = 'contract';

    public function label(): string
    {
        return match ($this) {
            self::FULL_TIME => 'Full-time',
            self::PART_TIME => 'Part-time',
            self::INTERNSHIP => 'Internship',
            self::CONTRACT => 'Contract',
        };
    }
}
