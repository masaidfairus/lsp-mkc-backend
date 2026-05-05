<?php

use App\Enums\AppealStatus;
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
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->json('ak04_data')->nullable();
            $table->string('status')->default(AppealStatus::PENDING);
            $table->string('asesi_signature')->nullable();
            $table->text('asesi_qr_code')->nullable();
            $table->timestamp('asesi_signature_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};
