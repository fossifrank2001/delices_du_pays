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
        Schema::create('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('CAT_ID_CATEGORY', true);
            $table->string('CAT_LIBELLE');
        });

        Schema::create('categories_repas', function (Blueprint $table){
            $table->unsignedBigInteger('CAT_ID_CATEGORY');
            $table->unsignedBigInteger('MEL_ID_MEAL');
            $table->foreign('CAT_ID_CATEGORY')->references('CAT_ID_CATEGORY')->on('categories')->onDelete('cascade');
            $table->foreign('MEL_ID_MEAL')->references('MEL_ID_MEAL')->on('repas');
            $table->primary(['CAT_ID_CATEGORY', 'MEL_ID_MEAL']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_repas');
        Schema::dropIfExists('categories');
    }
};
