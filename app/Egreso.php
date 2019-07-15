<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
  protected $fillable = [
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
}
