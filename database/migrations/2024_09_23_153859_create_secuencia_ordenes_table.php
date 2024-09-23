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
        Schema::create('secuencia_ordenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_secuencia');
            $table->unsignedBigInteger('id_toma');
            $table->integer('numero_secuencia');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secuencia_ordenes');
    }
};
