@extends('layouts.app')

@section('title', 'Importar Fotos')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Importar fotos
@endsection

@push('styles')
<style>
/* ===== IMPORTAR FOTOS ===== */
.imp-toolbar {
  display: flex; align-items: center; gap: 12px;
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
.btn-back {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; text-decoration: none; margin-left: auto;
  transition: background-color 150ms ease;
}
.btn-back:hover { background: var(--card); }

.imp-card {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke); border-radius: var(--r-lg);
  padding: 28px 28px 24px;
}
.pac-label { font-size: 13.5px; color: var(--txt-soft); margin-bottom: 4px; }
.imp-title  { font-family: 'Sora', sans-serif; font-size: 26px; font-weight: 700; margin-bottom: 6px; }
.imp-sub    { font-size: 14px; color: var(--txt-soft); margin-bottom: 20px; }

/* Selector disco */
.disk-wrap {
  position: relative; display: inline-flex;
  align-items: center; margin-bottom: 18px;
}
.disk-icon { position: absolute; left: 11px; color: var(--cyan); pointer-events: none; }
.disk-sel {
  appearance: none; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  padding: 10px 38px 10px 34px; font: inherit;
  font-size: 14px; font-weight: 600; color: var(--txt);
  cursor: pointer; min-width: 160px; outline: none;
}
.disk-sel:focus { border-color: var(--blue); }
.disk-sel option { background: var(--panel); }
.disk-chev { position: absolute; right: 10px; color: var(--txt-soft); pointer-events: none; }

/* Grid 3 col */
.imp-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 210px;
  gap: 16px; align-items: start;
}

