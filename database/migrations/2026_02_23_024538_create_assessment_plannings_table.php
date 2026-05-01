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
        Schema::create('assessment_plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->unique()->constrained('assessment_registrations')->cascadeOnDelete();
            $table->json('mapa01_data')->nullable();
            $table->json('mapa02_data')->nullable();
            $table->json('ak01_data')->nullable();
            $table->json('ak07_data')->nullable();
            $table->json('methods')->nullable();
            $table->string('version')->default('1.0');
            $table->timestamp('confirmed_at')->nullable();
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
