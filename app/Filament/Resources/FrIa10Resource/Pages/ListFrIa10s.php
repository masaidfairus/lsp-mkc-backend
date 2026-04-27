<?php

namespace App\Filament\Resources\FrIa10Resource\Pages;

use App\Filament\Resources\FrIa10Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa10s extends ListRecords
{
    protected static string $resource = FrIa10Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
