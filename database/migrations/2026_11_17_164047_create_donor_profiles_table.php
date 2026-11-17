<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Feature R1: Donor Health Evaluation Engine.
     */
    public function up(): void
    {
        Schema::create('donor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            
            // Core physical attributes
            $table->string('gender', 20)->default('male');
            $table->date('date_of_birth')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->string('blood_group', 10)->nullable();
            
            // Donation interval tracking
            $table->date('last_donation_date')->nullable();
            $table->string('last_donation_type', 30)->default('whole_blood'); // whole_blood, platelets, plasma
            
            // Health declaration questionnaire responses
            $table->boolean('has_recent_tattoo')->default(false); // Within 6 months
            $table->boolean('has_recent_surgery')->default(false); // Within 6 months
            $table->boolean('has_fever_infection')->default(false); // Active infection or cold
            $table->boolean('has_chronic_illness')->default(false); // HIV, Hepatitis, Heart condition, etc.
            $table->boolean('is_pregnant_or_breastfeeding')->default(false);
            $table->decimal('hemoglobin_g_dl', 4, 1)->nullable(); // e.g. 13.5
            
            // Calculated eligibility engine outcomes
            $table->boolean('is_eligible')->default(false)->index();
            $table->string('eligibility_status', 30)->default('pending'); // eligible, temporarily_deferred, permanently_deferred, pending
            $table->date('next_eligible_date')->nullable();
            $table->json('ineligibility_reasons')->nullable(); // Detailed itemized reasons if deferred
            $table->timestamp('last_evaluated_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donor_profiles');
    }
};
