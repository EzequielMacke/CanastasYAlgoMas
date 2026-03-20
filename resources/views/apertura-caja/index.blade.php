@extends('layouts.app')

@section('titulo', 'Apertura y Cierre de Caja')

@section('contenido')

<div class="section-header">
    <h1>Apertura y Cierre de Caja</h1>
</div>

{{-- Estado actual --}}
@if($activa)
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:24px 28px;margin-bottom:24px;border-left:4px solid #4caf7d;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;background:#edf7f1;border-radius:10px;">
                    <i data-lucide="lock-open" style="width:20px;height:20px;color:#4caf7d;"></i>
                </span>
                <div>
                    <div style="font-weight:600;font-size:15px;color:#2c2117;">Caja abierta</div>
                    <div style="font-size:12px;color:#a08c78;margin-top:2px;">
                        Apertura del {{ $activa->fecha->format('d/m/Y') }}
                        a las {{ $activa->abierto_at->format('H:i') }}
                        &nbsp;·&nbsp; Ventas registradas: <strong>{{ $activa->ventas()->count() }}</strong>
                        &nbsp;·&nbsp; Total: <strong>Gs. {{ number_format($activa->ventas()->where('estado_id', 1)->sum('total'), 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <button onclick="document.getElementById('modal-cierre').style.display='flex'"
                    class="btn btn-danger">
                <i data-lucide="lock" style="width:15px;height:15px;"></i>
                Cerrar caja
            </button>
        </div>
    </div>

    {{-- Modal cierre --}}
    <div id="modal-cierre"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:1000;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:12px;padding:32px;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <h2 style="font-size:17px;font-weight:600;color:#2c2117;margin-bottom:6px;">Cerrar caja</h2>
            <p style="font-size:13px;color:#a08c78;margin-bottom:24px;">
                Apertura del {{ $activa->fecha->format('d/m/Y') }} — {{ $activa->ventas()->count() }} ventas registradas
            </p>
            <form method="POST" action="{{ route('apertura-caja.cerrar', $activa) }}">
                @csrf
                <div style="background:#f5f0eb;border-radius:8px;padding:14px 16px;margin-bottom:16px;font-size:13px;color:#6b5744;">
                    <strong>Total en ventas:</strong>
                    Gs. {{ number_format($activa->ventas()->where('estado_id', 1)->sum('total'), 0, ',', '.') }}
                    ({{ $activa->ventas()->count() }} ventas registradas)
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:#6b5744;margin-bottom:6px;">
                        Observaciones
                    </label>
                    <textarea name="observaciones" rows="2" placeholder="Opcional…"
                              style="width:100%;border:1px solid #d8cec4;border-radius:7px;padding:9px 12px;font-size:14px;color:#2c2117;resize:vertical;outline:none;font-family:inherit;">{{ $activa->observaciones }}</textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="document.getElementById('modal-cierre').style.display='none'"
                            class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i data-lucide="lock" style="width:14px;height:14px;"></i>
                        Confirmar cierre
                    </button>
                </div>
            </form>
        </div>
    </div>

@else
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:24px 28px;margin-bottom:24px;border-left:4px solid #e07b54;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="display:flex;align-items:center;justify-content:center;width:40px;height:40px;background:#fff5f0;border-radius:10px;">
                    <i data-lucide="lock" style="width:20px;height:20px;color:#e07b54;"></i>
                </span>
                <div>
                    <div style="font-weight:600;font-size:15px;color:#2c2117;">Caja cerrada</div>
                    <div style="font-size:12px;color:#a08c78;margin-top:2px;">No hay una apertura activa. Abra la caja para registrar ventas.</div>
                </div>
            </div>
            <button onclick="document.getElementById('modal-apertura').style.display='flex'"
                    class="btn btn-primary">
                <i data-lucide="lock-open" style="width:15px;height:15px;"></i>
                Abrir caja
            </button>
        </div>
    </div>

    {{-- Modal apertura --}}
    <div id="modal-apertura"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:1000;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:12px;padding:32px;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <h2 style="font-size:17px;font-weight:600;color:#2c2117;margin-bottom:6px;">Abrir caja</h2>
            <p style="font-size:13px;color:#a08c78;margin-bottom:24px;">{{ now()->format('d/m/Y') }}</p>
            <form method="POST" action="{{ route('apertura-caja.abrir') }}">
                @csrf
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:12px;font-weight:500;color:#6b5744;margin-bottom:6px;">
                        Observaciones
                    </label>
                    <textarea name="observaciones" rows="2" placeholder="Opcional…"
                              style="width:100%;border:1px solid #d8cec4;border-radius:7px;padding:9px 12px;font-size:14px;color:#2c2117;resize:vertical;outline:none;font-family:inherit;"></textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="document.getElementById('modal-apertura').style.display='none'"
                            class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="lock-open" style="width:14px;height:14px;"></i>
                        Confirmar apertura
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- Historial --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0ebe4;display:flex;align-items:center;gap:8px;">
        <i data-lucide="history" style="width:16px;height:16px;color:#a08c78;"></i>
        <span style="font-weight:600;font-size:14px;">Historial de aperturas</span>
    </div>

    @if($aperturas->isEmpty())
        <div class="empty">
            <i data-lucide="calendar-x" style="width:36px;height:36px;"></i>
            <span>No hay aperturas registradas.</span>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th style="width:110px;">Fecha</th>
                    <th style="width:100px;">Apertura</th>
                    <th style="width:100px;">Cierre</th>
                    <th style="text-align:center;width:70px;">Ventas</th>
                    <th style="text-align:right;">Total ventas</th>
                    <th style="width:110px;">Estado</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($aperturas as $ap)
                <tr>
                    <td><span class="badge-id">#{{ $ap->id }}</span></td>
                    <td>{{ $ap->fecha->format('d/m/Y') }}</td>
                    <td style="color:#6b5744;">{{ $ap->abierto_at->format('H:i') }}</td>
                    <td style="color:#6b5744;">{{ $ap->cerrado_at ? $ap->cerrado_at->format('H:i') : '—' }}</td>
                    <td style="text-align:center;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;background:#f5f0eb;color:#6b5744;border-radius:10px;font-size:11px;font-weight:600;padding:2px 8px;">
                            {{ $ap->ventas()->count() }}
                        </span>
                    </td>
                    <td style="text-align:right;font-weight:600;">
                        Gs. {{ number_format($ap->ventas()->where('estado_id', 1)->sum('total'), 0, ',', '.') }}
                    </td>
                    <td>
                        @if($ap->estaAbierta())
                            <span style="display:inline-flex;align-items:center;gap:5px;background:#edf7f1;color:#2e7d5a;border-radius:20px;font-size:11px;font-weight:600;padding:3px 10px;">
                                <i data-lucide="circle" style="width:7px;height:7px;fill:#4caf7d;stroke:none;"></i>
                                Abierta
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:5px;background:#f5f0eb;color:#a08c78;border-radius:20px;font-size:11px;font-weight:600;padding:3px 10px;">
                                <i data-lucide="circle" style="width:7px;height:7px;fill:#a08c78;stroke:none;"></i>
                                Cerrada
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('apertura-caja.show', $ap) }}" class="btn btn-secondary btn-sm">
                            <i data-lucide="eye" style="width:13px;height:13px;"></i>
                            Ver
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
