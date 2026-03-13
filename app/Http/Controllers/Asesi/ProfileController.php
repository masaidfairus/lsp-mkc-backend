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
        return $this->successResponse(
            $request->user()->profile,
            'Data profile berhasil ditampilkan'
        );
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
            'asesi_type' => ['required', 'in:pribadi,institusi'],
            'institution_name' => ['required_if:asesi_type,institusi', 'nullable', 'string', 'max:255'],
        ]);

        $user->profile()->updateOrCreate(['user_id' => $user->id], $validated);

        return $this->successResponse(
            $user->fresh()->profile,
            'Data profile berhasil disimpan'
        );
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'gender' => ['sometimes', new Enum(Gender::class)],
            'date_of_birth' => ['sometimes', 'date', 'before:today'],
            'address' => ['sometimes', 'string', 'max:1000'],
            'phone_number' => ['sometimes', 'string', 'regex:/^(\+62|0)8[1-9][0-9]{6,10}$/'],
            'education_qualifications' => ['sometimes', 'string', 'max:255'],
            'company_name' => ['sometimes', 'string', 'max:255'],
            'job_title' => ['sometimes', 'string', 'max:255'],
            'company_address' => ['sometimes', 'string', 'max:1000'],
            'company_contact' => ['sometimes', 'string', 'max:20'],
            'asesi_type' => ['sometimes', 'in:pribadi,institusi'],
            'institution_name' => ['required_if:asesi_type,institusi', 'nullable', 'string', 'max:255'],
        ]);

        $user->profile()->update($validated);

        return $this->successResponse(
            $user->fresh()->profile,
            'Data profile berhasil diupdate'
        );
    }
}
