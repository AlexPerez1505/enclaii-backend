@extends('layouts.app')

@section('title', 'Capturas')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Capturas
@endsection

@push('styles')
<style>
/* ===== CAPTURAS ===== */
.cap-toolbar {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 22px; flex-wrap: wrap;
}
.btn-tool {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; text-decoration: none;
  transition: background-color 150ms ease;
}
.btn-tool svg { color: var(--cyan); }
.btn-tool:hover { background: var(--card); }

.btn-regresar {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; text-decoration: none;
  transition: background-color 150ms ease;
}
.btn-regresar:hover { background: var(--card); }

.btn-filtrar {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: background-color 150ms ease;
}
.btn-filtrar:hover { background: var(--card); }

.btn-agregar-cap {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 20px; border-radius: var(--r-md);
  border: 1px solid rgba(46,123,246,.5);
  background: rgba(46,123,246,.12);
  font-size: 14px; font-weight: 700; color: var(--blue);
  cursor: pointer; transition: background-color 150ms ease;
}
.btn-agregar-cap:hover { background: rgba(46,123,246,.22); }
.cap-toolbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }

/* Layout */
.cap-layout {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 18px; align-items: start;
}

/* Card izquierda */
.cap-card {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  padding: 0; overflow: hidden;
}
.pac-label { font-size: 13.5px; color: var(--txt-soft); margin-bottom: 4px; }
.cap-title  { font-family: 'Sora', sans-serif; font-size: 26px; font-weight: 700; margin-bottom: 18px; }

.cap-search-bar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 1px solid var(--stroke); gap: 14px;
}
.cap-search-box {
  display: flex; align-items: center; gap: 10px;
  background: var(--panel-2); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); padding: 9px 14px; flex: 1; max-width: 320px;
}
.cap-search-box input {
  background: none; border: none; outline: none;
  font: inherit; font-size: 14px; color: var(--txt); width: 100%;
}
.cap-search-box input::placeholder { color: var(--off); }
.cap-search-box svg { color: var(--txt-soft); flex: none; }

.sort-wrap {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; color: var(--txt-soft);
}
.sort-select {
  appearance: none; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  padding: 8px 30px 8px 12px; font: inherit; font-size: 13px;
  color: var(--txt); cursor: pointer; outline: none;
}
.sort-select-wrap { position: relative; }
.sort-chev { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: var(--txt-soft); pointer-events: none; }

/* Lista capturas */
.cap-list { padding: 0; }
.cap-item {
  display: flex; align-items: center; gap: 16px;
  padding: 16px 18px; border-bottom: 1px solid rgba(110,160,255,.07);
  cursor: pointer; transition: background-color 150ms ease; position: relative;
}
.cap-item:last-child { border-bottom: 0; }
.cap-item:hover { background: rgba(46,123,246,.05); }
.cap-item.active { background: rgba(46,123,246,.10); }

.cap-thumb {
  width: 110px; height: 76px; border-radius: 8px;
  overflow: hidden; flex: none; border: 1px solid var(--stroke-strong);
}
.cap-thumb img { width: 100%; height: 100%; object-fit: cover; }

