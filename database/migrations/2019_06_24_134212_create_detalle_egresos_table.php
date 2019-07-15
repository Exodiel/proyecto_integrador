<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEgresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_egresos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idegreso')->unsigned();
            $table->foreign('idegreso')->references('id')->on('egresos');
            $table->integer('iditem')->unsigned();
            $table->foreign('iditem')->references('id')->on('items');
            $table->integer('cantidad');
            $table->decimal('descuento', 4, 2);
            $table->decimal('precio', 11, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_egresos');
    }
}
