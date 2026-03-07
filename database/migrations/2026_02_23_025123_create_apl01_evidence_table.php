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
        Schema::create('apl01_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->foreignId('requirement_id')->constrained('scheme_requirements')->cascadeOnDelete();
            
            $table->string('file_path');
            $table->boolean('is_valid_admin')->default(false);
            $table->text('admin_notes')->nullable();

            // Statusnya: 'pending', 'valid', 'invalid'
            $table->string('status')->default('pending')->index();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl01_evidence');
    }
};
