<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('corte_cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_caja');
            $table->unsignedInteger('id_operador');
            $table->enum('estatus', ['aprobado', 'rechazado','pendiente']);
            $table->integer('cantidad_centavo_10');
            $table->integer('cantidad_centavo_20');
            $table->integer('cantidad_centavo_50');
            $table->integer('cantidad_moneda_1');
            $table->integer('cantidad_moneda_2');
            $table->integer('cantidad_moneda_5');
            $table->integer('cantidad_moneda_10');
            $table->integer('cantidad_moneda_20');
            $table->integer('cantidad_billete_20');
            $table->integer('cantidad_billete_50');
            $table->integer('cantidad_billete_100');
            $table->integer('cantidad_billete_200');
            $table->integer('cantidad_billete_500');
            $table->integer('cantidad_billete_1000');
            $table->decimal('total_efectivo_registrado', total: 8 , places:2);
            $table->decimal('total_efectivo_real', total: 8 , places:2);
            $table->decimal('total_tarjetas_credito_registrado', total: 8 , places:2);
            $table->decimal('total_tarjetas_credito_real', total: 8 , places:2);
            $table->decimal('total_tarjetas_debito_registrado', total: 8 , places:2);
            $table->decimal('total_tarjetas_debito_real', total: 8 , places:2);
            $table->decimal('total_cheques_registrado', total: 8 , places:2);
            $table->decimal('total_cheques_real', total: 8 , places:2);
            $table->decimal('total_transferencias_registrado', total: 8 , places:2);
            $table->decimal('total_transferencias_real', total: 8 , places:2);
            $table->decimal('total_documentos_registrado', total: 8 , places:2);
            $table->decimal('total_documentos_real', total: 8 , places:2);
            $table->decimal('total_registrado' , total: 8 , places: 2);
            $table->decimal('total_real', total: 8 , places:2);
            $table->enum('discrepancia' , ['si' , 'no']);
            $table->decimal('discrepancia_monto', total: 8 , places: 2);
            $table->string('descripcion')->nullable();
            $table->dateTime('fecha_corte'); //datetime?         
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corte_cajas');
    }
};
