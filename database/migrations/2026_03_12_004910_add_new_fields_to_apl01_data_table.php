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
        if (!Schema::hasColumn('apl01_data', 'home_postal_code')) {
            $table->string('home_postal_code')->nullable()->after('address');
        }
        if (!Schema::hasColumn('apl01_data', 'company_postal_code')) {
            $table->string('company_postal_code')->nullable()->after('company_address');
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
