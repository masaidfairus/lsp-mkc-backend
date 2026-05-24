<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Mapa01Resource\Pages;
use App\Filament\Traits\HasStandardIaForm;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Mapa01Resource extends Resource
{
    use HasStandardIaForm;
    protected static ?string $model = Mapa01::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'MAPA';
    protected static ?string $navigationLabel = 'MAPA.01';
    protected static ?string $modelLabel = 'MAPA.01';
    protected static ?string $pluralModelLabel = 'MAPA.01';

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
                                            $reg = AssessmentRegistration::with([
                                                'scheme.competencyUnits.workGroup',
                                                'candidate'
                                            ])->find($state);

                                            if ($reg) {
                                                $template = \App\Models\MapaTemplate::where('scheme_id', $reg->scheme_id)->first();
                                                if ($template) {
                                                    $set('_has_template', true);
                                                    $set('_template_id', $template->id);
                                                } else {
                                                    $set('_has_template', false);
                                                }

                                                if ($reg->scheme) {
                                                    $set('mapa01_data.header.certification_scheme', $reg->scheme->name);
                                                    $set('mapa01_data.header.scheme_code', $reg->scheme->code);

                                                    $units = $reg->scheme->competencyUnits;
                                                    $mappedUnits = [];
                                                    foreach ($units as $u) {
                                                        $wgName = $u->workGroup ? $u->workGroup->name : 'Unit Kompetensi';
                                                        $mappedUnits[] = [
                                                            'work_group'         => $wgName,
                                                            'unit_code'          => $u->unit_code,
                                                            'unit_title'         => $u->unit_title,
                                                            'evidence'           => '',
                                                            'evidence_type'      => [],
                                                            'assessment_methods' => self::defaultAssessmentMethods(),
                                                        ];
                                                    }
                                                    $set('mapa01_data.section_2_assessment_plan', $mappedUnits);
                                                }

                                                $set('mapa01_data.header.asesi_name', $reg->candidate?->name ?? '');
                                                $set('mapa01_data.header.assessment_date', now()->format('d-m-Y'));

                                                if ($reg->certification_purpose) {
                                                    $tujuan = match ($reg->certification_purpose->value) {
                                                        'sertifikasi'                   => 'Sertifikasi',
                                                        'pengakuan_kompetensi_terkini'  => 'Pengakuan Kompetensi Terkini (PKT)',
                                                        'rekognisi_pembelajaran_lampau' => 'Rekognisi Pembelajaran Lampau (RPL)',
                                                        default                         => null,
                                                    };
                                                    if ($tujuan) {
                                                        $set('mapa01_data.section_1_approach.tujuan_asesmen', [$tujuan]);
                                                    }
                                                }
                                                
                                                if ($reg->tuk) {
                                                    $set('mapa01_data.header.tuk', $reg->tuk);
                                                }
                                            }
                                        }
                                    }),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('template_info')
                                            ->label('')
                                            ->content(new HtmlString('<div class="bg-primary-50 p-4 rounded-xl border border-primary-100 flex items-center gap-3">
                                                <div class="bg-primary-500 p-2 rounded-lg text-white">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-primary-900">Template Ditemukan!</p>
                                                    <p class="text-xs text-primary-700">Skema ini memiliki data yang pernah disimpan sebelumnya.</p>
                                                </div>
                                            </div>'))
                                            ->visible(fn ($get) => $get('_has_template')),

                                        Forms\Components\Actions::make([
                                            Forms\Components\Actions\Action::make('load_template')
                                                ->label('Gunakan FR. MAPA 01 yang pernah dibuat')
                                                ->icon('heroicon-m-arrow-path')
                                                ->color('primary')
                                                ->action(function ($set, $get) {
                                                    $template = \App\Models\MapaTemplate::find($get('_template_id'));
                                                    if ($template) {
                                                        $currentData  = $get('mapa01_data');
                                                        $templateData = $template->data;
                                                        $templateData['header']['asesi_name']      = $currentData['header']['asesi_name'] ?? '';
                                                        $templateData['header']['assessment_date'] = $currentData['header']['assessment_date'] ?? '';
                                                        $set('mapa01_data', $templateData);
                                                        \Filament\Notifications\Notification::make()
                                                            ->title('Template Berhasil Dimuat')
                                                            ->success()
                                                            ->send();
                                                    }
                                                }),
                                        ])->visible(fn ($get) => $get('_has_template')),
                                    ])->columnSpanFull(),
                            ]),

                        Forms\Components\Group::make()
                            ->statePath('mapa01_data')
                            ->schema([

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Skema Sertifikasi (KKNI/Okupasi/Klaster)')
                                    ->statePath('header')
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('certification_scheme')
                                            ->label('Judul Skema')
                                            ->disabled()
                                            ->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('scheme_code')
                                            ->label('Nomor Skema')
                                            ->disabled()
                                            ->dehydrated(),
                                    ])->columns(2),

                                Forms\Components\Grid::make(3)
                                    ->statePath('header')
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('asesi_name')
                                            ->label('Nama Asesi')
                                            ->disabled()
                                            ->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('tuk')
                                            ->label('TUK')
                                            ->disabled()
                                            ->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessment_date')
                                            ->label('Tanggal Asesmen')
                                            ->disabled()
                                            ->dehydrated(),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('1. Menentukan Pendekatan Asesmen')
                                    ->statePath('section_1_approach')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('asesi_options')
                                            ->label('1.1 Asesi')
                                            ->options([
                                                'Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi' => 'Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi',
                                                'Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.' => 'Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.',
                                                'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi',
                                                'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.',
                                                'Pelatihan / belajar mandiri atau otodidak.' => 'Pelatihan / belajar mandiri atau otodidak.',
                                            ])
                                            ->required()
                                            ->columns(1)
                                            ->gridDirection('column'),

                                        Forms\Components\CheckboxList::make('tujuan_asesmen')
                                            ->label('1.2 Tujuan Asesmen')
                                            ->options([
                                                'Sertifikasi'                         => 'Sertifikasi',
                                                'Pengakuan Kompetensi Terkini (PKT)'  => 'Pengakuan Kompetensi Terkini (PKT)',
                                                'Rekognisi Pembelajaran Lampau (RPL)' => 'Rekognisi Pembelajaran Lampau (RPL)',
                                                'Other'                               => 'Lainnya:',
                                            ])
                                            ->disabled()
                                            ->dehydrated()
                                            ->reactive()
                                            ->columns(2),

                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('tujuan_asesmen_other')
                                            ->label('Tujuan Asesmen Lainnya')
                                            ->placeholder('Sebutkan...')
                                            ->visible(fn ($get) => in_array('Other', (array) $get('tujuan_asesmen')))
                                            ->columnSpanFull(),

                                        Forms\Components\Fieldset::make('Konteks Asesmen:')
                                            ->schema([
                                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                                Forms\Components\Select::make('lingkungan')
                                                    ->label('Lingkungan')
                                                    ->options([
                                                        'Tempat kerja nyata' => 'Tempat kerja nyata',
                                                        'Tempat kerja'       => 'Tempat kerja',
                                                        'Simulasi'           => 'Simulasi',
                                                    ])
                                                    ->searchable()
                                                    ->required(),
                                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                                Forms\Components\Select::make('peluang')
                                                    ->label('Peluang mengumpulkan bukti')
                                                    ->options([
                                                        'Tersedia' => 'Tersedia',
                                                        'Terbatas' => 'Terbatas',
                                                    ])
                                                    ->searchable()
                                                    ->required(),
                                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                                Forms\Components\Select::make('hubungan')
                                                    ->label('Hubungan antara standar kompetensi dan:')
                                                    ->options([
                                                        'Bukti untuk mendukung asesmen'         => 'Bukti untuk mendukung asesmen',
                                                        'Aktivitas kerja di tempat kerja Asesi' => 'Aktivitas kerja di tempat kerja Asesi',
                                                        'Kegiatan Pembelajaran'                 => 'Kegiatan Pembelajaran',
                                                    ])
                                                    ->searchable()
                                                    ->required(),
                                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                                Forms\Components\Select::make('pelaku')
                                                    ->label('Siapa yang melakukan asesmen / RPL')
                                                    ->options([
                                                        'Lembaga Sertifikasi'  => 'Lembaga Sertifikasi',
                                                        'Organisasi Pelatihan' => 'Organisasi Pelatihan',
                                                        'Asesor Perusahaan'    => 'Asesor Perusahaan',
                                                    ])
                                                    ->searchable()
                                                    ->required(),
                                            ])->columns(2),

                                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                        Forms\Components\Select::make('benchmark_standards')
                                            ->label('1.2 Standar Pembanding')
                                            ->options([
                                                'Standar Kompetensi: SKKNI'                         => 'Standar Kompetensi: SKKNI',
                                                'Kriteria asesmen dari kurikulum pelatihan'          => 'Kriteria asesmen dari kurikulum pelatihan',
                                                'Spesifikasi kinerja suatu perusahaan atau industri' => 'Spesifikasi kinerja suatu perusahaan atau industri',
                                                'Spesifikasi Produk'                                => 'Spesifikasi Produk',
                                                'Pedoman khusus'                                    => 'Pedoman khusus',
                                            ])
                                            ->searchable()
                                            ->required(),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
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
                                                    ->content(fn ($get) => new HtmlString(
                                                        '<div class="text-xs text-gray-500">Kelompok: ' . $get('work_group') . '</div>'
                                                        . '<strong class="text-primary-600">' . $get('unit_code') . '</strong> - ' . $get('unit_title')
                                                    ))
                                                    ->columnSpanFull(),

                                                Forms\Components\Textarea::make('evidence')
                                                    ->label('Bukti-bukti (Kinerja, Produk, Portofolio, dan / atau Pengetahuan)')
                                                    ->placeholder('Contoh: Hasil Verifikasi Portofolio dan Hasil Tanya Jawab tentang...')
                                                    ->rows(3)
                                                    ->columnSpan(3),

                                                Forms\Components\CheckboxList::make('evidence_type')
                                                    ->label('Jenis Bukti')
                                                    ->options([
                                                        'L'  => new HtmlString('<strong>L</strong> (Langsung)'),
                                                        'TL' => new HtmlString('<strong>TL</strong> (Tidak Langsung)'),
                                                        'T'  => new HtmlString('<strong>T</strong> (Tambahan)'),
                                                    ])
                                                    ->required()
                                                    ->columns(3)
                                                    ->columnSpan(1),

                                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                                Forms\Components\Section::make('Metode dan Perangkat Asesmen')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('assessment_methods')
                                                            ->label('')
                                                            ->schema([
                                                                Forms\Components\Hidden::make('method'),
                                                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                                                Forms\Components\Select::make('answer')
                                                                    ->label(fn ($get) => $get('method'))
                                                                    ->options([
                                                                        'CL'  => 'CL (Ceklis Observasi)',
                                                                        'DIT' => 'DIT (Daftar Instruksi Terstruktur)',
                                                                        'DPL' => 'DPL (Daftar Pertanyaan Lisan)',
                                                                        'DPT' => 'DPT (Daftar Pertanyaan Tertulis)',
                                                                        'VPK' => 'VPK (Verifikasi Pihak Ketiga)',
                                                                        'CVP' => 'CVP (Ceklis Verifikasi Portofolio)',
                                                                        'CRP' => 'CRP (Ceklis Reviu Produk)',
                                                                        'PW'  => 'PW (Pertanyaan Wawancara)',
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

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
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
                                                                Forms\Components\Radio::make('answer')
                                                                    ->label('')
                                                                    ->options([
                                                                        'Ada'       => new HtmlString('<span class="flex items-center gap-1"><span class="text-success-600">✅</span> Ada</span>'),
                                                                        'Tidak Ada' => new HtmlString('<span class="flex items-center gap-1"><span class="text-danger-600">❌</span> Tidak Ada</span>'),
                                                                    ])
                                                                    ->inline()
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

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
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

    /**
     * Table.
     *
     * @param  Table  $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereNotNull('mapa01_data'))
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
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListMapa01s::route('/'),
            'create' => Pages\CreateMapa01::route('/create'),
            'edit'   => Pages\EditMapa01::route('/{record}/edit'),
        ];
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Resolve a signature record's image to a usable URL or data URI.
     *
     * Handles four cases:
     *   1. data URI  (data:image/...) — return as-is, it's already embeddable.
     *   2. Full URL  (http/https)     — return as-is.
     *   3. Path with /storage/ prefix — strip prefix so Storage::url() doesn't
     *      produce /storage/storage/...
     *   4. Plain relative path        — pass directly to Storage::url().
     */
    private static function resolveSignatureUrl(?string $path): ?string
    {
        if (!$path) return null;

        // Case 1: data URI — already embeddable, return immediately
        if (str_starts_with($path, 'data:')) {
            Log::debug('[MAPA01] signature is data URI', ['prefix' => substr($path, 0, 40)]);
            return $path;
        }

        // Case 2: already a full URL — nothing to do
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            Log::debug('[MAPA01] signature already full URL', ['path' => $path]);
            return $path;
        }

        // Case 3 & 4: strip any leading /storage/ prefix, then resolve via Storage
        $normalized = preg_replace('#^/?storage/#', '', $path);
        $url        = Storage::url($normalized);

        Log::debug('[MAPA01] resolveSignatureUrl', [
            'raw'        => $path,
            'normalized' => $normalized,
            'resolved'   => $url,
        ]);

        return $url;
    }

    /**
     * Shared state-update logic for both compiler and confirmation schemas.
     */
    private static function applyUserSignatureToState(int|string|null $userId, callable $set): void
    {
        if (!$userId) {
            $set('name', null);
            $set('met_number', null);
            $set('signature', null);
            $set('qr_code', null);
            $set('date', null);
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            Log::warning('[MAPA01] User not found', ['user_id' => $userId]);
            return;
        }

        $set('name', $user->name);
        $set('met_number', $user->registration_number);
        $set('date', now()->format('d-m-Y'));

        $sig = UserSignature::where('user_id', $user->id)->first()
            ?? UserSignature::where('name', $user->name)->first();

        Log::debug('[MAPA01] UserSignature lookup', [
            'user_id'       => $user->id,
            'user_name'     => $user->name,
            'sig_found'     => $sig ? true : false,
            'sig_image_raw' => $sig?->signature_image,
            'sig_qr_raw'    => $sig?->qr_code,
        ]);

        if ($sig) {
            $signatureUrl = self::resolveSignatureUrl($sig->signature_image);
            $qrUrl        = self::resolveSignatureUrl($sig->qr_code);

            Log::debug('[MAPA01] Resolved URLs', [
                'signature_url' => $signatureUrl ? substr($signatureUrl, 0, 60) . '...' : null,
                'qr_url'        => $qrUrl        ? substr($qrUrl, 0, 60)        . '...' : null,
            ]);

            $set('signature', $signatureUrl);
            $set('qr_code',   $qrUrl);
        } else {
            Log::warning('[MAPA01] No signature record found', [
                'user_id'   => $user->id,
                'user_name' => $user->name,
            ]);
            $set('signature', null);
            $set('qr_code',   null);
        }
    }

    /**
     * Confirmation party schema.
     *
     * @param  string  $fieldKey
     * @param  string  $label
     * @return Forms\Components\Group
     */
    private static function confirmationPartySchema(string $fieldKey, string $label): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->statePath($fieldKey)
            ->schema([
                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                Forms\Components\Select::make('user_id')
                    ->label($label)
                    ->options(User::role('Asesor')->pluck('name', 'id'))
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(fn ($state, callable $set) => self::applyUserSignatureToState($state, $set)),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('met_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),
                Forms\Components\Hidden::make('qr_code'),

                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(function ($get) {
                        $name = $get('name');
                        if (!$name) return '-';

                        $signature = $get('signature');
                        $qrCode    = $get('qr_code');
                        $regNumber = $get('met_number') ?: 'N/A';
                        $date      = $get('date') ?: now()->format('d-m-Y');

                        $img   = self::renderSignatureImg($signature);
                        $qrImg = self::renderQrImg($qrCode);

                        return new HtmlString(self::renderSignatureCard($name, $regNumber, $date, $img, $qrImg));
                    })
                    ->visible(fn ($get) => $get('user_id') !== null),
            ]);
    }

    /**
     * Assessor signature schema.
     *
     * @param  string  $fieldKey
     * @param  string  $label
     * @param  string  $excludeKey
     * @return Forms\Components\Group
     */
    private static function assessorSignatureSchema(string $fieldKey, string $label, string $excludeKey = null): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->statePath($fieldKey)
            ->schema([
                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                Forms\Components\Select::make('user_id')
                    ->label($label)
                    ->options(function (\Filament\Forms\Get $get) use ($excludeKey, $label) {
                        $role = str_contains(strtolower($label), 'penyusun') ? 'penyusun' : (str_contains(strtolower($label), 'validator') ? 'validator' : 'asesor');
                        $query = User::role($role);
                        if ($excludeKey && $get('../../' . $excludeKey . '/user_id')) {
                            $query->where('id', '!=', $get('../../' . $excludeKey . '/user_id'));
                        }
                        return $query->pluck('name', 'id');
                    })
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(fn ($state, callable $set) => self::applyUserSignatureToState($state, $set)),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('met_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),
                Forms\Components\Hidden::make('qr_code'),

                Forms\Components\Toggle::make('is_endorsed')
                    ->label('Tandatangani Dokumen')
                    ->onColor('success')
                    ->offColor('gray')
                    ->onIcon('heroicon-m-pencil-square')
                    ->offIcon('heroicon-m-no-symbol')
                    ->reactive()
                    ->visible(fn ($get) => $get('user_id') !== null),

                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(function ($get) {
                        if (!$get('is_endorsed')) {
                            return new HtmlString(
                                '<div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 text-center text-gray-400 text-xs italic">
                                    Menunggu persetujuan validator / penyusun...
                                </div>'
                            );
                        }

                        $name = $get('name');
                        if (!$name) return '-';

                        $signature = $get('signature');
                        $qrCode    = $get('qr_code');
                        $regNumber = $get('met_number') ?: 'N/A';
                        $date      = $get('date') ?: now()->format('d-m-Y');

                        $img   = self::renderSignatureImg($signature);
                        $qrImg = self::renderQrImg($qrCode);

                        return new HtmlString(self::renderSignatureCard($name, $regNumber, $date, $img, $qrImg));
                    })
                    ->visible(fn ($get) => $get('user_id') !== null),
            ]);
    }

    // ── HTML Renderers ─────────────────────────────────────────────────────────

    /**
     * Render signature <img> with onerror fallback.
     */
    private static function renderSignatureImg(?string $url): string
    {
        if (!$url) {
            return "<div style='font-size:11px;color:#dc2626;font-style:italic;'>
                        Tanda tangan belum diunggah
                    </div>";
        }

        $escaped = htmlspecialchars($url, ENT_QUOTES);
        return "
        <img
            src='{$escaped}'
            style='max-height:60px;object-fit:contain;'
            onerror=\"
                this.style.display='none';
                this.nextElementSibling.style.display='block';
            \"
        />
        <div style='display:none;font-size:11px;color:#dc2626;font-style:italic;'>
            ❌ Gagal memuat tanda tangan
        </div>";
    }

    /**
     * Render QR <img> with onerror fallback.
     */
    private static function renderQrImg(?string $url): string
    {
        if (!$url) return '';

        $escaped = htmlspecialchars($url, ENT_QUOTES);
        return "
        <img
            src='{$escaped}'
            style='width:60px;height:60px;object-fit:contain;'
            onerror=\"
                this.style.display='none';
                this.nextElementSibling.style.display='block';
            \"
        />
        <div style='display:none;font-size:9px;color:#dc2626;font-style:italic;text-align:center;'>
            ❌ QR gagal
        </div>";
    }

    private static function renderSignatureCard(
        string $name,
        string $regNumber,
        string $date,
        string $imgHtml,
        string $qrHtml
    ): string {
        return "
        <div style='
            font-family: system-ui, sans-serif;
            background: var(--color-background-primary, #ffffff);
            border: 0.5px solid var(--color-border-secondary, #e2e8f0);
            border-radius: 16px;
            overflow: hidden;
            margin-top: 12px;
            max-width: 460px;
            color: var(--color-text-primary, #0f172a);
        '>
            <div style='
                padding: 5px 16px;
                background: var(--color-background-secondary, #f8fafc);
                border-bottom: 0.5px solid var(--color-border-tertiary, #f1f5f9);
            '>
                <span style='
                    font-size: 10px;
                    text-transform: uppercase;
                    letter-spacing: 0.1em;
                    font-weight: 600;
                    color: var(--color-text-tertiary, #94a3b8);
                '>Identitas &amp; Tanda Tangan</span>
            </div>

            <div style='padding: 18px;'>
                <div style='
                    display: flex;
                    gap: 14px;
                    align-items: flex-start;
                    margin-bottom: 18px;
                    padding-bottom: 18px;
                    border-bottom: 0.5px solid var(--color-border-tertiary, #f1f5f9);
                '>
                    <div style='flex: 1; min-width: 0;'>
                        <div style='font-size:10px;text-transform:uppercase;letter-spacing:0.12em;font-weight:600;color:var(--color-text-tertiary,#94a3b8);margin-bottom:4px;'>Nama lengkap</div>
                        <div style='font-size:15px;font-weight:600;color:var(--color-text-primary,#0f172a);margin-bottom:12px;letter-spacing:0.01em;'>{$name}</div>
                        <div style='font-size:10px;text-transform:uppercase;letter-spacing:0.12em;font-weight:600;color:var(--color-text-tertiary,#94a3b8);margin-bottom:5px;'>No. registrasi MET</div>
                        <span style='display:inline-block;font-size:12px;font-weight:500;color:var(--color-text-secondary,#475569);background:var(--color-background-secondary,#f8fafc);border:0.5px solid var(--color-border-secondary,#e2e8f0);border-radius:6px;padding:3px 10px;font-family:monospace;letter-spacing:0.03em;'>{$regNumber}</span>
                    </div>
                    <div style='width:120px;height:120px;border:0.5px solid var(--color-border-secondary,#e2e8f0);border-radius:10px;overflow:hidden;flex-shrink:0;background:var(--color-background-secondary,#f8fafc);display:flex;align-items:center;justify-content:center;'>
                        {$qrHtml}
                    </div>
                </div>

                <div style='display:grid;grid-template-columns:1fr 118px;gap:12px;'>
                    <div>
                        <div style='font-size:10px;text-transform:uppercase;letter-spacing:0.12em;font-weight:600;color:var(--color-text-tertiary,#94a3b8);margin-bottom:7px;'>Tanda tangan</div>
                        <div style='height:92px;border:0.5px dashed var(--color-border-secondary,#e2e8f0);border-radius:10px;background:var(--color-background-secondary,#f8fafc);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;'>
                            <div style='position:absolute;bottom:20px;left:14px;right:14px;height:0.5px;background:var(--color-border-secondary,#e2e8f0);'></div>
                            <div style='position:relative;z-index:1;'>{$imgHtml}</div>
                        </div>
                    </div>
                    <div>
                        <div style='font-size:10px;text-transform:uppercase;letter-spacing:0.12em;font-weight:600;color:var(--color-text-tertiary,#94a3b8);margin-bottom:7px;text-align:center;'>Tanggal</div>
                        <div style='height:92px;border:0.5px solid var(--color-border-secondary,#e2e8f0);border-radius:10px;background:var(--color-background-secondary,#f8fafc);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;'>
                            <div style='font-size:10px;font-weight:600;color:var(--color-text-tertiary,#94a3b8);text-transform:uppercase;letter-spacing:0.08em;'>Ditetapkan</div>
                            <div style='font-size:13px;font-weight:600;color:var(--color-text-primary,#0f172a);font-family:monospace;letter-spacing:0.03em;'>{$date}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
    }

    // ── Predefined Defaults ────────────────────────────────────────────────────

    private static function defaultAssessmentMethods(): array
    {
        return [
            ['method' => 'Observasi langsung',     'answer' => ''],
            ['method' => 'Kegiatan Terstruktur',   'answer' => ''],
            ['method' => 'Tanya Jawab',             'answer' => ''],
            ['method' => 'Verifikasi Portfolio',    'answer' => ''],
            ['method' => 'Reviu Produk',            'answer' => ''],
            ['method' => 'Verifikasi Pihak Ketiga', 'answer' => ''],
        ];
    }

    /**
     * Default contextualization.
     *
     * @return array
     */
    private static function defaultContextualization(): array
    {
        return [
            [
                'no'      => '3.1',
                'content' => [
                    ['type' => 'a. Karakteristik Kandidat:',                         'answer' => 'Tidak Ada', 'note' => ''],
                    ['type' => 'b. Kebutuhan kontekstualisasi terkait tempat kerja:', 'answer' => 'Tidak Ada', 'note' => ''],
                ],
            ],
            [
                'no'      => '3.2',
                'content' => [
                    ['type' => 'Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan', 'answer' => 'Tidak Ada', 'note' => ''],
                ],
            ],
            [
                'no'      => '3.3',
                'content' => [
                    ['type' => 'Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi', 'answer' => 'Tidak Ada', 'note' => ''],
                ],
            ],
            [
                'no'      => '3.4',
                'content' => [
                    ['type' => 'Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen', 'answer' => 'Tidak Ada', 'note' => ''],
                ],
            ],
        ];
    }
}