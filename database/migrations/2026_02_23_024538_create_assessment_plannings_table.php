<?php

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
        Schema::create('assessment_plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->json('methods');
            $table->boolean('is_ak01_confirmed')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->string('version')->default('1.0');
            $table->json('mapa_context_json')->nullable();
            $table->boolean('ak01_agreed_asesi')->default(false);
            $table->boolean('ak01_agreed_assessor')->default(false);
            $table->timestamp('ak01_agreed_at')->nullable();
            $table->text('reasonable_adjustments_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_plannings');
    }
};
