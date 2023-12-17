<?php

namespace App\Filament\Resources\WeeklyReportResource\Pages;

use App\Filament\Resources\WeeklyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWeeklyReport extends CreateRecord
{
    protected static string $resource = WeeklyReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['reports'] = json_encode($data['reports']);

        return $data;
    }
}
