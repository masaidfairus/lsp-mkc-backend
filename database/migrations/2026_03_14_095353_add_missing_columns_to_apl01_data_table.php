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
    Schema::table('apl01_data', function (Blueprint $table) {
        if (!Schema::hasColumn('apl01_data', 'place_of_birth')) {
            $table->string('place_of_birth')->nullable()->after('gender');
        }
        
        // kolom lain yang ada di migration ini juga wrap yang sama
        if (!Schema::hasColumn('apl01_data', 'kolom_lain')) {
            $table->string('kolom_lain')->nullable();
        }
    });
}

public function down(): void
{
    Schema::table('apl01_data', function (Blueprint $table) {
        $table->dropColumn(['place_of_birth', 'home_postal_code', 'company_postal_code']);
    });
}
};
