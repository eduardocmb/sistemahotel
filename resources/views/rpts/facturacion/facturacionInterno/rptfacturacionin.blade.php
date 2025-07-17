<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Factura Hotel</title>
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
            /* Resta el ancho de la imagen */
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

        .invoice-info,
        .other-info {
            margin-top: 5px;
        }

        .invoice-info h3,
        .other-info h3 {
            font-size: 12px;
            margin: 0 0 5px;
        }

        .invoice-info p,
        .other-info p {
            margin: 3px 0;
            font-size: 11px;
        }

        .details table,
        .totals table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .details th,
        .details td,
        .totals th,
        .totals td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }

        .details th {
            background-color: #f2f2f2;
        }

        .totals td {
            text-align: right;
        }

        .totals th {
            text-align: left;
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
                <p><strong>RTN:</strong> {{ $info->rtn }} <strong> | TELÉFONO:</strong>{{ $info->telefono }}</p>
                <p><strong>DIRECCIÓN:</strong>{{ $info->direccion }}</p>
                <p><strong>DE:</strong>{{ $info->propietario }}</p>
                <p><strong>CAI:</strong>{{ $cai->cai }} <strong> | FECHA LIM. DE
                        EMISIÓN:</strong>{{ $cai->fechalimite }}</p>
                <p><strong>CORREO:</strong>{{ $info->correo }}</p>
            </div>
            <div class="clear"></div>
        </div>

        <!-- Información de la factura -->
        <div class="invoice-info">
            <h3>FECHA: {{ $factura[0]->fecha }} | TURNO: {{ $factura[0]->turno }} | USUARIO:
                {{ $factura[0]->usuario }}</h3>
            <p><strong>CLIENTE:</strong> {{ $factura[0]->nombre_completo }} |
                <strong>RTN:</strong>{{ $factura[0]->rtn }}
            </p>
            <p><strong>IDCLIENTE:</strong> {{ $factura[0]->codigo_cliente }} | <strong>PAGO:</strong>
                {{ $factura[0]->pago }}</p>
        </div>

        <div class="other-info">
            <h2>FACTURA N°:{{ $factura[0]->factnum }}</h2>
            <h3>FACTURA {{ $tipofactura }}</h3>
            <p><strong>RANGO AUTORIZADO</strong></p>
            <p><strong>DESDE:</strong> {{ $cai->facturainicial }} | <strong>HASTA:</strong> {{ $cai->facturafinal }}
            </p>
        </div>

        <!-- Detalles de la factura -->
        <div class="details">
            <table>
                <thead>
                    <tr>
                        <th>CODPRODUCTO</th>
                        <th>DESCRIPCION</th>
                        <th>PRECIO</th>
                        <th>CANT</th>
                        <th>DESCTO</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factura as $item)
                    <tr>
                        <td>{{ $item->codproducto }}</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ number_format((float) ($item->precio ?? 0), 2) }}</td>
                        <td>{{ (int) ($item->cant ?? 0) }}</td>
                        <td>{{ number_format((float) ($item->descto ?? 0), 2) }}</td>
                        <td>{{ number_format((float) ($item->precio ?? 0) * (int) ($item->cant ?? 0) - (float) ($item->descto ?? 0), 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="totals" style="margin: 15px 0 0 0;">
            <table>
                <tr style="background-color: #9c9a95">
                    <th>EFECTIVO:</th>
                    <td>{{ number_format($factura[0]->efectivo, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95">
                    <th>CAMBIO:</th>
                    <td>{{ number_format($factura[0]->cambio, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95">
                    <th>IMPORTE EXONERADO:</th>
                    <td>{{ number_format($factura[0]->impoexon, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95">
                    <th>IMPORTE EXENTO:</th>
                    <td>{{ number_format($factura[0]->impoexen, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95">
                    <th>IMPORTE GRAVADO 15%:</th>
                    <td>{{ number_format($factura[0]->impograv15, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95">
                    <th>IMPORTE GRAVADO 18%:</th>
                    <td>{{ number_format($factura[0]->impograv18, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95">
                    <th>DESCUENTO:</th>
                    <td>{{ number_format($factura[0]->descuento_general, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95">
                    <th>ISV 15%:</th>
                    <td>{{ number_format($factura[0]->isv15, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95">
                    <th>ISV 18%:</th>
                    <td>{{ number_format($factura[0]->isv18, 2) }}</td>
                </tr>
                <tr style="background-color: #9c9a95" class="summary">
                    <th>TOTAL A PAGAR:</th>
                    <td>{{ number_format($factura[0]->totalfactura, 2) }}</td>
                </tr>
            </table>
            <div class="text-center">
                <p>{{$factura[0]->enletras}}</p><br>
            </div>
        </div>
        <div class="footer">
            <strong>Exija su Factura</strong><br>
            <small>La factura es beneficio de todos.</small><br>
            <small>Nº Correlativo de orden de compra exenta:_________________</small><br>
            <small>Nº Correlativo de constancia de registro exonerado:__________</small><br>
            <small>Nº identificativo del registro SAG:________________________</small><br>
            <br>
            <p>ORIGINAL: CLIENTE</p>
            <p>COPIA: OBLIGADO TRIBUTARIO EMISOR</p>
        </div>
    </div>
</body>

</html>
