<?php

namespace App\Filament\Resources\Ak02Resource\Pages;

use App\Filament\Resources\Ak02Resource;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;

class CreateAk02 extends CreateRecord
{
    use HasFormTemplates;
    protected static string $resource = Ak02Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveTemplateAction(),
            $this->getLoadTemplateAction(),
        ];
    }
}
