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
        Schema::create('abonos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pago')->unsigned()->default('1'); 
            $table->bigInteger('id_cargo')->unsigned()->default('1');
            $table->bigInteger('id_origen')->unsigned()->default('1'); 
            $table->string('modelo_origen');
            $table->decimal('total_abonado', 8, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonos');
    }
};
