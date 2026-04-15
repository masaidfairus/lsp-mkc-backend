<?php

namespace App\Filament\Resources\SchemeRequirementResource\Pages;

use App\Filament\Resources\SchemeRequirementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchemeRequirements extends ListRecords
{
    protected static string $resource = SchemeRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
