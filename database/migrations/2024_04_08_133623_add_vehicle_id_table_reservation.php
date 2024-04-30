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
        Schema::table('reservations', function (Blueprint $table) {
            // $table->integer('vehicleId')->nullable();
            // // $table->enum('status',['SIN ASIGNAR', 'COMPLETADO','ASIGNADO','EN PROGRESO'])->default('SIN ASIGNAR');
            // $table->enum('status',['SIN ASIGNAR', 'COMPLETADO','ASIGNADO','EN PROGRESO','REPRESENTANTE','DESP_CHOFER'])->default('SIN ASIGNAR');
            // $table->text('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('vehicleId')->nullable();
            $table->dropColumn('status')->nullable();
            $table->dropColumn('url')->nullable();
        });
    }
};
