<?php

use App\Enums\EvidenceStatus;
use App\Enums\ValidationResult;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('list_id')->constrained('document_lists')->cascadeOnDelete();
            $table->string('upload_document');
            $table->string('status')->default(EvidenceStatus::Pending);
            $table->string('validation_result')->default(ValidationResult::Pending);
            $table->text('admin_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_profiles');
    }
};
