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
        Schema::create('apl02_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->foreignId('portfolio_id')->constrained('portfolios')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl02_portfolios');
    }
};
