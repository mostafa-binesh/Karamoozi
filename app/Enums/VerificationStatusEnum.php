<?php

namespace App\Enums;

enum VerificationStatusEnum: int
{
    case NotAvailable = 0;
    case NotChecked = 1;
    case Approved = 2;
    case Refused = 3;
}