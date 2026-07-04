@extends('layouts.app')
@section('active', 'soporte')
@section('title', 'Soporte ')
@section('header-title', 'Soporte ')
@section('header-sub', 'Estamos aquí para ayudarte rápido.')

@push('styles')
<style>
/* ── Soporte layout ── */
.sop-grid{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
.sop-main{display:flex;flex-direction:column;gap:24px}

/* Sidebar derecho */
.sop-side{display:flex;flex-direction:column;gap:20px}

/* Card base */
.sop-card{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:24px}
.sop-card h2{font-size:16px;font-weight:700;margin-bottom:4px}
.sop-card .sub{font-size:13px;color:var(--txt-soft);margin-bottom:16px}

/* Canales */
.sop-canal{
  display:flex;align-items:center;gap:12px;
  padding:12px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);margin-bottom:10px;
  transition:background .15s;cursor:pointer;
}
.sop-canal:hover{background:rgba(110,160,255,.06)}
.sop-canal .icon-wrap{
  width:38px;height:38px;border-radius:50%;
  display:grid;place-items:center;flex-shrink:0;
}
.sop-canal .icon-wrap.wa{background:rgba(37,211,102,.15)}
.sop-canal .icon-wrap.phone{background:rgba(168,130,255,.15)}
.sop-canal .canal-info{flex:1}
.sop-canal .canal-info strong{font-size:13px;display:block}
.sop-canal .canal-info span{font-size:12px;color:var(--txt-soft)}
.sop-canal .canal-arrow{color:var(--txt-soft);font-size:14px}

/* Mis Tickets card */
.sop-tickets-btn{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:20px;
  display:flex;flex-direction:column;align-items:center;text-align:center;gap:10px;
}
.sop-tickets-btn svg{color:var(--blue)}
.sop-tickets-btn h3{font-size:15px;font-weight:700;margin:0}
.sop-tickets-btn p{font-size:12px;color:var(--txt-soft);margin:0}
.sop-tickets-btn .btn-tickets{
  width:100%;padding:11px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;color:var(--txt);
  font-size:13px;font-weight:600;text-decoration:none;
  display:flex;align-items:center;justify-content:center;gap:6px;
  transition:background .15s;margin-top:4px;
}
.sop-tickets-btn .btn-tickets:hover{background:rgba(110,160,255,.1)}

/* Temas de ayuda - Accordion */
.sop-temas h2{font-size:16px;font-weight:700;margin-bottom:16px}
.sop-accordion{border:1px solid var(--stroke);border-radius:var(--r-lg);overflow:hidden}
.sop-acc-item{border-bottom:1px solid var(--stroke)}
.sop-acc-item:last-child{border-bottom:0}
.sop-acc-btn{
  width:100%;display:flex;align-items:center;justify-content:space-between;
  padding:16px 20px;border:none;background:transparent;color:var(--txt);
  font-size:14px;font-weight:500;cursor:pointer;text-align:left;
  transition:background .15s;
}
.sop-acc-btn:hover{background:rgba(110,160,255,.04)}
.sop-acc-btn .acc-arrow{
  transition:transform .2s ease;color:var(--blue);flex-shrink:0;
}
.sop-acc-item.open .sop-acc-btn .acc-arrow{transform:rotate(180deg)}
.sop-acc-body{
  max-height:0;overflow:hidden;transition:max-height .25s ease;
  padding:0 20px;
}
.sop-acc-item.open .sop-acc-body{max-height:200px;padding:0 20px 16px}
.sop-acc-body p{font-size:13px;color:var(--txt-soft);line-height:1.6;margin:0}

