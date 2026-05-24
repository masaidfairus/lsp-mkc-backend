<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FileProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    /**
     * Mengunduh.
     *
     * @param  FileProfile  $record
     * @return mixed
     */
    public function download(FileProfile $record)
    {
        if(!auth()->user()->can('view_document')) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($record->upload_document)) {
            abort(404);
        }

        return Storage::disk('local')->response($record->upload_document);

        // Buat Donwload File Dokumen
        // return Storage::disk('local')->download($record->upload_document, $record->original_filename);
    }
}
