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
        Schema::create('lecturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_empleado_lecturista');
            $table->unsignedBigInteger('id_toma');
            $table->unsignedBigInteger('id_carga_trabajo');
            $table->integer('lectura');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturas');
    }
};
