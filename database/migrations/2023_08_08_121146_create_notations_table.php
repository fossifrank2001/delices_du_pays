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
        Schema::create('notations', function (Blueprint $table) {
            $table->unsignedBigInteger('NOT_ID_NOTATION', true);
            $table->decimal('VALUE', 8, 2)->default(0.00);
            $table->morphs("NOTABLE");
            $table->dateTime("NOT_CREATED_AT");
            $table->dateTime("NOT_UPDATED_AT");
            $table->unsignedBigInteger("CTE_ID_COMPTE");
            $table->foreign('CTE_ID_COMPTE')->references('CTE_ID_COMPTE')->on('users')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notations');
    }
};
