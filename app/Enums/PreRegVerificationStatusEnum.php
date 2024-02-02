<?php

namespace App\Enums;

enum PreRegVerificationStatusEnum: int
{
        // not sent bythe user
    case NotAvailable = 0;
        // professor
    case MasterPending = 1;
    case MasterApproved = 2;
    case MasterRefused = 3;
        // admin
    case AdminPending = 4;
    case AdminApproved = 5;
    case AdminRefused = 6;

        // totally verified
    case Verified = 7;

    public function normalize()
    {
        return match ($this) {
            static::NotAvailable => static::NotAvailable,
            static::MasterPending => static::MasterPending,
            static::MasterApproved => static::MasterApproved,
            static::MasterRefused => static::MasterRefused,
        };
    }
}
