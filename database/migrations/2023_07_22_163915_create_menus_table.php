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
        Schema::create('menus', function (Blueprint $table) {
            $table->unsignedBigInteger('MEN_ID_MENU', true);
            $table->string('MEN_LIBELLE', 255);
            $table->string('MEN_ICON',255);
            $table->string('MEN_URL',255)->nullable();
            $table->integer('MEN_GROUP');
            $table->integer('MEN_ORDER');

        });
        Schema::create('menus_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('MEN_ID_MENU');
            $table->unsignedBigInteger('ROL_ID_ROLE');
            $table->foreign('MEN_ID_MENU')->references('MEN_ID_MENU')->on('menus');
            $table->foreign('ROL_ID_ROLE')->references('ROL_ID_ROLE')->on('roles');
            $table->primary(['MEN_ID_MENU', 'ROL_ID_ROLE']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
        Schema::dropIfExists('accesses_menus');
    }
};
