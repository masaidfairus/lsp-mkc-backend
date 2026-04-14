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
            $table->foreignId('registration_id')->constrained('assessment_registrations')->cascadeOnDelete();
            $table->string('gender')->default(Gender::MALE);
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->text('address');
            $table->date('home_postal_code');
            $table->string('phone_number')->index();
            $table->string('education_qualifications');
            $table->string('company_name');
            $table->string('job_title');
            $table->text('company_address');
            $table->text('company_postal_code');
            $table->string('company_contact');
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
