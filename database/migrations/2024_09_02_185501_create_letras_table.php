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
        Schema::create('letras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_convenio');
            $table->enum('estado',['pendiente','saldado','cancelado']);
            $table->decimal('monto');
            $table->date('vigencia');
            $table->softDeletes();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letras');
    }
};
