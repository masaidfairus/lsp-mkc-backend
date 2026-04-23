<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkGroupResource\Pages;
use App\Filament\Resources\WorkGroupResource\RelationManagers;
use App\Models\WorkGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkGroupResource extends Resource
{
    protected static ?string $model = WorkGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Kelompok Pekerjaan';
    protected static ?string $modelLabel = 'Kelompok Pekerjaan';
    protected static ?string $pluralModelLabel = 'Kelompok Pekerjaan';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('scheme_id')
                    ->label('Skema Sertifikasi')
                    ->relationship('scheme', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kelompok Pekerjaan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Section::make('Unit Kompetensi yang Terkait')
                    ->schema([
                        Forms\Components\Repeater::make('competencyUnits')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('unit_code')
                                    ->label('Kode Unit')
                                    ->disabled(),
                                Forms\Components\TextInput::make('unit_title')
                                    ->label('Judul Unit')
                                    ->disabled(),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->grid(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scheme.code')
                    ->label('Kode Skema')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Nama Skema')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Kelompok Pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('competencyUnits.unit_code')
                    ->label('Units')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('competencyUnits.unit_title')
                    ->label('Titles')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),
                Tables\Columns\TextColumn::make('competency_units_count')
                    ->counts('competencyUnits')
                    ->label('Total')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListWorkGroups::route('/'),
            'create' => Pages\CreateWorkGroup::route('/create'),
            'edit' => Pages\EditWorkGroup::route('/{record}/edit'),
        ];
    }
}
