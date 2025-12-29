<?php

namespace App\Enums;

enum ReferralStatus: string
{
    case PENDING = 'pending';
    case REGISTERED = 'registered';
    case HIRED = 'hired';
    case REWARDED = 'rewarded';
}
