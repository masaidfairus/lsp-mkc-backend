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
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('home_postal_code', 10)->nullable();
            $table->string('phone_number')->index()->nullable();
            $table->foreignId('education_id')->nullable()->constrained('master_education')->nullOnDelete();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_postal_code')->nullable();
            $table->string('company_fax')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_contact')->nullable();
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
