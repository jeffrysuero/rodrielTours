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
            $table->foreignId('clientId')->constrained('client')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('vehicleId')->constrained('vehicles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('total_cost', 8, 2);
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
