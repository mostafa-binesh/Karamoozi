<?php

namespace App\Models;

use App\Enums\VerificationStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'reports' => 'array',
        'date' => 'date',
    ];

    // # relationships

    // # scopes

    // # attributes

    // # methods
    public static function new($userId, string | Carbon $date, int $weekNumber, VerificationStatusEnum $status, string $content = null): static
    {
        return static::create([
            'user_id' => $userId,
            'date' => $date,
            'week_number' => $weekNumber,
            'status' => $status,
            'content' => $content,
        ]);
    }
}
