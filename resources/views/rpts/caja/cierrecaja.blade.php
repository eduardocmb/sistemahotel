<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cierre de Caja</title>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>CIERRE DE CAJA</h2>
            <p>{{$info->nombre}}</p>
        </div>
        <div class="section">
            <span><strong>Apertura Nº:</strong> <span>{{ $apnum->codigo_apertura }}</span></span>
        </div>
        <div class="section">
            <span><strong>Fecha:</strong> <span>{{ $apnum->fecha }}</span></span>
        </div>
        <div class="section">
            <span><strong>Usuario:</strong> <span>{{ $usuario->username }}</span></span>
        </div>

        <hr>

        <div class="section">
            <span><strong>Fondo Inicial:</strong> <span>{{ $apnum->fondoinicial }} Lps.</span>
        </div>
        <div class="section">
            <span><strong>Ventas Efectivo:</strong> <span>{{ $cierre->ventasefe }} Lps.</span></span>
        </div>
        <div class="section">
            <span><strong>Ventas POS:</strong> <span>{{ $cierre->ventaspos }} Lps.</span></span>
        </div>
        <div class="section">
            <span><strong>Transferencias:</strong> <span>{{ $cierre->transferencias }} Lps.</span></span>
        </div>
        <div class="section">
            <span><strong>Total Ventas:</strong> <span>{{ $cierre->totventas }} Lps.</span></span>
        </div>
        <div class="section">
            <span><strong>Egresos:</strong> <span>{{ $cierre->egresos }} Lps.</span></span>
        </div>
        <div class="section">
            <span><strong>Caja:</strong> <span>{{ $cierre->caja }} Lps.</span></span>
        </div>
        <div class="section">
            <span><strong>Diferencia:</strong> <span>{{ $cierre->diferencia }} Lps.</span></span>
        </div>
        <div class="section">
            <span><strong>Observación:</strong> <span>{{ $cierre->observ }}</span></span>
        </div>
        <div class="section">
            <span><strong>Retirar:</strong> <span>{{ $cierre->retirar }} Lps.</span></span>
        </div>
        <hr>
        <div class="footer">
            <p><strong>Fecha de impresión:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
            <p><strong>Hora de impresión:</strong> <br> {{ \Carbon\Carbon::now()->format('H:i:s') }}</p>
        </div>
    </div>
</body>

</html>
