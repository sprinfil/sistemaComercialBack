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
        Schema::create('anomalia_catalogos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("descripcion")->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->nullable();
            $table->boolean('facturable')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->index('nombre');
            $table->index('estado');
            $table->unique('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anomalia_catalogos');
    }
};
