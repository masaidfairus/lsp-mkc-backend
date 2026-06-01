<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa04bResource\Pages;
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

class FrIa04bResource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-plus';
    protected static ?string $navigationGroup = 'IA (B)';
    protected static ?string $navigationLabel = 'FR.IA.04b';
    protected static ?string $modelLabel = 'FR.IA.04b';
    protected static ?string $pluralModelLabel = 'FR.IA.04b';
    protected static ?int $navigationSort = 1;

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
                Forms\Components\Section::make('FR.IA.04B - Penilaian Demonstrasi Proyek atau Kegiatan Terstruktur Lainnya')
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
                                            self::populateIa04bData($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa04bData($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.04B'),

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
                                        Forms\Components\TextInput::make('structured_activity_title')->label('Judul Kegiatan Terstruktur'),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\DatePicker::make('date')->label('Tanggal')->native(false),
                                    ]),

                                // Questions & Compliance
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                        Forms\Components\Section::make('Daftar Pertanyaan')
                                            ->schema([
                                                Forms\Components\Repeater::make('questions')
                                                    ->label('')
                                                    ->schema([
                                                        Forms\Components\Textarea::make('text')->label('Pertanyaan')->rows(2),
                                                    ])
                                                    ->addActionLabel('Tambah Pertanyaan')
                                                    ->columns(1),
                                            ])->columnSpan(1),

                                        // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                        Forms\Components\Section::make('Kesesuaian Standar Kompetensi')
                                            ->schema([
                                                Forms\Components\Repeater::make('competency_standard_compliance')
                                                    ->label('')
                                                    ->schema([
                                                        Forms\Components\Textarea::make('text')->label('Unit/Elemen/KUK')->rows(2),
                                                    ])
                                                    ->addActionLabel('Tambah')
                                                    ->columns(1),
                                            ])->columnSpan(1),
                                    ]),

                                // Assessment Aspects
                                Forms\Components\Section::make('Aspek Penilaian')
                                    ->schema([
                                        Forms\Components\Repeater::make('assessment_aspects')
                                            ->label('')
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('no')->label('No')->numeric()->disabled()->dehydrated(),
                                                Forms\Components\Textarea::make('presentation_scope')->label('Lingkup Penyajian Proyek / Kegiatan Terstruktur')->rows(2)->columnSpanFull(),

                                                Forms\Components\Group::make([
                                                    Forms\Components\Radio::make('achievement')
                                                        ->label('Pencapaian')
                                                        ->options(['yes' => 'Ya', 'no' => 'Tidak'])
                                                        ->inline()
                                                ])->columnSpanFull(),
                                            ])
                                            ->columns(1)
                                            ->addActionLabel('Tambah Aspek Penilaian'),
                                    ]),

                                // Rekomendasi
                                Forms\Components\Section::make('Rekomendasi Asesor')
                                    ->schema([
                                        Forms\Components\Radio::make('assessor_recommendation')
                                            ->label('Rekomendasi')
                                            ->options([
                                                'competent' => 'KOMPETEN',
                                                'not_yet_competent' => 'BELUM KOMPETEN',
                                            ])
                                            ->inline(),
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
     * Populate ia04b data.
     *
     * @param  mixed  $state
     * @param  callable  $set
     * @param  callable  $get
     * @param  bool  $isUpdate
     * @return mixed
     */
    protected static function populateIa04bData($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_number', $reg->scheme?->code);
            $set('main_data.header.tuk', $reg->tuk ?? '');
            $set('main_data.header.assessor_name', $reg->assessor?->name);
            $set('main_data.header.assessee_name', $reg->candidate?->name);
            
            if ($get && !$get('main_data.header.date')) {
                $set('main_data.header.date', now()->format('Y-m-d'));
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.04B'))
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration_id')->label('ID')->sortable()->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi'),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFrIa04bs::route('/'),
            'create' => Pages\CreateFrIa04b::route('/create'),
            'edit' => Pages\EditFrIa04b::route('/{record}/edit'),
        ];
    }
}