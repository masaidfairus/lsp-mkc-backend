<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Enums\Gender;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return $user->profile;
    }

    public function store(Request $request)
    {
        $user = $request->user();

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

        $user->profile()->firstOrCreate($validated);

        return $this->successResponse([$user->profile], 'Data profile anda telah disimpan');
    }

    public function update(Request $request)
    {}
}
