<?php

namespace App\Filament\Resources\SignatureInboxResource\Pages;

use App\Filament\Resources\SignatureInboxResource;
use Filament\Resources\Pages\ListRecords;

class ListSignatureInboxes extends ListRecords
{
    protected static string $resource = SignatureInboxResource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
