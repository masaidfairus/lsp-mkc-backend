<?php

namespace App\Filament\Traits;

use App\Models\FormTemplate;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;

trait HasFormTemplates
{
    protected function getSaveTemplateAction(): Action
    {
        return Action::make('saveTemplate')
            ->label('Simpan sebagai Template')
            ->icon('heroicon-o-bookmark')
            ->color(Color::Blue)
            ->form([
                TextInput::make('template_name')
                    ->label('Nama Template')
                    ->required()
                    ->placeholder('Misal: Standard Isian Teknik Jaringan'),
            ])
            ->action(function (array $data) {
                $formData = $this->form->getState();
                
                // We usually want to exclude some IDs or relationship keys if necessary, 
                // but for these documents, we usually want most of it.
                
                FormTemplate::create([
                    'user_id' => Auth::id(),
                    'name' => $data['template_name'],
                    'document_type' => $this->getTemplateDocumentType(),
                    'data' => $formData,
                ]);

                Notification::make()
                    ->title('Template berhasil disimpan')
                    ->success()
                    ->send();
            });
    }

    protected function getLoadTemplateAction(): Action
    {
        return Action::make('loadTemplate')
            ->label('Muat dari Template')
            ->icon('heroicon-o-arrow-down-tray')
            ->color(Color::Indigo)
            ->form([
                \Filament\Forms\Components\Select::make('template_id')
                    ->label('Pilih Template')
                    ->options(fn () => FormTemplate::where('document_type', $this->getTemplateDocumentType())
                        ->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
            ])
            ->action(function (array $data) {
                $template = FormTemplate::find($data['template_id']);
                if ($template) {
                    $this->form->fill($template->data);
                    
                    Notification::make()
                        ->title('Template berhasil dimuat')
                        ->success()
                        ->send();
                }
            });
    }

    /**
     * Override this in the page if the document type differs from the resource name.
     */
    protected function getTemplateDocumentType(): string
    {
        // Get the base name of the resource, e.g., Mapa01Resource
        $resourceClass = static::getResource();
        $parts = explode('\\', $resourceClass);
        return end($parts);
    }
}
