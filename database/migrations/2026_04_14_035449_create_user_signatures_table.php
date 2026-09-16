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
        Schema::create('user_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->longText('signature_image')->nullable(); // Base64 or path
            $table->longText('signature_shape')->nullable(); // Compressed path data
            $table->longText('qr_code')->nullable(); // QR code image data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_signatures');
    }
};
