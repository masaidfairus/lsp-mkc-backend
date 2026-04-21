<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apl01_evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apl01_data_id')->constrained('apl01_data')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->nullable();
            $table->string('validation_result')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apl01_evidence');
    }
};