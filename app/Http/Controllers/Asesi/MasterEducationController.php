<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\MasterEducation;
use Illuminate\Http\Request;

class MasterEducationController extends Controller
{
    /**
     * Menampilkan daftar semua resource.
     */
    public function index()
    {
        return $this->successResponse(
            MasterEducation::all(),
            'Data pendidikan master berhasil diambil'
        );
    }

    /**
     * Menampilkan resource yang ditentukan.
     */
    public function show(MasterEducation $masterEducation)
    {
        return $this->successResponse(
            $masterEducation,
            'Data pendidikan master berhasil diambil'
        );
    }
}
