<?php

namespace App\Filament\Admin\Resources\Apl01Data;

use App\Filament\Admin\Resources\Apl01Data\Pages\CreateApl01Data;
use App\Filament\Admin\Resources\Apl01Data\Pages\EditApl01Data;
use App\Filament\Admin\Resources\Apl01Data\Pages\ListApl01Data;
use App\Filament\Admin\Resources\Apl01Data\Schemas\Apl01DataForm;
use App\Filament\Admin\Resources\Apl01Data\Tables\Apl01DataTable;
use App\Models\Apl01Data;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class Apl01DataResource extends Resource
{
    protected static ?string $model = Apl01Data::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Apl01Data';

    /**
     * Form.
     *
     * @param  Schema  $schema
     * @return Schema
     */
    public static function form(Schema $schema): Schema
    {
        return Apl01DataForm::configure($schema);
    }

    /**
     * Table.
     *
     * @param  Table  $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return Apl01DataTable::configure($table);
    }

    /**
     * Mendapatkan relasi getRelations.
     *
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Mengambil pages.
     *
     * @return array
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
