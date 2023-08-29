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
        Schema::create('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('ART_ID_ARTICLE', true);
            $table->unsignedBigInteger('STA_ID_STATUT')->nullable();
            $table->string('ART_NAME');
            $table->decimal('ART_PRICE', 8, 2);
            $table->text('ART_DESCRIPTION')->nullable();
            $table->integer('ART_QUANTITY');
            $table->decimal('ART_NOTE', 2, 1)->nullable()->default(0.0);

            $table->foreign('STA_ID_STATUT')->references('STA_ID_STATUT')->on('statuts')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
