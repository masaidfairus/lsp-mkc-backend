<?php

namespace App\Filament\Resources\Ak06Resource\Pages;

use App\Filament\Resources\Ak06Resource;
use App\Models\Ak06;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAk06s extends ManageRecords
{
    protected static string $resource = Ak06Resource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Ak06 {
                    return Ak06::updateOrCreate(
                        ['registration_id' => $data['registration_id']],
                        $data
                    );
                }),
        ];
    }
}
