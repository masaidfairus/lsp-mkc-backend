<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FrIa02Resource\Pages;
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

class FrIa02Resource extends Resource
{
    protected static ?string $model = AssessmentInstrument::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'IA';
    protected static ?string $navigationLabel = 'FR.IA.02';
    protected static ?string $modelLabel = 'FR.IA.02';
    protected static ?string $pluralModelLabel = 'FR.IA.02';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('FR.IA.02. TPD - Tugas Praktik Demonstrasi')
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
                                                $set('main_data.header.certification_scheme', $reg->scheme?->name);
                                                $set('main_data.header.scheme_code', $reg->scheme?->code);
                                                $set('main_data.header.assessee_name', $reg->candidate?->name);
                                                $set('main_data.header.assessor_name', $reg->assessor?->name);
                                                $set('main_data.header.tuk', $reg->tuk ?? '');
                                                $set('main_data.header.assessment_date', now()->format('d-m-Y'));

                                                // Populate demonstration_tasks sesuai payload
                                                if ($reg->scheme) {
                                                    $units = $reg->scheme->competencyUnits;
                                                    $grouped = $units->groupBy(fn($u) => $u->workGroup?->name ?? 'Unit Kompetensi');

                                                    $tasks = [];
                                                    foreach ($grouped as $wgName => $wgUnits) {
                                                        $tasks[] = [
                                                            'work_group' => $wgName,
                                                            'units' => $wgUnits->map(fn($u) => [
                                                                'unit_code' => $u->unit_code,
                                                                'unit_title' => $u->unit_title,
                                                            ])->toArray(),
                                                            'scenario' => '',
                                                            'equipments' => [],
                                                            'time_allocation' => '',
                                                        ];
                                                    }
                                                    $set('main_data.demonstration_tasks', $tasks);
                                                }
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Hidden::make('document_type')->default('FR.IA.02'),

                        Forms\Components\Group::make()
                            ->statePath('main_data')
                            ->schema([

                                Forms\Components\Section::make('Header Dokumen')
                                    ->statePath('header')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('certification_scheme')->label('Judul Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('scheme_code')->label('Nomor Skema')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('tuk')->label('TUK')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessor_name')->label('Nama Asesor')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessee_name')->label('Nama Asesi')->disabled()->dehydrated(),
                                        Forms\Components\TextInput::make('assessment_date')->label('Tanggal')->disabled()->dehydrated(),
                                    ]),

                                // Demonstration Tasks sesuai payload
                                Forms\Components\Section::make('Tugas Praktik Demonstrasi')
                                    ->schema([
                                        Forms\Components\Repeater::make('demonstration_tasks')
                                            ->label('')
                                            ->schema([
                                                Forms\Components\Placeholder::make('work_group_label')
                                                    ->label('Kelompok Pekerjaan')
                                                    ->content(fn($get) => new HtmlString('<strong class="text-primary-600">' . ($get('work_group') ?? '-') . '</strong>')),

                                                Forms\Components\Repeater::make('units')
                                                    ->label('Unit Kompetensi')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('unit_code')->label('Kode Unit')->disabled()->dehydrated(),
                                                        Forms\Components\TextInput::make('unit_title')->label('Judul Unit')->disabled()->dehydrated(),
                                                    ])
                                                    ->columns(2)
                                                    ->disableItemCreation()
                                                    ->disableItemDeletion()
                                                    ->disableItemMovement(),

                                                Forms\Components\Textarea::make('scenario')
                                                    ->label('Skenario / Petunjuk Tugas Demonstrasi')
                                                    ->rows(3)
                                                    ->columnSpanFull(),

                                                Forms\Components\Repeater::make('equipments')
                                                    ->label('Peralatan / Perlengkapan')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('item')->label('Peralatan'),
                                                    ])
                                                    ->columns(1)
                                                    ->addActionLabel('Tambah Peralatan')
                                                    ->default([]),

