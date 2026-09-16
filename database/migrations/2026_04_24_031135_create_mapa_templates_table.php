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
        Schema::create('mapa_templates', function (Blueprint $blue) {
            $blue->id();
            $blue->foreignId('scheme_id')->unique()->constrained()->onDelete('cascade');
            $blue->json('data');
            $blue->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapa_templates');
    }
};
