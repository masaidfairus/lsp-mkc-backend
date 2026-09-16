<?php

namespace App\Filament\Resources\DocumentListResource\Pages;

use App\Filament\Resources\DocumentListResource;
use Filament\Actions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDocumentLists extends ListRecords
{
    protected static string $resource = DocumentListResource::class;

    /**
     * Mengambil header actions.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create_admin')
                ->label('Tambah Dokumen Admin')
                ->color('gray')
                ->after(fn($record) => $record->administrativeProof()->create()),

            Actions\CreateAction::make('create_scheme_doc')
                ->label('Tambah Dokumen Skema')
                ->form([
                    Grid::make(2)->schema([
                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                        Select::make('scheme_id')
                            ->label('Pilih Skema')
                            ->relationship('portfolioScheme.scheme', 'name')
                            ->required()
                            ->searchable(),

                        // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                        Select::make('type')
                            ->label('Pilih Tipe Dokumen')
                            ->native(false)
                            ->options([
                                'portfolio' => 'Portofolio',
                                'basic' => 'Persyaratan Dasar',
                            ])
                            ->required()
                            ->live(),
                    ]),

                    // KOLOM INPUT FORM: Bidang input teks (Text Input)
                    TextInput::make('title')->required()
                        ->label('Judul Dokumen'),

                    Textarea::make('description')
                        ->label('Deskripsi Dokumen')
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        Toggle::make('is_mandatory')
                            ->label('Wajib Diisi?')
                            ->default(true),

                        // KOLOM INPUT FORM: Bidang input teks (Text Input)
                        TextInput::make('or_group')
                            ->label('Kelompok Pilihan (Pilih Salah Satu)')
                            ->helperText('Isi dengan kode yang sama untuk dokumen pilihan (Salah satu wajib)')
                            ->visible(fn(Get $get) => $get('type') === 'basic'),
                    ]),
                ])
                ->action(function (array $data) {
                    $doc = \App\Models\DocumentList::create(['title' => $data['title']]);

                    if ($data['type'] === 'portfolio') {
                        $doc->portfolioScheme()->create([
                            'scheme_id' => $data['scheme_id'],
                            'is_mandatory' => $data['is_mandatory'],
                        ]);
                    } else {
                        $doc->basicRequirementProof()->create([
                            'scheme_id' => $data['scheme_id'],
                            'is_mandatory' => $data['is_mandatory'],
                            'or_group' => $data['or_group'],
                        ]);
                    }
                }),
        ];
    }

    /**
     * Mengambil tabs.
     *
     * @return array
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Dokumen'),

            'administrasi' => Tab::make('Bukti Administratif')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('administrativeProof')),

            'portfolio' => Tab::make('Portfolio Skema')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('portfolioScheme')),

            'persyaratan_dasar' => Tab::make('Bukti Persyaratan Dasar')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('basicRequirementProof'))
        ];
    }
}
