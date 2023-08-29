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
        Schema::create('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('PER_ID_PERMISSION', true);
            $table->string('PER_LIBELLE', 255);
        });
        // Migration pour la table 'role_permission'
        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedBigInteger('ROL_ID_ROLE'); // Clé étrangère liée à la table 'roles'
            $table->unsignedBigInteger('PER_ID_PERMISSION'); // Clé étrangère liée à la table 'permissions'
            $table->foreign('ROL_ID_ROLE')->references('ROL_ID_ROLE')->on('roles')->onDelete("cascade");
            $table->foreign('PER_ID_PERMISSION')->references('PER_ID_PERMISSION')->on('permissions');
            $table->primary(['ROL_ID_ROLE', 'PER_ID_PERMISSION']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
