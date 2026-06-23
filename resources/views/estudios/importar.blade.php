@extends('layouts.app')

@section('title', 'Importar fotos')
@section('active', 'nuevo-estudio')
@section('header-title', 'Importar fotos')
@section('header-sub')
  Sube imagenes o videos al estudio
@endsection

@push('styles')
<style>
.ip-shell{max-width:1040px}
.ip-toolbar{display:flex;align-items:center;gap:12px;margin-bottom:18px;flex-wrap:wrap}
.ip-back{
  height:44px;display:inline-flex;align-items:center;gap:8px;padding:0 16px;
  border:1px solid var(--stroke);border-radius:10px;background:var(--panel-2);
  color:var(--txt);font-size:13px;font-weight:700;cursor:pointer;
  transition:background-color 150ms,border-color 150ms;
  text-decoration:none;
}
.ip-back:hover{background:var(--panel);border-color:var(--blue)}
.ip-title{font-size:18px;font-weight:700;color:var(--txt);margin-left:auto}

.ip-dropzone{
  border:2px dashed rgba(46,123,246,.35);border-radius:18px;
  background:rgba(46,123,246,.05);
  padding:48px 24px;text-align:center;cursor:pointer;
  transition:border-color 150ms,background-color 150ms,transform 150ms;
}
.ip-dropzone:hover,.ip-dropzone.dragover{
  border-color:var(--blue);background:rgba(46,123,246,.1);transform:translateY(-1px);
}
.ip-dropzone-icon{
  width:64px;height:64px;border-radius:18px;
  background:rgba(46,123,246,.12);color:var(--blue);
  display:grid;place-items:center;margin:0 auto 16px;
}
.ip-dropzone-title{font-size:16px;font-weight:700;color:var(--txt);margin-bottom:6px}
.ip-dropzone-desc{font-size:13px;color:var(--txt-soft);margin-bottom:18px}
.ip-dropzone-hint{font-size:12px;color:var(--txt-soft);opacity:.7}
.ip-dropzone input[type=file]{display:none}

