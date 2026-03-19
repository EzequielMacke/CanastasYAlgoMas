<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('numero')->unique();
            $table->foreignId('vendedor_id')->constrained('vendedores');
            $table->string('cliente_nombre', 150)->nullable();
            $table->decimal('total', 14, 4)->default(0);
            $table->foreignId('estado_id')->default(1)->constrained('estados');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
