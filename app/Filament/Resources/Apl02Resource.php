<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Enums\Apl02Status;
use App\Filament\Resources\Apl02Resource\Pages;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Apl02Resource extends Resource
{
    protected static ?string $model = \App\Models\Apl02::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'APL-02';
    protected static ?string $modelLabel = 'APL-02';
    protected static ?string $pluralModelLabel = 'APL-02';
    protected static ?string $navigationGroup = 'APL';
    protected static ?int $navigationSort = 1;



    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('assessor_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Validasi Evaluasi Mandiri (APL-02)')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('candidate', 'name')
                            ->label('Asesi')
                            ->disabled(),
                        Forms\Components\Select::make('scheme_id')
                            ->relationship('scheme', 'name')
                            ->label('Skema')
                            ->disabled(),
                        Forms\Components\Select::make('status_apl02')
                            ->options(Apl02Status::class)
                            ->label('Status APL-02')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_apl02')
                    ->label('Status APL-02')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assessed_at')
                    ->label('Tanggal Asesmen')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApl02s::route('/'),
            'edit' => Pages\EditApl02::route('/{record}/edit'),
        ];
    }
}
