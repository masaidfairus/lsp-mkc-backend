<?php

namespace App\Filament\Traits;

use App\Models\SignatureRequest;

/**
 * Trait ini ditambahkan ke semua Resource Page (Create & Edit) yang perlu
 * mengirimkan permintaan tanda tangan setelah dokumen disimpan.
 *
 * Cara pakai:
 *   class CreateFrIa01 extends CreateRecord {
 *       use HasSignatureRequestSpawner;
 *       // override getDocumentLabel() jika perlu
 *   }
 */
trait HasSignatureRequestSpawner
{
    /**
     * Label dokumen untuk ditampilkan di inbox.
     * Override di masing-masing page class jika perlu.
     */
    protected function getDocumentLabel(): string
    {
        return $this->getRecord()->document_type ?? 'Dokumen';
    }

    /**
     * Ekstrak pasangan [user_id, role] dari record yang baru disimpan.
     * Cocok untuk AssessmentInstrument (main_data.signatures & main_data.document_control).
     * Override method ini di page class jika struktur JSON berbeda (misal MAPA).
     */
    protected function extractSignerMappings($record): array
    {
        $mainData = $record->main_data ?? [];
        $mappings = [];

        $label = $this->getDocumentLabel();

        // Pengecualian: FR.IA.05A dan FR.IA.06A memiliki alur khusus (Asesi kerja -> Asesor TTD Form C -> Asesi TTD)
        // Jadi kita tidak otomatis men-spawn TTD Asesor & Asesi pada saat form soal (A) ini dibuat.
        $excludedAutoSpawns = ['FR.IA.05A', 'FR.IA.06A'];

        if (!in_array($label, $excludedAutoSpawns)) {
            // Asesor (path bisa 'asesor' atau 'assessor')
            $asesorId = data_get($mainData, 'signatures.asesor.user_id')
                     ?? data_get($mainData, 'signatures.assessor.user_id');

            // Fallback: baca langsung dari relasi registration
            if (!$asesorId && method_exists($record, 'registration')) {
                $asesorId = optional($record->registration)->assessor_id;
            }

            if ($asesorId) {
                // Auto-Sign Master Data: Hanya spawn permintaan TTD manual untuk form AK.04 dan AK.06
                $cleanLabel = str_replace('FR.', '', $label);
                if (in_array($cleanLabel, ['AK.04', 'AK.06'])) {
                    $mappings[] = ['user_id' => (int) $asesorId, 'role' => 'Asesor'];
                }
            }

            // Asesi (Assessee) - Hanya request manual untuk form AK.02, AK.04, dan AK.06 karena tidak reusable
            $cleanLabel = str_replace('FR.', '', $label);
            if (in_array($cleanLabel, ['AK.02', 'AK.04', 'AK.06'])) {
                if (method_exists($record, 'registration') && $record->registration) {
                    $asesiId = $record->registration->user_id;
                    if ($asesiId) {
                        $mappings[] = ['user_id' => (int) $asesiId, 'role' => 'asesi'];
                    }
                }
            }
        }

        // Penyusun (dalam document_control.compilers)
        $compilers = data_get($mainData, 'document_control.compilers', []);
        foreach ($compilers as $compiler) {
            $uid = data_get($compiler, 'user_id');
            if ($uid) {
                $mappings[] = ['user_id' => (int) $uid, 'role' => 'Penyusun'];
            }
        }

        // Validator (dalam document_control.validators)
        $validators = data_get($mainData, 'document_control.validators', []);
        foreach ($validators as $validator) {
            $uid = data_get($validator, 'user_id');
            if ($uid) {
                $mappings[] = ['user_id' => (int) $uid, 'role' => 'Validator'];
            }
        }

        return $mappings;
    }

    /**
     * Sync signature requests setelah create/save.
     * - Buat request baru jika belum ada (status pending)
     * - Hapus request lama yang masih pending jika user diganti
     */
    protected function syncSignatureRequests($record): void
    {
        $registrationId = $record->registration_id ?? null;
        if (!$registrationId) return;

        $mappings = $this->extractSignerMappings($record);

        // Hapus pending requests yang user-nya tidak lagi dipilih
        $selectedUserRolePairs = collect($mappings)->map(
            fn($m) => $m['user_id'] . '_' . $m['role']
        )->toArray();

        SignatureRequest::where('signable_type', get_class($record))
            ->where('signable_id', $record->id)
            ->where('status', 'pending')
            ->get()
            ->each(function ($req) use ($selectedUserRolePairs) {
                $key = $req->user_id . '_' . $req->role;
                if (!in_array($key, $selectedUserRolePairs)) {
                    $req->delete();
                }
            });

        // Buat request baru untuk yang belum ada
        foreach ($mappings as $mapping) {
            $exists = SignatureRequest::where('signable_type', get_class($record))
                ->where('signable_id', $record->id)
                ->where('user_id', $mapping['user_id'])
                ->where('role', $mapping['role'])
                ->exists();

            if (!$exists) {
                SignatureRequest::create([
                    'signable_type'  => get_class($record),
                    'signable_id'    => $record->id,
                    'registration_id' => $registrationId,
                    'document_label' => $this->getDocumentLabel(),
                    'user_id'        => $mapping['user_id'],
                    'role'           => $mapping['role'],
                    'status'         => 'pending',
                ]);
            }
        }
    }

    /**
     * Hook afterCreate — dipanggil otomatis oleh Filament setelah record dibuat.
     */
    protected function afterCreate(): void
    {
        $this->syncSignatureRequests($this->getRecord());
    }

    /**
     * Hook afterSave — dipanggil otomatis oleh Filament setelah record disimpan (edit).
     */
    protected function afterSave(): void
    {
        $this->syncSignatureRequests($this->getRecord());
    }
}
