<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak05Resource\Pages;
use App\Models\Ak05;
use App\Models\AssessmentRegistration;
use App\Models\UserSignature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class Ak05Resource extends Resource
{
    use \App\Filament\Traits\HasStandardIaForm;
    protected static ?string $model = Ak05::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.05';
    protected static ?string $modelLabel = 'AK.05';
    protected static ?string $pluralModelLabel = 'AK.05';
    protected static ?int $navigationSort = 5;
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

                        // ── Registration Selector ──────────────────────────────
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
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!$state) return;
                                        $reg = AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
                                        if (!$reg) return;

                                        $set('main_data.header.certification_scheme', $reg->scheme?->name ?? '');
                                        $set('main_data.header.scheme_code', $reg->scheme?->code ?? '');
                                        $set('main_data.header.tuk', $reg->tuk ?? '');

                                        if ($reg->assessor) {
                                            $set('main_data.header.assessor_name', $reg->assessor->name);
                                            
                                            // Auto-populate assessor signature
                                            self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.assessor_footer.assessor_details');
                                        }

                                        // Pre-fill first assessment result row with this asesi
                                        $set('main_data.assessment_results', [
                                            [
                                                'no'             => 1,
                                                'assessee_name'  => $reg->candidate?->name ?? '',
                                                'recommendation' => null,
                                                'description'    => '',
                                            ],
                                        ]);
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
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('certification_scheme')
                                                    ->label('Skema Sertifikasi')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('scheme_code')
                                                    ->label('Kode Skema')
                                                    ->disabled()
                                                    ->dehydrated(),
                                            ]),
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('tuk')
                                                    ->label('TUK')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('assessor_name')
                                                    ->label('Nama Asesor')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Forms\Components\DatePicker::make('assessment_date')
                                                    ->label('Tanggal Asesmen')
                                                    ->default(fn () => now()->toDateString())
                                                    ->required(),
                                            ]),
                                    ]),

                                // ── Hasil Asesmen (Daftar Asesi) ──────────────
                                Forms\Components\Section::make('Rekapitulasi Hasil Asesmen')
                                    ->schema([
                                        Forms\Components\Repeater::make('assessment_results')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Grid::make(12)
                                                    ->schema([
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('no')
                                                            ->label('No.')
                                                            ->numeric()
                                                            ->columnSpan(1),

                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('assessee_name')
                                                            ->label('Nama Asesi')
                                                            ->columnSpan(5),

                                                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                                        Forms\Components\Select::make('recommendation')
                                                            ->label('Rekomendasi')
                                                            ->options([
                                                                'K'  => 'K — Kompeten',
                                                                'BK' => 'BK — Belum Kompeten',
                                                            ])
                                                            ->native(false)
                                                            ->columnSpan(3),

                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('description')
                                                            ->label('Keterangan (Kode/Judul Unit jika BK)')
                                                            ->columnSpan(3),
                                                    ]),
                                            ])
                                            ->addActionLabel('Tambah Asesi')
                                            ->reorderable(false)
                                            ->defaultItems(1),
                                    ]),

                                // ── Evaluasi Asesmen ──────────────────────────
                                Forms\Components\Section::make('Evaluasi Asesmen')
                                    ->statePath('evaluation_section')
                                    ->schema([
                                        Forms\Components\Textarea::make('positive_negative_aspects')
                                            ->label('Aspek Positif dan Negatif dalam Asesmen')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('rejection_records')
                                            ->label('Pencatatan Penolakan Hasil Asesmen')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('improvement_suggestions')
                                            ->label('Saran Perbaikan (Asesor/Personil Terkait)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                // ── Footer Asesor ─────────────────────────────
                                Forms\Components\Section::make('Tanda Tangan Asesor')
                                    ->statePath('assessor_footer')
                                    ->schema([
                                        Forms\Components\Textarea::make('notes')
                                            ->label('Catatan Umum')
                                            ->rows(2)
                                            ->columnSpanFull(),

                                        self::standardAssessorSignatureBlock('assessor_details'),
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
            ->columns([
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('Reg. ID')
                    ->sortable()
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable()
                    ->limit(35),
                // KOLOM TABEL: Menampilkan kolom data berupa teks (Text Column)
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

    /**
     * Mengambil pages.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAk05s::route('/'),
        ];
    }
}
