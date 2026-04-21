<?php

namespace App\Filament\Resources\DocumentListResource\Pages;

use App\Filament\Resources\DocumentListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentList extends EditRecord
{
    protected static string $resource = DocumentListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
