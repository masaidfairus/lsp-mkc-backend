<?php

use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apl01_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->string('gender')->default(Gender::MALE);
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('home_postal_code', 10)->nullable();
            $table->string('phone_number')->index()->nullable();
            $table->foreignId('education_id')->nullable()->constrained('master_education')->nullOnDelete();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_postal_code')->nullable();
            $table->string('company_contact')->nullable();
            
            // TTD Asesi APL.01
            $table->longText('asesi_signature')->nullable();
            $table->longText('asesi_qr_code')->nullable();
            $table->timestamp('asesi_signature_date')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apl01_data');
    }
};