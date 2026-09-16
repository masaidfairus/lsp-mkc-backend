<?php

namespace App\Filament\Resources\Mapa01Resource\Pages;

use App\Filament\Resources\Mapa01Resource;
use App\Filament\Traits\HasSignatureRequestSpawner;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\CreateRecord;
use App\Models\MapaTemplate;

class CreateMapa01 extends CreateRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    /**
     * Mengambil document label.
     *
     * @return string
     */
    protected function getDocumentLabel(): string
    {
        return 'MAPA.01';
    }

    /**
     * Extract signer mappings.
     *
     * @param  mixed  $record
     * @return array
     */
    protected function extractSignerMappings($record): array
    {
        $data = $record->mapa01_data ?? [];
        $mappings = [];

        // Penyusun 1 & 2
        foreach (['penyusun_1_id', 'penyusun_2_id'] as $key) {
            $uid = data_get($data, "signatures.compiler.{$key}.user_id");
            if ($uid) $mappings[] = ['user_id' => (int) $uid, 'role' => 'Penyusun'];
        }

        // Validator 1 & 2
        foreach (['validator_1_id', 'validator_2_id'] as $key) {
            $uid = data_get($data, "signatures.validator.{$key}.user_id");
            if ($uid) $mappings[] = ['user_id' => (int) $uid, 'role' => 'Validator'];
        }

        return $mappings;
    }

    protected static string $resource = Mapa01Resource::class;

    /**
     * Menangani record creation.
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     */
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

    /**
     * Mengambil redirect url.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

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
