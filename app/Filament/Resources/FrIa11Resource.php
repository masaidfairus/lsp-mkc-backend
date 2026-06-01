<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa11Resource\Pages;
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

class FrIa11Resource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.11';
    protected static ?string $modelLabel = 'FR.IA.11';
    protected static ?string $pluralModelLabel = 'FR.IA.11';
    protected static ?int $navigationSort = 11;

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
                Forms\Components\Section::make('FR.IA.11 - CRP - Ceklis Reviu Produk')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(fn (?\Illuminate\Database\Eloquent\Model $record) => static::getFilteredRegistrationOptions($record))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa11Data($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa11Data($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.11'),

                        Forms\Components\Group::make()->statePath('main_data')
                            ->schema([

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('scheme_number')->label('Nomor Skema')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessment_date')->label('Tanggal')->disabled()->dehydrated(),
                                    ]),

                                // Rancangan Produk
                                Forms\Components\Section::make('Rancangan Produk atau Data Teknis')
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('product_details.product_name')->label('Nama produk yang dihasilkan'),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('product_details.industry_standard')->label('Standar Industri atau tempat kerja'),

                                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                        Forms\Components\Section::make('Spesifikasi produk secara umum')
                                            ->columns(3)
                                            ->schema([
                                                Forms\Components\Textarea::make('product_details.general_specifications.dimensions')->label('Dimensi produk')->rows(2),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('product_details.general_specifications.material')->label('Bahan produk'),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('product_details.general_specifications.weight')->label('Berat produk'),
                                            ]),

                                        Forms\Components\Textarea::make('product_details.technical_specifications')->label('Spesifikasi produk secara teknis')->rows(3),

                                        Forms\Components\DatePicker::make('product_details.usage_date')
                                            ->label('Tanggal pengoperasian / penggunaan')
                                            ->native(false),

                                        Forms\Components\FileUpload::make('product_details.product_image_url')
                                            ->label('Gambar produk (jika ada)')
                                            ->image()
                                            ->directory('ia11/products')
                                            ->visibility('public'),
                                    ]),

                                // Hasil Reviu Produk
                                Forms\Components\Section::make('Hasil Reviu Produk')
                                    ->schema([
                                        Forms\Components\Repeater::make('product_reviews')
                                            ->label('')
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('category_name')
                                                    ->label('Kategori')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->extraAttributes(['class' => 'font-bold text-lg']),
                                                
                                                Forms\Components\Repeater::make('review_criteria')
                                                    ->label('Kriteria Review')
                                                    ->schema([
                                                        Forms\Components\Textarea::make('criterion_text')->label('Kriteria')->rows(1)->columnSpan(4)->disabled()->dehydrated(),
                                                        Forms\Components\Group::make([
                                                            Forms\Components\Radio::make('achievement.is_yes')
                                                                ->label('Pencapaian')
                                                                ->options([
                                                                    true => 'Ya',
                                                                    false => 'Tidak'
                                                                ])
                                                                ->inline()
                                                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                                    // Sync with is_no for compatibility if needed
                                                                    $set('achievement.is_no', !$state);
                                                                }),
                                                        ])->columnSpan(2)
                                                    ])
                                                    ->columns(6)
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement(),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn(array $state): ?string => $state['category_name'] ?? ''),
                                    ]),

                                Forms\Components\Textarea::make('assessment_notes')->label('Catatan')->rows(3),

                                // Rekomendasi
                                Forms\Components\Section::make('Rekomendasi Asesor')
                                    ->schema([
                                        Forms\Components\Radio::make('assessor_recommendation.is_competent')
                                            ->label('Rekomendasi')
                                            ->options([true => 'KOMPETEN', false => 'Observasi Langsung Diperlukan'])
                                            ->inline()
                                            ->live(),

                                        Forms\Components\Group::make()
                                            ->visible(fn($get) => $get('assessor_recommendation.is_competent') === false)
                                            ->columns(2)
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.work_group')->label('Kelompok Pekerjaan'),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.unit')->label('Unit'),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.element')->label('Elemen'),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.kuk')->label('KUK'),
                                            ]),
                                    ]),

                                self::standardDocumentControlBlock('document_control'),
                            ]),

                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                self::standardAsesiDisclaimer(),
                                self::standardAssessorSignatureBlock('main_data.signatures.assessor'),
                            ]),
                    ]),
            ]);
    }

    /**
     * Populate ia11 data.
     *
     * @param  mixed  $state
     * @param  callable  $set
     * @param  callable  $get
     * @param  bool  $isUpdate
     * @return mixed
     */
    protected static function populateIa11Data($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with(['scheme.competencyUnits.workGroup', 'candidate', 'assessor'])->find($state);
        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_number', $reg->scheme?->code);
            $set('main_data.header.tuk', $reg->tuk ?? '');
            $set('main_data.header.assessor_name', $reg->assessor?->name);
            $set('main_data.header.assessee_name', $reg->candidate?->name);
            
            if ($get && !$get('main_data.header.assessment_date')) {
                $set('main_data.header.assessment_date', now()->format('d-m-Y'));
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
            }

            // Populate categories if empty
            $existingReviews = $get ? (array) $get('main_data.product_reviews') : [];
            if ($isUpdate || empty($existingReviews) || empty(array_filter(array_column($existingReviews, 'category_name')))) {
                $reviews = [
                    [
                        'category_no' => 1,
                        'category_name' => 'Spesifikasi produk',
                        'review_criteria' => [
                            ['criterion_text' => 'Ukuran produk sesuai rencana atau gambar kerja', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                            ['criterion_text' => 'Estetika/penampilan produk', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                            ['criterion_text' => 'Kebersihan dan kerapian permukaan produk', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                        ]
                    ],
                    [
                        'category_no' => 2,
                        'category_name' => 'Performa produk atau Karateristik Produk',
                        'review_criteria' => [
                            ['criterion_text' => 'Kesesuaian ukuran (dimensi dan/atau berat)', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                            ['criterion_text' => 'Kesesuaian dengan gambar kerja atau bentuk', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                            ['criterion_text' => 'Kerapian dan kerapatan sambungan', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                            ['criterion_text' => 'Pemasangan perlengkapan bahan penolong', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                            ['criterion_text' => 'Kualitas produk sesuai dengan rujukan', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                            ['criterion_text' => '.......................................', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                            ['criterion_text' => '.......................................', 'achievement' => ['is_yes' => null, 'is_no' => null]],
                        ]
                    ]
                ];
                $set('main_data.product_reviews', $reviews);
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.11'))
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
            ->actions([Tables\Actions\EditAction::make()])
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
            'index' => Pages\ListFrIa11s::route('/'),
            'create' => Pages\CreateFrIa11::route('/create'),
            'edit' => Pages\EditFrIa11::route('/{record}/edit'),
        ];
    }
}