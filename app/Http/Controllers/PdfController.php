<?php
namespace App\Http\Controllers;
use App\Models\FacturaVenta;

class PdfController extends Controller {
    public function factura($id) {
        $factura = FacturaVenta::with(['cliente', 'detalles.item'])->findOrFail($id);
        return view('reportes.factura_pdf', compact('factura'));
    }
}