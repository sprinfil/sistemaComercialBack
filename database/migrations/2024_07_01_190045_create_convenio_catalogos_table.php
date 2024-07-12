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
        Schema::create('convenio_catalogos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre")->nullable();
            $table->text("descripcion")->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->nullable();
            $table->date("vigencia")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convenio_catalogos');
    }
};
