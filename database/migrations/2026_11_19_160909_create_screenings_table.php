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
            Schema::create('screenings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('donor_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('screened_by')->constrained('users');
        $table->unsignedBigInteger('appointment_id')->nullable()->unique();
        $table->decimal('weight_kg', 5, 2);
        $table->unsignedSmallInteger('systolic');
        $table->unsignedSmallInteger('diastolic');
        $table->unsignedSmallInteger('pulse');
        $table->decimal('temperature_c', 4, 1);
        $table->decimal('hemoglobin_g_dl', 4, 1);
        $table->string('outcome', 20);
        $table->text('reason')->nullable();
        $table->date('deferral_until')->nullable();
        $table->text('notes')->nullable();
        $table->timestamp('screened_at');
        $table->timestamps();
    });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('screenings');
        }
    };
