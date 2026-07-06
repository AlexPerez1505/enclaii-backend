@extends('layouts.app')

@section('title', 'Anuncios - Customer Success')
@section('active', 'customer-success')
@section('header-title', 'Customer Success')
@section('header-sub')
  Gestión de anuncios y comunicaciones
@endsection

@section('sidebar')
  @include('customer-success.partials.sidebar')
@endsection

@section('bottom-nav')
  @include('customer-success.partials.bottom-nav')
@endsection

@push('styles')
<style>
.cs-shell{max-width:900px}
.cs-card{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:16px;
  padding:24px;margin-bottom:20px;
}
.cs-card-title{font-size:16px;font-weight:700;color:var(--txt);margin-bottom:16px}
.cs-form{display:grid;gap:14px}
.cs-field{display:grid;gap:6px}
.cs-label{font-size:12px;font-weight:600;color:var(--txt-soft)}
.cs-input,.cs-textarea{
  width:100%;background:var(--panel);border:1px solid var(--stroke-strong);border-radius:10px;
  padding:10px 12px;color:var(--txt);font-size:13px;outline:none;box-sizing:border-box;
}
.cs-textarea{min-height:120px;resize:vertical}
.cs-input:focus,.cs-textarea:focus{border-color:var(--blue)}
.cs-row{display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap}
.cs-field-group{display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap}
.cs-btn{
  display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 18px;
  border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all 150ms;
  border:none;text-decoration:none;
}
.cs-btn-primary{background:var(--blue);color:#fff}
.cs-btn-primary:hover{background:var(--cyan)}
.cs-btn-danger{background:rgba(220,38,38,.12);color:#ef4444}
.cs-btn-danger:hover{background:rgba(220,38,38,.2)}
.cs-btn-secondary{background:var(--panel);border:1px solid var(--stroke);color:var(--txt-soft)}
.cs-btn-secondary:hover{border-color:var(--blue);color:var(--blue)}
.cs-table{width:100%;border-collapse:collapse}
.cs-table th,.cs-table td{padding:12px;text-align:left;font-size:13px;border-bottom:1px solid var(--stroke)}
.cs-table th{color:var(--txt-soft);font-weight:600}
.cs-table td{color:var(--txt)}
.cs-empty{text-align:center;padding:40px;color:var(--txt-soft)}
.cs-alert{padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:13px;display:none}
.cs-alert.success{background:rgba(34,197,94,.12);color:#22c55e}
.cs-alert.error{background:rgba(220,38,38,.12);color:#ef4444}

/* Editor WYSIWYG */
.cs-editor-wrap{border:1px solid var(--stroke-strong);border-radius:10px;background:var(--panel);overflow:hidden}
.cs-editor-toolbar{display:flex;align-items:center;gap:4px;padding:8px 12px;border-bottom:1px solid var(--stroke);background:var(--panel-2);flex-wrap:wrap}
.cs-editor-toolbar button{width:32px;height:32px;display:grid;place-items:center;border-radius:7px;border:0;background:transparent;color:var(--txt-soft);cursor:pointer;transition:all 150ms}
.cs-editor-toolbar button:hover{color:var(--cyan);background:rgba(56,199,244,.1)}
.cs-editor-toolbar button.active{color:var(--cyan);background:rgba(56,199,244,.18);box-shadow:inset 0 0 0 1px rgba(56,199,244,.45)}
.cs-editor-toolbar button svg{width:16px;height:16px}
.cs-editor-toolbar .sep{width:1px;height:22px;background:var(--stroke);margin:0 4px}
.cs-editor-content{min-height:160px;max-height:320px;overflow-y:auto;padding:14px 16px;font-size:13px;line-height:1.6;color:var(--txt);outline:none}
.cs-editor-content:empty:before{content:attr(data-placeholder);color:var(--txt-soft);opacity:.7}
.cs-editor-content ul{list-style:disc;padding-left:20px}
.cs-editor-content ol{list-style:decimal;padding-left:20px}
.cs-editor-content a{color:var(--cyan);text-decoration:underline}

/* Canales */
.cs-channels{display:flex;gap:16px;flex-wrap:wrap}
.cs-channel{display:flex;align-items:center;gap:8px;cursor:pointer}
.cs-channel input{width:16px;height:16px;cursor:pointer}

/* Vista previa */
.pv-ov{position:fixed;inset:0;z-index:3000;background:rgba(8,12,18,.78);backdrop-filter:blur(4px);display:none;flex-direction:column}
.pv-ov.open{display:flex}
.pv-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:13px 20px;background:var(--panel);border-bottom:1px solid var(--stroke);flex:none}
.pv-title{font-size:14px;font-weight:700;color:var(--txt)}
.pv-scroll{flex:1;overflow:auto;padding:26px 16px;display:flex;justify-content:center;align-items:flex-start}
.pv-card{width:100%;max-width:640px;background:var(--panel);border:1px solid var(--stroke);border-radius:16px;padding:28px;color:var(--txt)}
.pv-card h2{margin:0 0 12px;font-size:18px;font-weight:800}
.pv-card .meta{font-size:12px;color:var(--txt-soft);margin-bottom:16px}
.pv-card .body{font-size:13px;line-height:1.7;color:var(--txt)}
.pv-card .body ul,.pv-card .body ol{padding-left:20px}
</style>
@endpush

@section('content')
<div class="cs-shell rise d1">

  <div class="cs-card">
    <div class="cs-card-title">Nuevo anuncio</div>
    <div class="cs-alert" id="csAlert"></div>
    <form class="cs-form" id="csForm">
      @csrf
      <div class="cs-field">
        <label class="cs-label">Título</label>
        <input class="cs-input" type="text" id="csTitulo" required maxlength="255">
      </div>

      <div class="cs-field">
        <label class="cs-label">Contenido</label>
        <div class="cs-editor-wrap">
          <div class="cs-editor-toolbar" id="csToolbar">
            <button type="button" data-cmd="bold" title="Negrita"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg></button>
            <button type="button" data-cmd="italic" title="Cursiva"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg></button>
            <button type="button" data-cmd="underline" title="Subrayado"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/></svg></button>
            <span class="sep"></span>
            <button type="button" data-cmd="insertUnorderedList" title="Lista con viñetas"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
            <button type="button" data-cmd="insertOrderedList" title="Lista numerada"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg></button>
            <span class="sep"></span>
            <button type="button" data-cmd="createLink" title="Enlace"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></button>
            <button type="button" data-cmd="removeFormat" title="Limpiar formato"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18"/><path d="M6 7l2.5 14h7L18 7"/><path d="M9 7l3 12"/><path d="M15 7l-3 12"/></svg></button>
          </div>
          <div class="cs-editor-content" id="csContenido" contenteditable="true" data-placeholder="Escribe el contenido del anuncio..." required></div>
        </div>
        <input type="hidden" id="csContenidoHtml" name="contenido">
      </div>

      <div class="cs-row">
        <div class="cs-field" style="flex:1;min-width:180px">
          <label class="cs-label">Tipo</label>
          <select class="cs-input" id="csTipo" required>
            <option value="anuncios_internos">Anuncios internos</option>
            <option value="mejoras">Mejoras en Enclaii</option>
            <option value="mantenimiento">Mantenimiento de la plataforma</option>
            <option value="politicas">Políticas</option>
          </select>
        </div>
        <div class="cs-field" style="flex:1;min-width:180px">
          <label class="cs-label">Público objetivo</label>
          <select class="cs-input" id="csPublico" required>
            <option value="todos">Todos</option>
            <option value="doctores">Doctores</option>
            <option value="administradores">Administradores</option>
          </select>
        </div>
        <div class="cs-field" style="flex:1;min-width:180px">
          <label class="cs-label">Fecha de publicación</label>
          <input class="cs-input" type="datetime-local" id="csFecha">
        </div>
        <div class="cs-field" style="display:flex;align-items:center;gap:8px;padding-bottom:10px">
          <input type="checkbox" id="csActivo" checked>
          <label class="cs-label" for="csActivo" style="margin:0">Activo</label>
        </div>
      </div>

      <div class="cs-field">
        <label class="cs-label">Canales de notificación</label>
        <div class="cs-channels">
          <label class="cs-channel">
            <input type="checkbox" name="csCanales" value="web" checked>
            <span>Web</span>
          </label>
          <label class="cs-channel">
            <input type="checkbox" name="csCanales" value="email">
            <span>Correo electrónico</span>
          </label>
          <label class="cs-channel">
            <input type="checkbox" name="csCanales" value="push">
            <span>Push (requiere configuración)</span>
          </label>
        </div>
      </div>

      <div style="display:flex;gap:10px">
        <button class="cs-btn cs-btn-primary" type="submit">Publicar anuncio</button>
        <button class="cs-btn cs-btn-secondary" type="button" id="csPreview">Vista previa</button>
      </div>
    </form>
  </div>

  @php
    $tipoLabels = [
        'anuncios_internos' => 'Anuncios internos',
        'mejoras' => 'Mejoras en Enclaii',
        'mantenimiento' => 'Mantenimiento de la plataforma',
        'politicas' => 'Políticas',
    ];
    $publicoLabels = [
        'todos' => 'Todos',
        'doctores' => 'Doctores',
        'administradores' => 'Administradores',
    ];
  @endphp

  <div class="cs-card">
    <div class="cs-card-title">Anuncios publicados</div>
    @if($anuncios->isEmpty())
      <div class="cs-empty">No hay anuncios publicados.</div>
    @else
      <table class="cs-table">
        <thead>
          <tr>
            <th>Título</th>
            <th>Tipo</th>
            <th>Público</th>
            <th>Canales</th>
            <th>Autor</th>
            <th>Publicación</th>
            <th>Estado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($anuncios as $anuncio)
          <tr data-id="{{ $anuncio->id }}">
            <td>{{ $anuncio->titulo }}</td>
            <td>{{ $tipoLabels[$anuncio->tipo] ?? $anuncio->tipo }}</td>
            <td>{{ $publicoLabels[$anuncio->publico_objetivo] ?? $anuncio->publico_objetivo }}</td>
            <td>{{ is_array($anuncio->canales) ? implode(', ', $anuncio->canales) : 'web' }}</td>
            <td>{{ $anuncio->user->name ?? '—' }}</td>
            <td>{{ $anuncio->fecha_publicacion?->format('d/m/Y H:i') ?? '—' }}</td>
            <td>{{ $anuncio->activo ? 'Activo' : 'Inactivo' }}</td>
            <td>
              <button class="cs-btn cs-btn-danger cs-delete" type="button">Eliminar</button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div style="margin-top:16px">
        {{ $anuncios->links() }}
      </div>
    @endif
  </div>

</div>

{{-- Vista previa --}}
<div class="pv-ov" id="pvOverlay">
  <div class="pv-bar">
    <span class="pv-title">Vista previa del anuncio</span>
    <button class="cs-btn cs-btn-secondary" type="button" id="pvClose">Cerrar</button>
  </div>
  <div class="pv-scroll">
    <div class="pv-card">
      <h2 id="pvTitle">Título</h2>
      <div class="meta" id="pvMeta">Tipo • Público objetivo</div>
      <div class="body" id="pvBody">Contenido...</div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const form = document.getElementById('csForm');
  const alert = document.getElementById('csAlert');
  const editor = document.getElementById('csContenido');
  const hiddenInput = document.getElementById('csContenidoHtml');
  const toolbar = document.getElementById('csToolbar');
  const pvOverlay = document.getElementById('pvOverlay');
  const pvClose = document.getElementById('pvClose');
  const pvBtn = document.getElementById('csPreview');

  function showAlert(msg, type){
    alert.textContent = msg;
    alert.className = 'cs-alert ' + type;
    alert.style.display = 'block';
    setTimeout(() => { alert.style.display = 'none'; }, 4000);
  }

  function refreshToolbar(){
    toolbar.querySelectorAll('button[data-cmd]').forEach(btn => {
      const cmd = btn.dataset.cmd;
      if (['bold','italic','underline','insertUnorderedList','insertOrderedList'].includes(cmd)) {
        try { btn.classList.toggle('active', document.queryCommandState(cmd)); } catch(e){}
      }
    });
  }

  toolbar.querySelectorAll('button[data-cmd]').forEach(btn => {
    btn.addEventListener('mousedown', (e) => {
      e.preventDefault();
      const cmd = btn.dataset.cmd;
      if (cmd === 'createLink') {
        const url = prompt('Ingresa la URL del enlace:');
        if (url) document.execCommand('createLink', false, url);
      } else {
        document.execCommand(cmd, false, null);
      }
      editor.focus();
      refreshToolbar();
    });
  });

  editor.addEventListener('keyup', refreshToolbar);
  editor.addEventListener('mouseup', refreshToolbar);
  editor.addEventListener('blur', () => hiddenInput.value = editor.innerHTML);
  editor.addEventListener('input', () => hiddenInput.value = editor.innerHTML);

  function openPreview(){
    const titulo = document.getElementById('csTitulo').value || 'Sin título';
    const tipo = document.getElementById('csTipo');
    const tipoLabel = tipo.options[tipo.selectedIndex].text;
    const publico = document.getElementById('csPublico');
    const publicoLabel = publico.options[publico.selectedIndex].text;
    document.getElementById('pvTitle').textContent = titulo;
    document.getElementById('pvMeta').textContent = tipoLabel + ' • ' + publicoLabel;
    document.getElementById('pvBody').innerHTML = editor.innerHTML || '<p style="color:var(--txt-soft)">Sin contenido</p>';
    pvOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closePreview(){
    pvOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  pvBtn.addEventListener('click', openPreview);
  pvClose.addEventListener('click', closePreview);
  pvOverlay.addEventListener('click', (e) => { if (e.target === pvOverlay) closePreview(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && pvOverlay.classList.contains('open')) closePreview(); });

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    hiddenInput.value = editor.innerHTML;

    const canales = Array.from(document.querySelectorAll('input[name="csCanales"]:checked')).map(cb => cb.value);

    const payload = {
      titulo: document.getElementById('csTitulo').value,
      contenido: editor.innerHTML,
      tipo: document.getElementById('csTipo').value,
      publico_objetivo: document.getElementById('csPublico').value,
      canales: canales,
      fecha_publicacion: document.getElementById('csFecha').value || null,
      activo: document.getElementById('csActivo').checked,
    };

    try {
      const res = await fetch('/api/customer-success/anuncios', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(payload),
      });

      if (res.ok) {
        showAlert('Anuncio enviado correctamente.', 'success');
        form.reset();
        editor.innerHTML = '';
        setTimeout(() => location.reload(), 800);
      } else {
        const data = await res.json();
        showAlert(data.message || 'Error al publicar.', 'error');
      }
    } catch (err) {
      showAlert('Error de conexión.', 'error');
    }
  });

  document.querySelectorAll('.cs-delete').forEach(btn => {
    btn.addEventListener('click', async function(){
      const row = this.closest('tr');
      const id = row.dataset.id;
      if (!confirm('¿Eliminar este anuncio?')) return;

      try {
        const res = await fetch('/api/customer-success/anuncios/' + id, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
        });

        if (res.ok) {
          row.remove();
          showAlert('Anuncio eliminado.', 'success');
        } else {
          const data = await res.json();
          showAlert(data.message || 'Error al eliminar.', 'error');
        }
      } catch (err) {
        showAlert('Error de conexión.', 'error');
      }
    });
  });
})();
</script>
@endpush
