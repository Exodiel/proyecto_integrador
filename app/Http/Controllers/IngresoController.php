<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Ingreso;
use App\DetalleIngreso;
use App\Item;
use App\Transaccion;
use App\User;
use App\Notifications\NotifyAdmin;

class IngresoController extends Controller
{
  public function index(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $buscar = $request->buscar;
    $criterio = $request->criterio;

    if ($buscar == '') {
      $ingresos = Ingreso::join('proveedores', 'ingresos.idproveedor', '=', 'proveedores.id')
      ->join('users', 'users.id', '=', 'ingresos.idusuario')
      ->select('ingresos.id', 'ingresos.descripcion','ingresos.tipo_comprobante', 'ingresos.num_comprobante', 'ingresos.fecha_hora', 'ingresos.descuento','ingresos.iva', 'ingresos.total', 'proveedores.nombre as proveedor', 'users.usuario')
      ->orderBy('id', 'desc')->paginate(10);
    } else {
      $ingresos = Ingreso::join('proveedores', 'ingresos.idproveedor', '=', 'proveedores.id')
      ->join('users', 'users.id', '=', 'ingresos.idusuario')
      ->select('ingresos.id', 'ingresos.descripcion','ingresos.tipo_comprobante', 'ingresos.num_comprobante', 'ingresos.fecha_hora', 'ingresos.iva', 'ingresos.total', 'proveedores.nombre as proveedor', 'users.usuario')
      ->where('ingresos.'.$criterio, 'like', '%' . $buscar . '%')
      ->orderBy('ingresos.id', 'desc')->paginate(10);
    }

    return [
      'pagination' => [
        'total'         => $ingresos->total(),
        'current_page'  => $ingresos->currentPage(),
        'per_page'      => $ingresos->perPage(),
        'last_page'     => $ingresos->lastPage(),
        'from'          => $ingresos->firstItem(),
        'to'            => $ingresos->lastItem()
      ],
      'ingresos' => $ingresos
    ];
  }

  public function obtenerCabecera(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $id = $request->id;

    $ingreso = Ingreso::join('proveedores', 'ingresos.idproveedor','=', 'proveedores.id')
    ->join('users', 'users.id', '=', 'ingresos.idusuario')
    ->select('ingresos.id', 'ingresos.descripcion', 'ingresos.tipo_comprobante', 'ingresos.num_comprobante','ingresos.fecha_hora', 'ingresos.descuento','ingresos.iva', 'ingresos.total', 'proveedores.id as idproveedor','proveedores.nombre as proveedor','users.usuario')
    ->where('ingresos.id', '=', $id)
    ->orderBy('ingresos.id', 'desc')->take(1)->get();

    return ['ingreso' => $ingreso];
  }