.ip-preview-grid{
  display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px;
  margin-top:20px;
}
.ip-preview-item{
  position:relative;border-radius:12px;overflow:hidden;
  background:var(--panel-2);border:1px solid var(--stroke);aspect-ratio:1;
  display:grid;place-items:center;
}
.ip-preview-item img,.ip-preview-item video{width:100%;height:100%;object-fit:cover}
.ip-preview-item .ip-file-icon{
  display:flex;flex-direction:column;align-items:center;gap:6px;color:var(--txt-soft);
}
.ip-preview-item .ip-file-icon svg{color:var(--blue)}
.ip-preview-item .ip-file-name{
  position:absolute;bottom:0;left:0;right:0;padding:6px 8px;
  font-size:11px;color:var(--txt);background:linear-gradient(0deg,rgba(0,0,0,.8),transparent);
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.ip-preview-remove{
  position:absolute;top:6px;right:6px;width:24px;height:24px;border-radius:6px;
  background:rgba(0,0,0,.5);color:#fff;border:none;display:grid;place-items:center;
  cursor:pointer;opacity:0;transition:opacity 150ms;
}
.ip-preview-item:hover .ip-preview-remove{opacity:1}

.ip-actions{
  display:flex;align-items:center;justify-content:flex-end;gap:12px;
  margin-top:24px;padding-top:18px;border-top:1px solid var(--stroke);
}
.ip-btn{
  display:inline-flex;align-items:center;gap:8px;
  height:44px;padding:0 20px;border-radius:10px;
  font-size:13px;font-weight:700;cursor:pointer;transition:all 150ms;
}
.ip-btn-secondary{
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
}
.ip-btn-secondary:hover{background:var(--panel);border-color:var(--blue)}
.ip-btn-primary{
  background:var(--blue);border:1px solid var(--blue);color:#fff;
}
.ip-btn-primary:hover{background:#2563eb;border-color:#2563eb;transform:translateY(-1px)}
.ip-btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none}

.ip-empty{
  text-align:center;padding:40px 0;color:var(--txt-soft);display:none;
}

/* ================= TEMA CLARO ================= */
html[data-theme="light"] .ip-dropzone{
  border-color:rgba(46,123,246,.35);
  background:rgba(46,123,246,.05);
}
html[data-theme="light"] .ip-dropzone:hover,
html[data-theme="light"] .ip-dropzone.dragover{
  background:rgba(46,123,246,.08);
}
html[data-theme="light"] .ip-dropzone-icon{background:rgba(46,123,246,.12)}
html[data-theme="light"] .ip-preview-item .ip-file-name{background:linear-gradient(0deg,rgba(0,0,0,.7),transparent)}
html[data-theme="light"] .ip-preview-remove{background:rgba(0,0,0,.45);color:#fff}
html[data-theme="light"] .ip-btn-primary{color:#fff}
html[data-theme="light"] .ip-btn-primary:hover{background:#2563eb;border-color:#2563eb}
</style>
@endpush

@section('content')
<div class="ip-shell rise d2">
  <div class="ip-toolbar">
    <a class="ip-back" href="{{ route('nuevo-estudio') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver a Nuevo estudio
    </a>
    <div class="ip-title">Importar fotos</div>
    <a class="ip-btn ip-btn-secondary" href="{{ route('galeria') }}" style="text-decoration:none;margin-left:auto">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
      Ver capturas en galeria
    </a>
  </div>

  <div class="ip-dropzone" id="ipDropzone">
    <input type="file" id="ipFileInput" multiple accept="image/*,video/*">
    <div class="ip-dropzone-icon">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    </div>
    <div class="ip-dropzone-title">Arrastra archivos aqui</div>
    <div class="ip-dropzone-desc">O haz clic para seleccionar imagenes y videos</div>
    <div class="ip-dropzone-hint">JPG, PNG, MP4, MOV · Maximo 50 MB por archivo</div>
  </div>

  <div class="ip-empty" id="ipEmpty">No hay archivos seleccionados.</div>

  <div class="ip-preview-grid" id="ipPreviewGrid"></div>

  <div class="ip-actions" id="ipActions" style="display:none">
    <button class="ip-btn ip-btn-secondary" type="button" id="ipClearBtn">Limpiar</button>
    <button class="ip-btn ip-btn-primary" type="button" id="ipImportBtn" disabled>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Importar <span id="ipCount">0</span> archivos
    </button>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const dropzone = document.getElementById('ipDropzone');
  const input = document.getElementById('ipFileInput');
  const grid = document.getElementById('ipPreviewGrid');
  const actions = document.getElementById('ipActions');
  const empty = document.getElementById('ipEmpty');
  const clearBtn = document.getElementById('ipClearBtn');
  const importBtn = document.getElementById('ipImportBtn');
  const countSpan = document.getElementById('ipCount');
  let files = [];

  function updateUI(){
    grid.innerHTML = '';
    if (files.length === 0){
      actions.style.display = 'none';
      empty.style.display = 'block';
      importBtn.disabled = true;
      countSpan.textContent = '0';
      return;
    }
    empty.style.display = 'none';
    actions.style.display = 'flex';
    importBtn.disabled = false;
    countSpan.textContent = files.length;
    files.forEach(function(file, idx){
      const item = document.createElement('div');
      item.className = 'ip-preview-item';
      const url = URL.createObjectURL(file);
      if (file.type.startsWith('image/')){
        item.innerHTML = '<img src="'+url+'" alt=""><div class="ip-file-name">'+file.name+'</div>';
      } else if (file.type.startsWith('video/')){
        item.innerHTML = '<video src="'+url+'"></video><div class="ip-file-name">'+file.name+'</div>';
      } else {
        item.innerHTML = '<div class="ip-file-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><span>Archivo</span></div><div class="ip-file-name">'+file.name+'</div>';
      }
      const remove = document.createElement('button');
      remove.className = 'ip-preview-remove';
      remove.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
      remove.type = 'button';
      remove.addEventListener('click', function(e){
        e.stopPropagation();
        files.splice(idx, 1);
        updateUI();
      });
      item.appendChild(remove);
      grid.appendChild(item);
    });
  }

  function addFiles(newFiles){
    Array.from(newFiles).forEach(function(file){
      if (file.type.startsWith('image/') || file.type.startsWith('video/')) files.push(file);
    });
    updateUI();
  }

  dropzone.addEventListener('click', function(){ input.click(); });
  input.addEventListener('change', function(){ addFiles(this.files); this.value = ''; });

  ['dragenter','dragover','dragleave','drop'].forEach(function(evt){
    dropzone.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); });
  });
  ['dragenter','dragover'].forEach(function(evt){
    dropzone.addEventListener(evt, function(){ dropzone.classList.add('dragover'); });
  });
  ['dragleave','drop'].forEach(function(evt){
    dropzone.addEventListener(evt, function(){ dropzone.classList.remove('dragover'); });
  });
  dropzone.addEventListener('drop', function(e){ addFiles(e.dataTransfer.files); });

  clearBtn?.addEventListener('click', function(){
    files = [];
    updateUI();
  });

  importBtn?.addEventListener('click', function(){
    if (files.length === 0) return;
    alert('Importando ' + files.length + ' archivo(s)...');
  });

  updateUI();
})();
</script>
@endpush
