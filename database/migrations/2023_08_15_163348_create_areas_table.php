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
        Schema::create('areas', function (Blueprint $table) {
            $table->unsignedBigInteger('ARE_ID_AREA', true);
            $table->unsignedBigInteger('TWN_ID_TOWN');
            $table->string('ARE_LIBELLE');
            $table->foreign('TWN_ID_TOWN')->references('TWN_ID_TOWN')->on('towns')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
