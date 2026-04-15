<?php

namespace App\Filament\Resources\PerformanceCriterionResource\Pages;

use App\Filament\Resources\PerformanceCriterionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerformanceCriteria extends ListRecords
{
    protected static string $resource = PerformanceCriterionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
