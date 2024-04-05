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
        Schema::table('clients', function (Blueprint $table) {
            $table->text('airport')->nullable();
            $table->text('hotel')->nullable();
            $table->dateTime('arrivalDate')->nullable();
            $table->string('num_air')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('airport')->nullable();
            $table->dropColumn('hotel')->nullable();
            $table->dropColumn('arrivalDate')->nullable();
            $table->dropColumn('num_air')->nullable();
        });
    }
};
