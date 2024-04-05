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
            $table->string('min_KM')->nullable();
            $table->string('suitcases')->nullable();
            $table->string('numPeople')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('min_KM')->nullable();
            $table->dropColumn('suitcases')->nullable();
            $table->dropColumn('numPeople')->nullable();
        });
    }
};
