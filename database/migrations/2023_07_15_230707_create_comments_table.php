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
        Schema::create('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('COM_ID_COMMENT', true);
            $table->text('COM_CONTENT');
            $table->morphs("COMMENTABLE");
            $table->dateTime("COM_CREATED_AT");
            $table->dateTime("COM_UPDATED_AT");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
