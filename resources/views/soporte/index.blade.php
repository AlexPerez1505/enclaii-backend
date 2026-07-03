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

/* Card base */
.sop-card{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:24px}
.sop-card h2{font-size:16px;font-weight:700;margin-bottom:4px}
.sop-card .sub{font-size:13px;color:var(--txt-soft);margin-bottom:16px}

/* 1. Nueva solicitud */
.sop-nueva{display:grid;grid-template-columns:1fr 1fr;gap:32px}
.sop-nueva .sop-form{display:flex;flex-direction:column;gap:14px}
.sop-nueva .sop-form label{font-size:13px;font-weight:600;color:var(--txt-soft)}
.sop-nueva .sop-form select,
.sop-nueva .sop-form input,
.sop-nueva .sop-form textarea{
  width:100%;padding:10px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--panel);color:var(--txt);
  font-size:14px;resize:none;
}
.sop-nueva .sop-form textarea{min-height:80px}
.sop-nueva .sop-form .btn-enviar{
  align-self:flex-start;padding:10px 24px;border-radius:var(--r-md);
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  font-size:14px;font-weight:600;border:0;cursor:pointer;
  transition:opacity .15s;
}
.sop-nueva .sop-form .btn-enviar:hover{opacity:.85}

/* Canales */
.sop-canales h3{font-size:14px;font-weight:700;margin-bottom:4px}
.sop-canales .sub-ch{font-size:12px;color:var(--txt-soft);margin-bottom:16px}
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
.sop-canal .icon-wrap.chat{background:rgba(56,199,244,.15)}
.sop-canal .icon-wrap.wa{background:rgba(37,211,102,.15)}
.sop-canal .icon-wrap.phone{background:rgba(168,130,255,.15)}
.sop-canal .canal-info{flex:1}
.sop-canal .canal-info strong{font-size:13px;display:block}
.sop-canal .canal-info span{font-size:12px;color:var(--txt-soft)}
.sop-canal .badge-en-linea{
  font-size:11px;padding:3px 8px;border-radius:99px;
  background:rgba(37,211,102,.15);color:#25d366;font-weight:600;
}
.sop-canal .canal-arrow{color:var(--txt-soft);font-size:14px}

/* 3. Busca ayuda */
.sop-buscar{display:flex;flex-direction:column;gap:14px}
.sop-buscar-bar{display:flex;gap:8px}
.sop-buscar-bar input{
  flex:1;padding:10px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--panel);color:var(--txt);font-size:14px;
}
.sop-buscar-bar button{
  width:40px;height:40px;border-radius:var(--r-md);border:0;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  display:grid;place-items:center;cursor:pointer;
}
.sop-tags{display:flex;flex-wrap:wrap;gap:8px}
.sop-tags .tag{
  padding:6px 14px;border-radius:99px;font-size:12px;
  border:1px solid var(--stroke);color:var(--txt-soft);cursor:pointer;
  transition:background .15s,color .15s;
}
.sop-tags .tag:hover{background:rgba(110,160,255,.1);color:var(--txt)}

/* Problemas comunes */
.sop-problemas h2{font-size:16px;font-weight:700;margin-bottom:4px}
.sop-problemas .sub{font-size:13px;color:var(--txt-soft);margin-bottom:16px}
.sop-problemas .header-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
.sop-problemas .ver-todos{font-size:13px;color:var(--blue);cursor:pointer;text-decoration:none}
.sop-problemas .ver-todos:hover{text-decoration:underline}
.sop-problemas-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.sop-problema{
  padding:20px 16px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--panel);
  display:flex;flex-direction:column;align-items:center;text-align:center;gap:10px;
  transition:background .15s;cursor:pointer;
}
.sop-problema:hover{background:rgba(110,160,255,.06)}
.sop-problema .prob-icon{
  width:44px;height:44px;border-radius:50%;
  display:grid;place-items:center;
  background:rgba(110,160,255,.1);
}
.sop-problema strong{font-size:13px}
.sop-problema p{font-size:12px;color:var(--txt-soft);margin:0;line-height:1.4}
.sop-problema .ver-sol{font-size:12px;color:var(--blue);margin-top:6px}

