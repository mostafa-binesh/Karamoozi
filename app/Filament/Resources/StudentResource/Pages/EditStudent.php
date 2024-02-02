<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Report;
use App\Models\Student;
use Filament\Forms\Set;
use Livewire\Component;
use App\Models\WeeklyReport;
use Filament\Actions\Action;
use GuzzleHttp\Psr7\Request;
use App\Models\IndustrySupervisor;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StudentResource;
use App\Models\Form2s;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('calculateInternshipFinishedAt')
            ->label('محاسبه ی زمان پایان کاراموزی')
            ->color('info')
            ->action(function (array $data, array $arguments, Action $action, Component $livewire, $record) {
                $record->load('weeklyReport');
                $record->internship_finished_at = $record->calculateInternshipFinishedAt();
                $record->save();
            }),
            Action::make('MakeForm2')
                ->icon('heroicon-m-folder-minus')
                ->color('info')
                ->label("ساخت فرم 2")
                ->form([
                    Select::make('supervisor_id')
                        ->relationship(
                            name: 'industrySupervisor',
                            titleAttribute: null,
                            modifyQueryUsing: fn (Builder $query) => $query->with('user')
                        )
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record?->user?->fullName}")
                        ->preload(),
                ])
                ->requiresConfirmation()
                ->action(function (array $data, array $arguments, Action $action, Component $livewire) {
                    $indSupervisorId = IndustrySupervisor::whereId($data['supervisor_id'])->firstOrFail()->user->id;
                    $student = Student::whereId($livewire->data['id'])->firstOrFail();
                    $schedule_table = [
                        "08:00,12:00,14:00,18:00",
                        "08:00,12:00,14:00,18:00",
                        "00:00,00:00,00:00,00:00",
                        "08:00,12:00,14:00,18:00",
                        "08:00,13:00,13:30,18:00",
                        "00:00,00:00,00:00,00:00"
                    ];
                    $reports = [
                        [
                            "date" => "2020/1/1",
                            "desc" => "asdsasda"
                        ]
                    ];
                    $form2 = Form2s::create([
                        'industry_supervisor_id' => $indSupervisorId,
                        'student_id' => $student->id,
                        // ! fix later, dry
                        'schedule_table' => $schedule_table,
                        'introduction_letter_number' => 123412,
                        'introduction_letter_date' => '2020/5/12',
                        'internship_department' => 'dasdasdadasD',
                        'supervisor_position' => 'dasasdasdaa',
                        'internship_started_at' => '2020/5/12',
                        'internship_website' => 'dasasddasA',
                        'description' => 'Alaki',
                        'verified' => 1,
                        // waiting
                    ]);
                    $student->supervisor_id = $indSupervisorId;
                    $student->unevaluate();
                    $student->save();
                    // submit reports
                    foreach ($reports as $report) {
                        $result = Report::create([
                            'form2_id' => $form2->id,
                            'date' => $report['date'],
                            'description' => $report['desc'],
                        ]);
                    }
                    // set the reports attr. of the weeklyReports table for this student
                    $allWorkingDaysDate = $student->calculateAllWorkingDaysDate();
                    if ($allWorkingDaysDate == 0) {
                        return response()->json([
                            'message' => 'لطفا برنامه ی معتبری را وارد کنید',
                        ], 400);
                    }
                    WeeklyReport::updateOrCreate(
                        ['student_id' => $student->id],
                        [
                            'reports' => $allWorkingDaysDate
                        ]
                    );
                    //     {
                    //         "student_number" : "3981231019",
                    //         "national_code" : "5300053259",
                    //         "schedule_table" : [
                    //             "08:00,12:00,14:00,18:00",
                    //             "08:00,12:00,14:00,18:00",
                    //             "00:00,00:00,00:00,00:00",
                    //             "08:00,12:00,14:00,18:00",
                    //             "08:00,13:00,13:30,18:00",
                    //             "00:00,00:00,00:00,00:00"
                    //         ],
                    //         "introduction_letter_number" : "123412",
                    //         "introduction_letter_date" : "2020/5/12",
                    //         "internship_department" : "dasdasdadasD",
                    //         "supervisor_position" : " dasasdasdaa",
                    //         "internship_start_date" : "2020/5/12",
                    //         "internship_website" : "dasasddasA",
                    //         "reports" : [
                    //             {
                    //                 "date": "2020/1/1",
                    //                 "desc": "asdsasda"
                    //             }
                    //         ]
                    //     }
                }),
        ];
    }
}
