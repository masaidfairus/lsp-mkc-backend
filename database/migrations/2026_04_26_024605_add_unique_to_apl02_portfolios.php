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
        Schema::table('apl02_portfolios', function (Blueprint $table) {
            $table->foreignId('list_id')->nullable()->after('registration_id')->constrained('document_lists')->cascadeOnDelete();
            $table->unique(['registration_id', 'list_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apl02_portfolios', function (Blueprint $table) {
            $table->dropUnique(['registration_id', 'list_id']);
        });
    }
};
