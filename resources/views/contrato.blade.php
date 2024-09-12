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
            <h4>CONTRATO POR SERVICIO DE ALCANTARILLADO</h4>
        </div>

        <div class="contract-info" style="width: 100%; padding: 10px; width: 650px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Contrato No.:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $contrato_numero }}</td>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Servicio:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $servicio }}</td>
                </tr>
                <tr>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Direccion:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $direccion }}</td>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;"></td>
                    <td style="border-bottom: 1px solid black; padding: 8px;"></td>
                </tr>
                <tr>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Usuario:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $nombre_usuario }}</td>
                    <td class="label" style="border-bottom: 1px solid black; padding: 8px;">Fecha:</td>
                    <td style="border-bottom: 1px solid black; padding: 8px;">{{ $fecha }}</td>
                </tr>
            </table>
        </div>



        <div style="width: 660px;">
            <p>
                Se proceda la conexión a la red de alcantarillado, el servicio de la casa citada al principio de esta solicitud,
                para lo anterior, manifiesto mi conformidad con lo siguiente:
            </p>
            <p>
                a) En pagar la cuota por derecho de conexión aprobada por el sistema de Agua Potable y Alcantarillado de La Paz, con
                el objetivo de que dicha casa quede conectada al sistema de alcantarillado. Esta cuota por concepto de derechos
                de tubería de salida, será pagada al contado.
            </p>
            <p>
                b) Manifiesto mi conformidad en pagar las cuotas que establezcan las tarifas que autorice el Sistema de Agua Potable
                y Alcantarillado de La Paz, para la amortización, conservación y administración del sistema.
            </p>
            <p>
                c) Si en un plazo de seis meses, a partir de la fecha de su contrato no se instale la descarga por que el usuario no
                la necesite por el momento, se cobrará la diferencia del precio vigente del contrato cuando el usuario venga de
                nuevo a que le instale su descarga, ya contratada anteriormente.
            </p>
            <p>
                d) El pago de este contrato no incluye la construcción del registro para la descarga.
            </p>
        </div>

        <div style="width: 670px;  margin-top: 50px;">
            <div style="border-bottom: 1px solid black; display: inline-block; margin-left: 100px; padding: 10px; width: 200px; text-align:center; padding-bottom:30px;">
                <p>El Usuario</p>
                <p>{{ $nombre_usuario }}</p>
            </div>
            <div style="border-bottom: 1px solid black; display: inline-block; padding: 10px; margin-left: 10px;width: 200px; text-align:center; padding-bottom:30px;">
                <p>Por el Sistema</p>
                <p>{{ $nombre_sistema }}</p>
            </div>
        </div>

        <div style=" border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
            width: 640px;
            text-align: center;">
            <p>
                El pago de este contrato no incluye la construcción del registro para la descarga.
            </p>
        </div>
    </div>
</body>

</html>