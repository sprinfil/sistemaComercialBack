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
        Schema::create('factibilidad', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contrato')->default('1');
            $table->enum('estado_factible', ['no_factible', 'factible'])->default('no_factible');
            $table->decimal('derechos_conexion' , total:8 , places:2)->nullable(); //Cambio a decimal.
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factibilidad');
    }
};
