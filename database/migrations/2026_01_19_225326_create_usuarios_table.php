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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('cedula')->nullable();
            $table->string('nick')->nullable();
            $table->string('password',255)->nullable();
            $table->string('correo');
            $table->string('verificado')->nullable();
            $table->string('temporal')->nullable();
            $table->unsignedInteger('id_sucursal');
            $table->foreign('id_sucursal')->references('id')->on('sucursales');
            $table->unsignedInteger('id_estado');
            $table->foreign('id_estado')->references('id')->on('estados');
            $table->unsignedInteger('id_cargo');
            $table->foreign('id_cargo')->references('id')->on('cargos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
