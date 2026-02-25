<?php

use App\Enums\Result;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessor_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('performance_criteria')->cascadeOnDelete();

            $table->string('form_code', 10)->index();

            $table->boolean('is_valid')->default(false);
            $table->boolean('is_authentic')->default(false);
            $table->boolean('is_current')->default(false);
            $table->boolean('is_sufficient')->default(false);

            $table->string('result')->default(Result::BELUM_KOMPETEN)->index();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->unique(['registration_id', 'criteria_id', 'form_code'], 'unique_evaluation_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessor_evaluations');
    }
};
