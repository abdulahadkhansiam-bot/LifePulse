<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Feature R8: Automated Expiry & Low-Stock Alerts.
     */
    public function up(): void
    {
        Schema::create('blood_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('bag_serial_number', 50)->unique();
            $table->string('blood_group', 10)->index(); // A+, A-, B+, B-, AB+, AB-, O+, O-
            $table->string('component_type', 30)->default('whole_blood')->index(); // whole_blood, red_cells, platelets, plasma
            $table->unsignedInteger('volume_ml')->default(450);
            $table->date('collection_date');
            $table->dateTime('expiry_date')->index();
            $table->string('storage_location', 100)->default('Main Cold Storage Room');
            $table->string('status', 30)->default('available')->index(); // available, reserved, transfused, expired, discarded
            $table->string('hospital_name', 150)->default('Central Blood Bank');
            $table->timestamps();
        });

        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alert_type', 30)->index(); // near_expiry, critical_expiry, low_stock, stockout, expired
            $table->string('severity', 20)->default('warning'); // critical, warning, info
            $table->string('blood_group', 10)->nullable()->index();
            $table->string('component_type', 30)->nullable();
            $table->text('message');
            $table->json('details')->nullable();
            $table->boolean('is_resolved')->default(false)->index();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
        Schema::dropIfExists('blood_inventory');
    }
};
