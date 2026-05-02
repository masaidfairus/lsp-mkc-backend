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
        Schema::create('apl02_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->foreignId('file_profile_id')->constrained('file_profiles')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['registration_id', 'file_profile_id'], 'reg_file_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl02_portfolios');
    }
};
