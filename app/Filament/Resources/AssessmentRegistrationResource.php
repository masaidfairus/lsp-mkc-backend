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


    // public static function getEloquentQuery(): Builder
    // {
    //     $query =  parent::getEloquentQuery();

    //     if (!auth()->user()->hasRole('admin') || !auth()->user()->hasRole('super_admin')) {
    //         $query->where('assessor_id', auth()->id());
    //     }
        
    //     return $query;
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Pendaftaran Asesmen')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Status & Administrasi')
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
                                            ->label('Status APL.01')
                                            ->required(),
                                    ]),
                                Forms\Components\Textarea::make('admin_notes')
                                    ->label('Catatan Admin')
                                    ->placeholder('Tambahkan catatan admin di sini...')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                
                                Forms\Components\Section::make('Validasi Evaluasi Mandiri (APL.02)')
                                    ->schema([
                                        Forms\Components\Placeholder::make('apl02_warning')
                                            ->label('')
                                            ->content(new \Illuminate\Support\HtmlString('<div style="color: #b45309; background-color: #fffbeb; padding: 12px; border-radius: 8px; border: 1px solid #fde68a;">Asesi belum mengisi Form Asesmen Mandiri (APL.02). Status tidak dapat diubah.</div>'))
                                            ->visible(fn ($record) => !$record || $record->selfAssessments->isEmpty())
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('status_apl02')
                                            ->options(Apl02Status::class)
                                            ->label('Status APL.02')
                                            ->required()
                                            ->visible(fn ($record) => $record && $record->selfAssessments->isNotEmpty()),
                                    ])->columns(1),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('APL.01')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('candidate.name')
                                        ->label('Nama Lengkap')
                                        ->formatStateUsing(fn ($record) => $record?->candidate?->name)
                                        ->disabled(),
                                    Forms\Components\TextInput::make('candidate.email')
                                        ->label('Email')
                                        ->formatStateUsing(fn ($record) => $record?->candidate?->email)
                                        ->disabled(),
                                    Forms\Components\TextInput::make('candidate.registration_number')
                                        ->label('No. Registrasi')
                                        ->formatStateUsing(fn ($record) => $record?->candidate?->registration_number ?: 'Belum ada')
                                        ->disabled(),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('candidate.identity_type')
                                        ->label('Jenis Identitas')
                                        ->formatStateUsing(fn ($record) => match ($record?->candidate?->identity_type) {
                                            'id' => 'KTP',
                                            'passport' => 'Passport',
                                            default => $record?->candidate?->identity_type ?? '-',
                                        })
                                        ->disabled(),
                                    Forms\Components\TextInput::make('candidate.identity_number')
                                        ->label('No. Identitas')
                                        ->formatStateUsing(fn ($record) => $record?->candidate?->identity_number)
                                        ->disabled(),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('apl_gender')
                                        ->label('Jenis Kelamin')
                                        ->formatStateUsing(fn ($record) => $record?->aplData?->gender?->getLabel() ?? '-')
                                        ->disabled(),
                                    Forms\Components\TextInput::make('apl_place_of_birth')
                                        ->label('Tempat Lahir')
                                        ->formatStateUsing(fn ($record) => $record?->aplData?->place_of_birth)
                                        ->disabled(),
                                    Forms\Components\TextInput::make('apl_date_of_birth')
                                        ->label('Tanggal Lahir')
                                        ->formatStateUsing(fn ($record) => $record?->aplData?->date_of_birth?->format('d/m/Y'))
                                        ->disabled(),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('apl_phone_number')
                                        ->label('No. Telepon')
                                        ->formatStateUsing(fn ($record) => $record?->aplData?->phone_number)
                                        ->disabled(),
                                    Forms\Components\TextInput::make('apl_education')
                                        ->label('Pendidikan Terakhir')
                                        ->formatStateUsing(function ($record) {
                                            $data = $record?->aplData;
                                            if (!$data) return '-';
                                            if ($data->education_qualifications) return $data->education_qualifications;
                                            if ($data->education_id) {
                                                return \Illuminate\Support\Facades\DB::table('master_education')
                                                    ->where('id', $data->education_id)
                                                    ->value('title') ?? "ID: {$data->education_id}";
                                            }
                                            return '-';
                                        })
                                        ->disabled(),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Textarea::make('apl_address')
                                        ->label('Alamat Rumah')
                                        ->formatStateUsing(fn ($record) => $record?->aplData?->address)
                                        ->disabled(),
                                    Forms\Components\TextInput::make('apl_home_postal_code')
                                        ->label('Kode Pos Rumah')
                                        ->formatStateUsing(fn ($record) => $record?->aplData?->home_postal_code)
                                        ->disabled(),
                                ]),
                                Forms\Components\Fieldset::make('Pekerjaan/Perusahaan')
                                    ->schema([
                                        Forms\Components\TextInput::make('apl_company_name')
                                            ->label('Nama Perusahaan')
                                            ->formatStateUsing(fn ($record) => $record?->aplData?->company_name)
                                            ->disabled(),
                                        Forms\Components\TextInput::make('apl_job_title')
                                            ->label('Jabatan')
                                            ->formatStateUsing(fn ($record) => $record?->aplData?->job_title)
                                            ->disabled(),
                                        Forms\Components\TextInput::make('apl_company_contact')
                                            ->label('Kontak Perusahaan')
                                            ->formatStateUsing(fn ($record) => $record?->aplData?->company_contact)
                                            ->disabled(),
                                        Forms\Components\TextInput::make('apl_company_postal_code')
                                            ->label('Kode Pos Perusahaan')
                                            ->formatStateUsing(fn ($record) => $record?->aplData?->company_postal_code)
                                            ->disabled(),
                                        Forms\Components\Textarea::make('apl_company_address')
                                            ->label('Alamat Perusahaan')
                                            ->formatStateUsing(fn ($record) => $record?->aplData?->company_address)
                                            ->disabled()
                                            ->columnSpanFull(),
                                    ])->columns(2),
                            ])
                            ->visible(fn ($record) => $record && $record->aplData !== null),

                        Forms\Components\Tabs\Tab::make('APL.02')
                            ->icon('heroicon-o-check-badge')
                            ->schema([
                                Forms\Components\Repeater::make('selfAssessments')
                                    ->relationship(modifyQueryUsing: fn (Builder $query) => $query->with(['element.performanceCriteria']))
                                    ->label('Daftar Penilaian Mandiri')
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->schema([
                                        Forms\Components\Grid::make(12)
                                            ->schema([
                                                Forms\Components\Placeholder::make('element_info')
                                                    ->label('Elemen Kompetensi')
                                                    ->content(fn($record) => $record?->element?->title ?? '-')
                                                    ->columnSpan(9),
                                                
                                                Forms\Components\Checkbox::make('is_competent')
                                                    ->label('Kompeten')
                                                    ->disabled()
                                                    ->columnSpan(3),
                                            ]),
                                        
                                        Forms\Components\Placeholder::make('kuk_info')
                                            ->label('Kriteria Unjuk Kerja (KUK)')
                                            ->content(fn($record) => new \Illuminate\Support\HtmlString("
                                                <div class='mt-1 text-xs text-gray-500 bg-gray-50/50 p-3 rounded-lg border border-gray-100 italic'>
                                                    " . ($record?->element?->performanceCriteria?->map(fn($kuk) => "• " . e($kuk->name))->implode('<br>') ?? '-') . "
                                                </div>
                                            "))
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1)
                                    ->collapsible()
                                    ->collapsed(false),
                            ]),

                        Forms\Components\Tabs\Tab::make('Berkas & Portofolio')
                            ->icon('heroicon-o-folder-open')
                            ->schema([
                                Forms\Components\Repeater::make('filteredUserFiles')
                                    ->relationship(modifyQueryUsing: fn (Builder $query) => $query->with(['documentList']))
                                    ->label('Berkas Persyaratan & Portofolio')
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->schema([
                                        Forms\Components\Grid::make(12)
                                            ->schema([
                                                Forms\Components\Placeholder::make('doc_title')
                                                    ->label('Nama Dokumen')
                                                    ->content(fn($record) => $record->documentList?->title ?? 'Dokumen Tanpa Judul')
                                                    ->columnSpan(6),
                                                
                                                Forms\Components\TextInput::make('status')
                                                    ->label('Status Verifikasi')
                                                    ->formatStateUsing(fn($state) => $state instanceof \App\Enums\EvidenceStatus ? $state->getLabel() : ($state ?? '-'))
                                                    ->disabled()
                                                    ->columnSpan(4),

                                                Forms\Components\Actions::make([
                                                    Forms\Components\Actions\Action::make('view_file')
                                                        ->label('Lihat')
                                                        ->icon('heroicon-m-eye')
                                                        ->color('info')
                                                        ->url(fn($record) => \Illuminate\Support\Facades\Storage::url($record->upload_document))
                                                        ->openUrlInNewTab(),
                                                ])->columnSpan(2)->alignEnd(),
                                            ]),
                                    ])
                                    ->columns(1)
                                    ->collapsible(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Pencetakan Dokumen')
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
                                                ])->columnSpanFull(),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Dokumen AK')
                                            ->schema([
                                                Forms\Components\Actions::make([
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
                            ]),
                    ])->columnSpanFull(),
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
