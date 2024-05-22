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
            $table->text('airport')->nullable();
            $table->text('hotel')->nullable();
            // $table->string('arrivalDate')->nullable();
            $table->string('num_air')->nullable();
            $table->string('numChildren')->nullable();
            $table->string('numInfant')->nullable();
            $table->string('Datellegada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('airport')->nullable();
            $table->dropColumn('hotel')->nullable();
            // $table->dropColumn('arrivalDate')->nullable();
            $table->dropColumn('num_air')->nullable();
            $table->dropColumn('numChildren')->nullable();
            $table->dropColumn('numInfant')->nullable();
            $table->dropColumn('Datellegada')->nullable();
        });
    }
};
