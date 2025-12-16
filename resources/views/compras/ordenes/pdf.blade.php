<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ORDEN DE COMPRA {{ $orden->numero_orden }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

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

        .note-box {
            width: 100%;
            margin-top: 40px;
            clear: both;
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #fdfdfd;
            display: block;
        }

        .signature-area {
            width: 45%;
            float: left;
            text-align: center;
            margin-top: 60px;
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
            ORDEN DE COMPRA A PROVEEDOR
        </div>
    </div>

    <div class="recipient-box">
        <div style="font-weight: bold; margin-bottom: 5px; color: #555;">PROVEEDOR:</div>
        <div style="font-size: 14px; font-weight: bold;">{{ $orden->proveedor->razon_social }}</div>
        <div class="company-details">
            RUC: {{ $orden->proveedor->ruc }}<br>
            Teléfono: {{ $orden->proveedor->telefono ?? 'N/A' }}
        </div>
    </div>

    <div class="doc-info-box">
        <div class="doc-type">ORDEN DE COMPRA</div>
        <table class="meta-table">
            <tr>
                <td class="meta-label">N° ORDEN</td>
                <td class="meta-value">{{ $orden->numero_orden }}</td>
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
                <td class="meta-label">ESTADO</td>
                <td class="meta-value">{{ strtoupper($orden->estado) }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="50%">Descripción / Ítem</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Costo Unit.</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orden->detalles as $det)
                <tr>
                    <td>{{ $det->descripcion }}</td>
                    <td class="text-right">{{ $det->cantidad }}</td>
                    <td class="text-right">B/. {{ number_format($det->costo_unitario, 2) }}</td>
                    <td class="text-right">B/. {{ number_format($det->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-container">
        <table class="totals-table" style="border-top: 2px solid #000; margin-top: 5px;">
            <tr style="font-size: 14px; font-weight: bold;">
                <td>TOTAL ORDEN:</td>
                <td class="text-right">B/. {{ number_format($orden->total, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($orden->observaciones)
        <div class="note-box">
            <strong>OBSERVACIONES:</strong><br>
            {{ $orden->observaciones }}
        </div>
    @endif

    <div style="clear: both; margin-top: 50px;">
        <div class="signature-area" style="float: left;">
            <div class="signature-line"></div>
            <strong>AUTORIZADO POR</strong>
            <div style="font-size: 10px; color: #555;">{{ Auth::user()->name ?? 'Administración' }}</div>
        </div>
        <div class="signature-area" style="float: right;">
            <div class="signature-line"></div>
            <div style="font-size: 14px; margin-bottom: 5px;"></div>
            <div style="font-size: 10px; color: #555;">RECIBIDO POR PROVEEDOR</div>
        </div>
    </div>

</body>

</html>