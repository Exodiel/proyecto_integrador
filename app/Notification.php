<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  // protected $connection = 'mysql';
  protected $fillable = [
    'type',
    'notifiable_id',
    'norifiable_type',
    'data'
  ];
}
