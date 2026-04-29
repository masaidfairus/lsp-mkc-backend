<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeProof;
use App\Models\Apl02Portfolio;
use App\Models\AssessmentRegistration;
use App\Models\BasicRequirementProof;
use App\Models\DocumentList;
use App\Models\FileProfile;
use App\Models\PortfolioScheme;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $files = FileProfile::where('user_id', $user->id)
            ->with('documentList')
            ->latest()
            ->get()
            ->map(fn($f) => [
                'id' => $f->id,
                'list_id' => $f->list_id,
                'document_title' => optional($f->documentList)->title,
                'description' => $f->description,
                'upload_document' => $f->upload_document,
                'file_url' => $f->upload_document
                    ? route('documents.stream', $f->id)
                    : null,
            ]);

        return $this->successResponse($files, 'Data dokumen berhasil ditampilkan.');
    }

    public function stream(Request $request, FileProfile $document)
    {
        $user = $request->user();

        // Asesi hanya bisa akses milik sendiri
        // Admin bisa akses semua

        if ($user->role !== 'admin') {
            $document->where('user_id', $user->id);
        }

        $file = $document->firstOrFail();

        $content = Storage::disk('local')->get($file->upload_document);
        $mimeType = mime_content_type(Storage::disk('local')->path($file->upload_document));

        return response($content)->header('Content-Type', $mimeType);
    }

    public function showDocumentLists()
    {
        $result = DocumentList::all();
        return $this->successResponse($result, 'Data portfolio berhasil ditampilkan');
    }

    public function showBasicRequirementBySchemeId(Scheme $scheme)
    {
        $basic = DocumentList::forScheme($scheme->id, 'basicRequirementProof')->get();

        return $this->successResponse($basic, "Data persyaratan untuk skema {$scheme->name} berhasil ditampilkan");
    }

    public function showPortfolioBySchemeId(Scheme $scheme)
    {
        $portfolio = DocumentList::forScheme($scheme->id, 'portfolioScheme')->get(); //

        return $this->successResponse($portfolio, "Data persyaratan untuk skema {$scheme->name} berhasil ditampilkan");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'documents' => ['required', 'array', 'min:1'],
            'documents.*.list_id' => ['required', 'exists:document_lists,id'],
            'documents.*.file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        $saved = [];

        foreach ($validated['documents'] as $doc) {
            $file = $doc['file'];
            $oldFile = FileProfile::where('user_id', $user->id)->where('list_id', $doc['list_id'])->first();
            if ($oldFile) {
                Storage::disk('local')->delete($oldFile->upload_document);
            }

            $filename = "doc_{$user->id}_{$doc['list_id']}_" . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("document-profile/{$user->id}", $filename, 'local');

            $saved[] = FileProfile::updateOrCreate(
                ['user_id' => $user->id, 'list_id' => $doc['list_id']],
                ['upload_document' => $path]
            );
        }

        $result = collect($saved)->map(fn($f) => [
            'id' => $f->id,
            'list_id' => $f->list_id,
            'file_url' => $f->upload_document
                ? route('documents.stream', $f->id)
                : null,
        ]);

        return $this->successResponse($result, 'Portfolio berhasil diupload');
    }

    public function update(Request $request, FileProfile $document)
    {
        $validated = $request->validate([
            'file' => ['sometimes', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('file')) {
            if ($document->upload_document && Storage::disk('local')->exists($document->upload_document)) {
                Storage::disk('local')->delete($document->upload_document);
            }

            $user = $request->user();
            $file = $request->file('file');

            $filename = "doc_{$user->id}_{$document->id}_" . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("document-profile/{$user->id}", $filename, 'local');

            $validated['upload_document'] = $path;
            unset($validated['file']);
        }

        $document->update($validated);

        return $this->successResponse(
            $document->fresh()->load('documentList'),
            'Portfolio berhasil diupdate'
        );
    }

    public function destroy(Request $request, FileProfile $document)
    {
        if ($document->user_id !== $request->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        if ($document->upload_document && Storage::disk('local')->exists($document->upload_document)) {
            Storage::disk('local')->delete($document->upload_document);
        }

        $document->delete();
        return $this->successResponse([], 'Pendaftaran berhasil dibatalkan/dihapus');
    }
}

