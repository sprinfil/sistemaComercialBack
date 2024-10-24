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
        Schema::create('tipo_toma_aplicables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_modelo');
            $table->string('modelo_origen');
            $table->unsignedBigInteger('id_tipo_toma');
            $table->softDeletes();
            $table->timestamps();

            $table->index('id_modelo');
            $table->index('modelo_origen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_toma_aplicables');
    }
};
