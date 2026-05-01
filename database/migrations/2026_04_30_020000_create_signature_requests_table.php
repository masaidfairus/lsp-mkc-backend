<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signature_requests', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation ke dokumen (AssessmentInstrument, AssessmentPlanning, dll)
            $table->morphs('signable');

            // Untuk grouping di inbox per registrasi/skema
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();

            // Label dokumen untuk display (misal "FR.IA.01", "MAPA.01")
            $table->string('document_label');

            // User yang diminta TTD-nya
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Peran penanda tangan
            $table->string('role'); // "Asesor" | "Penyusun" | "Validator"

            // Status proses
            $table->enum('status', ['pending', 'signed'])->default('pending');

            // Data TTD real-time (diisi saat user menandatangani)
            $table->longText('raw_signature')->nullable(); // Base64 canvas data
            $table->longText('qr_code')->nullable();       // Base64/path QR yang dihasilkan

            $table->timestamp('signed_at')->nullable();
            $table->timestamps();

            // Index untuk query inbox
            $table->index(['user_id', 'status']);
            $table->index(['registration_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signature_requests');
    }
};
