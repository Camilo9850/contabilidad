<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->string('numero_factura', 50)->unique();
            $table->date('fecha');
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('impuesto', 15, 2)->default(0.00);
            $table->decimal('total_factura', 15, 2);

            $table->enum('estado', ['PENDIENTE', 'PAGADA', 'ANULADA'])->default('PENDIENTE');

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
        Schema::dropIfExists('facturations');
    }
}
