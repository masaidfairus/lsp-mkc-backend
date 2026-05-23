<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PrivateFileController;
use App\Http\Controllers\PDF\PdfAk01Controller;
use App\Http\Controllers\PDF\PdfAk02Controller;
use App\Http\Controllers\PDF\PdfAk03Controller;
use App\Http\Controllers\PDF\PdfAk04Controller;
use App\Http\Controllers\PDF\PdfAk05Controller;
use App\Http\Controllers\PDF\PdfAk06Controller;
use App\Http\Controllers\PDF\PdfAk07Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDF\PdfApl01Controller;
use App\Http\Controllers\PDF\PdfApl02Controller;
use App\Http\Controllers\PDF\PdfMapa01Controller;
use App\Http\Controllers\PDF\PdfMapa02Controller;

Route::redirect('/', '/admin');
Route::permanentRedirect('/', '/admin');

Route::get('/test-activation', function () {
    return view('auth.activation');
});

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/signature/verify', function (\Illuminate\Http\Request $request) {
    $id = $request->query('id');
    $type = $request->query('type');
    $sigData = null;

    if ($id) {
        if ($type === 'apl01') {
            $reg = \App\Models\AssessmentRegistration::with(['aplData', 'candidate'])->find($id);
            if ($reg && $reg->aplData && $reg->aplData->asesi_qr_code) {
                $sigData = [
                    'n' => $reg->candidate->name,
                    'r' => 'Asesi / Kandidat',
                    'd' => 'FR.APL.01 - Permohonan Sertifikasi',
                    't' => $reg->aplData->asesi_signature_date?->format('Y-m-d H:i:s') ?? '-',
                    's' => $reg->aplData->asesi_signature ? route('signature.stream', ['id' => $reg->id, 'type' => 'apl01']) : null,
                    'v' => true,
                ];
            }
        } elseif ($type === 'ak04') {
            $reg = \App\Models\AssessmentRegistration::with(['candidate', 'appeals'])->find($id);
            $appeal = $reg?->appeals->first();
            if ($reg && $appeal && isset($appeal->ak04_data['signature']['qr_code'])) {
                $sigData = [
                    'n' => $reg->candidate->name,
                    'r' => 'Asesi / Kandidat',
                    'd' => 'FR.AK.04 - Banding Asesmen',
                    't' => $appeal->ak04_data['signature']['date'] ?? $appeal->created_at->format('Y-m-d H:i:s'),
                    's' => isset($appeal->ak04_data['signature']['asesi_signature']) ? route('signature.stream', ['id' => $reg->id, 'type' => 'ak04']) : null,
                    'v' => true,
                ];
            }
        } elseif ($type === 'ak01') {
            $reg = \App\Models\AssessmentRegistration::with(['candidate', 'plannings'])->find($id);
            $planning = $reg?->plannings->first();
            if ($reg && $planning && isset($planning->ak01_data['signatures']['asesi']['qr_code'])) {
                $sigData = [
                    'n' => $reg->candidate->name,
                    'r' => 'Asesi / Kandidat',
                    'd' => 'FR.AK.01 - Persetujuan Asesmen & Kerahasiaan',
                    't' => $planning->ak01_data['signatures']['asesi']['date'] ?? $planning->updated_at->format('Y-m-d H:i:s'),
                    's' => isset($planning->ak01_data['signatures']['asesi']['signature']) ? route('signature.stream', ['id' => $reg->id, 'type' => 'ak01']) : null,
                    'v' => true,
                ];
            }
        } else {
            // Coba cari di SignatureRequest dulu (untuk dokumen spesifik)
            $req = \App\Models\SignatureRequest::find($id);
            if ($req && $req->status === 'signed') {
                $sigData = [
                    'n' => $req->user->name,
                    'r' => $req->role,
                    'd' => $req->document_label,
                    't' => $req->signed_at->format('Y-m-d H:i:s'),
                    's' => $req->raw_signature,
                    'v' => true,
                ];
            } else {
                // Fallback ke UserSignature (library lama)
                $sig = \App\Models\UserSignature::find($id);
                if ($sig) {
                    $sigData = [
                        'n' => $sig->name,
                        'r' => 'Pemilik Tanda Tangan',
                        'd' => 'Library Tanda Tangan',
                        't' => $sig->updated_at->format('Y-m-d H:i:s'),
                        's' => null,
                        'v' => true,
                    ];
                }
            }
        }
    }

    return view('signature.verify', ['sigData' => $sigData]);
})->name('signature.verify');

