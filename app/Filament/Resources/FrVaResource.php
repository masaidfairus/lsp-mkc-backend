<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrVaResource\Pages;
use App\Models\PeerValidation;
use App\Models\AssessmentRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FrVaResource extends Resource
{
    protected static ?string $model = PeerValidation::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'VA';
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
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!$state) return;
                                        $reg = AssessmentRegistration::with('scheme')->find($state);
                                        if ($reg && $reg->scheme) {
                                            $set('fr_va_data.header.scheme_name', $reg->scheme->name);
                                            $set('fr_va_data.header.scheme_number', $reg->scheme->code);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.VA'),

                        Forms\Components\Group::make()->statePath('fr_va_data')
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
                                    ]),

                                // Acuan Pembanding
                                Forms\Components\Section::make('Acuan Pembanding & Dokumen Terkait')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\CheckboxList::make('references.benchmarks')
                                            ->label('Acuan Pembanding')
                                            ->options([
                                                'competency_standards' => 'Standar Kompetensi (SKKNI/SKK/SKI)',
                                                'certification_scheme' => 'Skema Sertifikasi',
                                                'sop_ik' => 'SOP/IK',
                                                'manual_instruction' => 'Manual Instruction / Book Manual',
                                                'performance_standard' => 'Standar Kinerja',
                                            ]),

                                        Forms\Components\CheckboxList::make('references.materials')
                                            ->label('Dokumen Terkait dan Bahan-Bahan')
                                            ->options([
                                                'assessment_instruments' => 'Perangkat Asesmen',
                                                'regulations_guidelines' => 'Peraturan / Pedoman',
                                                'assessment_results_evidence' => 'Bukti-bukti hasil asesmen',
                                            ]),
                                    ]),

                                // 1. Menyiapkan Proses Validasi
                                Forms\Components\Section::make('1. Menyiapkan Proses Validasi')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('preparation.focus_and_purpose')
                                            ->label('Tujuan dan Fokus Validasi')
                                            ->options([
                                                'quality_assurance' => 'Bagian dari Proses Penjaminan Mutu Organisasi',
                                                'risk_anticipation' => 'Mengantisipasi Risiko',
                                                'bnsp_requirements' => 'Memenuhi Persyaratan BNSP',
                                                'evidence_consistency' => 'Memastikan Kesesuaian Bukti',
                                                'assessment_quality_improvement' => 'Meningkatkan Kualitas Asesmen',
                                                'evaluate_instrument_quality' => 'Mengevaluasi Kualitas Perangkat Asesmen',
                                            ])
                                            ->columns(2),
                                    ]),

                                // Orang yang Relevan
                                Forms\Components\Section::make('Orang yang Relevan')
                                    ->schema([
                                        Forms\Components\Repeater::make('preparation.relevant_persons')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('role')->label('Peran'),
                                                Forms\Components\TextInput::make('name')->label('Nama'),
                                                Forms\Components\Textarea::make('confirmation_result')->label('Hasil Konfirmasi/Diskusi')->rows(2),
                                            ])
                                            ->columns(3)
                                            ->addActionLabel('Tambah'),
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
                                            ->schema([
                                                Forms\Components\TextInput::make('no')->numeric()->disabled()->dehydrated(),
                                                Forms\Components\Textarea::make('aspect')->label('Aspek'),
                                                Forms\Components\CheckboxList::make('rules_of_evidence')
                                                    ->label('Aturan Bukti')
                                                    ->options(['v'=>'V','a'=>'A','t'=>'T','m'=>'M'])
                                                    ->columns(4),
                                                Forms\Components\CheckboxList::make('principles_of_assessment')
                                                    ->label('Prinsip Asesmen')
                                                    ->options(['v'=>'V','r'=>'R','f'=>'F','a'=>'F'])
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
                Tables\Columns\TextColumn::make('scheme.name')->label('Skema'),
                Tables\Columns\TextColumn::make('mainAssessor.name')->label('Asesor Utama'),
                Tables\Columns\TextColumn::make('peerAssessor.name')->label('Peer Assessor'),
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