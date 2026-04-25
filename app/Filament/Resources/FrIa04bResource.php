<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa04bResource\Pages;
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
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'IA (B)';
    protected static ?string $navigationLabel = 'FR.IA.04b';
    protected static ?string $modelLabel = 'FR.IA.04b';
    protected static ?string $pluralModelLabel = 'FR.IA.04b';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.04B - Penilaian Demonstrasi Proyek atau Kegiatan Terstruktur Lainnya')
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
                                            $reg = AssessmentRegistration::with(['scheme', 'candidate', 'assessor'])->find($state);
                                            if ($reg) {
                                                $set('fr_ia_04b_data.header.certification_scheme', $reg->scheme?->name);
                                                $set('fr_ia_04b_data.header.scheme_number', $reg->scheme?->code);
                                                $set('fr_ia_04b_data.header.tuk', $reg->tuk ?? '');
                                                $set('fr_ia_04b_data.header.assessor_name', $reg->assessor?->name);
                                                $set('fr_ia_04b_data.header.assessee_name', $reg->candidate?->name);
                                                $set('fr_ia_04b_data.header.date', now()->format('d-m-Y'));
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.04B'),

                        Forms\Components\Group::make()->statePath('fr_ia_04b_data')
                            ->schema([

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_number')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('structured_activity_title')->label('Judul Kegiatan Terstruktur'),
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\DatePicker::make('date')->label('Tanggal')->native(false),
                                    ]),

                                // Assessment Aspects
                                Forms\Components\Section::make('Aspek Penilaian')
                                    ->schema([
                                        Forms\Components\Repeater::make('assessment_aspects')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('no')->label('No')->numeric()->disabled()->dehydrated(),
                                                Forms\Components\Textarea::make('presentation_scope')->label('Lingkup Penyajian Proyek / Kegiatan Terstruktur')->rows(2)->columnSpanFull(),

                                                Forms\Components\Repeater::make('questions')
                                                    ->label('Daftar Pertanyaan')
                                                    ->schema([
                                                        Forms\Components\Textarea::make('text')->label('Pertanyaan')->rows(2),
                                                    ])
                                                    ->addActionLabel('Tambah Pertanyaan')
                                                    ->columns(1),

                                                Forms\Components\Repeater::make('competency_standard_compliance')
                                                    ->label('Kesesuaian dengan Standar Kompetensi Kerja')
                                                    ->schema([
                                                        Forms\Components\Textarea::make('text')->label('Unit/Elemen/KUK')->rows(2),
                                                    ])
                                                    ->addActionLabel('Tambah')
                                                    ->columns(1),

                                                Forms\Components\Radio::make('achievement')
                                                    ->label('Pencapaian')
                                                    ->options(['yes' => 'Ya', 'no' => 'Tidak'])
                                                    ->inline(),
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

                        // ================== TANDA TANGAN ==================
                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                // Asesi dihapus (akan diisi dari mobile app)
                                self::assessorSignatureBlock(),
                            ]),
                    ]),
            ]);
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
                            $set('date_signed', now()->format('d-m-Y'));
                            $sig = UserSignature::where('user_id', $user->id)->first() ?? UserSignature::where('name', $user->name)->first();
                            if ($sig) $set('signature_url', self::resolveSignatureUrl($sig->signature_image));
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('met_number'),
                Forms\Components\Hidden::make('date_signed'),
                Forms\Components\Hidden::make('signature_url'),

                Forms\Components\Placeholder::make('preview')
                    ->label('')
                    ->content(fn($get) => self::renderSignaturePreview($get, $label)),
            ]);
    }

    // ================== HANYA ASESSOR (Asesi dihapus) ==================
    private static function assessorSignatureBlock(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Asesor')
            ->statePath('fr_ia_04b_data.signatures.assessor')
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
                            $set('date_signed', now()->format('d-m-Y'));
                            $sig = UserSignature::where('user_id', $user->id)->first() ?? UserSignature::where('name', $user->name)->first();
                            if ($sig) $set('signature_url', self::resolveSignatureUrl($sig->signature_image));
                        }
                    }),
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('date_signed'),
                Forms\Components\Hidden::make('signature_url'),
                Forms\Components\Placeholder::make('preview')
                    ->content(fn($get) => self::renderSignaturePreview($get, 'Asesor')),
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

        $date = $get('date_signed') ?? now()->format('d-m-Y');
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.04B'))
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
            'index' => Pages\ListFrIa04bs::route('/'),
            'create' => Pages\CreateFrIa04b::route('/create'),
            'edit' => Pages\EditFrIa04b::route('/{record}/edit'),
        ];
    }
}