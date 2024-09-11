<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Factibilidad de Servicios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            max-width: 700px;
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
            max-width: 600px;
        }
        .contract-info {
            margin-bottom: 20px;
        }
        .contract-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .contract-info td {
            padding: 5px 0;
        }
        .contract-info td.label {
            width: 150px;
            font-weight: bold;
        }
        .clause {
            margin-top: 20px;
            text-align: justify;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature div {
            width: 45%;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        .footer-box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
            width: 670px;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>H. XVII AYUNTAMIENTO DE LA PAZ, B.C.S.</h3>
            <h4>ORGANISMO OPERADOR MUNICIPAL DEL SISTEMA DE AGUA POTABLE, ALCANTARILLADO Y SANEAMIENTO DE LA PAZ</h4>
            <h4>CONSTANCIA DE FACTIBILIDAD DE SERVICIOS</h4>
        </div>

        <div class="contract-info" style="width: 100%; padding: 10px; width: 650px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Constancia No.:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $constancia_numero }}</td>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">y la calle:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $calle_y }}</td>
                </tr>
                <tr>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Calle:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $calle }}</td>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Costo por factibilidad:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $costo_factibilidad }}</td>
                </tr>
                <tr>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Casa No.:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $numero_casa }}</td>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Recibo No.:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $recibo_numero }}</td>
                </tr>
                <tr>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Entre la calle:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $calle_entre }}</td>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Notificación en:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $notificacion_calle }}</td>
                </tr>
            </table>
        </div>

        <div style="width: 660px;">
            <h4>Factibilidad del Servicio</h4>
            <p>
                La presente constancia acredita que el predio ubicado en la dirección mencionada cuenta con la factibilidad de 
                recibir los servicios de agua potable, alcantarillado y saneamiento, conforme a las normativas y disposiciones 
                del Organismo Operador Municipal del Sistema de Agua Potable, Alcantarillado y Sane
