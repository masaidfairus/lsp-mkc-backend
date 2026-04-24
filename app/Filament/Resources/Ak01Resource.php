<?php
namespace App\Filament\Resources;
use App\Filament\Resources\Ak01Resource\Pages;
use App\Models\Ak01;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class Ak01Resource extends Resource
{
    protected static ?string $model = Ak01::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.01';
    protected static ?string $modelLabel = 'AK.01';
    protected static ?string $pluralModelLabel = 'AK.01';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Persetujuan Asesmen dan Kerahasiaan')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // 1. Select berdasarkan nama asesi, bukan ID
                                Forms\Components\Select::make('registration_id')
                                    ->label('Nama Asesi (ID Pendaftaran)')
                                    ->options(function () {
                                        $registrations = \App\Models\AssessmentRegistration::with(['candidate', 'scheme'])->get();
                                        $occurrences   = [];
                                        return $registrations->mapWithKeys(function ($reg) use (&$occurrences) {
                                            $key          = ($reg->user_id ?? 0) . '-' . ($reg->scheme_id ?? 0);
                                            $occurrences[$key] = ($occurrences[$key] ?? 0) + 1;
                                            $candidate    = $reg->candidate?->name ?? 'Unknown';
                                            $scheme       = $reg->scheme?->name ?? 'Tanpa Skema';
                                            return [$reg->id => "{$candidate} — {$scheme} #{$occurrences[$key]}"];
                                        });
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $reg = \App\Models\AssessmentRegistration::with(['scheme', 'candidate'])->find($state);
                                            if ($reg) {
                                                // Auto fill skema
                                                if ($reg->scheme) {
                                                    $set('ak01_data.certification_scheme.name', $reg->scheme->name);
                                                    $set('ak01_data.certification_scheme.code', $reg->scheme->code);
                                                }
                                                // 2. Auto fill nama asesi (read only)
                                                $set('ak01_data.asesi_name', $reg->candidate?->name ?? '');

                                                // 3. Auto fill TUK and Asesor
                                                if ($reg->tuk) {
                                                    $set('ak01_data.tuk', $reg->tuk);
                                                }
                                                if ($reg->assessor) {
                                                    $set('ak01_data.asesor_name', $reg->assessor->name);
                                                }
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Group::make()
                            ->statePath('ak01_data')
                            ->schema([
                                Forms\Components\Section::make('Skema Sertifikasi')
                                    ->statePath('certification_scheme')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Judul Skema')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('code')
                                            ->label('Nomor Skema')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(),
                                    ])->columns(2),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('tuk')
                                            ->label('TUK')
                                            ->options([
                                                'Sewaktu'      => 'Sewaktu',
                                                'Tempat Kerja' => 'Tempat Kerja',
                                                'Mandiri'      => 'Mandiri',
                                            ])
                                            ->required(),

                                        // 3. Nama asesor dari dropdown user role asesor
                                        Forms\Components\Select::make('asesor_name')
                                            ->label('Nama Asesor')
                                            ->options(function () {
                                                return \App\Models\User::role('asesor')
                                                    ->pluck('name', 'name');
                                            })
                                            ->searchable()
                                            ->required(),

                                        // 2. Nama asesi read only, auto fill dari pilihan registrasi
                                        Forms\Components\TextInput::make('asesi_name')
                                            ->label('Nama Asesi')
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                    ]),

                                Forms\Components\Section::make('Bukti yang akan dikumpulkan')
                                    ->schema([
                                        Forms\Components\Repeater::make('evidence_methods')
                                            ->label('Metode Bukti')
                                            ->schema([
                                                Forms\Components\Grid::make(4)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('label')
                                                            ->label('Metode/Bukti')
                                                            ->required()
                                                            ->columnSpan(3),
                                                        Forms\Components\Checkbox::make('answer')
                                                            ->label('Ya?')
                                                            ->columnSpan(1),
                                                    ]),
                                            ])
                                            ->default([
                                                ['label' => 'Hasil Verifikasi Portofolio',  'answer' => false],
                                                ['label' => 'Hasil Reviu Produk',           'answer' => false],
                                                ['label' => 'Hasil Observasi Langsung',     'answer' => false],
                                                ['label' => 'Hasil Kegiatan Terstruktur',   'answer' => false],
                                                ['label' => 'Hasil Tanya Jawab',            'answer' => false],
                                            ])
                                            ->addActionLabel('Tambah Bukti Lainnya')
                                            ->reorderable(true)
                                            ->addable(true)
                                            ->deletable(true),
                                    ]),

                                // 5. Bagian TTD — hanya dropdown dan QR preview
                                Forms\Components\Grid::make(2)
                                    ->statePath('signatures')
                                    ->schema([
                                        Forms\Components\Section::make('Tanda Tangan Asesor')
                                            ->statePath('asesor')
                                            ->schema([
                                                Forms\Components\Select::make('signature_id')
                                                    ->label('Pilih Tanda Tangan Asesor')
                                                    ->options(\App\Models\UserSignature::pluck('name', 'id'))
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $sig = \App\Models\UserSignature::find($state);
                                                        if ($sig) {
                                                            $set('name', $sig->name);
                                                            $set('signature', $sig->signature_image);
                                                            $set('qr_preview', $sig->qr_code);
                                                        }
                                                    }),
                                                Forms\Components\Hidden::make('name'),
                                                Forms\Components\Hidden::make('signature'),
                                                // QR preview saja
                                                Forms\Components\Placeholder::make('qr_preview')
                                                    ->label('QR Code Asesor')
                                                    ->content(fn ($get) => $get('qr_preview')
                                                        ? new \Illuminate\Support\HtmlString("<img src='{$get('qr_preview')}' width='120' />")
                                                        : '-'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('ak01_data'))
            ->columns([
                Tables\Columns\TextColumn::make('registration_id')
                    ->label('Registration ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('candidate.name')
                    ->label('Asesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme.name')
                    ->label('Skema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('confirmed_at')
                    ->label('Dikonfirmasi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAk01s::route('/'),
        ];
    }
}