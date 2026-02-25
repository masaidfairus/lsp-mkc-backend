<?php

use App\Enums\Gender;
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
        Schema::create('apl01_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('gender')->default(Gender::MALE);
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable()->index();
            $table->string('latest_education')->nullable();
            $table->string('company_name');
            $table->string('job_title');
            $table->text('company_address')->nullable();
            $table->string('company_contact')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apl01_data');
    }
};
