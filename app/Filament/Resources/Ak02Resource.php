<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak02Resource\Pages;
use App\Models\Ak02;
use App\Models\AssessmentRegistration;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class Ak02Resource extends Resource
{
    protected static ?string $model = Ak02::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.02';
    protected static ?string $modelLabel = 'AK.02';
    protected static ?string $pluralModelLabel = 'AK.02';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->columnSpanFull()
                    ->schema([

                        // ── Registration Selector ──────────────────────────────
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(function () {
                                        $registrations = AssessmentRegistration::with(['candidate', 'scheme'])->get();
                                        $occurrences   = [];
                                        return $registrations->mapWithKeys(function ($reg) use (&$occurrences) {
                                            $key               = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
                                            $occurrences[$key] = ($occurrences[$key] ?? 0) + 1;
                                            $candidate         = $reg->candidate?->name ?? 'Unknown';
                                            $scheme            = $reg->scheme?->name ?? 'Tanpa Skema';
                                            return [$reg->id => "{$candidate} — {$scheme} #{$occurrences[$key]}"];
                                        });
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!$state) return;
                                        $reg = AssessmentRegistration::with(['scheme.competencyUnits', 'candidate', 'assessor'])->find($state);
                                        if (!$reg) return;

                                        $set('main_data.header.certification_scheme', $reg->scheme?->name ?? '');
                                        $set('main_data.header.scheme_code', $reg->scheme?->code ?? '');
                                        $set('main_data.header.assessee_name', $reg->candidate?->name ?? '');
                                        $set('main_data.header.tuk', $reg->tuk ?? '');

                                        if ($reg->assessor) {
                                            $set('main_data.header.assessor_name', $reg->assessor->name);
                                        }

                                        // Auto-populate competency units from scheme
                                        if ($reg->scheme?->competencyUnits) {
                                            $units = $reg->scheme->competencyUnits->values()->map(function ($unit, $idx) {
                                                return [
                                                    'unit_sequence'  => $idx + 1,
                                                    'unit_title'     => $unit->unit_title,
                                                    'evidence_methods' => [
                                                        'observasi_demonstrasi'    => false,
                                                        'portofolio'               => false,
                                                        'pernyataan_pihak_ketiga'  => false,
                                                        'pertanyaan_wawancara'     => false,
                                                        'pertanyaan_lisan'         => false,
                                                        'pertanyaan_tertulis'      => false,
                                                        'proyek_kerja'             => false,
                                                        'lainnya'                  => false,
                                                    ],
                                                ];
                                            })->toArray();
                                            $set('main_data.competency_units', $units);
                                        }
                                    }),
                            ]),

                        // ── Main main_data group ───────────────────────────────
                        Forms\Components\Group::make()
                            ->statePath('main_data')
                            ->schema([

                                // ── Header ────────────────────────────────────
                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('certification_scheme')
                                                    ->label('Skema Sertifikasi')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Forms\Components\TextInput::make('scheme_code')
                                                    ->label('Kode Skema')
                                                    ->disabled()
                                                    ->dehydrated(),
                                            ]),
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('tuk')
                                                    ->label('TUK')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Forms\Components\TextInput::make('assessor_name')
                                                    ->label('Nama Asesor')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Forms\Components\TextInput::make('assessee_name')
                                                    ->label('Nama Asesi')
                                                    ->disabled()
                                                    ->dehydrated(),
                                            ]),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('assessment_date_start')
                                                    ->label('Tanggal Asesmen (Mulai)')
                                                    ->required(),
                                                Forms\Components\DatePicker::make('assessment_date_end')
                                                    ->label('Tanggal Asesmen (Selesai)')
                                                    ->required(),
                                            ]),
                                    ]),

                                // ── Unit Kompetensi & Metode Bukti ────────────
                                Forms\Components\Section::make('Unit Kompetensi & Metode Bukti')
                                    ->schema([
                                        Forms\Components\Repeater::make('competency_units')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Grid::make(12)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('unit_sequence')
                                                            ->label('No.')
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->columnSpan(1),
                                                        Forms\Components\TextInput::make('unit_title')
                                                            ->label('Judul Unit Kompetensi')
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->columnSpan(11),
                                                    ]),

                                                Forms\Components\Fieldset::make('Metode Bukti')
                                                    ->schema([
                                                        Forms\Components\Group::make()
                                                            ->statePath('evidence_methods')
                                                            ->schema([
                                                                Forms\Components\Grid::make(4)
                                                                    ->schema([
                                                                        Forms\Components\Toggle::make('observasi_demonstrasi')
                                                                            ->label('Observasi / Demonstrasi')
                                                                            ->inline(false),
                                                                        Forms\Components\Toggle::make('portofolio')
                                                                            ->label('Portofolio')
                                                                            ->inline(false),
                                                                        Forms\Components\Toggle::make('pernyataan_pihak_ketiga')
                                                                            ->label('Pernyataan Pihak Ketiga')
                                                                            ->inline(false),
                                                                        Forms\Components\Toggle::make('pertanyaan_wawancara')
                                                                            ->label('Pertanyaan Wawancara')
                                                                            ->inline(false),
                                                                        Forms\Components\Toggle::make('pertanyaan_lisan')
                                                                            ->label('Pertanyaan Lisan')
                                                                            ->inline(false),
                                                                        Forms\Components\Toggle::make('pertanyaan_tertulis')
                                                                            ->label('Pertanyaan Tertulis')
                                                                            ->inline(false),
                                                                        Forms\Components\Toggle::make('proyek_kerja')
                                                                            ->label('Proyek / Penugasan Kerja')
                                                                            ->inline(false),
                                                                        Forms\Components\Toggle::make('lainnya')
                                                                            ->label('Lainnya')
                                                                            ->inline(false),
                                                                    ]),
                                                            ]),
                                                    ]),
                                            ])
                                            ->addActionLabel('Tambah Unit Kompetensi')
                                            ->reorderable(false)
                                            ->defaultItems(0)
                                            ->collapsible()
                                            ->collapsed(false),
                                    ]),

                                // ── Ringkasan Asesmen ─────────────────────────
                                Forms\Components\Section::make('Ringkasan Asesmen')
                                    ->statePath('assessment_summary')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_competent')
                                            ->label('Kompeten? (Aktif = Kompeten, Non-aktif = Belum Kompeten)')
                                            ->onIcon('heroicon-m-check')
                                            ->offIcon('heroicon-m-x-mark')
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->inline(false),
                                        Forms\Components\Textarea::make('follow_up_action')
                                            ->label('Tindak Lanjut yang Dibutuhkan')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('assessor_comment')
                                            ->label('Komentar / Observasi Asesor')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                // ── Tanda Tangan ──────────────────────────────
                                Forms\Components\Section::make('Tanda Tangan')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([

                                                // Asesi signature (read-only, filled by mobile)
                                                Forms\Components\Section::make('Tanda Tangan Asesi')
                                                    ->statePath('signatures.assessee')
                                                    ->schema([
                                                        Forms\Components\Placeholder::make('_info')
                                                            ->label('')
                                                            ->content(new HtmlString('<span class="text-sm text-gray-400 italic">Tanda tangan asesi diisi melalui aplikasi mobile.</span>')),
                                                        Forms\Components\Hidden::make('name'),
                                                        Forms\Components\Hidden::make('date'),
                                                        Forms\Components\Hidden::make('signature'),
                                                    ]),

                                                // Asesor signature
                                                Forms\Components\Section::make('Tanda Tangan Asesor')
                                                    ->statePath('signatures.assessor')
                                                    ->schema([
                                                        Forms\Components\Grid::make(2)
                                                            ->schema([
                                                                Forms\Components\Select::make('_signature_id')
                                                                    ->label('Pilih Tanda Tangan Asesor')
                                                                    ->options(UserSignature::pluck('name', 'id'))
                                                                    ->reactive()
                                                                    ->dehydrated(false)
                                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                                        $sig = UserSignature::find($state);
                                                                        if ($sig) {
                                                                            $set('name', $sig->name);
                                                                            $set('reg_number', $sig->reg_number ?? '');
                                                                            $set('signature', $sig->signature_image);
                                                                            $set('qr_preview', $sig->qr_code);
                                                                        }
                                                                    }),
                                                                Forms\Components\TextInput::make('date')
                                                                    ->label('Tanggal TTD')
                                                                    ->type('date')
                                                                    ->default(fn () => now()->toDateString()),
                                                            ]),
                                                        Forms\Components\Hidden::make('name'),
                                                        Forms\Components\Hidden::make('reg_number'),
                                                        Forms\Components\Hidden::make('signature'),
                                                        Forms\Components\Placeholder::make('qr_preview')
                                                            ->label('QR Code Asesor')
                                                            ->content(fn ($get) => $get('qr_preview')
                                                                ? new HtmlString("<img src='{$get('qr_preview')}' width='100' />")
                                                                : '-'),
                                                        Forms\Components\Placeholder::make('_sig_preview')
                                                            ->label('Preview Tanda Tangan')
                                                            ->content(fn ($get) => $get('signature')
                                                                ? new HtmlString("<img src='{$get('signature')}' style='max-height:90px;border:1px solid #e5e7eb;border-radius:6px;padding:4px;' />")
                                                                : new HtmlString('<span class="text-sm text-gray-400 italic">Pilih tanda tangan untuk preview.</span>')),
                                                    ]),
                                            ]),
                                    ]),

                                // ── Lampiran (statis) ─────────────────────────
                                Forms\Components\Section::make('Lampiran')
                                    ->schema([
                                        Forms\Components\Placeholder::make('_attachments')
                                            ->label('')
                                            ->content(new HtmlString('
                                                <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                                                    <li>Dokumen APL 01 peserta</li>
                                                    <li>Dokumen APL 02 peserta</li>
                                                    <li>Bukti-bukti berkualitas peserta</li>
                                                    <li>Tinjauan proses asesmen</li>
                                                </ul>
                                            ')),
                                    ])
                                    ->collapsible()
                                    ->collapsed(true),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('Reg. ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->limit(35),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAk02s::route('/'),
        ];
    }
}
