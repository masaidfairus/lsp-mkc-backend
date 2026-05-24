<?php

namespace App\Filament\Resources\Ak03Resource\Pages;

use App\Filament\Resources\Ak03Resource;
use App\Models\Ak03;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAk03s extends ManageRecords
{
    protected static string $resource = Ak03Resource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, string $model): Ak03 {
                    return Ak03::updateOrCreate(
                        ['registration_id' => $data['registration_id']],
                        $data
                    );
                }),
        ];
    }
}
