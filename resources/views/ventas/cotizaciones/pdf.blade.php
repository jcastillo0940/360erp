<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Cotización {{ $cotizacion->numero_cotizacion }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        
        /* HEADER */
        .header-container { width: 100%; margin-bottom: 30px; }
        .company-logo { font-size: 24px; font-weight: bold; color: #000; text-transform: uppercase; margin-bottom: 5px; }
        .company-details { font-size: 10px; color: #555; line-height: 1.4; }
        
        /* CLIENTE / PROVEEDOR BOX */
        .recipient-box { margin-top: 20px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9; width: 60%; float: left; }
        .recipient-label { font-size: 9px; font-weight: bold; color: #777; text-transform: uppercase; margin-bottom: 3px; }
        .recipient-name { font-size: 14px; font-weight: bold; color: #000; margin-bottom: 3px; }
        
        /* DOCUMENT INFO (Derecha) */
        .doc-info-box { width: 35%; float: right; margin-top: 20px; text-align: right; }
        .doc-type { font-size: 16px; font-weight: bold; color: #000; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 4px 0; font-size: 11px; }
        .meta-label { font-weight: bold; color: #555; text-align: left; }
        .meta-value { text-align: right; font-weight: bold; }

        /* TABLA DE ITEMS */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 40px; clear: both; }
        .items-table th { border-bottom: 2px solid #000; border-top: 2px solid #000; padding: 8px; text-align: left; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .items-table td { padding: 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* TOTALES */
        .totals-container { float: right; width: 40%; margin-top: 20px; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 6px 0; font-size: 12px; }
        .total-label { font-weight: bold; text-align: left; }
        .total-amount { text-align: right; font-weight: bold; }
        .grand-total { font-size: 14px; border-top: 2px solid #000; padding-top: 8px; margin-top: 8px; }

        /* FIRMAS */
        .signatures { margin-top: 80px; width: 100%; clear: both; }
        .sig-box { width: 45%; float: left; text-align: center; }
        .sig-line { border-top: 1px solid #000; width: 80%; margin: 0 auto 10px auto; }
        .sig-label { font-size: 10px; font-weight: bold; text-transform: uppercase; }
        
        /* FOOTER */
        .footer { position: fixed; bottom: 20px; width: 100%; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
    </head>
<body>
    <div class="header-container">
        <div class="company-logo">ERP 360 PANAMÁ</div>
        <div class="company-details">Departamento de Ventas</div>
    </div>

    <div class="recipient-box">
        <div class="recipient-label">Cliente Potencial:</div>
        <div class="recipient-name">{{ $cotizacion->cliente->razon_social }}</div>
        <div class="company-details">Tel: {{ $cotizacion->cliente->telefono }}</div>
    </div>

    <div class="doc-info-box">
        <div class="doc-type">COTIZACIÓN</div>
        <table class="meta-table">
            <tr><td class="meta-label">NO. COTIZACIÓN</td><td class="meta-value">{{ $cotizacion->numero_cotizacion }}</td></tr>
            <tr><td class="meta-label">FECHA</td><td class="meta-value">{{ $cotizacion->fecha_emision }}</td></tr>
            <tr><td class="meta-label">VÁLIDO HASTA</td><td class="meta-value">{{ $cotizacion->fecha_vencimiento }}</td></tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Descripción</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Precio</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cotizacion->detalles as $det)
            <tr>
                <td>{{ $det->item->nombre ?? 'Producto' }}</td>
                <td class="text-right">{{ $det->cantidad }}</td>
                <td class="text-right">B/. {{ number_format($det->precio, 2) }}</td>
                <td class="text-right">B/. {{ number_format($det->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-container">
        <table class="totals-table">
            <tr><td class="total-label">Subtotal</td><td class="total-amount">B/. {{ number_format($cotizacion->subtotal, 2) }}</td></tr>
            <tr><td class="total-label">Impuestos</td><td class="total-amount">B/. {{ number_format($cotizacion->itbms, 2) }}</td></tr>
            <tr class="grand-total"><td class="total-label">TOTAL</td><td class="total-amount">B/. {{ number_format($cotizacion->total, 2) }}</td></tr>
        </table>
    </div>

    <div class="signatures">
        <div class="sig-box"><div class="sig-line"></div><div class="sig-label">Vendedor Autorizado</div></div>
    </div>
</body>
</html>