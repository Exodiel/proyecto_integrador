<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  // protected $connection = 'mysql';
  protected $fillable = [
    'idcategoria',
    'codigo',
    'nombre',
    'precio_compra',
    'utilidad',
    'precio_venta',
    'stock',
    'stock_ant',
    'descripcion',
    'condicion'
  ];

  public function categoria()
  {
    return $this->belongsTo('App\Categoria');
  }

  function transacciones()
  {
    return $this->hasMany('App\Transaccion');
  }

}