/* Phone call card */
.call-overlay{
  display:none;position:fixed;inset:0;z-index:9000;
  background:rgba(0,0,0,.45);backdrop-filter:blur(4px);
  place-items:center;
}
.call-overlay.active{display:grid}
.call-card{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:16px;
  padding:32px 36px;box-shadow:0 16px 48px rgba(0,0,0,.2);
  text-align:center;min-width:320px;max-width:380px;
  animation:fbSlideUp .3s ease;position:relative;
}
.call-card .call-close{
  position:absolute;top:10px;right:14px;background:none;border:none;
  color:var(--txt-soft);cursor:pointer;font-size:18px;line-height:1;
}
.call-card .call-close:hover{color:var(--txt)}
.call-card .call-icon{
  width:56px;height:56px;border-radius:50%;margin:0 auto 16px;
  background:rgba(74,222,128,.15);display:grid;place-items:center;
}
.call-card .call-icon svg{color:#16a34a}
.call-card h3{font-size:16px;font-weight:700;margin:0 0 6px}
.call-card .call-number{font-size:22px;font-weight:800;color:var(--blue);margin:12px 0 6px;letter-spacing:1px}
.call-card .call-sub{font-size:12px;color:var(--txt-soft);margin:0 0 20px}
.call-card .call-btn{
  display:inline-flex;align-items:center;gap:8px;
  padding:12px 28px;border:none;border-radius:99px;
  background:linear-gradient(135deg,#16a34a,#4ade80);color:#fff;
  font-size:14px;font-weight:700;cursor:pointer;
  transition:opacity .15s,transform .15s;text-decoration:none;
}
.call-card .call-btn:hover{opacity:.9;transform:scale(1.03)}

@keyframes fbSlideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
</style>
@endpush

@section('content')
<div class="sop-grid">

  {{-- ============ COLUMNA PRINCIPAL ============ --}}
  <div class="sop-main">

    {{-- Formulario de ticket --}}
    <div class="sop-card">
      <h2>Crear ticket</h2>
      <p class="sub">Selecciona una categoría y proporciona los detalles de tu problema.</p>

      @include('soporte._ticket_form', ['latestTicket' => $latestTicket ?? null])
    </div>

    {{-- Temas de ayuda (acordeon) --}}
    <div class="sop-card sop-temas">
      <h2>Consultar los temas de ayuda</h2>
      <div class="sop-accordion">

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Error al subir archivos</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Verifica que el archivo no supere los 10MB y sea de un formato compatible (JPG, PNG, PDF, MP4). Si el problema persiste, limpia la cache del navegador e intenta nuevamente.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>No puedo iniciar sesion</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>1. Verifica que tu correo y contrasena sean correctos.<br>2. Limpia la cache de tu navegador.<br>3. Usa la opcion "Recuperar contrasena" si la olvidaste.<br>4. Si el problema persiste, contacta a soporte tecnico.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Error al exportar datos</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Asegurate de tener al menos un estudio o reporte seleccionado. Intenta con un formato diferente (PDF o Excel). Si el archivo se descarga vacio, recarga la pagina y vuelve a intentar.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Problemas de conexion</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>1. Verifica tu conexion a internet.<br>2. Intenta recargar la pagina.<br>3. Limpia la cache del navegador.<br>4. Si usas VPN, intenta desactivarla temporalmente.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Como generar un reporte con IA</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Ve a la seccion "Reportes" en el menu lateral, selecciona "Generar reporte" y elige el estudio. Nuestro asistente de IA te ayudara a redactar el informe. Puedes editar y personalizar el reporte antes de guardarlo.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Administrar mi cuenta</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Accede a Configuracion desde el menu lateral para cambiar tu nombre, correo, contrasena, foto de perfil y preferencias de notificaciones.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Seguridad y privacidad</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Todos los datos medicos estan encriptados. Puedes activar la verificacion en dos pasos desde Configuracion > Seguridad. Nunca compartimos informacion de pacientes con terceros.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Facturacion y pagos</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Consulta tu historial de pagos y descarga facturas desde Configuracion > Facturacion. Si necesitas cambiar tu metodo de pago o plan, contacta al equipo de ventas.</p>
          </div>
        </div>

      </div>
    </div>

  </div>

  {{-- ============ SIDEBAR DERECHO ============ --}}
  <div class="sop-side">

    {{-- Mis Tickets --}}
    <div class="sop-tickets-btn">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H2V6h2a2 2 0 0 0 2-2V2"/><path d="M22 12h-2a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2V6h-2a2 2 0 0 1-2-2V2"/><path d="M7 2h10"/><path d="M7 22h10"/><rect x="7" y="6" width="10" height="12" rx="1"/></svg>
      <div>
        <h3>Mis Tickets</h3>
        <p>Consulta el historial y estado de todos tus tickets de soporte.</p>
      </div>
      <a href="{{ route('soporte.tickets') }}" class="btn-tickets">Ver tickets →</a>
    </div>

    {{-- Canales de soporte --}}
    <div class="sop-card">
      <h2>¿Es urgente? Contáctanos por otros canales</h2>
      <p class="sub">Elige el medio que prefieras para obtener ayuda más rápido.</p>

      <div class="sop-canales">
        <a href="{{ route('mensajes') }}?chat=soporte" class="sop-canal" style="text-decoration:none;color:inherit">
          <div class="icon-wrap wa">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
          </div>
          <div class="canal-info">
            <strong>Chat de soporte</strong>
            <span>Habla directamente con un tecnico</span>
          </div>
          <span class="canal-arrow">›</span>
        </a>

        <div class="sop-canal" id="btnLlamar" style="cursor:pointer">
          <div class="icon-wrap phone">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div class="canal-info">
            <strong>Llamar desde tu telefono</strong>
            <span>+52 55 1234 5678</span>
          </div>
          <span class="canal-arrow">›</span>
        </div>

        <p style="font-size:12px;color:var(--txt-soft);margin-top:6px">Lun – Vie de 9am a 6pm</p>
      </div>
    </div>

  </div>

</div>
@endsection

@push('styles')
<style>
/* Phone call card */
.call-overlay{
  display:none;position:fixed;inset:0;z-index:9000;
  background:rgba(0,0,0,.45);backdrop-filter:blur(4px);
  place-items:center;
}
.call-overlay.active{display:grid}
.call-card{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:16px;
  padding:32px 36px;box-shadow:0 16px 48px rgba(0,0,0,.2);
  text-align:center;min-width:320px;max-width:380px;
  animation:fbSlideUp .3s ease;position:relative;
}
.call-card .call-close{
  position:absolute;top:10px;right:14px;background:none;border:none;
  color:var(--txt-soft);cursor:pointer;font-size:18px;line-height:1;
}
.call-card .call-close:hover{color:var(--txt)}
.call-card .call-icon{
  width:56px;height:56px;border-radius:50%;margin:0 auto 16px;
  background:rgba(74,222,128,.15);display:grid;place-items:center;
}
.call-card .call-icon svg{color:#16a34a}
.call-card h3{font-size:16px;font-weight:700;margin:0 0 6px}
.call-card .call-number{font-size:22px;font-weight:800;color:var(--blue);margin:12px 0 6px;letter-spacing:1px}
.call-card .call-sub{font-size:12px;color:var(--txt-soft);margin:0 0 20px}
.call-card .call-btn{
  display:inline-flex;align-items:center;gap:8px;
  padding:12px 28px;border:none;border-radius:99px;
  background:linear-gradient(135deg,#16a34a,#4ade80);color:#fff;
  font-size:14px;font-weight:700;cursor:pointer;
  transition:opacity .15s,transform .15s;text-decoration:none;
}
.call-card .call-btn:hover{opacity:.9;transform:scale(1.03)}
</style>
@endpush

@push('scripts')
{{-- Phone call card --}}
<div class="call-overlay" id="callOverlay">
  <div class="call-card">
    <button class="call-close" id="callClose">&times;</button>
    <div class="call-icon">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    </div>
    <h3>Llamar desde tu telefono</h3>
    <div class="call-number">+52 55 1234 5678</div>
    <p class="call-sub">Lun - Vie de 8am a 6pm</p>
    <a href="tel:+525512345678" class="call-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
      Llamar ahora
    </a>
  </div>
</div>

<script>
(function(){
  // Accordion toggle
  document.querySelectorAll('.sop-acc-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      var item = btn.closest('.sop-acc-item');
      var isOpen = item.classList.contains('open');
      document.querySelectorAll('.sop-acc-item').forEach(function(i){ i.classList.remove('open'); });
      if(!isOpen) item.classList.add('open');
    });
  });

  // Phone call card
  var btnLlamar = document.getElementById('btnLlamar');
  var callOverlay = document.getElementById('callOverlay');
  var callClose = document.getElementById('callClose');

  if(btnLlamar) btnLlamar.addEventListener('click', function(){
    callOverlay.classList.add('active');
  });
  if(callClose) callClose.addEventListener('click', function(){
    callOverlay.classList.remove('active');
  });
  if(callOverlay) callOverlay.addEventListener('click', function(e){
    if(e.target === callOverlay) callOverlay.classList.remove('active');
  });
})();
</script>
@endpush
