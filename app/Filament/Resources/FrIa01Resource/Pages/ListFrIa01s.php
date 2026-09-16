<?php

namespace App\Filament\Resources\FrIa01Resource\Pages;

use App\Filament\Resources\FrIa01Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa01s extends ListRecords
{
    protected static string $resource = FrIa01Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
