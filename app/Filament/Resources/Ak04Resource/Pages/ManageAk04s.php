<?php

namespace App\Filament\Resources\Ak04Resource\Pages;

use App\Filament\Resources\Ak04Resource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAk04s extends ManageRecords
{
    protected static string $resource = Ak04Resource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            // Usually created via API/Kandidat mobile, but keep for debug
            Actions\CreateAction::make(),
        ];
    }
}
