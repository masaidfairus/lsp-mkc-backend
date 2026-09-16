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
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->boolean('is_apl02_continuable')->nullable()->after('status_apl02');
            $table->string('apl02_approach')->nullable()->after('is_apl02_continuable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->dropColumn(['is_apl02_continuable', 'apl02_approach']);
        });
    }
};
