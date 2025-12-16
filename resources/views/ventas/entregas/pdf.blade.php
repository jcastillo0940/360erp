<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ORDEN DE ENTREGA {{ $orden->numero_orden ?? $orden->numero_factura }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        /* HEADER */
        .header-container {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
        }

        .company-logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        /* CLIENTE / PROVEEDOR BOX */
        .recipient-box {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            width: 60%;
            float: left;
        }

        .doc-info-box {
            width: 35%;
            float: right;
            margin-top: 20px;
            text-align: right;
        }

        .doc-type {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 4px 0;
            font-size: 11px;
        }

        .meta-label {
            font-weight: bold;
            color: #555;
            text-align: left;
        }

        /* TABLA ITEMS */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            clear: both;
        }

        .items-table th {
            border-bottom: 2px solid #000;
            border-top: 2px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        /* TOTALES */
        .totals-container {
            float: right;
            width: 35%;
            margin-top: 20px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 6px 0;
            font-size: 12px;
        }

        /* LOGISTICA & FIRMAS */
        .logistics-box {
            width: 100%;
            margin-top: 40px;
            clear: both;
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f0f4ff;
            display: block;
        }

        .signature-area {
            width: 45%;
            float: left;
            text-align: center;
            margin-top: 50px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 10px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header-container">
        <div class="company-logo">ERP 360 PANAMÁ</div>
        <div class="company-details">
            ORDEN DE ENTREGA (Documento {{ $orden->estado }})
        </div>
    </div>

    <div class="recipient-box">
        <div class="recipient-label">Cliente:</div>
        <div class="recipient-name">{{ $orden->cliente->razon_social }}</div>
        <div class="company-details">
            RUC: {{ $orden->cliente->identificacion }}<br>
            Dirección: {{ $orden->sucursal->direccion ?? $orden->cliente->direccion }}
        </div>
    </div>

    <div class="doc-info-box">
        <div class="doc-type">ORDEN DE ENTREGA</div>
        <table class="meta-table">
            <tr>
                <td class="meta-label">N° DOCUMENTO</td>
                <td class="meta-value">{{ $orden->numero_orden ?? $orden->numero_factura }}</td>
            </tr>
            <tr>
                <td class="meta-label">FECHA EMISIÓN</td>
                <td class="meta-value">{{ $orden->fecha_emision }}</td>
            </tr>
            <tr>
                <td class="meta-label">FECHA ENTREGA</td>
                <td class="meta-value">{{ $orden->fecha_entrega }}</td>
            </tr>
            <tr>
                <td class="meta-label">CONDICIÓN</td>
                <td class="meta-value">{{ strtoupper($orden->cliente->condicion_pago ?? 'Contado') }}</td>
            </tr>
            @if($orden->oc_externa)
                <tr>
                    <td class="meta-label">OC CLIENTE</td>
                    <td class="meta-value" style="color: #d946ef; font-weight: bold;">{{ strtoupper($orden->oc_externa) }}
                    </td>
                </tr>
            @endif
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
            @foreach($orden->detalles as $det)
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
        <table class="totals-table" style="display: none">
            <tr>
                <td style="font-weight: bold;">Subtotal:</td>
                <td class="text-right">B/. {{ number_format($orden->subtotal ?? $orden->total / 1.07, 2) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">ITBMS (7%):</td>
                <td class="text-right">B/.
                    {{ number_format($orden->itbms ?? $orden->total - ($orden->total / 1.07), 2) }}
                </td>
            </tr>
        </table>
        <table class="totals-table" style="border-top: 2px solid #000; margin-top: 5px;">
            <tr style="font-size: 14px; font-weight: bold;">
                <td>TOTAL:</td>
                <td class="text-right">B/. {{ number_format($orden->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="logistics-box">
        <h4>DETALLES DE REPARTO</h4>
        <div style="width: 100%;">
            Ruta Asignada: <strong>{{ $orden->ruta->nombre ?? 'N/A' }}</strong><br>
            Repartidor: {{ $orden->ruta->repartidor->nombre ?? 'Sin Asignar' }}
        </div>
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