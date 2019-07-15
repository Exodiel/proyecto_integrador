<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('egresos', function (Blueprint $table) {
          $table->increments('id');
          $table->string('detalle', 100);
          $table->integer('idusuario')->unsigned();
          $table->foreign('idusuario')->references('id')->on('users');
          $table->string('tipo_comprobante',20);
          $table->string('num_comprobante',10);
          $table->dateTime('fecha_hora');
          $table->decimal('descuento', 11, 2);
          $table->decimal('iva', 4, 2);
          $table->decimal('total', 11, 2);
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
        Schema::dropIfExists('egresos');
    }
}
