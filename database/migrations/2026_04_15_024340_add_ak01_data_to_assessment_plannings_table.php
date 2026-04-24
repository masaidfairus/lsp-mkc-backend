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
    Schema::table('assessment_plannings', function (Blueprint $table) {
        if (!Schema::hasColumn('assessment_plannings', 'ak01_data')) {
            $table->json('ak01_data')->nullable()->after('registration_id');
        }
    });
}

public function down(): void
{
    Schema::table('assessment_plannings', function (Blueprint $table) {
        $table->dropColumn('ak01_data');
    });
}
};
