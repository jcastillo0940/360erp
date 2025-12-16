<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; margin: 0; padding: 40px 40px 150px 40px; }
        .header-container { width: 100%; margin-bottom: 30px; }
        .company-logo { font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .recipient-box { margin-top: 20px; padding: 10px; border: 1px solid #ddd; background: #f9f9f9; width: 60%; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .items-table th { border-bottom: 2px solid #000; text-align: left; padding: 5px; }
        .items-table td { padding: 5px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .footer-section { position: fixed; bottom: 0; left: 0; right: 0; height: 100px; padding: 20px 40px; background: white; }
        .signatures { width: 100%; display: table; }
        .sig-box { display: table-cell; width: 50%; text-align: center; }
        .sig-line { border-top: 1px solid #000; width: 80%; margin: 0 auto; }
        
        /* Estilo para Nota de Débito anidada */
        .nd-row { color: #c0392b; font-style: italic; font-size: 11px; }
        .nd-icon { font-weight: bold; margin-right: 5px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header-container">
        <div class="company-logo">ERP 360 PANAMÁ</div>
        <div>Departamento de Cuentas por Pagar</div>
    </div>

    <div class="recipient-box">
        <strong>PROVEEDOR:</strong> {{ $proveedor->razon_social }}<br>
        RUC: {{ $proveedor->ruc }}
    </div>

    <h2 style="text-align:right; margin-top:-60px;">ESTADO DE CUENTA</h2>
    <p style="text-align:right;">Corte al: {{ date('d/m/Y') }}</p>

    <table class="items-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Documento</th>
                <th>Vencimiento</th>
                <th class="text-right">Monto</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php $granTotal = 0; @endphp
            @foreach($movimientos as $m)
                <tr>
                    <td>{{ $m->fecha_emision }}</td>
                    <td style="font-weight:bold;">FAC {{ $m->numero_factura_proveedor }}</td>
                    <td>{{ $m->fecha_vencimiento }}</td>
                    <td class="text-right">B/. {{ number_format($m->total, 2) }}</td>
                    <td class="text-right" style="font-weight:bold;">B/. {{ number_format($m->saldo_pendiente, 2) }}</td>
                </tr>
                @php $granTotal += $m->saldo_pendiente; @endphp

                @foreach($m->notasDebito as $nd)
                <tr class="nd-row">
                    <td>{{ $nd->fecha_emision }}</td>
                    <td colspan="2"><span class="nd-icon">[+] ND {{ $nd->numero_nota }}</span>: {{ $nd->motivo }}</td>
                    <td class="text-right">{{ number_format($nd->total, 2) }}</td>
                    <td class="text-right">(Incluido)</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right" style="padding-top:10px; font-weight:bold;">TOTAL A PAGAR:</td>
                <td class="text-right" style="padding-top:10px; font-weight:bold; font-size:14px;">B/. {{ number_format($granTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-section">
        <div class="signatures">
            <div class="sig-box">
                <div>{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="sig-line"></div>
                <strong>ELABORADO POR</strong>
            </div>
            <div class="sig-box">
                <br><div class="sig-line"></div>
                <strong>RECIBIDO CONFORME</strong>
            </div>
        </div>
    </div>
</body>
</html>