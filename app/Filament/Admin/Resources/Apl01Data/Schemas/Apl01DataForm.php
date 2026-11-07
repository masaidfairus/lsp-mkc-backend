<?php

namespace App\Filament\Admin\Resources\Apl01Data\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;

class Apl01DataForm
{
    /**
     * Configure.
     */
    public static function configure(Form $form): Form
    {
        return $form
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),
            ]);
    }
}
