{{-- IMPORTAR FOTOS --}}
@extends('layouts.app')

@section('title', 'Importar Fotos')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Importar fotos
@endsection

@push('styles')
<style>
/* =============================================
   IMPORTAR FOTOS - UI PROFESIONAL
   ============================================= */

/* Toolbar */
.imp-toolbar {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 24px; flex-wrap: wrap;
}
.btn-tool, .btn-back {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 9px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 13.5px; font-weight: 600; color: var(--txt);
  cursor: pointer; text-decoration: none;
  transition: background 150ms, border-color 150ms;
}
.btn-tool:hover, .btn-back:hover { background: var(--card); border-color: var(--blue); }
.btn-tool svg { color: var(--cyan); }
.btn-back { margin-left: auto; }

/* Layout principal */
.imp-shell {
  display: grid;
  grid-template-columns: 280px 1fr 220px;
  gap: 0;
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  overflow: hidden;
  min-height: 520px;
}

/* Sidebar izquierdo */
.imp-sidebar {
  border-right: 1px solid var(--stroke);
  display: flex; flex-direction: column;
  background: var(--panel-2);
}
.imp-sidebar-head {
  padding: 18px 18px 14px;
  border-bottom: 1px solid var(--stroke);
}
.imp-pac-label { font-size: 12px; color: var(--txt-soft); margin-bottom: 2px; }
.imp-title { font-family: 'Sora', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 2px; }
.imp-sub   { font-size: 12px; color: var(--txt-soft); }

/* Selector disco */
.disk-wrap {
  position: relative; display: flex; align-items: center;
  margin: 14px 18px 0;
}
.disk-icon  { position: absolute; left: 10px; color: var(--cyan); pointer-events: none; }
.disk-chev  { position: absolute; right: 10px; color: var(--txt-soft); pointer-events: none; }
.disk-sel {
  appearance: none; width: 100%;
  background: var(--card); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); padding: 9px 34px 9px 32px;
  font: inherit; font-size: 13px; font-weight: 700;
  color: var(--txt); cursor: pointer; outline: none;
}
.disk-sel:focus { border-color: var(--blue); }
.disk-sel option { background: var(--panel); }

/* Breadcrumb */
.imp-breadcrumb {
  display: flex; flex-wrap: wrap; align-items: center; gap: 3px;
  padding: 10px 18px 0; font-size: 11px; font-family: monospace;
  color: var(--txt-soft); min-height: 28px;
}
.path-crumb { color: var(--blue); cursor: pointer; font-weight: 700; }
.path-crumb:hover { text-decoration: underline; }
.path-sep { color: var(--stroke-strong); }

/* Arbol carpetas */
.folder-tree {
  flex: 1; overflow-y: auto; padding: 8px 0;
}
.folder-tree::-webkit-scrollbar { width: 4px; }
.folder-tree::-webkit-scrollbar-thumb { background: var(--stroke-strong); border-radius: 4px; }
.folder-item {
  display: flex; align-items: center; gap: 9px;
  padding: 9px 18px; font-size: 13px; font-weight: 600;
  cursor: pointer; border-left: 3px solid transparent;
  transition: background 120ms, border-color 120ms;
  user-select: none;
}
.folder-item:hover  { background: rgba(46,123,246,.07); }
.folder-item.active { background: rgba(46,123,246,.15); border-left-color: var(--blue); color: var(--blue); }
.folder-item.active svg { color: var(--blue); }
.folder-item svg    { color: var(--cyan); flex: none; }
.folder-item span   { overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }

/* Centro: panel fotos */
.imp-center {
  display: flex; flex-direction: column;
  border-right: 1px solid var(--stroke);
}
.imp-center-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 1px solid var(--stroke);
  gap: 12px; flex-wrap: wrap;
}
.imp-center-title { font-size: 13px; font-weight: 700; color: var(--txt-soft); letter-spacing: .04em; }
.imp-sel-badge {
  font-size: 11.5px; font-weight: 700; color: var(--blue);
  background: rgba(46,123,246,.12); padding: 3px 10px;
  border-radius: 20px; display: none;
}
.imp-center-actions { display: flex; align-items: center; gap: 8px; }
.btn-pick {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 12.5px; font-weight: 600;
  color: var(--cyan); cursor: pointer; transition: background 150ms;
}
.btn-pick:hover { background: rgba(56,199,244,.08); }
.btn-sel-all {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: transparent;
  font: inherit; font-size: 12.5px; font-weight: 600;
  color: var(--txt-soft); cursor: pointer; transition: background 150ms, color 150ms;
}
.btn-sel-all:hover { background: var(--panel-2); color: var(--txt); }

