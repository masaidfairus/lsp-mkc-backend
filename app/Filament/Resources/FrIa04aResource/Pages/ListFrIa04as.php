<?php

namespace App\Filament\Resources\FrIa04aResource\Pages;

use App\Filament\Resources\FrIa04aResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa04as extends ListRecords
{
    protected static string $resource = FrIa04aResource::class;

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
