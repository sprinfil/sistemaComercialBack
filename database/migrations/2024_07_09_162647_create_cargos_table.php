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
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_origen')->unsigned()->default('1'); 
            $table->string('modelo_origen');
            $table->bigInteger('id_dueño')->unsigned()->default('1'); 
            $table->string('modelo_dueño');
            $table->decimal('monto', 8, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado']);
            $table->date('fecha_cargo');
            $table->date('fecha_liquidacion');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
