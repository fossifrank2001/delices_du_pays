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
        Schema::create('repas', function (Blueprint $table) {
            $table->unsignedBigInteger('MEL_ID_MEAL', true);
            $table->unsignedBigInteger('ART_ID_ARTICLE')->nullable();
            $table->boolean('MEL_IN_PROMOTION')->default(false);
            $table->decimal('MEL_REDUCTION', 8, 2)->default(0.00);
            $table->dateTime("MEL_CREATED_AT")->nullable();
            $table->dateTime("MEL_UPDATED_AT")->nullable();

            $table->foreign('ART_ID_ARTICLE')->references('ART_ID_ARTICLE')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repas');
    }
};
