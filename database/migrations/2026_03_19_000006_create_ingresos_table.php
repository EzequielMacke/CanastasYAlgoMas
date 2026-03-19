<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('articulo_id')->constrained('articulos');
            $table->decimal('cantidad', 14, 4);      // en la unidad del artículo
            $table->decimal('precio', 14, 4);        // precio total pagado
            $table->decimal('precio_costo', 14, 6);  // precio / cantidad
            $table->text('observacion')->nullable();
            $table->foreignId('estado_id')->default(1)->constrained('estados');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
