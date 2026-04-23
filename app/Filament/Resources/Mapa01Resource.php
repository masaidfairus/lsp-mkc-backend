<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Mapa01Resource\Pages;
use App\Models\Mapa01;
use App\Models\AssessmentRegistration;
use App\Models\UserSignature;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class Mapa01Resource extends Resource
{
    protected static ?string $model = Mapa01::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'MAPA';
    protected static ?string $navigationLabel = 'MAPA.01';
    protected static ?string $modelLabel = 'MAPA.01';
    protected static ?string $pluralModelLabel = 'MAPA.01';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->columnSpanFull()
                    ->schema([
                        // ── Header & Registration ──────────────────────────────────────
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(function () {
                                        $registrations = AssessmentRegistration::with(['candidate', 'scheme'])->get();
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
                                            $reg = AssessmentRegistration::with([
                                                'scheme.competencyUnits.workGroup', 
                                                'candidate'
                                            ])->find($state);

                                            if ($reg) {
                                                // 1. Populate Header info
                                                if ($reg->scheme) {
                                                    $set('mapa01_data.header.certification_scheme', $reg->scheme->name);
                                                    $set('mapa01_data.header.scheme_code', $reg->scheme->code);
                                                    
                                                    // 2. Populate Section 2 Looping Data
                                                    $units = $reg->scheme->competencyUnits;
                                                    
                                                    $mappedUnits = [];
                                                    foreach($units as $u) {
                                                        $wgName = $u->workGroup ? $u->workGroup->name : 'Unit Kompetensi';
                                                        $mappedUnits[] = [
                                                            'work_group' => $wgName,
                                                            'unit_code' => $u->unit_code,
                                                            'unit_title' => $u->unit_title,
                                                            'evidence' => '', // To be filled by assessor
                                                            'evidence_type' => [
                                                                'langsung' => false,
                                                                'tidak_langsung' => false,
                                                                'tambahan' => false,
                                                            ],
                                                            'assessment_methods' => self::defaultAssessmentMethods(),
                                                        ];
                                                    }
                                                    $set('mapa01_data.section_2_assessment_plan', $mappedUnits);
                                                }
                                                $set('mapa01_data.header.asesi_name', $reg->candidate?->name ?? '');
                                                $set('mapa01_data.header.assessment_date', now()->format('d-m-Y'));

                                                // 3. Populate Tujuan Asesmen from Registration (APL-01)
                                                if ($reg->certification_purpose) {
                                                    $tujuan = match($reg->certification_purpose->value) {
                                                        'sertifikasi' => 'Sertifikasi',
                                                        'pengakuan_kompetensi_terkini' => 'Pengakuan Kompetensi Terkini (PKT)',
                                                        'rekognisi_pembelajaran_lampau' => 'Rekognisi Pembelajaran Lampau (RPL)',
                                                        default => null,
                                                    };
                                                    if ($tujuan) {
                                                        $set('mapa01_data.section_1_approach.tujuan_asesmen', $tujuan);
                                                    }
                                                }
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Group::make()
                            ->statePath('mapa01_data')
                            ->schema([
                                
                                // ── Skema Sertifikasi ──────────────────────────────────
                                Forms\Components\Section::make('Skema Sertifikasi (KKNI/Okupasi/Klaster)')
                                    ->statePath('header')
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')
                                            ->label('Judul Skema')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_code')
                                            ->label('Nomor Skema')
                                            ->disabled()
                                            ->dehydrated(),
                                    ])->columns(2),

                                // ── Detail Header ──────────────────────────────────────
                                Forms\Components\Grid::make(3)
                                    ->statePath('header')
                                    ->schema([
                                        Forms\Components\TextInput::make('asesi_name')
                                            ->label('Nama Asesi')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\Select::make('tuk')
                                            ->label('TUK')
                                            ->options([
                                                'Sewaktu' => 'Sewaktu',
                                                'Tempat Kerja' => 'Tempat Kerja',
                                                'Mandiri' => 'Mandiri',
                                            ]),
                                        Forms\Components\TextInput::make('assessment_date')
                                            ->label('Tanggal Asesmen')
                                            ->disabled()
                                            ->dehydrated(),
                                    ]),

                                // ── Section 1: Pendekatan Asesmen ──────────────────────
                                Forms\Components\Section::make('1. Menentukan Pendekatan Asesmen')
                                    ->statePath('section_1_approach')
                                    ->schema([
                                        Forms\Components\Select::make('asesi_options')
                                            ->label('Asesi')
                                            ->options([
                                                'Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi' => 'Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi',
                                                'Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.' => 'Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.',
                                                'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi',
                                                'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.',
                                                'Pelatihan / belajar mandiri atau otodidak.' => 'Pelatihan / belajar mandiri atau otodidak.',
                                            ])
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\Select::make('tujuan_asesmen')
                                            ->label('Tujuan Asesmen')
                                            ->options([
                                                'Sertifikasi' => 'Sertifikasi',
                                                'Pengakuan Kompetensi Terkini (PKT)' => 'Pengakuan Kompetensi Terkini (PKT)',
                                                'Rekognisi Pembelajaran Lampau (RPL)' => 'Rekognisi Pembelajaran Lampau (RPL)',
                                            ])
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\Fieldset::make('Konteks Asesmen:')
                                            ->schema([
                                                Forms\Components\Select::make('lingkungan')
                                                    ->label('Lingkungan')
                                                    ->options([
                                                        'Tempat kerja nyata' => 'Tempat kerja nyata',
                                                        'Tempat kerja' => 'Tempat kerja',
                                                        'Simulasi' => 'Simulasi',
                                                    ])
                                                    ->searchable()
                                                    ->required(),
                                                Forms\Components\Select::make('peluang')
                                                    ->label('Peluang mengumpulkan bukti')
                                                    ->options([
                                                        'Tersedia' => 'Tersedia',
                                                        'Terbatas' => 'Terbatas',
                                                    ])
                                                    ->searchable()
                                                    ->required(),
                                                Forms\Components\Select::make('hubungan')
                                                    ->label('Hubungan antara standar kompetensi dan:')
                                                    ->options([
                                                        'Bukti untuk mendukung asesmen' => 'Bukti untuk mendukung asesmen',
                                                        'Aktivitas kerja di tempat kerja Asesi' => 'Aktivitas kerja di tempat kerja Asesi',
                                                        'Kegiatan Pembelajaran' => 'Kegiatan Pembelajaran',
                                                    ])
                                                    ->searchable()
                                                    ->required(),
                                                Forms\Components\Select::make('pelaku')
                                                    ->label('Siapa yang melakukan asesmen / RPL')
                                                    ->options([
                                                        'Lembaga Sertifikasi' => 'Lembaga Sertifikasi',
                                                        'Organisasi Pelatihan' => 'Organisasi Pelatihan',
                                                        'Asesor Perusahaan' => 'Asesor Perusahaan',
                                                    ])
                                                    ->searchable()
                                                    ->required(),
                                            ])->columns(2),

                                        Forms\Components\Select::make('benchmark_standards')
                                            ->label('1.2 Standar Pembanding')
                                            ->options([
                                                'Standar Kompetensi: SKKNI' => 'Standar Kompetensi: SKKNI',
                                                'Kriteria asesmen dari kurikulum pelatihan' => 'Kriteria asesmen dari kurikulum pelatihan',
                                                'Spesifikasi kinerja suatu perusahaan atau industri' => 'Spesifikasi kinerja suatu perusahaan atau industri',
                                                'Spesifikasi Produk' => 'Spesifikasi Produk',
                                                'Pedoman khusus' => 'Pedoman khusus',
                                            ])
                                            ->searchable()
                                            ->required(),
                                    ]),

                                // ── Section 2: Rencana Asesmen ─────────────────────────
                                Forms\Components\Section::make('2. Rencana Asesmen')
                                    ->schema([
                                        Forms\Components\Repeater::make('section_2_assessment_plan')
                                            ->label('Daftar Unit Kompetensi & Jenis Bukti')
                                            ->schema([
                                                Forms\Components\Hidden::make('work_group'),
                                                Forms\Components\Hidden::make('unit_code'),
                                                Forms\Components\Hidden::make('unit_title'),

                                                Forms\Components\Placeholder::make('unit_display')
                                                    ->label('Unit Kompetensi')
                                                    ->content(fn ($get) => new HtmlString('<div class="text-xs text-gray-500">Kelompok: '.$get('work_group').'</div><strong class="text-primary-600">'.$get('unit_code').'</strong> - ' . $get('unit_title')))
                                                    ->columnSpanFull(),
                                                    
                                                Forms\Components\Textarea::make('evidence')
                                                    ->label('Bukti-bukti (Kinerja, Produk, Portofolio, dan / atau Pengetahuan)')
                                                    ->placeholder('Contoh: Hasil Verifikasi Portofolio dan Hasil Tanya Jawab tentang...')
                                                    ->rows(3)
                                                    ->columnSpan(3),

                                                Forms\Components\Select::make('evidence_type_select')
                                                    ->label('Jenis Bukti')
                                                    ->options([
                                                        'L' => 'L (Langsung)',
                                                        'TL' => 'TL (Tidak Langsung)',
                                                        'T' => 'T (Tambahan)',
                                                    ])
                                                    ->required()
                                                    ->columnSpan(1),
                                                    
                                                Forms\Components\Section::make('Metode dan Perangkat Asesmen')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('assessment_methods')
                                                            ->label('')
                                                            ->schema([
                                                                Forms\Components\Hidden::make('method'),
                                                                Forms\Components\Select::make('answer')
                                                                    ->label(fn ($get) => $get('method'))
                                                                    ->options([
                                                                        'CL' => 'CL (Ceklis Observasi)',
                                                                        'DIT' => 'DIT (Daftar Instruksi Terstruktur)',
                                                                        'DPL' => 'DPL (Daftar Pertanyaan Lisan)',
                                                                        'DPT' => 'DPT (Daftar Pertanyaan Tertulis)',
                                                                        'VPK' => 'VPK (Verifikasi Pihak Ketiga)',
                                                                        'CVP' => 'CVP (Ceklis Verifikasi Portofolio)',
                                                                        'CRP' => 'CRP (Ceklis Reviu Produk)',
                                                                        'PW' => 'PW (Pertanyaan Wawancara)',
                                                                    ])
                                                                    ->placeholder('Pilih kode'),
                                                            ])
                                                            ->grid(6)
                                                            ->disableItemCreation()
                                                            ->disableItemDeletion()
                                                            ->disableItemMovement()
                                                            ->columnSpanFull(),
                                                    ])->compact(),
                                            ])
                                            ->columns(4)
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn (array $state): ?string => $state['unit_code'] ?? null),
                                    ]),

                                // ── Section 3: Kontekstualisasi ────────────────────────
                                Forms\Components\Section::make('3. Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi:')
                                    ->schema([
                                        Forms\Components\Repeater::make('section_3_contextualization')
                                            ->label('Detail Pengkajian')
                                            ->schema([
                                                Forms\Components\Hidden::make('no'),
                                                Forms\Components\Repeater::make('content')
                                                    ->label(fn ($get) => 'Poin ' . $get('../../no'))
                                                    ->schema([
                                                        Forms\Components\Hidden::make('type'),
                                                        Forms\Components\Grid::make(4)
                                                            ->schema([
                                                                Forms\Components\Placeholder::make('label_display')
                                                                    ->label('')
                                                                    ->content(fn ($get) => $get('type'))
                                                                    ->columnSpan(2),
                                                                Forms\Components\Select::make('answer')
                                                                    ->label('')
                                                                    ->options([
                                                                        'Ada' => 'Ada',
                                                                        'Tidak Ada' => 'Tidak Ada',
                                                                    ])
                                                                    ->reactive()
                                                                    ->columnSpan(1),
                                                                Forms\Components\Textarea::make('note')
                                                                    ->label('Jika Ada, tuliskan')
                                                                    ->placeholder('Masukkan alasan/catatan...')
                                                                    ->rows(2)
                                                                    ->visible(fn ($get) => $get('answer') === 'Ada')
                                                                    ->columnSpan(1),
                                                            ]),
                                                    ])
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement()
                                            ])
                                            ->default(fn () => self::defaultContextualization())
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->columnSpanFull(),
                                    ]),

                                // ── Section 4 & Signatures ─────────────────────────────
                                Forms\Components\Section::make('4. Konfirmasi & Persetujuan')
                                    ->schema([
                                        Forms\Components\Fieldset::make('Konfirmasi dengan orang yang relevan')
                                            ->statePath('section_4_relevant_confirmation')
                                            ->schema([
                                                self::confirmationPartySchema('lsp_manager', 'Manajer Sertifikasi LSP'),
                                                self::confirmationPartySchema('master_assessor', 'Master Asesor / Master Trainer / Lead Asesor Kompetensi'),
                                                self::confirmationPartySchema('training_manager', 'Manajer Pelatihan Lembaga Training terakreditasi'),
                                                self::confirmationPartySchema('workplace_supervisor', 'Manajer atau supervisor ditempat kerja'),
                                            ])->columns(2),

                                        Forms\Components\Fieldset::make('Penyusun Dokumen')
                                            ->statePath('signatures.compiler')
                                            ->schema([
                                                self::assessorSignatureSchema('penyusun_1_id', 'Penyusun 1'),
                                                self::assessorSignatureSchema('penyusun_2_id', 'Penyusun 2', 'penyusun_1_id'),
                                            ])->columns(2),

                                        Forms\Components\Fieldset::make('Validator Dokumen')
                                            ->statePath('signatures.validator')
                                            ->schema([
                                                self::assessorSignatureSchema('validator_1_id', 'Validator 1'),
                                                self::assessorSignatureSchema('validator_2_id', 'Validator 2', 'validator_1_id'),
                                            ])->columns(2),
                                    ]),

                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereNotNull('mapa01_data'))
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMapa01s::route('/'),
            'create' => Pages\CreateMapa01::route('/create'),
            'edit' => Pages\EditMapa01::route('/{record}/edit'),
        ];
    }

    // ── Reusable Schema Generators ─────────────────────────────────────────────

    private static function confirmationPartySchema(string $fieldKey, string $label): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->statePath($fieldKey)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label($label)
                    ->options(User::role('Asesor')->pluck('name', 'id'))
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('name', $user->name);
                            $set('met_number', $user->registration_number);
                            $set('date', now()->toDateString());
                            if ($sig = UserSignature::where('user_id', $user->id)->first()) {
                                $path = $sig->signature_image;
                                $set('signature', $path ? \Illuminate\Support\Facades\Storage::url($path) : null);
                            } else {
                                // Try finding by name if ID fails
                                if ($sigByName = UserSignature::where('name', $user->name)->first()) {
                                    $path = $sigByName->signature_image;
                                    $set('signature', $path ? \Illuminate\Support\Facades\Storage::url($path) : null);
                                } else {
                                    $set('signature', null);
                                }
                            }
                        } else {
                            $set('name', null);
                            $set('met_number', null);
                            $set('signature', null);
                            $set('date', null);
                        }
                    }),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('met_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),
                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(function ($get) {
                        if (!$get('met_number') && !$get('name')) return '-';
                        $img = $get('signature') ? "<img src='{$get('signature')}' style='max-height:80px; margin-top:8px;'/>" : "";
                        
                        // Fetch QR code if exists - Match by NAME for maximum compatibility
                        $qrImg = "";
                        if ($get('name')) {
                            if ($sig = UserSignature::where('name', $get('name'))->first()) {
                                if ($sig->qr_code) {
                                    $qrImg = "<img src='{$sig->qr_code}' style='max-height:80px; margin-top:8px;'/>";
                                }
                            }
                        }

                        return new HtmlString("<div class='flex gap-4 items-end'>
                            <div><strong>{$get('name')}</strong><br><small>No. MET: {$get('met_number')}</small><br><small>Tgl: {$get('date')}</small>{$img}</div>
                            {$qrImg}
                        </div>");
                    })
                    ->visible(fn ($get) => $get('user_id') !== null),
            ]);
    }

    private static function assessorSignatureSchema(string $fieldKey, string $label, string $excludeKey = null): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->statePath($fieldKey)
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label($label)
                    ->options(function (\Filament\Forms\Get $get) use ($excludeKey) {
                        $query = User::role('Asesor');
                        if ($excludeKey && $get('../../' . $excludeKey . '/user_id')) {
                            $query->where('id', '!=', $get('../../' . $excludeKey . '/user_id'));
                        }
                        return $query->pluck('name', 'id');
                    })
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('name', $user->name);
                            $set('met_number', $user->registration_number);
                            $set('date', now()->toDateString());
                            if ($sig = UserSignature::where('user_id', $user->id)->first()) {
                                $path = $sig->signature_image;
                                $set('signature', $path ? \Illuminate\Support\Facades\Storage::url($path) : null);
                            }
                        } else {
                            $set('name', null);
                            $set('met_number', null);
                            $set('signature', null);
                            $set('date', null);
                        }
                    }),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('met_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),
                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(function ($get) {
                        if (!$get('met_number') && !$get('name')) return '-';
                        $img = $get('signature') ? "<img src='{$get('signature')}' style='max-height:80px; margin-top:8px;'/>" : "";
                        
                        // Fetch QR code if exists - Match by NAME for maximum compatibility
                        $qrImg = "";
                        if ($get('name')) {
                            if ($sig = UserSignature::where('name', $get('name'))->first()) {
                                if ($sig->qr_code) {
                                    $qrImg = "<img src='{$sig->qr_code}' style='max-height:80px; margin-top:8px;'/>";
                                }
                            }
                        }

                        return new HtmlString("<div class='flex gap-4 items-end'>
                            <div><strong>{$get('name')}</strong><br><small>No. MET: {$get('met_number')}</small><br><small>Tgl: {$get('date')}</small>{$img}</div>
                            {$qrImg}
                        </div>");
                    })
                    ->visible(fn ($get) => $get('user_id') !== null),
            ]);
    }

    // ── Predefined Default Arrays ──────────────────────────────────────────────

    private static function defaultAssessmentMethods(): array
    {
        return [
            ['method' => 'Observasi langsung', 'answer' => ''],
            ['method' => 'Kegiatan Terstruktur', 'answer' => ''],
            ['method' => 'Tanya Jawab', 'answer' => ''],
            ['method' => 'Verifikasi Portfolio', 'answer' => ''],
            ['method' => 'Reviu Produk', 'answer' => ''],
            ['method' => 'Verifikasi Pihak Ketiga', 'answer' => ''],
        ];
    }

    private static function defaultContextualization(): array
    {
        return [
            [
                'no' => '3.1',
                'content' => [
                    ['type' => 'a. Karakteristik Kandidat:', 'answer' => 'Tidak Ada', 'note' => ''],
                    ['type' => 'b. Kebutuhan kontekstualisasi terkait tempat kerja:', 'answer' => 'Tidak Ada', 'note' => ''],
                ]
            ],
            [
                'no' => '3.2',
                'content' => [
                    ['type' => 'Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan', 'answer' => 'Tidak Ada', 'note' => ''],
                ]
            ],
            [
                'no' => '3.3',
                'content' => [
                    ['type' => 'Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi', 'answer' => 'Tidak Ada', 'note' => ''],
                ]
            ],
            [
                'no' => '3.4',
                'content' => [
                    ['type' => 'Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen', 'answer' => 'Tidak Ada', 'note' => ''],
                ]
            ],
        ];
    }
}
