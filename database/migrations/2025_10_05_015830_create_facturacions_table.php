<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('facturacions', function (Blueprint $table) {
            
        
            $table->id('id_factura'); 

            // 2. Datos de Referencia y Cliente
            $table->string('numero_factura', 50)->unique(); // Número de factura único
            $table->date('fecha');

            // Clave Foránea a Clientes (Asumiendo que la PK de clientes se llama 'idcliente' y es unsigned)
            $table->foreignId('fk_id_cliente')
                  ->nullable()
                  ->constrained('clientes', 'idcliente')
                  ->onDelete('set null'); 

            // 3. Montos (DECIMAL para precisión)
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('impuesto', 15, 2)->default(0.00);
            $table->decimal('total_factura', 15, 2);
            
            // 4. Estado de la Factura (ENUM)
            $table->enum('estado', ['PENDIENTE', 'PAGADA', 'ANULADA'])->default('PENDIENTE');

            // 5. Marcas de tiempo (created_at y updated_at)
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
        Schema::dropIfExists('facturacions');
    }
}
