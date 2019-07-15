<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
  public function index(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $buscar = $request->buscar;
    $criterio = $request->criterio;

    if ($buscar == '') {
      $items = Item::join('categorias', 'items.idcategoria', '=', 'categorias.id')
        ->select('items.id', 'items.idcategoria', 'items.codigo', 'items.nombre', 'categorias.nombre as nombre_categoria', 'items.precio_compra', 'items.utilidad', 'items.precio_venta', 'items.stock', 'items.descripcion', 'items.condicion')
        ->orderBy('items.id', 'desc')->paginate(10);
    } else {
      $items = Item::join('categorias', 'items.idcategoria', '=', 'categorias.id')
        ->select('items.id', 'items.idcategoria', 'items.codigo', 'items.nombre', 'categorias.nombre as nombre_categoria', 'items.precio_compra', 'items.utilidad', 'items.precio_venta', 'items.stock', 'items.descripcion', 'items.condicion')
        ->where('items.' . $criterio, 'like', '%' . $buscar . '%')
        ->orderBy('items.id', 'desc')->paginate(10);
    }


    return [
      'pagination' => [
        'total'         => $items->total(),
        'current_page'  => $items->currentPage(),
        'per_page'      => $items->perPage(),
        'last_page'     => $items->lastPage(),
        'from'          => $items->firstItem(),
        'to'            => $items->lastItem()
      ],
      'items' => $items
    ];
  }

  public function listarItem(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $buscar = $request->buscar;
    $criterio = $request->criterio;

    if ($buscar == '') {
      $items = Item::join('categorias', 'items.idcategoria', '=', 'categorias.id')
        ->select('items.id', 'items.idcategoria', 'items.codigo', 'items.nombre', 'categorias.nombre as nombre_categoria', 'items.precio_compra', 'items.utilidad', 'items.precio_venta', 'items.stock', 'items.descripcion', 'items.condicion')
        ->orderBy('items.id', 'desc')->paginate(10);
    } else {
      $items = Item::join('categorias', 'items.idcategoria', '=', 'categorias.id')
        ->select('items.id', 'items.idcategoria', 'items.codigo', 'items.nombre', 'categorias.nombre as nombre_categoria', 'items.precio_compra', 'items.utilidad', 'items.precio_venta', 'items.stock', 'items.descripcion', 'items.condicion')
        ->where('items.' . $criterio, 'like', '%' . $buscar . '%')
        ->orderBy('items.id', 'desc')->paginate(10);
    }


    return ['items' => $items];
  }

  public function listarPdf(Request $request, $id)
  {

    $transacciones = Item::join('transacciones', 'transacciones.iditem', '=', 'items.id')
      ->select('transacciones.detalle', 'transacciones.fecha_hora', 'transacciones.entrada_cantidad', 'transacciones.entrada_precio_unitario', 'transacciones.entrada_valor_total', 'transacciones.salida_cantidad', 'transacciones.salida_precio_unitario', 'transacciones.salida_valor_total', 'transacciones.exis_cantidad', 'transacciones.exis_precio_unitario', 'transacciones.exis_valor_total')
      ->where('items.id', '=', $id)
      ->orderBy('transacciones.fecha_hora', 'asc')->get();

    $item = Item::select('codigo')->where('id', $id)->get();


    $pdf = \PDF::loadView('pdf.kardexpdf', ['transacciones' => $transacciones]);
    return $pdf->setPaper('a4', 'landscape')
      ->download('kardex-' . $id . '-' . $item[0]->codigo . '.pdf');
  }

  public function buscarItem(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $filtro = $request->filtro;
    $items = Item::where('codigo', '=', $filtro)
      ->select('id', 'nombre')->orderBy('nombre', 'asc')->take(1)->get();

    return ['items' => $items];
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $item = new Item();
    $item->idcategoria = $request->idcategoria;
    $item->codigo = $request->codigo;
    $item->nombre = $request->nombre;
    $item->precio_compra = $request->precio_compra;
    $item->utilidad = $request->utilidad;
    $item->precio_venta = $request->precio_venta;
    $item->stock = $request->stock;
    $item->stock_ant = $request->stock;
    $item->descripcion = $request->descripcion;
    $item->condicion = '1';
    $item->save();
  }

  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $item = Item::findOrFail($request->id);
    $item->idcategoria = $request->idcategoria;
    $item->codigo = $request->codigo;
    $item->nombre = $request->nombre;
    $item->precio_compra = $request->precio_compra;
    $item->utilidad = $request->utilidad;
    $item->precio_venta = $request->precio_venta;
    $item->stock = $request->stock;
    $item->stock_ant = $request->stock;
    $item->descripcion = $request->descripcion;
    $item->condicion = '1';
    $item->save();
  }

  public function desactivar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $item = Item::findOrFail($request->id);
    $item->condicion = '0';
    $item->save();
  }

  public function activar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $item = Item::findOrFail($request->id);
    $item->condicion = '1';
    $item->save();
  }
}