/* Grid fotos */
.photos-grid {
  flex: 1; padding: 16px;
  display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
  gap: 12px; align-content: start; overflow-y: auto;
}
.photos-grid::-webkit-scrollbar { width: 4px; }
.photos-grid::-webkit-scrollbar-thumb { background: var(--stroke-strong); border-radius: 4px; }

/* Estado vacio */
.photos-empty {
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; gap: 12px; height: 100%;
  color: var(--txt-soft); text-align: center; padding: 40px;
}
.photos-empty svg { opacity: .35; }
.photos-empty p { font-size: 13.5px; line-height: 1.5; }

/* Miniaturas */
.photo-thumb {
  position: relative; border-radius: 10px; overflow: hidden;
  border: 2px solid transparent; cursor: pointer;
  background: var(--panel); aspect-ratio: 1;
  transition: border-color 150ms, transform 150ms, box-shadow 150ms;
}
.photo-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.photo-thumb:hover { transform: scale(1.03); box-shadow: 0 6px 20px rgba(0,0,0,.4); }
.photo-thumb.sel  { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(46,123,246,.25); }
.photo-thumb .chk {
  position: absolute; top: 5px; right: 5px;
  width: 20px; height: 20px; border-radius: 50%;
  background: var(--blue); display: none; place-items: center;
  color: #fff; font-size: 11px; font-weight: 800;
  box-shadow: 0 2px 8px rgba(0,0,0,.4);
}
.photo-thumb.sel .chk { display: grid; }
.photo-thumb .fname {
  position: absolute; bottom: 0; left: 0; right: 0;
  background: linear-gradient(transparent, rgba(0,0,0,.75));
  padding: 14px 6px 5px; font-size: 10px; color: #fff;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  opacity: 0; transition: opacity 150ms;
}
.photo-thumb:hover .fname, .photo-thumb.sel .fname { opacity: 1; }

/* Sidebar derecho */
.imp-right {
  display: flex; flex-direction: column; gap: 0;
  background: var(--panel-2);
}
.imp-right-section {
  padding: 16px; border-bottom: 1px solid var(--stroke);
}
.imp-section-title {
  font-size: 11px; font-weight: 700; color: var(--txt-soft);
  letter-spacing: .06em; text-transform: uppercase; margin-bottom: 12px;
}

/* Formatos */
.fmt-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.fmt-chk {
  display: flex; align-items: center; gap: 7px;
  font-size: 12.5px; font-weight: 600; cursor: pointer;
  padding: 7px 10px; border-radius: var(--r-md);
  border: 1px solid var(--stroke); background: var(--card);
  transition: border-color 150ms, background 150ms;
}
.fmt-chk:has(input:checked) { border-color: var(--blue); background: rgba(46,123,246,.1); }
.fmt-chk input { accent-color: var(--blue); width: 14px; height: 14px; cursor: pointer; }

