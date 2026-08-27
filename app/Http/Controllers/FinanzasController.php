<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\Venta;
use Illuminate\Http\Request;

class FinanzasController extends Controller
{
    private const MESES = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    public function index(Request $request)
    {
        $tab     = in_array($request->query('tab'), ['gastos', 'ventas', 'ganancia']) ? $request->query('tab') : 'gastos';
        $periodo = in_array($request->query('periodo'), ['dia', 'mes', 'anio']) ? $request->query('periodo') : 'mes';
        $fecha   = $request->query('fecha', now()->toDateString());
        $mes     = (int) $request->query('mes', now()->month);
        $anio    = (int) $request->query('anio', now()->year);

        [$filas, $total, $cantidad] = match ($tab) {
            'ventas'   => $this->detalleVentas($periodo, $fecha, $mes, $anio),
            'ganancia' => $this->detalleGanancia($periodo, $fecha, $mes, $anio),
            default    => $this->detalleGastos($periodo, $fecha, $mes, $anio),
        };

        $meses = self::MESES;

        return view('finanzas.index', compact('tab', 'periodo', 'fecha', 'mes', 'anio', 'meses', 'filas', 'total', 'cantidad'));
    }

    private function aplicarPeriodo($query, string $columna, string $periodo, string $fecha, int $mes, int $anio): void
    {
        match ($periodo) {
            'dia'   => $query->whereDate($columna, $fecha),
            'mes'   => $query->whereMonth($columna, $mes)->whereYear($columna, $anio),
            'anio'  => $query->whereYear($columna, $anio),
        };
    }

    private function detalleGastos(string $periodo, string $fecha, int $mes, int $anio): array
    {
        $query = Ingreso::with(['articulo.unidadMedida'])->where('estado_id', 1);
        $this->aplicarPeriodo($query, 'fecha', $periodo, $fecha, $mes, $anio);

        $registros = $query->orderBy('fecha')->orderBy('id')->get();

        return [$registros, $registros->sum('precio'), $registros->count()];
    }

    private function detalleVentas(string $periodo, string $fecha, int $mes, int $anio): array
    {
        $query = Venta::with('vendedor')->where('estado_id', 1);
        $this->aplicarPeriodo($query, 'created_at', $periodo, $fecha, $mes, $anio);

        $registros = $query->orderBy('created_at')->get();

        return [$registros, $registros->sum('total'), $registros->count()];
    }

    private function detalleGanancia(string $periodo, string $fecha, int $mes, int $anio): array
    {
        $query = Venta::with(['detalles.articulo.precioCosto'])->where('estado_id', 1);
        $this->aplicarPeriodo($query, 'created_at', $periodo, $fecha, $mes, $anio);

        $ventas = $query->orderBy('created_at')->get();

        $filas = $ventas->map(function ($venta) {
            $costo = $venta->detalles->sum(
                fn ($detalle) => $detalle->cantidad * ($detalle->articulo->precioCosto->costo ?? 0)
            );

            return [
                'venta'    => $venta,
                'ventas'   => (float) $venta->total,
                'costo'    => (float) $costo,
                'ganancia' => (float) $venta->total - $costo,
            ];
        });

        return [$filas, $filas->sum('ganancia'), $ventas->count()];
    }
}
