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
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('CTE_ID_COMPTE', true);
            $table->unsignedBigInteger('STA_ID_STATUT')->nullable();
            $table->string('CTE_FIRSTNAME')->nullable();
            $table->string('CTE_LASTNAME')->nullable();
            $table->string('CTE_EMAIL')->nullable()->unique();
            $table->dateTime('EMAIL_VERIFIED_AT')->nullable();
            $table->string('CTE_PASSWORD')->nullable();
            $table->string('CTE_PHONE')->nullable()->unique();
            $table->string('CTE_TOWN')->nullable();
            $table->string('CTE_QUARTER')->nullable();
            $table->string('CTE_TOKEN')->nullable();
            $table->text('REMENBER_TOKEN')->nullable();
            $table->dateTime('CTE_DATECREATE')->nullable();
            $table->dateTime('CTE_DATEUPDATE')->nullable();

            $table->foreign('STA_ID_STATUT')->references('STA_ID_STATUT')->on('statuts')->onDelete("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
