<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Nota Ajuste {{ $nota->numero_nota }}</title>
<style>
    body { font-family: Helvetica, Arial, sans-serif; padding: 40px; }
    .header { width: 100%; border-bottom: 2px solid #333; margin-bottom: 20px; }
    .title { font-size: 20px; font-weight: bold; text-transform: uppercase; }
    .type-label { font-size: 14px; font-weight: bold; padding: 5px; color: white; display: inline-block; margin-top: 5px; }
    .bg-red { background-color: #c0392b; }
    .bg-green { background-color: #27ae60; }
    table { width: 100%; border-collapse: collapse; margin-top: 30px; }
    th, td { padding: 10px; border-bottom: 1px solid #eee; }
    th { background: #eee; text-align: left; }
</style>
</head>
<body onload="window.print()">
    <table class="header">
        <tr>
            <td>
                <h3>ERP 360 PANAMÁ</h3>
                Departamento de Compras
            </td>
            <td style="text-align: right;">
                <div class="title">NOTA DE {{ strtoupper($nota->tipo_nota) == 'DEBITO' ? 'DÉBITO' : 'CRÉDITO' }}</div>
                <div class="type-label {{ $nota->tipo_nota == 'debito' ? 'bg-red' : 'bg-green' }}">
                    {{ $nota->tipo_nota == 'debito' ? 'CARGO (AUMENTA DEUDA)' : 'ABONO (DISMINUYE DEUDA)' }}
                </div>
                <h3>{{ $nota->numero_nota }}</h3>
            </td>
        </tr>
    </table>

    <p><strong>Proveedor:</strong> {{ $nota->proveedor->razon_social }} (RUC: {{ $nota->proveedor->ruc }})</p>
    <p><strong>Factura Afectada:</strong> {{ $nota->factura->numero_factura_proveedor ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $nota->fecha_emision }}</p>

    <table>
        <thead><tr><th>Concepto</th><th style="text-align: right;">Monto</th></tr></thead>
        <tbody>
            <tr>
                <td>{{ $nota->motivo }}</td>
                <td style="text-align: right;">B/. {{ number_format($nota->monto, 2) }}</td>
            </tr>
            @if($nota->itbms > 0)
            <tr><td>ITBMS</td><td style="text-align: right;">B/. {{ number_format($nota->itbms, 2) }}</td></tr>
            @endif
            <tr>
                <td style="font-weight: bold; text-align: right;">TOTAL:</td>
                <td style="font-weight: bold; text-align: right;">B/. {{ number_format($nota->total, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>