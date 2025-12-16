<?php
namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\ListaPrecio;
use App\Models\ListaPrecioItem;
use App\Models\Lote;
use Illuminate\Http\Request;
use DB;

class ItemController extends Controller {
    
    // --- METODO INDEX (RESTABLECIDO) ---
    public function index() {
        $items = Item::latest()->paginate(10);
        return view('inventario.items.index', compact('items'));
    }

    // --- CRUD ESTÁNDAR ---
    public function create() {
        return view('inventario.items.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required',
            'costo_unitario' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'tasa_itbms' => 'required|numeric|min:0'
        ]);
        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Producto creado.');
    }

    public function edit($id) {
        $item = Item::findOrFail($id);
        return view('inventario.items.edit', compact('item'));
    }
    
    public function update(Request $request, $id) {
        $request->validate([
            'nombre' => 'required',
            'costo_unitario' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'tasa_itbms' => 'required|numeric|min:0'
        ]);

        $item = Item::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Producto actualizado.');
    }

    public function destroy($id) {
        Item::destroy($id);
        return back()->with('success', 'Producto eliminado.');
    }

    // --- GESTIÓN DE PRECIOS POR LISTA ---
    public function precios($id) {
        $item = Item::findOrFail($id);
        $listas = ListaPrecio::where('activa', true)->get();
        $precios = ListaPrecioItem::where('item_id', $id)->pluck('precio', 'lista_precio_id')->toArray();

        return view('inventario.items.precios', compact('item', 'listas', 'precios'));
    }

    public function storePrecios(Request $request, $id) {
        $item = Item::findOrFail($id);
        $preciosData = $request->input('precios', []);

        DB::transaction(function() use ($item, $preciosData) {
            foreach($preciosData as $listaId => $precio) {
                $precio = (float)$precio;
                if ($precio > 0) {
                    ListaPrecioItem::updateOrCreate(
                        ['item_id' => $item->id, 'lista_precio_id' => $listaId],
                        ['precio' => $precio]
                    );
                } else {
                    ListaPrecioItem::where('item_id', $item->id)->where('lista_precio_id', $listaId)->delete();
                }
            }
        });

        return redirect()->route('items.index')->with('success', 'Precios de lista actualizados para ' . $item->nombre);
    }
    
    // --- API DE BÚSQUEDA (Para LiveSearch) ---
    public function search(Request $request) {
        $term = $request->q;
        $listaId = $request->lista_precio_id; 

        $items = Item::where('activo', true)
                     ->where('stock', '>', 0)
                     ->where(function($q) use ($term) {
                         $q->where('nombre', 'LIKE', "%$term%")
                           ->orWhere('codigo', 'LIKE', "%$term%");
                     })
                     ->take(20)
                     ->get();

        $resultados = $items->map(function($item) use ($listaId) {
            $precioFinal = $item->precio_venta;

            if($listaId) {
                $precioLista = ListaPrecioItem::where('lista_precio_id', $listaId)
                                              ->where('item_id', $item->id)
                                              ->value('precio');
                if($precioLista) {
                    $precioFinal = $precioLista;
                }
            }

            return [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'codigo' => $item->codigo,
                'stock' => $item->stock,
                'precio' => $precioFinal,
                'tasa_itbms' => $item->tasa_itbms,
                'es_precio_especial' => ($precioFinal != $item->precio_venta)
            ];
        });

        return response()->json($resultados);
    }
}