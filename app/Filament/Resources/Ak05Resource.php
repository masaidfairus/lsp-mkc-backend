<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak05Resource\Pages;
use App\Filament\Resources\Ak05Resource\RelationManagers;
use App\Models\Ak05;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Ak05Resource extends Resource
{
    protected static ?string $model = Ak05::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.05';
    protected static ?string $modelLabel = 'AK.05';
    protected static ?string $pluralModelLabel = 'AK.05';
    protected static ?int $navigationSort = 5;

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
            'index' => Pages\ListAk05s::route('/'),
            'create' => Pages\CreateAk05::route('/create'),
            'edit' => Pages\EditAk05::route('/{record}/edit'),
        ];
    }
}
