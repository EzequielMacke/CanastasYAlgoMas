<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aperturas_caja', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->timestamp('abierto_at');
            $table->timestamp('cerrado_at')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aperturas_caja');
    }
};
