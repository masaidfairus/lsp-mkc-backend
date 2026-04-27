<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa05aResource\Pages;
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

class FrIa05aResource extends Resource
{
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'IA (A)';
    protected static ?string $navigationLabel = 'FR.IA.05a';
    protected static ?string $modelLabel = 'FR.IA.05a';
    protected static ?string $pluralModelLabel = 'FR.IA.05a';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.05A - DPT - Daftar Pertanyaan Tertulis Pilihan Ganda')
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
                                                $set('fria05a_data.header.certification_scheme', $reg->scheme?->name);
                                                $set('fria05a_data.header.scheme_code', $reg->scheme?->code);
                                                $set('fria05a_data.header.tuk', $reg->tuk ?? '');
                                                $set('fria05a_data.header.assessor_name', $reg->assessor?->name);
                                                $set('fria05a_data.header.assessee_name', $reg->candidate?->name);
                                                $set('fria05a_data.header.assessment_date', now()->format('d-m-Y'));
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.05A'),

                        Forms\Components\Group::make()->statePath('fria05a_data')
                            ->schema([

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Skema Sertifikasi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_code')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\DatePicker::make('assessment_date')->label('Tanggal')->native(false),
                                        Forms\Components\TimePicker::make('assessment_time')->label('Waktu')->native(false),
                                    ]),

                                // Daftar Pertanyaan
                                Forms\Components\Section::make('Daftar Pertanyaan Pilihan Ganda')
                                    ->schema([
                                        Forms\Components\Repeater::make('questions_list')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\TextInput::make('question_no')->label('No')->numeric()->disabled()->dehydrated(),
                                                Forms\Components\Textarea::make('question_text')->label('Pertanyaan')->rows(3)->columnSpanFull(),

                                                Forms\Components\Repeater::make('options')
                                                    ->label('Pilihan Jawaban')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('label')->label('Label')->default(fn($get) => chr(97 + $get('../../options')?->count() ?? 0))->disabled()->dehydrated(),
                                                        Forms\Components\Textarea::make('option_text')->label('Teks Pilihan')->rows(2)->columnSpan(4),
                                                    ])
                                                    ->columns(5)
                                                    ->addActionLabel('Tambah Pilihan')
                                                    ->default([
                                                        ['label' => 'a', 'option_text' => ''],
                                                        ['label' => 'b', 'option_text' => ''],
                                                        ['label' => 'c', 'option_text' => ''],
                                                        ['label' => 'd', 'option_text' => ''],
                                                    ]),
                                            ])
                                            ->addActionLabel('Tambah Pertanyaan')
                                            ->itemLabel(fn($state) => 'Pertanyaan ' . ($state['question_no'] ?? '')),
                                    ]),

                                // Penyusun & Validator
                                Forms\Components\Section::make('Penyusun dan Validator')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Section::make('Penyusun')
                                                    ->schema([self::signatureRepeater('compilers', 'Penyusun')])
                                                    ->columnSpan(1),
                                                Forms\Components\Section::make('Validator')
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
                            $set('date', now()->format('d-m-Y'));
                            $sig = UserSignature::where('user_id', $user->id)->first() ?? UserSignature::where('name', $user->name)->first();
                            if ($sig) $set('signature', self::resolveSignatureUrl($sig->signature_image));
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('met_number'),
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
            ->statePath('fria05a_data.signatures.assessor')
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

        $regNumber = $get('reg_number') ?? $get('met_number') ?? 'N/A';
        $date = $get('date') ?? $get('signature_and_date') ?? now()->format('d-m-Y');
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
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.05A'))
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('registration.candidate.name')->label('Asesi'),
                Tables\Columns\TextColumn::make('registration.scheme.name')->label('Skema'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFrIa05as::route('/'),
            'create' => Pages\CreateFrIa05a::route('/create'),
            'edit' => Pages\EditFrIa05a::route('/{record}/edit'),
        ];
    }
}