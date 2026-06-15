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
        Schema::create('heating_readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id')->unique();
            $table->decimal('temperature_c', 5, 2);
            $table->decimal('humidity', 5, 2);
            $table->string('heating_status');
            $table->string('heating_mode');
            $table->decimal('target_temperature', 5, 2)->nullable();
            $table->string('device')->nullable();
            $table->timestamp('read_at');
            // no timestamps() — read_at already tracks when the sensor reported
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heating_readings');
    }
};
