<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa08Resource\Pages;
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

class FrIa08Resource extends Resource
{
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.08';
    protected static ?string $modelLabel = 'FR.IA.08';
    protected static ?string $pluralModelLabel = 'FR.IA.08';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.08 - CVP - Ceklis Verifikasi Portofolio')
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
                                                $set('fria08_data.header.certification_scheme', $reg->scheme?->name);
                                                $set('fria08_data.header.scheme_number', $reg->scheme?->code);
                                                $set('fria08_data.header.tuk', $reg->tuk ?? '');
                                                $set('fria08_data.header.assessor_name', $reg->assessor?->name);
                                                $set('fria08_data.header.assessee_name', $reg->candidate?->name);
                                                $set('fria08_data.header.date', now()->format('d-m-Y'));

                                                if ($reg->scheme) {
                                                    $units = $reg->scheme->competencyUnits;
                                                    $followUps = $units->map(fn($u) => [
                                                        'is_checked' => false,
                                                        'unit_code' => $u->unit_code,
                                                        'element_no' => '',
                                                        'interview_substance' => '',
                                                    ])->toArray();
                                                    $set('fria08_data.follow_up_verification', $followUps);
                                                }
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.08'),

                        Forms\Components\Group::make()
                            ->statePath('fria08_data')
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

                                Forms\Components\Section::make('Verifikasi Bukti Portofolio')
                                    ->schema([
                                        Forms\Components\Repeater::make('portfolio_verification')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')->label('No')->numeric()->disabled()->dehydrated(),
                                                Forms\Components\Textarea::make('portfolio_evidence_name')->label('Bukti Portofolio')->rows(2)->columnSpan(3),

                                                Forms\Components\Section::make('Aturan Bukti')
                                                    ->columns(4)
                                                    ->schema([
                                                        Forms\Components\Checkbox::make('rules.valid.is_yes')->label('Valid Ya'),
                                                        Forms\Components\Checkbox::make('rules.valid.is_no')->label('Valid Tidak'),
                                                        Forms\Components\Checkbox::make('rules.authentic.is_yes')->label('Asli Ya'),
                                                        Forms\Components\Checkbox::make('rules.authentic.is_no')->label('Asli Tidak'),
                                                        Forms\Components\Checkbox::make('rules.current.is_yes')->label('Terkini Ya'),
                                                        Forms\Components\Checkbox::make('rules.current.is_no')->label('Terkini Tidak'),
                                                        Forms\Components\Checkbox::make('rules.sufficient.is_yes')->label('Memadai Ya'),
                                                        Forms\Components\Checkbox::make('rules.sufficient.is_no')->label('Memadai Tidak'),
                                                    ]),
                                            ])
                                            ->columns(5)
                                            ->addActionLabel('Tambah Bukti Portofolio'),
                                    ]),

                                Forms\Components\Section::make('Tindak Lanjut Verifikasi Bukti')
                                    ->schema([
                                        Forms\Components\Repeater::make('follow_up_verification')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Checkbox::make('is_checked')->label('Cek'),
                                                Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled(),
                                                Forms\Components\TextInput::make('element_no')->label('No. Elemen'),
                                                Forms\Components\Textarea::make('interview_substance')->label('Materi/Substansi Wawancara')->rows(2)->columnSpan(3),
                                            ])
                                            ->columns(6)
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->itemLabel(fn(array $state): ?string => $state['unit_code'] ?? ''),
                                    ]),

                                Forms\Components\Textarea::make('additional_evidence_details')
                                    ->label('Bukti Tambahan Diperlukan')
                                    ->rows(3),

                                Forms\Components\Section::make('Rekomendasi Asesor')
                                    ->schema([
                                        Forms\Components\Radio::make('assessor_recommendation.is_competent')
                                            ->label('Rekomendasi')
                                            ->options([true => 'KOMPETEN', false => 'Observasi Langsung Diperlukan'])
                                            ->inline()
                                            ->live(),

                                        Forms\Components\Group::make()
                                            ->visible(fn($get) => $get('assessor_recommendation.is_competent') === false)
                                            ->schema([
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.work_group')->label('Kelompok Pekerjaan'),
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.unit')->label('Unit'),
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.element')->label('Elemen'),
                                                Forms\Components\TextInput::make('assessor_recommendation.observation_details.kuk')->label('KUK'),
                                            ])
                                            ->columns(2),
                                    ]),

                                // Penyusun & Validator (style MAPA.02)
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
            ->statePath('fria08_data.signatures.assessor')
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Pilih Asesor')
                    ->options(User::role('asesor')->pluck('name', 'id'))
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($user = User::find($state)) {
                            $set('name', $user->name);
                            $set('reg_number', $user->registration_number ?? '');
                            $set('signature_and_date', now()->format('d-m-Y'));
                        }
                    }),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('signature_and_date'),
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
        $name = $get('name') ?? '';
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.08'))
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
            'index' => Pages\ListFrIa08s::route('/'),
            'create' => Pages\CreateFrIa08::route('/create'),
            'edit' => Pages\EditFrIa08::route('/{record}/edit'),
        ];
    }
}