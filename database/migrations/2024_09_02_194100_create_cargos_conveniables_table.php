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
        Schema::create('cargos_conveniables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_concepto_catalogo');
            $table->unsignedBigInteger('id_convenio_catalogo');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos_conveniables');
    }
};
