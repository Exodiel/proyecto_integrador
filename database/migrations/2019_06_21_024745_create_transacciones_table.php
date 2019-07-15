<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iditem')->unsigned();
            $table->dateTime('fecha_hora');
            $table->string('detalle', 200);
            $table->integer('entrada_cantidad')->default(0);
            $table->decimal('entrada_precio_unitario', 11, 2)->default(0);
            $table->decimal('entrada_valor_total', 11, 2)->default(0);
            $table->integer('salida_cantidad')->default(0);
            $table->decimal('salida_precio_unitario', 11, 2)->default(0);
            $table->decimal('salida_valor_total', 11, 2)->default(0);
            $table->integer('exis_cantidad')->default(0);
            $table->decimal('exis_precio_unitario', 11, 2)->default(0);
            $table->decimal('exis_valor_total', 11, 2)->default(0);

            $table->foreign('iditem')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacciones');
    }
}