/* Árbol carpetas */
.folder-panel {
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  overflow: hidden; background: var(--panel-2); min-height: 300px;
}
.folder-item {
  display: flex; align-items: center; gap: 10px;
  padding: 11px 16px; font-size: 14px; font-weight: 600;
  cursor: pointer; border-bottom: 1px solid rgba(110,160,255,.07);
  transition: background-color 150ms ease; user-select: none;
}
.folder-item:last-child { border-bottom: 0; }
.folder-item:hover  { background: rgba(46,123,246,.08); }
.folder-item.active { background: rgba(46,123,246,.22); color: #fff; }
.folder-item.sub    { padding-left: 36px; font-weight: 500; }
.folder-item svg    { color: var(--cyan); flex: none; }
.folder-item.active svg { color: #fff; }

/* Panel fotos */
.photos-panel {
  border: 1px solid var(--stroke-strong); border-radius: var(--r-md);
  background: var(--panel-2); min-height: 300px; position: relative; overflow: hidden;
}
.photos-lbl {
  font-size: 12px; color: var(--txt-soft); padding: 10px 14px 8px;
  border-bottom: 1px solid var(--stroke); font-weight: 600; letter-spacing: .04em;
}
.photos-inner {
  display: grid; grid-template-columns: repeat(3,1fr);
  gap: 8px; padding: 10px;
}
.photo-thumb {
  aspect-ratio: 1; border-radius: 6px;
  background: var(--panel); border: 2px solid transparent;
  overflow: hidden; cursor: pointer; position: relative;
  transition: border-color 150ms ease;
}
.photo-thumb img { width: 100%; height: 100%; object-fit: cover; }
.photo-thumb.sel { border-color: var(--blue); }
.photo-thumb .chk {
  position: absolute; top: 4px; right: 4px;
  width: 18px; height: 18px; border-radius: 50%;
  background: var(--blue); display: none;
  place-items: center; color: #fff; font-size: 11px; font-weight: 700;
}
.photo-thumb.sel .chk { display: grid; }
.photos-empty {
  position: absolute; inset: 0;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 10px; color: var(--txt-soft); font-size: 13.5px; text-align: center;
}
.btn-pick-files {
  display: flex; align-items: center; gap: 6px;
  padding: 9px 16px; border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  font: inherit; font-size: 13px; font-weight: 600;
  color: var(--cyan); cursor: pointer; background: none;
  transition: background-color 150ms ease;
}
.btn-pick-files:hover { background: rgba(56,199,244,.08); }

/* Panel derecho */
.side-right { display: flex; flex-direction: column; gap: 14px; }
.fmt-box, .prev-box {
  background: var(--panel-2); border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md); padding: 14px 16px;
}
.fmt-title, .prev-title {
  font-size: 12px; font-weight: 700; color: var(--txt-soft);
  letter-spacing: .05em; margin-bottom: 12px;
}
.fmt-checks { display: flex; flex-wrap: wrap; gap: 10px; }
.fmt-chk {
  display: flex; align-items: center; gap: 6px;
  font-size: 13px; font-weight: 600; cursor: pointer;
}
.fmt-chk input { accent-color: var(--blue); width: 15px; height: 15px; cursor: pointer; }
.prev-img {
  width: 100%; aspect-ratio: 1; border-radius: 8px;
  background: var(--panel); overflow: hidden;
  display: flex; align-items: center; justify-content: center;
}
.prev-img img { width: 100%; height: 100%; object-fit: contain; display: none; }
.prev-placeholder { color: var(--off); font-size: 12px; text-align: center; }

/* Footer */
.imp-footer {
  display: flex; align-items: center;
  justify-content: space-between; margin-top: 20px;
  flex-wrap: wrap; gap: 12px;
}
.footer-links { display: flex; gap: 20px; }
.btn-ftr {
  background: none; border: none; font: inherit;
  font-size: 14px; font-weight: 600; color: var(--txt-soft);
  cursor: pointer; transition: color 150ms ease;
}
.btn-ftr:hover { color: var(--txt); }
.btn-transferir {
  padding: 13px 36px; border-radius: var(--r-md);
  background: linear-gradient(135deg,#1668D9,var(--blue));
  color: #fff; font-size: 15px; font-weight: 800;
  letter-spacing: .06em; border: none; cursor: pointer;
  box-shadow: 0 8px 22px -8px rgba(46,123,246,.6);
  transition: opacity 150ms ease, transform 160ms var(--ease-out);
}
.btn-transferir:hover  { opacity: .9; }
.btn-transferir:active { transform: scale(.97); }

@media(max-width:1000px){
  .imp-grid { grid-template-columns: 1fr 1fr; }
  .side-right { grid-column: span 2; flex-direction: row; }
}
@media(max-width:640px){
  .imp-grid { grid-template-columns: 1fr; }
  .side-right { flex-direction: column; grid-column: span 1; }
}
</style>
@endpush

@section('content')

  {{-- Toolbar --}}
  <div class="imp-toolbar rise d1">
    <a class="btn-tool" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo paciente
    </a>
    <button class="btn-tool">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Buscar paciente
    </button>
    <a class="btn-back" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  {{-- Card --}}
  <div class="imp-card rise d2">

    <p class="pac-label">Paciente: Maria Gonzalez</p>
    <h1 class="imp-title">Importar Fotos</h1>
    <p class="imp-sub">Seleccione el directorio donde se encuentran las fotos</p>

    {{-- Selector disco --}}
    <div class="disk-wrap">
      <svg class="disk-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
      <select class="disk-sel" id="diskSelect">
        <option value="C">C:</option>
        <option value="D">D:</option>
        <option value="E">E:</option>
      </select>
      <svg class="disk-chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </div>

    {{-- Grid --}}
    <div class="imp-grid">

      {{-- Árbol carpetas --}}
      <div class="folder-panel" id="folderTree">
        <div class="folder-item" data-folder="root">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          C:\
        </div>
        <div class="folder-item active" data-folder="users">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          Users
        </div>
        <div class="folder-item sub" data-folder="grupos">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          grupos
        </div>
        <div class="folder-item sub" data-folder="public">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          Public
        </div>
        <div class="folder-item sub" data-folder="downloads">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          Downloads
        </div>
        <div class="folder-item sub" data-folder="pictures">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          Pictures
        </div>
      </div>

      {{-- Panel fotos --}}
      <div class="photos-panel">
        <div class="photos-lbl">Seleccionar las fotos a importar</div>
        <div class="photos-inner" id="photosGrid"></div>
        <div class="photos-empty" id="photosEmpty">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          <span>Importa imágenes desde<br>tu dispositivo</span>
          <button class="btn-pick-files" type="button" id="btnPickFiles">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Seleccionar archivos
          </button>
          <input type="file" id="importFiles" accept=".bmp,.jpg,.jpeg,.tif,.tiff,.png" multiple style="display:none">
        </div>
      </div>

      {{-- Panel derecho --}}
      <div class="side-right">
        <div class="fmt-box">
          <div class="fmt-title">Formato de la imagen</div>
          <div class="fmt-checks">
            <label class="fmt-chk"><input type="checkbox" checked> BMP</label>
            <label class="fmt-chk"><input type="checkbox" checked> JPG</label>
            <label class="fmt-chk"><input type="checkbox" checked> TIF</label>
            <label class="fmt-chk"><input type="checkbox" checked> PNG</label>
          </div>
        </div>
        <div class="prev-box">
          <div class="prev-title">Vista Previa</div>
          <div class="prev-img" id="prevWrap">
            <img id="prevImg" src="" alt="Vista previa">
            <div class="prev-placeholder" id="prevPh">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <div style="margin-top:6px">Sin selección</div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- Footer --}}
    <div class="imp-footer">
      <div class="footer-links">
        <button class="btn-ftr" type="button">Recordar ubicación</button>
        <button class="btn-ftr" type="button" id="btnSelAll">Seleccionar Toda la Lista</button>
      </div>
      <button class="btn-transferir" type="button" id="btnTransferir">TRANSFERIR</button>
    </div>

  </div>

