<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
  // protected $connection = 'mysql';
  protected $fillable = [
    'idproveedor',
    'idusuario',
    'tipo_comprobante',
    'serie_comprobante',
    'num_comprobante',
    'fecha_hora',
    'iva',
    'total'
  ];
  public function usuario() {
    return $this->belongsTo('App\User');
  }
  public function proveedor() {
    return $this->belongsTo('App\Proveedor');
  }
}