/* ── Sidebar derecho ── */
.sop-side{display:flex;flex-direction:column;gap:20px}

/* Estado solicitudes */
.sop-estado{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:20px}
.sop-estado h2{font-size:15px;font-weight:700;margin-bottom:4px}
.sop-estado .sub{font-size:12px;color:var(--txt-soft);margin-bottom:16px}
.sop-ticket{display:flex;align-items:flex-start;gap:12px;margin-bottom:16px}
.sop-ticket:last-of-type{margin-bottom:10px}
.sop-ticket .tick-dot{
  width:28px;height:28px;border-radius:50%;flex-shrink:0;
  display:grid;place-items:center;font-size:14px;
}
.sop-ticket .tick-dot.revision{background:rgba(251,146,60,.15);color:#fb923c}
.sop-ticket .tick-dot.proceso{background:rgba(96,165,250,.15);color:#60a5fa}
.sop-ticket .tick-dot.solucionado{background:rgba(74,222,128,.15);color:#4ade80}
.sop-ticket .tick-info{flex:1}
.sop-ticket .tick-info strong{font-size:13px;display:block}
.sop-ticket .tick-info span{font-size:11px;color:var(--txt-soft);display:block;margin-top:2px}
.sop-ticket .tick-badge{
  font-size:11px;padding:3px 10px;border-radius:99px;font-weight:600;white-space:nowrap;
}
.sop-ticket .tick-badge.revision{background:rgba(251,146,60,.15);color:#fb923c}
.sop-ticket .tick-badge.proceso{background:rgba(96,165,250,.15);color:#60a5fa}
.sop-ticket .tick-badge.solucionado{background:rgba(74,222,128,.15);color:#4ade80}
.sop-estado .ver-todas{font-size:13px;color:var(--blue);cursor:pointer;text-decoration:none}
.sop-estado .ver-todas:hover{text-decoration:underline}

/* Ayuda ahora */
.sop-ayuda-ahora{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:20px;
  display:flex;flex-direction:column;align-items:center;text-align:center;gap:10px;
}
.sop-ayuda-ahora .orb-big{
  width:52px;height:52px;border-radius:50%;
  display:grid;place-items:center;
  background:radial-gradient(circle at 30% 30%, rgba(56,199,244,.5), rgba(46,123,246,.15));
  box-shadow:0 0 24px rgba(56,199,244,.4);
}
.sop-ayuda-ahora h3{font-size:15px;font-weight:700;margin:0}
.sop-ayuda-ahora p{font-size:12px;color:var(--txt-soft);margin:0}
.sop-ayuda-ahora .btn-chat-live{
  width:100%;padding:12px;border-radius:var(--r-md);border:0;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  font-size:14px;font-weight:600;cursor:pointer;transition:opacity .15s;
  display:flex;align-items:center;justify-content:center;gap:8px;
}
.sop-ayuda-ahora .btn-chat-live:hover{opacity:.85}
.sop-ayuda-ahora .btn-wa{
  width:100%;padding:10px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;color:var(--txt);
  font-size:13px;font-weight:600;cursor:pointer;
  display:flex;align-items:center;justify-content:center;gap:8px;
  transition:background .15s;
}
.sop-ayuda-ahora .btn-wa:hover{background:rgba(110,160,255,.06)}
.sop-ayuda-ahora .btn-call{
  width:100%;padding:10px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;color:var(--txt);
  font-size:13px;font-weight:600;cursor:pointer;
  display:flex;align-items:center;justify-content:center;gap:8px;
  transition:background .15s;
}
.sop-ayuda-ahora .btn-call:hover{background:rgba(110,160,255,.06)}

/* Feedback */
.sop-feedback{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:20px;
  text-align:center;
}
.sop-feedback h3{font-size:14px;font-weight:700;margin-bottom:4px}
.sop-feedback p{font-size:12px;color:var(--txt-soft);margin-bottom:12px}
.sop-feedback .fb-btns{display:flex;justify-content:center;gap:16px}
.sop-feedback .fb-btn{
  width:44px;height:44px;border-radius:50%;
  border:1px solid var(--stroke);background:var(--panel);
  display:grid;place-items:center;cursor:pointer;transition:background .15s;
}
.sop-feedback .fb-btn:hover{background:rgba(110,160,255,.1)}

/* Tickets */
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

/* Responsive */
@media (max-width:1100px){
  .sop-grid{grid-template-columns:1fr}
  .sop-side{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px}
}
@media (max-width:768px){
  .sop-nueva{grid-template-columns:1fr}
  .sop-problemas-grid{grid-template-columns:repeat(2,1fr)}
}
@media (max-width:480px){
  .sop-problemas-grid{grid-template-columns:1fr}
}
</style>
@endpush

@section('content')
<div class="sop-grid">

  {{-- ============ COLUMNA PRINCIPAL ============ --}}
  <div class="sop-main">

    {{-- Nueva Solicitud o Reporte --}}
    <div class="sop-card">
      <h2> Nueva Solicitud o Reporte</h2>
      <p class="sub">Cuéntanos tu problema en un solo paso y te ayudaremos.</p>

      <div class="sop-nueva">
        <div class="sop-form">
          <div>
            <label>Categoría</label>
            <select id="sopCategoria">
              <option value="">Selecciona una categoría</option>
              <option value="tecnico">Problema técnico</option>
              <option value="cuenta">Mi cuenta</option>
              <option value="facturacion">Facturación</option>
              <option value="datos">Exportar datos</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div>
            <label>Asunto</label>
            <input type="text" id="sopAsunto" placeholder="Resume tu problema en pocas palabras">
          </div>
          <div>
            <label>Descripción</label>
            <textarea id="sopDescripcion" placeholder="Cuéntanos más detalles sobre lo que está ocurriendo..."></textarea>
          </div>
          <button class="btn-enviar" type="button" id="btnEnviarSolicitud">Enviar solicitud</button>
        </div>

        <div class="sop-canales">
          <h3>¿Es urgente? Contáctanos por otros canales</h3>
          <p class="sub-ch">Elige el medio que prefieras para obtener ayuda más rápido.</p>

          <a class="sop-canal" href="{{ route('soporte.chat') }}" style="text-decoration:none;color:inherit">
            <div class="icon-wrap chat">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div class="canal-info">
              <strong>Chat con IA</strong>
              <span>Habla con nuestro asistente de IA</span>
            </div>
            <span class="badge-en-linea">En línea</span>
            <span class="canal-arrow">›</span>
          </a>

          <div class="sop-canal">
            <div class="icon-wrap wa">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </div>
            <div class="canal-info">
              <strong>WhatsApp</strong>
              <span>Escríbenos por WhatsApp</span>
            </div>
            <span class="canal-arrow">›</span>
          </div>

          <div class="sop-canal">
            <div class="icon-wrap phone">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div class="canal-info">
              <strong>Llámanos</strong>
              <span>+52 55 1234 5678</span>
            </div>
            <span class="canal-arrow">›</span>
          </div>

          <p style="font-size:12px;color:var(--txt-soft);margin-top:6px">Lun – Vie de 8am a 6pm</p>
        </div>
      </div>
    </div>


    {{-- Problemas comunes --}}
    <div class="sop-card sop-problemas">
      <div class="header-row">
        <div>
          <h2>Problemas comunes que puedes resolver</h2>
          <p class="sub">Soluciones rápidas para los errores más frecuentes.</p>
        </div>
        <a class="ver-todos" href="#">Ver todos los tutoriales →</a>
      </div>
      <div class="sop-problemas-grid">
        <div class="sop-problema">
          <div class="prob-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          </div>
          <strong>Error al subir archivos</strong>
          <p>Aprende a solucionar problemas al cargar estudios.</p>
          <span class="ver-sol">Ver solución →</span>
        </div>
        <div class="sop-problema">
          <div class="prob-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </div>
          <strong>No puedo iniciar sesión</strong>
          <p>Pasos para recuperar tu acceso a la plataforma.</p>
          <span class="ver-sol">Ver solución →</span>
        </div>
        <div class="sop-problema">
          <div class="prob-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          </div>
          <strong>Error al exportar datos</strong>
          <p>Soluciona errores al descargar o exportar información.</p>
          <span class="ver-sol">Ver solución →</span>
        </div>
        <div class="sop-problema">
          <div class="prob-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 1l22 22"/><path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"/><path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"/><path d="M10.71 5.05A16 16 0 0 1 22.56 9"/><path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
          </div>
          <strong>Problemas de conexión</strong>
          <p>Cómo resolver interrupciones o lentitud en la plataforma.</p>
          <span class="ver-sol">Ver solución →</span>
        </div>
      </div>
    </div>

  </div>

  {{-- ============ SIDEBAR DERECHO ============ --}}
  <div class="sop-side">

    {{-- Estado de tus solicitudes --}}
    <div class="sop-estado">
      <h2>Estado de tus solicitudes</h2>
      <p class="sub">Así vamos con tus reportes más recientes.</p>

      <div class="sop-ticket">
        <div class="tick-dot revision">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
        <div class="tick-info">
          <strong>Error en la carga de archivos</strong>
          <span>Creado: 24 May 2024 · ID: #1248</span>
          <span>Última actualización: Soporte técnico está revisando el servidor.</span>
        </div>
        <span class="tick-badge revision">En revisión</span>
      </div>

      <div class="sop-ticket">
        <div class="tick-dot proceso">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="tick-info">
          <strong>No puedo iniciar sesión</strong>
          <span>Creado: 22 May 2024 · ID: #1245</span>
          <span>Última actualización: Estamos trabajando en una solución para ti.</span>
        </div>
        <span class="tick-badge proceso">En proceso</span>
      </div>

      <div class="sop-ticket">
        <div class="tick-dot solucionado">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="tick-info">
          <strong>Error al exportar datos</strong>
          <span>Creado: 18 May 2024 · ID: #1239</span>
          <span>Última actualización: El problema ha sido resuelto exitosamente.</span>
        </div>
        <span class="tick-badge solucionado">Solucionado</span>
      </div>

      <a class="ver-todas" href="#">Ver todas mis solicitudes →</a>
    </div>


    {{-- Feedback --}}
    <div class="sop-feedback">
      <h3>¿Te fue útil esta página?</h3>
      <p>Tu opinión nos ayuda a mejorar.</p>
      <div class="fb-btns">
        <button class="fb-btn" type="button" title="Sí">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
        </button>
        <button class="fb-btn" type="button" title="No">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z"/><path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/></svg>
        </button>
      </div>
    </div>

    {{-- Tickets --}}
    <div class="sop-tickets-btn">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H2V6h2a2 2 0 0 0 2-2V2"/><path d="M22 12h-2a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2V6h-2a2 2 0 0 1-2-2V2"/><path d="M7 2h10"/><path d="M7 22h10"/><rect x="7" y="6" width="10" height="12" rx="1"/></svg>
      <div>
        <h3>Mis Tickets</h3>
        <p>Consulta el historial y estado de todos tus tickets de soporte.</p>
      </div>
      <a href="{{ route('soporte.tickets') }}" class="btn-tickets">Ver tickets →</a>
    </div>

  </div>

</div>
@endsection
