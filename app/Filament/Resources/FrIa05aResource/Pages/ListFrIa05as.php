<?php

namespace App\Filament\Resources\FrIa05aResource\Pages;

use App\Filament\Resources\FrIa05aResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa05as extends ListRecords
{
    protected static string $resource = FrIa05aResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
