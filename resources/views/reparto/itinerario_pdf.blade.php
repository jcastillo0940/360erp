<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ITINERARIO DE ENTREGAS - {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #3b82f6;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 4px 8px;
        }

        .info-label {
            font-weight: bold;
            color: #475569;
            width: 150px;
        }

        .orden-item {
            page-break-inside: avoid;
            border: 1px solid #e2e8f0;
            margin-bottom: 15px;
            padding: 12px;
            background: white;
        }

        .orden-header {
            background: #3b82f6;
            color: white;
            padding: 8px 12px;
            margin: -12px -12px 10px -12px;
            font-weight: bold;
            font-size: 13px;
        }

        .orden-numero {
            font-size: 16px;
            font-family: monospace;
        }

        .cliente-info {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #cbd5e1;
        }

        .cliente-nombre {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        .direccion {
            color: #64748b;
            margin-top: 4px;
            font-size: 11px;
        }

        .productos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        .productos-table th {
            background: #f1f5f9;
            padding: 6px;
            text-align: left;
            border-bottom: 2px solid #cbd5e1;
            font-weight: bold;
            text-transform: uppercase;
        }

        .productos-table td {
            padding: 5px 6px;
            border-bottom: 1px solid #e2e8f0;
        }

        .total-box {
            text-align: right;
            margin-top: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .firma-box {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 5px;
            text-align: center;
            font-size: 10px;
        }

        .oc-badge {
            background: #a855f7;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            margin-left: 8px;
        }

        .resumen {
            background: #f0f9ff;
            border: 2px solid #3b82f6;
            padding: 15px;
            margin-top: 20px;
            page-break-before: avoid;
        }

        .resumen h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h1>📋 ITINERARIO DE ENTREGAS</h1>
        <div class="subtitle">ERP 360 PANAMÁ - Sistema de Gestión Logística</div>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td class="info-label">FECHA DE ENTREGA:</td>
                <td><strong>{{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</strong>
                </td>
            </tr>
            <tr>
                <td class="info-label">REPARTIDOR:</td>
                <td><strong>{{ $repartidor ? $repartidor->nombre : 'TODOS LOS REPARTIDORES' }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">TOTAL DE ENTREGAS:</td>
                <td><strong>{{ $ordenes->count() }} órdenes</strong></td>
            </tr>
            <tr>
                <td class="info-label">GENERADO:</td>
                <td>{{ now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    @php
        $totalGeneral = 0;
        $totalCobrar = 0;
    @endphp

    @foreach($ordenes as $index => $orden)
        @php
            $totalGeneral += $orden->total;
            if (strtoupper($orden->cliente->condicion_pago ?? '') == 'CONTADO') {
                $totalCobrar += $orden->total;
            }
        @endphp
        <div class="orden-item">
            <div class="orden-header">
                <span class="orden-numero">{{ $orden->numero_orden }}</span>
                <span style="float: right;">Entrega #{{ $index + 1 }}</span>
            </div>

            <div class="cliente-info">
                <div class="cliente-nombre">
                    {{ $orden->cliente->razon_social }}
                    @if($orden->oc_externa)
                        <span class="oc-badge">OC: {{ $orden->oc_externa }}</span>
                    @endif
                </div>
                <div class="direccion">
                    <strong>📍 Dirección:</strong> {{ $orden->sucursal->direccion ?? $orden->cliente->direccion }}
                    ({{ $orden->sucursal->nombre ?? 'Principal' }})
                </div>
                <div class="direccion">
                    <strong>📞 Teléfono:</strong> {{ $orden->cliente->telefono ?? 'N/A' }}
                </div>
                <div class="direccion">
                    <strong>💳 Condición:</strong> {{ strtoupper($orden->cliente->condicion_pago ?? 'CONTADO') }}
                </div>
            </div>

            <table class="productos-table">
                <thead>
                    <tr>
                        <th width="50%">Producto</th>
                        <th width="15%" style="text-align: right;">Cant.</th>
                        <th width="20%" style="text-align: right;">Precio Unit.</th>
                        <th width="15%" style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orden->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->descripcion }}</td>
                            <td style="text-align: right;">{{ $detalle->cantidad }}</td>
                            <td style="text-align: right;">B/. {{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td style="text-align: right;">B/. {{ number_format($detalle->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-box">
                TOTAL ORDEN: B/. {{ number_format($orden->total, 2) }}
                @if(strtoupper($orden->cliente->condicion_pago ?? '') != 'CONTADO')
                    <div style="color: #059669; font-size: 11px; margin-top: 4px;">✓ CRÉDITO - NO COBRAR</div>
                @endif
            </div>

            <div class="firma-box">
                <table style="width: 100%; font-size: 10px;">
                    <tr>
                        <td style="width: 50%;">
                            <strong>Entregado por:</strong> _______________________
                        </td>
                        <td style="width: 50%; text-align: right;">
                            <strong>Hora:</strong> _______________________
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach

    <div class="resumen">
        <h3>RESUMEN DEL ITINERARIO</h3>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td><strong>Total de Entregas:</strong></td>
                <td style="text-align: right;">{{ $ordenes->count() }} órdenes</td>
            </tr>
            <tr>
                <td><strong>Monto Total (Todas las órdenes):</strong></td>
                <td style="text-align: right;">B/. {{ number_format($totalGeneral, 2) }}</td>
            </tr>
            <tr style="border-top: 2px solid #1e40af; padding-top: 8px;">
                <td><strong>Monto Total a Cobrar (Solo CONTADO):</strong></td>
                <td style="text-align: right; font-size: 16px; color: #1e40af;"><strong>B/.
                        {{ number_format($totalCobrar, 2) }}</strong></td>
            </tr>
        </table>
    </div>

</body>

</html>