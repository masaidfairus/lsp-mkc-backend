<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FrIa10Resource\Pages;
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

class FrIa10Resource extends Resource
{
    use HasStandardIaForm;

    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.10';
    protected static ?string $modelLabel = 'FR.IA.10';
    protected static ?string $pluralModelLabel = 'FR.IA.10';
    protected static ?int $navigationSort = 10;

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
                Forms\Components\Section::make('FR.IA.10 - VPK – Verifikasi Pihak Ketiga')
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
                                            self::populateIa10Data($state, $set, $get);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            self::populateIa10Data($state, $set, $get, true);
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.10'),

                        Forms\Components\Group::make()
                            ->statePath('main_data')
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
                                        Forms\Components\TextInput::make('date')->label('Tanggal')->disabled()->dehydrated(),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Kelompok Pekerjaan')
                                    ->schema([
                                        Forms\Components\Repeater::make('work_groups')
                                            ->label('')
                                            ->schema([
                                                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                Forms\Components\TextInput::make('group_name')->label('Kelompok Pekerjaan')->disabled()->dehydrated(),
                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->dehydrated()->numeric(),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
                                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled()->dehydrated(),
                                                    ])
                                                    ->columns(3)
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement(),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn(array $state): ?string => $state['group_name'] ?? ''),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Identitas Pihak Ketiga')
                                    ->schema([
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('third_party_details.name')->label('Nama Pengawas / Atasan / Orang Lain'),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('third_party_details.workplace')->label('Tempat Kerja'),
                                        Forms\Components\Textarea::make('third_party_details.address')->label('Alamat')->rows(2),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('third_party_details.phone')->label('Telepon'),
                                    ])->columns(2),

                                 // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                 Forms\Components\Section::make('Verifikasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('verification_questions')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Textarea::make('question')->label('Pertanyaan')->rows(2)->columnSpan(4)->required(),
                                                Forms\Components\Checkbox::make('is_yes')->label('Ya'),
                                                Forms\Components\Checkbox::make('is_no')->label('Tidak'),
                                            ])
                                            ->columns(6)
                                            ->default([
                                                ['question' => 'Apakah asesi bekerja dengan mempertimbangkan Kesehatan, Keamanan dan Keselamatan Kerja?', 'is_yes' => false, 'is_no' => false],
                                                ['question' => 'Apakah asesi berinteraksi dengan harmonis didalam kelompoknya?', 'is_yes' => false, 'is_no' => false],
                                                ['question' => 'Apakah asesi dapat mengelola tugas-tugas secara bersamaan?', 'is_yes' => false, 'is_no' => false],
                                                ['question' => 'Apakah asesi dapat dengan cepat beradaptasi dengan peralatan dan lingkungan yang baru?', 'is_yes' => false, 'is_no' => false],
                                                ['question' => 'Apakah asesi dapat merespon dengan cepat masalah-masalah yang ada di tempat kerjanya?', 'is_yes' => false, 'is_no' => false],
                                                ['question' => 'Apakah Anda bersedia dihubungi jika verifikasi lebih lanjut dari pernyataan ini diperlukan?', 'is_yes' => false, 'is_no' => false],
                                            ]),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Penilaian Naratif')
                                    ->schema([
                                        Forms\Components\Textarea::make('narrative_assessment.relationship_with_assessee')->label('Apa hubungan Anda dengan asesi?')->rows(2),
                                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                                        Forms\Components\TextInput::make('narrative_assessment.work_duration')->label('Berapa lama Anda bekerja dengan asesi?'),
                                        Forms\Components\Textarea::make('narrative_assessment.proximity_in_assessed_area')->label('Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?')->rows(2),
                                        Forms\Components\Textarea::make('narrative_assessment.technical_experience_qualification')->label('Apa pengalaman teknis dan/atau kualifikasi Anda di bidang yang dinilai?')->rows(3),
                                    ]),

                                // BAGIAN FORM: Grup bidang form berbingkai (Section)
                                Forms\Components\Section::make('Kesimpulan Keseluruhan')
                                    ->schema([
                                        Forms\Components\Textarea::make('overall_conclusion.is_consistent_with_standards')
                                            ->label('Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('overall_conclusion.further_training_needs')->label('Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi')->rows(2),
                                        Forms\Components\Textarea::make('overall_conclusion.other_comments')->label('Ada komentar lain')->rows(3),
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
     * Populate ia10 data.
     *
     * @param  mixed  $state
     * @param  callable  $set
     * @param  callable  $get
     * @param  bool  $isUpdate
     * @return mixed
     */
    protected static function populateIa10Data($state, callable $set, callable $get = null, bool $isUpdate = false)
    {
        $reg = AssessmentRegistration::with([
            'scheme.competencyUnits.workGroup',
            'candidate',
            'assessor'
        ])->find($state);

        if ($reg) {
            $set('main_data.header.certification_scheme', $reg->scheme?->name);
            $set('main_data.header.scheme_number', $reg->scheme?->code);
            $set('main_data.header.tuk', $reg->tuk ?? '');
            $set('main_data.header.assessor_name', $reg->assessor?->name);
            $set('main_data.header.assessee_name', $reg->candidate?->name);

            if ($get && !$get('main_data.header.date')) {
                $set('main_data.header.date', now()->format('d-m-Y'));
            }

            if ($reg->assessor_id) {
                self::populateAssessorSignature($reg->assessor_id, $set, 'main_data.signatures.assessor');
            }

            // Only repopulate work_groups if empty, missing units, or user changed registration
            $existingGroups = $get ? (array) $get('main_data.work_groups') : [];
            $hasUnits = false;
            foreach ($existingGroups as $group) {
                if (!empty($group['units'])) {
                    $hasUnits = true;
                    break;
                }
            }

            if (($isUpdate || empty($existingGroups) || !$hasUnits) && $reg->scheme) {
                $units = $reg->scheme->competencyUnits;
                $grouped = $units->groupBy(fn($u) => $u->workGroup?->name ?? 'Lainnya');

                $workGroups = [];
                foreach ($grouped as $wgName => $wgUnits) {
                    $workGroups[] = [
                        'group_name' => $wgName,
                        'units' => $wgUnits->map(fn($u, $idx) => [
                            'no' => $idx + 1,
                            'unit_code' => $u->unit_code,
                            'unit_title' => $u->unit_title,
                        ])->toArray(),
                    ];
                }
                $set('main_data.work_groups', $workGroups);
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.10'))
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
            'index' => Pages\ListFrIa10s::route('/'),
            'create' => Pages\CreateFrIa10::route('/create'),
            'edit' => Pages\EditFrIa10::route('/{record}/edit'),
        ];
    }
}