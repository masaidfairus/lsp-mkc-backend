<?php

namespace App\Filament\Resources\Mapa01Resource\Pages;

use App\Filament\Resources\Mapa01Resource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\MapaTemplate;

class CreateMapa01 extends CreateRecord
{
    protected static string $resource = Mapa01Resource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = static::getModel()::updateOrCreate(
            ['registration_id' => $data['registration_id']],
            $data
        );

        // Auto-save as template linked to scheme
        $registration = \App\Models\AssessmentRegistration::find($data['registration_id']);
        if ($registration && $registration->scheme_id) {
            MapaTemplate::updateOrCreate(
                ['scheme_id' => $registration->scheme_id],
                ['data' => $data['mapa01_data']]
            );
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
