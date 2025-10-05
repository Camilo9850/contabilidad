<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('idcliente');
            $table->string('nombre', 50)->nullable();
            $table->string('apellido', 50)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('direccion', 50)->nullable();
            $table->string('dni', 50)->nullable();
            $table->string('celular', 50)->nullable();
            $table->string('correo', 50)->nullable()->unique(); 
            $table->string('clave', 150)->nullable();
            $table->timestamp('fecha_registro')->useCurrent()->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}