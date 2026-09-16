<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\AssessmentRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $invoices = $registration->payments()
            ->latest()
            ->get();

        return $this->successResponse($invoices, 'Data invoice anda berhasil ditampilkan');
    }

    /**
     * Simulate a dummy payment for development.
     */
    public function simulate(AssessmentRegistration $registration)
    {
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        try {
            DB::beginTransaction();

            // 1. Create or Update Payment to PAID
            $payment = $registration->payments()->firstOrCreate(
                ['registration_id' => $registration->id],
                [
                    'amount' => $registration->scheme->price ?? 0,
                    'payment_method' => 'Dummy Payment (Dev)',
                    'status' => \App\Enums\PaymentStatus::PAID,
                    'paid_at' => now(),
                    'invoice_number' => 'INV-' . strtoupper(uniqid()),
                ]
            );

            if ($payment->status !== \App\Enums\PaymentStatus::PAID) {
                $payment->update([
                    'status' => \App\Enums\PaymentStatus::PAID,
                    'paid_at' => now(),
                ]);
            }

            // 2. Advance registration status to APPROVED so APL.02 is accessible
            $registration->update([
                'status_apl01' => \App\Enums\Apl01Status::APPROVED
            ]);

            DB::commit();

            return $this->successResponse($payment, 'Pembayaran berhasil disimulasi (DUMMY)');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Gagal simulasi pembayaran: ' . $e->getMessage(), 500);
        }
    }
}
