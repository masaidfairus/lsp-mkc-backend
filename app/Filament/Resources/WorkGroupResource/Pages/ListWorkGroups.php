<?php

namespace App\Filament\Resources\WorkGroupResource\Pages;

use App\Filament\Resources\WorkGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkGroups extends ListRecords
{
    protected static string $resource = WorkGroupResource::class;

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
