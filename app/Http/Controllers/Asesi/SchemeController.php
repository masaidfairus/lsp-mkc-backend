<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Scheme;

class SchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schemes = Scheme::with(['competencyUnits.competencyElements.performanceCriteria'])->get();
        return $this->successResponse($schemes, "Data skema berhasil ditampilkan");
    }

    /**
     * Display the specified resource.
     */ 
    public function show(Scheme $scheme)
    {
        $scheme->load(['competencyUnits.competencyElements.performanceCriteria']);
        return $this->successResponse($scheme, "Data skema berhasil ditampilkan");
    }
}
