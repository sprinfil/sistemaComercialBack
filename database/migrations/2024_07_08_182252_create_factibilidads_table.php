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
            //$table->unsignedBigInteger('contrato_id');
            $table->enum('estado_factible', ['no_factible', 'factible'])->default('no_factible');
            $table->double('monto_derechos_conexion')->nullable(); //Puede que cambie a double.
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
