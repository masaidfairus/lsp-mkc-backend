<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak03Resource\Pages;
use App\Filament\Resources\Ak03Resource\RelationManagers;
use App\Models\Ak03;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Ak03Resource extends Resource
{
    protected static ?string $model = Ak03::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.03';
    protected static ?string $modelLabel = 'AK.03';
    protected static ?string $pluralModelLabel = 'AK.03';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListAk03s::route('/'),
            'create' => Pages\CreateAk03::route('/create'),
            'edit' => Pages\EditAk03::route('/{record}/edit'),
        ];
    }
}
