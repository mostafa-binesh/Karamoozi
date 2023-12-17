<?php

namespace App\Filament\Resources\WeeklyReportResource\Pages;

use App\Filament\Resources\WeeklyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWeeklyReport extends EditRecord
{
    protected static string $resource = WeeklyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['reports'] = json_encode($data['reports']);

        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['reports'] = json_decode($data['reports']);

        return $data;
    }
}
