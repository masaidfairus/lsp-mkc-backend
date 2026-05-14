<?php

namespace App\Filament\Resources\FrIa06bResource\Pages;

use App\Filament\Resources\FrIa06bResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa06bs extends ListRecords
{
    protected static string $resource = FrIa06bResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
