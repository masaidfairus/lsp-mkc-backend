<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrVaResource\Pages;
use App\Models\AssessmentValidation;
use App\Models\PeerValidation;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FrVaResource extends Resource
{
    protected static ?string $model = AssessmentValidation::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'MKVA';
    protected static ?string $navigationLabel = 'FR.VA';
    protected static ?string $modelLabel = 'FR.VA';
    protected static ?string $pluralModelLabel = 'FR.VA';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.VA - Memberikan Kontribusi dalam Validasi Asesmen')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->label('Pilih Registrasi')
                                    ->options(function () {
                                        $regs = AssessmentRegistration::with(['candidate', 'scheme'])->get();
                                        $occ = [];
                                        return $regs->mapWithKeys(function ($reg) use (&$occ) {
                                            $key = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
                                            $occ[$key] = ($occ[$key] ?? 0) + 1;
                                            return [$reg->id => ($reg->candidate?->name ?? '-') . ' — ' . ($reg->scheme?->name ?? '-') . " #{$occ[$key]}"];
                                        });
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set) {
                                        if (!$state) return;
                                        $reg = AssessmentRegistration::with('scheme')->find($state);
                                        if ($reg && $reg->scheme) {
                                            $set('validation_data.header.scheme_name', $reg->scheme->name);
                                            $set('validation_data.header.scheme_number', $reg->scheme->code);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!$state) return;
                                        $reg = AssessmentRegistration::with('scheme')->find($state);
                                        if ($reg && $reg->scheme) {
                                            $set('validation_data.header.scheme_name', $reg->scheme->name);
                                            $set('validation_data.header.scheme_number', $reg->scheme->code);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.VA'),

                        Forms\Components\Group::make()->statePath('validation_data')
                            ->schema([

                                // HEADER
                                Forms\Components\Section::make('Header Validasi')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TagsInput::make('header.validation_team')->label('Tim Validasi'),
                                        Forms\Components\DatePicker::make('header.day_date')->label('Hari / Tanggal')->native(false),
                                        Forms\Components\TextInput::make('header.location')->label('Tempat'),
                                        Forms\Components\CheckboxList::make('header.period')
                                            ->label('Periode Validasi')
                                            ->options([
                                                'before_assessment' => 'Sebelum Asesmen',
                                                'during_assessment' => 'Pada Saat Asesmen',
                                                'after_assessment'  => 'Setelah Asesmen',
                                            ])
                                            ->columns(3),
                                        Forms\Components\TextInput::make('header.scheme_name')->label('Nama Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('header.scheme_number')->label('Nomor Skema')->disabled()->dehydrated(),
                                        
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Select::make('header.validator_id')
                                                    ->label('Pilih Validator')
                                                    ->options(\App\Models\User::role(['asesor', 'admin'])->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->preload(),
                                                Forms\Components\Select::make('header.lead_assessor_id')
                                                    ->label('Pilih Lead Assessor')
                                                    ->options(\App\Models\User::role(['asesor', 'admin'])->pluck('name', 'id'))
                                                    ->searchable()
                                                    ->preload(),
                                            ])->columns(2)->columnSpanFull(),
                                    ]),

                                // 1. Menyiapkan Proses Validasi
                                Forms\Components\Section::make('1. Menyiapkan Proses Validasi')
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\CheckboxList::make('preparation.focus_and_purpose')
                                                    ->label('Tujuan dan Fokus Validasi')
                                                    ->options([
                                                        'Bagian dari Proses Penjaminan Mutu Organisasi' => 'Bagian dari Proses Penjaminan Mutu Organisasi',
                                                        'Mengantisipasi Risiko' => 'Mengantisipasi Risiko',
                                                        'Memenuhi Persyaratan BNSP' => 'Memenuhi Persyaratan BNSP',
                                                        'Memastikan Kesesuaian Bukti' => 'Memastikan Kesesuaian Bukti',
                                                        'Meningkatkan Kualitas Asesmen' => 'Meningkatkan Kualitas Asesmen',
                                                        'Mengevaluasi Kualitas Perangkat Asesmen' => 'Mengevaluasi Kualitas Perangkat Asesmen',
                                                    ]),
                                                Forms\Components\CheckboxList::make('preparation.validation_context')
                                                    ->label('Konteks Validasi')
                                                    ->options([
                                                        'Karakteristik Asesi' => 'Karakteristik Asesi',
                                                        'Lingkungan' => 'Lingkungan',
                                                        'Kebijakan / SOP' => 'Kebijakan / SOP',
                                                        'Bahan dan Alat Pendukung' => 'Bahan dan Alat Pendukung',
                                                        'Persyaratan Khusus' => 'Persyaratan Khusus',
                                                        'Lainnya' => 'Lainnya',
                                                    ]),
                                                Forms\Components\CheckboxList::make('preparation.validation_approach')
                                                    ->label('Pendekatan Validasi')
                                                    ->options([
                                                        'Asesmen Berbasis Kompetensi' => 'Asesmen Berbasis Kompetensi',
                                                        'Asesmen Portofolio' => 'Asesmen Portofolio',
                                                        'Asesmen RPL' => 'Asesmen RPL',
                                                        'Observasi Langsung' => 'Observasi Langsung',
                                                        'Pendekatan Tertulis/Lisan' => 'Pendekatan Tertulis/Lisan',
                                                    ]),
                                            ]),
                                    ]),

                                // Acuan Pembanding
                                Forms\Components\Section::make('Acuan Pembanding & Dokumen Terkait')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\CheckboxList::make('references.benchmarks')
                                            ->label('Acuan Pembanding')
                                            ->options([
                                                'Standar Kompetensi (SKKNI/SKK/SKI)' => 'Standar Kompetensi (SKKNI/SKK/SKI)',
                                                'Skema Sertifikasi' => 'Skema Sertifikasi',
                                                'SOP/IK' => 'SOP/IK',
                                                'Manual Instruction / Book Manual' => 'Manual Instruction / Book Manual',
                                                'Standar Kinerja' => 'Standar Kinerja',
                                            ]),

                                        Forms\Components\CheckboxList::make('references.materials')
                                            ->label('Dokumen Terkait dan Bahan-Bahan')
                                            ->options([
                                                'Perangkat Asesmen' => 'Perangkat Asesmen',
                                                'Peraturan / Pedoman' => 'Peraturan / Pedoman',
                                                'Bukti-bukti hasil asesmen' => 'Bukti-bukti hasil asesmen',
                                            ]),
                                    ]),

                                // Orang yang Relevan
                                Forms\Components\Section::make('Orang yang Relevan')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\CheckboxList::make('preparation.relevant_roles')
                                                    ->label('Peran Orang yang Relevan')
                                                    ->options([
                                                        'Asesor Kompetensi (wajib)' => 'Asesor Kompetensi (wajib)',
                                                        'Lead Asesor [Ketua TUK]' => 'Lead Asesor [Ketua TUK]',
                                                        'Manager, Supervisor' => 'Manager, Supervisor',
                                                        'Tenaga Ahli di bidangnya' => 'Tenaga Ahli di bidangnya',
                                                        'Koordinator Pelatihan' => 'Koordinator Pelatihan',
                                                        'Anggota Asosiasi Industry Profesi' => 'Anggota Asosiasi Industry Profesi',
                                                    ])
                                                    ->columns(2),
                                                Forms\Components\TagsInput::make('preparation.relevant_names_combined')
                                                    ->label('Nama Keseluruhan')
                                                    ->placeholder('Tambah nama baru...'),
                                            ]),
                                        Forms\Components\Textarea::make('preparation.confirmation_result')
                                            ->label('Hasil Konfirmasi/Diskusi (Tujuan, Fokus & Konteks)')
                                            ->rows(2),
                                    ]),

                                // 2. Memberikan Kontribusi
                                Forms\Components\Section::make('2. Memberikan Kontribusi dalam Proses Validasi')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('communication_skills')
                                            ->label('Keterampilan Komunikasi')
                                            ->options([
                                                'proactive' => 'PRO AKTIF',
                                                'active_listening' => 'ACTIVE LISTENING',
                                                'empathy' => 'Empati',
                                            ])
                                            ->columns(3),
                                    ]),

                                // Matriks Validasi
                                Forms\Components\Section::make('Aspek Dalam Kegiatan Validasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('validation_matrix')
                                            ->label('')
                                            ->default([
                                                ['no' => 1, 'aspect' => 'Meninjau Perangkat Asesmen', 'rules_of_evidence' => [], 'principles_of_assessment' => []],
                                                ['no' => 2, 'aspect' => 'Membandingkan Bukti-bukti Asesmen', 'rules_of_evidence' => [], 'principles_of_assessment' => []],
                                                ['no' => 3, 'aspect' => 'Mengevaluasi Hasil Asesmen', 'rules_of_evidence' => [], 'principles_of_assessment' => []],
                                            ])
                                            ->schema([
                                                Forms\Components\TextInput::make('no')->numeric()->disabled()->dehydrated(),
                                                Forms\Components\Textarea::make('aspect')->label('Aspek'),
                                                Forms\Components\CheckboxList::make('rules_of_evidence')
                                                    ->label('Aturan Bukti')
                                                    ->options(['v'=>'V','a'=>'A','t'=>'T','m'=>'M'])
                                                    ->columns(4),
                                                Forms\Components\CheckboxList::make('principles_of_assessment')
                                                    ->label('Prinsip Asesmen')
                                                    ->options(['v'=>'V','r'=>'R','f'=>'F','a'=>'A']) // Fixed typo: A instead of F twice
                                                    ->columns(4),
                                            ])
                                            ->columns(1)
                                            ->addActionLabel('Tambah Aspek'),
                                    ]),

                                // 3. Memberikan Kontribusi untuk Hasil Asesmen
                                Forms\Components\Section::make('3. Memberikan Kontribusi untuk Hasil Asesmen')
                                    ->schema([
                                        Forms\Components\Repeater::make('contributions.findings_list')
                                            ->label('Temuan Validasi')
                                            ->schema([Forms\Components\Textarea::make('finding')->rows(2)])
                                            ->addActionLabel('Tambah Temuan'),

                                        Forms\Components\Repeater::make('contributions.recommendations_list')
                                            ->label('Rekomendasi')
                                            ->schema([Forms\Components\Textarea::make('recommendation')->rows(2)])
                                            ->addActionLabel('Tambah Rekomendasi'),
                                    ]),

                                // Rencana Implementasi
                                Forms\Components\Section::make('Rencana Implementasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('implementation_plan')
                                            ->schema([
                                                Forms\Components\Textarea::make('activity')->label('Kegiatan Perbaikan')->rows(2),
                                                Forms\Components\DatePicker::make('completion_time')->label('Waktu Penyelesaian')->native(false),
                                                Forms\Components\TextInput::make('person_in_charge')->label('Penanggung Jawab'),
                                            ])
                                            ->columns(3)
                                            ->addActionLabel('Tambah Kegiatan'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi')->searchable(),
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema')->searchable(),
                Tables\Columns\TextColumn::make('registration.assessor.name')->label('Asesor')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrVas::route('/'),
            'create' => Pages\CreateFrVa::route('/create'),
            'edit' => Pages\EditFrVa::route('/{record}/edit'),
        ];
    }
}