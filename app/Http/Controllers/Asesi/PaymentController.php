<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Payment $payment, AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $invoices = Payment::where('registration_id', $registration->id)
            ->latest()
            ->get();

        return $this->successResponse($invoices, 'Data invoice anda berhasil ditampilkan');
    }
}
