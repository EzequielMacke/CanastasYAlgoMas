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
        Schema::create('ingreso_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ingreso_id');
            $table->foreign('ingreso_id')->references('id')->on('ingresos');
            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio', 10, 2);
            $table->unsignedInteger('unidad_medida_id');
            $table->foreign('unidad_medida_id')->references('id')->on('unidad_medidas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_detalles');
    }
};
