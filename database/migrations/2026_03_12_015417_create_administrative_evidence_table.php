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
        Schema::create('administrative_evidence_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // "Pas Foto 3x4", "KTP"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('administrative_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')
                ->constrained('assessment_registrations')
                ->cascadeOnDelete();
            $table->foreignId('evidence_type_id')
                ->constrained('administrative_evidence_types')
                ->cascadeOnDelete();
            $table->string('file_path');
            $table->string('status')->default('pending'); // pending, valid, invalid
            $table->boolean('is_valid_admin')->default(false);
            $table->text('admin_notes')->nullable();
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
        Schema::dropIfExists('administrative_evidence');
    }
};
