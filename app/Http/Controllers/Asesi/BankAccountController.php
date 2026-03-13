<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse(
            BankAccount::where('is_active', true)->get(),
            'Data rekening bank LSP berhasil ditampilkan'
        );
    }
}
