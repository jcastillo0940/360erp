<!DOCTYPE html>
<html>
<head><title>Orden Entrega</title></head>
<body onload="window.print()" style="font-family: sans-serif; padding: 40px;">
    <div style="border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; display:flex; justify-content:space-between;">
        <div><h1 style="margin:0">ERP 360</h1><p>Departamento de Logística</p></div>
        <div style="text-align:right"><h2>ORDEN DE ENTREGA</h2><h3>{{ $entrega->numero_entrega }}</h3></div>
    </div>
    <div style="margin-bottom: 20px;">
        <strong>Cliente:</strong> {{ $entrega->cliente->razon_social }}<br>
        <strong>Dirección:</strong> {{ $entrega->direccion_destino }}<br>
        <strong>Fecha:</strong> {{ $entrega->fecha_despacho }}
    </div>
    <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 15px;">
        <strong>Transportista:</strong> {{ $entrega->transportista }} | <strong>Placa:</strong> {{ $entrega->placa_vehiculo }}
    </div>
    
    @if($entrega->factura)
    <h3>Detalle de Carga</h3>
    <table style="width:100%; border-collapse:collapse; margin-bottom:30px;">
        <thead><tr style="background:#eee;"><th style="padding:5px; text-align:left;">Item</th><th style="padding:5px; text-align:right;">Cantidad</th></tr></thead>
        <tbody>
            @foreach($entrega->factura->detalles as $det)
            <tr>
                <td style="padding:5px; border-bottom:1px solid #ddd;">{{ $det->item->descripcion ?? 'Producto' }}</td>
                <td style="padding:5px; border-bottom:1px solid #ddd; text-align:right;">{{ $det->cantidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div style="margin-top: 50px; display:flex; justify-content:space-between;">
        <div style="border-top:1px solid #000; width:40%; padding-top:10px; text-align:center">Entregado Por</div>
        <div style="border-top:1px solid #000; width:40%; padding-top:10px; text-align:center">Recibido Conforme</div>
    </div>
</body>
</html>