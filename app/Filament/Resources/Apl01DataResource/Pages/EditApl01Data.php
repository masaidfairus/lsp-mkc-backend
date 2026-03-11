<?php

namespace App\Filament\Resources\Apl01DataResource\Pages;

use App\Filament\Resources\Apl01DataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApl01Data extends EditRecord
{
    protected static string $resource = Apl01DataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
