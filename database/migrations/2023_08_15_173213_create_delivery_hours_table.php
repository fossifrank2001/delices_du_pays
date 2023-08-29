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
        Schema::create('delivery_hours', function (Blueprint $table) {
            $table->unsignedBigInteger('HRS_ID_HOURS', true);
            $table->time('HRS_TIME');
        });
        Schema::create('delivery_hours_users', function (Blueprint $table) {
            $table->unsignedBigInteger('HRS_ID_HOURS');
            $table->unsignedBigInteger('CTE_ID_COMPTE');
            $table->foreign('HRS_ID_HOURS')->references('HRS_ID_HOURS')->on('delivery_hours');
            $table->foreign('CTE_ID_COMPTE')->references('CTE_ID_COMPTE')->on('users');
            $table->primary(['HRS_ID_HOURS', 'CTE_ID_COMPTE']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_hours');
        Schema::dropIfExists('delivery_hours_users');
    }
};
