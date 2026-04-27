<?php

namespace App\Filament\Resources\Mapa01Resource\Pages;

use App\Filament\Resources\Mapa01Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMapa01 extends EditRecord
{
    protected static string $resource = Mapa01Resource::class;

    protected function afterSave(): void
    {
        $data = $this->record->toArray();
        $registration = \App\Models\AssessmentRegistration::find($data['registration_id']);
        if ($registration && $registration->scheme_id) {
            \App\Models\MapaTemplate::updateOrCreate(
                ['scheme_id' => $registration->scheme_id],
                ['data' => $data['mapa01_data']]
            );
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
