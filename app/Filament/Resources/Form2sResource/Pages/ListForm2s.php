<?php

namespace App\Filament\Resources\Form2sResource\Pages;

use App\Filament\Resources\Form2sResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForm2s extends ListRecords
{
    protected static string $resource = Form2sResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