  public function obtenerDetalles(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $id = $request->id;

    $detalles = DetalleIngreso::join('items', 'detalle_ingresos.iditem','=', 'items.id')
    ->select('items.id as iditem', 'detalle_ingresos.cantidad', 'detalle_ingresos.precio', 'detalle_ingresos.descuento', 'items.nombre as item', 'items.stock')
    ->where('detalle_ingresos.idingreso', '=', $id)
    ->orderBy('detalle_ingresos.id', 'desc')->get();

    return ['detalles' => $detalles];
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    try {
      DB::beginTransaction();

      $mytime = Carbon::now('America/Guayaquil');

      $now = $mytime->toDateTimeString();

      $ingreso = new Ingreso();
      $ingreso->idproveedor = $request->idproveedor;
      $ingreso->idusuario = \Auth::user()->id;
      $ingreso->descripcion = $request->descripcion;
      $ingreso->tipo_comprobante = $request->tipo_comprobante;
      $ingreso->num_comprobante = $request->num_comprobante;
      $ingreso->fecha_hora = $now;
      $ingreso->descuento = $request->descuento;
      $ingreso->iva = $request->iva;
      $ingreso->total = $request->total;
      $ingreso->save();

      $detalles = $request->data; //Array de los detalles

      foreach ($detalles as $ep => $det)
      {
        $item = Item::findOrFail($det['iditem']);

        $t = Transaccion::where([
          ['iditem', '=', $det['iditem']],
          ['detalle', '=', 'SALIDA']
        ])->orderBy('id', 'desc')
        ->take(1)
        ->get();

        $tranAnt = Transaccion::where('iditem', '=', $det['iditem'])->orderBy('id', 'desc')->take(1)->get();

        $tran = new Transaccion();
        $tr = new Transaccion();

        if($request->descripcion === 'DEVOLUCION_E') {


          foreach ($t as $key => $value) {
            $tr->iditem = $value['iditem'];
            $tr->fecha_hora = $now;
            $tr->detalle = $request->descripcion;
            $tr->entrada_cantidad = $det['cantidad'];
            $tr->entrada_precio_unitario = $value['salida_precio_unitario'];
            $tr->entrada_valor_total = $tr->entrada_cantidad * $tr->entrada_precio_unitario;
            $tr->exis_cantidad = $value->exis_cantidad +  $tr->entrada_cantidad;
            $tr->exis_valor_total = $value->exis_valor_total + $tr->entrada_valor_total;
            $tr->exis_precio_unitario = $tr->exis_valor_total / $tr->exis_cantidad;
            $tr->save();
          }

        }else {

          foreach ($tranAnt as $k => $val) {
            $tran->iditem = $val['iditem'];
            $tran->fecha_hora = $now;
            $tran->detalle = $request->descripcion;
            $tran->entrada_cantidad = $det['cantidad'];
            $tran->entrada_precio_unitario = $det['precio'];
            $tran->entrada_valor_total = $tran->entrada_cantidad * $tran->entrada_precio_unitario;
            $tran->exis_cantidad = $val->exis_cantidad +  $tran->entrada_cantidad;
            $tran->exis_valor_total = $val->exis_valor_total + $tran->entrada_valor_total;
            $tran->exis_precio_unitario = $tran->exis_valor_total / $tran->exis_cantidad;
            $tran->save();
          }
        }


        $item->stock_ant = $det['stock'];
        $item->precio_compra = $tran->exis_precio_unitario;
        $item->save();
        $detalle = new DetalleIngreso();
        $detalle->idingreso = $ingreso->id;
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

      $ingreso = Ingreso::findOrFail($id);
      $ingreso->idproveedor = $request->idproveedor;
      $ingreso->idusuario = \Auth::user()->id;
      $ingreso->descripcion = $request->descripcion;
      $ingreso->tipo_comprobante = $request->tipo_comprobante;
      $ingreso->num_comprobante = $request->num_comprobante;
      $ingreso->fecha_hora = $now;
      $ingreso->descuento = $request->descuento;
      $ingreso->iva = $request->iva;
      $ingreso->total = $request->total;
      $ingreso->save();

      $detalles = $request->data; //Array de los detalles
      $detDeleted = $request->dataDeleted; //Array de los detalles Eliminados
      $d = DetalleIngreso::where('idingreso', '=', $id)->get();


      foreach ($detalles as $ep => $det)
      {
        $detalle = DetalleIngreso::updateOrCreate(
          ['idingreso' => $id, 'iditem' => $det['iditem']],
          [
            'cantidad' => $det['cantidad'],
            'descuento' => $det['descuento'],
            'precio' => $det['precio']
          ]
        );
        $item = Item::findOrFail($det['iditem']);
        $item->stock = $item->stock_ant + $det['cantidad'];
        $item->save();

      }

      foreach ($d as $key => $value) {
        foreach ($detDeleted as $k => $val) {
          DetalleIngreso::where([
            ['idingreso', '=', $value['idingreso']],
            ['iditem', '=', $val['iditem']]
          ])->delete();
        }
      }

      DB::commit();
    }catch(Exception $e) {
      DB::rollBack();
    }
  }

  public function destroy(Request $request, $id)
  {
    if(!$request->ajax()) return redirect('/');
    DetalleIngreso::where('idingreso', '=', $id)->delete();
    Ingreso::destroy($id);
  }

}
