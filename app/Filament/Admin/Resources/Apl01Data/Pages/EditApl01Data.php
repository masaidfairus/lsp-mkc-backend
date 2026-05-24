<?php

namespace App\Filament\Admin\Resources\Apl01Data\Pages;

use App\Filament\Admin\Resources\Apl01Data\Apl01DataResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApl01Data extends EditRecord
{
    protected static string $resource = Apl01DataResource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
