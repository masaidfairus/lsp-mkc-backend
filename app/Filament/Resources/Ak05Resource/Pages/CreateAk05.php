<?php

namespace App\Filament\Resources\Ak05Resource\Pages;

use App\Filament\Resources\Ak05Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;

class CreateAk05 extends CreateRecord
{
    use HasFormTemplates;
    protected static string $resource = Ak05Resource::class;

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
            $this->getDeleteTemplateAction(),
        ];
    }
}
