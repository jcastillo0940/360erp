<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ddd; }
        .total { text-align: right; margin-top: 20px; font-size: 1.2em; font-weight: bold; }
        .qr { text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA ELECTRÓNICA</h1>
        <h3>{{ config('app.name') }}</h3>
    </div>
    
    <div class="info">
        <strong>Cliente:</strong> {{ $factura->cliente->razon_social }}<br>
        <strong>RUC:</strong> {{ $factura->cliente->identificacion }}<br>
        <strong>Fecha:</strong> {{ $factura->fecha_emision }}<br>
        <strong>Factura N°:</strong> {{ $factura->numero_factura }}<br>
        <strong>CUFE:</strong> {{ $factura->cufe }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Cant</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->detalles as $det)
            <tr>
                <td>{{ $det->cantidad }}</td>
                <td>{{ $det->item->descripcion }}</td>
                <td>${{ number_format($det->precio, 2) }}</td>
                <td>${{ number_format($det->total_linea, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Subtotal: ${{ number_format($factura->subtotal, 2) }}<br>
        ITBMS: ${{ number_format($factura->itbms, 2) }}<br>
        TOTAL: ${{ number_format($factura->total, 2) }}
    </div>

    <div class="qr">
        <p>Escanee para validar en DGI</p>
        <div style="background: #eee; width: 100px; height: 100px; margin: 0 auto; line-height: 100px;">QR CODE</div>
    </div>
</body>
</html>