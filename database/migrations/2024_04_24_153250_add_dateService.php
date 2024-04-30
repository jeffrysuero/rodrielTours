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
            // $table->timestamp('dateInitiated')->nullable();
            // $table->timestamp('pickUpClient')->nullable();
            // $table->timestamp('finishTrip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('dateInitiated')->nullable();
            $table->dropColumn('pickUpClient')->nullable();
            $table->dropColumn('finishTrip')->nullable();
        });
    }
};
