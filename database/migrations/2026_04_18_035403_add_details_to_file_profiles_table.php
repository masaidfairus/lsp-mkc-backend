<?php

use App\Enums\EvidenceStatus;
use App\Enums\ValidationResult;
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
        Schema::table('file_profiles', function (Blueprint $table) {
            $table->string('status')->default(EvidenceStatus::Pending)->after('upload_document');
            $table->string('validation_result')->default(ValidationResult::Pending)->after('status');
            $table->text('admin_notes')->nullable()->after('validation_result');
            $table->timestamp('verified_at')->nullable()->after('admin_notes');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_profiles', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);

            $table->dropColumn([
                'status',
                'validation_result',
                'admin_notes',
                'verified_at',
                'verified_by',
            ]);
        });
    }
};
