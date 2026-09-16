<?php

namespace App\Filament\Resources\MasterEducationResource\Pages;

use App\Filament\Resources\MasterEducationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEducation extends EditRecord
{
    protected static string $resource = MasterEducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