@endsection

@push('scripts')
<script>
(function () {
  /* Carpetas */
  document.querySelectorAll('.folder-item').forEach(item => {
    item.addEventListener('click', () => {
      document.querySelectorAll('.folder-item').forEach(i => i.classList.remove('active'));
      item.classList.add('active');
    });
  });

  /* Input archivos */
  const btnPick   = document.getElementById('btnPickFiles');
  const fileInput = document.getElementById('importFiles');
  const grid      = document.getElementById('photosGrid');
  const empty     = document.getElementById('photosEmpty');
  const prevImg   = document.getElementById('prevImg');
  const prevPh    = document.getElementById('prevPh');
  const btnSelAll = document.getElementById('btnSelAll');

  let allSelected = false;

  btnPick.addEventListener('click', () => fileInput.click());

  fileInput.addEventListener('change', function () {
    const files = Array.from(this.files);
    if (!files.length) return;

    empty.style.display = 'none';
    grid.innerHTML = '';

    files.forEach((file, idx) => {
      const reader = new FileReader();
      reader.onload = e => {
        const thumb = document.createElement('div');
        thumb.className = 'photo-thumb';
        thumb.innerHTML = `<img src="${e.target.result}" alt="${file.name}"><div class="chk">✓</div>`;

        thumb.addEventListener('click', () => {
          thumb.classList.toggle('sel');
          /* Vista previa */
          if (thumb.classList.contains('sel')) {
            prevImg.src = e.target.result;
            prevImg.style.display = 'block';
            prevPh.style.display  = 'none';
          }
        });

        grid.appendChild(thumb);
      };
      reader.readAsDataURL(file);
    });
  });

  /* Seleccionar toda la lista */
  btnSelAll.addEventListener('click', () => {
    const thumbs = grid.querySelectorAll('.photo-thumb');
    allSelected = !allSelected;
    thumbs.forEach(t => {
      allSelected ? t.classList.add('sel') : t.classList.remove('sel');
    });
    btnSelAll.textContent = allSelected ? 'Deseleccionar Todo' : 'Seleccionar Toda la Lista';
  });

  /* Transferir */
  document.getElementById('btnTransferir').addEventListener('click', () => {
    const sel = grid.querySelectorAll('.photo-thumb.sel').length;
    if (sel === 0) {
      alert('Selecciona al menos una foto para transferir.');
      return;
    }
    alert(`${sel} imagen(es) transferida(s) correctamente.`);
  });
})();
</script>
@endpush
