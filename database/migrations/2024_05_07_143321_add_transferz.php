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
        Schema::create('transferzs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('driver_link')->nullable();
            $table->string('journey_code')->nullable();
            $table->string('pickup_date')->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->string('flight_number')->nullable();
            $table->string('travellers')->nullable();
            $table->string('suitcases')->nullable();
            $table->string('meet_greet')->nullable();
            $table->string('Add_ons')->nullable();
            $table->string('comments')->nullable();
            $table->string('vehicle_category')->nullable();
            $table->string('partner_reference')->nullable();
            $table->integer('userId')->nullable();
            $table->integer('vehicleId')->nullable();
            $table->integer('representId')->nullable();
            $table->string('numServcice')->nullable();

            $table->enum('status',['SIN ASIGNAR', 'COMPLETADO','ASIGNADO','EN PROGRESO','REPRESENTANTE','DESP_CHOFER'])->default('SIN ASIGNAR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferzs');
    }
};
