<?php
// app/Http/Controllers/Api/Apl01Controller.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;

class Apl01Controller extends Controller
{
    // GET /api/apl01
    public function index()
    {
        return response()->json(
            AssessmentRegistration::with([
                'user',
                'scheme',
                'apl01Data',
            ])->latest()->paginate(20)
        );
    }

    // GET /api/apl01/{id}
    public function show($id)
    {
        return response()->json(
            AssessmentRegistration::with([
                'user',
                'scheme.requirements',
                'apl01Data',
                'evidence.requirement',
                'assessor',
            ])->findOrFail($id)
        );
    }
}