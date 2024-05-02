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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->integer('userId')->nullable();
            // $table->foreignId('userId')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('marca');
            $table->string('modelo');
            // $table->integer('year');
            // $table->string('type');
            $table->string('image');
            $table->integer('passenger_capacity');
            $table->integer('luggage_capacity');
            // $table->decimal('cost_per_day', 8, 2);
            $table->string('placa')->nullable();
            $table->string('color')->nullable();
            $table->integer('percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
