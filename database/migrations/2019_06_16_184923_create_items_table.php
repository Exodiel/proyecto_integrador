<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('idcategoria')->unsigned();
          $table->string('codigo', 50)->nullable();
          $table->string('nombre', 100)->unique();
          $table->decimal('precio_compra', 11, 2);
          $table->integer('utilidad');
          $table->decimal('precio_venta', 11, 2);
          $table->integer('stock');
          $table->integer('stock_ant');
          $table->string('descripcion', 256)->nullable();
          $table->boolean('condicion')->default(1);
          $table->timestamps();

          $table->foreign('idcategoria')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('items');
    }
}
