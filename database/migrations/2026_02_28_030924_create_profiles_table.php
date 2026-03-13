<?php

use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('gender')->default(Gender::MALE);
            $table->date('date_of_birth');
            $table->text('address');
            $table->string('phone_number')->index();
            $table->string('education_qualifications');
            $table->string('company_name');
            $table->string('job_title');
            $table->text('company_address');
            $table->string('company_contact');
            $table->string('asesi_type')->default('pribadi');
            $table->string('institution_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
