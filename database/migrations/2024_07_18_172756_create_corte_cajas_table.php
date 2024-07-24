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
            $table->unsignedInteger('id_pago');
            $table->decimal('saldo_real', total: 8 , places:2);
            $table->decimal('saldo_contable' , total: 8 , places: 2);
            $table->enum('discrepancia' , ['si' , 'no']);
            $table->decimal('discrepancia_monto', total: 8 , places: 2);
            $table->dateTime('periodo'); //datetime?
            $table->enum('moneda_extranjera', ['MXN' , 'USD']);
            $table->enum('moneda_nacional', ['MXN' , 'USD']);

            $table->integer('cantidad_billete_20');
            $table->integer('cantidad_billete_50');
            $table->integer('cantidad_billete_100');
            $table->integer('cantidad_billete_200');
            $table->integer('cantidad_billete_500');
            $table->integer('cantidad_billete_1000');

            $table->integer('cantidad_moneda_1');
            $table->integer('cantidad_moneda_2');
            $table->integer('cantidad_moneda_5');
            $table->integer('cantidad_moneda_10');
            $table->integer('cantidad_moneda_20');

            $table->integer('cantidad_centavo_10');
            $table->integer('cantidad_centavo_20');
            $table->integer('cantidad_centavo_50');

            $table->integer('cantidad_billete_dolar_1');
            $table->integer('cantidad_billete_dolar_2');
            $table->integer('cantidad_billete_dolar_5');
            $table->integer('cantidad_billete_dolar_10');
            $table->integer('cantidad_billete_dolar_20');
            $table->integer('cantidad_billete_dolar_50');
            $table->integer('cantidad_billete_dolar_100');
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
