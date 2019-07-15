<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['guest']], function(){
  Route::get('/', 'Auth\LoginController@showLoginForm');
  Route::post('/login', 'Auth\LoginController@login')->name('login');
});


Route::group(['middleware' => ['auth']], function () {

  Route::post('/logout', 'Auth\loginController@logout')->name('logout');

  Route::get('/dashboard', 'DashboardController');

  Route::post('/notification/get', 'NotificationController@get');

  Route::get('/main', function () {
    return view('contenido/contenido');
  })->name('main');

  Route::group(['middleware' => ['Administrador']], function () {
    //categorias
    //proveedores
    //items
    //reporte ingresos y egresos
    //ingresos y egresos
    //transacciones - kardex

    Route::get('/categoria', 'CategoriaController@index');
    Route::post('/categoria/registrar', 'CategoriaController@store');
    Route::put('/categoria/actualizar', 'CategoriaController@update');
    Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
    Route::put('/categoria/activar', 'CategoriaController@activar');
    Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

    Route::get('/item', 'ItemController@index');
    Route::post('/item/registrar', 'ItemController@store');
    Route::put('/item/actualizar', 'ItemController@update');
    Route::put('/item/desactivar', 'ItemController@desactivar');
    Route::put('/item/activar', 'ItemController@activar');
    Route::get('/item/buscarItem', 'ItemController@buscarItem');
    Route::get('/item/listarItem', 'ItemController@listarItem');
    Route::get('/item/listarPdf/{id}', 'itemController@listarPdf')->name('items_pdf');

    Route::get('/proveedor', 'ProveedorController@index');
    Route::post('/proveedor/registrar', 'ProveedorController@store');
    Route::put('/proveedor/actualizar', 'ProveedorController@update');
    Route::put('/proveedor/desactivar', 'ProveedorController@desactivar');
    Route::put('/proveedor/activar', 'ProveedorController@activar');
    Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

    Route::get('/user', 'UserController@index');
    Route::post('/user/registrar', 'UserController@store');
    Route::put('/user/actualizar', 'UserController@update');
    Route::put('/user/desactivar', 'UserController@desactivar');
    Route::put('/user/activar', 'UserController@activar');

    Route::get('/ingreso', 'IngresoController@index');
    Route::post('/ingreso/registrar', 'IngresoController@store');
    Route::put('/ingreso/actualizar', 'IngresoController@update');
    Route::delete('/ingreso/eliminar/{id}', 'IngresoController@destroy');
    Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
    Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');

    Route::get('/egreso', 'EgresoController@index');
    Route::post('/egreso/registrar', 'EgresoController@store');
    Route::put('/egreso/actualizar', 'EgresoController@update');
    Route::delete('/egreso/eliminar/{id}', 'EgresoController@destroy');
    Route::get('/egreso/obtenerCabecera', 'EgresoController@obtenerCabecera');
    Route::get('/egreso/obtenerDetalles', 'EgresoController@obtenerDetalles');
  });
});

