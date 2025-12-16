<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- CONTROLADORES DE AUTENTICACIÓN ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FacturaCompraController;
use App\Http\Controllers\NotaDebitoCompraController;

// --- DECLARACIÓN DE CONTROLADORES (CRÍTICO) ---
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FacturaVentaController;
use App\Http\Controllers\OrdenEntregaController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RutaRepartoController;
use App\Http\Controllers\RepartidorController;
use App\Http\Controllers\ListaPrecioController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\AjusteController;
use App\Http\Controllers\MermaController;
use App\Http\Controllers\BodegaController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\ReporteController;

/*
|--------------------------------------------------------------------------
| Rutas Web de la Aplicación (Módulos ERP)
|--------------------------------------------------------------------------
*/

// --- 1. AUTENTICACIÓN ---
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// --- GRUPO DE RUTAS PROTEGIDAS (REQUIERE LOGIN) ---
Route::middleware(['auth'])->group(function () {

    // --- 2. RUTAS BASE ---
    Route::get('/', function () {
        return redirect()->route('facturas.index');
    });


    // --- 3. MÓDULO DE VENTAS Y CLIENTES ---
    Route::resource('clientes', ClienteController::class);
    Route::resource('sucursales', SucursalController::class);
    Route::resource('facturas', FacturaVentaController::class);
    Route::resource('listas_precios', ListaPrecioController::class);

    // Rutas para agregar sucursales desde el cliente
    Route::post('clientes/{cliente_id}/sucursales', [ClienteController::class, 'storeSucursal'])->name('clientes.sucursales.store');
    Route::delete('clientes/sucursales/{id}', [ClienteController::class, 'destroySucursal'])->name('clientes.sucursales.destroy');

    // Rutas específicas de Listas de Precios
    Route::get('listas_precios/{lista_precio}/asignar_clientes', [ListaPrecioController::class, 'asignarClientes'])->name('listas_precios.asignarClientes');
    Route::post('listas_precios/{lista_precio}/asignar_clientes', [ListaPrecioController::class, 'storeAsignacionClientes'])->name('listas_precios.storeAsignacionClientes');

    // Rutas específicas de Facturas
    Route::get('facturas/{id}/pdf', [FacturaVentaController::class, 'pdf'])->name('facturas.pdf');
    Route::get('facturas/api/data/{cliente_id}', [FacturaVentaController::class, 'getData'])->name('facturas.api.getData');


    // --- 4. MÓDULO DE ÓRDENES DE ENTREGA (PREFACTURAS) ---
    Route::resource('entregas', OrdenEntregaController::class);

    // Rutas específicas de Órdenes de Entrega
    Route::get('entregas/{id}/convertir', [OrdenEntregaController::class, 'convertir'])->name('entregas.convertir');
    Route::get('entregas/{id}/pdf', [OrdenEntregaController::class, 'pdf'])->name('entregas.pdf');

    // --- MÓDULO DE COTIZACIONES ---
    Route::resource('cotizaciones', CotizacionController::class);
    Route::get('cotizaciones/{id}/pdf', [CotizacionController::class, 'pdf'])->name('cotizaciones.pdf');


    // --- 5. MÓDULO DE COMPRAS Y PROVEEDORES ---
    Route::get('/compras', function () {
        return redirect()->route('ordenes_compra.index');
    });
    Route::resource('ordenes_compra', OrdenCompraController::class);
    Route::resource('proveedores', ProveedorController::class);

    // Facturas de Compra (Cuentas por Pagar)
    Route::resource('facturas_compra', FacturaCompraController::class);
    Route::get('facturas_compra/{id}/pdf', [FacturaCompraController::class, 'pdf'])->name('facturas_compra.pdf');
    Route::get('ordenes_compra/{id}/pdf', [OrdenCompraController::class, 'pdf'])->name('ordenes_compra.pdf');
    Route::get('ordenes_compra/{id}/convertir', [FacturaCompraController::class, 'createFromOrden'])->name('ordenes_compra.convertir');

    // Notas de Débito
    Route::resource('notas_debito', NotaDebitoCompraController::class);


    // --- 6. MÓDULO DE INVENTARIO ---
    Route::resource('items', ItemController::class);
    Route::resource('bodegas', BodegaController::class);
    Route::resource('ajustes', AjusteController::class);
    Route::resource('mermas', MermaController::class);

    // Rutas específicas de Inventario
    Route::get('items/{id}/precios', [ItemController::class, 'precios'])->name('items.precios');
    Route::post('items/{id}/precios', [ItemController::class, 'storePrecios'])->name('items.storePrecios');
    Route::get('kardex', [KardexController::class, 'index'])->name('kardex.index');


    // --- 7. MÓDULO DE REPARTO Y LOGÍSTICA ---
    Route::resource('rutas_reparto', RutaRepartoController::class);
    Route::resource('repartidores', RepartidorController::class);
    Route::get('repartidor/dashboard', [RepartidorController::class, 'dashboard'])->name('repartidor.dashboard');
    Route::put('repartidor/check/{id}', [RepartidorController::class, 'check'])->name('repartidor.check');
    Route::get('repartidor/itinerario-pdf', [RepartidorController::class, 'itinerarioPdf'])->name('repartidor.itinerario.pdf');


    // --- 8. MÓDULO DE REPORTES ---
    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('rentabilidad', [ReporteController::class, 'rentabilidad'])->name('reportes.rentabilidad');
    });


    // ==========================================================
    // RUTAS API PARA LIVE-SEARCH Y DATOS (CRÍTICAS)
    // ==========================================================
    Route::prefix('api')->group(function () {

        // API GENERAL DE ITEMS (Usada por Facturas y Compras)
        Route::get('ventas/search-items', [FacturaVentaController::class, 'searchItems'])->name('ventas.api.searchItems');

        // API ESPECÍFICA DE ÓRDENES DE ENTREGA (LiveSearch en Prefacturas)
        Route::get('entregas/search-items', [OrdenEntregaController::class, 'searchItems'])->name('entregas.api.searchItems');
        Route::get('entregas/data/{cliente_id}', [OrdenEntregaController::class, 'getData'])->name('entregas.api.getData');

        // API DE COMPRAS
        Route::get('compras/search-items', [OrdenCompraController::class, 'searchItems'])->name('compras.api.searchItems');
    });

});