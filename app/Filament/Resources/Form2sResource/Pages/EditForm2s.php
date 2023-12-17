<?php

namespace App\Filament\Resources\Form2sResource\Pages;

use App\Filament\Resources\Form2sResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForm2s extends EditRecord
{
    protected static string $resource = Form2sResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
