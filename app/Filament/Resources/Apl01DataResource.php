<?php
// app/Filament/Resources/Apl01Resource.php

namespace App\Filament\Resources;

use App\Models\Apl01;
use Filament\Resources\Resource;
use Filament\Forms\Form;          // ← pastikan ini ada
use Filament\Tables\Table;        // ← dan ini
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;

class Apl01DataResource extends Resource
{
    protected static ?string $model = Apl01::class;
    protected static ?string $navigationLabel = 'APL 01';
    protected static ?string $navigationGroup = 'Form Asesmen';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Data Pribadi')->schema([
                TextInput::make('nama')->required(),
                TextInput::make('nik')->label('No. KTP/NIK')->required(),
                TextInput::make('tempat_lahir')->required(),
                DatePicker::make('tanggal_lahir')->required(),
                Select::make('jenis_kelamin')
                    ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])
                    ->required(),
                TextInput::make('kebangsaan')->default('Indonesia'),
                TextInput::make('alamat')->columnSpanFull(),
                TextInput::make('rt_rw')->label('RT/RW'),
                TextInput::make('kelurahan'),
                TextInput::make('kecamatan'),
                TextInput::make('kabupaten'),
                TextInput::make('provinsi'),
                TextInput::make('kode_pos'),
                TextInput::make('no_telp')->label('No. HP'),
                TextInput::make('email')->email(),
                TextInput::make('pendidikan')->label('Kualifikasi Pendidikan'),
            ])->columns(2),

            Section::make('Data Pekerjaan')->schema([
                TextInput::make('instansi')->label('Nama Institusi/Perusahaan'),
                TextInput::make('pekerjaan')->label('Jabatan'),
                TextInput::make('alamat_instansi')->label('Alamat Kantor')->columnSpanFull(),
            ])->columns(2),

            Section::make('Data Sertifikasi')->schema([
                TextInput::make('nama_skema')->required(),
                TextInput::make('kode_skema')->required(),
                Select::make('tujuan_asesmen')
                    ->options([
                        'Sertifikasi' => 'Sertifikasi',
                        'PKT'         => 'Pengakuan Kompetensi Terkini (PKT)',
                        'RPL'         => 'Rekognisi Pembelajaran Lampau (RPL)',
                        'Lainnya'     => 'Lainnya',
                    ]),
                Select::make('status_terima')
                    ->options([
                        'Diterima' => 'Diterima',
                        'Ditolak'  => 'Ditolak',
                    ]),
            ])->columns(2),

            Section::make('Unit Kompetensi')->schema([
                Repeater::make('unit_kompetensi')
                    ->label('')
                    ->schema([
                        TextInput::make('kode')->label('Kode Unit')->required(),
                        TextInput::make('judul')->label('Judul Unit')->required()->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->addActionLabel('+ Tambah Unit'),
            ]),

            Section::make('Status')->schema([
                Select::make('status')
                    ->options([
                        'draft'   => 'Draft',
                        'selesai' => 'Selesai',
                    ])
                    ->default('draft'),
            ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable(),
                TextColumn::make('nama_skema'),
                TextColumn::make('tujuan_asesmen'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'selesai' => 'success',
                        default   => 'warning',
                    }),
                TextColumn::make('created_at')->date()->label('Dibuat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                TableAction::make('preview')
                    ->label('Preview PDF')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Apl01 $record) => route('preview.apl01', $record->id))
                    ->openUrlInNewTab(),

                TableAction::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Apl01 $record) => route('download.apl01', $record->id))
                    ->openUrlInNewTab(),
            ]);
    }
}