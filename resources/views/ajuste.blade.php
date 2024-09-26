<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Servicio de Alcantarillado</title>
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
            <h4>REPORTE DE AJUSTES</h4>
        </div>

        <div class="contract-info" style="width: 100%; padding: 10px; width: 650px;">
            <table style="width: 100%; border-collapse: collapse;">
                <th>
                    <td style="border-bottom: 1px solid black; padding: 8px;">Tipo de ajuste</td>

                    <td style="border-bottom: 1px solid black; padding: 8px;">Usuario</td>
                </th>
                <tr>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">ASASA</td>
                </tr>

            </table>
            <input type="checkbox" name="" id="" checked="true"><p>{{ $monto_total }}</p>
        </div>

    </div>
</body>

</html>