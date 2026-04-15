<?php

namespace App\Filament\Resources\CompetencyUnitResource\Pages;

use App\Filament\Resources\CompetencyUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompetencyUnits extends ListRecords
{
    protected static string $resource = CompetencyUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
