<?php
namespace App\Filament\Resources;
use App\Filament\Resources\Ak01Resource\Pages;
use App\Filament\Traits\HasStandardIaForm;
use App\Models\Ak01;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class Ak01Resource extends Resource
{
    use HasStandardIaForm;
    protected static ?string $model = Ak01::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.01';
    protected static ?string $modelLabel = 'AK.01';
    protected static ?string $pluralModelLabel = 'AK.01';
    protected static ?int $navigationSort = 1;
    
    /**
     * Mengambil eloquent query.
     *
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
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
                Forms\Components\Section::make('Persetujuan Asesmen dan Kerahasiaan')
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
                                            $reg = \App\Models\AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
                                            if ($reg) {
                                                if ($reg->scheme) {
                                                    $set('ak01_data.certification_scheme.name', $reg->scheme->name);
                                                    $set('ak01_data.certification_scheme.code', $reg->scheme->code);
                                                }
                                                $set('ak01_data.asesi_name', $reg->candidate?->name ?? '');

                                                if ($reg->tuk) {
                                                    $set('ak01_data.tuk', $reg->tuk);
                                                }
                                                if ($reg->assessor) {
                                                    $set('ak01_data.asesor_name', $reg->assessor->name);
                                                    
                                                    // Auto-populate assessor signature info
                                                    self::populateAssessorSignature($reg->assessor_id, $set, 'signatures.asesor');
                                                }
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Group::make()
                            ->statePath('ak01_data')
                            ->schema([
                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Skema Sertifikasi')
                                    ->statePath('certification_scheme')
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('name')
                                            ->label('Judul Skema')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('code')
                                            ->label('Nomor Skema')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                    ])->columns(2),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('tuk')
                                            ->label('TUK')
                                            ->disabled()
                                            ->dehydrated(),

                                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                                        Forms\Components\Select::make('asesor_name')
                                            ->label('Nama Asesor')
                                            ->disabled()
                                            ->searchable()
                                            ->required(),

                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('asesi_name')
                                            ->label('Nama Asesi')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Bukti yang akan dikumpulkan')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('evidence_selections')
                                            ->label('Metode Bukti')
                                            ->options([
                                                'Hasil Verifikasi Portofolio' => 'Hasil Verifikasi Portofolio',
                                                'Hasil Reviu Produk' => 'Hasil Reviu Produk',
                                                'Hasil Observasi Langsung' => 'Hasil Observasi Langsung',
                                                'Hasil Kegiatan Terstruktur' => 'Hasil Kegiatan Terstruktur',
                                                'Hasil Tanya Jawab' => 'Hasil Tanya Jawab',
                                                'Other' => 'Lainnya:',
                                            ])
                                            ->required()
                                            ->columns(2)
                                            ->reactive(),
                                        
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('evidence_other')
                                            ->label('Bukti Lainnya')
                                            ->placeholder('Sebutkan...')
                                            ->visible(fn ($get) => in_array('Other', (array) $get('evidence_selections')))
                                            ->columnSpanFull(),

                                        Forms\Components\DateTimePicker::make('assessment_schedule')
                                            ->label('Hari/Tanggal/Waktu Asesmen')
                                            ->required(),
                                    ]),
                                self::standardAsesiDisclaimer(),
                                self::standardAssessorSignatureBlock('signatures.asesor'),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('ak01_data'))
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ManageAk01s::route('/'),
        ];
    }
}