Route::get('admin/view-document/{record}', [PrivateFileController::class, 'download'])
    ->name('private.file.view')
    ->middleware(['auth']);

// ============================================================
// PDF Documents (Supports Signed URLs for Mobile)
// ============================================================
Route::middleware(['web', 'signed'])->group(function () {
    Route::get('apl01/{registration}', [PdfApl01Controller::class, 'show'])->name('apl01.form');
    Route::get('apl02/{registration}', [PdfApl02Controller::class, 'show'])->name('apl02.form');
    Route::get('ak01/{registration}', [PdfAk01Controller::class, 'show'])->name('ak01.form');
    Route::get('ak02/{registration}', [PdfAk02Controller::class, 'show'])->name('ak02.form');
    Route::get('ak03/{registration}', [PdfAk03Controller::class, 'show'])->name('ak03.form');
    Route::get('ak04/{registration}', [PdfAk04Controller::class, 'show'])->name('ak04.form');
    Route::get('ak05/{registration}', [PdfAk05Controller::class, 'show'])->name('ak05.form');
    Route::get('ak06/{registration}', [PdfAk06Controller::class, 'show'])->name('ak06.form');
    Route::get('ak07/{registration}', [PdfAk07Controller::class, 'show'])->name('ak07.form');
    Route::get('ia01/{registration}', [\App\Http\Controllers\PDF\PdfIA01Controller::class, 'show'])->name('ia01.form');
    Route::get('ia02/{registration}', [\App\Http\Controllers\PDF\PdfIa02Controller::class, 'show'])->name('ia02.form');
    Route::get('ia03/{registration}', [\App\Http\Controllers\PDF\PdfIa03Controller::class, 'show'])->name('ia03.form');
    Route::get('ia04a/{registration}', [\App\Http\Controllers\PDF\PdfIa04aController::class, 'show'])->name('ia04a.form');
    Route::get('ia04b/{registration}', [\App\Http\Controllers\PDF\PdfIa04bController::class, 'show'])->name('ia04b.form');
    Route::get('ia05a/{registration}', [\App\Http\Controllers\PDF\PdfIa05aController::class, 'show'])->name('ia05a.form');
    Route::get('ia05b/{registration}', [\App\Http\Controllers\PDF\PdfIa05bController::class, 'show'])->name('ia05b.form');
    Route::get('ia05c/{registration}', [\App\Http\Controllers\PDF\PdfIa05cController::class, 'show'])->name('ia05c.form');
    Route::get('ia06a/{registration}', [\App\Http\Controllers\PDF\PdfIa06aController::class, 'show'])->name('ia06a.form');
    Route::get('ia06b/{registration}', [\App\Http\Controllers\PDF\PdfIa06bController::class, 'show'])->name('ia06b.form');
    Route::get('ia06c/{registration}', [\App\Http\Controllers\PDF\PdfIa06cController::class, 'show'])->name('ia06c.form');
    Route::get('ia07/{registration}', [\App\Http\Controllers\PDF\PdfIa07Controller::class, 'show'])->name('ia07.form');
    Route::get('ia08/{registration}', [\App\Http\Controllers\PDF\PdfIa08Controller::class, 'show'])->name('ia08.form');
    Route::get('ia09/{registration}', [\App\Http\Controllers\PDF\PdfIa09Controller::class, 'show'])->name('ia09.form');
    Route::get('ia10/{registration}', [\App\Http\Controllers\PDF\PdfIa10Controller::class, 'show'])->name('ia10.form');
    Route::get('ia11/{registration}', [\App\Http\Controllers\PDF\PdfIa11Controller::class, 'show'])->name('ia11.form');
    Route::get('va/{registration}', [\App\Http\Controllers\PDF\PdfVaController::class, 'show'])->name('frva.print');
    Route::get('mapa01/{registration}', [\App\Http\Controllers\PDF\PdfMapa01Controller::class, 'show'])->name('mapa01.form');
    Route::get('mapa02/{registration}', [\App\Http\Controllers\PDF\PdfMapa02Controller::class, 'show'])->name('mapa02.form');
});

Route::get('cek-ttd/{id}', [\App\Http\Controllers\SignatureVerificationController::class, 'show'])->name('ttd.cek');
Route::get('signature-img/{id}/{type}', [\App\Http\Controllers\SignatureVerificationController::class, 'stream'])->name('signature.stream');