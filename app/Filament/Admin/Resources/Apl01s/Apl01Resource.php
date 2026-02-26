<?php

namespace App\Filament\Admin\Resources\Apl01s;

use App\Filament\Admin\Resources\Apl01s\Pages\CreateApl01;
use App\Filament\Admin\Resources\Apl01s\Pages\EditApl01;
use App\Filament\Admin\Resources\Apl01s\Pages\ListApl01s;
use App\Filament\Admin\Resources\Apl01s\Schemas\Apl01Form;
use App\Filament\Admin\Resources\Apl01s\Tables\Apl01sTable;
use App\Models\Apl01;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class Apl01Resource extends Resource
{
    protected static ?string $model = Apl01::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'APL 01';

    public static function form(Schema $schema): Schema
    {
        return Apl01Form::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Apl01sTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApl01s::route('/'),
            'create' => CreateApl01::route('/create'),
            'edit' => EditApl01::route('/{record}/edit'),
        ];
    }
}
