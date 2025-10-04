<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContabilidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('contabilidads', function (Blueprint $table) {
            $table->id('idcontabilidad'); // Usando la clave primaria correcta según el modelo
            
            // 1. Datos de la Transacción
            $table->date('fecha_transaccion');
            $table->enum('tipo_movimiento', ['INGRESO', 'EGRESO', 'TRANSFERENCIA']);
            $table->decimal('monto', 15, 2)->default(0.00); 
            $table->text('descripcion')->nullable();

            // 2. Referencia (Clave Foránea Lógica/Simple)
            $table->unsignedBigInteger('referencia_id')->nullable()->comment('ID de la Factura o Pedido asociado');

            // 3. Clave Foránea a Sucursales
            $table->unsignedInteger('fk_id_sucursal')->nullable(); 

            $table->timestamps();

            // 4. Definición de la Restricción Foreign Key
            $table->foreign('fk_id_sucursal')
                  ->references('idsucursal')
                  ->on('sucursales')
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
        Schema::dropIfExists('contabilidads');
    }
}
