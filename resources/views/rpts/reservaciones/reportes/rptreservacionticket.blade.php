<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ticket Factura</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        body {
            width: 95%;
        }

        .ticket {
            width: 80mm;
            box-sizing: border-box;
            border: 1px dashed #ccc;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .section {
            margin-bottom: 10px;
        }

        .details-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .details-table th,
        .details-table td,
        .totals-table th,
        .totals-table td {
            text-align: left;
        }

        .details-table th {
            border-bottom: 1px dashed #000;
        }

        .totals-table td {
            text-align: right;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <!-- Encabezado -->
        <div class="header center">
            <p class="bold">{{ $info->nombre }}</p>
            <p>RTN: {{ $info->rtn }}</p>
            <p>TEL: {{ $info->telefono }}</p>
            <p style="width: 80%">{{ $info->direccion }}</p>
            <p>CAI: {{ $cai->cai }}</p>
            <p>Fecha Límite Emisión: {{ $cai->fechalimite }}</p>
        </div>

        <!-- Información de la factura -->
        <div class="section" style="margin: 5px 0 0 0">
            <p><strong>Fecha:</strong> {{ $factura[0]->fecha }} <br> <strong>Turno:</strong> {{ $factura[0]->turno }}
            </p>
            <p><strong>Usuario:</strong> {{ $factura[0]->usuario }}</p>
            <p><strong>Cliente:</strong> {{ $factura[0]->nombre_completo }}</p>
            <p><strong>RTN:</strong> {{ $factura[0]->rtn }}</p>
            <p><strong>ID Cliente:</strong> {{ $factura[0]->codigo_cliente }}</p>
            <p><strong>Pago:</strong> {{ $factura[0]->pago }}</p>
            <h2>FACTURA N°:{{ $factura[0]->factnum }}</h2>
            <h3>FACTURA {{ $tipofactura }}</h3>
            <p><strong>RANGO AUTORIZADO</strong></p>
            <p><strong>DESDE:</strong> {{ $cai->facturainicial }} <br> <strong>HASTA:</strong>
                {{ $cai->facturafinal }}
            </p>
            <hr style="margin: 5px 0 5px 0">
        </div>

        <!-- Detalles de la factura -->
        <div class="section">
            <table style="width: 88%">
                <thead>
                    <tr>
                        <th style="width: 14%">ID</th>
                        <th style="width: 14%">SERVICIO</th>
                        <th style="width: 14%">PRECIO</th>
                        <th style="width: 14%">DIAS</th>
                        <th style="width: 14%">CANT</th>
                        <th style="width: 14%">DESCTO</th>
                        <th style="width: 14%">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px dotted  black;">{{ $habitacion->id }}</td>
                        <td style="border: 1px dotted  black;">Habitación N°:{{ $habitacion->numero_habitacion }}</td>
                        <td style="border: 1px dotted  black;">{{ number_format($habitacion->precio_diario, 2) }}</td>
                        <td style="border: 1px dotted  black;">
                            @php
                                $fechaentrada = \Carbon\Carbon::parse($factura[0]->fechaentrada);
                                $fechasalida = \Carbon\Carbon::parse($factura[0]->fechasalida);
                                $diffInDays = $fechaentrada->diffInDays($fechasalida);
                                echo $diffInDays;
                            @endphp
                        </td>
                        <td style="border: 1px dotted  black;">----</td>
                        <td style="border: 1px dotted  black;">{{ number_format($factura[0]->descto, 2) }}</td>
                        <td style="border: 1px dotted  black;">
                            @php
                                $total = $habitacion->precio_diario * $diffInDays - $factura[0]->descto;
                            @endphp
                            {{ number_format($total, 2) }}
                        </td>

                    </tr>

                    @php
                        $index = 0;
                    @endphp
                    @foreach ($serviciosadquiridos as $servicio)
                        <tr>
                            <td>{{ $servicio->producto_id }}</td>
                            <td>{{ $servicio->nombre }}</td>
                            <td>{{ number_format($servicio->precio_venta, 2) }}</td>
                            <td>----</td>
                            <td>{{ $servicio->cantidad }}</td>
                            <td>{{ number_format($servicio->descto, 2) }}</td>
                            <td>{{ number_format($servicio->total, 2) }}</td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="section">
            <table class="totals-table">
                <tr>
                    <th>Efectivo: {{ number_format($factura[0]->efectivo, 2) }} Lps.</th>
                </tr>
                <tr>
                    <th>Cambio: {{ number_format($factura[0]->cambio, 2) }} Lps.</th>
                </tr>
                <tr>
                    <th>Importe Exonerado: {{ number_format($factura[0]->impoexon, 2) }} Lps.</th>
                </tr>
                <tr>
                    <th>Importe Exento:{{ number_format($factura[0]->impoexen, 2) }} Lps.</th>
                </tr>
                <tr>
                    <th>ISV 15%: {{ number_format($factura[0]->isv15, 2) }} Lps.</th>
                </tr>
                <tr>
                    <th>ISV 18%:{{ number_format($factura[0]->isv18, 2) }} Lps.</th>
                </tr>
                <tr class="bold">
                    <th>Total a Pagar: {{ number_format($factura[0]->totalfactura, 2) }} Lps.</th>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ $factura[0]->enletras }}</p><br>
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
