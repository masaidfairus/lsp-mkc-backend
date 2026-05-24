<?php

namespace App\Filament\Resources\FrIa06aResource\Pages;

use App\Filament\Resources\FrIa06aResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa06as extends ListRecords
{
    protected static string $resource = FrIa06aResource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
