<?php

use App\Enums\AssessmentStatus;
use App\Enums\CertificationPurpose;
use App\Enums\Result;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessment_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('scheme_id')->nullable()->constrained('schemes')->onDelete('restrict');
            $table->foreignId('assessor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tuk')->default('TUK Sewaktu');
            $table->string('certification_purpose')->default(CertificationPurpose::SERTIFIKASI);
            $table->string('status')->default(AssessmentStatus::APL01_DRAFT);
            $table->string('final_result')->default(Result::BELUM_KOMPETEN)->nullable();
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_registrations');
    }
};
