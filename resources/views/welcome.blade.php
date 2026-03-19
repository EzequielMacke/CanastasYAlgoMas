@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')

<div style="text-align:center; padding: 64px 0;">
    <div style="width:64px;height:64px;background:#2c2117;border-radius:18px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#f5f0eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
    </div>
    <h2 style="font-size:24px;font-weight:600;letter-spacing:-0.01em;color:#1a1208;">
        Bienvenido, {{ auth()->user()->nick }}
    </h2>
    <p style="margin-top:8px;font-size:14px;color:#a08c78;">
        Panel de administración — Canastas y Algo Más
    </p>
</div>

@endsection
