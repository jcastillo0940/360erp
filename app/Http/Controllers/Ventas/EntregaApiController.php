<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntregaApiController extends Controller
{
    /**
     * Búsqueda de productos para LiveSearch
     * Ruta: /entregas/api/search-items
     */
    public function searchItems(Request $request)
    {
        try {
            $query = $request->input('q', '');
            $lista_precio_id = $request->input('lista_precio_id', null);
            
            Log::info('LiveSearch API llamada', [
                'query' => $query,
                'lista_precio_id' => $lista_precio_id
            ]);

            if (strlen($query) < 2) {
                return response()->json([]);
            }

            // Búsqueda base de items
            $items = DB::table('items')
                ->where('activo', true)
                ->where(function($q) use ($query) {
                    $q->where('nombre', 'ILIKE', '%' . $query . '%')
                      ->orWhere('codigo', 'ILIKE', '%' . $query . '%');
                })
                ->limit(20)
                ->get();

            $results = [];
            
            foreach ($items as $item) {
                // Obtener precio según lista o precio base
                $precio = $item->precio_venta;
                
                if ($lista_precio_id) {
                    $precioLista = DB::table('lista_precio_detalle')
                        ->where('lista_precio_id', $lista_precio_id)
                        ->where('item_id', $item->id)
                        ->first();
                    
                    if ($precioLista) {
                        $precio = $precioLista->precio;
                    }
                }
                
                // Obtener tasa ITBMS
                $tasa_itbms = 0;
                if ($item->impuesto_id) {
                    $impuesto = DB::table('impuestos')
                        ->where('id', $item->impuesto_id)
                        ->first();
                    
                    if ($impuesto) {
                        $tasa_itbms = $impuesto->tasa;
                    }
                }
                
                $results[] = [
                    'id' => $item->id,
                    'codigo' => $item->codigo,
                    'nombre' => $item->nombre,
                    'precio' => (float) $precio,
                    'stock' => (float) ($item->stock ?? 0),
                    'tasa_itbms' => (float) $tasa_itbms
                ];
            }
            
            Log::info('LiveSearch resultados', ['count' => count($results)]);
            
            return response()->json($results);
            
        } catch (\Exception $e) {
            Log::error('Error en LiveSearch API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error en búsqueda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener datos del cliente (sucursales y lista de precios)
     * Ruta: /entregas/api/data/{cliente_id}
     */
    public function getClientData($cliente_id)
    {
        try {
            Log::info('API Cliente Data llamada', ['cliente_id' => $cliente_id]);
            
            $cliente = DB::table('clientes')->where('id', $cliente_id)->first();
            
            if (!$cliente) {
                return response()->json(['error' => 'Cliente no encontrado'], 404);
            }
            
            $sucursales = DB::table('clientes_sucursales')
                ->where('cliente_id', $cliente_id)
                ->where('activo', true)
                ->get();
            
            return response()->json([
                'lista_precio_id' => $cliente->lista_precio_id,
                'sucursales' => $sucursales
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo datos del cliente', [
                'cliente_id' => $cliente_id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}