<?php

namespace App\Http\Controllers;

use App\Models\Scheme;

class SchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schemes = Scheme::with(['competencyUnits.competencyElements.performanceCriteria', 'requirements'])->get();
        return $schemes;
    }

    /**
     * Display the specified resource.
     */ 
    public function show(Scheme $scheme)
    {
        $schemeData = $scheme::with(['competencyUnits.competencyElements.performanceCriteria', 'requirements'])->get();
        return $schemeData;
    }
}
