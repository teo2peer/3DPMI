<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/login/redirect',  [App\Http\Controllers\Auth\LoginController::class, 'redirectToProvider']);
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleProviderCallback']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);


//Errors Routes
Route::get('/errorNotAllowed', [App\Http\Controllers\ErrorController::class, 'NotAllowed']);
Route::get('/checkIsAllowed', [App\Http\Controllers\ErrorController::class, 'CheckIsAllowed']);

Route::get('/smartLogin', function () {
    return view('auth.smartLogin');
});

Route::post('/login/smartLogin', [App\Http\Controllers\DashboardController::class, 'smartLogin']);



Route::prefix('dashboard')->middleware('IsLoged')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    
   
    
    Route::get('/users', [App\Http\Controllers\DashboardController::class, 'manageUsers'])->middleware("IsAdmin")->name('dashboard.users'); 
    Route::get('/users/{id}', [App\Http\Controllers\DashboardController::class, 'userDetails'])->middleware("IsAdmin")->name('dashboard.usersDetails'); 
    Route::get('/user/impresiones', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'userImpresiones'])->name('dashboard.user.impresiones'); 
    
    Route::get('/users/smartLogin', function () {
        return view('dashboard.user.smartLogin');
    });
    Route::post('/users/smartLogin/add', [App\Http\Controllers\DashboardController::class, 'addTarjeta'])->name('dashboard.addTarjeta');


    
    Route::get('/filamentos', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'filamentos'])->name('dashboard.filamentos'); 
    Route::post('/filamentos/add', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'filamentos_add'])->name('dashboard.filamentos.add'); 
    Route::get('/filamentos/delete/{id}', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'filamentos_delete'])->name('dashboard.filamentos.delete');
    
    
    Route::get('/impresoras', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresoras'])->name('dashboard.impresoras'); 
    Route::post('/impresoras/add', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresoras_add'])->name('dashboard.impresoras.add'); 
    Route::get('/impresoras/delete/{id}', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresoras_delete'])->name('dashboard.impresoras.delete'); 
    Route::get('/impresoras/alter/{id}', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresoras_alter'])->name('dashboard.impresoras.alter'); 
    
    
    Route::get('/user/gcodes', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'userGcodes'])->name('dashboard.user.gcodes'); 
    Route::get('/user/gcodes/delete/{id}', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'deleteGcode'])->name('dashboard.user.gcodes.delete'); 
    
    
    Route::get('/impresiones', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresiones'])->name('dashboard.impresiones'); 


    Route::post('/impresion/crear', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresion_add'])->name('dashboard.impresion.add'); 
    Route::get('/impresion/start/{id}', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresion_start'])->name('dashboard.impresion.start'); 
    Route::get('/impresion/finish/{id}', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresion_finish'])->name('dashboard.impresion.finish'); 
    Route::get('/impresion/error/{id}', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresion_error'])->name('dashboard.impresion.error'); 
    Route::get('/impresion/delete/{id}', [App\Http\Controllers\Dashboard\ImpresionesController::class, 'impresion_delete'])->name('dashboard.impresion.delete'); 
    

    Route::get('/incidencias', [App\Http\Controllers\DashboardController::class, 'incidencias'])->name('dashboard.incidencias');
    

    Route::get('/zonas', [App\Http\Controllers\Dashboard\InventarioController::class, 'zonas'])->name('dashboard.zonas');
    Route::get('/zonas/imprimir', [App\Http\Controllers\Dashboard\InventarioController::class, 'zonas_imprimir'])->name('dashboard.zonas.imprimir');
    Route::post('/zonas/add', [App\Http\Controllers\Dashboard\InventarioController::class, 'zonas_add'])->name('dashboard.zonas.add');
    Route::get('/zonas/delete/{id}', [App\Http\Controllers\Dashboard\InventarioController::class, 'zonas_delete'])->name('dashboard.zonas.delete');
    

    Route::post('/zonas/subzonas/add', [App\Http\Controllers\Dashboard\InventarioController::class, 'subzonas_add'])->name('dashboard.zonas.subzonas.add');
    Route::get('/zonas/subzonas/get/{id}', [App\Http\Controllers\Dashboard\InventarioController::class, 'subzonas_by_zona'])->name('dashboard.zonas.subzonas.get');
    Route::get('/zonas/subzonas/get/barcodes/{id}', [App\Http\Controllers\Dashboard\InventarioController::class, 'subzonas_barcodes_by_zona'])->name('dashboard.zonas.subzonas.get');
    Route::get('/zonas/subzonas/delete/{id}', [App\Http\Controllers\Dashboard\InventarioController::class, 'subzonas_delete'])->name('dashboard.zonas.subzonas.delete');


    Route::get('/inventario', [App\Http\Controllers\Dashboard\InventarioController::class, 'inventario'])->name('dashboard.inventario');
    Route::post('/inventario/add', [App\Http\Controllers\Dashboard\InventarioController::class, 'inventario_Add'])->name('dashboard.inventario.add');
    Route::get('/inventario/delete/{id}', [App\Http\Controllers\Dashboard\InventarioController::class, 'inventario_delete'])->name('dashboard.inventario.delete');
    
    Route::get('/categorias', [App\Http\Controllers\Dashboard\InventarioController::class, 'categorias'])->name('dashboard.categorias');
    Route::post('/categorias/add', [App\Http\Controllers\Dashboard\InventarioController::class, 'categorias_add'])->name('dashboard.categorias.add');
    Route::get('/categorias/delete/{id}', [App\Http\Controllers\Dashboard\InventarioController::class, 'categorias_delete'])->name('dashboard.categorias.delete');

    Route::get('/buscador', function () {
        return view('dashboard.inventario.buscador');
    })->name('dashboard.buscador');
    Route::post('/buscador/get', [App\Http\Controllers\Dashboard\InventarioController::class, 'buscador_get'])->name('dashboard.buscador.get');
});

Route::get('/home', function() {
    return redirect('/checkIsAllowed');
})->name('home');