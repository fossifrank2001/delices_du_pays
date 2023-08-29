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
        Schema::create('systeme_paiements', function (Blueprint $table) {
            $table->unsignedBigInteger('SDP_ID_SYSTEM_PAIEMENT', true);
            $table->unsignedBigInteger('MDP_ID_MOD_PAIEMENT');
            $table->string('SDP_LIBELLE')->nullable();

            $table->foreign('MDP_ID_MOD_PAIEMENT')->references('MDP_ID_MOD_PAIEMENT')->on('mode_paiements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systeme_paiements');
    }
};
