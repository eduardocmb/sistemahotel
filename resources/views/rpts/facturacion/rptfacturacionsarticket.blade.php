<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ticket Factura Hotel</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 10px;
        }

        .ticket {
            width: 200px;
            margin: 0 auto;
            padding: 10px;
            box-sizing: border-box;
            text-align: center;
        }

        .logo img {
            width: 80px;
            height: auto;
            margin-bottom: 5px;
        }

        h1 {
            font-size: 12px;
            margin: 0;
        }

        .info {
            margin: 5px 0;
            text-align: left;
        }

        .info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        th,
        td {
            text-align: left;
            padding: 2px;
            font-size: 9px;
        }

        th {
            font-weight: bold;
            border-bottom: 1px dashed black;
        }

        tfoot td {
            border-top: 1px dashed black;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 9px;
        }

        .footer p {
            margin: 2px 0;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <!-- Encabezado -->
        <h1>{{ $info->nombre }}</h1>
        <p><strong>RTN:</strong> {{ $info->rtn }}</p>
        <p><strong>Tel:</strong> {{ $info->telefono }}</p>
        <p><strong>Dirección:</strong> {{ $info->direccion }}</p>
        <p><strong>CAI:</strong> {{ $cai->cai }}</p>
        <p><strong>Fecha Límite:</strong> {{ $cai->fechalimite }}</p>
        <hr>

        <!-- Información de la factura -->
        <div class="info">
            <p><strong>Fecha:</strong> {{ $factura[0]->fecha }}</p>
            <p><strong>Turno:</strong> {{ $factura[0]->turno }}</p>
            <p><strong>Usuario:</strong> {{ $factura[0]->usuario }}</p>
            <p><strong>Cliente:</strong> {{ $factura[0]->nombre_completo }}</p>
            <p><strong>RTN Cliente:</strong> {{ $factura[0]->rtn }}</p>
            <h2>FACTURA N°:{{ $factura[0]->factnum }}</h2>
            <h3>FACTURA {{ $tipofactura }}</h3>
            <p><strong>RANGO AUTORIZADO</strong></p>
            <p><strong>DESDE:</strong> {{ $cai->facturainicial }} <br> <strong>HASTA:</strong> {{ $cai->facturafinal }}
            </p>            <hr>
        </div>

        <!-- Detalles -->
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($factura as $item)
                    <tr>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->cant }}</td>
                        <td>{{ number_format($item->precio * $item->cant - $item->descto, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Descuento:</td>
                    <td>{{ number_format($factura[0]->descuento_general, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2">ISV 15%:</td>
                    <td>{{ number_format($factura[0]->isv15, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2">ISV 18%:</td>
                    <td>{{ number_format($factura[0]->isv18, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total:</strong></td>
                    <td><strong>{{ number_format($factura[0]->totalfactura, 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2">Efectivo:</td>
                    <td>{{ number_format($factura[0]->efectivo, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2">Cambio:</td>
                    <td>{{ number_format($factura[0]->cambio, 2) }}</td>
                </tr>
            </tfoot>
        </table>
        <hr>

        <!-- Pie de página -->
        <div class="footer">
            <p>{{$factura[0]->enletras}}</p><br>
            <strong>Exija su Factura</strong><br>
            <small>La factura es beneficio de todos.</small><br>
            <small>Nº Correlativo de orden de compra exenta: <br>_________________</small><br>
            <small>Nº Correlativo de constancia de registro exonerado: <br>__________</small><br>
            <small>Nº identificativo del registro SAG: <br>________________________</small><br>
            <br>
            <p>ORIGINAL: CLIENTE</p>
            <p>COPIA: OBLIGADO TRIBUTARIO EMISOR</p>
        </div>
    </div>
</body>

</html>
