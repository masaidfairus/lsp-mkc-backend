<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeProof;
use App\Models\BasicRequirementProof;
use App\Models\DocumentList;
use App\Models\FileProfile;
use App\Models\PortfolioScheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentProfileController extends Controller
{
    public function index(Request $request)
    {
        $portfolios = FileProfile::where('user_id', $request->user()->id)
            ->with('documentList')
            ->latest()
            ->get();

        return $this->successResponse($portfolios, 'Data file-file dokument berhasil ditampilkan');
    }

    public function showDocumentLists()
    {
        $result = DocumentList::all();
        return $this->successResponse($result, 'Data portfolio berhasil ditampilkan');
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
            $existing = FileProfile::where('user_id', $user->id)
                ->where('list_id', $doc['list_id'])
                ->first();

            if ($existing) {
                Storage::disk('public')->delete($existing->upload_document);
                $existing->delete();
            }

            $file = $doc['file'];
            $filename = "doc_{$user->id}_{$doc['list_id']}_" . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("document-profile/{$user->id}", $filename, 'public');


            $saved[] = FileProfile::create([
                'user_id' => $user->id,
                'list_id' => $doc['list_id'],
                'upload_document' => $path,
            ]);
        }
        return $this->successResponse($saved, 'Portfolio berhasil diupload');
    }

    public function update(Request $request, $id)
    {
        $portfolioListIds = PortfolioScheme::pluck('list_id');

        $portfolio = FileProfile::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->whereIn('list_id', $portfolioListIds)
            ->firstOrFail();

        $validated = $request->validate([
            'list_id' => ['sometimes', 'exists:document_lists,id'],
            'description' => ['sometimes', 'string', 'max:500'],
            'file' => ['sometimes', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($portfolio->upload_document);

            $user = $request->user();
            $file = $request->file('file');
            $filename = "portfolio_{$user->id}_" . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("portfolios/{$user->id}", $filename, 'public');

            $validated['upload_document'] = $path;
            unset($validated['file']);
        }

        $portfolio->update($validated);

        return $this->successResponse(
            $portfolio->fresh()->load('documentList'),
            'Portfolio berhasil diupdate'
        );
    }
}

