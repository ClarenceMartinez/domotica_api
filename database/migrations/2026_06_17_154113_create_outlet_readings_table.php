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
        Schema::create('outlet_readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_id');
            $table->unsignedBigInteger('device_id');
            $table->string('outlet_name');
            $table->string('outlet_status');          // on | off
            $table->decimal('power_watts', 8, 2);
            $table->unsignedSmallInteger('minutes_on');
            $table->unsignedSmallInteger('minutes_over_limit');
            $table->unsignedSmallInteger('max_safe_minutes');
            $table->string('appliance_type')->nullable();
            $table->string('energy_status');          // normal | danger
            $table->string('risk_level');             // normal | medium | high
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlet_readings');
    }
};
