<?php

namespace App\Enums;

enum VacationType: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
