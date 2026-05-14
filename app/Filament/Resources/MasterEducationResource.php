<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterEducationResource\Pages;
use App\Filament\Resources\MasterEducationResource\RelationManagers;
use App\Models\MasterEducation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterEducationResource extends Resource
{
    protected static ?string $model = MasterEducation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Kualifikasi Pendidikan';
    protected static ?string $modelLabel = 'Kualifikasi Pendidikan';
    protected static ?string $pluralModelLabel = 'Kualifikasi Pendidikan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Nama Kualifikasi Pendidikan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Kualifikasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada:')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui pada:')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
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
            'index' => Pages\ListMasterEducation::route('/'),
        ];
    }
}
