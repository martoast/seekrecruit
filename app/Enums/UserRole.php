<?php

namespace App\Enums;

enum UserRole: string
{
    case CANDIDATE = 'candidate';
    case HR_ADMIN = 'hr_admin';
    case SUPER_ADMIN = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::CANDIDATE => 'Candidate',
            self::HR_ADMIN => 'HR Admin',
            self::SUPER_ADMIN => 'Super Admin',
        };
    }
}
