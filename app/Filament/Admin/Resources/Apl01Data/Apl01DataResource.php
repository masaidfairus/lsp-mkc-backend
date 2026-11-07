<?php

namespace App\Filament\Admin\Resources\Apl01Data;

use App\Filament\Admin\Resources\Apl01Data\Pages\CreateApl01Data;
use App\Filament\Admin\Resources\Apl01Data\Pages\EditApl01Data;
use App\Filament\Admin\Resources\Apl01Data\Pages\ListApl01Data;
use App\Filament\Admin\Resources\Apl01Data\Schemas\Apl01DataForm;
use App\Filament\Admin\Resources\Apl01Data\Tables\Apl01DataTable;
use App\Models\Apl01Data;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class Apl01DataResource extends Resource
{
    protected static ?string $model = Apl01Data::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'Apl01Data';

    /**
     * Form.
     */
    public static function form(Form $form): Form
    {
        return Apl01DataForm::configure($form);
    }

    /**
     * Table.
     */
    public static function table(Table $table): Table
    {
        return Apl01DataTable::configure($table);
    }

    /**
     * Mendapatkan relasi getRelations.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Mengambil pages.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListApl01Data::route('/'),
            'create' => CreateApl01Data::route('/create'),
            'edit' => EditApl01Data::route('/{record}/edit'),
        ];
    }
}
