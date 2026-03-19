<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $fillable = ['articulo_id', 'cantidad'];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }

    /**
     * Suma cantidad al stock del artículo (crea el registro si no existe).
     */
    public static function sumar(int $articuloId, float $cantidad): void
    {
        $stock = self::firstOrCreate(
            ['articulo_id' => $articuloId],
            ['cantidad'    => 0]
        );
        $stock->increment('cantidad', $cantidad);
    }

    /**
     * Resta cantidad del stock del artículo.
     */
    public static function restar(int $articuloId, float $cantidad): void
    {
        $stock = self::firstOrCreate(
            ['articulo_id' => $articuloId],
            ['cantidad'    => 0]
        );
        $stock->decrement('cantidad', $cantidad);
    }
}
