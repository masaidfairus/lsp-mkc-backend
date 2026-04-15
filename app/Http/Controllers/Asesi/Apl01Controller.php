<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Enums\Apl01Status;
use App\Enums\CertificationPurpose;
use App\Enums\FileContext;
use App\Enums\Gender;
use App\Enums\PaymentStatus;
use App\Models\AdministrativeProof;
use App\Models\AssessmentRegistration;
use App\Models\BasicRequirementProof;
use App\Models\FileProfile;
use App\Services\InvoiceService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                'place_of_birth' => ['required', 'string', 'max:1000'],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'address' => ['required', 'string', 'max:1000'],
                'home_postal_code' => ['required', 'numeric', 'digits:5', 'between:10000,99999'],
                'phone_number' => ['required', 'string', 'regex:/^(\+62|0)8[1-9][0-9]{6,10}$/'],
                'education_qualifications' => ['required', 'string', 'max:255'],
                'company_name' => ['required', 'string', 'max:255'],
                'job_title' => ['required', 'string', 'max:255'],
                'company_address' => ['required', 'string', 'max:1000'],
                'company_postal_code' => ['required', 'numeric', 'digits:5', 'between:10000,99999'],
                'company_contact' => ['required', 'string', 'max:20'],
            ]);

            $asesiType = $request->validate([
                'asesi_type' => ['required', 'in:pribadi,institusi'],
                'institution_name' => ['required_if:asesi_type,institusi', 'nullable', 'string', 'max:255'],
            ]);

            $user->profile()->updateOrCreate([], array_merge($validated, $asesiType));

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

            if ($registration->status_apl01 === Apl01Status::PENDING || $registration->status_apl01 === Apl01Status::PAID || $registration->status_apl01 === Apl01Status::APPROVED) {
                return $this->errorResponse('E2017', 403);
            }

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

            $buktiDasar = BasicRequirementProof::where('scheme_id', $validated['scheme_id'])
                ->with('documentList')
                ->get()
                ->map(function ($req) use ($user) {
                    $uploaded = FileProfile::where('user_id', $user->id)
                        ->where('list_id', $req->list_id)
                        ->first();

                    return [
                        'list_id' => $req->list_id,
                        'title' => $req->documentList->title,
                        'is_mandatory' => $req->is_mandatory,
                        'or_group' => $req->or_group,
                        'is_uploaded' => $uploaded ? true : false,
                        'file_url' => $uploaded ? asset('storage/' . $uploaded->upload_document) : null,
                        'file_profile_id' => $uploaded?->id,
                    ];
                });

            $buktiAdmin = AdministrativeProof::with('documentList')
                ->get()
                ->map(function ($adm) use ($user) {
                    $uploaded = FileProfile::where('user_id', $user->id)
                        ->where('list_id', $adm->list_id)
                        ->first();

                    return [
                        'list_id' => $adm->list_id,
                        'title' => $adm->documentList->title,
                        'is_mandatory' => true,
                        'is_uploaded' => $uploaded ? true : false,
                        'file_url' => $uploaded ? asset('storage/' . $uploaded->upload_document) : null,
                        'file_profile_id' => $uploaded?->id,
                    ];
                });

            return $this->successResponse([
                'next_step' => 'bukti-persyaratan-dasar',
                'registration_id' => $registration->id,
                'asesi_type' => $profile->asesi_type,
                'bukti_persyaratan_dasar' => $buktiDasar,
                'bukti_administratif' => $buktiAdmin,
            ], 'Bagian 2 tersimpan. Lanjutkan ke bagian berikutnya.');
        }

        if ($step == 'bukti-persyaratan-dasar') {
            $validated = $request->validate([
                'registration_id' => ['required', 'exists:assessment_registrations,id'],
                'evidence' => ['required', 'array', 'min:1'],
                'evidence.*.list_id' => ['required', 'exists:document_lists,id'],
                'evidence.*.file' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);

            if (!$registration->scheme_id) {
                return $this->errorResponse('E2008', 403);
            }

            if ($registration->status_apl01 === Apl01Status::PENDING || $registration->status_apl01 === Apl01Status::PAID || $registration->status_apl01 === Apl01Status::APPROVED) {
                return $this->errorResponse('E2017', 403);
            }

            $validListIds = BasicRequirementProof::where('scheme_id', $registration->scheme_id)
                ->pluck('list_id')
                ->toArray();

            foreach ($validated['evidence'] as $item) {
                if (!in_array($item['list_id'], $validListIds)) {
                    return $this->errorResponse('E2008', 422);
                }
            }

            foreach ($validated['evidence'] as $item) {
                $alreadyUploaded = FileProfile::where('user_id', $user->id)
                    ->where('list_id', $item['list_id'])
                    ->exists();

                if (!$alreadyUploaded && !isset($item['file'])) {
                    return $this->errorResponse('E2013', 422);
                }
            }

            $orGroups = BasicRequirementProof::where('scheme_id', $registration->scheme_id)
                ->whereNotNull('or_group')
                ->get()
                ->groupBy('or_group');

            $submittedIds = array_column($validated['evidence'], 'list_id');

            foreach ($orGroups as $groupName => $groupItems) {
                $groupListIds = collect($groupItems)->pluck('list_id')->toArray();
                $alreadyHasOne = FileProfile::where('user_id', $user->id)
                    ->whereIn('list_id', $groupListIds)
                    ->exists();
                $submittingOne = !empty(array_intersect($submittedIds, $groupListIds));

                if (!$alreadyHasOne && !$submittingOne) {
                    return $this->errorResponse('E2014', 422);
                }
            }

            DB::transaction(function () use ($validated, $registration, $user) {
                foreach ($validated['evidence'] as $item) {
                    $file = $item['file'];
                    $filename = "user_{$user->id}_req_{$item['list_id']}_" . uniqid() . "." . $file->getClientOriginalExtension();
                    $path = $file->storeAs("bukti-dasar/{$registration->id}", $filename, 'public');

                    $existing = FileProfile::where([
                        'list_id' => $item['list_id'],
                    ])->first();

                    if ($existing) {
                        Storage::disk('public')->delete($existing->upload_document);
                        $existing->delete();
                    }

                    FileProfile::create([
                        'user_id' => $user->id,
                        'list_id' => $item['list_id'],
                        'upload_document' => $path,
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
                'evidence.*.list_id' => ['required', 'exists:administrative_proofs,list_id'],
                'evidence.*.file' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);

            if ($registration->status_apl01 === Apl01Status::PENDING || $registration->status_apl01 === Apl01Status::PAID || $registration->status_apl01 === Apl01Status::APPROVED) {
                return $this->errorResponse('E2017', 403);
            }

            $requiredListIds = AdministrativeProof::pluck('list_id')->toArray();

            foreach ($requiredListIds as $listId) {
                $alreadyUploaded = FileProfile::where('user_id', $user->id)
                    ->where('list_id', $listId)
                    ->exists();

                $submittedIds = array_column($validated['evidence'], 'list_id');
                $isSubmitting = in_array($listId, $submittedIds);

                if (!$alreadyUploaded && !$isSubmitting) {
                    return $this->errorResponse('E2015', 422);
                }
            }

            DB::transaction(function () use ($validated, $registration, $user) {
                foreach ($validated['evidence'] as $item) {
                    $file = $item['file'];
                    $filename = "user_{$user->id}_list_{$item['list_id']}_" . uniqid() . "." . $file->getClientOriginalExtension();
                    $path = $file->storeAs("bukti-admin/{$registration->id}", $filename, 'public');

                    $existing = FileProfile::where([
                        'list_id' => $item['list_id'],
                    ])->first();

                    if ($existing) {
                        Storage::disk('public')->delete($existing->upload_document);
                        $existing->delete();
                    }

                    FileProfile::create([
                        'user_id' => $user->id,
                        'list_id' => $item['list_id'],
                        'upload_document' => $path,
                    ]);
                }
            });

            $registration->update([
                'status_apl01' => Apl01Status::PENDING,
            ]);

            $isPribadi = $user->profile->asesi_type === 'pribadi';

            if ($isPribadi) {
                $registration->payments()->firstOrCreate(
                    ['registration_id' => $registration->id],
                    [
                        'invoice_number' => InvoiceService::generateNumber('LSP-INV'),
                        'amount' => $registration->scheme->certification_fee,
                        'status' => PaymentStatus::UNPAID,
                    ]
                );
            }

            return $this->successResponse([
                'has_payment' => $isPribadi,
                'registration_id' => $registration->id,
            ], 'Pendaftaran APL-01 berhasil dikirim.');
        }

        abort(404);
    }

    public function show(AssessmentRegistration $registration)
    {
        $this->authorize('view', $registration);

        $buktiDasarListIds = BasicRequirementProof::where('scheme_id', $registration->scheme_id)
            ->pluck('list_id');

        $buktiDasar = FileProfile::where('user_id', $registration->user_id)
            ->whereIn('list_id', $buktiDasarListIds)
            ->get();

        $buktiAdminListIds = AdministrativeProof::pluck('list_id');

        $buktiAdmin = FileProfile::where('user_id', $registration->user_id)
            ->whereIn('list_id', $buktiAdminListIds)
            ->get();

        $profile = $registration->candidate->profile;

        return $this->successResponse([
            'registration_id' => $registration->id,
            'asesi_type' => $profile->asesi_type,
            'institution_name' => $profile->institution_name,
            'status_apl01' => $registration->status_apl01,
            'certification_purpose' => $registration->certification_purpose,
            'scheme' => $registration->scheme?->only('id', 'code', 'name'),
            'apl_data' => $registration->aplData,
            'evidence' => $buktiDasar,
            'administrative_evidence' => $buktiAdmin,
            'payment' => $profile->asesi_type === 'pribadi'
                ? $registration->payments()->first()?->only('invoice_number', 'amount', 'status')
                : null
        ], 'Data APL01 berhasil ditampilkan');
    }
}
