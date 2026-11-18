<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Feature R4: Blood Request Tracking
     * and Feature R5: Emergency Escalation.
     */
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Core request details
            $table->string('patient_name', 150);
            $table->string('blood_group', 10)->index();
            $table->string('component_type', 30)->default('whole_blood'); // whole_blood, red_cells, platelets, plasma
            $table->unsignedInteger('units_needed')->default(1);
            $table->unsignedInteger('units_fulfilled')->default(0);

            // Location
            $table->string('hospital_name', 200);
            $table->string('city', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Status progression: open -> partial -> fulfilled / expired
            $table->string('status', 20)->default('open')->index(); // open, partial, fulfilled, expired
            $table->string('urgency', 20)->default('normal'); // normal, urgent, critical

            // Deadline / expiration
            $table->dateTime('deadline_at');
            $table->text('notes')->nullable();

            // Emergency escalation fields (Feature R5)
            $table->boolean('is_emergency')->default(false)->index();
            $table->string('emergency_level', 20)->nullable(); // sos_critical, urgent_escalated
            $table->unsignedInteger('current_radius_km')->default(10);
            $table->unsignedInteger('escalation_count')->default(0);
            $table->dateTime('last_escalated_at')->nullable();
            $table->boolean('is_broadcast_pinned')->default(false);
            $table->string('contact_phone', 25)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
