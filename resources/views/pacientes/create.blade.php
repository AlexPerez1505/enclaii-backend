@extends('layouts.app')

@section('title', 'Nuevo Paciente')
@section('active', 'pacientes')
@section('header-title', 'Nuevo Paciente')
@section('header-sub')
  Ingresa los datos correctamente de tu paciente
@endsection

@push('styles')
<style>
/* ============ ESTILOS NUEVO PACIENTE ============ */

/* Link volver */
.back-link{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-size:13px;
  color:var(--blue);
  font-weight:600;
  margin-bottom:12px;
  transition:color 150ms ease;
}
.back-link:hover{color:var(--cyan)}
.back-link svg{width:16px;height:16px}

/* Form container */
.form-card{
  background:linear-gradient(180deg,var(--card),var(--panel-2));
  border:1px solid var(--stroke);
  border-radius:var(--r-lg);
  padding:28px 32px;
  position:relative;
}

.section-title{
  font-family:'Sora',sans-serif;
  font-size:18px;
  font-weight:700;
  margin-bottom:20px;
  display:flex;
  align-items:center;
  justify-content:space-between;
}

/* Grid formularios */
.form-grid{
  display:grid;
  gap:18px;
  margin-bottom:28px;
}
.form-grid.personal{
  grid-template-columns:repeat(4, 1fr);
}
.form-grid.medical{
  grid-template-columns:repeat(2, 1fr);
  max-width:600px;
}

