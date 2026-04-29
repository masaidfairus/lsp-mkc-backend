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
                                    ->relationship('assessor', 'name', fn(Builder $query) => $query->role(Role::ASESOR->value))
                                    ->label('Asesor')
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
                        Forms\Components\Tabs::make('Kategori Dokumen')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Dokumen APL')
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
                            Forms\Components\Actions\Action::make('generate_ak02_pdf')
                                ->label('Cetak Dokumen AK.02')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak02.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_ak03_pdf')
                                ->label('Cetak Dokumen AK.03')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak03.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_ak04_pdf')
                                ->label('Cetak Dokumen AK.04')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak04.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_ak05_pdf')
                                ->label('Cetak Dokumen AK.05')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak05.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_ak06_pdf')
                                ->label('Cetak Dokumen AK.06')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak06.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('generate_ak07_pdf')
                                ->label('Cetak Dokumen AK.07')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ak07.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                        ])->columnSpanFull(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Dokumen IA')
                                    ->schema([
                                        Forms\Components\Fieldset::make('IA.01 - IA.03 (Persiapan & Observasi)')
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Forms\Components\Actions\Action::make('generate_ia01_pdf')
                                ->label('Cetak Dokumen IA.01')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia01.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia02_pdf')
                                ->label('Cetak Dokumen IA.02')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia02.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia03_pdf')
                                ->label('Cetak Dokumen IA.03')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia03.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                ])->columnSpanFull(),
                                            ]),
                                        Forms\Components\Fieldset::make('IA.04 (Penjelasan Singkat)')
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Forms\Components\Actions\Action::make('generate_ia04a_pdf')
                                ->label('Cetak Dokumen IA.04a')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia04a.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia04b_pdf')
                                ->label('Cetak Dokumen IA.04b')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia04b.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                ])->columnSpanFull(),
                                            ]),
                                        Forms\Components\Fieldset::make('IA.05 (Pertanyaan Tertulis & Lisan)')
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Forms\Components\Actions\Action::make('generate_ia05a_pdf')
                                ->label('Cetak Dokumen IA.05a')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia05a.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia05b_pdf')
                                ->label('Cetak Dokumen IA.05b')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia05b.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia05c_pdf')
                                ->label('Cetak Dokumen IA.05c')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia05c.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                ])->columnSpanFull(),
                                            ]),
                                        Forms\Components\Fieldset::make('IA.06 (Pertanyaan Wawancara)')
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Forms\Components\Actions\Action::make('generate_ia06a_pdf')
                                ->label('Cetak Dokumen IA.06a')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia06a.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia06b_pdf')
                                ->label('Cetak Dokumen IA.06b')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia06b.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia06c_pdf')
                                ->label('Cetak Dokumen IA.06c')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia06c.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                ])->columnSpanFull(),
                                            ]),
                                        Forms\Components\Fieldset::make('IA.07 - IA.11 (Lainnya)')
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Forms\Components\Actions\Action::make('generate_ia07_pdf')
                                ->label('Cetak Dokumen IA.07')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia07.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia08_pdf')
                                ->label('Cetak Dokumen IA.08')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia08.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia09_pdf')
                                ->label('Cetak Dokumen IA.09')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia09.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia10_pdf')
                                ->label('Cetak Dokumen IA.10')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia10.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ia11_pdf')
                                ->label('Cetak Dokumen IA.11')
                                ->color('success')
                                ->icon('heroicon-o-printer')
                                ->url(fn($record) => $record ? route('ia11.form', ['registration' => $record->id]) : '#')
                                ->openUrlInNewTab(),
                                                ])->columnSpanFull(),
                                            ]),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Dokumen MAPA')
                                    ->schema([
                                        Forms\Components\Actions::make([
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
                                        ])->columnSpanFull(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Dokumen MKVA')
                                    ->schema([
                                        Forms\Components\Actions::make([
                                            Forms\Components\Actions\Action::make('generate_va_pdf')
                                                ->label('Cetak Dokumen FR.VA')
                                                ->color('success')
                                                ->icon('heroicon-o-printer')
                                                ->url(fn($record) => $record ? route('frva.print', ['registration' => $record->id]) : '#')
                                                ->openUrlInNewTab(),
                                        ])->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
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
