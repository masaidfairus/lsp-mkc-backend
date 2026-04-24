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
        if (Schema::hasColumn('assessment_plannings', 'methods')) {
            $table->json('methods')->nullable()->change();
        } else {
            $table->json('methods')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('assessment_plannings', function (Blueprint $table) {
        $table->json('methods')->nullable(false)->change();
    });
}
};
