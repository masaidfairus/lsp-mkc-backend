<?php

namespace App\Filament\Resources\MasterEducationResource\Pages;

use App\Filament\Resources\MasterEducationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEducation extends ListRecords
{
    protected static string $resource = MasterEducationResource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Kualifikasi Baru'),
        ];
    }
}
