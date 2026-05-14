<?php

namespace App\Filament\Resources\FrIa08Resource\Pages;

use App\Filament\Resources\FrIa08Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa08s extends ListRecords
{
    protected static string $resource = FrIa08Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
