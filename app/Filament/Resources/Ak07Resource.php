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
    use \App\Filament\Traits\HasStandardIaForm;
    protected static ?string $model = Ak07::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.07';
    protected static ?string $modelLabel = 'AK.07';
    protected static ?string $pluralModelLabel = 'AK.07';
    protected static ?int $navigationSort = 7;
    /**
     * Form.
     *
     * @param  Form  $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->columnSpanFull()
                    ->schema([

                        // ── 1. Registration selector with scheme name + occurrence ──────
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(fn (\Illuminate\Database\Eloquent\Model $record) => static::getFilteredRegistrationOptions($record))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $reg = \App\Models\AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
                                            if ($reg) {
                                                if ($reg->scheme) {
                                                    $set('ak07_data.certification_scheme.name', $reg->scheme->name);
                                                    $set('ak07_data.certification_scheme.code', $reg->scheme->code);
                                                }
                                                $set('ak07_data.asesi_name', $reg->candidate?->name ?? '');
                                                
                                                // Auto-populate Assessor from Registration
                                                if ($reg->assessor) {
                                                    $set('ak07_data.asesor_name', $reg->assessor->name);
                                                    
                                                    // Auto-populate signature
                                                    self::populateAssessorSignature($reg->assessor_id, $set, 'ak07_data.signatures.asesor');
                                                }

                                                if ($reg->tuk) {
                                                    $set('ak07_data.tuk', $reg->tuk);
                                                }
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
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('name')
                                            ->label('Judul')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('code')
                                            ->label('Nomor')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                    ])->columns(2),

                                // ── Header fields ──────────────────────────────────────
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('tuk')
                                            ->label('TUK')
                                            ->disabled()
                                            ->dehydrated(),

                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('asesi_name')
                                            ->label('Nama Asesi')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),

                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('asesor_name')
                                            ->label('Nama Asesor')
                                            ->disabled()
                                            ->dehydrated()
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
                                        // ... repeater content ...
                                    ]),

                                // ── 3. Bagian A – redesigned for clean UI ──────────────
                                Forms\Components\Section::make('Bagian A – Penyesuaian sesuai karakteristik asesi')
                                    ->schema([
                                        // ... repeater content ...
                                    ]),

                                // ── 4. Bagian B – with adjustment_plan column ───────────
                                Forms\Components\Section::make('Bagian B – Kesesuaian Rencana Asesmen')
                                    ->schema([
                                        // ... repeater content ...
                                    ]),

                                // ── Hasil Penyesuaian Asesmen ──────────────────────────
                                Forms\Components\Section::make('Hasil Penyesuaian Asesmen')
                                    ->schema([
                                        // ... section content ...
                                    ]),

                                // ── 5. Tanda Tangan – ASESOR ONLY ──────────────────────
                                Forms\Components\Section::make('Tanda Tangan Asesor')
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->statePath('signatures')
                                            ->schema([
                                                self::standardAssessorSignatureBlock('asesor'),

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

    /**
     * Table.
     *
     * @param  Table  $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereNotNull('ak07_data'))
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('Registration ID')
                    ->searchable()
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('confirmed_at')
                    ->label('Dikonfirmasi')
                    ->dateTime()
                    ->sortable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
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

    /**
     * Mengambil pages.
     *
     * @return array
     */
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

    /**
     * Default part a.
     *
     * @return array
     */
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

    /**
     * Default part b.
     *
     * @return array
     */
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
