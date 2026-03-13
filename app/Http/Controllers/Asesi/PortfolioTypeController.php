<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\PortfolioType;
use Illuminate\Http\Request;

class PortfolioTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse(
            PortfolioType::latest()->get(),
            'Data tipe portfolio berhasil ditampilkan'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(PortfolioType $portfolioType)
    {
        return $this->successResponse($portfolioType, 'Detail tipe portfolio');
    }
}
