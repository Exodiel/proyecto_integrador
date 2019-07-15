<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Egreso;
use App\DetalleEgreso;
use App\Item;
use App\Transaccion;
use App\User;
use App\Notifications\NotifyAdmin;

class EgresoController extends Controller
{
  public function index(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $buscar = $request->buscar;
    $criterio = $request->criterio;

    if ($buscar == '') {
      $egresos = Egreso::join('users', 'users.id', '=', 'egresos.idusuario')
      ->select('egresos.id', 'egresos.descripcion','egresos.tipo_comprobante', 'egresos.num_comprobante', 'egresos.fecha_hora', 'egresos.descuento','egresos.iva', 'egresos.total', 'users.usuario')
      ->orderBy('egresos.id', 'desc')->paginate(10);
    } else {
      $egresos = Egreso::join('users', 'users.id', '=', 'egresos.idusuario')
      ->select('egresos.id', 'egresos.tipo_comprobante', 'egresos.num_comprobante', 'egresos.fecha_hora', 'egresos.iva', 'egresos.total', 'users.usuario')
      ->where('egresos.'.$criterio, 'like', '%' . $buscar . '%')
      ->orderBy('egresos.id', 'desc')->paginate(10);
    }

    return [
      'pagination' => [
        'total'         => $egresos->total(),
        'current_page'  => $egresos->currentPage(),
        'per_page'      => $egresos->perPage(),
        'last_page'     => $egresos->lastPage(),
        'from'          => $egresos->firstItem(),
        'to'            => $egresos->lastItem()
      ],
      'egresos' => $egresos
    ];
  }

  public function obtenerCabecera(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $id = $request->id;

    $egreso = Egreso::join('users', 'users.id', '=', 'egresos.idusuario')
    ->select('egresos.id', 'egresos.descripcion','egresos.tipo_comprobante', 'egresos.num_comprobante','egresos.fecha_hora', 'egresos.descuento','egresos.iva', 'egresos.total', 'users.usuario')
    ->where('egresos.id', '=', $id)
    ->orderBy('egresos.id', 'desc')->take(1)->get();

    return ['egreso' => $egreso];
  }

