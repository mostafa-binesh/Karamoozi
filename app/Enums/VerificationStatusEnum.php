<?php

namespace App\Enums;

enum VerificationStatusEnum: int
{
    case NotAvailable = 0; // sabt nashode
    case NotChecked = 1; // check nashode
    case Approved = 2; // taeed shode
    case Refused = 3; // rad shode
}
