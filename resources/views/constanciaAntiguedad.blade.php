<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Constancia de no adeudo</title>

    <style type="text/css">
        h1 {
            color: #6C6C6C;
            font-family: "Microsoft Sans Serif", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 14pt;
            text-align: center;
        }

        .s1 {
            color: #6C6C6C;
            font-family: "Microsoft Sans Serif", sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 14pt;
        }

        .s2 {
            color: black;
            font-family: "Microsoft Sans Serif", sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
        }

        .s3 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
        }

        h2 {
            color: black;
            font-family: "Times New Roman", serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 12pt;
            text-align: center;
        }

        p {
            color: black;
            font-family: "Times New Roman", serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 12pt;
            margin-top: 24pt;
        }

        .s4 {
            color: #4E1B06;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: none;
            font-size: 8pt;
        }
    
        .line {
             width: 100%; /* O el ancho que desees */
             height: 2px; /* El grosor de la línea */
             background-color: black; /* Color de la línea */
             position: relative; /* Para posicionar el área si es necesario */
             margin-top: 20px; /* Espacio superior si lo deseas */
        }
    </style>

</head>

<body>

    <div style="">
        {{-- storage_ path --}}
        <img style="float: left" alt="image" src="images/Image_001.jfif" />
        <img style="float: right" alt="image" src="images/Image_002.jfif" />

        <div style="margin: 2rem">
            <h1>ORGANISMO OPERADOR MUNICIPAL DEL SISTEMA DE AGUA POTABLE, ALCANTARILLADO
                Y SANEAMIENTO DE LA PAZ</h1>
        </div>
        <!--<div style="text-align: center;">
            <img alt="image" src="images/Image_005?.jfif" />
        </div>-->

        <div class="line"></div>

        <p class="s2" style="text-align: right;">FOLIO N°<b>{{ $folio }}</b> / {{ $año }}.</p>

        <p class="s3">ASUNTO: CONSTANCIA DE ANTIGÜEDAD</p>

        <br />

        <h2 style="text-align: left">A QUIEN CORRESPONDA:</h2>

        <p style="text-align: justify;">
            Por medio del presente se hace constar, que el C. <b>{{ $nombre }}</b>, con domicilio en <b>{{ $domicilio }}</b>,
            con clave de usuario <b>{{ $codigo_usuario }}</b> realizo su contrato de agua potable de uso <b>{{ $tipo_toma }}</b>
            con fecha de instalación <b>{{ $fecha_instalacion }}</b>, con clave catastral <b>{{ $clave_catastral }}</b> el cual
            se encuentra al corriente con sus pagos.
        </p>


        <p style="text-align: justify;">
            A petición del interesado y para que los usos legales convenientes, se extiende la presente en la Ciudad de
            La Paz, Baja California Sur,
            el día {{ $fecha_texto }}.
        </p>

        <br />

        <div style="text-align: center;">
            <img alt="image" src="images/firmadc-new.jfif" />
        </div>


        <h2>A T E N T A M E N T E <br>
            LIC. NEYMA LUNA SALAICES<br>
            DIRECTORA COMERCIAL</h2>

       

        <p class="s4">
            CENTRO DE ATENCIÓN TELEFÓNICA LÍNEA ÚNICA (612) 123 86 28 <br>
            TEL. CONMUTADOR (612) 123 86 00
        </p>

        <div style="text-align: center;">
            <img alt="image" src="images/Image_003.jfif" />
        </div>
    </div>

</body>

</html>
