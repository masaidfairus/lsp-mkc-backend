<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Enums\Apl01Status;
use App\Enums\CertificationPurpose;
use App\Enums\Gender;
use App\Enums\PaymentStatus;
use App\Models\AssessmentRegistration;
use App\Services\InvoiceService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class Apl01Controller extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, $step)
    {
        $user = $request->user();

        if ($step == 'data-pemohon-sertifikasi') {
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
            $registration = $user->registrations()
                ->whereNull('scheme_id')
                ->firstOrCreate([]);
            $registration->aplData()->create($validated);

            return $this->successResponse([
                'next_step' => 'data-sertifikasi',
                'registration_id' => $registration->id
            ], 'Bagian 1 tersimpan. Lanjutkan ke bagian berikutnya.');
        }

        if ($step == 'data-sertifikasi') {
            $validated = $request->validate([
                'registration_id' => ['required', 'exists:assessment_registrations,id'],
                'certification_purpose' => ['required', new Enum(CertificationPurpose::class)],
                'scheme_id' => ['required', 'exists:schemes,id'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);

            if (!$registration->aplData()->exists()) {
                return $this->errorResponse('E2006', 403);
            }

            $profile = $user->profile;
            $registration->update([
                'certification_purpose' => $validated['certification_purpose'],
                'scheme_id' => $validated['scheme_id'],
                'asesi_type' => $profile->asesi_type,
                'institution_name' => $profile->institution_name,
            ]);

            return $this->successResponse([
                'next_step' => 'bukti-kelengkapan-pemohon',
                'registration_id' => $registration->id,
                'asesi_type' => $profile->asesi_type,
            ], 'Bagian 2 tersimpan. Lanjutkan ke bagian berikutnya.');
        }

        if ($step == 'bukti-persyaratan-dasar') {
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

            DB::transaction(function () use ($validated, $registration, $user) {
                foreach ($validated['evidence'] as $item) {
                    $file = $item['file'];
                    $filename = "user_{$user->id}_req_{$item['requirement_id']}_" . uniqid() . "." . $file->getClientOriginalExtension();
                    $path = $file->storeAs("admin_evidence/{$registration->id}", $filename, 'public');

                    $registration->evidence()->create([
                        'requirement_id' => $item['requirement_id'],
                        'file_path' => $path,
                        'status' => 'pending',
                        'is_valid_admin' => false,
                    ]);
                }
            });

            return $this->successResponse([
                'next_step' => 'bukti-administratif',
                'registration_id' => $registration->id,
            ], 'Bukti persyaratan dasar berhasil diupload.');
        }

        if ($step == 'bukti-administratif') {
            $validated = $request->validate([
                'registration_id' => ['required', 'exists:assessment_registrations,id'],
                'evidence' => ['required', 'array', 'min:1'],
                'evidence.*.evidence_type_id' => ['required', 'exists:administrative_evidence_types,id'],
                'evidence.*.file' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);

            DB::transaction(function () use ($validated, $registration, $user) {
                foreach ($validated['evidence'] as $item) {
                    $file = $item['file'];
                    $filename = "user_{$user->id}_req_{$item['evidence_type_id']}_" . uniqid() . "." . $file->getClientOriginalExtension();
                    $path = $file->storeAs("administration-evidence/{$registration->id}", $filename, 'public');

                    $registration->adminEvidence()->create([
                        'evidence_type_id' => $item['evidence_type_id'],
                        'file_path' => $path,
                        'status' => 'pending',
                        'is_valid_admin' => false,
                    ]);
                }
            });

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

            return $this->successResponse([
                'has_payment' => $registration->candidate->profile->asesi_type === 'pribadi',
                'registration_id' => $registration->id,
            ], 'Pendaftaran APL-01 berhasil dikirim.');
        }

        abort(404);
    }

    public function show(AssessmentRegistration $registration)
    {
        $this->authorize('view', $registration);

        return $this->successResponse([
            'registration_id' => $registration->id,
            'asesi_type' => $registration->asesi_type,
            'institution_name' => $registration->institution_name,
            'status_apl01' => $registration->status_apl01,
            'certification_purpose' => $registration->certification_purpose,
            'scheme' => $registration->scheme?->only('id', 'code', 'name'),
            'apl_data' => $registration->aplData,
            'evidence' => $registration->evidence->map(fn($e) => [
                'id' => $e->id,
                'requirement_id' => $e->requirement_id,
                'file_url' => asset('storage/' . $e->file_path),
                'status' => $e->status,
            ]),
            'administrative_evidence' => $registration->administrativeEvidences->map(fn($e) => [
                'id' => $e->id,
                'evidence_type' => $e->evidenceType->name,
                'file_url' => asset('storage/' . $e->file_path),
                'status' => $e->status,
            ]),
            'payment' => $registration->asesi_type === 'pribadi'
                ? $registration->payments()->first()?->only('invoice_number', 'amount', 'status')
                : null
        ], 'Data APL01 berhasil ditampilkan');
    }
}
