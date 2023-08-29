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
        Schema::create('boissons', function (Blueprint $table) {
            $table->unsignedBigInteger('BEV_ID_BEVERAGE', true);
            $table->unsignedBigInteger('ART_ID_ARTICLE')->nullable();
            $table->boolean('BEV_IS_ALCOHOLIC')->default(false);
            $table->decimal('BEV_DEGREE_ALCOHOLIC', 8, 2)->default(0.00);
            $table->dateTime("BEV_CREATED_AT")->nullable();
            $table->dateTime("BEV_UPDATED_AT")->nullable();

            $table->foreign('ART_ID_ARTICLE')->references('ART_ID_ARTICLE')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('boissons');
    }
};
