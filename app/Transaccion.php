<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
  // protected $connection = 'mysql';
  protected $table = 'transacciones';
  protected $fillable = [
    'iditem',
    'fecha_hora',
    'detalle',
    'entrada_cantidad',
    'entrada_precio_unitario',
    'entrada_valor_total',
    'salida_cantidad',
    'salida_precio_unitario',
    'salida_valor_total',
    'exis_cantidad',
    'exis_precio_unitario',
    'exis_valor_total'
  ];

  public $timestamps = false;

  public function item()
  {
    return $this->belongsTo('App\Item');
  }
}
