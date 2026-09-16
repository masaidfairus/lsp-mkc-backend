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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('job_code')->nullable()->after('job_title');
        });

        Schema::table('apl01_data', function (Blueprint $table) {
            $table->string('job_code')->nullable()->after('job_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('job_code');
        });

        Schema::table('apl01_data', function (Blueprint $table) {
            $table->dropColumn('job_code');
        });
    }
};
