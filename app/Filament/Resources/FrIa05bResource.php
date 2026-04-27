<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa05bResource\Pages;
use App\Models\AssessmentInstrument;
use App\Models\AssessmentRegistration;
use App\Models\CompetencyUnit;
use App\Models\User;
use App\Models\UserSignature;
use App\Models\WorkGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class FrIa05bResource extends Resource
{
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'IA (B)';
    protected static ?string $navigationLabel = 'FR.IA.05b';
    protected static ?string $modelLabel = 'FR.IA.05b';
    protected static ?string $pluralModelLabel = 'FR.IA.05b';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.05B - LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
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
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $reg = AssessmentRegistration::with('scheme')->find($state);
                                            if ($reg && $reg->scheme) {
                                                $set('fria05b_data.header.certification_scheme', $reg->scheme->name);
                                                $set('fria05b_data.header.scheme_number', $reg->scheme->code ?? '');
                                                $set('fria05b_data.header.tuk', 'Sewaktu/Tempat Kerja/Mandiri');

                                                $workGroups = self::getMasterWorkGroups($reg->scheme_id);
                                                $set('fria05b_data.work_groups', $workGroups);
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.05B'),

                        Forms\Components\Group::make()->statePath('fria05b_data')
                            ->schema([

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_number')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                    ]),

                                // ================== MASTER DATA KELOMPOK & UNIT (READ ONLY) ==================
                                Forms\Components\Section::make('Kelompok Pekerjaan dan Unit Kompetensi')
                                    ->schema([
                                        Forms\Components\Repeater::make('work_groups')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('group_name')
                                                    ->label('Nama Kelompok Pekerjaan')
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->columnSpanFull(),

                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->dehydrated()->numeric(),
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled()->dehydrated()->columnSpan(2),
                                                        Forms\Components\Textarea::make('kuk')
                                                            ->label('Kriteria Unjuk Kerja (KUK)')
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->rows(3)
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->columns(3)
                                                    ->disabled()
                                                    ->dehydrated()
                                                    ->addActionLabel('Unit dari Master Data'),
                                            ])
                                            ->disabled()
                                            ->dehydrated()
                                            ->reorderable(false)
                                            ->deletable(false)
                                            ->cloneable(false)
                                            ->addActionLabel('Kelompok Pekerjaan dari Master Data'),
                                    ]),

                                // ================== KUNCI JAWABAN (Dropdown Abjad) ==================
                                Forms\Components\Section::make('Kunci Jawaban Pertanyaan Tertulis Pilihan Ganda')
                                    ->schema([
                                        Forms\Components\Repeater::make('answer_keys')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('question_no')
                                                    ->label('No. Soal')
                                                    ->numeric()
                                                    ->required(),

                                                Forms\Components\Select::make('key')
                                                    ->label('Kunci Jawaban')
                                                    ->options([
                                                        'A' => 'A',
                                                        'B' => 'B',
                                                        'C' => 'C',
                                                        'D' => 'D',
                                                        'E' => 'E',
                                                    ])
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->addActionLabel('Tambah Kunci Jawaban'),
                                    ]),

                                // Penyusun & Validator
                                Forms\Components\Section::make('Penyusun dan Validator')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Section::make('Penyusun')->schema([self::signatureRepeater('compilers', 'Penyusun')])->columnSpan(1),
                                                Forms\Components\Section::make('Validator')->schema([self::signatureRepeater('validators', 'Validator')])->columnSpan(1),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    /**
     * Fetch Master Data Kelompok Pekerjaan + Unit Kompetensi
     */
    private static function getMasterWorkGroups($schemeId): array
    {
        if (!$schemeId) return [];

        $workGroups = WorkGroup::where('scheme_id', $schemeId)
            ->with(['competencyUnits' => function ($query) {
                $query->orderBy('unit_code');
            }])
            ->orderBy('id')
            ->get();

        $result = [];

        foreach ($workGroups as $wg) {
            $units = [];
            foreach ($wg->competencyUnits as $unit) {
                $kukText = $unit->competencyElements
                    ->flatMap(fn($el) => $el->performanceCriteria->pluck('description'))
                    ->implode("\n");

                $units[] = [
                    'no'         => $unit->id, // atau tambahkan kolom order jika ada
                    'unit_code'  => $unit->unit_code,
                    'unit_title' => $unit->unit_title,
                    'kuk'        => $kukText ?: '-',
                ];
            }

            $result[] = [
                'group_name' => $wg->name,
                'units'      => $units,
            ];
        }

        return $result;
    }

    // ====================== SIGNATURE HELPERS ======================
    private static function signatureRepeater(string $key, string $label): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make("document_control.{$key}")
            ->label('')
            ->schema([self::signatureSchema($label)])
            ->minItems(1)
            ->maxItems(2)
            ->disableItemCreation()
            ->disableItemDeletion()
            ->default([[]]);
    }

    private static function signatureSchema(string $label): Forms\Components\Group
    {
        return Forms\Components\Group::make()
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label($label)
                    ->options(User::role('asesor')->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('name', $user->name);
                            $set('met_number', $user->registration_number ?? '');
                            $set('date_signature', now()->format('d-m-Y'));
                            $sig = UserSignature::where('user_id', $user->id)->first() ?? UserSignature::where('name', $user->name)->first();
                            if ($sig) $set('signature_url', self::resolveSignatureUrl($sig->signature_image));
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('met_number'),
                Forms\Components\Hidden::make('date_signature'),
                Forms\Components\Hidden::make('signature_url'),

                Forms\Components\Placeholder::make('preview')
                    ->content(fn($get) => self::renderSignaturePreview($get, $label)),
            ]);
    }

    private static function resolveSignatureUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'data:') || str_starts_with($path, 'http')) return $path;
        $normalized = preg_replace('#^/?storage/#', '', $path);
        return Storage::url($normalized);
    }

    private static function renderSignaturePreview($get, string $role): HtmlString
    {
        $name = $get('name') ?? '';
        if (!$name) {
            return new HtmlString('<div class="text-gray-400 italic text-sm">Pilih ' . $role . ' untuk preview</div>');
        }

        $date = $get('date_signature') ?? now()->format('d-m-Y');
        $signature = $get('signature_url');

        $imgHtml = $signature
            ? "<img src='" . htmlspecialchars($signature) . "' style='max-height:70px;object-fit:contain;' />"
            : "<div style='font-size:11px;color:#dc2626;font-style:italic;'>Belum ada tanda tangan</div>";

        $html = "
        <div style='font-family: system-ui, sans-serif; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; max-width: 480px; color: #0f172a;'>
            <div style='padding: 8px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;'>
                <span style='font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; color: #64748b;'>Tanda Tangan {$role}</span>
            </div>
            <div style='padding: 20px;'>
                <p style='font-size: 16px; font-weight: 600; margin: 0 0 14px;'>{$name}</p>
                <div style='height: 110px; border: 1px dashed #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center;'>
                    {$imgHtml}
                </div>
                <p style='text-align: center; margin-top: 8px; font-size: 13px;'>{$date}</p>
            </div>
        </div>";

        return new HtmlString($html);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.05B'))
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi'),
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrIa05bs::route('/'),
            'create' => Pages\CreateFrIa05b::route('/create'),
            'edit' => Pages\EditFrIa05b::route('/{record}/edit'),
        ];
    }
}