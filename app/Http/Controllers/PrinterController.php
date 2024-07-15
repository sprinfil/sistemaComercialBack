<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\CapabilityProfile;
use Endroid\QrCode\QrCode;

class PrinterController extends Controller
{
    public function print()
    {
        try {
            // Nombre de la impresora compartida en Windows
            $connector = new WindowsPrintConnector("EPSON TM-T20");
            $printer = new Printer($connector);

            // Configurar el perfil de capacidades para la impresora
            $profile = CapabilityProfile::load("simple");

            // Iniciar la impresora con el perfil de capacidades
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);

            // Texto formateado para el ticket
            $text = "--------------------------------\n"
                  . "          TICKET DE COMPRA       \n"
                  . "--------------------------------\n"
                  . "Padre nuestro que estás en el cielo,\n"
                  . "santificado sea tu nombre; venga a\n"
                  . "nosotros tu reino; hágase tu\n"
                  . "voluntad, en la tierra como en el\n"
                  . "cielo. Danos hoy nuestro pan de\n"
                  . "cada día; perdona nuestras\n"
                  . "ofensas como también nosotros\n"
                  . "perdonamos a los que nos ofenden;\n"
                  . "no nos dejes caer en la tentación,\n"
                  . "y líbranos del mal. Amén.\n"
                  . "--------------------------------\n";

            // Imprimir el texto
            $printer->text($text);

            // Generar código QR
            $qrText = "Texto que quieres en el QR";
            $qrCode = new QrCode($qrText);
            $qrCode->setSize(80); // Tamaño del QR en la impresión
            $qrCodeImage = $qrCode->writeString();

            // Convertir la imagen del QR a EscposImage
            $escposQrImage = EscposImage::loadFromPngData($qrCodeImage);

            // Imprimir el código QR
            $printer->bitImage($escposQrImage);

            // Agregar código de barras (ejemplo de código de barras 123456789)
            $printer->setBarcodeHeight(80); // Altura del código de barras
            $printer->barcode("123456789"); // Imprimir código de barras

            // Cortar papel (si es compatible)
            $printer->cut();
            $printer->close(); // Cerrar conexión con la impresora

            return response()->json(['message' => 'Impresión enviada'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}



