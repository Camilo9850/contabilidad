<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContabilidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contabilidad', function (Blueprint $table) {
            $table->increments('idcontabilidad');
            $table->date('fecha_transaccion');
            $table->enum('tipo_movimiento', ['INGRESO', 'EGRESO', 'TRANSFERENCIA']);
            $table->decimal('monto', 10, 2);
            $table->integer('fk_id_sucursal')->nullable();
            $table->integer('referencia_id')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            // Agregar clave foránea si es necesario
            $table->foreign('fk_id_sucursal')->references('idsucursal')->on('sucursals')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contabilidad');
    }
}