
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
        Schema::create('retiro_cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_sesion_caja');
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
            $table->decimal('monto_total');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retiro_cajas');
    }
};
