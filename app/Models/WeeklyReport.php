<?php

namespace App\Models;

use App\Enums\VerificationStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'reports' => 'array',
        'date' => 'date',
        'status' => VerificationStatusEnum::class,
    ];

    // # relationships

    // # scopes

    // # attributes

    // # methods
    public static function new(int $studentId, string | Carbon $date, int $weekNumber, VerificationStatusEnum $status, string $content = null, bool $isWeekVerified = false): static
    {
        return static::create([
            'student_id' => $studentId,
            'date' => $date,
            'week_number' => $weekNumber,
            'status' => $status,
            'content' => $content,
            'reports' => '', // delete the report column later
            'is_week_verified' => $isWeekVerified,
        ]);
    }

    // arguments >>  GroupedByweeklyReports: a groupedBy('week_number') collection of weeklyReport 
    public static function getFirstUnfinishedWeeklyReport(Collection $GroupedByweeklyReports)
    {
        dd($GroupedByweeklyReports);
        foreach ($GroupedByweeklyReports as $weeklyReportCollection) {
            $finished = true;
            foreach ($weeklyReportCollection as $weeklyReport) {
                // dd($weeklyReport->status);
                if (
                    $weeklyReport->status == VerificationStatusEnum::NotAvailable
                    && $weeklyReport->is_week_finished == false
                    // || $weeklyReport->status == VerificationStatusEnum::Refused
                ) {
                    $finished = false;
                    break;
                }
            }
            // return  ? $weeklyReportCollection : null;
            if (!$finished) return $weeklyReportCollection;
        }
        return null;
    }
}
