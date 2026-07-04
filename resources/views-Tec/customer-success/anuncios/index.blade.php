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
.cs-row{display:flex;gap:12px;align-items:flex-end}
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
        <textarea class="cs-textarea" id="csContenido" required></textarea>
      </div>
      <div class="cs-row">
        <div class="cs-field" style="flex:1">
          <label class="cs-label">Tipo</label>
          <select class="cs-input" id="csTipo" required>
            <option value="anuncios_internos">Anuncios internos</option>
            <option value="mejoras">Mejoras en Enclaii</option>
            <option value="mantenimiento">Mantenimiento de la plataforma</option>
            <option value="politicas">Políticas</option>
          </select>
        </div>
        <div class="cs-field" style="flex:1">
          <label class="cs-label">Fecha de publicación</label>
          <input class="cs-input" type="datetime-local" id="csFecha">
        </div>
        <div class="cs-field" style="display:flex;align-items:center;gap:8px;padding-bottom:10px">
          <input type="checkbox" id="csActivo" checked>
          <label class="cs-label" for="csActivo" style="margin:0">Activo</label>
        </div>
      </div>
      <div>
        <button class="cs-btn cs-btn-primary" type="submit">Publicar anuncio</button>
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
@endsection

@push('scripts')
<script>
(function(){
  const form = document.getElementById('csForm');
  const alert = document.getElementById('csAlert');

  function showAlert(msg, type){
    alert.textContent = msg;
    alert.className = 'cs-alert ' + type;
    alert.style.display = 'block';
    setTimeout(() => { alert.style.display = 'none'; }, 4000);
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();

    const payload = {
      titulo: document.getElementById('csTitulo').value,
      contenido: document.getElementById('csContenido').value,
      tipo: document.getElementById('csTipo').value,
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
