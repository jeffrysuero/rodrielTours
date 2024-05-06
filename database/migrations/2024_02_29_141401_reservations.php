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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('clientId')->constrained('clients')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('clientId')->nullable();;
            $table->decimal('total_cost', 8, 2);
            // $table->foreignId('vehicleId')->constrained('vehicles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('min_KM')->nullable();
            $table->string('suitcases')->nullable();
            $table->string('numPeople')->nullable();
            $table->text('url')->nullable();
            $table->integer('vehicleId')->nullable();
            $table->string('arrivalDate')->nullable();
            $table->string('hour')->nullable();
            $table->enum('active',['PAGOS', 'SIN PAGAR'])->default('SIN PAGAR');
            $table->string('numServcice')->nullable();
            $table->boolean('arrive')->default(false);
            $table->enum('status',['SIN ASIGNAR', 'COMPLETADO','ASIGNADO','EN PROGRESO','REPRESENTANTE','DESP_CHOFER'])->default('SIN ASIGNAR');
            $table->timestamp('dateInitiated')->nullable();
            $table->timestamp('pickUpClient')->nullable();
            $table->timestamp('finishTrip')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
