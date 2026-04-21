<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Ak01Resource\Pages;
use App\Filament\Resources\Ak01Resource\RelationManagers;
use App\Models\Ak01;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Ak01Resource extends Resource
{
    protected static ?string $model = Ak01::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'AK';
    protected static ?string $navigationLabel = 'AK.01';
    protected static ?string $modelLabel = 'AK.01';
    protected static ?string $pluralModelLabel = 'AK.01';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Persetujuan Asesmen dan Kerahasiaan')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('registration_id')
                                    ->relationship('registration', 'id')
                                    ->label('ID Pendaftaran')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $reg = \App\Models\AssessmentRegistration::with('scheme')->find($state);
                                            if ($reg && $reg->scheme) {
                                                $set('ak01_data.certification_scheme.name', $reg->scheme->name);
                                                $set('ak01_data.certification_scheme.code', $reg->scheme->code);
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
                                                'Sewaktu' => 'Sewaktu',
                                                'Tempat Kerja' => 'Tempat Kerja',
                                                'Mandiri' => 'Mandiri',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('asesor_name')
                                            ->label('Nama Asesor')
                                            ->required(),
                                        Forms\Components\TextInput::make('asesi_name')
                                            ->label('Nama Asesi')
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
                                            // ->columns(1) // mobile-style list
                                            ->default([
                                                ['label' => 'Hasil Verifikasi Portofolio', 'answer' => false],
                                                ['label' => 'Hasil Reviu Produk', 'answer' => false],
                                                ['label' => 'Hasil Observasi Langsung', 'answer' => false],
                                                ['label' => 'Hasil Kegiatan Terstruktur', 'answer' => false],
                                                ['label' => 'Hasil Tanya Jawab', 'answer' => false],
                                            ])
                                            ->addActionLabel('Tambah Bukti Lainnya')
                                            ->reorderable(true)
                                            ->addable(true)
                                            ->deletable(true),
                                    ]),

                                Forms\Components\Section::make('Pelaksanaan asesmen disepakati pada')
                                    ->statePath('agreement_time')
                                    ->schema([
                                        Forms\Components\DatePicker::make('date')
                                            ->label('Hari/ Tanggal')
                                            ->default(now())
                                            ->required(),
                                        Forms\Components\TimePicker::make('time')
                                            ->label('Waktu')
                                            ->default(now())
                                            ->required(),
                                        Forms\Components\TextInput::make('tuk')
                                            ->label('TUK (Lokasi)')
                                            ->required(),
                                    ])->columns(3),

                                Forms\Components\Grid::make(2)
                                    ->statePath('signatures')
                                    ->schema([
                                        Forms\Components\Section::make('Asesor')
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
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nama Asesor')
                                                    ->disabled()
                                                    ->dehydrated(),
                                                Forms\Components\DatePicker::make('date')
                                                    ->label('Tanggal')
                                                    ->default(now()),
                                                Forms\Components\Hidden::make('signature'),
                                                Forms\Components\Placeholder::make('sig_preview')
                                                    ->label('Preview Signature')
                                                    ->content(fn ($get) => $get('signature') ? new \Illuminate\Support\HtmlString("<img src='{$get('signature')}' width='150' class='border' />") : ''),
                                                Forms\Components\Placeholder::make('qr_preview')
                                                    ->label('QR Code')
                                                    ->content(fn ($get) => $get('qr_preview') ? new \Illuminate\Support\HtmlString("<img src='{$get('qr_preview')}' width='100' />") : ''),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ->filters([
                //
            ])
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
