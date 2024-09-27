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
            margin: 5px;
            max-width: 800px;
        }

        .header {
            text-align: center;
            font-weight: bold;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 20px;
            max-width: 600px;
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


.contract-info {
    width: 100%;
    margin: 20px auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    table-layout: fixed; /* Fixes the table layout */
}

thead td {
    background-color: #53be80;
    color: white;
    text-align: left;
    font-weight: bold;
    padding: 8px; /* Reduced padding */
    font-size: xx-small;
}

tbody tr:nth-child(even) {
    background-color: #f2f2f2; /* Zebra striping */
}

tbody tr:hover {
    background-color: #ddd; /* Hover effect */
}

td {
    padding: 8px; /* Reduced padding */
    border: 1px solid #ddd;
    text-align: center; /* Center-align the data */
    word-wrap: break-word; /* Allow breaking of long words */
    font-size: xx-small
}

td.dato {
    /* Additional styles for data cells if necessary */
}

thead {
    border-bottom: 2px solid #2d8059;
}
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h3>H. XVII AYUNTAMIENTO DE LA PAZ, B.C.S.</h3>
            <h4>ORGANISMO OPERADOR MUNICIPAL DEL SISTEMA DE AGUA POTABLE, ALCANTARILLADO Y SANEAMIENTO DE LA PAZ</h4>
            <h4>REPORTE DE AJUSTES</h4>
            <h5>Fecha: {{$fecha}}</h5>
        </div>

        <div class="contract-info">
            <table>
                <thead>
                    <td >Tipo de ajuste</td>
                    <td >Usuario/Código de toma</td>
                    <td >Operador</td>
                    <td >Monto ajustado</td>
                    <td >Monto total</td>
                    <td >Estado</td>
                    <td >Comentarios</td>
                    <td > Motivo de cancelación </td>
                    <td> fecha de creación </td>
                </thead>
                <tbody>
                    
                    @foreach($reporte as $ajuste)
                                    <tr class="fila">
                                        <td class="dato">{{$ajuste['ajusteCatalogo']['nombre']}}</td>
                                        <td class="dato"> {{$ajuste['dueno']['nombre'] ?? $ajuste['dueno']['codigo_toma']}} </td> 
                                        <td class="dato"> {{$ajuste['operador']['nombre']}} </td>
                                        <td class="dato"> {{$ajuste['monto_ajustado']}} </td> 
                                        <td class="dato"> {{$ajuste['monto_total']}} </td> 
                                        <td class="dato"> {{$ajuste['estado']}} </td> 
                                        <td class="dato comentario"> {{$ajuste['comentario']}} </td> 
                                        <td class="dato"> {{$ajuste['motivo_cancelacion']}} </td> 
                                        <td class="dato"> {{$ajuste['created_at']}} </td> 
                                    </tr>
                                  
                         
                    @endforeach
                </tbody>
             

            </table>

        </div>

    </div>
</body>

</html>