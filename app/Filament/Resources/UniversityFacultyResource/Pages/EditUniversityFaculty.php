<?php

namespace App\Filament\Resources\UniversityFacultyResource\Pages;

use App\Filament\Resources\UniversityFacultyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniversityFaculty extends EditRecord
{
    protected static string $resource = UniversityFacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
