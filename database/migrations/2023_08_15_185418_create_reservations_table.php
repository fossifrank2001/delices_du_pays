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
            $table->unsignedBigInteger('RES_ID_RESERVATIONS', true);
            $table->unsignedBigInteger('STA_ID_STATUT');
            $table->unsignedBigInteger('HRS_ID_HOURS');
            $table->integer('RES_PEOPLES')->default(1);
            $table->string('NAME', 80);
            $table->timestamps();

            $table->foreign('STA_ID_STATUT')->references('STA_ID_STATUT')->on('statuts')->onDelete("restrict");
            $table->foreign('HRS_ID_HOURS')->references('HRS_ID_HOURS')->on('delivery_hours');
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
