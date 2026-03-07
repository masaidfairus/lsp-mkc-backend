<?php

namespace App\Http\Controllers;

use App\Enums\Apl01Status;
use App\Enums\CertificationPurpose;
use App\Enums\Gender;
use App\Enums\PaymentStatus;
use App\Models\Apl01Evidence;
use App\Models\AssessmentRegistration;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rules\Enum;

class AssessmentRegistrationController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
        // Except itu buat yang ga perlu auth:sanctum
    }

    public function store(Request $request, $step)
    {
        $user = $request->user();

        if ($step == 1) {
            $validated = $request->validate([
                'gender' => ['required', new Enum(Gender::class)],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'address' => ['required', 'string', 'max:1000'],
                'phone_number' => ['required', 'string', 'regex:/^(\+62|0)8[1-9][0-9]{6,10}$/'],
                'education_qualifications' => ['required', 'string', 'max:255'],
                'company_name' => ['required', 'string', 'max:255'],
                'job_title' => ['required', 'string', 'max:255'],
                'company_address' => ['required', 'string', 'max:1000'],
                'company_contact' => ['required', 'string', 'max:20'],
            ]);

            $user->profile()->updateOrCreate([], $validated);

            $registration = $user->registrations()->create();

            $registration->aplData()->create($validated);

            return $this->successResponse([
                'next_step' => 2,
                'registration_id' => $registration->id
            ], 'Bagian 1 tersimpan. Lanjutkan ke bagian berikutnya.');
        }

        if ($step == 2) {
            $validated = $request->validate([
                'registration_id' => ['required', 'exists:assessment_registrations,id'],
                'certification_purpose' => ['required', new Enum(CertificationPurpose::class)],
                'scheme_id' => ['required', 'exists:schemes,id'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);

            if (!$registration->aplData()->exists()) {
                return $this->errorResponse('E2006', 403);
            }

            $registration->update([
                'certification_purpose' => $validated['certification_purpose'],
                'scheme_id' => $validated['scheme_id'],
            ]);

            return $this->successResponse([
                'next_step' => 3,
                'registration_id' => $registration->id
            ], 'Bagian 2 tersimpan. Lanjutkan ke bagian berikutnya.');
        }

        if ($step == 3) {
            $validated = $request->validate([
                'registration_id' => ['required', 'exists:assessment_registrations,id'],
                'evidence' => ['required', 'array', 'min:1'],
                'evidence.*.requirement_id' => ['required', 'exists:scheme_requirements,id'],
                'evidence.*.file' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);

            if (!$registration->scheme_id) {
                return $this->errorResponse('E2008', 403);
            }

            $uploadedRecords = [];

            foreach ($validated['evidence'] as $item) {
                $file = $item['file'];
                $filename = "user_{$user->id}_req_{$item['requirement_id']}_" . time() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs("evidence/{$registration->id}", $filename, 'public');

                $uploadedRecords[] = $registration->evidence()->create([
                    'requirement_id' => $item['requirement_id'],
                    'file_path' => $path,
                    'status' => 'pending',
                    'is_valid_admin' => false,
                ]);
            }

            $registration->update([
                'status_apl01' => Apl01Status::PENDING,
            ]);

            $registration->payments()->firstOrCreate(
                ['registration_id' => $registration->id],
                [
                    'invoice_number' => InvoiceService::generateNumber('LSP-INV'),
                    'amount' => $registration->scheme->certification_fee,
                    'status' => PaymentStatus::UNPAID,
                ]
            );

            return $this->successResponse(
                ['files_uploaded' => count($uploadedRecords)],
                'Pendaftaran APL-01 berhasil dikirim.'
            );
        }

        abort(404);
    }
}
