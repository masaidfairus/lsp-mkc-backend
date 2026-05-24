<?php

namespace App\Filament\Resources\Ak03Resource\Pages;

use App\Filament\Resources\Ak03Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;

class EditAk03 extends EditRecord
{
    use HasFormTemplates;
    protected static string $resource = Ak03Resource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
            Actions\DeleteAction::make(),
        ];
    }
}
