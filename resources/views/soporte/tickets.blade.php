@extends('layouts.app')
@section('active', 'soporte')
@section('title', 'Mis Tickets')
@section('header-title', 'Tickets')
@section('header-sub', '¿Cómo podemos ayudarte hoy?')

@push('styles')
<style>
/* ── Tickets layout ── */
.tkt-grid{display:grid;grid-template-columns:1fr;gap:24px;align-items:start;min-width:0}
.tkt-main{display:flex;flex-direction:column;gap:24px;min-width:0}

/* Card base */
.tkt-card{background:linear-gradient(160deg,var(--panel-2),var(--panel-3));border:1px solid var(--stroke-strong);border-radius:18px;box-shadow:0 12px 40px rgba(0,0,0,.3),inset 0 1px 0 rgba(255,255,255,.03);overflow:hidden;min-width:0}
.tkt-card-header{padding:22px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px;border-bottom:1px solid var(--stroke);background:linear-gradient(135deg,rgba(59,130,246,.06),rgba(139,92,246,.04))}
.tkt-card-title{display:flex;align-items:center;gap:14px;min-width:0}
.tkt-card-icon{width:44px;height:44px;border-radius:13px;background:linear-gradient(135deg,rgba(59,130,246,.3),rgba(139,92,246,.18));display:grid;place-items:center;color:var(--blue);flex-shrink:0;box-shadow:0 4px 14px rgba(59,130,246,.25)}
.tkt-card-icon svg{filter:drop-shadow(0 0 6px rgba(59,130,246,.4))}
.tkt-card-title h2{font-size:17px;font-weight:800;margin:0;color:var(--txt);letter-spacing:-.01em}
.tkt-card-title p{font-size:12px;color:var(--txt-soft);margin:5px 0 0}
.tkt-card .sub{font-size:13px;color:var(--txt-soft);margin:0;padding:16px 24px}
.tkt-new-ticket{display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 18px;border-radius:11px;background:linear-gradient(135deg,var(--blue),#7c3aed);color:#fff;font-size:12px;font-weight:700;text-decoration:none;white-space:nowrap;box-shadow:0 4px 16px rgba(59,130,246,.35);transition:transform .15s,box-shadow .15s}
.tkt-new-ticket:hover{transform:translateY(-1px);box-shadow:0 6px 22px rgba(59,130,246,.5)}

/* Tabla tickets activos */
.tkt-table-wrap{max-width:100%;overflow-x:auto}
.tkt-table{width:100%;border-collapse:collapse;font-size:13px;table-layout:fixed}
.tkt-table thead{background:linear-gradient(180deg,rgba(15,23,42,.8),rgba(15,23,42,.5))}
.tkt-table th{
  text-align:left;padding:14px 16px;font-size:10px;font-weight:800;
  color:#818cf8;text-transform:uppercase;letter-spacing:.06em;
  border-bottom:2px solid rgba(99,102,241,.15);
}
.tkt-table td{padding:14px 16px;border-bottom:1px solid var(--stroke);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;transition:background .15s;vertical-align:middle}
.tkt-table th:first-child,.tkt-table td:first-child{width:64px;text-align:left}
.tkt-table th:nth-child(2),.tkt-table td:nth-child(2){width:240px;max-width:240px}
.tkt-table th:nth-child(3),.tkt-table td:nth-child(3){width:160px}
.tkt-table th:nth-child(4),.tkt-table td:nth-child(4){width:120px}
.tkt-table th:last-child,.tkt-table td:last-child{width:200px;text-align:right}
.tkt-table tr:last-child td{border-bottom:0}
.tkt-table tbody tr{transition:background .15s}
.tkt-table tbody tr:hover td{background:linear-gradient(90deg,rgba(99,102,241,.08),rgba(59,130,246,.04))}
.tkt-id{color:#a5b4fc;font-weight:800;font-size:12px;font-family:ui-monospace,SFMono-Regular,Menlo,monospace}
.tkt-title{display:block;width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:600;color:var(--txt)}
.tkt-category{display:inline-flex;align-items:center;gap:7px;max-width:100%;font-size:11px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding:4px 10px;border-radius:8px}
.tkt-cat-facturacion{background:rgba(168,85,247,.12);color:#c4b5fd;border:1px solid rgba(168,85,247,.22)}
.tkt-cat-tecnico{background:rgba(239,68,68,.12);color:#fca5a5;border:1px solid rgba(239,68,68,.22)}
.tkt-cat-funcion{background:rgba(34,197,94,.12);color:#86efac;border:1px solid rgba(34,197,94,.22)}
.tkt-cat-como-hacer{background:rgba(245,158,11,.12);color:#fcd34d;border:1px solid rgba(245,158,11,.22)}
.tkt-cat-otro{background:rgba(100,116,139,.12);color:#cbd5e1;border:1px solid rgba(100,116,139,.22)}
.tkt-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:99px;font-size:11px;font-weight:700}
.tkt-badge::before{content:'';width:6px;height:6px;border-radius:50%;background:currentColor;box-shadow:0 0 8px currentColor}
.tkt-badge.pendiente{background:rgba(245,158,11,.15);color:#fbbf24;border:1px solid rgba(245,158,11,.25)}
.tkt-badge.resuelto{background:rgba(34,197,94,.15);color:#4ade80;border:1px solid rgba(34,197,94,.25)}
.tkt-table th:last-child,.tkt-table td:last-child{width:72px;text-align:center}
.tkt-table td:last-child{overflow:visible}
.tkt-menu{position:relative;display:inline-block}
.tkt-menu-btn{width:32px;height:32px;border-radius:8px;border:1px solid var(--stroke);background:var(--panel);color:var(--txt-soft);display:grid;place-items:center;cursor:pointer;transition:all .15s}
.tkt-menu-btn:hover{border-color:var(--blue);color:var(--blue);background:rgba(59,130,246,.1)}
.tkt-menu-dropdown{position:absolute;top:calc(100% + 6px);right:0;z-index:20;min-width:190px;background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:12px;box-shadow:0 12px 30px rgba(0,0,0,.35);padding:6px;display:none;flex-direction:column;gap:4px}
.tkt-menu-dropdown.open{display:flex}
.tkt-menu-dropdown a,.tkt-menu-dropdown span{display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:8px;font-size:12px;font-weight:600;color:var(--txt);text-decoration:none;transition:background .15s}
.tkt-menu-dropdown a:hover{background:var(--hover-bg)}
.tkt-menu-dropdown span.disabled{color:var(--txt-soft);opacity:.5;cursor:not-allowed}
.tkt-menu-dropdown a.resolved{color:#4ade80}
.tkt-menu-dropdown a.resolved:hover{background:rgba(74,222,128,.1)}
.tkt-prioridad{display:flex;align-items:center;gap:6px}
.tkt-prioridad .dot{width:8px;height:8px;border-radius:50%}
.tkt-prioridad .dot.alta{background:#f87171;box-shadow:0 0 8px rgba(248,113,113,.5)}
.tkt-prioridad .dot.media{background:#fbbf24;box-shadow:0 0 8px rgba(251,191,36,.5)}
.tkt-prioridad .dot.baja{background:#60a5fa;box-shadow:0 0 8px rgba(96,165,250,.5)}
.tkt-arrow{color:var(--txt-soft);font-size:14px}
.tkt-ver-todos{font-size:13px;color:var(--blue);text-decoration:none;display:inline-block;margin-top:12px}
.tkt-ver-todos:hover{text-decoration:underline}

.tkt-section-title{display:flex;align-items:center;gap:8px;margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid var(--stroke);font-size:14px;font-weight:700;color:var(--blue)}

/* Filters */
.tkt-search{display:flex;align-items:center;gap:10px;padding:14px 24px;border-bottom:1px solid var(--stroke);background:rgba(59,130,246,.04);flex-wrap:wrap}
.tkt-search svg{color:var(--txt-soft);flex-shrink:0}
.tkt-search input{flex:1;min-width:200px;background:transparent;border:none;color:var(--txt);font-size:14px;outline:none}
.tkt-search input::placeholder{color:var(--txt-soft)}
.tkt-filter select{height:34px;padding:0 28px 0 12px;border-radius:9px;border:1px solid var(--stroke);background:var(--panel);color:var(--txt);font-size:13px;font-weight:600;outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center}
.tkt-filter select:focus{border-color:var(--blue)}
.tkt-search-clear{display:flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;color:var(--txt-soft);text-decoration:none;font-size:18px;font-weight:700}
.tkt-search-clear:hover{background:var(--stroke);color:var(--txt)}

/* Pagination */
.tkt-pagination{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 24px;border-top:1px solid var(--stroke)}
.tkt-page-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:9px;border:1px solid var(--stroke);background:var(--panel);color:var(--txt);font-size:12px;font-weight:700;text-decoration:none;transition:all .15s}
.tkt-page-btn:hover:not(.disabled){border-color:var(--blue);color:var(--blue);background:rgba(59,130,246,.1)}
.tkt-page-btn.disabled{opacity:.5;cursor:not-allowed;background:rgba(100,116,139,.08);color:var(--txt-soft)}
.tkt-page-info{font-size:12px;color:var(--txt-soft)}

/* Empty state */
.tkt-empty{display:flex;flex-direction:column;align-items:center;gap:14px;padding:48px 24px;text-align:center}
.tkt-empty-icon{width:56px;height:56px;border-radius:50%;background:rgba(99,102,241,.1);display:grid;place-items:center;color:#818cf8}
.tkt-empty-text{font-size:14px;color:var(--txt-soft)}
.tkt-empty-link{font-size:13px;font-weight:700;color:var(--blue);text-decoration:none}
.tkt-empty-link:hover{text-decoration:underline}

@media(max-width:640px){
  .tkt-card{padding:16px}
  .tkt-table{min-width:680px}
}

/* ===== TEMA CLARO ===== */
html[data-theme="light"] .tkt-card{background:linear-gradient(160deg,#fff,#f8fafc);border-color:#e2e8f0;box-shadow:0 8px 24px rgba(15,23,42,.08)}
html[data-theme="light"] .tkt-card-header{background:linear-gradient(135deg,rgba(59,130,246,.08),rgba(139,92,246,.05));border-color:#e2e8f0}
html[data-theme="light"] .tkt-card-icon{background:linear-gradient(135deg,rgba(59,130,246,.2),rgba(139,92,246,.12));color:#2563eb;box-shadow:0 4px 12px rgba(37,99,235,.15)}
html[data-theme="light"] .tkt-card-icon svg{filter:drop-shadow(0 0 3px rgba(37,99,235,.25))}
html[data-theme="light"] .tkt-card-title h2{color:#0f172a}
html[data-theme="light"] .tkt-card-title p{color:#64748b}
html[data-theme="light"] .tkt-card .sub{color:#64748b}
html[data-theme="light"] .tkt-new-ticket{background:linear-gradient(135deg,#2563eb,#7c3aed);box-shadow:0 4px 16px rgba(37,99,235,.25)}
html[data-theme="light"] .tkt-table thead{background:linear-gradient(180deg,#f8fafc,#f1f5f9)}
html[data-theme="light"] .tkt-table th{color:#4f46e5;border-bottom-color:rgba(79,70,229,.15)}
html[data-theme="light"] .tkt-table td{border-bottom-color:#e2e8f0}
html[data-theme="light"] .tkt-table tbody tr:hover td{background:linear-gradient(90deg,rgba(59,130,246,.06),rgba(37,99,235,.03))}
html[data-theme="light"] .tkt-id{color:#2563eb}
html[data-theme="light"] .tkt-title{color:#0f172a}
html[data-theme="light"] .tkt-cat-facturacion{background:rgba(168,85,247,.1);color:#7c3aed;border-color:rgba(168,85,247,.2)}
html[data-theme="light"] .tkt-cat-tecnico{background:rgba(239,68,68,.1);color:#dc2626;border-color:rgba(239,68,68,.2)}
html[data-theme="light"] .tkt-cat-funcion{background:rgba(34,197,94,.1);color:#16a34a;border-color:rgba(34,197,94,.2)}
html[data-theme="light"] .tkt-cat-como-hacer{background:rgba(245,158,11,.1);color:#d97706;border-color:rgba(245,158,11,.2)}
html[data-theme="light"] .tkt-cat-otro{background:rgba(100,116,139,.1);color:#475569;border-color:rgba(100,116,139,.2)}
html[data-theme="light"] .tkt-badge.pendiente{background:rgba(245,158,11,.1);color:#b45309;border-color:rgba(245,158,11,.2)}
html[data-theme="light"] .tkt-badge.resuelto{background:rgba(34,197,94,.1);color:#15803d;border-color:rgba(34,197,94,.2)}
html[data-theme="light"] .tkt-menu-btn{background:#fff;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .tkt-menu-btn:hover{border-color:#2563eb;color:#2563eb;background:rgba(37,99,235,.08)}
html[data-theme="light"] .tkt-menu-dropdown{background:#fff;border-color:#e2e8f0;box-shadow:0 8px 24px rgba(15,23,42,.1)}
html[data-theme="light"] .tkt-menu-dropdown a,html[data-theme="light"] .tkt-menu-dropdown span{color:#0f172a}
html[data-theme="light"] .tkt-menu-dropdown a:hover{background:#f1f5f9}
html[data-theme="light"] .tkt-menu-dropdown a.resolved{color:#15803d}
html[data-theme="light"] .tkt-menu-dropdown a.resolved:hover{background:rgba(34,197,94,.08)}
html[data-theme="light"] .tkt-empty-icon{background:rgba(99,102,241,.1);color:#4f46e5}
html[data-theme="light"] .tkt-empty-text{color:#64748b}
html[data-theme="light"] .tkt-ver-todos{color:#2563eb}
html[data-theme="light"] .tkt-pagination{border-color:#e2e8f0}
html[data-theme="light"] .tkt-page-btn{background:#fff;border-color:#e2e8f0;color:#0f172a}
html[data-theme="light"] .tkt-page-btn:hover:not(.disabled){border-color:#2563eb;color:#2563eb;background:rgba(37,99,235,.08)}
html[data-theme="light"] .tkt-page-btn.disabled{background:#f1f5f9;color:#94a3b8}
html[data-theme="light"] .tkt-page-info{color:#64748b}
html[data-theme="light"] .tkt-search{background:rgba(37,99,235,.04);border-color:#e2e8f0}
html[data-theme="light"] .tkt-search input{color:#0f172a}
html[data-theme="light"] .tkt-search input::placeholder{color:#94a3b8}
html[data-theme="light"] .tkt-filter select{background:#fff;border-color:#e2e8f0;color:#0f172a;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E")}
html[data-theme="light"] .tkt-filter select:focus{border-color:#2563eb}
html[data-theme="light"] .tkt-search-clear{color:#64748b}
html[data-theme="light"] .tkt-search-clear:hover{background:#e2e8f0;color:#0f172a}

</style>
@endpush

@section('content')
<div class="tkt-grid">

  {{-- ============ COLUMNA PRINCIPAL ============ --}}
  <div class="tkt-main">

    <div class="tkt-card">
      <div class="tkt-card-header">
        <div class="tkt-card-title">
          <div class="tkt-card-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          </div>
          <div>
            <h2>Mis tickets</h2>
            <p>Consulta el estado y las respuestas de soporte.</p>
          </div>
        </div>
        <a class="tkt-new-ticket" href="{{ route('soporte') }}">Crear ticket</a>
      </div>

      @php
        $categoryLabels = ['facturacion'=>'Facturación','tecnico'=>'Problema técnico','funcion'=>'Solicitud de función','como-hacer'=>'Cómo hacer','otro'=>'Otro'];
      @endphp
      <form class="tkt-search" id="tktFilterForm" action="{{ route('soporte.tickets') }}" method="GET">
        <span style="color:var(--txt-soft);font-size:13px;font-weight:600">Filtrar por:</span>
        <label class="tkt-filter">
          <select name="category">
            <option value="">Todas las categorías</option>
            @foreach($categoryLabels as $key => $label)
              <option value="{{ $key }}" {{ ($category ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </label>
        <label class="tkt-filter">
          <select name="status">
            <option value="">Todos los estados</option>
            <option value="pendiente" {{ ($status ?? '') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="resuelto" {{ ($status ?? '') === 'resuelto' ? 'selected' : '' }}>Resuelto</option>
          </select>
        </label>
        @if(!empty($category) || !empty($status))
          <a href="{{ route('soporte.tickets') }}" class="tkt-search-clear" aria-label="Limpiar">×</a>
        @endif
      </form>

      <div>
        <p class="sub">Tus tickets creados recientemente.</p>

        <div class="tkt-table-wrap">
          <table class="tkt-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($tickets as $ticket)
                @php
                  $catLabel = $categoryLabels[$ticket->category] ?? ucfirst(str_replace('-',' ',$ticket->category));
                  $isResolved = in_array($ticket->status, ['respondido', 'resuelto', 'cerrado']);
                @endphp
                <tr>
                  <td class="tkt-id">#{{ $ticket->id }}</td>
                  <td title="{{ $ticket->subject }}"><span class="tkt-title">{{ \Illuminate\Support\Str::limit($ticket->subject, 35) }}</span></td>
                  <td title="{{ $catLabel }}"><span class="tkt-category tkt-cat-{{ str_replace(['-','_'], '-', $ticket->category) }}">{{ $catLabel }}</span></td>
                  <td><span class="tkt-badge {{ $isResolved ? 'resuelto' : 'pendiente' }}">{{ $isResolved ? 'Resuelto' : 'Pendiente' }}</span></td>
                  <td>
                    <div class="tkt-menu">
                      <button type="button" class="tkt-menu-btn" aria-label="Acciones">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
                      </button>
                      <div class="tkt-menu-dropdown">
                        <a href="{{ route('soporte.tickets.show', $ticket) }}">
                          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                          Mi ticket enviado
                        </a>
                        @if($isResolved)
                          <a class="resolved" href="{{ route('soporte.tickets.show', $ticket) }}#respuesta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Ticket resuelto
                          </a>
                        @else
                          <span class="disabled">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Ticket resuelto
                          </span>
                        @endif
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5">
                    <div class="tkt-empty">
                      <div class="tkt-empty-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                      </div>
                      <span class="tkt-empty-text">No tienes tickets creados aún.</span>
                      <a class="tkt-empty-link" href="{{ route('soporte') }}">Crear mi primer ticket →</a>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($tickets->hasPages())
        <div class="tkt-pagination">
          <a class="tkt-page-btn {{ $tickets->onFirstPage() ? 'disabled' : '' }}" href="{{ $tickets->previousPageUrl() }}">← Anterior</a>
          <span class="tkt-page-info">Página {{ $tickets->currentPage() }} de {{ $tickets->lastPage() }}</span>
          <a class="tkt-page-btn {{ !$tickets->hasMorePages() ? 'disabled' : '' }}" href="{{ $tickets->nextPageUrl() }}">Siguiente →</a>
        </div>
        @endif
      </div>
    </div>

  </div>

</div>

@endsection

@push('scripts')
<script>
(function(){
  var menuBtns = document.querySelectorAll('.tkt-menu-btn');
  menuBtns.forEach(function(btn){
    btn.addEventListener('click', function(e){
      e.stopPropagation();
      var dropdown = this.nextElementSibling;
      var isOpen = dropdown.classList.contains('open');
      document.querySelectorAll('.tkt-menu-dropdown.open').forEach(function(d){ d.classList.remove('open'); });
      if(!isOpen) dropdown.classList.add('open');
    });
  });
  document.addEventListener('click', function(){
    document.querySelectorAll('.tkt-menu-dropdown.open').forEach(function(d){ d.classList.remove('open'); });
  });

  var filterForm = document.getElementById('tktFilterForm');
  var searchInput = filterForm && filterForm.querySelector('input[name="search"]');
  var selects = filterForm ? filterForm.querySelectorAll('select') : [];
  var searchTimer;

  if(searchInput){
    searchInput.addEventListener('input', function(){
      clearTimeout(searchTimer);
      searchTimer = setTimeout(function(){ if(filterForm) filterForm.submit(); }, 500);
    });
  }

  selects.forEach(function(select){
    select.addEventListener('change', function(){
      if(filterForm) filterForm.submit();
    });
  });
})();
</script>
@endpush


