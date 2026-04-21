<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak07Resource\Pages;
use App\Models\Ak07;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class Ak07Resource extends Resource
{
    protected static ?string $model = Ak07::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.07';
    protected static ?string $modelLabel = 'AK.07';
    protected static ?string $pluralModelLabel = 'AK.07';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->columnSpanFull()
                    ->schema([

                        // ── 1. Registration selector with scheme name + occurrence ──────
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(function () {
                                        $registrations = \App\Models\AssessmentRegistration::with(['candidate', 'scheme'])->get();
                                        $occurrences   = [];
                                        return $registrations->mapWithKeys(function ($reg) use (&$occurrences) {
                                            $key          = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
                                            $occurrences[$key] = ($occurrences[$key] ?? 0) + 1;
                                            $candidate    = $reg->candidate?->name ?? 'Unknown';
                                            $scheme       = $reg->scheme?->name ?? 'Tanpa Skema';
                                            return [$reg->id => "{$candidate} — {$scheme} #{$occurrences[$key]}"];
                                        });
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $reg = \App\Models\AssessmentRegistration::with(['scheme', 'candidate'])->find($state);
                                            if ($reg) {
                                                if ($reg->scheme) {
                                                    $set('ak07_data.certification_scheme.name', $reg->scheme->name);
                                                    $set('ak07_data.certification_scheme.code', $reg->scheme->code);
                                                }
                                                $set('ak07_data.asesi_name', $reg->candidate?->name ?? '');
                                            }
                                        }
                                    }),
                            ]),

                        // ── Main ak07_data group ───────────────────────────────────────
                        Forms\Components\Group::make()
                            ->statePath('ak07_data')
                            ->schema([

                                // ── Skema Sertifikasi ──────────────────────────────────
                                Forms\Components\Section::make('Skema Sertifikasi (KKNI/Okupasi/Klaster)')
                                    ->statePath('certification_scheme')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Judul')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('code')
                                            ->label('Nomor')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                    ])->columns(2),

                                // ── Header fields ──────────────────────────────────────
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\Select::make('tuk')
                                            ->label('TUK')
                                            ->options([
                                                'Sewaktu'      => 'Sewaktu',
                                                'Tempat Kerja' => 'Tempat Kerja',
                                                'Mandiri'      => 'Mandiri',
                                            ])
                                            ->required(),

                                        Forms\Components\TextInput::make('asesi_name')
                                            ->label('Nama Asesi')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),

                                        Forms\Components\Select::make('asesor_name')
                                            ->label('Nama Asesor')
                                            ->options(fn () => \App\Models\User::role('asesor')->pluck('name', 'name'))
                                            ->searchable()
                                            ->required(),

                                        // 2. Auto-fill assessment_date to today on create
                                        Forms\Components\DatePicker::make('assessment_date')
                                            ->label('Tanggal Asesmen')
                                            ->default(fn () => now()->toDateString())
                                            ->required(),
                                    ]),

                                // ── Potensi Asesi ──────────────────────────────────────
                                Forms\Components\Section::make('Potensi Asesi')
                                    ->schema([
                                        Forms\Components\Repeater::make('potential')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Hidden::make('label'),
                                                Forms\Components\Checkbox::make('is_checked')
                                                    ->label(fn ($get) => $get('label')),
                                            ])
                                            ->default(self::defaultPotential())
                                            ->addActionLabel('Tambah Potensi')
                                            ->reorderable(false)
                                            ->addable(true)
                                            ->deletable(false),
                                    ]),

                                // ── 3. Bagian A – redesigned for clean UI ──────────────
                                Forms\Components\Section::make('Bagian A – Penyesuaian sesuai karakteristik asesi')
                                    ->schema([
                                        Forms\Components\Repeater::make('part_a')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Hidden::make('id'),
                                                Forms\Components\Hidden::make('title'),

                                                // Removed grid to fix flexbox issues, elements stack nicely
                                                Forms\Components\Placeholder::make('title_display')
                                                    ->label('Karakteristik Asesi')
                                                    ->content(fn ($get) => $get('title')),

                                                Forms\Components\Toggle::make('needs_adjustment')
                                                    ->label('Diperlukan?')
                                                    ->onIcon('heroicon-m-check')
                                                    ->offIcon('heroicon-m-x-mark')
                                                    ->onColor('success')
                                                    ->offColor('gray')
                                                    ->inline(false),

                                                // 3. Using collapsed Section instead of Fieldset + live() to completely remove lag
                                                Forms\Components\Section::make('Keterangan Penyesuaian (Buka jika Diperlukan)')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('note')
                                                            ->label('')
                                                            ->schema([
                                                                Forms\Components\Hidden::make('title'),
                                                                Forms\Components\Hidden::make('custom_value')->default(''),
                                                                
                                                                Forms\Components\Checkbox::make('is_checked')
                                                                    ->label(fn ($get) => $get('title')),
                                                            ])
                                                            ->grid(2)
                                                            ->reorderable(false)
                                                            ->addable(false)
                                                            ->deletable(false),
                                                    ])
                                                    ->collapsed(),
                                            ])
                                            ->default(self::defaultPartA())
                                            ->reorderable(false)
                                            ->addable(false)
                                            ->deletable(false),
                                    ]),

                                // ── 4. Bagian B – with adjustment_plan column ───────────
                                Forms\Components\Section::make('Bagian B – Kesesuaian Rencana Asesmen')
                                    ->schema([
                                        Forms\Components\Repeater::make('part_b')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Hidden::make('id'),

                                                Forms\Components\Hidden::make('question'),

                                                // Removed grid to fix flexbox layout
                                                Forms\Components\Placeholder::make('question_display')
                                                    ->label('Aspek yang Diperiksa')
                                                    ->content(fn ($get) => $get('question')),

                                                Forms\Components\Toggle::make('answer')
                                                    ->label('Ya')
                                                    ->onIcon('heroicon-m-check')
                                                    ->offIcon('heroicon-m-x-mark')
                                                    ->onColor('success')
                                                    ->offColor('gray')
                                                    ->inline(false),

                                                // Wrap dependent fields in a collapsed section to avoid live() lag
                                                Forms\Components\Section::make('Detail Penyesuaian (Buka jika Tidak)')
                                                    ->schema([
                                                        Forms\Components\Grid::make(2)
                                                            ->schema([
                                                                Forms\Components\Textarea::make('adjustment_criteria_result')
                                                                    ->label('Hasil Penyesuaian sesuai Kriteria Asesi')
                                                                    ->rows(2)
                                                                    ->columnSpan(1),

                                                                Forms\Components\Textarea::make('adjustment_plan_result')
                                                                    ->label('Hasil Penyesuaian Rencana Asesmen')
                                                                    ->rows(2)
                                                                    ->columnSpan(1),
                                                            ]),

                                                        // Keputusan Penyesuaian
                                                        Forms\Components\Section::make('Keputusan Penyesuaian')
                                                            ->statePath('adjustment_decisions')
                                                            ->schema([
                                                                Forms\Components\TextInput::make('industry_standard')
                                                                    ->label('Standar Industri')
                                                                    ->placeholder('Isi jika diperlukan'),
                                                                Forms\Components\TextInput::make('sop')
                                                                    ->label('SOP')
                                                                    ->placeholder('Isi jika diperlukan'),
                                                                Forms\Components\TextInput::make('technical_regulation')
                                                                    ->label('Regulasi Teknis')
                                                                    ->placeholder('Isi jika diperlukan'),
                                                                Forms\Components\TextInput::make('assessment_method')
                                                                    ->label('Metode Asesmen')
                                                                    ->placeholder('Isi jika diperlukan'),
                                                                Forms\Components\TextInput::make('fr_ia_assessment_instrument')
                                                                    ->label('FR.IA / Instrumen Asesmen')
                                                                    ->placeholder('Isi jika diperlukan'),
                                                            ])->columns(2)->collapsed(),
                                                    ])
                                                    ->collapsed(),
                                            ])
                                            ->default(self::defaultPartB())
                                            ->reorderable(false)
                                            ->addable(false)
                                            ->deletable(false),
                                    ]),

                                // ── Hasil Penyesuaian Asesmen ──────────────────────────
                                Forms\Components\Section::make('Hasil Penyesuaian Asesmen')
                                    ->statePath('adjustment_results')
                                    ->schema([
                                        Forms\Components\Textarea::make('assessment_benchmark')
                                            ->label('Acuan Pembanding Asesmen')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('assessment_method')
                                            ->label('Metode Asesmen')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('assessment_instrument')
                                            ->label('Instrumen Asesmen')
                                            ->rows(3),
                                    ])->columns(3),

                                // ── 5. Tanda Tangan – ASESOR ONLY ──────────────────────
                                Forms\Components\Section::make('Tanda Tangan Asesor')
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->statePath('signatures')
                                            ->schema([
                                                Forms\Components\Group::make()
                                                    ->statePath('asesor')
                                                    ->schema([
                                                        Forms\Components\Grid::make(3)
                                                            ->schema([
                                                                Forms\Components\Select::make('_signature_id')
                                                                    ->label('Pilih Tanda Tangan Asesor')
                                                                    ->options(\App\Models\UserSignature::pluck('name', 'id'))
                                                                    ->reactive()
                                                                    ->dehydrated(false)
                                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                                        $sig = \App\Models\UserSignature::find($state);
                                                                        if ($sig) {
                                                                            $set('name', $sig->name);
                                                                            $set('signature', $sig->signature_image);
                                                                            $set('qr_preview', $sig->qr_code);
                                                                        }
                                                                    }),

                                                                // 2. Auto-fill asesor date to today
                                                                Forms\Components\TextInput::make('date')
                                                                    ->label('Tanggal TTD Asesor')
                                                                    ->type('date')
                                                                    ->default(fn () => now()->toDateString()),

                                                                Forms\Components\Placeholder::make('qr_preview')
                                                                    ->label('QR Code Asesor')
                                                                    ->content(fn ($get) => $get('qr_preview')
                                                                        ? new \Illuminate\Support\HtmlString("<img src='{$get('qr_preview')}' width='120' />")
                                                                        : '-'),
                                                            ]),

                                                        Forms\Components\Hidden::make('name'),
                                                        Forms\Components\Hidden::make('signature'),

                                                        // Signature image preview
                                                        Forms\Components\Placeholder::make('_sig_preview')
                                                            ->label('Preview Tanda Tangan Asesor')
                                                            ->content(fn ($get) => $get('signature')
                                                                ? new \Illuminate\Support\HtmlString("<img src='{$get('signature')}' style='max-height:100px;border:1px solid #e5e7eb;border-radius:6px;padding:4px;' />")
                                                                : new \Illuminate\Support\HtmlString('<span class="text-sm text-gray-400 italic">Pilih tanda tangan di atas untuk preview.</span>')),
                                                    ]),

                                                // Asesi signature hidden fields only (no UI – mobile fills this)
                                                Forms\Components\Group::make()
                                                    ->statePath('asesi')
                                                    ->schema([
                                                        Forms\Components\Hidden::make('name'),
                                                        Forms\Components\Hidden::make('date'),
                                                        Forms\Components\Hidden::make('signature'),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('confirmed_at')
                    ->label('Dikonfirmasi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ManageAk07s::route('/'),
        ];
    }

    // ── Defaults ───────────────────────────────────────────────────────────────

    private static function defaultPotential(): array
    {
        return [
            ['label' => 'Hasil pelatihan dan/atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi.', 'is_checked' => false],
            ['label' => 'Hasil pelatihan dan/atau pendidikan, dimana kurikulum belum berbasis kompetensi.', 'is_checked' => false],
            ['label' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi.', 'is_checked' => false],
            ['label' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.', 'is_checked' => false],
            ['label' => 'Pelatihan / belajar mandiri atau otodidak.', 'is_checked' => false],
        ];
    }

    private static function defaultPartA(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Keterbatasan asesi terhadap persyaratan bahasa, literasi, numerasi.',
                'needs_adjustment' => false,
                'note' => [
                    ['title' => 'Memerlukan dukungan pembaca, penerjemah, pelayan, penulis untuk merekam jawaban asesi.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Melakukan asesmen verbal (gunakan pertanyaan lisan/pertanyaan wawancara) dengan dilengkapi gambar diagram dan bentuk-bentuk visual.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan Hasil produksi.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan Ceklis observasi/demonstrasi.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan daftar instruksi terstruktur.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan instruksi-instruksi spesifik pada proyek yang dapat dilakukan pada berbagai tingkatan.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Lainnya', 'is_checked' => false, 'custom_value' => ''],
                ],
            ],
            [
                'id' => 2,
                'title' => 'Penyediaan dukungan pembaca, penerjemah, pelayan, penulis.',
                'needs_adjustment' => false,
                'note' => [
                    ['title' => 'Menggunakan pertanyaan lisan dengan dilengkapi gambar diagram dan bentuk-bentuk visual.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan pertanyaan wawancara dengan dilengkapi gambar diagram dan bentuk-bentuk visual.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Lainnya', 'is_checked' => false, 'custom_value' => ''],
                ],
            ],
            [
                'id' => 3,
                'title' => 'Penggunaan teknologi adaptif atau peralatan khusus. (Tidak dapat menggunakan teknologi adaptif, misal: mengoperasikan komputer dan printer, peralatan digital, dsb).',
                'needs_adjustment' => false,
                'note' => [
                    ['title' => 'Ceklis observasi/demonstrasi.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Pertanyaan lisan.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Pertanyaan tertulis.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Pertanyaan wawancara.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Daftar instruksi terstruktur.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Ceklis verifikasi portofolio.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan dukungan operator komputer.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Lainnya', 'is_checked' => false, 'custom_value' => ''],
                ],
            ],
            [
                'id' => 4,
                'title' => 'Pelaksanaan asesmen secara fleksibel karena alasan keletihan atau keperluan pengobatan.',
                'needs_adjustment' => false,
                'note' => [
                    ['title' => 'Menggunakan juru tulis.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan kameramen perekam vidio/audio.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Memperbolehkan periode waktu yang lebih panjang untuk menyelesaikan tugas pekerjaan dalam asesmen.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan instruksi-instruksi spesifik pada proyek yang dapat dilakukan pada berbagai tingkatan.', 'is_checked' => false, 'custom_value' => ''],
                ],
            ],
            [
                'id' => 5,
                'title' => 'Penyediaan peralatan asesmen berupa braille, audio/video-tape.',
                'needs_adjustment' => false,
                'note' => [
                    ['title' => 'Menggunakan pertanyaan lisan.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan pertanyaan wawancara.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Lainnya', 'is_checked' => false, 'custom_value' => ''],
                ],
            ],
            [
                'id' => 6,
                'title' => 'Penyesuaian tempat fisik/lingkungan asesmen.',
                'needs_adjustment' => false,
                'note' => [
                    ['title' => 'Pertanyaan lisan.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Pertanyaan tulis.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Pertanyaan wawancara.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Ceklis verifikasi portofolio.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Ceklis reviu produk.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Daftar instruksi terstruktur.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Lainnya', 'is_checked' => false, 'custom_value' => ''],
                ],
            ],
            [
                'id' => 7,
                'title' => 'Pertimbangan umur/usia lanjut/gender asesi. (Adanya perbedaan usia dengan asesor yang lebih muda).',
                'needs_adjustment' => false,
                'note' => [
                    ['title' => 'Menggunakan studi kasus/daftar instruksi terstruktur.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan instrumen asesmen dengan huruf normal jangan terlalu kecil.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan asesor dengan jenis kelamin yang sama dengan asesi.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan instrumen asesmen yang sama walaupun berbeda jenis kelamin (tidak boleh memberi tanda tambahan pada instrumen asesmen yang digunakan dengan tujuan untuk membedakan jenis kelamin).', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Lainnya', 'is_checked' => false, 'custom_value' => ''],
                ],
            ],
            [
                'id' => 8,
                'title' => 'Pertimbangan budaya/tradisi/agama.',
                'needs_adjustment' => false,
                'note' => [
                    ['title' => 'Menggunakan studi kasus daftar instruksi terstruktur.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan asesor tanpa pertimbangan budaya/tradisi/agama.', 'is_checked' => false, 'custom_value' => ''],
                    ['title' => 'Menggunakan instrumen asesmen yang sama walaupun berbeda budaya/tradisi/agama.', 'is_checked' => false, 'custom_value' => ''],
                ],
            ],
        ];
    }

    private static function defaultPartB(): array
    {
        $emptyDecisions = [
            'industry_standard'            => '',
            'sop'                          => '',
            'technical_regulation'         => '',
            'assessment_method'            => '',
            'fr_ia_assessment_instrument'  => '',
        ];

        return [
            [
                'id'                       => 1,
                'question'                 => 'Apakah rekaman rencana asesmen tervalidasi dibuat menggunakan acuan pembanding, minimal standar kompetensi kerja?',
                'answer'                   => false,
                'adjustment_criteria_result' => '',
                'adjustment_plan_result'   => '',
                'adjustment_decisions'     => $emptyDecisions,
            ],
            [
                'id'                       => 2,
                'question'                 => 'Apakah rekaman rencana asesmen tervalidasi sudah sesuai dengan potensi asesi yang akan diujikan?',
                'answer'                   => false,
                'adjustment_criteria_result' => '',
                'adjustment_plan_result'   => '',
                'adjustment_decisions'     => $emptyDecisions,
            ],
            [
                'id'                       => 3,
                'question'                 => 'Apakah rekaman rencana asesmen tervalidasi sudah sesuai dengan konteks asesi (berdasarkan rekaman APL.01 tervalidasi LSP dan rekaman APL.02 tervalidasi asesi)?',
                'answer'                   => false,
                'adjustment_criteria_result' => '',
                'adjustment_plan_result'   => '',
                'adjustment_decisions'     => $emptyDecisions,
            ],
        ];
    }
}
