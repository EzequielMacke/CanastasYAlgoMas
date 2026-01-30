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
        Schema::create('unidad_medidas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion');
            $table->string('abreviacion');
            $table->decimal('correccion', 8, 2)->nullable();
            $table->string('operacion_correccion')->nullable();
            $table->unsignedInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidad_medidas');
    }
};