.form-group{
  display:flex;
  flex-direction:column;
  gap:8px;
}
.form-group label{
  font-size:12px;
  font-weight:600;
  color:var(--txt-soft);
  text-transform:uppercase;
  letter-spacing:0.03em;
}
.form-group input,
.form-group select,
.form-group textarea{
  padding:12px 14px;
  border-radius:10px;
  border:1px solid var(--stroke-strong);
  background:var(--panel-2);
  font:inherit;
  font-size:14px;
  color:var(--txt);
  outline:none;
  transition:border-color 150ms ease, box-shadow 150ms ease;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus{
  border-color:var(--cyan);
  box-shadow:0 0 0 3px rgba(56,199,244,.15);
}
.form-group input::placeholder{color:var(--off)}

/* Spans de campos que ocupan más espacio */
.form-group.span-2{grid-column:span 2}
.form-group.span-3{grid-column:span 3}

/* Select estilizado */
.form-group select{
  cursor:pointer;
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat:no-repeat;
  background-position:right 12px center;
  padding-right:36px;
}

/* Botón agregar foto */
.btn-photo{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:10px 18px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:transparent;
  font-size:13px;
  font-weight:600;
  color:var(--txt);
  transition:all 150ms ease;
  cursor:pointer;
}
.btn-photo:hover{
  border-color:var(--cyan);
  background:rgba(56,199,244,.08);
}

/* Recuadro de foto del paciente */
.patient-photo-container{
  position:absolute;
  top:140px;
  right:30px;
  width:120px;
  height:120px;
  border-radius:var(--r-md);
  overflow:hidden;
  border:2px solid var(--stroke-strong);
  background:linear-gradient(180deg,var(--panel-2),var(--panel-1));
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 4px 16px rgba(0,0,0,.25);
  z-index:10;
}
.patient-photo-container img{
  width:100%;
  height:100%;
  object-fit:cover;
}
.patient-photo-placeholder{
  font-size:48px;
  color:var(--txt-soft);
}
.patient-photo-label{
  position:absolute;
  bottom:0;
  left:0;
  right:0;
  background:rgba(0,0,0,.7);
  color:#fff;
  font-size:10px;
  padding:4px 8px;
  text-align:center;
}

/* Textarea diagnóstico */
textarea{
  resize:vertical;
  min-height:100px;
}

/* Holograma */
.hologram-container{
  position:absolute;
  right:40px;
  top:58%;
  transform:translateY(-50%);
  width:280px;
  height:380px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
}
.hologram{
  width:100%;
  height:280px;
  position:relative;
}
.hologram svg{
  width:100%;
  height:100%;
  filter:drop-shadow(0 0 20px rgba(56,199,244,.4));
}
.hologram-body{
  fill:none;
  stroke:var(--cyan);
  stroke-width:1.5;
  stroke-linecap:round;
  stroke-linejoin:round;
  opacity:0.8;
}
.hologram-organs{
  fill:rgba(56,199,244,.15);
  stroke:var(--cyan);
  stroke-width:1;
  stroke-linecap:round;
  stroke-linejoin:round;
}
.hologram-highlight{
  fill:rgba(245,158,45,.25);
  stroke:var(--orange);
  stroke-width:1.5;
  animation:pulse 2s ease-in-out infinite;
}
@keyframes pulse{
  0%,100%{opacity:0.4}
  50%{opacity:0.8}
}
/* Base/platform del holograma */
.hologram::after{
  content:'';
  position:absolute;
  bottom:0;
  left:50%;
  transform:translateX(-50%);
  width:180px;
  height:40px;
  background:radial-gradient(ellipse, rgba(56,199,244,.3), transparent 70%);
  border-radius:50%;
  animation:holo-base 2.6s var(--ease-in-out) infinite;
}
@keyframes holo-base{
  0%,100%{opacity:0.3;transform:translateX(-50%) scale(1)}
  50%{opacity:0.6;transform:translateX(-50%) scale(1.1)}
}

/* Botón guardar */
.form-footer{
  display:flex;
  justify-content:flex-end;
  margin-top:24px;
}
.btn-save{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding:14px 28px;
  border-radius:var(--r-md);
  border:1px solid var(--green);
  background:rgba(61,220,151,.12);
  font-size:15px;
  font-weight:700;
  color:var(--green);
  transition:all 150ms ease;
  cursor:pointer;
}
.btn-save:hover{
  background:rgba(61,220,151,.2);
  transform:translateY(-1px);
}
.btn-save:active{transform:scale(.97)}

/* Select con botón agregar */
.select-with-add{
  display:flex;
  gap:8px;
  align-items:center;
}
.select-with-add select{
  flex:1;
}
.btn-add-procedimiento{
  width:36px;
  height:36px;
  border-radius:var(--r-sm);
  border:1px solid var(--stroke-strong);
  background:var(--panel-2);
  color:var(--cyan);
  display:grid;
  place-items:center;
  cursor:pointer;
  transition:all 150ms ease;
  flex:none;
}
.btn-add-procedimiento:hover{
  background:rgba(56,199,244,.15);
  border-color:var(--cyan);
}
.procedimientos-tags{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
  margin-top:10px;
}
.procedimiento-tag{
  display:flex;
  align-items:center;
  gap:6px;
  padding:6px 12px;
  background:rgba(56,199,244,.1);
  border:1px solid var(--cyan);
  border-radius:20px;
  font-size:12px;
  color:var(--cyan);
}
.procedimiento-tag button{
  width:16px;
  height:16px;
  border-radius:50%;
  border:none;
  background:rgba(255,90,110,.2);
  color:var(--red);
  display:grid;
  place-items:center;
  cursor:pointer;
  font-size:10px;
  line-height:1;
}

/* Responsive */
@media (max-width:1200px){
  .hologram-container{display:none}
  .form-grid.personal{grid-template-columns:repeat(2, 1fr)}
  .form-group.span-2,
  .form-group.span-3{grid-column:span 2}
}
/* ============ MODAL FOTO ============ */
.modal-overlay{
  position:fixed;
  inset:0;
  background:rgba(6,8,28,.85);
  backdrop-filter:blur(8px);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:1000;
  padding:20px;
}
.modal-overlay.active{display:flex}

/* ============ MODAL ÉXITO ============ */
.modal-success-overlay{
  position:fixed;
  inset:0;
  background:rgba(5,14,27,.85);
  backdrop-filter:blur(8px);
  display:none;
  align-items:center;
  justify-content:center;
  z-index:2000;
}
.modal-success-overlay.active{display:flex}
.modal-success{
  background:linear-gradient(180deg,var(--card),var(--panel-2));
  border:1px solid var(--stroke-strong);
  border-radius:var(--r-lg);
  padding:40px 50px;
  text-align:center;
  max-width:400px;
  width:90%;
  box-shadow:0 25px 50px rgba(0,0,0,.4);
}
.modal-success-icon{
  width:70px;
  height:70px;
  background:linear-gradient(135deg,var(--green),#2ecc71);
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  margin:0 auto 24px;
}
.modal-success-icon svg{
  width:35px;
  height:35px;
  color:#fff;
}
.modal-success h2{
  font-size:22px;
  font-weight:600;
  color:var(--txt);
  margin-bottom:12px;
}
.modal-success p{
  font-size:14px;
  color:var(--txt-soft);
  margin-bottom:28px;
}
.btn-aceptar{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding:12px 32px;
  background:linear-gradient(180deg,#3ddc97 0%,var(--green) 100%);
  color:#fff;
  border:none;
  border-radius:var(--r-md);
  font-size:14px;
  font-weight:600;
  cursor:pointer;
  transition:all 150ms ease;
}
.btn-aceptar:hover{
  background:linear-gradient(180deg,#2ecc71 0%,#27ae60 100%);
  transform:translateY(-1px);
}

.modal-photo{
  background:linear-gradient(180deg,var(--card),var(--panel-2));
  border:1px solid var(--stroke-strong);
  border-radius:var(--r-lg);
  width:100%;
  max-width:900px;
  max-height:90vh;
  overflow-y:auto;
  padding:28px 32px;
  animation:modalIn 300ms var(--ease-out);
}
@keyframes modalIn{
  from{opacity:0;transform:scale(.95) translateY(20px)}
  to{opacity:1;transform:scale(1) translateY(0)}
}

.modal-header{
  margin-bottom:24px;
}
.modal-header h2{
  font-family:'Sora',sans-serif;
  font-size:20px;
  font-weight:700;
  margin-bottom:8px;
}
.modal-header p{
  font-size:14px;
  color:var(--txt-soft);
}

.modal-body{
  display:grid;
  grid-template-columns:1.2fr 1fr;
  gap:24px;
}

/* Panel izquierdo - Preview */
.preview-panel{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:20px;
}
.preview-panel h3{
  font-size:14px;
  font-weight:600;
  margin-bottom:14px;
}

.camera-frame{
  aspect-ratio:4/3;
  background:#d1d5db;
  border-radius:var(--r-md);
  position:relative;
  overflow:hidden;
  display:flex;
  align-items:center;
  justify-content:center;
}

/* Esquinas del marco */
.corner{
  position:absolute;
  width:24px;
  height:24px;
  border:3px solid var(--blue);
}
.corner-tl{top:12px;left:12px;border-right:0;border-bottom:0;border-radius:8px 0 0 0}
.corner-tr{top:12px;right:12px;border-left:0;border-bottom:0;border-radius:0 8px 0 0}
.corner-bl{bottom:12px;left:12px;border-right:0;border-top:0;border-radius:0 0 0 8px}
.corner-br{bottom:12px;right:12px;border-left:0;border-top:0;border-radius:0 0 8px 0}

/* Avatar placeholder */
.avatar-preview{
  width:140px;
  height:140px;
  border-radius:50%;
  background:linear-gradient(135deg,#e0e0e0,#f5f5f5);
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:60px;
}

/* Botones de cámara */
.camera-controls{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:32px;
  margin-top:16px;
}
.cam-btn{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:6px;
  background:transparent;
  border:0;
  color:var(--txt-soft);
  font-size:12px;
  font-weight:500;
  cursor:pointer;
  transition:color 150ms ease;
}
.cam-btn:hover{color:var(--txt)}
.cam-btn.active{color:var(--cyan)}
.cam-btn .icon{
  width:44px;
  height:44px;
  border-radius:50%;
  display:grid;
  place-items:center;
  background:var(--panel);
  border:1px solid var(--stroke);
}
.cam-btn.active .icon{
  background:var(--blue);
  border-color:var(--blue);
  color:#fff;
}
.cam-btn svg{width:20px;height:20px}

/* Panel derecho - Opciones */
.options-panel{
  display:flex;
  flex-direction:column;
  gap:18px;
}

.option-section h4{
  font-size:13px;
  font-weight:600;
  margin-bottom:12px;
}

.source-options{
  display:flex;
  flex-direction:column;
  gap:10px;
}
.source-option{
  display:flex;
  align-items:center;
  gap:14px;
  padding:14px 16px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
  cursor:pointer;
  transition:all 150ms ease;
}
.source-option:hover{
  border-color:var(--stroke-strong);
  background:var(--panel);
}
.source-option.active{
  border-color:var(--blue);
  background:rgba(46,123,246,.12);
}
.source-option .icon{
  width:36px;
  height:36px;
  border-radius:10px;
  display:grid;
  place-items:center;
  background:rgba(46,123,246,.15);
  color:var(--cyan);
}
.source-option.active .icon{
  background:var(--blue);
  color:#fff;
}
.source-option svg{width:20px;height:20px}
.source-option .info{
  flex:1;
}
.source-option .info strong{
  display:block;
  font-size:13px;
  font-weight:600;
  margin-bottom:2px;
}
.source-option .info span{
  font-size:12px;
  color:var(--txt-soft);
}

/* Select resolución */
.resolution-select{
  width:100%;
  padding:12px 14px;
  border-radius:10px;
  border:1px solid var(--stroke-strong);
  background:var(--panel-2);
  color:var(--txt);
  font:inherit;
  font-size:13px;
  cursor:pointer;
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat:no-repeat;
  background-position:right 12px center;
  padding-right:36px;
}

/* Recomendaciones */
.recommendations{
  background:rgba(46,123,246,.1);
  border:1px solid rgba(46,123,246,.3);
  border-radius:var(--r-md);
  padding:14px 16px;
}
.rec-header{
  display:flex;
  align-items:center;
  gap:8px;
  margin-bottom:10px;
  color:var(--cyan);
  font-size:13px;
  font-weight:700;
}
.rec-header svg{width:16px;height:16px}
.recommendations ul{
  list-style:none;
  font-size:12px;
  color:var(--txt-soft);
  line-height:1.6;
}
.recommendations li{
  position:relative;
  padding-left:12px;
}
.recommendations li::before{
  content:'•';
  position:absolute;
  left:0;
  color:var(--cyan);
}

/* Footer modal */
.modal-footer{
  display:flex;
  justify-content:flex-end;
  gap:12px;
  margin-top:24px;
  padding-top:20px;
  border-top:1px solid var(--stroke);
}
.btn-cancel{
  padding:12px 28px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:transparent;
  color:var(--txt);
  font-size:14px;
  font-weight:600;
  cursor:pointer;
  transition:all 150ms ease;
}
.btn-cancel:hover{
  background:var(--panel-2);
}
.btn-confirm{
  padding:12px 28px;
  border-radius:var(--r-md);
  border:1px solid var(--blue);
  background:var(--blue);
  color:#fff;
  font-size:14px;
  font-weight:700;
  cursor:pointer;
  transition:all 150ms ease;
}
.btn-confirm:hover{
  background:#1E5AE8;
  transform:translateY(-1px);
}

@media (max-width:768px){
  .form-card{padding:20px}
  .form-grid.personal,
  .form-grid.medical{grid-template-columns:1fr}
  .form-group.span-2,
  .form-group.span-3{grid-column:span 1}
  .modal-body{grid-template-columns:1fr}
  .modal-photo{padding:20px}
}
</style>
@endpush

@section('content')

  {{-- Link volver --}}
  <a href="{{ route('pacientes') }}" class="back-link rise d1">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver a pacientes
  </a>

  {{-- Formulario --}}
  <div class="form-card rise d2">
    
    {{-- Sección Información Personal --}}
    <h2 class="section-title">
      Información personal
      <button class="btn-photo" id="btnAgregarFoto">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Agregar foto
      </button>
    </h2>

    {{-- Recuadro de foto del paciente --}}
    <div class="patient-photo-container" id="patientPhotoContainer">
      <div class="patient-photo-placeholder" id="patientPhotoPlaceholder">👤</div>
      <img id="patientPhoto" style="display:none;">
      <div class="patient-photo-label">Foto del paciente</div>
    </div>

    <div class="form-grid personal" style="padding-right:150px;">
      <div class="form-group span-2">
        <label>Nombre completo</label>
        <input type="text" placeholder="María Fernanda López Ruiz">
      </div>
      <div class="form-group">
        <label>Identificación</label>
        <input type="text" placeholder="0256987450">
      </div>
      <div class="form-group">
        <label>N.S.S</label>
        <input type="text" placeholder="25849563-9">
      </div>

      <div class="form-group">
        <label>Fecha de nacimiento</label>
        <input type="text" placeholder="25/12/1998">
      </div>
      <div class="form-group">
        <label>Edad</label>
        <input type="text" placeholder="28 años" readonly>
      </div>
      <div class="form-group">
        <label>Peso</label>
        <input type="text" placeholder="30 kg">
      </div>
      <div class="form-group">
        <label>Altura</label>
        <input type="text" placeholder="1.75 m">
      </div>

      <div class="form-group">
        <label>Sexo</label>
        <select>
          <option value="femenino">Femenino</option>
          <option value="masculino">Masculino</option>
        </select>
      </div>
      <div class="form-group">
        <label>N.S.S</label>
        <input type="text" placeholder="25849563-9">
      </div>
      <div class="form-group span-2">
        <label>Dirección</label>
        <input type="text" placeholder="CALLE, CP">
      </div>

      <div class="form-group">
        <label>Teléfono</label>
        <input type="tel" placeholder="722 162 0815">
      </div>
      <div class="form-group span-3">
        <label>e-mail</label>
        <input type="email" placeholder="@gmail.com">
      </div>
    </div>

    {{-- Sección Información Médica --}}
    <h2 class="section-title">Información médica</h2>

    <div style="display:flex;gap:40px;align-items:flex-start;">
      <div style="flex:1;max-width:600px;">
        <div class="form-grid medical">
          <div class="form-group procedimiento-group">
            <label>Procedimiento</label>
            <div class="select-with-add">
              <select id="procedimientoSelect" onchange="onProcedimientoChange()">
                <option value="colonoscopia">Colonoscopia</option>
                <option value="panendoscopia">Panendoscopia</option>
                <option value="endoscopia">Endoscopia diagnóstica</option>
                <option value="gastroscopia">Gastroscopia</option>
                <option value="otro">Otro...</option>
              </select>
              <button type="button" class="btn-add-procedimiento" onclick="addProcedimiento()" title="Agregar procedimiento">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
            </div>
            <div id="otroProcedimientoContainer" style="display:none;margin-top:8px;">
              <input type="text" id="otroProcedimientoInput" placeholder="Nombre del procedimiento" style="width:100%;">
            </div>
            <div id="procedimientosAgregados" class="procedimientos-tags"></div>
          </div>
          <div class="form-group">
            <label>Fecha</label>
            <input type="text" placeholder="30/05/2025">
          </div>
          <div class="form-group medico-group">
            <label>Médico</label>
            <div class="select-with-add">
              <select id="medicoSelect" onchange="onMedicoChange()">
                <option value="dr-victor">Dr. Victor</option>
                <option value="dr-ricardo">Dr. Ricardo</option>
                <option value="otro">Otro...</option>
              </select>
              <button type="button" class="btn-add-procedimiento" onclick="addMedico()" title="Agregar médico">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
            </div>
            <div id="otroMedicoContainer" style="display:none;margin-top:8px;">
              <input type="text" id="otroMedicoInput" placeholder="Nombre del médico" style="width:100%;">
            </div>
          </div>
          <div class="form-group referido-group">
            <label>Referido por</label>
            <div class="select-with-add">
              <select id="referidoSelect" onchange="onReferidoChange()">
                <option value="particular">Particular</option>
                <option value="hospital">Hospital General</option>
                <option value="otro">Otro...</option>
              </select>
              <button type="button" class="btn-add-procedimiento" onclick="addReferido()" title="Agregar referencia">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </button>
            </div>
            <div id="otroReferidoContainer" style="display:none;margin-top:8px;">
              <input type="text" id="otroReferidoInput" placeholder="Nombre de referencia" style="width:100%;">
            </div>
          </div>
        </div>

        <div class="form-group" style="margin-top:18px;">
          <label>Diagnóstico Preliminar</label>
          <textarea placeholder="Define lo que podría tener"></textarea>
        </div>
      </div>

      {{-- Holograma --}}
      <div class="hologram-container">
        <div class="hologram">
          {{-- SVG simplified body hologram --}}
          <svg viewBox="0 0 200 280" fill="none" xmlns="http://www.w3.org/2000/svg">
            {{-- Head --}}
            <circle cx="100" cy="25" r="15" class="hologram-body"/>
            {{-- Neck --}}
            <line x1="100" y1="40" x2="100" y2="50" class="hologram-body"/>
            {{-- Torso/shoulders --}}
            <path d="M70 55 Q100 48 130 55 L125 100 L75 100 Z" class="hologram-body"/>
            {{-- Chest/lungs area (highlighted) --}}
            <ellipse cx="85" cy="75" rx="12" ry="15" class="hologram-organs"/>
            <ellipse cx="115" cy="75" rx="12" ry="15" class="hologram-organs"/>
            {{-- Stomach/digestive (highlighted in orange) --}}
            <ellipse cx="100" cy="115" rx="20" ry="25" class="hologram-highlight"/>
            <path d="M85 100 Q100 95 115 100 L110 130 Q100 135 90 130 Z" class="hologram-highlight"/>
            {{-- Arms --}}
            <line x1="70" y1="55" x2="55" y2="140" class="hologram-body"/>
            <line x1="130" y1="55" x2="145" y2="140" class="hologram-body"/>
            {{-- Hands --}}
            <circle cx="55" cy="145" r="6" class="hologram-body"/>
            <circle cx="145" cy="145" r="6" class="hologram-body"/>
            {{-- Hips/pelvis --}}
            <path d="M75 120 Q100 125 125 120 L120 160 L80 160 Z" class="hologram-body"/>
            {{-- Legs --}}
            <line x1="85" y1="160" x2="80" y2="240" class="hologram-body"/>
            <line x1="115" y1="160" x2="120" y2="240" class="hologram-body"/>
            {{-- Feet --}}
            <ellipse cx="80" cy="245" rx="10" ry="5" class="hologram-body"/>
            <ellipse cx="120" cy="245" rx="10" ry="5" class="hologram-body"/>
            {{-- Vertical spine line --}}
            <line x1="100" y1="50" x2="100" y2="160" class="hologram-body" stroke-dasharray="3 2"/>
          </svg>
        </div>
        <button class="btn-save" id="btnGuardarPaciente" style="margin-top:180px;">
          Guardar paciente
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
      </div>
    </div>

    {{-- Footer móvil (cuando el holograma no se muestra) --}}
    <div class="form-footer" style="display:none;">
      <button class="btn-save" id="btnGuardarPacienteMobile">
        Guardar paciente
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>
    </div>

  </div>

  {{-- Modal de éxito al guardar paciente --}}
  <div class="modal-success-overlay" id="modalSuccess">
    <div class="modal-success">
      <div class="modal-success-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
      </div>
      <h2>¡Paciente Guardado!</h2>
      <p>El paciente ha sido registrado exitosamente en el sistema.</p>
      <button class="btn-aceptar" id="btnAceptar" onclick="window.location.href='{{ route('pacientes') }}'">
        Aceptar
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>
    </div>
  </div>

  {{-- Modal de captura de foto --}}
  <div class="modal-overlay" id="modalFoto">
    <div class="modal-photo">
      <div class="modal-header">
        <h2>Inserta foto del paciente</h2>
        <p>Capture una fotografía o imagen en vivo del paciente para su expediente</p>
      </div>

      <div class="modal-body">
        {{-- Panel izquierdo: Preview --}}
        <div class="preview-panel">
          <h3>Vista previa</h3>
          <div class="camera-frame">
            <span class="corner corner-tl"></span>
            <span class="corner corner-tr"></span>
            <span class="corner corner-bl"></span>
            <span class="corner corner-br"></span>
            <div class="avatar-preview">👤</div>
          </div>
          <div class="camera-controls">
            <button class="cam-btn">
              <span class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              </span>
              Galería
            </button>
            <button class="cam-btn active">
              <span class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              </span>
              Tomar foto
            </button>
            <button class="cam-btn">
              <span class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 12"/><path d="M3 3v9h9"/></svg>
              </span>
              Rotar cámara
            </button>
          </div>
        </div>

        {{-- Panel derecho: Opciones --}}
        <div class="options-panel">
          <div class="option-section">
            <h4>Seleccionar fuente</h4>
            <div class="source-options">
              <div class="source-option active">
                <div class="icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
                <div class="info">
                  <strong>Cámara en vivo</strong>
                  <span>usar camara del dispositivo</span>
                </div>
              </div>
              <div class="source-option">
                <div class="icon" style="background:rgba(245,158,45,.15);color:var(--orange)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15h6"/></svg>
                </div>
                <div class="info">
                  <strong>Subir imagen</strong>
                  <span>Seleccionar desde archivos</span>
                </div>
              </div>
            </div>
          </div>

          <div class="option-section">
            <h4>Seleccionar resolución</h4>
            <select class="resolution-select">
              <option>Alta (1920 x 1080)</option>
              <option>Media (1280 x 720)</option>
              <option>Baja (640 x 480)</option>
            </select>
          </div>

          <div class="recommendations">
            <div class="rec-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
              Recomendaciones
            </div>
            <ul>
              <li>Asegúrese de que el rostro esté centrado.</li>
              <li>Buena iluminación y fondo claro.</li>
              <li>Evite sombras o reflejos en el rostro.</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn-cancel" id="btnCancelarFoto">Cancelar</button>
        <button class="btn-confirm" id="btnUsarFoto">Usar esta foto</button>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
(function(){
  const btnAgregarFoto = document.getElementById('btnAgregarFoto');
  const modalFoto = document.getElementById('modalFoto');
  const btnCancelarFoto = document.getElementById('btnCancelarFoto');

  // Input file oculto para subir imagen
  const inputFileFoto = document.createElement('input');
  inputFileFoto.type = 'file';
  inputFileFoto.accept = 'image/*';
  inputFileFoto.style.display = 'none';
  document.body.appendChild(inputFileFoto);

  // Referencias al recuadro de foto
  const patientPhotoContainer = document.getElementById('patientPhotoContainer');
  const patientPhoto = document.getElementById('patientPhoto');
  const patientPhotoPlaceholder = document.getElementById('patientPhotoPlaceholder');
  const btnUsarFoto = document.getElementById('btnUsarFoto');

  // Variable para almacenar la foto actual (base64)
  let currentPhotoData = null;

  btnAgregarFoto.addEventListener('click', () => {
    modalFoto.classList.add('active');
    // Resetear vista previa
    avatarPreview.textContent = '👤';
    avatarPreview.style.backgroundImage = '';
    currentPhotoData = null;
  });

  btnCancelarFoto.addEventListener('click', () => {
    modalFoto.classList.remove('active');
  });

  modalFoto.addEventListener('click', (e) => {
    if (e.target === modalFoto) {
      modalFoto.classList.remove('active');
    }
  });

  // Toggle opciones de fuente
  const sourceOptions = document.querySelectorAll('.source-option');
  sourceOptions.forEach(opt => {
    opt.addEventListener('click', () => {
      sourceOptions.forEach(o => o.classList.remove('active'));
      opt.classList.add('active');
    });
  });

  // Toggle botones de cámara
  const camBtns = document.querySelectorAll('.cam-btn');
  camBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      camBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  // Manejar selección de fuente (Cámara vs Galería)
  const avatarPreview = document.querySelector('.avatar-preview');
  
  sourceOptions.forEach((opt, index) => {
    opt.addEventListener('click', () => {
      if (index === 1) { // Galería
        inputFileFoto.click();
      }
    });
  });

  // Manejar selección de archivo
  inputFileFoto.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        currentPhotoData = event.target.result;
        avatarPreview.style.backgroundImage = `url(${currentPhotoData})`;
        avatarPreview.style.backgroundSize = 'cover';
        avatarPreview.style.backgroundPosition = 'center';
        avatarPreview.textContent = '';
      };
      reader.readAsDataURL(file);
    }
  });

  // Botón "Usar esta foto"
  btnUsarFoto.addEventListener('click', () => {
    if (currentPhotoData) {
      // Mostrar foto en el recuadro superior derecho
      patientPhoto.src = currentPhotoData;
      patientPhoto.style.display = 'block';
      patientPhotoPlaceholder.style.display = 'none';
      
      // Cerrar modal
      modalFoto.classList.remove('active');
      
      // Opcional: Guardar en localStorage
      localStorage.setItem('patientPhoto', currentPhotoData);
    } else {
      alert('Por favor capture o seleccione una foto primero');
    }
  });

  // Botón "Guardar paciente"
  const btnGuardarPaciente = document.getElementById('btnGuardarPaciente');
  const btnGuardarPacienteMobile = document.getElementById('btnGuardarPacienteMobile');
  const modalSuccess = document.getElementById('modalSuccess');
  
  function mostrarModalExito() {
    modalSuccess.classList.add('active');
  }
  
  if (btnGuardarPaciente) {
    btnGuardarPaciente.addEventListener('click', (e) => {
      e.preventDefault();
      mostrarModalExito();
    });
  }
  
  if (btnGuardarPacienteMobile) {
    btnGuardarPacienteMobile.addEventListener('click', (e) => {
      e.preventDefault();
      mostrarModalExito();
    });
  }

  // Cargar foto guardada al iniciar (si existe)
  const savedPhoto = localStorage.getItem('patientPhoto');
  if (savedPhoto) {
    patientPhoto.src = savedPhoto;
    patientPhoto.style.display = 'block';
    patientPhotoPlaceholder.style.display = 'none';
  }

  // Cargar procedimientos personalizados desde localStorage
  function cargarProcedimientosPersonalizados() {
    const guardados = localStorage.getItem('procedimientosPersonalizados');
    if (guardados) {
      const lista = JSON.parse(guardados);
      const select = document.getElementById('procedimientoSelect');
      // Agregar opciones personalizadas antes de "Otro"
      lista.forEach(proc => {
        // Verificar si ya existe
        let existe = false;
        for (let i = 0; i < select.options.length; i++) {
          if (select.options[i].value === proc.valor) {
            existe = true;
            break;
          }
        }
        if (!existe) {
          const option = document.createElement('option');
          option.value = proc.valor;
          option.textContent = proc.texto;
          // Insertar antes de la última opción (Otro)
          select.insertBefore(option, select.options[select.options.length - 1]);
        }
      });
    }
  }

  // Cargar al iniciar
  cargarProcedimientosPersonalizados();

  // Arreglo para guardar procedimientos seleccionados
  let procedimientosGuardados = [];

  // Función para mostrar/ocultar input de "Otro"
  window.onProcedimientoChange = function() {
    const select = document.getElementById('procedimientoSelect');
    const otroContainer = document.getElementById('otroProcedimientoContainer');
    
    if (select.value === 'otro') {
      otroContainer.style.display = 'block';
      document.getElementById('otroProcedimientoInput').focus();
    } else {
      otroContainer.style.display = 'none';
    }
  };

  // Función para agregar procedimiento
  window.addProcedimiento = function() {
    const select = document.getElementById('procedimientoSelect');
    const container = document.getElementById('procedimientosAgregados');
    const otroInput = document.getElementById('otroProcedimientoInput');
    
    let valor = select.value;
    let texto = select.options[select.selectedIndex].text;

    // Si es "Otro", tomar el valor del input
    if (valor === 'otro') {
      texto = otroInput.value.trim();
      if (!texto) {
        alert('Por favor ingrese el nombre del procedimiento');
        return;
      }
      // Crear un valor slug para el procedimiento personalizado
      valor = 'custom_' + texto.toLowerCase().replace(/\s+/g, '_');
      
      // Guardar en localStorage para futuras sesiones
      guardarProcedimientoPersonalizado(valor, texto);
      
      // Agregar al select para futura selección
      const nuevaOption = document.createElement('option');
      nuevaOption.value = valor;
      nuevaOption.textContent = texto;
      select.insertBefore(nuevaOption, select.options[select.options.length - 1]);
      
      // Limpiar input
      otroInput.value = '';
      otroContainer.style.display = 'none';
      select.value = valor;
    }

    // Verificar si ya existe en los agregados
    if (procedimientosGuardados.includes(valor)) {
      alert('Este procedimiento ya fue agregado');
      return;
    }

    // Agregar al arreglo
    procedimientosGuardados.push(valor);

    // Crear tag visual
    const tag = document.createElement('div');
    tag.className = 'procedimiento-tag';
    tag.dataset.value = valor;
    tag.innerHTML = `
      <span>${texto}</span>
      <button type="button" onclick="removeProcedimiento('${valor}')">×</button>
    `;

    container.appendChild(tag);
  };

  // Función para guardar procedimiento personalizado en localStorage
  function guardarProcedimientoPersonalizado(valor, texto) {
    let guardados = localStorage.getItem('procedimientosPersonalizados');
    if (!guardados) {
      guardados = [];
    } else {
      guardados = JSON.parse(guardados);
    }
    
    // Verificar si ya existe
    const existe = guardados.some(p => p.valor === valor);
    if (!existe) {
      guardados.push({ valor, texto });
      localStorage.setItem('procedimientosPersonalizados', JSON.stringify(guardados));
    }
  }

  // Función para eliminar procedimiento
  window.removeProcedimiento = function(valor) {
    // Remover del arreglo
    procedimientosGuardados = procedimientosGuardados.filter(p => p !== valor);

    // Remover tag visual
    const tag = document.querySelector(`.procedimiento-tag[data-value="${valor}"]`);
    if (tag) {
      tag.remove();
    }
  };

  // Función para obtener procedimientos guardados (para enviar al backend)
  window.getProcedimientos = function() {
    return procedimientosGuardados;
  };

  // ============ FUNCIONES PARA MÉDICO ============
  
  // Cargar médicos personalizados desde localStorage
  function cargarMedicosPersonalizados() {
    const guardados = localStorage.getItem('medicosPersonalizados');
    if (guardados) {
      const lista = JSON.parse(guardados);
      const select = document.getElementById('medicoSelect');
      lista.forEach(med => {
        let existe = false;
        for (let i = 0; i < select.options.length; i++) {
          if (select.options[i].value === med.valor) {
            existe = true;
            break;
          }
        }
        if (!existe) {
          const option = document.createElement('option');
          option.value = med.valor;
          option.textContent = med.texto;
          select.insertBefore(option, select.options[select.options.length - 1]);
        }
      });
    }
  }

  // Cargar al iniciar
  cargarMedicosPersonalizados();

  // Función para mostrar/ocultar input de "Otro" médico
  window.onMedicoChange = function() {
    const select = document.getElementById('medicoSelect');
    const otroContainer = document.getElementById('otroMedicoContainer');
    
    if (select.value === 'otro') {
      otroContainer.style.display = 'block';
      document.getElementById('otroMedicoInput').focus();
    } else {
      otroContainer.style.display = 'none';
    }
  };

  // Función para agregar médico
  window.addMedico = function() {
    const select = document.getElementById('medicoSelect');
    const otroInput = document.getElementById('otroMedicoInput');
    const otroContainer = document.getElementById('otroMedicoContainer');
    
    let valor = select.value;
    let texto = select.options[select.selectedIndex].text;

    // Si es "Otro", tomar el valor del input
    if (valor === 'otro') {
      texto = otroInput.value.trim();
      if (!texto) {
        alert('Por favor ingrese el nombre del médico');
        return;
      }
      // Crear un valor slug
      valor = 'medico_' + texto.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
      
      // Guardar en localStorage
      guardarMedicoPersonalizado(valor, texto);
      
      // Agregar al select
      const nuevaOption = document.createElement('option');
      nuevaOption.value = valor;
      nuevaOption.textContent = texto;
      select.insertBefore(nuevaOption, select.options[select.options.length - 1]);
      
      // Limpiar input
      otroInput.value = '';
      otroContainer.style.display = 'none';
      select.value = valor;
      
      alert('Médico "' + texto + '" agregado exitosamente');
    } else {
      alert('Médico seleccionado: ' + texto);
    }
  };

  // Función para guardar médico personalizado en localStorage
  function guardarMedicoPersonalizado(valor, texto) {
    let guardados = localStorage.getItem('medicosPersonalizados');
    if (!guardados) {
      guardados = [];
    } else {
      guardados = JSON.parse(guardados);
    }
    
    const existe = guardados.some(m => m.valor === valor);
    if (!existe) {
      guardados.push({ valor, texto });
      localStorage.setItem('medicosPersonalizados', JSON.stringify(guardados));
    }
  }

  // ============ FUNCIONES PARA REFERIDO POR ============
  
  // Cargar referidos personalizados desde localStorage
  function cargarReferidosPersonalizados() {
    const guardados = localStorage.getItem('referidosPersonalizados');
    if (guardados) {
      const lista = JSON.parse(guardados);
      const select = document.getElementById('referidoSelect');
      lista.forEach(ref => {
        let existe = false;
        for (let i = 0; i < select.options.length; i++) {
          if (select.options[i].value === ref.valor) {
            existe = true;
            break;
          }
        }
        if (!existe) {
          const option = document.createElement('option');
          option.value = ref.valor;
          option.textContent = ref.texto;
          select.insertBefore(option, select.options[select.options.length - 1]);
        }
      });
    }
  }

  // Cargar al iniciar
  cargarReferidosPersonalizados();

  // Función para mostrar/ocultar input de "Otro" referido
  window.onReferidoChange = function() {
    const select = document.getElementById('referidoSelect');
    const otroContainer = document.getElementById('otroReferidoContainer');
    
    if (select.value === 'otro') {
      otroContainer.style.display = 'block';
      document.getElementById('otroReferidoInput').focus();
    } else {
      otroContainer.style.display = 'none';
    }
  };

  // Función para agregar referido
  window.addReferido = function() {
    const select = document.getElementById('referidoSelect');
    const otroInput = document.getElementById('otroReferidoInput');
    const otroContainer = document.getElementById('otroReferidoContainer');
    
    let valor = select.value;
    let texto = select.options[select.selectedIndex].text;

    // Si es "Otro", tomar el valor del input
    if (valor === 'otro') {
      texto = otroInput.value.trim();
      if (!texto) {
        alert('Por favor ingrese el nombre de la referencia');
        return;
      }
      // Crear un valor slug
      valor = 'ref_' + texto.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
      
      // Guardar en localStorage
      guardarReferidoPersonalizado(valor, texto);
      
      // Agregar al select
      const nuevaOption = document.createElement('option');
      nuevaOption.value = valor;
      nuevaOption.textContent = texto;
      select.insertBefore(nuevaOption, select.options[select.options.length - 1]);
      
      // Limpiar input
      otroInput.value = '';
      otroContainer.style.display = 'none';
      select.value = valor;
      
      alert('Referencia "' + texto + '" agregada exitosamente');
    } else {
      alert('Referencia seleccionada: ' + texto);
    }
  };

  // Función para guardar referido personalizado en localStorage
  function guardarReferidoPersonalizado(valor, texto) {
    let guardados = localStorage.getItem('referidosPersonalizados');
    if (!guardados) {
      guardados = [];
    } else {
      guardados = JSON.parse(guardados);
    }
    
    const existe = guardados.some(r => r.valor === valor);
    if (!existe) {
      guardados.push({ valor, texto });
      localStorage.setItem('referidosPersonalizados', JSON.stringify(guardados));
    }
  }
})();
</script>
@endpush
