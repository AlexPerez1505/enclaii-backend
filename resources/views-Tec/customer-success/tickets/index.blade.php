@extends('layouts.app')

@section('title', 'Tickets de soporte')
@section('active', 'customer-success-tickets')
@section('header-title', 'Tickets de soporte')
@section('header-sub', 'Tickets creados por usuarios.')

@section('sidebar')
  @include('customer-success.partials.sidebar')
@endsection

@section('bottom-nav')
  @include('customer-success.partials.bottom-nav')
@endsection

@push('styles')
<style>
.tk-page{--tk-bg:#060b14;--tk-panel:#0f1629;--tk-panel-2:#131b32;--tk-border:#1e293b;--tk-border-soft:#253047;--tk-text:#e2e8f0;--tk-text-soft:#94a3b8;--tk-blue:#3b82f6;--tk-blue-soft:#1d4ed8;--tk-cyan:#06b6d4;--tk-amber:#f59e0b;--tk-green:#22c55e;--tk-slate:#64748b;--tk-radius:18px;--tk-shadow:0 10px 30px rgba(0,0,0,.25)}
.tk-page{display:grid;gap:22px;max-width:1200px;margin:0 auto}

.tk-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.tk-stat{background:var(--tk-panel);border:1px solid var(--tk-border);border-radius:var(--tk-radius);padding:20px;box-shadow:var(--tk-shadow);display:flex;align-items:center;gap:16px;position:relative;overflow:hidden;z-index:1}
.tk-stat::before{content:'';position:absolute;inset:0;border-radius:inherit;padding:1px;background:linear-gradient(135deg,rgba(59,130,246,.45),transparent 45%);-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none;z-index:2}
.tk-stat::after{content:'';position:absolute;right:-40px;top:-40px;width:140px;height:140px;border-radius:50%;filter:blur(45px);opacity:.35;pointer-events:none;z-index:-1}
.tk-stat:nth-child(1)::after{background:var(--tk-blue)}
.tk-stat:nth-child(2)::after{background:var(--tk-cyan)}
.tk-stat:nth-child(3)::after{background:var(--tk-amber)}
.tk-stat:nth-child(4)::after{background:var(--tk-green)}
.tk-stat-icon{width:48px;height:48px;border-radius:14px;display:grid;place-items:center;background:rgba(59,130,246,.18);color:var(--tk-blue);flex-shrink:0;box-shadow:0 0 20px rgba(59,130,246,.25)}
.tk-stat:nth-child(2) .tk-stat-icon{background:rgba(6,182,212,.18);color:var(--tk-cyan);box-shadow:0 0 20px rgba(6,182,212,.25)}
.tk-stat:nth-child(3) .tk-stat-icon{background:rgba(245,158,11,.18);color:var(--tk-amber);box-shadow:0 0 20px rgba(245,158,11,.25)}
.tk-stat:nth-child(4) .tk-stat-icon{background:rgba(34,197,94,.18);color:var(--tk-green);box-shadow:0 0 20px rgba(34,197,94,.25)}
.tk-stat-info{flex:1}
.tk-stat-label{font-size:13px;color:var(--tk-text-soft);margin-bottom:4px}
.tk-stat-value{font-size:28px;font-weight:800;color:var(--tk-text);line-height:1}

.tk-card{background:var(--tk-panel);border:1px solid var(--tk-border);border-radius:var(--tk-radius);box-shadow:var(--tk-shadow);overflow:hidden}
.tk-card-header{padding:22px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px;border-bottom:1px solid var(--tk-border-soft)}
.tk-card-title{display:flex;align-items:center;gap:12px}
.tk-card-title-icon{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,rgba(59,130,246,.25),rgba(37,99,235,.12));display:grid;place-items:center;color:var(--tk-blue)}
.tk-card-title-text h3{font-size:16px;font-weight:700;color:var(--tk-text);margin:0}
.tk-card-title-text p{font-size:12px;color:var(--tk-text-soft);margin:4px 0 0}
.tk-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.tk-search{position:relative}
.tk-search input{width:260px;background:var(--tk-panel-2);border:1px solid var(--tk-border);border-radius:12px;padding:10px 14px 10px 38px;color:var(--tk-text);font-size:13px;outline:none}
.tk-search input:focus{border-color:var(--tk-blue)}
.tk-search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--tk-text-soft);width:16px;height:16px;pointer-events:none}
.tk-btn{display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 16px;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;transition:all 150ms;border:1px solid transparent;text-decoration:none;white-space:nowrap}
.tk-dropdown{position:relative}
.tk-dropdown-trigger{display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 14px;border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;border:1px solid var(--tk-border);background:var(--tk-panel-2);color:var(--tk-text-soft);transition:all 150ms;white-space:nowrap}
.tk-dropdown-trigger:hover,.tk-dropdown.open .tk-dropdown-trigger{border-color:var(--tk-blue);color:var(--tk-text);box-shadow:0 0 0 3px rgba(59,130,246,.12)}
.tk-dropdown-trigger svg{transition:transform .2s}
.tk-dropdown.open .tk-dropdown-trigger svg{transform:rotate(180deg)}
.tk-dropdown-menu{display:none;position:absolute;top:calc(100% + 6px);left:0;min-width:100%;background:var(--tk-panel);border:1px solid var(--tk-border);border-radius:14px;padding:6px;box-shadow:0 16px 40px rgba(0,0,0,.4);z-index:100;animation:tkFadeIn .15s ease}
.tk-dropdown.open .tk-dropdown-menu{display:block}
@keyframes tkFadeIn{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}
.tk-dropdown-item{display:flex;align-items:center;gap:8px;padding:9px 14px;border-radius:9px;font-size:13px;font-weight:500;color:var(--tk-text-soft);cursor:pointer;transition:all .12s;white-space:nowrap}
.tk-dropdown-item:hover{background:rgba(59,130,246,.1);color:var(--tk-text)}
.tk-dropdown-item.active{background:rgba(59,130,246,.15);color:var(--tk-blue);font-weight:700}
.tk-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.tk-btn-primary{background:linear-gradient(135deg,var(--tk-blue),var(--tk-blue-soft));color:#fff;border:none}
.tk-btn-primary:hover{filter:brightness(1.1)}
.tk-btn-ghost{background:transparent;border-color:var(--tk-border);color:var(--tk-text-soft);height:auto;padding:6px 12px}
.tk-btn-ghost:hover{border-color:var(--tk-blue);color:var(--tk-blue)}

.tk-table{width:100%;border-collapse:collapse}
.tk-table thead{background:var(--tk-panel-2)}
.tk-table th{padding:14px 18px;text-align:left;font-size:11px;font-weight:700;color:var(--tk-text-soft);text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid var(--tk-border-soft)}
.tk-table td{padding:16px 18px;border-bottom:1px solid var(--tk-border-soft);vertical-align:middle}
.tk-table tbody tr{transition:background .15s}
.tk-table tbody tr:hover{background:rgba(59,130,246,.05)}
.tk-table tbody tr:last-child td{border-bottom:none}
.tk-folio{display:inline-flex;align-items:center;padding:6px 12px;background:var(--tk-panel-2);border:1px solid var(--tk-border);border-radius:10px;font-size:12px;font-weight:700;color:var(--tk-text);font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace}
.tk-user{display:flex;align-items:center;gap:12px}
.tk-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#3b82f6);display:grid;place-items:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
.tk-user-info{min-width:0}
.tk-user-name{font-size:13px;font-weight:600;color:var(--tk-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tk-user-email{font-size:11px;color:var(--tk-text-soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tk-category{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--tk-text-soft)}
.tk-asunto{font-size:13px;color:var(--tk-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px}
.tk-estado{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:99px;font-size:12px;font-weight:700;background:rgba(59,130,246,.15);color:#60a5fa}
.tk-estado::before{content:'';width:7px;height:7px;border-radius:50%;background:currentColor}
.tk-estado.nuevo{background:rgba(168,85,247,.15);color:#c084fc;animation:tk-pulse 2s infinite}
@keyframes tk-pulse{0%,100%{opacity:1}50%{opacity:.6}}
.tk-estado.abierto{background:rgba(59,130,246,.15);color:#60a5fa}
.tk-estado.en_proceso{background:rgba(245,158,11,.15);color:#fbbf24}
.tk-estado.respondido{background:rgba(16,185,129,.15);color:#4ade80}
.tk-estado.cerrado{background:rgba(148,163,184,.15);color:#94a3b8}
.tk-fecha{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--tk-text-soft)}
.tk-empty{text-align:center;padding:60px 20px;color:var(--tk-text-soft)}
.tk-pagination{padding:18px 24px;display:flex;justify-content:flex-end;gap:6px}
.tk-pagination a,.tk-pagination span{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;background:var(--tk-panel-2);border:1px solid var(--tk-border);color:var(--tk-text-soft)}
.tk-pagination a:hover{border-color:var(--tk-blue);color:var(--tk-blue)}
.tk-pagination span.active{background:var(--tk-blue);border-color:var(--tk-blue);color:#fff}

/* ===== TEMA CLARO ===== */
html[data-theme="light"] .tk-page{--tk-bg:#f8fafc;--tk-panel:#ffffff;--tk-panel-2:#f1f5f9;--tk-border:#e2e8f0;--tk-border-soft:#e2e8f0;--tk-text:#0f172a;--tk-text-soft:#64748b;--tk-shadow:0 4px 16px rgba(15,23,42,.06)}
html[data-theme="light"] .tk-stat::before{background:linear-gradient(135deg,rgba(59,130,246,.25),transparent 45%)}
html[data-theme="light"] .tk-stat::after{opacity:.18}
html[data-theme="light"] .tk-stat-icon{box-shadow:none}
html[data-theme="light"] .tk-stat:nth-child(2) .tk-stat-icon{box-shadow:none}
html[data-theme="light"] .tk-stat:nth-child(3) .tk-stat-icon{box-shadow:none}
html[data-theme="light"] .tk-stat:nth-child(4) .tk-stat-icon{box-shadow:none}
html[data-theme="light"] .tk-dropdown-menu{background:#fff;border-color:#e2e8f0;box-shadow:0 12px 32px rgba(15,23,42,.12)}
html[data-theme="light"] .tk-dropdown-item:hover{background:rgba(59,130,246,.08)}
html[data-theme="light"] .tk-dropdown-item.active{background:rgba(59,130,246,.1);color:#2563eb}
html[data-theme="light"] .tk-table tbody tr:hover{background:rgba(59,130,246,.04)}
html[data-theme="light"] .tk-avatar{background:linear-gradient(135deg,#8b5cf6,#3b82f6)}
html[data-theme="light"] .tk-estado.nuevo{background:rgba(147,51,234,.1);color:#7c3aed}
html[data-theme="light"] .tk-estado.abierto{background:rgba(37,99,235,.1);color:#2563eb}
html[data-theme="light"] .tk-estado.en_proceso{background:rgba(217,119,6,.1);color:#b45309}
html[data-theme="light"] .tk-estado.respondido{background:rgba(5,150,105,.1);color:#047857}
html[data-theme="light"] .tk-estado.cerrado{background:rgba(100,116,139,.1);color:#475569}
html[data-theme="light"] .tk-folio{background:#f8fafc;border-color:#e2e8f0;color:#1e293b}
html[data-theme="light"] .tk-pagination a,html[data-theme="light"] .tk-pagination span{background:#f8fafc;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .tk-pagination a:hover{border-color:#3b82f6;color:#3b82f6}
html[data-theme="light"] .tk-btn-ghost{border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .tk-btn-ghost:hover{border-color:#3b82f6;color:#3b82f6}
html[data-theme="light"] .tk-card-title-icon{background:rgba(59,130,246,.1)}

@media(max-width:900px){
  .tk-stats{grid-template-columns:repeat(2,1fr)}
  .tk-card-header{flex-direction:column;align-items:flex-start}
  .tk-search input{width:100%}
  .tk-table{display:block;overflow-x:auto}
  .tk-table thead{display:none}
  .tk-table tbody tr{display:block;border-bottom:1px solid var(--tk-border-soft);padding:14px}
  .tk-table td{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border:none}
  .tk-table td::before{content:attr(data-label);font-size:11px;font-weight:700;color:var(--tk-text-soft);text-transform:uppercase}
  .tk-asunto{max-width:none}
}
</style>
@endpush

@section('content')
<div class="tk-page">

  <div class="tk-stats">
    <div class="tk-stat">
      <div class="tk-stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H2V6h2a2 2 0 0 0 2-2V2"/><path d="M22 12h-2a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2V6h-2a2 2 0 0 1-2-2V2"/><path d="M7 2h10"/><path d="M7 22h10"/><rect x="7" y="6" width="10" height="12" rx="1"/></svg>
      </div>
      <div class="tk-stat-info">
        <div class="tk-stat-label">Total de tickets</div>
        <div class="tk-stat-value">{{ $stats['total'] }}</div>
      </div>
    </div>
    <div class="tk-stat">
      <div class="tk-stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
      <div class="tk-stat-info">
        <div class="tk-stat-label">Nuevos</div>
        <div class="tk-stat-value">{{ $stats['nuevos'] }}</div>
      </div>
    </div>
    <div class="tk-stat">
      <div class="tk-stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
      </div>
      <div class="tk-stat-info">
        <div class="tk-stat-label">En curso</div>
        <div class="tk-stat-value">{{ $stats['en_curso'] }}</div>
      </div>
    </div>
    <div class="tk-stat">
      <div class="tk-stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      </div>
      <div class="tk-stat-info">
        <div class="tk-stat-label">Cerrados</div>
        <div class="tk-stat-value">{{ $stats['cerrados'] }}</div>
      </div>
    </div>
  </div>

  <div class="tk-card">
    <div class="tk-card-header">
      <div class="tk-card-title">
        <div class="tk-card-title-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
        </div>
        <div class="tk-card-title-text">
          <h3>Tickets recibidos</h3>
          <p>Gestiona y da seguimiento a los tickets de soporte.</p>
        </div>
      </div>
      <div class="tk-actions">
        <div class="tk-search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" id="ticketSearch" placeholder="Buscar tickets, usuario o asunto...">
        </div>
        <div class="tk-dropdown" id="dropStatus">
          <button type="button" class="tk-dropdown-trigger" id="filterStatusTrigger">
            <span>Todos los estados</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div class="tk-dropdown-menu" id="filterStatusMenu">
            <div class="tk-dropdown-item active" data-value="">Todos los estados</div>
            <div class="tk-dropdown-item" data-value="nuevo"><span class="tk-dot" style="background:#c084fc"></span>Nuevo</div>
<<<<<<< HEAD
=======
            <div class="tk-dropdown-item" data-value="abierto"><span class="tk-dot" style="background:#60a5fa"></span>Abierto</div>
>>>>>>> origin/main
            <div class="tk-dropdown-item" data-value="en_proceso"><span class="tk-dot" style="background:#fbbf24"></span>En proceso</div>
            <div class="tk-dropdown-item" data-value="respondido"><span class="tk-dot" style="background:#4ade80"></span>Respondido</div>
            <div class="tk-dropdown-item" data-value="cerrado"><span class="tk-dot" style="background:#94a3b8"></span>Cerrado</div>
          </div>
          <input type="hidden" id="filterStatus" value="">
        </div>
        <div class="tk-dropdown" id="dropCategory">
          <button type="button" class="tk-dropdown-trigger" id="filterCategoryTrigger">
            <span>Todas las categorías</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div class="tk-dropdown-menu" id="filterCategoryMenu">
            <div class="tk-dropdown-item active" data-value="">Todas las categorías</div>
            <div class="tk-dropdown-item" data-value="facturacion">Facturación</div>
            <div class="tk-dropdown-item" data-value="tecnico">Problema técnico</div>
            <div class="tk-dropdown-item" data-value="funcion">Solicitud de función</div>
            <div class="tk-dropdown-item" data-value="como-hacer">Cómo hacer</div>
            <div class="tk-dropdown-item" data-value="otro">Otro</div>
          </div>
          <input type="hidden" id="filterCategory" value="">
        </div>
      </div>
    </div>

    @if($tickets->isEmpty())
      <div class="tk-empty">No hay tickets registrados.</div>
    @else
      <table class="tk-table" id="ticketsTable">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Usuario</th>
            <th>Categoría</th>
            <th>Asunto</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($tickets as $ticket)
          <tr data-status="{{ $ticket->status }}" data-category="{{ $ticket->category }}">
            <td data-label="Folio"><span class="tk-folio">{{ $ticket->operation_folio }}</span></td>
            <td data-label="Usuario">
              <div class="tk-user">
                <div class="tk-avatar">{{ mb_strtoupper(mb_substr($ticket->user?->name ?? '?', 0, 2)) }}</div>
                <div class="tk-user-info">
                  <div class="tk-user-name">{{ $ticket->user?->name }} {{ $ticket->user?->apellido_paterno }}</div>
                  <div class="tk-user-email">{{ $ticket->user?->email }}</div>
                </div>
              </div>
            </td>
            <td data-label="Categoría">
              <span class="tk-category">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                {{ $ticket->category }}
              </span>
            </td>
            <td data-label="Asunto"><div class="tk-asunto">{{ $ticket->subject }}</div></td>
            <td data-label="Estado">
              <span class="tk-estado {{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
            </td>
            <td data-label="Fecha">
              <span class="tk-fecha">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $ticket->created_at?->format('d/m/Y H:i') }}
              </span>
            </td>
            <td data-label="Acciones">
              <a href="{{ route('customer-success.tickets.show', $ticket) }}" class="tk-btn tk-btn-ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                Ver
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="tk-pagination">
        {{ $tickets->links() }}
      </div>
    @endif
  </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
  var input = document.getElementById('ticketSearch');
  var table = document.getElementById('ticketsTable');
  var filterStatus = document.getElementById('filterStatus');
  var filterCategory = document.getElementById('filterCategory');
  if(!input || !table) return;

  // Custom dropdown logic
  document.querySelectorAll('.tk-dropdown').forEach(function(drop){
    var trigger = drop.querySelector('.tk-dropdown-trigger');
    var menu = drop.querySelector('.tk-dropdown-menu');
    var hiddenInput = drop.querySelector('input[type="hidden"]');
    var label = trigger.querySelector('span');

    trigger.addEventListener('click', function(e){
      e.stopPropagation();
      var isOpen = drop.classList.contains('open');
      document.querySelectorAll('.tk-dropdown.open').forEach(function(d){ d.classList.remove('open'); });
      if(!isOpen) drop.classList.add('open');
    });

    menu.querySelectorAll('.tk-dropdown-item').forEach(function(item){
      item.addEventListener('click', function(e){
        e.stopPropagation();
        menu.querySelectorAll('.tk-dropdown-item').forEach(function(i){ i.classList.remove('active'); });
        item.classList.add('active');
        hiddenInput.value = item.getAttribute('data-value');
        label.textContent = item.textContent.trim();
        drop.classList.remove('open');
        applyFilters();
      });
    });
  });

  document.addEventListener('click', function(){ document.querySelectorAll('.tk-dropdown.open').forEach(function(d){ d.classList.remove('open'); }); });

  function applyFilters(){
    var term = input.value.toLowerCase();
    var status = filterStatus ? filterStatus.value : '';
    var category = filterCategory ? filterCategory.value : '';

    table.querySelectorAll('tbody tr').forEach(function(row){
      var matchText = !term || row.textContent.toLowerCase().includes(term);
      var matchStatus = !status || row.getAttribute('data-status') === status;
      var matchCategory = !category || row.getAttribute('data-category') === category;
      row.style.display = (matchText && matchStatus && matchCategory) ? '' : 'none';
    });
  }

  input.addEventListener('input', applyFilters);

  // ---- Polling: auto-actualizar tickets cada 10s ----
  var pollUrl = "{{ route('customer-success.api.tickets.poll') }}";
  var statEls = document.querySelectorAll('.tk-stat-value');

  function renderRow(t){
    return '<tr data-status="' + t.status + '" data-category="' + t.category + '">' +
      '<td data-label="Folio"><span class="tk-folio">' + t.folio + '</span></td>' +
      '<td data-label="Usuario"><div class="tk-user"><div class="tk-avatar">' + t.user_initials + '</div><div class="tk-user-info"><div class="tk-user-name">' + t.user_name + '</div><div class="tk-user-email">' + t.user_email + '</div></div></div></td>' +
      '<td data-label="Categoría"><span class="tk-category"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> ' + t.category + '</span></td>' +
      '<td data-label="Asunto"><div class="tk-asunto">' + t.subject + '</div></td>' +
      '<td data-label="Estado"><span class="tk-estado ' + t.status + '">' + t.status_label + '</span></td>' +
      '<td data-label="Fecha"><span class="tk-fecha"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> ' + t.created_at + '</span></td>' +
      '<td data-label="Acciones"><a href="' + t.url + '" class="tk-btn tk-btn-ghost"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg> Ver</a></td>' +
    '</tr>';
  }

  function pollTickets(){
    fetch(pollUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function(r){ return r.json(); })
      .then(function(data){
        if(!data.tickets) return;

        if(statEls.length >= 4 && data.stats){
          statEls[0].textContent = data.stats.total;
          statEls[1].textContent = data.stats.nuevos;
          statEls[2].textContent = data.stats.en_curso;
          statEls[3].textContent = data.stats.cerrados;
        }

        var tbody = table.querySelector('tbody');
        if(!tbody) return;

        var html = '';
        data.tickets.forEach(function(t){ html += renderRow(t); });
        tbody.innerHTML = html;

        applyFilters();
      })
      .catch(function(){});
  }

  setInterval(pollTickets, 10000);
})();
</script>
@endpush
