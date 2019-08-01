<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function __invoke(Request $request)
  {
    $anio = date('Y');
    $ingresos = DB::table('ingresos as i')
      ->select(
        DB::raw('MONTH(i.fecha_hora) as mes'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('SUM(i.total) as total')
      )
      ->whereYear('i.fecha_hora', $anio)
      ->groupBy(
        DB::raw('MONTH(i.fecha_hora)'),
        DB::raw('YEAR(i.fecha_hora)')
      )
      ->get();

      $egresos = DB::table('egresos as e')
      ->select(
        DB::raw('MONTH(e.fecha_hora) as mes'),
        DB::raw('YEAR(e.fecha_hora) as anio'),
        DB::raw('SUM(e.total) as total')
      )
      ->whereYear('e.fecha_hora', $anio)
      ->groupBy(
        DB::raw('MONTH(e.fecha_hora)'),
        DB::raw('YEAR(e.fecha_hora)')
      )
      ->get();

    //   $ingresos = DB::connection('mysql2')
    // ->table('ingresos as i')
    //   ->select(
    //     DB::raw('MONTH(i.fecha_hora) as mes'),
    //     DB::raw('YEAR(i.fecha_hora) as anio'),
    //     DB::raw('SUM(i.total) as total')
    //   )
    //   ->whereYear('i.fecha_hora', $anio)
    //   ->groupBy(
    //     DB::raw('MONTH(i.fecha_hora)'),
    //     DB::raw('YEAR(i.fecha_hora)')
    //   )
    //   ->get();

    // $egresos = DB::connection('mysql2')
    //   ->table('egresos as e')
    //   ->select(
    //     DB::raw('MONTH(e.fecha_hora) as mes'),
    //     DB::raw('YEAR(e.fecha_hora) as anio'),
    //     DB::raw('SUM(e.total) as total')
    //   )
    //   ->whereYear('e.fecha_hora', $anio)
    //   ->groupBy(
    //     DB::raw('MONTH(e.fecha_hora)'),
    //     DB::raw('YEAR(e.fecha_hora)')
    //   )
    //   ->get();

    return ['ingresos'=>$ingresos, 'egresos'=>$egresos, 'anio'=>$anio];
  }
}
