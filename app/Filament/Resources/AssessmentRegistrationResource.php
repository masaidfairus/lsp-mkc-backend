<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
use App\Filament\Resources\AssessmentRegistrationResource\RelationManagers\PortfolioFilesRelationManager;
use App\Filament\Resources\AssessmentRegistrationResource\RelationManagers\SelfAssessmentRelationManager;
use App\Filament\Resources\AssessmentRegistrationResource\RelationManagers\EvidenceRelationManager;
use App\Filament\Resources\AssessmentRegistrationResource\Pages;
use App\Filament\Resources\AssessmentRegistrationResource\RelationManagers\AplDataRelationManager;
use App\Models\Apl01;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AssessmentRegistrationResource extends Resource
{
    protected static ?string $model = AssessmentRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pendaftaran Sertifikasi';
    protected static ?string $modelLabel = 'Pendaftaran Sertifikasi';
    protected static ?string $pluralModelLabel = 'Pendaftaran Sertifikasi';
    protected static ?string $navigationGroup = 'Pendaftaran';


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
                                    ->relationship('assessor', 'name', fn(Builder $query) => $query->where('role', Role::ASESOR->value))
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
                Forms\Components\Section::make('Mencetak Dokumen Asesi')
                    ->schema([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generate_pdf')
                                ->label('Cetak Dokumen APL.01')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('apl01.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_pdf')
                                ->label('Cetak Dokumen APL.02')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('apl02.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                        ])->columnSpanFull(), // Agar tombol mengambil lebar penuh jika perlu
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
                Tables\Columns\TextInputColumn::make('admin_notes')
                    ->label('Catatan Admin')
                    ->placeholder('Tambah catatan...')
                    ->searchable(),
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
            AplDataRelationManager::class,
            EvidenceRelationManager::class,
            PortfolioFilesRelationManager::class,
            SelfAssessmentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessmentRegistrations::route('/'),
            'edit' => Pages\EditAssessmentRegistration::route('/{record}/edit'),
        ];
    }
}
