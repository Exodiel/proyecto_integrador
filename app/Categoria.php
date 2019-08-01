<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
  // protected $connection = 'mysql';
  protected $fillable = ['nombre','descripcion','condicion'];

  function items()
  {
    return $this->hasMany('App\Item');
  }
}
