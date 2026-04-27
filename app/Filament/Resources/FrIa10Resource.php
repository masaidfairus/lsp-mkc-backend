<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa10Resource\Pages;
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
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.10';
    protected static ?string $modelLabel = 'FR.IA.10';
    protected static ?string $pluralModelLabel = 'FR.IA.10';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.10 - VPK – Verifikasi Pihak Ketiga')
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
                                            $reg = AssessmentRegistration::with([
                                                'scheme.competencyUnits.workGroup',
                                                'candidate',
                                                'assessor'
                                            ])->find($state);

                                            if ($reg) {
                                                $set('fria10_data.header.certification_scheme', $reg->scheme?->name);
                                                $set('fria10_data.header.scheme_number', $reg->scheme?->code);
                                                $set('fria10_data.header.tuk', $reg->tuk ?? '');
                                                $set('fria10_data.header.assessor_name', $reg->assessor?->name);
                                                $set('fria10_data.header.assessee_name', $reg->candidate?->name);
                                                $set('fria10_data.header.date', now()->format('d-m-Y'));

                                                // Auto populate work groups
                                                if ($reg->scheme) {
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
                                                    $set('fria10_data.work_groups', $workGroups);
                                                }
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.10'),

                        Forms\Components\Group::make()
                            ->statePath('fria10_data')
                            ->schema([

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_number')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('date')->label('Tanggal')->disabled()->dehydrated(),
                                    ]),

                                Forms\Components\Section::make('Kelompok Pekerjaan')
                                    ->schema([
                                        Forms\Components\Repeater::make('work_groups')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('group_name')->label('Kelompok Pekerjaan')->disabled()->dehydrated(),
                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('no')->label('No')->disabled()->numeric(),
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled(),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled(),
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

                                Forms\Components\Section::make('Identitas Pihak Ketiga')
                                    ->schema([
                                        Forms\Components\TextInput::make('third_party_details.name')->label('Nama Pengawas / Atasan / Orang Lain'),
                                        Forms\Components\TextInput::make('third_party_details.workplace')->label('Tempat Kerja'),
                                        Forms\Components\Textarea::make('third_party_details.address')->label('Alamat')->rows(2),
                                        Forms\Components\TextInput::make('third_party_details.phone')->label('Telepon'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Verifikasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('verification_questions')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Textarea::make('question')->label('Pertanyaan')->rows(2)->columnSpan(4)->disabled()->dehydrated(),
                                                Forms\Components\Checkbox::make('is_yes')->label('Ya'),
                                                Forms\Components\Checkbox::make('is_no')->label('Tidak'),
                                            ])
                                            ->columns(6)
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement(),
                                    ]),

                                Forms\Components\Section::make('Penilaian Naratif')
                                    ->schema([
                                        Forms\Components\Textarea::make('narrative_assessment.relationship_with_assessee')->label('Apa hubungan Anda dengan asesi?')->rows(2),
                                        Forms\Components\TextInput::make('narrative_assessment.work_duration')->label('Berapa lama Anda bekerja dengan asesi?'),
                                        Forms\Components\Textarea::make('narrative_assessment.proximity_in_assessed_area')->label('Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?')->rows(2),
                                        Forms\Components\Textarea::make('narrative_assessment.technical_experience_qualification')->label('Apa pengalaman teknis dan/atau kualifikasi Anda di bidang yang dinilai?')->rows(3),
                                    ]),

                                Forms\Components\Section::make('Kesimpulan Keseluruhan')
                                    ->schema([
                                        Forms\Components\Textarea::make('overall_conclusion.is_consistent_with_standards')
                                            ->label('Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?')
                                            ->rows(3),
                                        Forms\Components\Textarea::make('overall_conclusion.further_training_needs')->label('Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi')->rows(2),
                                        Forms\Components\Textarea::make('overall_conclusion.other_comments')->label('Ada komentar lain')->rows(3),
                                    ]),
                            ]),

                        // Penyusun & Validator
                        Forms\Components\Section::make('Penyusun dan Validator')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Section::make('Penyusun Dokumen')
                                            ->schema([self::signatureRepeater('compilers', 'Penyusun')])
                                            ->columnSpan(1),
                                        Forms\Components\Section::make('Validator Dokumen')
                                            ->schema([self::signatureRepeater('validators', 'Validator')])
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        Forms\Components\Section::make('Tanda Tangan Asesor')
                            ->schema([self::assessorSignatureBlock()]),
                    ]),
            ]);
    }

    // ====================== SIGNATURE HELPER ======================
    private static function signatureRepeater(string $key, string $label): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make("document_control.{$key}")
            ->label('')
            ->schema([self::signatureSchema($label)])
            ->minItems(2)
            ->maxItems(2)
            ->disableItemCreation()
            ->disableItemDeletion()
            ->default([[], []]);
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
                            $set('reg_number', $user->registration_number ?? '');
                            $set('date', now()->format('d-m-Y'));

                            $sig = UserSignature::where('user_id', $user->id)->first()
                                ?? UserSignature::where('name', $user->name)->first();

                            if ($sig) {
                                $set('signature', self::resolveSignatureUrl($sig->signature_image));
                            }
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),

                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(fn($get) => self::renderSignaturePreview($get, $label)),
            ]);
    }

    private static function assessorSignatureBlock(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Asesor')
            ->statePath('fria10_data.overall_conclusion')
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pilih Asesor')
                    ->options(User::role('asesor')->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('assessor_signature', $user->name);
                            $set('date', now()->format('d-m-Y'));
                        }
                    }),
                Forms\Components\Hidden::make('assessor_signature'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Placeholder::make('preview')->content(fn($get) => self::renderSignaturePreview($get, 'Asesor')),
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
        $name = $get('name') ?? $get('assessor_signature') ?? '';
        if (!$name) {
            return new HtmlString('<div class="text-gray-400 italic text-sm">Pilih ' . $role . ' untuk preview</div>');
        }

        $regNumber = $get('reg_number') ?? 'N/A';
        $date = $get('date') ?? now()->format('d-m-Y');
        $signature = $get('signature');

        $imgHtml = $signature
            ? "<img src='" . htmlspecialchars($signature) . "' style='max-height:70px;object-fit:contain;' />"
            : "<div style='font-size:11px;color:#dc2626;font-style:italic;'>Tanda tangan belum diunggah</div>";

        $html = "
        <div style='font-family: system-ui, sans-serif; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; max-width: 480px; color: #0f172a;'>
            <div style='padding: 8px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;'>
                <span style='font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; color: #64748b;'>{$role}</span>
            </div>
            <div style='padding: 20px;'>
                <div style='display: flex; gap: 16px; align-items: flex-start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;'>
                    <div style='flex: 1;'>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 4px;'>Nama Lengkap</p>
                        <p style='font-size: 16px; font-weight: 600; margin: 0 0 14px;'>{$name}</p>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 6px;'>No. Registrasi MET</p>
                        <span style='font-size: 13px; font-weight: 500; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 12px; font-family: monospace;'>{$regNumber}</span>
                    </div>
                </div>
                <div style='display: grid; grid-template-columns: 1fr 130px; gap: 16px;'>
                    <div>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;'>Tanda Tangan</p>
                        <div style='height: 110px; border: 1px dashed #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; position: relative;'>
                            <div style='position: relative; z-index: 10;'>{$imgHtml}</div>
                        </div>
                    </div>
                    <div>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; text-align: center;'>Tanggal</p>
                        <div style='height: 110px; border: 1px solid #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;'>
                            <p style='font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em;'>Ditetapkan</p>
                            <p style='font-size: 15px; font-weight: 600; font-family: monospace;'>{$date}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>";

        return new HtmlString($html);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.10'))
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')->label('Registration ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi')->searchable(),
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrIa10s::route('/'),
            'create' => Pages\CreateFrIa10::route('/create'),
            'edit' => Pages\EditFrIa10::route('/{record}/edit'),
        ];
    }
}