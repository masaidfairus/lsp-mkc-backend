<?php

namespace App\Filament\Resources\FrIa06cResource\Pages;

use App\Filament\Resources\FrIa06cResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa06cs extends ListRecords
{
    protected static string $resource = FrIa06cResource::class;

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
