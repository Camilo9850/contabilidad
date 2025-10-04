<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToContabilidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contabilidads', function (Blueprint $table) {
            // Aseguramos que la columna fk_id_sucursal existe
            if (!Schema::hasColumn('contabilidads', 'fk_id_sucursal')) {
                $table->unsignedInteger('fk_id_sucursal')->nullable()->after('referencia_id');
            }
            
            // Agregamos la clave foránea
            $table->foreign('fk_id_sucursal')
                  ->references('idsucursal')
                  ->on('sucursals')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contabilidads', function (Blueprint $table) {
            $table->dropForeign(['fk_id_sucursal']);
            $table->dropColumn('fk_id_sucursal');
        });
    }
}
