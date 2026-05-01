<?php

use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
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
        Schema::table('assessment_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('assessment_registrations', 'status_apl01')) {
                $table->string('status_apl01')->default(Apl01Status::NOT_STARTED->value)->index()->after('tuk');
            }
            if (!Schema::hasColumn('assessment_registrations', 'status_apl02')) {
                $table->string('status_apl02')->default(Apl02Status::NOT_STARTED->value)->index()->after('status_apl01');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->dropColumn(['status_apl01', 'status_apl02']);
        });
    }
};
