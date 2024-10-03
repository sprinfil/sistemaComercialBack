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
        Schema::create('secuencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_empleado')->nullable();
            $table->unsignedBigInteger('id_secuencia_padre')->nullable();
            $table->unsignedBigInteger('id_libro');
            $table->enum('tipo_secuencia',['padre','personalizada']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secuencias');
    }
};
