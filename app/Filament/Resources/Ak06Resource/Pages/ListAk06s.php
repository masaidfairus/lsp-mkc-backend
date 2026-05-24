<?php

namespace App\Filament\Resources\Ak06Resource\Pages;

use App\Filament\Resources\Ak06Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAk06s extends ListRecords
{
    protected static string $resource = Ak06Resource::class;

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
