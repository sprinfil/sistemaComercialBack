<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORGANISMO OPERADOR MUNICIPAL DEL SISTEMA DE AGUA POTABLE, ALCANTARILLADO Y SANEAMIENTO DE LA PAZ</title>
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

        .headerB {
            text-align: center;
            font-weight: bold;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
            max-width: 600px;
            font-size: 15px;
            
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            
            
        }
        .signature p {
            text-align: center; /* Centra el texto */
            margin: 0;         /* Elimina márgenes */
            font-size: 20px;
            
        }

        .footer-box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
            width: 670px;
            text-align: justify;
        }
        .izquierdaImg{
            left: 1000px;
        }
    </style>
</head>


<div class="contract-info" style="width: 100%; padding: 00px; width: 700px;">
    <table style="width: 100%; border-collapse: collapse; border-bottom: 1px solid black;">
        <tr>
            <td style="padding: 8px; text-align: left;">
                <img src="images/logoAyuntamiento.png" alt="Logo Ayuntamiento" style="width: 100px; height: auto; margin-left: 0; ">
            </td>

            <td class="label" style="border-bottom: 1px solid black; padding: 8px; font-size: 18px; text-align: center;">ORGANISMO OPERADOR MUNICIPAL DEL SISTEMA DE AGUA POTABLE, ALCANTARILLADO Y SANEAMIENTO DE LA PAZ:</td>

            <td style="padding: 8px;">
                <img src="images/logo.png" alt="Imagen logo" style="width: 100px; height: auto;">
            </td>
        </tr>
    </table>
</div>

<body>
    <div class="container">

        <div class="headerB">
            <div>
                <h3>ASUNTO: CONSTANCIA DE NO ADEUDO</h3>
            </div>
        </div>


        <div style="width: 660px; font-size: 15px; text-align: justify;" >
            <p>
                Por medio del presente se hace constar, que no existe adeudo por cosumo de agua Potable y Alcantarillado, sobre el uso que se le da
                a esta toma segun nuestro registro que tiene clave de usuario N°{{$codigo_usuario}} a nombre de C. {{$nombre}},
                con domicilio {{$domicilio}}, hasta la facturación de {{$facturacion_previa}}, emitido con fecha de {{$fecha}}.

            </p>
            <p>
                La expedición de esta constancia no exenta al usuario de cobro de multa u otros cargos que se haya suscitado posteriores y que estén en proceso
                de facturación.
            </p>
            <p>
                A peticíon del interesado y para que los usos legales convenientes, se extiende la presente en la Ciudad de La Paz, Baja California Sur,
                a la fecha {{$fecha_texto}}.
            </p>
           
        </div>

            <div class="signature">
                <p>Por el Sistema</p>
                <p>{{$nombre_sistema }}</p>
            </div>
        </div>

        <img src="images/firmadc-new.jfif" alt="Logo Ayuntamiento" style="width: 200px; height: auto; margin-left: 300px; margin-bottom: -100px; ">
        
        <img src="images/Image_003.jfif" alt="Imagen logo" style="width: 700px; height: auto;vertical-align: bottom;  margin-top: 450px;">
        
    </div>
</body>

</html>