  public function obtenerDetalles(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $id = $request->id;

    $detalles = DetalleEgreso::join('items', 'detalle_egresos.iditem','=', 'items.id')
    ->select('items.id as iditem', 'detalle_egresos.cantidad', 'detalle_egresos.precio', 'detalle_egresos.descuento', 'items.nombre as item', 'items.stock')
    ->where('detalle_egresos.idegreso', '=', $id)
    ->orderBy('detalle_egresos.id', 'desc')->get();

    return ['detalles' => $detalles];
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    try {
      DB::beginTransaction();

      $mytime = Carbon::now('America/Guayaquil');
      $now = $mytime->toDateTimeString();

      $egreso = new Egreso();
      $egreso->idusuario = \Auth::user()->id;
      $egreso->descripcion = $request->descripcion;
      $egreso->tipo_comprobante = $request->tipo_comprobante;
      $egreso->num_comprobante = $request->num_comprobante;
      $egreso->fecha_hora = $now;
      $egreso->descuento = $request->descuento;
      $egreso->iva = $request->iva;
      $egreso->total = $request->total;
      $egreso->save();

      $detalles = $request->data; //Array de los detalles

      foreach ($detalles as $ep => $det)
      {
        $item = Item::findOrFail($det['iditem']);

        $tranAnt = Transaccion::where('iditem', '=', $det['iditem'])->orderBy('id', 'desc')->take(1)->get();
        $t = Transaccion::where([
          ['iditem', '=', $det['iditem']],
          ['detalle', '=', 'ENTRADA']
        ])->orderBy('id', 'desc')
        ->take(1)
        ->get();

        $tran = new Transaccion();
        $tr = new Transaccion();

        if($request->descripcion === 'DEVOLUCION_S') {
          foreach ($t as $key => $value) {
            $tr->iditem = $value['iditem'];
            $tr->fecha_hora = $now;
            $tr->detalle = $request->descripcion;
            $tr->salida_cantidad = $det['cantidad'];
            $tr->salida_precio_unitario = $value['entrada_precio_unitario'];
            $tr->salida_valor_total = $tr->salida_cantidad * $tr->salida_precio_unitario;
            $tr->exis_cantidad = $value->exis_cantidad -  $tr->salida_cantidad;
            $tr->exis_valor_total = $value->exis_valor_total - $tr->salida_valor_total;
            $tr->exis_precio_unitario = $tr->exis_valor_total / $tr->exis_cantidad;
            $tr->save();
          }
        }else {

          foreach ($tranAnt as $k => $val) {
            $tran->iditem = $val['iditem'];
            $tran->fecha_hora = $now;
            $tran->detalle = $request->descripcion;
            $tran->salida_cantidad = $det['cantidad'];
            $tran->salida_precio_unitario = $det['precioC'];
            $tran->salida_valor_total = $tran->salida_cantidad * $tran->salida_precio_unitario;
            $tran->exis_cantidad = $val->exis_cantidad -  $tran->salida_cantidad;
            $tran->exis_valor_total = $val->exis_valor_total - $tran->entrada_valor_total;
            $tran->exis_precio_unitario = $tran->exis_valor_total / $tran->exis_cantidad;
            $tran->save();
          }
        }

        $item->stock_ant = $det['stock'];
        $item->precio_compra = $tran->exis_precio_unitario;
        $item->save();
        $detalle = new DetalleEgreso();
        $detalle->idegreso = $egreso->id;
        $detalle->iditem = $det['iditem'];
        $detalle->cantidad = $det['cantidad'];
        $detalle->descuento = $det['descuento'];
        $detalle->precio = $det['precio'];
        $detalle->save();
      }

      $fechaActual = date('Y-m-d');
      $numEgresos = DB::table('egresos')->whereDate('created_at',$fechaActual)->count();
      $numIngresos = DB::table('ingresos')->whereDate('created_at',$fechaActual)->count();

      $arrDatos = [
        'egresos' => [
          'numero' => $numEgresos,
          'msj' => 'Egresos'
        ],
        'ingresos' => [
          'numero' => $numIngresos,
          'msj' => 'Ingresos'
        ]
      ];
      $allUsers = User::all();

      foreach ($allUsers as $notificar) {
        User::findOrFail($notificar->id)->notify(new NotifyAdmin($arrDatos));
      }

      DB::commit();
    }catch(Exception $e) {
      DB::rollBack();
    }
  }

  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    try {
      DB::beginTransaction();

      $id = $request->id;
      $mytime = Carbon::now('America/Guayaquil');
      $now = $mytime->toDateTimeString();
      $egreso = Egreso::findOrFail($id);
      $egreso->idusuario = \Auth::user()->id;
      $egreso->descripcion = $request->descripcion;
      $egreso->tipo_comprobante = $request->tipo_comprobante;
      $egreso->num_comprobante = $request->num_comprobante;
      $egreso->fecha_hora = $now;
      $egreso->descuento = $request->descuento;
      $egreso->iva = $request->iva;
      $egreso->total = $request->total;
      $egreso->save();

      $detalles = $request->data; //Array detalles
      $detDeleted = $request->detDeleted; //Array detalles eliminados

      $d = DetalleEgreso::where('idegreso', '=', $id)->get();

      foreach ($detalles as $ep => $det)
      {
        $detalle = DetalleEgreso::updateOrCreate(
          ['idegreso' => $id, 'iditem' => $det['iditem']],
          [
            'cantidad' => $det['cantidad'],
            'descuento' => $det['descuento'],
            'precio' => $det['precio']
          ]
        );
        $item = Item::findOrFail($det['iditem']);
        $item->stock = $item->stock_ant - $det['cantidad'];
        $item->save();

        foreach ($d as $key => $value) {
          foreach ($detDeleted as $k => $val) {
            DetalleEgreso::where([
              ['idegreso', '=', $value['idegreso']],
              ['iditem', '=', $val['iditem']]
            ])->delete();
          }
        }

      }

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
    }
  }

  public function destroy(Request $request, $id)
  {
    if(!$request->ajax()) return redirect('/');
    DetalleEgreso::where('idegreso', '=', $id)->delete();
    Egreso::destroy($id);
  }


}
