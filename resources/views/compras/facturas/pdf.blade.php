<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CXP {{ $factura->numero_factura }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        
        /* HEADER */
        .header-container { width: 100%; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .company-logo { font-size: 24px; font-weight: bold; color: #000; text-transform: uppercase; margin-bottom: 5px; }
        .company-details { font-size: 10px; color: #555; }
        
        /* PROVEEDOR BOX */
        .recipient-box { margin-top: 20px; padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9; width: 55%; float: left; }
        .recipient-label { font-size: 9px; font-weight: bold; color: #777; text-transform: uppercase; margin-bottom: 3px; }
        .recipient-name { font-size: 14px; font-weight: bold; color: #000; margin-bottom: 3px; }
        
        /* DOCUMENT INFO */
        .doc-info-box { width: 40%; float: right; margin-top: 20px; text-align: right; }
        .doc-type { font-size: 16px; font-weight: bold; color: #000; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 4px 0; font-size: 11px; }
        .meta-label { font-weight: bold; color: #555; text-align: left; }
        .meta-value { text-align: right; font-weight: bold; }

        /* TABLA ITEMS */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 40px; clear: both; }
        .items-table th { border-bottom: 2px solid #000; border-top: 2px solid #000; padding: 8px; text-align: left; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .items-table td { padding: 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        .text-right { text-align: right; }

        /* TOTALES */
        .totals-container { float: right; width: 40%; margin-top: 20px; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 6px 0; font-size: 12px; }
        .total-label { font-weight: bold; text-align: left; }
        .total-amount { text-align: right; font-weight: bold; }
        .grand-total { font-size: 14px; border-top: 2px solid #000; padding-top: 8px; margin-top: 8px; }

        /* SALDO */
        .balance-box { clear: both; margin-top: 30px; border: 1px dashed #ccc; padding: 10px; text-align: center; font-size: 14px; }
        .balance-amount { color: #c0392b; font-weight: bold; font-size: 16px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header-container">
        <div class="company-logo">ERP 360 PANAMÁ</div>
        <div class="company-details">COMPROBANTE DE CUENTA POR PAGAR (INTERNO)</div>
    </div>

    <div class="recipient-box">
        <div class="recipient-label">Proveedor:</div>
        <div class="recipient-name">{{ $factura->proveedor->razon_social }}</div>
        <div class="company-details">
            RUC: {{ $factura->proveedor->ruc }}<br>
            Tel: {{ $factura->proveedor->telefono }}<br>
            {{ $factura->proveedor->direccion }}
        </div>
    </div>

    <div class="doc-info-box">
        <div class="doc-type">FACTURA DE COMPRA</div>
        <table class="meta-table">
            <tr><td class="meta-label">ID SISTEMA</td><td class="meta-value">{{ $factura->numero_factura }}</td></tr>
            <tr><td class="meta-label">N° FISCAL PROVEEDOR</td><td class="meta-value">{{ $factura->numero_factura_proveedor }}</td></tr>
            <tr><td class="meta-label">FECHA EMISIÓN</td><td class="meta-value">{{ $factura->fecha_emision }}</td></tr>
            <tr><td class="meta-label">VENCIMIENTO</td><td class="meta-value">{{ $factura->fecha_vencimiento }}</td></tr>
            <tr><td class="meta-label">CONDICIÓN</td><td class="meta-value">{{ strtoupper($factura->condicion_pago) }}</td></tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Descripción / Concepto</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Costo Unit.</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @if($factura->ordenCompra && $factura->ordenCompra->detalles)
                @foreach($factura->ordenCompra->detalles as $det)
                <tr>
                    <td>
                        {{ $det->descripcion }}
                        @if($det->item)<br><small style="color:#777">{{ $det->item->codigo }}</small>@endif
                    </td>
                    <td class="text-right">{{ $det->cantidad }}</td>
                    <td class="text-right">B/. {{ number_format($det->costo_unitario, 2) }}</td>
                    <td class="text-right">B/. {{ number_format($det->total, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="4" class="text-center">Detalle genérico por monto total.</td></tr>
            @endif
        </tbody>
    </table>

    <div class="totals-container">
        <table class="totals-table">
            <tr><td class="total-label">Subtotal</td><td class="total-amount">B/. {{ number_format($factura->subtotal, 2) }}</td></tr>
            <tr><td class="total-label">Impuestos</td><td class="total-amount">B/. {{ number_format($factura->itbms, 2) }}</td></tr>
            <tr class="grand-total"><td class="total-label">TOTAL FACTURA</td><td class="total-amount">B/. {{ number_format($factura->total, 2) }}</td></tr>
        </table>
    </div>

    <div class="balance-box">
        Estado Actual: <strong>{{ strtoupper($factura->estado_pago) }}</strong>
        <br>Saldo Pendiente de Pago: <span class="balance-amount">B/. {{ number_format($factura->saldo_pendiente, 2) }}</span>
    </div>

    <div style="margin-top: 50px; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 10px;">
        Documento de control interno. Este comprobante valida el registro de la deuda en el sistema ERP 360.
    </div>
</body>
</html>