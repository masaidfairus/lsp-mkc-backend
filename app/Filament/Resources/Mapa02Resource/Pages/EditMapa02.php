<?php

namespace App\Filament\Resources\Mapa02Resource\Pages;

use App\Filament\Resources\Mapa02Resource;
use App\Filament\Traits\HasSignatureRequestSpawner;
use Filament\Actions;
use App\Filament\Traits\HasFormTemplates;
use Filament\Resources\Pages\EditRecord;

class EditMapa02 extends EditRecord
{
    use HasFormTemplates;
    use HasSignatureRequestSpawner;

    protected static string $resource = Mapa02Resource::class;

    /**
     * Mengambil document label.
     *
     * @return string
     */
    protected function getDocumentLabel(): string
    {
        return 'MAPA.02';
    }

    /**
     * Extract signer mappings.
     *
     * @param  mixed  $record
     * @return array
     */
    protected function extractSignerMappings($record): array
    {
        $data = $record->mapa02_data ?? [];
        $mappings = [];
        
        $compilers = data_get($data, 'compiler_and_validator.compiler', []);
        foreach ($compilers as $compiler) {
            if (!empty($compiler['user_id'])) {
                $mappings[] = ['user_id' => (int) $compiler['user_id'], 'role' => 'Penyusun'];
            }
        }

        $validators = data_get($data, 'compiler_and_validator.validator', []);
        foreach ($validators as $validator) {
            if (!empty($validator['user_id'])) {
                $mappings[] = ['user_id' => (int) $validator['user_id'], 'role' => 'Validator'];
            }
        }

        return $mappings;
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
            Actions\DeleteAction::make(),
        ];
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
}
