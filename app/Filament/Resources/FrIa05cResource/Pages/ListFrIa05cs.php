<?php

namespace App\Filament\Resources\FrIa05cResource\Pages;

use App\Filament\Resources\FrIa05cResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrIa05cs extends ListRecords
{
    protected static string $resource = FrIa05cResource::class;

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
