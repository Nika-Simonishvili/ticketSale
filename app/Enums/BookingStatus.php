<?php

namespace App\Enums;

enum BookingStatus: int
{
    case COMPLETED = 1;

    case PENDING = 2;

    case DISCARDED = 3;
}
