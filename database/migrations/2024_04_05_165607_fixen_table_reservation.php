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
            
            // $table->dropForeign('reservations_vehicleid_foreign');
            // $table->dropColumn('vehicleId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicleId');
            $table->foreign('vehicleId')->references('id')->on('vehicles')->cascadeOnUpdate()->cascadeOnDelete();
          
        });
    }
};
