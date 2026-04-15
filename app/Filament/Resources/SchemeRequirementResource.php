<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchemeRequirementResource\Pages;
use App\Filament\Resources\SchemeRequirementResource\RelationManagers;
use App\Models\SchemeRequirement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchemeRequirementResource extends Resource
{
    protected static ?string $model = SchemeRequirement::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Mastering Data';
    protected static ?string $navigationLabel = 'Persyaratan Skema';
    protected static ?string $modelLabel = 'Persyaratan Skema';
    protected static ?string $pluralModelLabel = 'Persyaratan Skema';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Persyaratan')
                    ->schema([
                        Forms\Components\Select::make('scheme_id')
                            ->relationship('scheme', 'name')
                            ->label('Skema')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('requirement_name')
                            ->label('Nama Persyaratan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_mandatory')
                            ->label('Wajib?')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('requirement_name')
                    ->label('Nama Persyaratan')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\IconColumn::make('is_mandatory')
                    ->label('Wajib')
                    ->boolean(),
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
            'index' => Pages\ListSchemeRequirements::route('/'),
            'create' => Pages\CreateSchemeRequirement::route('/create'),
            'edit' => Pages\EditSchemeRequirement::route('/{record}/edit'),
        ];
    }
}