                                                Forms\Components\TextInput::make('time_allocation')
                                                    ->label('Alokasi Waktu (contoh: 120 Menit)'),
                                            ])
                                            ->disableItemCreation()
                                            ->disableItemDeletion()
                                            ->disableItemMovement()
                                            ->itemLabel(fn(array $state): ?string => $state['work_group'] ?? 'Tugas Demonstrasi'),
                                    ]),

                            ]),

                        Forms\Components\Section::make('Tanda Tangan')
                            ->schema([
                                self::assessorSignatureBlock('assessor', 'Asesor'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('document_type', 'FR.IA.02'))
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
            'index' => Pages\ListFrIa02s::route('/'),
            'create' => Pages\CreateFrIa02::route('/create'),
            'edit' => Pages\EditFrIa02::route('/{record}/edit'),
        ];
    }

    // ====================== SIGNATURE HELPER ======================
    private static function assessorSignatureBlock(string $key, string $label): Forms\Components\Section
    {
        return Forms\Components\Section::make($label)
            ->statePath("validation_data.signatures.{$key}")
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label("Pilih {$label}")
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
                                $set('qr_code', self::resolveSignatureUrl($sig->qr_code));
                            }
                        }
                    }),

                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('reg_number'),
                Forms\Components\Hidden::make('date'),
                Forms\Components\Hidden::make('signature'),
                Forms\Components\Hidden::make('qr_code'),

                Forms\Components\Placeholder::make('sig_preview')
                    ->label('')
                    ->content(fn ($get) => self::renderSignaturePreview($get)),
            ]);
    }

    private static function resolveSignatureUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'data:') || str_starts_with($path, 'http')) return $path;
        $normalized = preg_replace('#^/?storage/#', '', $path);
        return Storage::url($normalized);
    }

    private static function renderSignaturePreview($get): HtmlString
    {
        $name = $get('name') ?? '';
        if (!$name) {
            return new HtmlString('<div class="text-gray-400 italic text-sm">Pilih Asesor untuk melihat preview tanda tangan</div>');
        }

        $regNumber = $get('reg_number') ?? 'N/A';
        $date = $get('date') ?? now()->format('d-m-Y');
        $signature = $get('signature');
        $qrCode = $get('qr_code');

        $imgHtml = $signature
            ? "<img src='" . htmlspecialchars($signature) . "' style='max-height:70px;object-fit:contain;' />"
            : "<div style='font-size:11px;color:#dc2626;font-style:italic;'>Tanda tangan belum diunggah</div>";

        $qrHtml = $qrCode
            ? "<img src='" . htmlspecialchars($qrCode) . "' style='width:70px;height:70px;object-fit:contain;' />"
            : '';

        $html = "
        <div style='font-family: system-ui, sans-serif; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; max-width: 480px; color: #0f172a;'>
            <div style='padding: 8px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;'>
                <span style='font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600; color: #64748b;'>Tanda Tangan Asesor</span>
            </div>
            <div style='padding: 20px;'>
                <div style='display: flex; gap: 16px; align-items: flex-start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;'>
                    <div style='flex: 1;'>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 4px;'>Nama Lengkap</p>
                        <p style='font-size: 16px; font-weight: 600; margin: 0 0 14px;'>{$name}</p>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 6px;'>No. Registrasi MET</p>
                        <span style='font-size: 13px; font-weight: 500; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 12px; font-family: monospace;'>{$regNumber}</span>
                    </div>
                    <div style='width: 80px; height: 80px; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f8fafc; flex-shrink: 0;'>
                        {$qrHtml}
                    </div>
                </div>
                <div style='display: grid; grid-template-columns: 1fr 130px; gap: 16px;'>
                    <div>
                        <p style='font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;'>Tanda Tangan</p>
                        <div style='height: 110px; border: 1px dashed #e2e8f0; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; position: relative;'>
                            <div style='position: absolute; bottom: 18px; left: 20px; right: 20px; height: 1px; background: #e2e8f0;'></div>
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
}