@extends('layouts.app')

@section('title', 'Pre-registro QR')
@section('active', 'qr')
@section('header-title', 'Pre-registro QR')
@section('header-sub', 'Genera códigos seguros y recibe los datos del paciente antes de su cita')

@push('styles')
<style>
.qr-page{display:grid;gap:18px}
.qr-alert{padding:12px 15px;border-radius:12px;font-size:13px;font-weight:650;border:1px solid}
.qr-alert.ok{color:#3ddc97;background:rgba(61,220,151,.09);border-color:rgba(61,220,151,.24)}
.qr-alert.err{color:#ff7183;background:rgba(255,90,110,.09);border-color:rgba(255,90,110,.24)}
.qr-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.qr-stat{padding:17px 18px;border:1px solid var(--stroke);border-radius:16px;background:var(--card)}
.qr-stat span{font-size:12px;color:var(--txt-soft)}
.qr-stat strong{display:block;margin-top:5px;font:800 25px 'Sora',sans-serif}
.qr-grid{display:grid;grid-template-columns:minmax(280px,.75fr) minmax(0,1.55fr);gap:18px;align-items:start}
.qr-card{padding:20px;border:1px solid var(--stroke);border-radius:18px;background:var(--card)}
.qr-card h2{font:700 16px 'Sora',sans-serif}
.qr-card-sub{margin-top:4px;font-size:12.5px;line-height:1.5;color:var(--txt-soft)}
.qr-create{display:grid;gap:13px;margin-top:18px}
.qr-create label{display:grid;gap:7px;font-size:12px;font-weight:700;color:var(--txt-soft)}
.qr-create select{padding:11px 12px;border:1px solid var(--stroke-strong);border-radius:10px;background:var(--panel-2);color:var(--txt);font:inherit}
.qr-primary{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 14px;border:0;border-radius:11px;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-size:13px;font-weight:750;cursor:pointer}
.qr-note{display:flex;gap:8px;margin-top:15px;padding:11px;border-radius:11px;background:rgba(14,165,233,.07);color:var(--txt-soft);font-size:11.5px;line-height:1.5}
.qr-links{display:grid;gap:12px;margin-top:16px}
.qr-link{display:grid;grid-template-columns:116px minmax(0,1fr);gap:14px;padding:13px;border:1px solid var(--stroke);border-radius:14px;background:var(--panel-2)}
.qr-image-wrap{position:relative;width:116px;height:116px}
.qr-image{width:116px;height:116px;padding:5px;border-radius:10px;background:#fff}
.qr-link.inactive .qr-image{opacity:.28;filter:grayscale(1)}
.qr-image-state{position:absolute;inset:0;display:grid;place-items:center;padding:8px;text-align:center;color:#fff;font-size:10px;font-weight:800;text-transform:uppercase}
.qr-link-info{min-width:0;display:flex;flex-direction:column;justify-content:center;gap:7px}
.qr-link-top{display:flex;align-items:center;justify-content:space-between;gap:8px}
.qr-link-top strong{font-size:12.5px}
.qr-status{display:inline-flex;padding:3px 8px;border-radius:99px;font-size:9.5px;font-weight:750}
.qr-status.active,.qr-status.accepted{color:#3ddc97;background:rgba(61,220,151,.12)}
.qr-status.pending,.qr-status.submitted{color:#f9b34b;background:rgba(245,158,45,.12)}
.qr-status.expired,.qr-status.revoked,.qr-status.rejected{color:#ff7183;background:rgba(255,90,110,.12)}
.qr-url{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:10.5px;color:var(--txt-soft)}
.qr-state-note{font-size:10.5px;line-height:1.4;color:var(--txt-soft)}
.qr-actions{display:flex;gap:7px;flex-wrap:wrap}
.qr-action{padding:7px 9px;border:1px solid var(--stroke-strong);border-radius:8px;color:var(--cyan);font-size:10.5px;font-weight:700;cursor:pointer}
.qr-action.danger{color:var(--red)}
.qr-empty{padding:25px 10px;text-align:center;color:var(--txt-soft);font-size:12.5px}
.qr-prereg-list{display:grid;gap:12px;margin-top:16px}
.qr-prereg{border:1px solid var(--stroke);border-radius:14px;background:var(--panel-2);overflow:hidden}
.qr-prereg summary{list-style:none;display:grid;grid-template-columns:minmax(160px,1.3fr) 1fr auto;gap:12px;align-items:center;padding:14px;cursor:pointer}
.qr-prereg summary::-webkit-details-marker{display:none}
.qr-person strong{display:block;font-size:13px}
.qr-person span,.qr-meta{font-size:10.5px;color:var(--txt-soft)}
.qr-detail{padding:0 14px 15px;border-top:1px solid var(--stroke)}
.qr-data{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:14px}
.qr-data div{padding:10px;border-radius:10px;background:rgba(110,160,255,.055)}
.qr-data b{display:block;margin-bottom:3px;font-size:10px;color:var(--txt-soft)}
.qr-data span{font-size:11.5px;white-space:pre-wrap}
.qr-data .wide{grid-column:span 3}
.qr-warning{margin-top:12px;padding:9px 11px;border-radius:9px;color:#f9b34b;background:rgba(245,158,45,.09);font-size:11px}
.qr-review-actions{display:flex;justify-content:flex-end;gap:9px;margin-top:14px}
.qr-review-actions button{padding:9px 13px;border-radius:9px;font-size:11.5px;font-weight:750;cursor:pointer}
.qr-accept{border:0;background:#16a86b;color:#fff}
.qr-reject{border:1px solid rgba(255,90,110,.35);color:var(--red)}
@media(max-width:1050px){.qr-grid{grid-template-columns:1fr}.qr-links{grid-template-columns:repeat(2,1fr)}}
@media(max-width:720px){.qr-stats{grid-template-columns:1fr}.qr-links{grid-template-columns:1fr}.qr-prereg summary{grid-template-columns:1fr auto}.qr-meta{display:none}.qr-data{grid-template-columns:1fr 1fr}.qr-data .wide{grid-column:span 2}}
@media(max-width:480px){.qr-link{grid-template-columns:90px minmax(0,1fr)}.qr-image-wrap,.qr-image{width:90px;height:90px}.qr-data{grid-template-columns:1fr}.qr-data .wide{grid-column:span 1}}
</style>
@endpush

@section('content')
@php
  $activeLinks = $links->filter(fn($link) => $link->status === 'active' && $link->expires_at->isFuture());
  $pendingCount = $preregistrations->where('status', 'pending')->count();
  $acceptedCount = $preregistrations->where('status', 'accepted')->count();
  $statusLabels = [
    'active' => 'Activo',
    'submitted' => 'Utilizado',
    'expired' => 'Vencido',
    'revoked' => 'Cancelado',
    'pending' => 'Pendiente',
    'accepted' => 'Aceptado',
    'rejected' => 'Rechazado',
  ];
@endphp

<div class="qr-page">
  @if(session('success'))<div class="qr-alert ok">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="qr-alert err">{{ session('error') }}</div>@endif

  <div class="qr-stats">
    <div class="qr-stat"><span>QR activos</span><strong>{{ $activeLinks->count() }}</strong></div>
    <div class="qr-stat"><span>Pre-registros pendientes</span><strong>{{ $pendingCount }}</strong></div>
    <div class="qr-stat"><span>Expedientes aceptados</span><strong>{{ $acceptedCount }}</strong></div>
  </div>

  <div class="qr-grid">
    <div class="qr-card">
      <h2>Generar nuevo QR</h2>
      <p class="qr-card-sub">Entrega este código al paciente para que complete sus datos desde su celular.</p>
      <form class="qr-create" method="POST" action="{{ route('qr.links.store') }}">
        @csrf
        <label>
          Vigencia del enlace
          <select name="expires_in_hours">
            <option value="24">24 horas</option>
            <option value="48" selected>48 horas</option>
            <option value="168">7 días</option>
          </select>
        </label>
        <button class="qr-primary" type="submit">
          <span style="font-size:18px">＋</span> Generar código QR
        </button>
      </form>
      <div class="qr-note"><span>ⓘ</span><span>Cada código acepta un solo envío. Puedes cancelarlo antes de que el paciente lo utilice.</span></div>

      <div class="qr-links">
        @forelse($links as $link)
          @php
            $displayStatus = $link->status === 'active' && $link->expires_at->isPast() ? 'expired' : $link->status;
            $publicUrl = route('qr.public.show', ['token' => $link->token]);
            $isActive = $displayStatus === 'active' && !$link->preregistration;
            $statusDate = match ($displayStatus) {
              'submitted' => 'Utilizado: '.(format_user_date($link->submitted_at) ?: '—'),
              'revoked' => 'Cancelado: '.(format_user_date($link->revoked_at) ?: '—'),
              'expired' => 'Venció: '.format_user_date($link->expires_at),
              default => 'Vence: '.format_user_date($link->expires_at),
            };
          @endphp
          <div class="qr-link {{ $isActive ? '' : 'inactive' }}">
            <div class="qr-image-wrap">
              <img class="qr-image" src="{{ route('qr.links.image', $link) }}" alt="Código QR de pre-registro">
              @unless($isActive)
                <span class="qr-image-state">{{ $statusLabels[$displayStatus] ?? 'No disponible' }}</span>
              @endunless
            </div>
            <div class="qr-link-info">
              <div class="qr-link-top">
                <strong>QR #{{ $link->id }}</strong>
                <span class="qr-status {{ $displayStatus }}">{{ $statusLabels[$displayStatus] ?? ucfirst($displayStatus) }}</span>
              </div>
              @if($isActive)
                <div class="qr-url" title="{{ $publicUrl }}">{{ $publicUrl }}</div>
              @else
                <div class="qr-state-note">
                  {{ $displayStatus === 'submitted'
                    ? 'Este QR ya recibió la información del paciente.'
                    : 'Este enlace ya no acepta información. Genera uno nuevo para compartirlo.' }}
                </div>
              @endif
              <div style="font-size:10px;color:var(--txt-soft)">{{ $statusDate }}</div>
              <div class="qr-actions">
                @if($isActive)
                  <button type="button" class="qr-action" data-copy-url="{{ $publicUrl }}">Copiar enlace</button>
                  <a class="qr-action" href="{{ route('qr.links.image', ['link' => $link, 'download' => 1]) }}">Descargar</a>
                  <form method="POST" action="{{ route('qr.links.destroy', $link) }}" data-delete-confirmed="true">
                    @csrf @method('DELETE')
                    <button class="qr-action danger" type="submit" onclick="return confirm('¿Invalidar este QR? El paciente ya no podrá utilizar su enlace.')">Invalidar QR</button>
                  </form>
                @else
                  <form method="POST" action="{{ route('qr.links.store') }}">
                    @csrf
                    <input type="hidden" name="expires_in_hours" value="48">
                    <button class="qr-action" type="submit">Generar reemplazo</button>
                  </form>
                @endif
                <form method="POST" action="{{ route('qr.links.archive', $link) }}" data-delete-confirmed="true">
                  @csrf @method('DELETE')
                  <button class="qr-action danger" type="submit" onclick="return confirm('¿Eliminar este QR de la lista? Los datos del paciente y su expediente se conservarán.')">Eliminar</button>
                </form>
              </div>
            </div>
          </div>
        @empty
          <div class="qr-empty">Todavía no has generado códigos QR.</div>
        @endforelse
      </div>
    </div>

    <div class="qr-card">
      <h2>Pre-registros recibidos</h2>
      <p class="qr-card-sub">Revisa la información antes de crear el expediente definitivo.</p>
      <div class="qr-prereg-list">
        @forelse($preregistrations as $item)
          <details class="qr-prereg" @if(session('new_qr_link_id') === $item->registration_link_id) open @endif>
            <summary>
              <div class="qr-person">
                <strong>{{ $item->nombre_completo }}</strong>
                <span>{{ $item->telefono }} · {{ $item->email ?: 'Sin correo' }}</span>
              </div>
              <div class="qr-meta">Recibido {{ $item->created_at->diffForHumans() }}</div>
              <span class="qr-status {{ $item->status }}">{{ $statusLabels[$item->status] ?? ucfirst($item->status) }}</span>
            </summary>
            <div class="qr-detail">
              @if($possibleDuplicates[$item->id] ?? false)
                <div class="qr-warning">⚠ Existe un paciente con el mismo teléfono o correo. Revisa posibles duplicados antes de aceptar.</div>
              @endif
              <div class="qr-data">
                <div><b>Fecha de nacimiento</b><span>{{ format_user_date($item->fecha_nacimiento) }} ({{ $item->edad }} años)</span></div>
                <div><b>Sexo</b><span>{{ ucfirst($item->sexo ?: 'No indicado') }}</span></div>
                <div><b>Peso / altura</b><span>{{ $item->peso ?: '—' }} kg · {{ $item->altura ?: '—' }} m</span></div>
                <div class="wide"><b>Dirección</b><span>{{ $item->direccion ?: 'No indicada' }}</span></div>
                <div><b>Procedimiento</b><span>{{ $item->procedimiento ?: 'No indicado' }}</span></div>
                <div><b>Identificación</b><span>{{ $item->identificacion ?: 'No indicada' }}</span></div>
                <div><b>Consentimiento</b><span>{{ format_user_date($item->consent_accepted_at) }}</span></div>
                <div class="wide"><b>Motivo de consulta</b><span>{{ $item->motivo_consulta ?: 'No indicado' }}</span></div>
                <div class="wide"><b>Alergias</b><span>{{ $item->alergias ?: 'Ninguna indicada' }}</span></div>
                <div class="wide"><b>Enfermedades</b><span>{{ $item->enfermedades ?: 'Ninguna indicada' }}</span></div>
                <div class="wide"><b>Medicamentos actuales</b><span>{{ $item->medicamentos_actuales ?: 'Ninguno indicado' }}</span></div>
                <div class="wide"><b>Antecedentes médicos</b><span>{{ $item->antecedentes_medicos ?: 'No indicados' }}</span></div>
                @if($item->observaciones)<div class="wide"><b>Observaciones</b><span>{{ $item->observaciones }}</span></div>@endif
              </div>
              @if($item->status === 'pending')
                <div class="qr-review-actions">
                  <form method="POST" action="{{ route('qr.preregistrations.reject', $item) }}">
                    @csrf
                    <button class="qr-reject" type="submit" onclick="return confirm('¿Rechazar este pre-registro?')">Rechazar</button>
                  </form>
                  <form method="POST" action="{{ route('qr.preregistrations.accept', $item) }}">
                    @csrf
                    <button class="qr-accept" type="submit" onclick="return confirm('¿Crear el expediente de este paciente?')">Aceptar y crear paciente</button>
                  </form>
                </div>
              @elseif($item->patient)
                <div class="qr-review-actions"><a class="qr-action" href="{{ route('pacientes.edit', $item->patient) }}">Abrir expediente {{ $item->patient->folio }}</a></div>
              @endif
            </div>
          </details>
        @empty
          <div class="qr-empty">Los formularios enviados por pacientes aparecerán aquí.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-copy-url]').forEach(button => {
  button.addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(button.dataset.copyUrl);
      const original = button.textContent;
      button.textContent = 'Copiado';
      setTimeout(() => { button.textContent = original; }, 1500);
    } catch (error) {
      window.prompt('Copia este enlace:', button.dataset.copyUrl);
    }
  });
});
</script>
@endpush
