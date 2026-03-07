<?php

namespace App\Http\Controllers;

use App\Enums\CertificationPurpose;
use App\Enums\Gender;
use App\Enums\Status;
use App\Models\Apl01Evidence;
use App\Models\AssessmentRegistration;
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
            $profile = $user->profile;
            if ($profile) {
                $user->aplData()->create(['user_id' => $user->id], $profile);
                return $this->successResponse(['next_step' => 2], 'Step 1 saved. Proceed to next step details.');
            }

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

            $user->aplData()->create($validated);
            return $this->successResponse(['next_step' => 2], 'Step 1 saved. Proceed to next step details.');
        }

        if ($step == 2) {
            if (!$user->aplData()->exists()) {
                return $this->errorResponse('E2006', 403);
            }

            $validated = $request->validate([
                'certification_purpose' => ['required', new Enum(CertificationPurpose::class)],
                'scheme_id' => ['required', 'exists:\App\Models\Scheme,id']
            ]);

            $registration = $user->registrations()->create($validated);
            return $this->successResponse(['next_step' => 3, 'registration_id' => $registration->id], 'Step 2 saved. Proceed to next step details.');
        }

        if ($step == 3) {
            $validated = $request->validate([
                'registration_id' => ['required', 'exists:assessment_registrations,id'],
                'evidence' => ['required', 'array', 'min:1'],
                'evidence.*.requirement_id' => ['required', 'exists:scheme_requirements,id'],
                'evidence.*.file' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:2048'],
            ]);

            $registrationId = $validated['registration_id'];
            $uploadedRecords = [];

            foreach ($validated['evidence'] as $item) {
                $file = $item['file'];

                $filename = "user_{$user->id}_req_{$item['requirement_id']}_" . time() . "." . $file->getClientOriginalExtension();

                $path = $file->storeAs("evidence/{$registrationId}", $filename, 'public');

                $uploadedRecords[] = Apl01Evidence::create([
                    'registration_id' => $registrationId,
                    'requirement_id' => $item['requirement_id'],
                    'file_path' => $path,
                    'status' => 'pending',
                    'is_valid_admin' => false,
                ]);
            }

            $user->registrations()->where('id', $registrationId)->update([
                'status_apl01' => Status::PENDING,
            ]);

            return $this->successResponse(['files_uploaded' => count($uploadedRecords)], 'Bukti sukses di unggah');
        }

        abort(404);
    }
}
