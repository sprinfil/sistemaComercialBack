<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\CapabilityProfile;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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

            // Cargar e imprimir imagen (logo)
            $img = EscposImage::load(public_path('images/logo.png'), false);
            $printer->bitImage($img);

            // Texto formateado para el ticket
            $printer->text("--------------------------------\n");
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->text("          TICKET DE COMPRA       \n");
            $printer->selectPrintMode(); // Volver al modo normal
            $printer->text("--------------------------------\n");
            $text = "Padre nuestro que estás en el cielo,\n"
                  . "santificado sea tu nombre; vengan \n"
                  . "--------------------------------\n";

            // Imprimir el texto
            $printer->text($text);

            // Generar código QR más pequeño
            $qrText = "https://www.leagueofgraphs.com/es/summoner/lan/Killhatto-LAN";
            $qrCode = QrCode::create($qrText)
                ->setSize(100) // Tamaño del QR
                ->setMargin(2); // Margen alrededor del QR

            $writer = new PngWriter();
            $qrResult = $writer->write($qrCode);
            $qrImagePath = tempnam(sys_get_temp_dir(), 'qr') . '.png';
            $qrResult->saveToFile($qrImagePath);

            // Convertir la imagen del QR a EscposImage
            $escposQrImage = EscposImage::load($qrImagePath);

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






