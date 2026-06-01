<?php

namespace App\Filament\Resources\AssessmentRegistrationResource\RelationManagers;

use App\Models\Apl02Portfolio;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PortfolioFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'aplPortfolio';

    protected static ?string $title = 'Dokumen Portofolio - APL.02';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    /**
     * Cache: list_id => Apl02Portfolio (dengan fileProfile ter-load)
     * Di-build di getTableQuery(), dipakai di getStateUsing()
     */
    protected \Illuminate\Support\Collection $portfolioMap;

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $registration = $this->getOwnerRecord();

        // Build map: document_list.id => Apl02Portfolio
        // list_id ada di file_profiles, bukan di apl02_portfolios
        // Jadi kita index by fileProfile->list_id
        $this->portfolioMap = Apl02Portfolio::query()
            ->where('registration_id', $registration->id)
            ->with('fileProfile')
            ->get()
            ->filter(fn($p) => $p->fileProfile !== null)   // buang yang file_profile-nya null
            ->keyBy(fn($p) => $p->fileProfile->list_id);   // index by list_id dari file_profiles

        return \App\Models\DocumentList::query()
            ->whereHas('portfolioScheme', fn($q) => $q->where('scheme_id', $registration->scheme_id))
            ->orderBy('id', 'asc');
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50])
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Portofolio Skema'),

                Tables\Columns\TextColumn::make('berkas')
                    ->label('Berkas')
                    ->html()
                    ->getStateUsing(function (\App\Models\DocumentList $record) {
                        /** @var \App\Models\Apl02Portfolio|null $portfolio */
                        $portfolio = $this->portfolioMap[$record->id] ?? null;

                        $hasFile = $portfolio
                            && $portfolio->fileProfile
                            && $portfolio->fileProfile->upload_document;

                        if ($hasFile) {
                            $url = route('private.file.view', $portfolio->fileProfile->id);
                            return "
                                <a href='{$url}'
                                   target='_blank'
                                   style='color: #f59e0b; font-weight: 600; text-decoration: none;'
                                   onmouseover=\"this.style.textDecoration='underline'\"
                                   onmouseout=\"this.style.textDecoration='none'\">
                                    Lihat Dokumen
                                </a>
                            ";
                        }

                        return "<span style='color: #6b7280; font-style: italic;'>Dokumen Tidak Tersedia</span>";
                    }),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}   