/* Vista Previa */
.prev-wrap {
  width: 100%; aspect-ratio: 1; border-radius: 10px;
  background: var(--card); border: 1px solid var(--stroke);
  overflow: hidden; position: relative;
  display: flex; align-items: center; justify-content: center;
}
.prev-wrap img { width: 100%; height: 100%; object-fit: contain; display: none; }
.prev-placeholder { color: var(--off); text-align: center; font-size: 12px; }
.prev-placeholder svg { display: block; margin: 0 auto 6px; opacity: .4; }
.prev-info {
  display: none; padding: 10px 0 0; font-size: 11.5px;
  color: var(--txt-soft); line-height: 1.6;
}
.prev-info strong { color: var(--txt); display: block; font-size: 12px; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Estadisticas */
.imp-stats { display: flex; flex-direction: column; gap: 8px; flex: 1; padding: 16px; }
.stat-row { display: flex; justify-content: space-between; font-size: 12.5px; }
.stat-row .lbl { color: var(--txt-soft); }
.stat-row .val { font-weight: 700; color: var(--txt); }
.stat-row .val.blue { color: var(--blue); }

/* Footer */
.imp-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 20px; margin-top: 12px; flex-wrap: wrap; gap: 10px;
}
.footer-links { display: flex; gap: 16px; }
.btn-ftr {
  background: none; border: none; font: inherit;
  font-size: 13px; font-weight: 600; color: var(--txt-soft);
  cursor: pointer; transition: color 150ms;
  display: inline-flex; align-items: center; gap: 5px;
}
.btn-ftr:hover { color: var(--txt); }
.btn-ftr.saved { color: var(--green); }
.btn-transferir {
  display: inline-flex; align-items: center; gap: 9px;
  padding: 12px 32px; border-radius: var(--r-md);
  background: linear-gradient(135deg,#1668D9,var(--blue));
  color: #fff; font-size: 14.5px; font-weight: 800;
  letter-spacing: .05em; border: none; cursor: pointer;
  box-shadow: 0 8px 24px -8px rgba(46,123,246,.65);
  transition: opacity 150ms, transform 160ms var(--ease-out);
}
.btn-transferir:hover  { opacity: .9; }
.btn-transferir:active { transform: scale(.97); }

/* Modal */
.imp-modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.6); backdrop-filter: blur(6px);
  z-index: 600; align-items: center; justify-content: center;
}
.imp-modal-overlay.open { display: flex; }
.imp-modal {
  background: var(--card); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-lg); padding: 36px 40px;
  max-width: 400px; width: 92%; text-align: center;
  display: flex; flex-direction: column; align-items: center; gap: 14px;
  animation: modalIn 220ms var(--ease-out);
}
@keyframes modalIn { from { transform: scale(.92); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.imp-modal-icon {
  width: 60px; height: 60px; border-radius: 50%;
  display: grid; place-items: center;
  background: rgba(61,220,151,.12);
  border: 1px solid rgba(61,220,151,.3);
}
.imp-modal-title { font-family: 'Sora', sans-serif; font-size: 20px; font-weight: 700; }
.imp-modal-sub   { font-size: 13.5px; color: var(--txt-soft); line-height: 1.55; }
.imp-modal-ok {
  padding: 12px 36px; border-radius: var(--r-md);
  background: linear-gradient(135deg,#1668D9,var(--blue));
  color: #fff; font-size: 14px; font-weight: 700;
  border: none; cursor: pointer; margin-top: 4px;
  transition: opacity 150ms;
}
.imp-modal-ok:hover { opacity: .9; }
.imp-modal-warn .imp-modal-icon {
  background: rgba(245,158,45,.1); border-color: rgba(245,158,45,.3);
}

@media(max-width:960px){
  .imp-shell { grid-template-columns: 220px 1fr; }
  .imp-right { display: none; }
}
@media(max-width:640px){
  .imp-shell { grid-template-columns: 1fr; min-height: auto; }
  .imp-sidebar { display: none; }
}
</style>
@endpush

@section('content')

{{-- Toolbar --}}
<div class="imp-toolbar rise d1">
  <a class="btn-tool" href="{{ route('nuevo-estudio.crear') }}">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nuevo paciente
  </a>
  <button class="btn-tool" type="button">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    Buscar paciente
  </button>
  <a class="btn-back" href="{{ route('nuevo-estudio.crear') }}">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver
  </a>
</div>

{{-- Shell principal --}}
<div class="imp-shell rise d2">

  {{-- Sidebar izquierdo --}}
  <div class="imp-sidebar">
    <div class="imp-sidebar-head">
      <p class="imp-pac-label">Paciente: Maria Gonzalez</p>
      <h1 class="imp-title">Importar Fotos</h1>
      <p class="imp-sub">Selecciona el directorio</p>

      <div class="disk-wrap" style="margin-top:12px">
        <svg class="disk-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        <select class="disk-sel" id="diskSelect">
          <option value="C">C:</option>
          <option value="D">D:</option>
          <option value="E">E:</option>
        </select>
        <svg class="disk-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <div class="imp-breadcrumb" id="currentPath">
      <span class="path-crumb" data-level="0">C:</span>
    </div>

    <div class="folder-tree" id="folderTree"></div>
  </div>

  {{-- Centro --}}
  <div class="imp-center">
    <div class="imp-center-head">
      <span class="imp-center-title">FOTOS EN DIRECTORIO</span>
      <span class="imp-sel-badge" id="selBadge">0 seleccionadas</span>
      <div class="imp-center-actions">
        <button class="btn-sel-all" type="button" id="btnSelAll">Seleccionar todo</button>
        <button class="btn-pick" type="button" id="btnPickFiles">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          Subir imagenes
        </button>
        <input type="file" id="importFiles" accept="image/*,.bmp,.tif,.tiff" multiple style="display:none">
      </div>
    </div>

    <div class="photos-grid" id="photosGrid">
      <div class="photos-empty" id="photosEmpty" style="grid-column:1/-1">
        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
        <p>Selecciona una carpeta con imagenes<br>o sube archivos desde tu dispositivo</p>
      </div>
    </div>
  </div>

  {{-- Sidebar derecho --}}
  <div class="imp-right">

    <div class="imp-right-section">
      <div class="imp-section-title">Formato</div>
      <div class="fmt-grid">
        <label class="fmt-chk"><input type="checkbox" id="fmtBmp" checked> BMP</label>
        <label class="fmt-chk"><input type="checkbox" id="fmtJpg" checked> JPG</label>
        <label class="fmt-chk"><input type="checkbox" id="fmtTif" checked> TIF</label>
        <label class="fmt-chk"><input type="checkbox" id="fmtPng" checked> PNG</label>
      </div>
    </div>

    <div class="imp-right-section">
      <div class="imp-section-title">Vista Previa</div>
      <div class="prev-wrap" id="prevWrap">
        <img id="prevImg" src="" alt="Vista previa">
        <div class="prev-placeholder" id="prevPh">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          Sin seleccion
        </div>
      </div>
      <div class="prev-info" id="prevInfo">
        <strong id="prevName">-</strong>
        <span id="prevMeta">-</span>
      </div>
    </div>

    <div class="imp-stats">
      <div class="imp-section-title">Resumen</div>
      <div class="stat-row"><span class="lbl">Total en carpeta</span><span class="val" id="statTotal">0</span></div>
      <div class="stat-row"><span class="lbl">Seleccionadas</span><span class="val blue" id="statSel">0</span></div>
      <div class="stat-row"><span class="lbl">Formato activo</span><span class="val" id="statFmt">JPG, PNG</span></div>
    </div>

  </div>
</div>

{{-- Footer --}}
<div class="imp-footer">
  <div class="footer-links">
    <button class="btn-ftr" type="button" id="btnRecordar">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
      Recordar ubicacion
    </button>
    <button class="btn-ftr" type="button" id="btnSelAllFtr">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Seleccionar Toda la Lista
    </button>
  </div>
  <button class="btn-transferir" type="button" id="btnTransferir">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    TRANSFERIR
  </button>
</div>

{{-- Modal: Transferencia exitosa --}}
<div class="imp-modal-overlay" id="transferModal">
  <div class="imp-modal">
    <div class="imp-modal-icon">
      <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#3DDC97" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="imp-modal-title">Transferencia exitosa</div>
    <div class="imp-modal-sub" id="transferModalSub">Las imagenes han sido importadas correctamente.</div>
    <button class="imp-modal-ok" id="transferModalOk">Aceptar</button>
  </div>
</div>

{{-- Modal: Sin seleccion --}}
<div class="imp-modal-overlay imp-modal-warn" id="warnModal">
  <div class="imp-modal">
    <div class="imp-modal-icon">
      <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#F59E2D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div class="imp-modal-title">Sin imagenes seleccionadas</div>
    <div class="imp-modal-sub">Selecciona al menos una imagen del directorio o sube archivos desde tu dispositivo.</div>
    <button class="imp-modal-ok" id="warnModalOk" style="background:linear-gradient(135deg,#c47a00,var(--orange))">Entendido</button>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function () {

  /* Virtual FS */
  const IMG = '{{ asset("images/captura1.jpg") }}';
  const vfs = {
    'C:':                    { ch: ['Users','Windows','Program Files'], files: [] },
    'C:/Users':              { ch: ['grupos','Public','Downloads','Pictures'], files: [] },
    'C:/Users/grupos':       { ch: ['Fotos','Documentos'], files: [] },
    'C:/Users/grupos/Fotos': { ch: [], files: [
      { name:'IMG_001.jpg',  ext:'jpg', src:IMG, size:'2.1 MB', dim:'1920x1080' },
      { name:'IMG_002.jpg',  ext:'jpg', src:IMG, size:'1.8 MB', dim:'1920x1080' },
      { name:'IMG_003.png',  ext:'png', src:IMG, size:'3.4 MB', dim:'2560x1440' },
      { name:'SCAN_001.tif', ext:'tif', src:IMG, size:'8.2 MB', dim:'3000x2000' },
    ]},
    'C:/Users/Public':   { ch: ['Desktop','Pictures'], files: [{ name:'foto.jpg', ext:'jpg', src:IMG, size:'950 KB', dim:'1280x720' }] },
    'C:/Users/Downloads':{ ch: [], files: [
      { name:'captura1.jpg', ext:'jpg', src:IMG, size:'1.2 MB', dim:'1920x1080' },
      { name:'captura2.jpg', ext:'jpg', src:IMG, size:'1.1 MB', dim:'1920x1080' },
      { name:'scan.bmp',     ext:'bmp', src:IMG, size:'5.6 MB', dim:'2048x1536' },
    ]},
    'C:/Users/Pictures': { ch: ['Camera Roll','Screenshots'], files: [
      { name:'photo1.jpg', ext:'jpg', src:IMG, size:'2.3 MB', dim:'4000x3000' },
      { name:'photo2.png', ext:'png', src:IMG, size:'4.1 MB', dim:'3840x2160' },
      { name:'photo3.jpg', ext:'jpg', src:IMG, size:'1.9 MB', dim:'1920x1080' },
      { name:'photo4.tif', ext:'tif', src:IMG, size:'11 MB',  dim:'4096x3072' },
      { name:'photo5.jpg', ext:'jpg', src:IMG, size:'2.7 MB', dim:'4000x3000' },
    ]},
    'C:/Users/Pictures/Camera Roll':  { ch:[], files:[{ name:'cam1.jpg', ext:'jpg', src:IMG, size:'3.2 MB', dim:'4000x3000' }] },
    'C:/Users/Pictures/Screenshots':  { ch:[], files:[{ name:'ss1.png',  ext:'png', src:IMG, size:'1.4 MB', dim:'2560x1440' }] },
    'D:':                    { ch: ['ENCLAII','Backup'], files: [] },
    'D:/ENCLAII':            { ch: ['Patient'], files: [] },
    'D:/ENCLAII/Patient':    { ch: ['13'], files: [] },
    'D:/ENCLAII/Patient/13': { ch: [], files: [
      { name:'endoscopia1.jpg', ext:'jpg', src:IMG, size:'2.8 MB', dim:'1920x1080' },
      { name:'endoscopia2.jpg', ext:'jpg', src:IMG, size:'3.1 MB', dim:'1920x1080' },
      { name:'endoscopia3.png', ext:'png', src:IMG, size:'4.4 MB', dim:'2560x1440' },
    ]},
    'E:': { ch:[], files:[] },
  };

  /* Estado */
  let pathStack     = ['C:'];
  let uploadedFiles = [];
  let allSelected   = false;

  /* Refs DOM */
  const folderTree = document.getElementById('folderTree');
  const photosGrid = document.getElementById('photosGrid');
  const photosEmpty= document.getElementById('photosEmpty');
  const prevImg    = document.getElementById('prevImg');
  const prevPh     = document.getElementById('prevPh');
  const prevInfo   = document.getElementById('prevInfo');
  const prevName   = document.getElementById('prevName');
  const prevMeta   = document.getElementById('prevMeta');
  const selBadge   = document.getElementById('selBadge');
  const statTotal  = document.getElementById('statTotal');
  const statSel    = document.getElementById('statSel');
  const statFmt    = document.getElementById('statFmt');
  const pathEl     = document.getElementById('currentPath');
  const diskSelect = document.getElementById('diskSelect');

  /* Formatos activos */
  function getFormats() {
    const map = { fmtBmp:'bmp', fmtJpg:'jpg', fmtTif:'tif', fmtPng:'png' };
    return Object.entries(map).filter(([id]) => document.getElementById(id)?.checked).map(([,v]) => v);
  }
  ['fmtBmp','fmtJpg','fmtTif','fmtPng'].forEach(id =>
    document.getElementById(id)?.addEventListener('change', () => { renderPhotos(); updateStats(); })
  );

  /* Breadcrumb */
  function renderBreadcrumb() {
    pathEl.innerHTML = '';
    pathStack.forEach((seg, i) => {
      const c = document.createElement('span');
      c.className = 'path-crumb';
      c.textContent = seg;
      c.addEventListener('click', () => { pathStack = pathStack.slice(0, i + 1); renderAll(); });
      pathEl.appendChild(c);
      if (i < pathStack.length - 1) {
        const s = document.createElement('span');
        s.className = 'path-sep';
        s.textContent = ' > ';
        pathEl.appendChild(s);
      }
    });
  }

  /* Iconos SVG */
  function folderIcon(size) {
    size = size || 16;
    return '<svg width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>';
  }
  function driveIcon() {
    return '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>';
  }

  /* Arbol carpetas */
  function renderTree() {
    folderTree.innerHTML = '';
    const curPath = pathStack.join('/');
    const node = vfs[curPath] || { ch:[], files:[] };

    var root = document.createElement('div');
    root.className = 'folder-item' + (pathStack.length === 1 ? ' active' : '');
    root.style.paddingLeft = '18px';
    root.innerHTML = driveIcon() + '<span>' + pathStack[0] + '</span>';
    root.addEventListener('click', function() { pathStack = [pathStack[0]]; renderAll(); });
    folderTree.appendChild(root);

    if (pathStack.length > 1) {
      pathStack.slice(1).forEach(function(seg, i) {
        var lvl = i + 1;
        var el = document.createElement('div');
        el.className = 'folder-item' + (lvl === pathStack.length - 1 ? ' active' : '');
        el.style.paddingLeft = (18 + lvl * 14) + 'px';
        el.innerHTML = folderIcon() + '<span>' + seg + '</span>';
        var cl = lvl;
        el.addEventListener('click', function() { pathStack = pathStack.slice(0, cl + 1); renderAll(); });
        folderTree.appendChild(el);
      });
    }

    node.ch.forEach(function(child) {
      var el = document.createElement('div');
      el.className = 'folder-item';
      el.style.paddingLeft = (18 + pathStack.length * 14) + 'px';
      el.innerHTML = folderIcon() + '<span>' + child + '</span>';
      el.addEventListener('click', function() { pathStack = pathStack.concat([child]); renderAll(); });
      folderTree.appendChild(el);
    });
  }

  /* Fotos */
  function renderPhotos() {
    var curPath = pathStack.join('/');
    var node    = vfs[curPath] || { ch:[], files:[] };
    var formats = getFormats();
    var files   = node.files.concat(uploadedFiles)
      .filter(function(f) { return formats.indexOf(f.ext.replace('jpeg','jpg')) !== -1; });

    Array.from(photosGrid.children).forEach(function(c) { if (c !== photosEmpty) c.remove(); });
    allSelected = false;
    updateSelBadge();

    if (files.length === 0) {
      photosEmpty.style.display = 'flex';
      statTotal.textContent = '0';
      return;
    }
    photosEmpty.style.display = 'none';
    statTotal.textContent = files.length;

    files.forEach(function(file) {
      var thumb = document.createElement('div');
      thumb.className = 'photo-thumb';
      thumb.innerHTML = '<img src="' + file.src + '" alt="' + file.name + '"><div class="chk">&#10003;</div><div class="fname">' + file.name + '</div>';

      thumb.addEventListener('click', function() {
        thumb.classList.toggle('sel');
        if (thumb.classList.contains('sel')) {
          showPreview(file);
        } else {
          if (!photosGrid.querySelector('.photo-thumb.sel')) clearPreview();
        }
        updateSelBadge();
      });

      photosGrid.insertBefore(thumb, photosEmpty);
    });
  }

  function showPreview(file) {
    prevImg.src = file.src;
    prevImg.style.display = 'block';
    prevPh.style.display  = 'none';
    prevInfo.style.display = 'block';
    prevName.textContent = file.name;
    prevMeta.textContent = [file.size, file.dim].filter(Boolean).join(' - ');
  }
  function clearPreview() {
    prevImg.style.display  = 'none';
    prevPh.style.display   = 'flex';
    prevInfo.style.display = 'none';
  }

  function updateSelBadge() {
    var n = photosGrid.querySelectorAll('.photo-thumb.sel').length;
    selBadge.style.display = n > 0 ? 'inline-block' : 'none';
    selBadge.textContent   = n + ' seleccionada' + (n !== 1 ? 's' : '');
    statSel.textContent    = n;
  }

  function updateStats() {
    var fmts = getFormats();
    statFmt.textContent = fmts.length ? fmts.map(function(f){ return f.toUpperCase(); }).join(', ') : '-';
  }

  function renderAll() {
    renderBreadcrumb();
    renderTree();
    renderPhotos();
    updateStats();
  }

  /* Cambio de disco */
  diskSelect.addEventListener('change', function() {
    pathStack     = [this.value + ':'];
    uploadedFiles = [];
    renderAll();
  });

  /* Subir archivos reales */
  var btnPick   = document.getElementById('btnPickFiles');
  var fileInput = document.getElementById('importFiles');
  btnPick.addEventListener('click', function() { fileInput.click(); });

  fileInput.addEventListener('change', function() {
    var files = Array.from(this.files);
    if (!files.length) return;
    var done = 0;
    uploadedFiles = [];

    files.forEach(function(file) {
      var ext    = file.name.split('.').pop().toLowerCase().replace('jpeg','jpg');
      var sizeMB = file.size > 1048576
        ? (file.size / 1048576).toFixed(1) + ' MB'
        : Math.round(file.size / 1024) + ' KB';

      var reader = new FileReader();
      reader.onload = function(e) {
        var img = new Image();
        img.onload = function() {
          uploadedFiles.push({
            name: file.name, ext: ext, src: e.target.result,
            size: sizeMB, dim: img.naturalWidth + 'x' + img.naturalHeight
          });
          done++;
          if (done === files.length) renderPhotos();
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    });
  });

  /* Seleccionar todo */
  function toggleAll() {
    var thumbs = photosGrid.querySelectorAll('.photo-thumb');
    if (!thumbs.length) return;
    allSelected = !allSelected;
    thumbs.forEach(function(t) {
      allSelected ? t.classList.add('sel') : t.classList.remove('sel');
    });
    var label = allSelected ? 'Deseleccionar todo' : 'Seleccionar todo';
    document.getElementById('btnSelAll').textContent = label;
    if (allSelected) {
      var firstImg = photosGrid.querySelector('.photo-thumb.sel img');
      if (firstImg) { prevImg.src = firstImg.src; prevImg.style.display = 'block'; prevPh.style.display = 'none'; }
    } else {
      clearPreview();
    }
    updateSelBadge();
  }
  document.getElementById('btnSelAll').addEventListener('click', toggleAll);
  document.getElementById('btnSelAllFtr').addEventListener('click', toggleAll);

  /* Recordar ubicacion */
  var LS_KEY = 'enclaii-import-path';
  try {
    var saved = JSON.parse(localStorage.getItem(LS_KEY));
    if (Array.isArray(saved) && saved.length) {
      pathStack = saved;
      diskSelect.value = saved[0].replace(':', '');
    }
  } catch(e) {}

  document.getElementById('btnRecordar').addEventListener('click', function() {
    localStorage.setItem(LS_KEY, JSON.stringify(pathStack));
    this.textContent = 'Ubicacion guardada';
    this.classList.add('saved');
    var self = this;
    setTimeout(function() {
      self.textContent = 'Recordar ubicacion';
      self.classList.remove('saved');
    }, 2200);
  });

  /* Transferir */
  document.getElementById('btnTransferir').addEventListener('click', function() {
    var sel = photosGrid.querySelectorAll('.photo-thumb.sel').length;
    if (sel === 0) {
      document.getElementById('warnModal').classList.add('open');
      return;
    }
    document.getElementById('transferModalSub').textContent =
      sel + ' imagen' + (sel !== 1 ? 'es' : '') + ' importada' + (sel !== 1 ? 's' : '') + ' correctamente al estudio de Maria Gonzalez.';
    document.getElementById('transferModal').classList.add('open');
  });

  document.getElementById('transferModalOk').addEventListener('click', function() {
    document.getElementById('transferModal').classList.remove('open');
    photosGrid.querySelectorAll('.photo-thumb.sel').forEach(function(t) { t.classList.remove('sel'); });
    clearPreview();
    updateSelBadge();
  });
  document.getElementById('warnModalOk').addEventListener('click', function() {
    document.getElementById('warnModal').classList.remove('open');
  });

  /* Init */
  renderAll();

})();
</script>
@endpush