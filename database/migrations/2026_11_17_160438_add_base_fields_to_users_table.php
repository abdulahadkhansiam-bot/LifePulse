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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('donor')->after('password')->index();
            $table->string('phone', 25)->nullable()->after('role');
            $table->string('blood_group', 10)->nullable()->after('phone');
            $table->string('status', 25)->default('active')->after('blood_group')->index();
            $table->string('city', 100)->nullable()->after('status');
            $table->string('address', 255)->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'blood_group', 'status', 'city', 'address']);
        });
    }
};
