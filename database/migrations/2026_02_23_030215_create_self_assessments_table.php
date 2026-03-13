<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('self_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->foreignId('element_id')->constrained('competency_elements')->cascadeOnDelete();
            $table->boolean('is_competent')->default(false);
            $table->boolean('is_verified_by_assessor')->default(false);
            $table->text('assessor_notes')->nullable();
            $table->timestamps();
            $table->unique(['registration_id', 'element_id'], 'unique_kuk_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_assessments');
    }
};
