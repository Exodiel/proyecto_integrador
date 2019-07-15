<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $buscar = $request->buscar;
    $criterio = $request->criterio;

    if ($buscar == '') {
      $usuarios = User::orderBy('id', 'desc')->paginate(10);
    } else {
      $usuarios = User::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate(10);
    }

    return [
      'pagination' => [
        'total'         => $usuarios->total(),
        'current_page'  => $usuarios->currentPage(),
        'per_page'      => $usuarios->perPage(),
        'last_page'     => $usuarios->lastPage(),
        'from'          => $usuarios->firstItem(),
        'to'            => $usuarios->lastItem()
      ],
      'users' => $usuarios
    ];
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $user = new User();
    $user->nombre = $request->nombre;
    $user->tipo_documento = $request->tipo_documento;
    $user->num_documento = $request->num_documento;
    $user->direccion = $request->direccion;
    $user->telefono = $request->telefono;
    $user->email = $request->email;
    $user->usuario = $request->usuario;
    $user->password = bcrypt($request->password);
    $user->condicion = '1';
    $user->save();
  }

  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $user = User::findOrFail($request->id);
    $user->nombre = $request->nombre;
    $user->tipo_documento = $request->tipo_documento;
    $user->num_documento = $request->num_documento;
    $user->direccion = $request->direccion;
    $user->telefono = $request->telefono;
    $user->email = $request->email;
    $user->usuario = $request->usuario;
    $user->password = bcrypt($request->password);
    $user->condicion = '1';
    $user->save();
  }

  public function desactivar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $user = User::findOrFail($request->id);
    $user->condicion = '0';
    $user->save();
  }

  public function activar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $user = User::findOrFail($request->id);
    $user->condicion = '1';
    $user->save();
  }
}
