<?php

namespace App\Filament\Resources\Apl01s;

use App\Filament\Resources\Apl01s\Pages\CreateApl01;
use App\Filament\Resources\Apl01s\Pages\EditApl01;
use App\Filament\Resources\Apl01s\Pages\ListApl01s;
use App\Filament\Resources\Apl01s\Schemas\Apl01Form;
use App\Filament\Resources\Apl01s\Tables\Apl01sTable;
use App\Models\Apl01;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Apl01Resource extends Resource
{
    protected static ?string $model = Apl01::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'APL 01';

    public static function form(Form $form): Form
    {
        return Apl01Form::configure($form);
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

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
