<?php

namespace App\Filament\Admin\Resources\Apl01Data\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;

class Apl01DataForm
{
    /**
     * Configure.
     *
     * @param  Schema  $schema
     * @return Schema
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),
            ]);
    }
}
