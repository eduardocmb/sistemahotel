<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte de Existencia de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .invoice {
            width: 100%;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
        }

        .header {
            width: 100%;
            overflow: hidden;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        .logo {
            margin: 60px 0 0 15px;
            float: left;
            width: 100px;
            height: auto;
        }

        .header-text {
            margin-top: 40px;
            float: left;
            width: calc(100% - 120px);
            text-align: center;
        }

        .header-text h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }

        .header-text h3 {
            font-size: 12px;
            margin: 5px 0 0 0;
        }

        .clear {
            clear: both;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .details th,
        .details td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }

        .details th {
            background-color: #f2f2f2;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 9px;
            text-align: center;
            background: white;
            padding: 10px 0;
            z-index: 999;
        }
    </style>
</head>

<body>
    <div class="invoice">
        <!-- Encabezado -->
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('storage/imgs/logo.jpg') }}" height="100vh" width="100vh" alt="Hotel Alameda">
            </div>
            <div class="header-text">
                <h1>{{ $info->nombre }}</h1>
                <p><strong>RTN:</strong> {{ $info->rtn }} <strong> | TELÉFONO:</strong> {{ $info->telefono }}</p>
                <p><strong>DIRECCIÓN:</strong> {{ $info->direccion }}</p>
                <p><strong>DE:</strong> {{ $info->propietario }}</p>
                <p><strong>CORREO:</strong> {{ $info->correo }}</p>
            </div>
            <div class="clear"></div>
            <h3>Reporte de Existencia de Productos</h3>
            <h4>Fecha: {{ \Carbon\Carbon::today()->format('d/m/Y') }}</h4>
        </div>

        <!-- Detalles -->
        <div class="details">
            <table>
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Cantidad en Inventario</th>
                        <th>Stock Minimo</th>
                        <th>Precio Venta</th>
                        <th>Categoría</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->codigo }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->total_cantidad }}</td>
                            <td>{{$producto->stock_minimo}}</td>
                            <td>{{ number_format($producto->precio_venta, 2) }}</td>
                            <td>{{ $producto->categoria }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
