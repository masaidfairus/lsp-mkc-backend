<?php

namespace App\Filament\Resources\Mapa01Resource\Pages;

use App\Filament\Resources\Mapa01Resource;
use App\Filament\Traits\HasSignatureRequestSpawner;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;

class EditMapa01 extends EditRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected static string $resource = Mapa01Resource::class;

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
        foreach (['penyusun_1_id', 'penyusun_2_id'] as $key) {
            $uid = data_get($data, "signatures.compiler.{$key}.user_id");
            if ($uid) $mappings[] = ['user_id' => (int) $uid, 'role' => 'Penyusun'];
        }
        foreach (['validator_1_id', 'validator_2_id'] as $key) {
            $uid = data_get($data, "signatures.validator.{$key}.user_id");
            if ($uid) $mappings[] = ['user_id' => (int) $uid, 'role' => 'Validator'];
        }
        return $mappings;
    }

    /**
     * After save.
     *
     * @return void
     */
    protected function afterSave(): void
    {
        // Sync MAPA template
        $data = $this->record->toArray();
        $registration = \App\Models\AssessmentRegistration::find($data['registration_id']);
        if ($registration && $registration->scheme_id) {
            \App\Models\MapaTemplate::updateOrCreate(
                ['scheme_id' => $registration->scheme_id],
                ['data' => $data['mapa01_data']]
            );
        }

        // Sync signature requests
        $this->syncSignatureRequests($this->getRecord());
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
            Actions\DeleteAction::make(),
        ];
    }
}
