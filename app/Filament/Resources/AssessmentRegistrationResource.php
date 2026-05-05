<?php

namespace App\Filament\Resources;

use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
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
    protected static ?string $globalSearchEloquentKey = null;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pendaftaran Sertifikasi';
    protected static ?string $modelLabel = 'Pendaftaran Sertifikasi';
    protected static ?string $pluralModelLabel = 'Pendaftaran Sertifikasi';
    protected static ?string $navigationGroup = 'Pendaftaran';


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['candidate:id,name,email,identity_type,identity_number,registration_number', 'scheme:id,name', 'aplData']);

        if (!auth()->user()->hasRole(['admin', 'superadmin'])) {
            $query->where('assessor_id', auth()->id());
        }

        return $query;
    }

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
                                            ->preload(false)
                                            ->disabled(fn() => auth()->user()->hasRole('asesor')),
                                        Forms\Components\Select::make('status_apl01')
                                            ->options([
                                                Apl01Status::PENDING->value => Apl01Status::PENDING->getLabel(),
                                                Apl01Status::APPROVED->value => Apl01Status::APPROVED->getLabel(),
                                                Apl01Status::REJECTED->value => Apl01Status::REJECTED->getLabel(),
                                            ])
                                            ->label('Status APL.01')
                                            ->required()
                                            ->hidden(fn() => auth()->user()->hasRole('asesor')),
                                    ]),
                                Forms\Components\Textarea::make('admin_notes')
                                    ->label('Catatan Admin')
                                    ->placeholder('Tambahkan catatan admin di sini...')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->hidden(fn() => auth()->user()->hasRole('asesor')),

                                Forms\Components\Section::make('Validasi Evaluasi Mandiri (APL.02)')
                                    ->hidden(fn() => auth()->user()->hasRole('admin'))
                                    ->schema([
                                        Forms\Components\Placeholder::make('apl02_warning')
                                            ->label('')
                                            ->content(new \Illuminate\Support\HtmlString('<div style="color: #b45309; background-color: #fffbeb; padding: 12px; border-radius: 8px; border: 1px solid #fde68a;">Asesi belum mengisi Form Asesmen Mandiri (APL.02). Status tidak dapat diubah.</div>'))
                                            ->visible(fn($record) => !$record || $record->selfAssessments->isEmpty())
                                            ->columnSpanFull(),
                                        Forms\Components\Select::make('status_apl02')
                                            ->options(Apl02Status::class)
                                            ->label('Status APL.02')
                                            ->required()
                                            ->visible(fn($record) => $record && $record->status_apl02 !== null),
                                    ])->columns(1),

                                Forms\Components\Section::make('Penerbitan Sertifikat')
                                    ->schema([
                                        Forms\Components\FileUpload::make('certificate')
                                            ->label('Upload Sertifikat (PDF/Image)')
                                            ->directory('certificates')
                                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                                            ->maxSize(5120) // 5MB max
                                            ->visible(fn() => auth()->user()->hasRole(['superadmin', 'admin']))
                                            ->hint('Pastikan semua tahapan asesmen sudah selesai sebelum mengunggah sertifikat.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('APL.01')
                            ->hidden(fn() => auth()->user()->hasRole('asesor'))
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('apl_candidate_name')
                                        ->label('Nama Lengkap')
                                        ->afterStateHydrated(fn(Forms\Components\TextInput $component, $record) => $component->state($record?->candidate?->name))
                                        ->disabled()
                                        ->dehydrated(false),
                                    Forms\Components\TextInput::make('apl_candidate_email')
                                        ->label('Email')
                                        ->afterStateHydrated(fn(Forms\Components\TextInput $component, $record) => $component->state($record?->candidate?->email ?? '-'))
                                        ->disabled()
                                        ->dehydrated(false),
                                    Forms\Components\TextInput::make('apl_candidate_registration_number')
                                        ->label('No. Registrasi')
                                        ->afterStateHydrated(fn(Forms\Components\TextInput $component, $record) => $component->state($record?->candidate?->registration_number ?: 'Belum ada'))
                                        ->disabled()
                                        ->dehydrated(false),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('apl_identity_type')
                                        ->label('Jenis Identitas')
                                        ->afterStateHydrated(fn(Forms\Components\TextInput $component, $record) => $component->state(match ($record?->candidate?->identity_type) {
                                            'id' => 'KTP',
                                            'passport' => 'Passport',
                                            default => $record?->candidate?->identity_type ?? '-',
                                        }))
                                        ->disabled()
                                        ->dehydrated(false),
                                    Forms\Components\TextInput::make('apl_identity_number')
                                        ->label('No. KTP / Paspor')
                                        ->formatStateUsing(fn($record) => $record?->aplData?->identity_number ?? $record?->candidate?->identity_number ?? '-')
                                        ->disabled()
                                        ->helperText('Nomor identitas yang diisi pada formulir APL.01'),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('apl_gender')
                                        ->label('Jenis Kelamin')
                                        ->formatStateUsing(fn($record) => $record?->aplData?->gender?->getLabel() ?? '-')
                                        ->disabled(),
                                    Forms\Components\TextInput::make('apl_place_of_birth')
                                        ->label('Tempat Lahir')
                                        ->formatStateUsing(fn($record) => $record?->aplData?->place_of_birth)
                                        ->disabled(),
                                    Forms\Components\TextInput::make('apl_date_of_birth')
                                        ->label('Tanggal Lahir')
                                        ->formatStateUsing(fn($record) => $record?->aplData?->date_of_birth?->format('d/m/Y'))
                                        ->disabled(),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('apl_phone_number')
                                        ->label('No. Telepon')
                                        ->formatStateUsing(fn($record) => $record?->aplData?->phone_number)
                                        ->disabled(),
                                    Forms\Components\TextInput::make('apl_education')
                                        ->label('Pendidikan Terakhir')
                                        ->formatStateUsing(function ($record) {
                                            $data = $record?->aplData;
                                            if (!$data)
                                                return '-';
                                            if ($data->education_qualifications)
                                                return $data->education_qualifications;
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
                                        ->formatStateUsing(fn($record) => $record?->aplData?->address)
                                        ->disabled(),
                                    Forms\Components\TextInput::make('apl_home_postal_code')
                                        ->label('Kode Pos Rumah')
                                        ->formatStateUsing(fn($record) => $record?->aplData?->home_postal_code)
                                        ->disabled(),
                                ]),
                                Forms\Components\Fieldset::make('Pekerjaan/Perusahaan')
                                    ->schema([
                                        Forms\Components\TextInput::make('apl_company_name')
                                            ->label('Nama Perusahaan')
                                            ->formatStateUsing(fn($record) => $record?->aplData?->company_name)
                                            ->disabled(),
                                        Forms\Components\TextInput::make('apl_job_title')
                                            ->label('Jabatan')
                                            ->formatStateUsing(fn($record) => $record?->aplData?->job_title)
                                            ->disabled(),
                                        Forms\Components\TextInput::make('apl_company_contact')
                                            ->label('No. Telepon Kantor')
                                            ->formatStateUsing(fn($record) => $record?->aplData?->company_contact)
                                            ->disabled(),
                                        Forms\Components\TextInput::make('apl_company_fax')
                                            ->label('Fax Perusahaan')
                                            ->formatStateUsing(fn($record) => $record?->aplData?->company_fax ?? '-')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('apl_company_email')
                                            ->label('Email Perusahaan')
                                            ->formatStateUsing(fn($record) => $record?->aplData?->company_email ?? '-')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('apl_company_postal_code')
                                            ->label('Kode Pos Perusahaan')
                                            ->formatStateUsing(fn($record) => $record?->aplData?->company_postal_code)
                                            ->disabled(),
                                        Forms\Components\Textarea::make('apl_company_address')
                                            ->label('Alamat Perusahaan')
                                            ->formatStateUsing(fn($record) => $record?->aplData?->company_address)
                                            ->disabled()
                                            ->columnSpanFull(),
                                    ])->columns(2),
                            ])
                            ->visible(fn($record) => $record && $record->aplData !== null),

                        Forms\Components\Tabs\Tab::make('APL.02')
                            ->icon('heroicon-o-check-badge')
                            ->hidden(fn() => auth()->user()->hasRole('admin'))
                            ->schema([
                                Forms\Components\ViewField::make('apl02_detail')
                                    ->view('filament.forms.components.apl02-summary')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Berkas & Portofolio')
                            ->icon('heroicon-o-folder-open')
                            ->schema([
                                Forms\Components\Repeater::make('filteredUserFiles')
                                    ->relationship(modifyQueryUsing: fn(Builder $query) => $query->with(['documentList']))
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

                                                Forms\Components\Placeholder::make('view_file_link')
                                                    ->label('Aksi')
                                                    ->content(fn($record) => $record?->upload_document
                                                        ? new \Illuminate\Support\HtmlString("<a href='" . route('private.file.view', ['record' => $record->id]) . "' target='_blank' class='inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-white transition-all bg-info-600 rounded-lg shadow-sm hover:bg-info-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-info-600'>
                                                            <div class='flex items-center gap-1'>
                                                                <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'></path><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'></path></svg>
                                                                Lihat
                                                            </div>
                                                        </a>")
                                                        : '-')
                                                    ->columnSpan(2),
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
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('apl01.form', ['registration' => $record->id]) : '#')
                                                        ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_apl02_pdf')
                                                        ->label('Cetak Dokumen APL.02')
                                                        ->color('success')
                                                        ->icon('heroicon-o-printer')
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('apl02.form', ['registration' => $record->id]) : '#')
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
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ak01.form', ['registration' => $record->id]) : '#')
                                                        ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ak02_pdf')
                                                        ->label('Cetak Dokumen AK.02')
                                                        ->color('success')
                                                        ->icon('heroicon-o-printer')
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ak02.form', ['registration' => $record->id]) : '#')
                                                        ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ak03_pdf')
                                                        ->label('Cetak Dokumen AK.03')
                                                        ->color('success')
                                                        ->icon('heroicon-o-printer')
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ak03.form', ['registration' => $record->id]) : '#')
                                                        ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ak04_pdf')
                                                        ->label('Cetak Dokumen AK.04')
                                                        ->color('success')
                                                        ->icon('heroicon-o-printer')
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ak04.form', ['registration' => $record->id]) : '#')
                                                        ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ak05_pdf')
                                                        ->label('Cetak Dokumen AK.05')
                                                        ->color('success')
                                                        ->icon('heroicon-o-printer')
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ak05.form', ['registration' => $record->id]) : '#')
                                                        ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ak06_pdf')
                                                        ->label('Cetak Dokumen AK.06')
                                                        ->color('success')
                                                        ->icon('heroicon-o-printer')
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ak06.form', ['registration' => $record->id]) : '#')
                                                        ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_ak07_pdf')
                                                        ->label('Cetak Dokumen AK.07')
                                                        ->color('success')
                                                        ->icon('heroicon-o-printer')
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ak07.form', ['registration' => $record->id]) : '#')
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
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia01.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia02_pdf')
                                                                ->label('Cetak Dokumen IA.02')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia02.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia03_pdf')
                                                                ->label('Cetak Dokumen IA.03')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia03.form', ['registration' => $record->id]) : '#')
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
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia04a.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia04b_pdf')
                                                                ->label('Cetak Dokumen IA.04b')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia04b.form', ['registration' => $record->id]) : '#')
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
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia05a.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia05b_pdf')
                                                                ->label('Cetak Dokumen IA.05b')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia05b.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia05c_pdf')
                                                                ->label('Cetak Dokumen IA.05c')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia05c.form', ['registration' => $record->id]) : '#')
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
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia06a.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia06b_pdf')
                                                                ->label('Cetak Dokumen IA.06b')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia06b.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia06c_pdf')
                                                                ->label('Cetak Dokumen IA.06c')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia06c.form', ['registration' => $record->id]) : '#')
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
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia07.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia08_pdf')
                                                                ->label('Cetak Dokumen IA.08')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia08.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia09_pdf')
                                                                ->label('Cetak Dokumen IA.09')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia09.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia10_pdf')
                                                                ->label('Cetak Dokumen IA.10')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia10.form', ['registration' => $record->id]) : '#')
                                                                ->openUrlInNewTab(),
                                                            Forms\Components\Actions\Action::make('generate_ia11_pdf')
                                                                ->label('Cetak Dokumen IA.11')
                                                                ->color('success')
                                                                ->icon('heroicon-o-printer')
                                                                ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('ia11.form', ['registration' => $record->id]) : '#')
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
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('mapa01.form', ['registration' => $record->id]) : '#')
                                                        ->openUrlInNewTab(),
                                                    Forms\Components\Actions\Action::make('generate_mapa02_pdf')
                                                        ->label('Cetak Dokumen MAPA.02')
                                                        ->color('success')
                                                        ->icon('heroicon-o-printer')
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('mapa02.form', ['registration' => $record->id]) : '#')
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
                                                        ->url(fn($record) => $record ? \Illuminate\Support\Facades\URL::signedRoute('frva.print', ['registration' => $record->id]) : '#')
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
            ->deferLoading()
            ->defaultPaginationPageOption(10)
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
                Tables\Columns\TextColumn::make('admin_notes')
                    ->label('Catatan Admin')
                    ->limit(50)
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
