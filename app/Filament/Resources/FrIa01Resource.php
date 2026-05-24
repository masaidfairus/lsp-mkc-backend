<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa01Resource\Pages;
use App\Filament\Traits\HasStandardIaForm;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use App\Models\User;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

class FrIa01Resource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.01';
    protected static ?string $modelLabel = 'FR.IA.01';
    protected static ?string $pluralModelLabel = 'FR.IA.01';
    protected static ?int $navigationSort = 1;
    
    /**
     * Mengambil eloquent query.
     *
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->where('document_type', 'FR.IA.01');
        
        if (auth()->user()->hasRole('asesor')) {
            $query->whereHas('registration', function ($q) {
                $q->where('assessor_id', auth()->id());
            });
        }
        
        return $query;
    }

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
                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                Forms\Components\Section::make('FR.IA.01. CL - Ceklis Observasi Aktivitas Di Tempat Kerja atau Tempat Kerja Simulasi')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(function () {
                                        $registrations = AssessmentRegistration::with(['candidate', 'scheme'])->get();
                                        $occurrences = [];
                                        return $registrations->mapWithKeys(function ($reg) use (&$occurrences) {
                                            $key = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
                                            $occurrences[$key] = ($occurrences[$key] ?? 0) + 1;
                                            $candidate = $reg->candidate?->name ?? 'Unknown';
                                            $scheme = $reg->scheme?->name ?? 'Tanpa Skema';
                                            return [$reg->id => "{$candidate} — {$scheme} #{$occurrences[$key]}"];
                                        });
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa01Data($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa01Data($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.01'),

                        Forms\Components\Group::make()
                            ->statePath('main_data')
                            ->schema([

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('scheme_name')->label('Judul Skema')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('scheme_code')->label('Nomor Skema')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('asesor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('asesi_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('tanggal')->label('Tanggal')->disabled()->dehydrated(),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Panduan Bagi Asesor - Ceklis Observasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('sections')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Placeholder::make('work_group_label')
                                                    ->label('Kelompok Pekerjaan')
                                                    ->content(fn($get) => new HtmlString('<strong class="text-primary-600">' . ($get('work_group') ?? '-') . '</strong>')),

                                                Forms\Components\Repeater::make('kuk_rows')
                                                    ->label('Elemen & KUK')
                                                    ->columns(6)
                                                    ->schema([
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->columnSpan(1)->disabled()->dehydrated(),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->columnSpan(2)->disabled()->dehydrated(),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('elemen')->label('Elemen')->columnSpan(1)->disabled()->dehydrated(),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('kuk')->label('Kriteria Unjuk Kerja')->columnSpan(2)->disabled()->dehydrated(),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('standar_industri')->label('Standar Industri / Tempat Kerja')->columnSpan(2),
                                                        Forms\Components\Group::make([
                                                            Forms\Components\Radio::make('pencapaian')
                                                                ->label('Pencapaian')
                                                                ->options(['ya' => 'Ya', 'tidak' => 'Tidak'])
                                                                ->inline()
                                                        ])->columnSpan(1),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('penilaian_lanjut')->label('Penilaian Lanjut')->columnSpan(3),
                                                    ])
                                                    ->extraAttributes(['style' => 'max-height: 400px; overflow-y: auto;']),

                                                Forms\Components\Textarea::make('umpan_balik')
                                                    ->label('Umpan Balik bagi Asesi')
                                                    ->rows(2),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn(array $state): ?string => 'Kelompok: ' . ($state['work_group'] ?? '')),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Rekomendasi')
                                    ->statePath('rekomendasi')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\Radio::make('hasil')
                                            ->label('Hasil Rekomendasi')
                                            ->options(['kompeten' => 'KOMPETEN', 'belum_kompeten' => 'BELUM KOMPETEN'])
                                            ->inline(),
                                        Forms\Components\Textarea::make('catatan_belum_kompeten')
                                            ->label('Catatan (jika Belum Kompeten)')
                                            ->rows(2),
                                    ]),
                            ]),

                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                self::standardAsesiDisclaimer(),
                                self::standardAssessorSignatureBlock('main_data.signatures.asesor'),
                            ]),
                    ]),
            ]);
    }

    /**
     * Populate ia01 data.
     *
     * @param  mixed  $state
     * @param  callable  $set
     * @param  callable  $get
     * @param  bool  $isUpdate
     * @return mixed
     */
    protected static function populateIa01Data($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with([
            'scheme.competencyUnits.workGroup',
            'scheme.competencyUnits.competencyElements.performanceCriteria',
            'candidate',
            'assessor'
        ])->find($state);

        if ($reg) {
            $set('main_data.header.scheme_name', $reg->scheme?->name);
            $set('main_data.header.scheme_code', $reg->scheme?->code);
            $set('main_data.header.asesi_name', $reg->candidate?->name);
            $set('main_data.header.asesor_name', $reg->assessor?->name);
            $set('main_data.header.tuk', $reg->tuk ?? '');
            
            if ($get && !$get('main_data.header.tanggal')) {
                $set('main_data.header.tanggal', now()->format('d-m-Y'));
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.asesor');
            }

            // Populate real Elemen + KUK if empty
            $existingSections = $get ? (array) $get('main_data.sections') : [];
            if (($isUpdate || empty($existingSections) || empty(array_filter(array_column($existingSections, 'work_group')))) && $reg->scheme) {
                $units = $reg->scheme->competencyUnits;
                $groupedUnits = $units->groupBy(fn($u) => $u->workGroup?->name ?? 'Unit Kompetensi');

                $sections = [];
                foreach ($groupedUnits as $wgName => $wgUnits) {
                    $kuk_rows = [];
                    foreach ($wgUnits as $unit) {
                        foreach ($unit->competencyElements as $element) {
                            foreach ($element->performanceCriteria as $kuk) {
                                $kuk_rows[] = [
                                    'unit_code'       => $unit->unit_code,
                                    'unit_title'      => $unit->unit_title,
                                    'elemen'          => $element->title,
                                    'kuk'             => $kuk->description,
                                    'standar_industri' => '',
                                    'pencapaian'      => null,
                                    'penilaian_lanjut' => '',
                                ];
                            }
                        }
                    }
                    $sections[] = [
                        'work_group' => $wgName,
                        'kuk_rows'   => $kuk_rows,
                        'umpan_balik' => '',
                    ];
                }
                $set('main_data.sections', $sections);
            }
        }
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
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration_id')->label('Registration ID')->sortable()->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi')->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema')->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    /**
     * Mengambil pages.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrIa01s::route('/'),
            'create' => Pages\CreateFrIa01::route('/create'),
            'edit' => Pages\EditFrIa01::route('/{record}/edit'),
        ];
    }
}