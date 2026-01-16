<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

//auth
Route::view('auth/noRole', 'auth.noRole')
    ->middleware(['auth', 'verified'])
    ->name('auth.noRole');

//Ventas
Route::view('ventas/index', 'ventas.index')
    ->middleware(['auth', 'verified'])
    ->name('ventas.index');

Route::middleware('auth')->group(function () { 
    Route::view('ventas/index', 'ventas.index')->name('ventas.index');
    Route::view('notaVenta/create', 'ventas.notaVenta.create')->name('notaVenta.create');
});

//Inventario
Route::middleware('auth')->group(function () { 
    Route::view('inventario/index', 'inventario.index')->name('inventario.index');
    Route::view('producto/create', 'inventario.producto.create')->name('producto.create');
    Route::view('producto/edit/{productoId}', 'inventario.producto.edit')->name('producto.edit');

});

//Estadisticas
Route::view('estadisticas/index', 'estadisticas.index')
    ->middleware(['auth', 'verified'])
    ->name('estadisticas.index');

require __DIR__.'/auth.php';
