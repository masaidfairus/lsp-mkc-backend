<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Enums\AssessmentStatus;
use App\Enums\CertificationPurpose;
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
                'identity_number' => ['required', 'numeric', 'digits:16'],
                'gender' => ['required', new Enum(Gender::class)],
                'place_of_birth' => ['required', 'string', 'max:1000'],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'address' => ['required', 'string', 'max:1000'],
                'home_postal_code' => ['required', 'numeric', 'digits:5', 'between:10000,99999'],
                'phone_number' => ['required', 'string', 'regex:/^(\+62|0)8[1-9][0-9]{6,10}$/'],
                'education_id' => ['required', 'exists:master_education,id'],
                'company_name' => ['required', 'string', 'max:255'],
                'job_title' => ['required', 'string', 'max:255'],
                'company_address' => ['required', 'string', 'max:1000'],
                'company_postal_code' => ['required', 'numeric', 'digits:5', 'between:10000,99999'],
                'company_fax' => ['nullable', 'string', 'max:20'],
                'company_email' => ['nullable', 'email', 'max:255'],
                'company_contact' => ['required', 'string', 'max:20'],
                'registration_id' => ['nullable', 'exists:assessment_registrations,id'],
                'scheme_id' => ['required', 'exists:schemes,id'],
            ]);

            $asesiType = $request->validate([
                'asesi_type' => ['required', 'in:pribadi,institusi'],
                'institution_name' => ['required_if:asesi_type,institusi', 'nullable', 'string', 'max:255'],
            ]);

            $user->profile()->updateOrCreate([], array_merge(collect($validated)->except('registration_id')->toArray(), $asesiType));

            if (!empty($validated['registration_id'])) {
                $registration = $user->registrations()->findOrFail($validated['registration_id']);
                $registration->update(['scheme_id' => $validated['scheme_id']]);
                
                $registration->aplData()->updateOrCreate(
                    ['registration_id' => $registration->id],
                    collect($validated)->except(['registration_id', 'scheme_id'])->toArray()
                );
            } else {
                $registration = $user->registrations()->firstOrCreate([
                    'scheme_id' => $validated['scheme_id'],
                    'status' => \App\Enums\AssessmentStatus::APL01_DRAFT,
                ]);
                $registration->aplData()->updateOrCreate(
                    ['registration_id' => $registration->id],
                    collect($validated)->except(['registration_id', 'scheme_id'])->toArray()
                );
            }

            return $this->successResponse([
                'next_step' => 'data-sertifikasi',
                'registration_id' => $registration->id
            ], 'Bagian 1 tersimpan. Lanjutkan ke bagian berikutnya.');
        }

        if ($step == 'data-sertifikasi') {
            $validated = $request->validate([
                'registration_id' => ['required', 'exists:assessment_registrations,id'],
                'asesi_type' => ['required', 'string'],
                'institution_name' => ['required_if:asesi_type,institusi', 'nullable', 'string'],
                'certification_purpose' => ['required', 'string'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);
            
            // Update data di pendaftaran (isolasi)
            $registration->update([
                'asesi_type' => $validated['asesi_type'],
                'institution_name' => $validated['institution_name'],
                'certification_purpose' => $validated['certification_purpose'],
            ]);

            // Update juga di profil untuk data default di masa depan
            $user->profile()->update([
                'asesi_type' => $validated['asesi_type'],
                'institution_name' => $validated['institution_name'],
            ]);

            $userFiles = FileProfile::where('user_id', $user->id)->get()->keyBy('list_id');

            $buktiDasar = BasicRequirementProof::where('scheme_id', $registration->scheme_id)
                ->with('documentList')
                ->get()
                ->map(function ($req) use ($userFiles) {
                    $uploaded = $userFiles->get($req->list_id);

                    return [
                        'list_id' => $req->list_id,
                        'title' => $req->documentList?->title ?? 'Dokumen Tidak Ditemukan',
                        'is_mandatory' => $req->is_mandatory,
                        'or_group' => $req->or_group,
                        'is_uploaded' => $uploaded ? true : false,
                        'file_url' => $uploaded ? route('documents.stream', ['document' => $uploaded->id]) : null,
                        'file_profile_id' => $uploaded?->id,
                    ];
                });

            $buktiAdmin = AdministrativeProof::with('documentList')
                ->get()
                ->map(function ($adm) use ($userFiles) {
                    $uploaded = $userFiles->get($adm->list_id);

                    return [
                        'list_id' => $adm->list_id,
                        'title' => $adm->documentList?->title ?? 'Dokumen Tidak Ditemukan',
                        'is_mandatory' => true,
                        'is_uploaded' => $uploaded ? true : false,
                        'file_url' => $uploaded ? route('documents.stream', ['document' => $uploaded->id]) : null,
                        'file_profile_id' => $uploaded?->id,
                    ];
                });

            return $this->successResponse([
                'next_step' => 'bukti-persyaratan-dasar',
                'registration_id' => $registration->id,
                'asesi_type' => $registration->asesi_type,
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
                'evidence.*.file_profile_id' => ['nullable', 'exists:file_profiles,id'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);

            if (!$registration->scheme_id) {
                return $this->errorResponse('E2008', 403);
            }

            if (in_array($registration->status, [
                AssessmentStatus::APL01_PENDING,
                AssessmentStatus::APL01_PAID,
                AssessmentStatus::APL01_APPROVED,
            ])) {
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

                $hasFileId = !empty($item['file_profile_id']);

                if (!$alreadyUploaded && !$hasFileId && !isset($item['file'])) {
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
                    if (!empty($item['file_profile_id'])) {
                        $fileProfile = FileProfile::where('user_id', $user->id)->findOrFail($item['file_profile_id']);
                        $fileProfile->update(['list_id' => $item['list_id']]);
                        continue;
                    }

                    if (isset($item['file'])) {
                        $file = $item['file'];
                        $filename = "user_{$user->id}_req_{$item['list_id']}_" . uniqid() . "." . $file->getClientOriginalExtension();
                        $path = $file->storeAs("bukti-dasar/{$registration->id}", $filename, 'local');

                        $existing = FileProfile::where([
                            'user_id' => $user->id,
                            'list_id' => $item['list_id'],
                        ])->first();

                        if ($existing) {
                            Storage::disk('local')->delete($existing->upload_document);
                            $existing->delete();
                        }

                        FileProfile::create([
                            'user_id' => $user->id,
                            'list_id' => $item['list_id'],
                            'upload_document' => $path,
                        ]);
                    }
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
                'evidence.*.file_profile_id' => ['nullable', 'exists:file_profiles,id'],
                'signature' => ['nullable', 'image', 'max:1024'],
            ]);

            $registration = $user->registrations()->findOrFail($validated['registration_id']);

            if (in_array($registration->status, [
                AssessmentStatus::APL01_PENDING,
                AssessmentStatus::APL01_PAID,
                AssessmentStatus::APL01_APPROVED,
            ])) {
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

            DB::transaction(function () use ($validated, $registration, $user, $request) {
                foreach ($validated['evidence'] as $item) {
                    if (!empty($item['file_profile_id'])) {
                        $fileProfile = FileProfile::where('user_id', $user->id)->findOrFail($item['file_profile_id']);
                        $fileProfile->update(['list_id' => $item['list_id']]);
                        continue;
                    }

                    if (isset($item['file'])) {
                        $file = $item['file'];
                        $filename = "user_{$user->id}_list_{$item['list_id']}_" . uniqid() . "." . $file->getClientOriginalExtension();
                        $path = $file->storeAs("bukti-admin/{$registration->id}", $filename, 'local');

                        $existing = FileProfile::where([
                            'user_id' => $user->id,
                            'list_id' => $item['list_id'],
                        ])->first();

                        if ($existing) {
                            Storage::disk('local')->delete($existing->upload_document);
                            $existing->delete();
                        }

                        FileProfile::create([
                            'user_id' => $user->id,
                            'list_id' => $item['list_id'],
                            'upload_document' => $path,
                        ]);
                    }
                }

                if ($request->hasFile('signature')) {
                    $sig = $request->file('signature');
                    $sigName = "sig_user_{$user->id}_" . uniqid() . "." . $sig->getClientOriginalExtension();
                    $sigPath = $sig->storeAs("signatures", $sigName, 'local');

                    // 1. Update Profile Signature
                    $user->userSignature()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'name' => $user->name,
                            'signature_image' => $sigPath,
                        ]
                    );

                    // 2. Snapshot to APL.01 Data
                    $qrText = route('signature.verify', ['id' => $registration->id, 'type' => 'apl01']);
                    $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($qrText));

                    $registration->aplData()->update([
                        'asesi_signature' => $sigPath,
                        'asesi_qr_code' => $qrBase64,
                        'asesi_signature_date' => now(),
                    ]);
                }
            });

            $registration->update([
                'status_apl01' => \App\Enums\Apl01Status::PENDING,
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
        if ($registration->user_id !== request()->user()->id) {
            return $this->errorResponse('E1005', 403);
        }

        $userFiles = FileProfile::where('user_id', $registration->user_id)->get()->keyBy('list_id');

        $buktiDasar = BasicRequirementProof::where('scheme_id', $registration->scheme_id)
            ->with('documentList')
            ->get()
            ->map(function ($req) use ($userFiles) {
                $uploaded = $userFiles->get($req->list_id);
                return [
                    'list_id' => $req->list_id,
                    'title' => $req->documentList?->title ?? 'Dokumen Tidak Ditemukan',
                    'is_mandatory' => $req->is_mandatory,
                    'or_group' => $req->or_group,
                    'is_uploaded' => $uploaded ? true : false,
                    'file_url' => $uploaded ? asset('storage/' . $uploaded->upload_document) : null,
                    'file_profile_id' => $uploaded?->id,
                ];
            });

        $buktiAdmin = AdministrativeProof::with('documentList')
            ->get()
            ->map(function ($adm) use ($userFiles) {
                $uploaded = $userFiles->get($adm->list_id);
                return [
                    'list_id' => $adm->list_id,
                    'title' => $adm->documentList?->title ?? 'Dokumen Tidak Ditemukan',
                    'is_mandatory' => true,
                    'is_uploaded' => $uploaded ? true : false,
                    'file_url' => $uploaded ? asset('storage/' . $uploaded->upload_document) : null,
                    'file_profile_id' => $uploaded?->id,
                ];
            });

        $registration->load(['candidate.profile', 'scheme', 'aplData']);
        $profile = $registration->candidate?->profile;

        if (!$profile) {
            return $this->errorResponse('E2001', 404);
        }

        return $this->successResponse([
            'registration_id' => $registration->id,
            'candidate_name' => $registration->candidate?->name ?? 'User',
            'asesi_type' => $profile->asesi_type,
            'institution_name' => $profile->institution_name,
            'status' => $registration->status,
            'certification_purpose' => $registration->certification_purpose,
            'scheme' => $registration->scheme?->only('id', 'code', 'name'),
            'apl_data' => $registration->aplData,
            'bukti_persyaratan_dasar' => $buktiDasar,
            'bukti_administratif' => $buktiAdmin,
            'payment' => $profile->asesi_type === 'pribadi'
                ? $registration->payments()->first()?->only('invoice_number', 'amount', 'status')
                : null
        ], 'Data APL01 berhasil ditampilkan');
    }
}
