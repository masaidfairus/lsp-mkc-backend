<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Enums\AssessmentStatus;
use App\Filament\Resources\AssessmentRegistrationResource\RelationManagers\PortfolioFilesRelationManager;
use App\Filament\Resources\AssessmentRegistrationResource\RelationManagers\SelfAssessmentRelationManager;
use App\Filament\Resources\AssessmentRegistrationResource\RelationManagers\EvidenceRelationManager;
use App\Filament\Resources\AssessmentRegistrationResource\Pages;
use App\Filament\Resources\AssessmentRegistrationResource\RelationManagers\AplDataRelationManager;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
        $query =  parent::getEloquentQuery();

        if (!auth()->user()->hasRole('admin') || !auth()->user()->hasRole('super_admin')) {
            $query->where('assessor_id', auth()->id());
        }
        
        return $query;
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
                                Forms\Components\Select::make('status')
                                    ->options(AssessmentStatus::class)
                                    ->label('Status Pendaftaran')
                                    ->required()
                                    ->native(false),
                            ]),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Catatan Admin')
                            ->placeholder('Tambahkan catatan admin di sini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Mencetak Dokumen Asesi')
                    ->schema([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generate_apl01_pdf')
                                ->label('Cetak Dokumen APL.01')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('apl01.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_apl02_pdf')
                                ->label('Cetak Dokumen APL.02')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('apl02.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_mapa01_pdf')
                                ->label('Cetak Dokumen MAPA.01')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('mapa01.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_mapa02_pdf')
                                ->label('Cetak Dokumen MAPA.02')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('mapa02.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_ak01_pdf')
                                ->label('Cetak Dokumen AK.01')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak01.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_ak04_pdf')
                                ->label('Cetak Dokumen AK.04')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak04.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_ak07_pdf')
                                ->label('Cetak Dokumen AK.07')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak07.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                        ])->columnSpanFull(),
                    ])->columns(2),
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
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(AssessmentStatus::class)
                    ->native(false),
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
