<?php

namespace App\Filament\Resources\SchemeRequirementResource\Pages;

use App\Filament\Resources\SchemeRequirementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchemeRequirement extends EditRecord
{
    protected static string $resource = SchemeRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
