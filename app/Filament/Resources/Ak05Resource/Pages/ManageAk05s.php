<?php

namespace App\Filament\Resources\Ak05Resource\Pages;

use App\Filament\Resources\Ak05Resource;
use App\Models\Ak05;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAk05s extends ManageRecords
{
    protected static string $resource = Ak05Resource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Ak05 {
                    return Ak05::updateOrCreate(
                        ['registration_id' => $data['registration_id']],
                        $data
                    );
                }),
        ];
    }
}
