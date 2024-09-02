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
        Schema::create('orden_trabajo_catalogos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('servicio',['CONSUMO DE AGUA POTABLE','SERV. ALCANTARILLADO','TRAT. Y SANEAMIENTO','OTRO']);
            $table->string('descripcion');
            $table->integer('vigencias');
            $table->enum('momento_cargo',['generar','asignar','concluir','No genera']);
            $table->boolean('genera_masiva');
            $table->integer('limite_ordenes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_trabajo_catalogos');
    }
};
