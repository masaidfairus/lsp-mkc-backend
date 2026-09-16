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
    /**
     * Mengambil save template action.
     *
     * @return Action
     */
    protected function getSaveTemplateAction(): Action
    {
        return Action::make('saveTemplate')
            ->label('Simpan sebagai Template')
            ->icon('heroicon-o-bookmark')
            ->color(Color::Blue)
            ->form([
                // KOLOM INPUT FORM: Bidang input teks (Text Input)
                TextInput::make('template_name')
                    ->label('Nama Template')
                    ->required()
                    ->placeholder('Misal: Standard Isian Teknik Jaringan'),
            ])
            ->action(function (array $data) {
                $formData = $this->form->getState();

                // Strip field-field yang berkaitan dengan asesi/registrasi agar
                // tidak tersimpan ke dalam template (supaya saat load, field
                // asesi tidak tertimpa oleh data template).
                $formData = $this->stripAsesiFieldsFromData($formData);

                FormTemplate::create([
                    'user_id'       => Auth::id(),
                    'name'          => $data['template_name'],
                    'document_type' => $this->getTemplateDocumentType(),
                    'data'          => $formData,
                ]);

                Notification::make()
                    ->title('Template berhasil disimpan')
                    ->success()
                    ->send();
            });
    }

    /**
     * Mengambil load template action.
     *
     * @return Action
     */
    protected function getLoadTemplateAction(): Action
    {
        return Action::make('loadTemplate')
            ->label('Muat dari Template')
            ->icon('heroicon-o-arrow-down-tray')
            ->color(Color::Indigo)
            ->form([
                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
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
                    // Simpan state asesi saat ini sebelum di-fill (tidak memicu validasi form)
                    $preserved = $this->getPreservedAsesiState();

                    // Bersihkan data template dari field asesi
                    $templateData = $this->stripAsesiFieldsFromData($template->data ?? []);

                    // Gabungkan state asesi yang dipreservasi ke dalam template data sebelum di-fill
                    if (!empty($preserved['top'])) {
                        foreach ($preserved['top'] as $key => $value) {
                            $templateData[$key] = $value;
                        }
                    }

                    if (!empty($preserved['headers'])) {
                        foreach ($preserved['headers'] as $path => $value) {
                            data_set($templateData, $path, $value);
                        }
                    }

                    // Fill form dengan data gabungan (template + asesi preserved)
                    $this->form->fill($templateData);

                    Notification::make()
                        ->title('Template berhasil dimuat')
                        ->success()
                        ->send();
                }
            });
    }

    /**
     * Mengambil delete template action.
     *
     * @return Action
     */
    protected function getDeleteTemplateAction(): Action
    {
        return Action::make('deleteTemplate')
            ->label('Hapus Template')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Hapus Template')
            ->modalDescription(fn () => 'Pilih template yang ingin dihapus. Tindakan ini tidak dapat dibatalkan.')
            ->form([
                // PILIHAN FORM: Menu dropdown pilihan (Dropdown Select)
                \Filament\Forms\Components\Select::make('template_id')
                    ->label('Pilih Template yang Ingin Dihapus')
                    ->options(fn () => FormTemplate::where('document_type', $this->getTemplateDocumentType())
                        ->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
            ])
            ->action(function (array $data) {
                $template = FormTemplate::find($data['template_id']);
                if ($template) {
                    $template->delete();

                    Notification::make()
                        ->title('Template berhasil dihapus')
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
        $parts         = explode('\\', $resourceClass);
        return end($parts);
    }

    /**
     * Daftar top-level key dalam data form yang berkaitan dengan asesi/registrasi
     * yang TIDAK boleh disimpan ke template atau di-overwrite saat load template.
     *
     * Override method ini di page jika struktur field berbeda.
     *
     * @return array
     */
    protected function getTemplateExcludedTopLevelKeys(): array
    {
        return ['registration_id'];
    }

    /**
     * Daftar key di dalam sub-path "header" yang berkaitan dengan asesi/registrasi.
     * Key-key ini akan dikosongkan di dalam setiap nested array bernama "header".
     *
     * Override method ini di page jika nama field berbeda.
     *
     * @return array
     */
    protected function getTemplateExcludedHeaderKeys(): array
    {
        return [
            'asesi_name',
            'assessee_name',
            'asesor_name',
            'assessor_name',
            'scheme_name',
            'certification_scheme',
            'scheme_code',
            'tuk',
            'tanggal',
            'assessment_date',
            'assessment_date_start',
            'assessment_date_end',
        ];
    }

    /**
     * Hapus field-field asesi/registrasi dari array data form secara rekursif.
     * - Top-level key yang ada di getTemplateExcludedTopLevelKeys() dihapus.
     * - Semua nested array dengan key "header" akan dikosongkan key-key yang
     *   ada di getTemplateExcludedHeaderKeys().
     *
     * @param  array  $data
     * @return array
     */
    protected function stripAsesiFieldsFromData(array $data): array
    {
        // 1. Hapus top-level keys (mis. registration_id)
        foreach ($this->getTemplateExcludedTopLevelKeys() as $key) {
            unset($data[$key]);
        }

        // 2. Rekursif: cari semua nested array bernama "header" dan kosongkan
        //    field-field yang berkaitan dengan asesi.
        $data = $this->stripHeaderKeysRecursive($data, $this->getTemplateExcludedHeaderKeys());

        return $data;
    }

    /**
     * Rekursif: cari sub-array dengan key "header" dan kosongkan field asesi di dalamnya.
     *
     * @param  array  $data
     * @param  array  $headerKeys
     * @return array
     */
    private function stripHeaderKeysRecursive(array $data, array $headerKeys): array
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                if ($key === 'header') {
                    // Kosongkan field-field asesi di dalam "header"
                    foreach ($headerKeys as $hKey) {
                        if (array_key_exists($hKey, $value)) {
                            $value[$hKey] = null;
                        }
                    }
                } else {
                    // Lanjut rekursif ke dalam sub-array
                    $value = $this->stripHeaderKeysRecursive($value, $headerKeys);
                }
            }
        }
        unset($value);

        return $data;
    }

    /**
     * Simpan state form yang berkaitan dengan asesi/registrasi sebelum load template.
     *
     * @return array
     */
    protected function getPreservedAsesiState(): array
    {
        // Gunakan getRawState atau property $data untuk menghindari error validasi form
        $state = [];
        if (method_exists($this->form, 'getRawState')) {
            $state = $this->form->getRawState();
        }
        if (empty($state) && isset($this->data) && is_array($this->data)) {
            $state = $this->data;
        }

        $preserved = [];

        // Simpan top-level keys (mis. registration_id)
        foreach ($this->getTemplateExcludedTopLevelKeys() as $key) {
            if (array_key_exists($key, $state)) {
                $preserved['top'][$key] = $state[$key];
            }
        }

        // Simpan nilai header dari setiap sub-array (rekursif)
        $preserved['headers'] = $this->extractHeaderValues($state, $this->getTemplateExcludedHeaderKeys());

        return $preserved;
    }

    /**
     * Restore state asesi/registrasi yang sudah disimpan sebelumnya ke form.
     *
     * @param  array  $preserved
     * @return void
     */
    protected function restorePreservedAsesiState(array $preserved): void
    {
        // Restore top-level keys menggunakan Livewire $data property
        if (!empty($preserved['top'])) {
            foreach ($preserved['top'] as $key => $value) {
                data_set($this->data, $key, $value);
            }
        }

        // Restore nested header values menggunakan dot-notation
        if (!empty($preserved['headers'])) {
            foreach ($preserved['headers'] as $path => $value) {
                data_set($this->data, $path, $value);
            }
        }
    }

    /**
     * Rekursif: ekstrak nilai field-field header dari nested data.
     *
     * @param  array   $data
     * @param  array   $headerKeys
     * @param  string  $prefix
     * @return array
     */
    private function extractHeaderValues(array $data, array $headerKeys, string $prefix = ''): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $currentPath = $prefix ? "{$prefix}.{$key}" : $key;
                if ($key === 'header') {
                    foreach ($headerKeys as $hKey) {
                        if (array_key_exists($hKey, $value)) {
                            $result["{$currentPath}.{$hKey}"] = $value[$hKey];
                        }
                    }
                } else {
                    $nested = $this->extractHeaderValues($value, $headerKeys, $currentPath);
                    $result = array_merge($result, $nested);
                }
            }
        }

        return $result;
    }
}
