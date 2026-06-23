@extends('layouts.app')

@section('title', 'Todos los reportes')
@section('active', 'ia-reportes')
@section('header-title', 'Todos los reportes')
@section('header-sub')
  Listado completo de reportes generados por IA
@endsection

@push('styles')
<style>
/* ============ TODOS LOS REPORTES ============ */
.rp-top{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:16px;flex-wrap:wrap}
.rp-search{position:relative;flex:1;max-width:340px}
.rp-search input{width:100%;padding:10px 12px 10px 38px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel);color:var(--txt);font:inherit;font-size:13.5px}
.rp-search input::placeholder{color:var(--off)}
.rp-search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--txt-soft)}
.rp-back{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);background:var(--panel-2);font-weight:600;font-size:13.5px;transition:background-color .15s}
.rp-back svg{width:16px;height:16px}
@media (hover:hover){.rp-back:hover{background:rgba(110,160,255,.1)}}

table.tbl{width:100%;border-collapse:collapse;font-size:14px}
.tbl th{text-align:left;font-size:12.5px;font-weight:600;color:var(--txt-soft);padding:8px 12px;border-bottom:1px solid var(--stroke)}
.tbl td{padding:10px 12px;border-bottom:1px solid rgba(110,160,255,.08)}
.tbl tr:last-child td{border-bottom:0}
.tbl tbody tr{transition:background-color 150ms ease}
@media (hover:hover) and (pointer:fine){.tbl tbody tr:hover{background:rgba(110,160,255,.05)}}
.pat{display:flex;align-items:center;gap:10px;font-weight:600}
.pat .mini{width:30px;height:30px;border-radius:50%;background:rgba(46,123,246,.2);border:1px solid var(--stroke-strong);display:grid;place-items:center;font-size:11px;font-weight:700;color:var(--cyan)}
.tbl .date{line-height:1.3}
.tbl .date small{display:block;color:var(--txt-soft);font-size:11.5px}
.conf{display:flex;align-items:center;gap:8px}
.conf .ring{position:relative;width:36px;height:36px;flex:none}
.conf .ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.conf .ring circle{fill:none;stroke-width:5;stroke-linecap:round}
.conf .ring .track{stroke:rgba(110,160,255,.14)}
.conf .ring .val{stroke:var(--cyan)}
.conf .ring span{position:absolute;inset:0;display:grid;place-items:center;font-size:10.5px;font-weight:700}
.row-actions{display:flex;align-items:center;gap:6px}
.row-actions button,.row-actions a{width:30px;height:30px;display:grid;place-items:center;border-radius:8px;color:var(--txt-soft);transition:color .15s,background-color .15s}
@media (hover:hover) and (pointer:fine){.row-actions button:hover,.row-actions a:hover{color:var(--cyan);background:rgba(56,199,244,.1)}}
.row-actions svg{width:17px;height:17px}
</style>
@endpush

@section('content')

  <div class="rp-top">
    <div class="rp-search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" placeholder="Buscar reporte o paciente...">
    </div>
    <a class="rp-back" href="{{ route('ia-reportes') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  <article class="card rise d2">
    <table class="tbl">
      <thead>
        <tr>
          <th>Paciente</th><th>Estudio</th><th>Fecha</th><th>Estado</th><th>Confianza IA</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @php
          $reportes = [
            ['MG','María González','Colonoscopia','08/05/2024','10:30 AM','wait','Pendiente',92],
            ['JL','Jorge López','Endoscopia','08/05/2024','09:15 AM','wait','Pendiente',88],
            ['AR','Ana Ramírez','Gastroscopia','07/05/2024','16:40 PM','wait','Pendiente',95],
            ['PT','Pedro Torres','Colonoscopia','07/05/2024','11:05 AM','done','Completado',99],
            ['LM','Laura Méndez','Endoscopia','06/05/2024','13:20 PM','done','Completado',97],
            ['CS','Carlos Sánchez','Gastroscopia','06/05/2024','08:45 AM','done','Completado',91],
            ['RV','Rosa Vega','Colonoscopia','05/05/2024','15:10 PM','urgent','Urgente',86],
            ['DM','Diego Morales','Endoscopia','05/05/2024','10:00 AM','done','Completado',94],
            ['SG','Sofía Guzmán','Gastroscopia','04/05/2024','12:30 PM','wait','Pendiente',90],
            ['HT','Héctor Trujillo','Colonoscopia','04/05/2024','09:50 AM','done','Completado',98],
          ];
        @endphp
        @foreach($reportes as $r)
          @php $dash = 113.1; $off = $dash - ($dash * $r[7] / 100); @endphp
          <tr>
            <td><span class="pat"><span class="mini">{{ $r[0] }}</span>{{ $r[1] }}</span></td>
            <td>{{ $r[2] }}</td>
            <td class="date">{{ $r[3] }} <small>{{ $r[4] }}</small></td>
            <td><span class="chip {{ $r[5] }}">{{ $r[6] }}</span></td>
            <td>
              <span class="conf">
                <span class="ring">
                  <svg viewBox="0 0 44 44"><circle class="track" cx="22" cy="22" r="18"/><circle class="val" cx="22" cy="22" r="18" stroke-dasharray="113.1" stroke-dashoffset="{{ $off }}"/></svg>
                  <span>{{ $r[7] }}%</span>
                </span>
              </span>
            </td>
            <td>
              <div class="row-actions">
                <a href="{{ route('ia-reportes.ver') }}" aria-label="Ver"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                <button aria-label="Descargar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button>
                <a href="{{ route('ia-reportes.editar') }}" aria-label="Editar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </article>

@endsection
