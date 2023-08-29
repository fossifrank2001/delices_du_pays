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
        Schema::create('accesses', function (Blueprint $table) {
            $table->unsignedBigInteger('ACC_ID_ACCESS', true);
            $table->unsignedBigInteger('STA_ID_STATUT')->nullable();
            $table->unsignedBigInteger('CTE_ID_COMPTE')->nullable();
             $table->unsignedBigInteger('ROL_ID_ROLE')->nullable();
            $table->string('ACC_CODE_USER')->nullable();
             $table->foreign('ROL_ID_ROLE')->references('ROL_ID_ROLE')->on('roles')->onDelete('restrict');


            $table->foreign('STA_ID_STATUT')->references('STA_ID_STATUT')->on('statuts')->onDelete('restrict');
            $table->foreign('CTE_ID_COMPTE')->references('CTE_ID_COMPTE')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesses');
    }
};
