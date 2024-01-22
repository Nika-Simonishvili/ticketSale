<?php

namespace App\Enums;

enum OrderStatus: int
{
    case SUCCESS = 1;
    case PENDING = 2;
    case CANCEL = 3;
}