.cap-info { flex: 1; min-width: 0; }
.cap-nombre { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
.cap-date {
  display: flex; align-items: center; gap: 6px;
  font-size: 12.5px; color: var(--txt-soft); margin-bottom: 5px;
}
.cap-tipo { font-size: 12px; color: var(--off); }

.cap-more {
  background: none; border: none; color: var(--txt-soft);
  font-size: 18px; font-weight: 700; cursor: pointer;
  padding: 4px 8px; border-radius: var(--r-md);
  transition: background-color 150ms ease; line-height: 1;
}
.cap-more:hover { background: rgba(110,160,255,.1); color: var(--txt); }

.cap-footer {
  padding: 14px 18px; font-size: 13px; color: var(--txt-soft);
  border-top: 1px solid var(--stroke);
}

/* Panel derecho: vista previa */
.cap-preview-card {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  padding: 20px; display: flex; flex-direction: column; gap: 16px;
}
.prev-title { font-size: 15px; font-weight: 700; margin-bottom: 2px; }
.prev-img-box {
  width: 100%; aspect-ratio: 16/9; border-radius: 10px;
  overflow: hidden; border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
}
.prev-img-box img { width: 100%; height: 100%; object-fit: cover; }

.info-section-title { font-size: 12px; font-weight: 700; color: var(--txt-soft); letter-spacing: .05em; margin-bottom: 10px; }
.info-row {
  display: flex; justify-content: space-between;
  font-size: 12.5px; padding: 5px 0;
  border-bottom: 1px solid rgba(110,160,255,.07);
}
.info-row:last-child { border-bottom: 0; }
.info-row .lbl { color: var(--txt-soft); }
.info-row .val { font-weight: 600; text-align: right; }

.accs-title { font-size: 12px; font-weight: 700; color: var(--txt-soft); letter-spacing: .05em; margin-bottom: 10px; }
.accs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.acc-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  padding: 11px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 13.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; transition: background-color 150ms ease, transform 150ms ease;
}
.acc-btn:hover { background: var(--card); }
.acc-btn:active { transform: scale(.97); }
.acc-btn.danger { color: #f87171; border-color: rgba(248,113,113,.3); }
.acc-btn.danger:hover { background: rgba(248,113,113,.08); }
.acc-btn svg { flex: none; }

@media(max-width:900px){
  .cap-layout { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

  {{-- Toolbar --}}
  <div class="cap-toolbar rise d1">
    <a class="btn-tool" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo paciente
    </a>
    <button class="btn-tool">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Buscar paciente
    </button>
    <div class="cap-toolbar-right">
      <a class="btn-regresar" href="{{ route('nuevo-estudio.crear') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Regresar
      </a>
      <button class="btn-filtrar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filtrar
      </button>
      <button class="btn-agregar-cap" id="btnAgregarCap">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        Agregar Capturas
      </button>
      <input type="file" id="capInput" accept="image/*" multiple style="display:none">
    </div>
  </div>

  {{-- Encabezado paciente --}}
  <div style="margin-bottom:18px">
    <p class="pac-label">Paciente: Maria Gonzalez</p>
    <h1 class="cap-title">Capturas</h1>
  </div>

  {{-- Layout --}}
  <div class="cap-layout">

    {{-- Lista --}}
    <div class="cap-card rise d2">

      <div class="cap-search-bar">
        <div class="cap-search-box">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="capSearch" placeholder="Buscar Capturas...">
        </div>
        <div class="sort-wrap">
          Ordenar por:
          <div class="sort-select-wrap">
            <select class="sort-select">
              <option>Fecha (más reciente)</option>
              <option>Fecha (más antigua)</option>
              <option>Nombre</option>
            </select>
            <svg class="sort-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>
      </div>

      <div class="cap-list" id="capList">

        @php
        $capturas = [
          ['id'=>1,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva','img'=>1],
          ['id'=>2,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva','img'=>2],
          ['id'=>3,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva','img'=>3],
        ];
        @endphp

        @foreach($capturas as $c)
        <div class="cap-item" data-id="{{ $c['id'] }}"
          data-nombre="{{ $c['nombre'] }}"
          data-fecha="{{ $c['fecha'] }}"
          data-hora="{{ $c['hora'] }}"
          data-estudio="{{ $c['estudio'] }}"
          data-tipo-estudio="{{ $c['tipo_estudio'] }}"
          data-img="{{ $c['img'] }}">
          <div class="cap-thumb">
            <img src="{{ asset('images/captura1.jpg') }}" alt="captura">
          </div>
          <div class="cap-info">
            <div class="cap-nombre">{{ $c['nombre'] }}</div>
            <div class="cap-date">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $c['fecha'] }} {{ $c['hora'] }}
            </div>
            <div class="cap-tipo">{{ $c['tipo'] }}</div>
          </div>
          <button class="cap-more">⋮</button>
        </div>
        @endforeach

      </div>

      <div class="cap-footer" id="capFooter">Mostrando 3 de 3</div>

    </div>

    {{-- Vista previa --}}
    <div class="cap-preview-card rise d3" id="previewCard">

      <div class="prev-title">Vista Previa</div>

      <div class="prev-img-box">
        <img id="prevImg" src="{{ asset('images/captura1.jpg') }}" alt="Vista previa">
      </div>

      <div>
        <div class="info-section-title">Información de la captura</div>
        <div class="info-row"><span class="lbl">Fecha y hora</span>    <span class="val" id="pifecha">24/05/2026</span></div>
        <div class="info-row"><span class="lbl">Descripción</span>     <span class="val" id="pidesc">Lesion del Esófago</span></div>
        <div class="info-row"><span class="lbl">Estudio</span>         <span class="val" id="piestudio">EST-2024-0587</span></div>
        <div class="info-row"><span class="lbl">Tipo de estudio</span> <span class="val" id="pitipo">Endoscopia digestiva</span></div>
        <div class="info-row"><span class="lbl">Imagen</span>          <span class="val" id="piimagen">1 de 3</span></div>
      </div>

      <div>
        <div class="accs-title">Acciones</div>
        <div class="accs-grid">
          <button class="acc-btn" id="btnEditar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Editar
          </button>
          <button class="acc-btn" id="btnExportar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Exportar
          </button>
          <button class="acc-btn" id="btnImprimir">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Imprimir
          </button>
          <button class="acc-btn danger" id="btnEliminar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            Eliminar
          </button>
        </div>
      </div>

    </div>

  </div>

@endsection

@push('scripts')
<script>
(function () {
  /* Seleccionar captura → actualizar vista previa */
  const items   = document.querySelectorAll('.cap-item');
  const prevImg = document.getElementById('prevImg');

  items.forEach((item, idx) => {
    item.addEventListener('click', () => {
      items.forEach(i => i.classList.remove('active'));
      item.classList.add('active');

      const d = item.dataset;
      prevImg.src = '{{ asset('images/captura1.jpg') }}';
      document.getElementById('piecha')    && (document.getElementById('piecha').textContent = d.fecha);
      document.getElementById('pifecha').textContent   = d.fecha + ' ' + d.hora;
      document.getElementById('pidesc').textContent    = d.nombre;
      document.getElementById('piestudio').textContent = d.estudio;
      document.getElementById('pitipo').textContent    = d.tipoEstudio;
      document.getElementById('piimagen').textContent  = (idx + 1) + ' de ' + items.length;
    });
  });

  /* Activar primera captura por defecto */
  if (items.length) items[0].click();

  /* Buscador */
  const searchInput = document.getElementById('capSearch');
  searchInput.addEventListener('input', () => {
    const q = searchInput.value.toLowerCase().trim();
    let visible = 0;
    items.forEach(item => {
      const match = item.dataset.nombre.toLowerCase().includes(q);
      item.style.display = match ? '' : 'none';
      if (match) visible++;
    });
    document.getElementById('capFooter').textContent =
      `Mostrando ${visible} de ${items.length}`;
  });

  /* Botón agregar capturas → input file */
  document.getElementById('btnAgregarCap').addEventListener('click', () => {
    document.getElementById('capInput').click();
  });

  document.getElementById('capInput').addEventListener('change', function () {
    const files = Array.from(this.files);
    if (!files.length) return;
    alert(`${files.length} captura(s) agregada(s) correctamente.`);
  });

  /* Funciones de acciones */
  function getCurrentId() {
    const active = document.querySelector('.cap-item.active');
    return active ? parseInt(active.dataset.id) : null;
  }

  document.getElementById('btnEditar').addEventListener('click', () => {
    const id = getCurrentId();
    if (!id) return;
    const active = document.querySelector('.cap-item.active');
    const nuevo = prompt('Editar descripcion:', active.dataset.nombre);
    if (nuevo && nuevo.trim()) {
      active.dataset.nombre = nuevo.trim();
      active.querySelector('.cap-nombre').textContent = nuevo.trim();
      document.getElementById('pidesc').textContent = nuevo.trim();
    }
  });

  document.getElementById('btnExportar').addEventListener('click', () => {
    const src = prevImg.src;
    const a = document.createElement('a');
    a.href = src;
    a.download = 'captura_' + getCurrentId() + '.jpg';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  });

  document.getElementById('btnImprimir').addEventListener('click', () => {
    const w = window.open('', '_blank');
    if (!w) return;
    w.document.write(`<img src="${prevImg.src}" style="width:100%;max-width:600px;display:block;margin:auto;">`);
    w.document.close();
    w.focus();
    w.print();
  });

  document.getElementById('btnEliminar').addEventListener('click', () => {
    const id = getCurrentId();
    if (!id) return;
    if (!confirm('¿Eliminar esta captura?')) return;
    const active = document.querySelector('.cap-item.active');
    active.remove();
    const remaining = document.querySelectorAll('.cap-item');
    if (remaining.length) {
      remaining[0].click();
    } else {
      document.getElementById('previewCard').style.display = 'none';
    }
    document.getElementById('capFooter').textContent = `Mostrando ${remaining.length} de ${remaining.length}`;
  });
})();
</script>
@endpush
