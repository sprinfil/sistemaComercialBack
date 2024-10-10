<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Multa</title>
</head>
<body>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .content {
            width: 100%;
            margin-top: 20px;
        }
        .content h2 {
            font-size: 16px;
            text-align: center;
            margin-bottom: 15px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content table, .content th, .content td {
            border: 1px solid #000;
        }
        .content th, .content td {
            padding: 8px;
            text-align: left;
        }
        .content th {
            background-color: #f2f2f2;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature p {
            margin: 0;
            border-top: 1px solid #000;
            display: inline-block;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>H.AYUNTAMIENTO DE LA PAZ, B.C.S.</h1>
        <h2>ORGANISMO OPERADOR MUNICIPAL DEL SISTEMA DE AGUA 
            POTABLE, ALCANTARILLADO Y SANEAMIENTO DE LA PAZ 
            DIRECCION COMERCIAL</h2>
        <p><strong>{{$folio}}</strong></p>
        <p>{{$fecha}}</p>
    </div>

    <div class="content">
        <h2>Datos del servicio publico actuante</h2>
        <table>
            <tr>
                <th>Nombre:</th>
                <td></td>
            </tr>
            <tr>
                <th>Numero de identificacion:</th>
                <td></td>
            </tr>
        </table>

        <h2>Datos del presunto infractor</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <td></td>
            </tr>
            <tr>
                <th>Cuenta</th>
                <td></td>
            </tr>
            <tr>
                <th>Domicilio</th>
                <td></td>
            </tr>
            <tr>
                <th>Numero de medidor</th>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <p>Firma del Oficial</p>
        <p>______________________________</p>
    </div>
</body>
</html>