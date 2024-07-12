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
        Schema::create('operadores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_user")->nullable();
            $table->string("codigo_empleado")->nullable();
            $table->string("nombre");
            $table->string("apellido_paterno");
            $table->string("apellido_materno");
            $table->string("CURP",18);
            $table->date("fecha_nacimiento");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operadores');
    }
};
