<?php

namespace App\Filament\Resources\Ak06Resource\Pages;

use App\Filament\Resources\Ak06Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;

class CreateAk06 extends CreateRecord
{
    use HasFormTemplates;
    protected static string $resource = Ak06Resource::class;

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
        ];
    }
}
