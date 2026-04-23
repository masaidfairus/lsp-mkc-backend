<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Enums\Apl01Status;
use App\Filament\Resources\Apl01Resource\Pages;
use App\Filament\Resources\Apl01Resource\RelationManagers;
use App\Models\Apl01;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Apl01Resource extends Resource
{
    protected static ?string $model = Apl01::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'APL-01';
    protected static ?string $modelLabel = 'APL-01';
    protected static ?string $pluralModelLabel = 'APL-01';
    protected static ?string $navigationGroup = 'APL';
    protected static ?int $navigationSort = 1;


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status_apl01', '!=', Apl01Status::NOT_STARTED->value);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('candidate', 'name')
                                    ->label('Asesi')
                                    ->disabled(),
                                Forms\Components\Select::make('scheme_id')
                                    ->relationship('scheme', 'name')
                                    ->label('Skema')
                                    ->disabled(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('assessor_id')
                                    ->relationship('assessor', 'name', function (Builder $query) {
                                        return $query->where('role', Role::ASESOR->value);
                                    })
                                    ->label('Asesi Bertugas')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('status_apl01')
                                    ->options([
                                        Apl01Status::PENDING->value => Apl01Status::PENDING->getLabel(),
                                        Apl01Status::APPROVED->value => Apl01Status::APPROVED->getLabel(),
                                        Apl01Status::REJECTED->value => Apl01Status::REJECTED->getLabel(),
                                    ])
                                    ->label('Status APL-01')
                                    ->required(),
                            ]),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->placeholder('Tambahkan catatan admin di sini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('Registration ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('admin_notes')
                    ->label('Catatan Admin')
                    ->placeholder('Tambah catatan...')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_apl01')
                    ->label('Status APL-01')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary'),
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
            // RelationManagers\EvidenceRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApl01s::route('/'),
            'edit' => Pages\EditApl01::route('/{record}/edit'),
        ];
    }
}
