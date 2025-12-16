<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>FACTURA DE VENTA {{ $factura->numero_factura }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        
        /* HEADER */
        .header-container { width: 100%; margin-bottom: 30px; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .company-logo { font-size: 24px; font-weight: bold; color: #3b82f6; text-transform: uppercase; margin-bottom: 5px; }
        
        /* CLIENTE / PROVEEDOR BOX */
        .recipient-box { margin-top: 20px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9; width: 60%; float: left; }
        .doc-info-box { width: 35%; float: right; margin-top: 20px; text-align: right; }
        .doc-type { font-size: 16px; font-weight: bold; color: #000; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 4px 0; font-size: 11px; }
        .meta-label { font-weight: bold; color: #555; text-align: left; }

        /* TABLA ITEMS */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 40px; clear: both; }
        .items-table th { border-bottom: 2px solid #000; border-top: 2px solid #000; padding: 8px; text-align: left; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .items-table td { padding: 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        .text-right { text-align: right; }

        /* TOTALES */
        .totals-container { float: right; width: 35%; margin-top: 20px; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 6px 0; font-size: 12px; }

        /* FIRMAS */
        .signature-area { width: 45%; float: left; text-align: center; margin-top: 50px; }
        .signature-line { border-top: 1px solid #000; margin-top: 10px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header-container">
        <div class="company-logo">ERP 360 PANAMÁ</div>
        <div class="company-details">
            FACTURA DE VENTA (Documento {{ $factura->estado }})
        </div>
    </div>

    <div class="recipient-box">
        <div class="recipient-label">Cliente:</div>
        <div class="recipient-name">{{ $factura->cliente->razon_social }}</div>
        <div class="company-details">
            RUC: {{ $factura->cliente->identificacion }}<br>
            Dirección: {{ $factura->sucursal->direccion ?? $factura->cliente->direccion }}
        </div>
    </div>

    <div class="doc-info-box">
        <div class="doc-type">FACTURA DE VENTA</div>
        <table class="meta-table">
            <tr><td class="meta-label">N° DOCUMENTO</td><td class="meta-value">{{ $factura->numero_factura }}</td></tr>
            <tr><td class="meta-label">FECHA EMISIÓN</td><td class="meta-value">{{ $factura->fecha_emision }}</td></tr>
            <tr><td class="meta-label">FECHA VENC.</td><td class="meta-value">{{ $factura->fecha_vencimiento ?? 'N/A' }}</td></tr>
            <tr><td class="meta-label">CONDICIÓN</td><td class="meta-value">{{ strtoupper($factura->cliente->condicion_pago ?? 'Contado') }}</td></tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="60%">Ítem</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Precio Unit.</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->detalles as $det)
            <tr>
                <td>{{ $det->descripcion }}</td>
                <td class="text-right">{{ $det->cantidad }}</td>
                <td class="text-right">B/. {{ number_format($det->precio_unitario, 2) }}</td>
                <td class="text-right">B/. {{ number_format($det->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="totals-container">
        <table class="totals-table">
            <tr><td style="font-weight: bold;">Subtotal:</td><td class="text-right">B/. {{ number_format($factura->subtotal, 2) }}</td></tr>
            <tr><td style="font-weight: bold;">ITBMS (7%):</td><td class="text-right">B/. {{ number_format($factura->itbms, 2) }}</td></tr>
        </table>
        <table class="totals-table" style="border-top: 2px solid #000; margin-top: 5px;">
            <tr style="font-size: 14px; font-weight: bold;">
                <td>TOTAL A PAGAR:</td>
                <td class="text-right">B/. {{ number_format($factura->total, 2) }}</td>
            </tr>
        </table>
    </div>
    
    <div style="clear: both; margin-top: 50px;">
        <div class="signature-area" style="float: left;">
            <div class="signature-line"></div>
            <strong>{{ strtoupper(Auth::user()->name ?? 'N/A') }}</strong>
            <div style="font-size: 10px; color: #555;">ELABORADO POR (Sistema)</div>
        </div>
        <div class="signature-area" style="float: right;">
            <div class="signature-line"></div>
            <div style="font-size: 14px; margin-bottom: 5px;"></div>
            <div style="font-size: 10px; color: #555;">RECIBIDO CONFORME</div>
        </div>
    </div>

</body>
</html>