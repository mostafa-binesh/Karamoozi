<?php

namespace App\Filament\Resources\IndustrySupervisorResource\Pages;

use App\Filament\Resources\IndustrySupervisorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndustrySupervisors extends ListRecords
{
    protected static string $resource = IndustrySupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
