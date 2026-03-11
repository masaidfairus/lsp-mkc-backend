<?php

namespace App\Http\Controllers;

use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
use App\Enums\CertificationPurpose;
use App\Enums\Gender;
use App\Enums\PaymentStatus;
use App\Models\AssessmentRegistration;
use App\Models\SelfAssessment;
use App\Services\InvoiceService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class AssessmentRegistrationController extends Controller
{
    use AuthorizesRequests;

    public function storeApl01(Request $request, $step)
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

            $registration = $user->registrations()->whereNull('scheme_id')->whereNull('status_apl01')->firstOrCreate([]);

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

            $registration->update([
                'certification_purpose' => $validated['certification_purpose'],
                'scheme_id' => $validated['scheme_id'],
            ]);

            return $this->successResponse([
                'next_step' => 'bukti-kelengkapan-pemohon',
                'registration_id' => $registration->id
            ], 'Bagian 2 tersimpan. Lanjutkan ke bagian berikutnya.');
        }

        if ($step == 'bukti-kelengkapan-pemohon') {
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

            DB::transaction(function () use ($validated, $registration, $user, &$uploadedRecords) {
                foreach ($validated['evidence'] as $item) {
                    $file = $item['file'];
                    $filename = "user_{$user->id}_req_{$item['requirement_id']}_" . uniqid() . "." . $file->getClientOriginalExtension();
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
            });

            return $this->successResponse(
                ['files_uploaded' => count($uploadedRecords)],
                'Pendaftaran APL-01 berhasil dikirim.'
            );
        }

        abort(404);
    }

    public function showApl01(AssessmentRegistration $registration)
    {
        $this->authorize('view', $registration);

        return $this->successResponse([
            'registration_id' => $registration->id,
            'status_apl01' => $registration->status_apl01,
            'certification_purpose' => $registration->certification_purpose,
            'scheme' => $registration->scheme?->only('id', 'code', 'name'),
            'apl_data' => $registration->aplData,
            'evidence' => $registration->evidence->map(fn($e) => [
                'id' => $e->id,
                'requirement_id' => $e->requirement_id,
                'file_url' => asset('storage/' . $e->file_path),
                'status' => $e->status,
                'is_valid_admin' => $e->is_valid_admin,
            ]),
            'payment' => $registration->payments()->first()?->only(
                'invoice_number',
                'amount',
                'status'
            ),
        ], 'Data APL01 berhasil ditampilkan');
    }

    public function form(AssessmentRegistration $registration)
    {
        $this->authorize('view', $registration);

        $payment = $registration->payments()->first();
        if (!$payment || $payment->status !== PaymentStatus::PAID) {
            return $this->errorResponse('E2010', 403);
        }

        if ($registration->status_apl01 !== Apl01Status::APPROVED) {
            return $this->errorResponse('E2009', 403);
        }

        $scheme = $registration->scheme->load([
            'competencyUnits.competencyElements.performanceCriteria'
        ]);

        return $this->successResponse([
            'registration_id' => $registration->id,
            'scheme' => [
                'id' => $scheme->id,
                'code' => $scheme->code,
                'name' => $scheme->name,
                'units' => $scheme->competencyUnits->map(fn($unit) => [
                    'id' => $unit->id,
                    'unit_code' => $unit->unit_code,
                    'unit_title' => $unit->unit_title,
                    'elements' => $unit->competencyElements->map(fn($element) => [
                        'id' => $element->id,
                        'title' => $element->title,
                        'criteria' => $element->performanceCriteria->map(fn($criterion) => [
                            'id' => $criterion->id,
                            'name' => $criterion->name,
                            'description' => $criterion->description,
                        ]),
                    ]),
                ]),
            ]
        ]);
    }

    public function uploadBuktiRelevan(Request $request, AssessmentRegistration $registration)
    {
        $this->authorize('view', $registration);

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        $file = $request->file('file');
        $filename = "user_{$user->id}_" . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("apl02/{$registration->id}", $filename, 'public');
        $url = Storage::url($path);

        return $this->successResponse([
            'file_path' => $path,
            'file_url' => $url,
        ], 'File berhasil diupload.');
    }

    public function submitAnswers(Request $request, AssessmentRegistration $registration)
    {
        $this->authorize('view', $registration);

        $validated = $request->validate([
            'is_declared' => ['required', 'accepted'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.element_id' => ['required', 'exists:competency_elements,id'],
            'answers.*.is_competent' => ['required', 'boolean'],
            
            'answers.*.evidence_id' => ['nullable', 'exists:apl01_evidence,id'],
            'answers.*.file_path' => ['nullable', 'string'],
            'answers.*.file_url' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $registration) {
            $rows = array_map(fn($answer) => [
                'registration_id' => $registration->id,
                'element_id' => $answer['element_id'],
                'is_competent' => $answer['is_competent'],
                'evidence_id' => $answer['evidence_id'] ?? null,
                'file_path' => $answer['file_path'] ?? null,
                'file_url' => $answer['file_url'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ], $validated['answers']);

            SelfAssessment::upsert(
                $rows,
                ['registration_id', 'element_id'],
                ['is_competent', 'updated_at'],
            );

            $registration->update([
                'status_apl02' => Apl02Status::SUBMITTED,
            ]);
        });

        return $this->successResponse(
            ['total_answers' => count($validated['answers'])],
            'APL.02 berhasil disubmit.'
        );
    }
}
