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
        //
        Schema::table('devices', function(Blueprint $table){
            // Actualizar columna type with this enums 'light', 'blind', 'door_sensor', 'window_sensor', 'camera', 'heating', 'smart_outlet', 'gas'
            $table->enum('type', ['light', 'blind', 'door_sensor', 'window_sensor', 'camera', 'heating', 'smart_outlet', 'gas'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
