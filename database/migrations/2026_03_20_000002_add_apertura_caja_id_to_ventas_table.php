<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId('apertura_caja_id')->nullable()->after('vendedor_id')
                ->constrained('aperturas_caja')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['apertura_caja_id']);
            $table->dropColumn('apertura_caja_id');
        });
    }
};
