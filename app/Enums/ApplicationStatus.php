<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case REGISTERED = 'registered';
    case PRESELECTED = 'preselected';
    case INTERVIEW = 'interview';
    case EVALUATION = 'evaluation';
    case FINALIST = 'finalist';
    case HIRED = 'hired';
    case DISCARDED = 'discarded';
}
