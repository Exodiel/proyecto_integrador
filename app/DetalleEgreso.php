<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEgreso extends Model
{
  // protected $connection = 'mysql';
  protected $table = 'detalle_egresos';
  protected $fillable = [
    'idegreso',
    'iditem',
    'cantidad',
    'precio',
    'descuento'
  ];
  public $timestamps = false;
}
