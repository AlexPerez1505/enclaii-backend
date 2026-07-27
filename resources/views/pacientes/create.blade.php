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
  grid-template-columns:repeat(4, minmax(0, 1fr));
}
.form-grid.medical{
  grid-template-columns:repeat(2, 1fr);
  max-width:600px;
}

.form-group{
  display:flex;
  flex-direction:column;
  gap:8px;
  min-width:0;
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
  width:100%;
  min-width:0;
  box-sizing:border-box;
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

.phone-composite{
  display:grid;
  grid-template-columns:76px minmax(0, 1fr);
  width:100%;
}
.phone-composite input{height:100%}
.phone-composite .phone-lada{
  border-top-right-radius:0;
  border-bottom-right-radius:0;
  text-align:center;
  font-weight:700;
  color:var(--cyan);
  padding-left:10px;
  padding-right:10px;
}
.phone-composite .phone-number{
  border-top-left-radius:0;
  border-bottom-left-radius:0;
  border-left:0;
}
.phone-composite input:focus{box-shadow:none}
.phone-composite:focus-within{
  border-radius:10px;
  box-shadow:0 0 0 3px rgba(56,199,244,.15);
}
.phone-composite:focus-within input{border-color:var(--cyan)}

/* Forzar color oscuro en los inputs en modo oscuro */
html[data-theme="dark"] .form-group input,
html[data-theme="dark"] .form-group select,
html[data-theme="dark"] .form-group textarea{
  background:var(--panel-2);
  color:var(--txt);
}

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
  width:180px;
  height:180px;
  border-radius:var(--r-md);
  overflow:hidden;
  border:2px solid var(--stroke-strong);
  background:linear-gradient(180deg,var(--panel-2),var(--panel-1));
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 4px 16px rgba(0,0,0,.25);
  flex:none;
}
.personal-layout{
  display:flex;
  gap:28px;
  align-items:flex-start;
}
.personal-photo-col{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:10px;
  flex:none;
}
.personal-photo-col span{
  font-size:12px;
  color:var(--txt-soft);
  text-align:center;
}
.patient-photo-container img{
  width:100%;
  height:100%;
  object-fit:cover;
}
.patient-photo-placeholder{
  font-size:60px;
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
  right:60px;
  top:68%;
  transform:translateY(-50%);
  width:200px;
  height:260px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  background:transparent;
  mix-blend-mode:lighten;
}
.btn-save-fixed{
  position:absolute;
  bottom:32px;
  right:32px;
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
  width:15px;
  height:15px;
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

/* Mini modal agregar opción */
.mini-modal-overlay{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.55);
  z-index:9999;
  align-items:center;
  justify-content:center;
}
.mini-modal-overlay.active{display:flex;}
.mini-modal{
  background:var(--panel);
  border:1px solid var(--stroke);
  border-radius:var(--r-lg);
  padding:28px 24px 20px;
  width:340px;
  box-shadow:0 20px 60px rgba(0,0,0,.5);
}
.mini-modal h4{ 
  margin:0 0 4px;
  font-size:15px;
  font-weight:700;
  color:var(--txt);
}
.mini-modal p{
  margin:0 0 16px;
  font-size:12px;
  color:var(--txt-soft);
}
.mini-modal input{
  width:100%;
  margin-bottom:16px;
}
.mini-modal-footer{
  display:flex;
  justify-content:flex-end;
  gap:8px;
}
.mini-modal-footer .btn-cancel{
  padding:8px 16px !important;
  border-radius:var(--r-md) !important;
  border:1px solid var(--stroke) !important;
  background:transparent !important;
  color:var(--txt-soft) !important;
  font-size:13px !important;
  font-weight:500 !important;
  cursor:pointer;
  transform:none !important;
}
.mini-modal-footer .btn-confirm{
  padding:8px 16px !important;
  border-radius:var(--r-md) !important;
  border:none !important;
  background:var(--blue) !important;
  color:#fff !important;
  font-size:13px !important;
  font-weight:600 !important;
  cursor:pointer;
  transform:none !important;
}
.mini-modal-footer .btn-confirm:hover{background:var(--cyan) !important;transform:none !important;}

/* Adaptación modo claro */
html[data-theme="light"] .mini-modal{
  background:#ffffff;
  border-color:#e2e8f0;
  box-shadow:0 20px 60px rgba(0,0,0,.15);
}
html[data-theme="light"] .mini-modal h4{color:#1a202c;}
html[data-theme="light"] .mini-modal p{color:#64748b;}
html[data-theme="light"] .mini-modal input{
  background:#f8fafc;
  border-color:#e2e8f0;
  color:#1a202c;
}
html[data-theme="light"] .mini-modal input::placeholder{color:#94a3b8;}
html[data-theme="light"] .mini-modal-footer .btn-cancel{
  border-color:#e2e8f0 !important;
  color:#64748b !important;
}
html[data-theme="light"] .mini-modal-overlay{background:rgba(0,0,0,.3);}

/* Botón y modal Agendar cita */
.btn-agendar{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:10px 18px;
  border-radius:var(--r-md);
  border:1px solid var(--cyan);
  background:rgba(56,199,244,.1);
  color:var(--cyan);
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  transition:all 150ms ease;
  margin-top:32px;
}
.btn-agendar:hover{
  background:rgba(56,199,244,.2);
  transform:translateY(-1px);
}
.modal-cita-overlay{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.6);
  backdrop-filter:blur(4px);
  z-index:1000;
  align-items:center;
  justify-content:center;
}
.modal-cita-overlay.active{display:flex;}
.modal-cita{
  background:var(--card);
  border:1px solid var(--stroke-strong);
  border-radius:var(--r-lg);
  padding:28px;
  width:100%;
  max-width:420px;
  box-shadow:0 20px 60px rgba(0,0,0,.5);
}
.modal-cita h3{
  font-size:18px;
  font-weight:700;
  margin-bottom:6px;
}
.modal-cita p{
  font-size:13px;
  color:var(--txt-soft);
  margin-bottom:22px;
}
.modal-cita .form-group{
  margin-bottom:16px;
}
.modal-cita-footer{
  display:flex;
  gap:10px;
  justify-content:flex-end;
  margin-top:22px;
}
.btn-cita-cancel{
  padding:10px 18px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:transparent;
  color:var(--txt-soft);
  font-size:13px;
  font-weight:600;
  cursor:pointer;
}
.btn-cita-confirm{
  padding:10px 20px;
  border-radius:var(--r-md);
  border:none;
  background:var(--cyan);
  color:#0a0e1a;
  font-size:13px;
  font-weight:700;
  cursor:pointer;
  display:flex;
  align-items:center;
  gap:8px;
  transition:opacity 150ms ease;
}
.btn-cita-confirm:hover{opacity:.85}

/* Toast cita guardada */
.cita-toast{
  display:none;
  position:fixed;
  bottom:32px;
  right:32px;
  background:var(--card,#1a2035);
  border:1px solid rgba(56,199,244,.4);
  border-radius:14px;
  padding:18px 20px 16px;
  width:300px;
  box-shadow:0 12px 40px rgba(0,0,0,.55);
  z-index:99990;
  animation:toastIn .3s ease;
}
.cita-toast.active{display:block;}
@keyframes toastIn{
  from{opacity:0;transform:translateY(16px);}
  to{opacity:1;transform:translateY(0);}
}
.cita-toast-header{
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:10px;
}
.cita-toast-icon{
  width:32px;
  height:32px;
  border-radius:50%;
  background:rgba(56,199,244,.15);
  display:flex;
  align-items:center;
  justify-content:center;
  flex:none;
}
.cita-toast-icon svg{color:var(--cyan,#38c7f4);}
.cita-toast-title{
  font-size:14px;
  font-weight:700;
  color:var(--txt,#e0e6f0);
}
.cita-toast-body{
  font-size:12px;
  color:var(--txt-soft,#8fa3cf);
  line-height:1.5;
  margin-bottom:14px;
  padding-left:42px;
}
.cita-toast-actions{
  display:flex;
  justify-content:flex-end;
  gap:8px;
}
.btn-toast-ver{
  padding:7px 14px;
  border-radius:8px;
  border:1px solid var(--cyan,#38c7f4);
  background:rgba(56,199,244,.1);
  color:var(--cyan,#38c7f4);
  font-size:12px;
  font-weight:600;
  cursor:pointer;
}
.btn-toast-cerrar{
  padding:7px 14px;
  border-radius:8px;
  border:1px solid var(--stroke,#2e3650);
  background:transparent;
  color:var(--txt-soft,#8fa3cf);
  font-size:12px;
  cursor:pointer;
}

/* Responsive */
@media (max-width:1200px){
  .hologram-container{display:none}
  .form-grid.personal{grid-template-columns:repeat(2, minmax(0, 1fr))}
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
  .patient-photo-container{position:static;margin:0 auto 20px;display:flex}
  .form-grid.personal{padding-right:0!important}
  .btn-save-fixed{position:static;margin-top:24px;width:100%;justify-content:center}
}
@media (max-width:480px){
  .form-card{padding:16px;border-radius:12px}
  .section-title{font-size:14px;flex-wrap:wrap;gap:8px}
  .btn-photo{padding:8px 12px;font-size:12px}
  .form-group label{font-size:12px}
  .form-group input,.form-group select,.form-group textarea{font-size:13px;padding:10px 12px}
  .phone-composite{grid-template-columns:68px minmax(0, 1fr)}
  .btn-save-fixed{font-size:13px;padding:12px 20px}
  .back-link{font-size:13px}
  .modal-photo{padding:16px}
  .camera-frame{height:160px}
}

.camera-frame video,
.camera-frame canvas{
  width:100%;
  height:100%;
  object-fit:cover;
  display:none;
}
.camera-frame.camera-active video{display:block}
.camera-frame.camera-active .avatar-preview{display:none}

</style>
@endpush

@section('content')

  {{-- Link volver --}}
  <a href="{{ route('pacientes.index') }}" class="back-link rise d1">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver a pacientes
  </a>

  {{-- Mensajes de sesión --}}
  @if(session('error'))
    <div style="margin-bottom:20px;padding:14px 18px;background:rgba(231,76,60,.15);border:1px solid rgba(231,76,60,.4);border-radius:var(--r-md);color:#ff6b6b;font-size:14px;">
      {{ session('error') }}
    </div>
  @endif
  @if(session('success'))
    <div style="margin-bottom:20px;padding:14px 18px;background:rgba(46,204,113,.15);border:1px solid rgba(46,204,113,.4);border-radius:var(--r-md);color:#2ecc71;font-size:14px;">
      {{ session('success') }}
    </div>
  @endif

  {{-- Formulario --}}
  <form id="pacienteForm" class="form-card rise d2" action="{{ route('pacientes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    {{-- Sección Información Personal --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
      <h2 class="section-title" style="margin:0;">Información personal</h2>
      <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 12px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);font-size:13px;">
        <span style="color:var(--txt-soft);">Folio:</span>
        <span style="font-weight:700;color:var(--cyan);">{{ old('folio', $folio ?? 'P-001') }}</span>
        <input type="hidden" name="folio" id="folioInput" value="{{ old('folio', $folio ?? 'P-001') }}">
        <input type="hidden" name="identificacion" id="identificacionInput" value="{{ old('identificacion', old('folio', $folio ?? 'P-001')) }}">
      </div>
    </div>

    <div class="personal-layout">
      {{-- Foto --}}
      <div class="personal-photo-col">
        <div class="patient-photo-container" id="patientPhotoContainer">
          <div class="patient-photo-placeholder" id="patientPhotoPlaceholder"></div>
          <img id="patientPhoto" style="display:none;" alt="Foto del paciente">
        </div>
        <input type="file" name="foto" id="inputFileFoto" accept="image/*" style="display:none;">
        <button type="button" class="btn-photo" id="btnAgregarFoto" style="width:100%;justify-content:center;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          Agregar foto
        </button>
      </div>
      {{-- Campos personales --}}
      <div class="form-grid personal" style="flex:1;">
      <div class="form-group span-2">
        <label>Nombre completo</label>
        <input type="text" name="nombre_completo" value="{{ old('nombre_completo') }}" placeholder="Nombre completo del paciente" required>
      </div>


      <div class="form-group">
        <label>Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento" id="fechaNacimiento" value="{{ old('fecha_nacimiento') }}" style="color-scheme:dark;" onclick="this.showPicker && this.showPicker()" onfocus="this.showPicker && this.showPicker()">
      </div>
      <div class="form-group">
        <label>Edad</label>
        <input type="number" name="edad" id="edadCalculada" value="{{ old('edad') }}" placeholder="--" readonly style="background:var(--panel-2);color:var(--txt-soft);cursor:default;">
      </div>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var fi = document.getElementById('fechaNacimiento');
          var fe = document.getElementById('edadCalculada');
          if (!fi || !fe) return;
          function calcE() {
            var v = fi.value; if (!v) { fe.value=''; return; }
            var p = v.split('-');
            var n = new Date(+p[0], +p[1]-1, +p[2]);
            var h = new Date();
            if (isNaN(n) || n > h) { fe.value=''; return; }
            var e = h.getFullYear()-n.getFullYear();
            var m = h.getMonth()-n.getMonth();
            if (m<0||(m===0&&h.getDate()<n.getDate())) e--;
            fe.value = e < 0 ? '' : e;
          }
          fi.addEventListener('change', calcE);
          fi.addEventListener('input', calcE);
          calcE();
        });
      </script>
      <div class="form-group">
        <label>Peso</label>
        <input type="number" step="0.01" name="peso" value="{{ old('peso') }}" placeholder="Peso en kg">
      </div>
      <div class="form-group">
        <label>Altura</label>
        <input type="number" step="0.01" name="altura" value="{{ old('altura') }}" placeholder="Altura en metros">
      </div>

      <div class="form-group">
        <label>Sexo</label>
        <select name="sexo">
          <option value="">Selecciona sexo</option>
          <option value="femenino" {{ old('sexo') == 'femenino' ? 'selected' : '' }}>Femenino</option>
          <option value="masculino" {{ old('sexo') == 'masculino' ? 'selected' : '' }}>Masculino</option>
          <option value="otro" {{ old('sexo') == 'otro' ? 'selected' : '' }}>Otro</option>
        </select>
      </div>
      <div class="form-group span-2">
        <label>Dirección</label>
        <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="CALLE, CP">
      </div>

      @php
        $telefonoCompleto = trim((string) old('telefono', ''));
        $telefonoLadaOld = old('telefono_lada');
        $telefonoNumeroOld = old('telefono_numero');
        $telefonoLada = $telefonoLadaOld !== null ? $telefonoLadaOld : '+52';
        $telefonoNumero = $telefonoNumeroOld !== null ? $telefonoNumeroOld : '';

        if ($telefonoLadaOld === null && $telefonoNumeroOld === null && $telefonoCompleto !== '') {
            if (preg_match('/^\s*(\+\d{1,4})[\s\-.]*(.*)$/', $telefonoCompleto, $telefonoPartes)) {
                $telefonoLada = $telefonoPartes[1];
                $telefonoNumero = $telefonoPartes[2] ?? '';
            } else {
                $telefonoNumero = $telefonoCompleto;
            }
        }
      @endphp
      <div class="form-group">
        <label>Teléfono</label>
        <input type="hidden" name="telefono" id="telefonoCompleto" value="{{ $telefonoCompleto }}">
        <div class="phone-composite">
          <input class="phone-lada" type="text" name="telefono_lada" id="telefonoLada" value="{{ $telefonoLada }}" placeholder="+52" maxlength="5" inputmode="tel" autocomplete="tel-country-code" aria-label="Lada del telefono">
          <input class="phone-number" type="tel" name="telefono_numero" id="telefonoNumero" value="{{ $telefonoNumero }}" placeholder="722 162 0815" maxlength="20" inputmode="tel" autocomplete="tel-national" aria-label="Numero de telefono">
        </div>
      </div>
      <div class="form-group span-3">
        <label>e-mail</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com">
      </div>
      </div>{{-- /form-grid personal --}}
    </div>{{-- /personal-layout --}}

    <hr style="border:none;border-top:1px solid var(--stroke);margin:28px 0;">

    {{-- Sección Información Médica --}}
    <h2 class="section-title">Información médica</h2>

    <div style="display:flex;gap:32px;align-items:flex-start;">
      {{-- Columna izquierda: Procedimiento + Fecha --}}
      <div style="flex:1;">
        <div class="form-group" style="margin-bottom:18px;">
          <label>Médico</label>
          <div class="select-with-add">
            <select id="medicoSelectMed" name="medico" data-campo="medico" style="flex:1;">
              <option value="">Seleccione un médico...</option>
              @foreach($listaMedicos as $m)
                <option value="{{ $m->nombre_completo }}">
                  {{ $m->nombre_completo }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:18px;">
          <label>Procedimiento</label>
          <div class="select-with-add">
            <select id="procedimientoSelect" name="procedimiento" data-campo="procedimiento" style="flex:1;">
              <option value="">Seleccione un procedimiento...</option>
              @foreach($listaProcedimientos as $p)
                <option value="{{ $p->nombre }}">
                  {{ $p->nombre }}
                </option>
              @endforeach
            </select>
          </div>
          <div id="procedimientosAgregados" class="procedimientos-tags"></div>
        </div>
        <div class="form-group" style="margin-bottom:18px;">
          <label>Anestesiólogo</label>
          <div class="select-with-add">
            <select id="anestesiologoSelect" name="anestesiologo" data-campo="anestesiologo" style="flex:1;">
              <option value="">Seleccione un anestesiólogo...</option>
              @foreach($listaAnestesiologos as $a)
                <option value="{{ $a->nombre_completo }}">
                  {{ $a->nombre_completo }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      {{-- Columna derecha: Diagnóstico --}}
      <div style="flex:1;display:flex;flex-direction:column;">
        <div class="form-group" style="flex:1;">
          <label>Diagnóstico Preliminar</label>
          <textarea name="diagnostico_preliminar" placeholder="Define lo que podría tener" style="min-height:220px;width:100%;">{{ old('diagnostico_preliminar') }}</textarea>
        </div>
      </div>
    </div>

    <hr style="border:none;border-top:1px solid var(--stroke);margin:28px 0;">

    {{-- Enfermedad, Alergias y Estudios --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
      <div class="form-group">
        <label>Enfermedad</label>
        <textarea name="enfermedad" placeholder="Describe la enfermedad o padecimiento del paciente" style="min-height:110px;width:100%;">{{ old('enfermedad') }}</textarea>
      </div>
      <div class="form-group">
        <label>Alergias</label>
        <textarea name="alergias" placeholder="Especifica las alergias del paciente (medicamentos, alimentos, etc.)" style="min-height:110px;width:100%;">{{ old('alergias') }}</textarea>
      </div>
    </div>

    <div class="form-group" style="margin-bottom:24px;">
      <label>Estudios (archivos)</label>
      <div style="border-radius:var(--r-md);padding:20px;background:var(--panel-2);border:1px solid var(--stroke);">
        <input type="file" name="estudios_archivos[]" id="estudiosArchivos" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.mp4,.mov,.avi" style="display:none;">
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:4px;">
          <button type="button" onclick="document.getElementById('estudiosArchivos').click()" style="display:flex;align-items:center;gap:8px;padding:9px 18px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--card);color:var(--txt);font:inherit;font-size:13px;font-weight:600;cursor:pointer;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Subir archivos
          </button>
          <span style="font-size:12.5px;color:var(--txt-soft);">PDF, imágenes, Word — múltiples archivos permitidos</span>
        </div>
        <div id="estudiosArchivosList" style="display:none;margin-top:16px;">
          <p style="font-size:12px;font-weight:700;color:var(--txt-soft);margin:0 0 10px;text-transform:uppercase;letter-spacing:.5px;">Por subir</p>
          <div id="estudiosArchivosGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;"></div>
        </div>
        {{-- Modal visor --}}
        <div id="visorArchivoOverlay" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.8);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
          <div style="background:var(--card);border:1px solid var(--stroke);border-radius:16px;width:min(960px,95vw);max-height:92vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 32px 80px rgba(0,0,0,.6);">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--stroke);flex-shrink:0;">
              <span id="visorArchivoNombre" style="font-size:14px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:60%;"></span>
              <div style="display:flex;gap:8px;flex-shrink:0;">
                <a id="visorArchivoDescarga" href="#" download style="display:flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;background:var(--panel-2);border:1px solid var(--stroke);font-size:12px;font-weight:600;color:var(--txt);text-decoration:none;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  Descargar
                </a>
                <button type="button" onclick="cerrarVisorArchivo()" style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);cursor:pointer;color:var(--txt);">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
              </div>
            </div>
            <div id="visorArchivoContenido" style="flex:1;overflow:auto;display:flex;align-items:center;justify-content:center;min-height:400px;background:var(--panel-2);"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Botón guardar --}}
    <div style="display:flex;justify-content:flex-end;margin-top:28px;">
      <button type="submit" class="btn-save" id="btnGuardarPaciente">
        Guardar paciente
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </button>
    </div>

  </form>


  {{-- Toast cita guardada --}}
  <div class="cita-toast" id="citaToast">
    <div class="cita-toast-header">
      <div class="cita-toast-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <span class="cita-toast-title">Cita guardada</span>
    </div>
    <div class="cita-toast-body" id="citaToastBody">—</div>
    <div class="cita-toast-actions">
      <button class="btn-toast-cerrar" onclick="document.getElementById('citaToast').classList.remove('active')">Cerrar</button>
      <button class="btn-toast-ver" onclick="verDetallesCita()">Ver detalles</button>
    </div>
  </div>

  {{-- Modal detalle de cita --}}
  <div class="modal-cita-overlay" id="modalDetalleCita">
    <div class="modal-cita">
      <h3>Detalles de la cita</h3>
      <p id="detalleCitaTexto" style="color:var(--txt);font-size:14px;line-height:1.7;"></p>
      <div class="modal-cita-footer">
        <button class="btn-cita-cancel" onclick="document.getElementById('modalDetalleCita').classList.remove('active')">Cerrar</button>
      </div>
    </div>
  </div>

  {{-- Modal Agendar Cita --}}
  <div class="modal-cita-overlay" id="modalCita">
    <div class="modal-cita">
      <h3>Agendar cita</h3>
      <p>Selecciona la fecha y hora para la cita del paciente</p>
      <div class="form-group">
        <label>Fecha de la cita</label>
        <input type="date" id="citaFecha" style="width:100%;">
      </div>
      <div class="form-group">
        <label>Hora</label>
        <input type="time" id="citaHora" style="width:100%;">
      </div>
      <div class="form-group">
        <label>Motivo</label>
        <input type="text" id="citaMotivo" placeholder="Consulta, seguimiento..." style="width:100%;">
      </div>
      <div class="modal-cita-footer">
        <button class="btn-cita-cancel" onclick="document.getElementById('modalCita').classList.remove('active')">Cancelar</button>
        <button class="btn-cita-confirm" onclick="confirmarCita()">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          Confirmar cita
        </button>
      </div>
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
      <h2>¡Paciente registrado!</h2>
      <p>El paciente ha sido registrado exitosamente en el sistema.</p>
      <button class="btn-aceptar" onclick="window.location.href='{{ route('agendar') }}'">
        Ir a agenda
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
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
            <div class="avatar-preview"></div>
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

  // ===== MINI MODAL AGREGAR OPCIÓN (se inyecta en body) =====
  (function(){
    var _selId = null;
    var html = '<div id="_mm" class="mini-modal-overlay" style="display:none;">'
      +'<div class="mini-modal">'
      +'<h4 id="_mmT">Agregar</h4>'
      +'<p id="_mmD">Escribe el nombre</p>'
      +'<input id="_mmI" type="text" placeholder="Nombre..." autocomplete="off">'
      +'<div class="mini-modal-footer">'
      +'<button id="_mmC" type="button" class="btn-cancel">Cancelar</button>'
      +'<button id="_mmO" type="button" class="btn-confirm">Agregar</button>'
      +'</div></div></div>';
    document.body.insertAdjacentHTML('beforeend', html);
    var ov = document.getElementById('_mm');
    window.abrirMiniModal = function(sid, t, d) {
      _selId = sid;
      document.getElementById('_mmT').textContent = t;
      document.getElementById('_mmD').textContent = d;
      document.getElementById('_mmI').value = '';
      ov.style.display = 'flex';
      setTimeout(function(){ document.getElementById('_mmI').focus(); }, 60);
    };
    window.cerrarMiniModal = function() { ov.style.display='none'; _selId=null; };
    window.confirmarMiniModal = function() {
      var n = document.getElementById('_mmI').value.trim();
      if (!n || !_selId) return;
      var s = document.getElementById(_selId);
      var campo = s?.dataset.campo;
      var pacienteId = document.querySelector('input[name="paciente_id"]')?.value;

      // Si es anestesiologo, crear registro real en la base de datos
      if (_selId === 'anestesiologoSelect') {
        fetch('{{ route("anestesiologos.store") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ nombres: n, activo: 1 })
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
          if (data.success && data.anestesiologo) {
            var nombre = data.anestesiologo.nombre_completo;
            agregarOpcionSelect(s, nombre);
            window.cerrarMiniModal();
          } else {
            alert('No se pudo guardar el anestesiólogo.');
          }
        })
        .catch(function(err){
          console.error('Error:', err);
          alert('Error al crear anestesiólogo: ' + err.message);
        });
        return;
      }

      // Si es medico, crear registro real en la base de datos
      if (_selId === 'medicoSelectMed') {
        fetch('{{ route("medicos.store") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ nombres: n, activo: 1 })
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
          if (data.success && data.medico) {
            var nombre = data.medico.nombre_completo;
            agregarOpcionSelect(s, nombre);
            window.cerrarMiniModal();
          } else {
            alert('No se pudo guardar el médico.');
          }
        })
        .catch(function(err){
          console.error('Error:', err);
          alert('Error al crear médico: ' + err.message);
        });
        return;
      }

      // Si el select tiene data-campo y estamos editando (hay paciente_id), guardar en base de datos
      if (campo && pacienteId) {
        fetch('{{ route("pacientes.update-campo", ":paciente_id") }}'.replace(':paciente_id', pacienteId), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ campo: campo, valor: n })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            if (s && s.tagName === 'INPUT') {
              s.value = data.valor;
            } else {
              s.innerHTML = '';
              var o = document.createElement('option');
              o.value = data.valor.toLowerCase().replace(/\s+/g,'-');
              o.textContent = data.valor;
              o.selected = true;
              s.appendChild(o);
            }
            window.cerrarMiniModal();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          if (s && s.tagName === 'INPUT') {
            s.value = n;
          } else {
            agregarOpcionSelect(s, n);
          }
          window.cerrarMiniModal();
        });
        return;
      }
      
      // En create (sin paciente_id), asignar al input de texto
      if (s && s.tagName === 'INPUT') {
        s.value = n;
      } else {
        agregarOpcionSelect(s, n);
      }
      window.cerrarMiniModal();
    };

    function agregarOpcionSelect(s, n) {
      var o = document.createElement('option');
      o.value = n.toLowerCase().replace(/\s+/g,'-');
      o.textContent = n;
      o.selected = true;
      s.insertBefore(o, s.lastElementChild);
    }
    document.getElementById('_mmC').onclick = window.cerrarMiniModal;
    document.getElementById('_mmO').onclick = window.confirmarMiniModal;
    document.getElementById('_mmI').onkeydown = function(e){
      if(e.key==='Enter') window.confirmarMiniModal();
      if(e.key==='Escape') window.cerrarMiniModal();
    };
    ov.onclick = function(e){ if(e.target===ov) window.cerrarMiniModal(); };
  })();

  window.addNuevoProcedimiento = function(){ window.abrirMiniModal('procedimientoSelect','Agregar procedimiento','Nombre del procedimiento'); };
  window.addMedicoMed         = function(){ window.abrirMiniModal('medicoSelectMed','Agregar médico','Nombre del médico'); };
  window.addAnestesiologo     = function(){ window.abrirMiniModal('anestesiologoSelect','Agregar anestesiólogo','Nombre del anestesiólogo'); };
  window.addReferidoMed       = function(){ window.abrirMiniModal('referidoSelectMed','Agregar referido','Nombre del referido'); };
  window.addEquipo            = function(){ window.abrirMiniModal('equipoSelect','Agregar equipo','Nombre del equipo'); };

  const btnAgregarFotoOld = null; /* reemplazado por FOTO DEL PACIENTE */
  const modalFotoOld = null; /* reemplazado por FOTO DEL PACIENTE */
  const btnCancelarFotoOld = null; /* reemplazado por FOTO DEL PACIENTE */

  // Input file del formulario
  const inputFileFotoOld = null; /* reemplazado por FOTO DEL PACIENTE */

  // Referencias al recuadro de foto
  const patientPhotoContainer = document.getElementById('patientPhotoContainer');
  const patientPhotoOld = null; /* reemplazado por FOTO DEL PACIENTE */
  const patientPhotoPlaceholderOld = null; /* reemplazado por FOTO DEL PACIENTE */
  const btnUsarFotoOld = null; /* reemplazado por FOTO DEL PACIENTE */

  // Variable para almacenar la foto actual (base64)
  let currentPhotoDataOld = null;
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
      const select = document.getElementById('medicoSelectMed');
      if (!select) return;
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
  // ===== PROCEDIMIENTO / MÉDICO / ANESTESIÓLOGO / REFERIDO / EQUIPO =====
  window.addNuevoProcedimiento = function() { window.abrirMiniModal('procedimientoSelect','Agregar procedimiento','Escribe el nombre del procedimiento'); };
  window.addMedicoMed         = function() { window.abrirMiniModal('medicoSelectMed','Agregar médico','Escribe el nombre del médico'); };
  window.addAnestesiologo     = function() { window.abrirMiniModal('anestesiologoSelect','Agregar anestesiólogo','Escribe el nombre del anestesiólogo'); };
  window.addReferidoMed       = function() { window.abrirMiniModal('referidoSelectMed','Agregar referido','Escribe el nombre del referido'); };
  window.addEquipo            = function() { window.abrirMiniModal('equipoSelect','Agregar equipo','Escribe el nombre del equipo'); };

  // ===== ARCHIVOS DE ESTUDIOS =====
  function makeFileCard(nombre, url, tipo, size) {
    const ext = nombre.split('.').pop().toUpperCase();
    const colors = { imagen:{bg:'#f59e0b22',color:'#f59e0b'}, pdf:{bg:'#ef444422',color:'#ef4444'}, video:{bg:'#f9731622',color:'#f97316'} };
    const c = colors[tipo] || {bg:'#6b728022',color:'#6b7280'};
    const iconSvg = tipo==='imagen'
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="'+c.color+'" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>'
      : tipo==='pdf'
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="'+c.color+'" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>'
      : tipo==='video'
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="'+c.color+'" stroke-width="2" stroke-linecap="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="'+c.color+'" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
    const sizeStr = size>=1048576?(size/1048576).toFixed(1)+' MB':size>=1024?(size/1024).toFixed(1)+' KB':size+' B';
    const card = document.createElement('div');
    card.style.cssText = 'position:relative;display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:12px;background:var(--card);border:1px solid var(--stroke);cursor:pointer;transition:border-color 150ms;';
    card.onmouseenter = function(){ this.style.borderColor='var(--blue)'; };
    card.onmouseleave = function(){ this.style.borderColor='var(--stroke)'; };
    card.onclick = function(){ abrirVisorArchivo(url, nombre, tipo); };
    card.innerHTML =
      '<div style="width:38px;height:44px;border-radius:6px;background:'+c.bg+';display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;">'
        +iconSvg
        +'<span style="font-size:7px;font-weight:800;color:'+c.color+';margin-top:2px;">'+ext+'</span>'
      +'</div>'
      +'<div style="flex:1;min-width:0;">'
        +'<p style="margin:0;font-size:12.5px;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="'+nombre+'">'+nombre+'</p>'
        +'<p style="margin:3px 0 0;font-size:11px;color:var(--txt-soft);">'+sizeStr+' &bull; pendiente</p>'
      +'</div>';
    return card;
  }

  document.getElementById('estudiosArchivos')?.addEventListener('change', function(){
    const wrapper = document.getElementById('estudiosArchivosList');
    const grid = document.getElementById('estudiosArchivosGrid');
    grid.innerHTML = '';
    if (this.files.length === 0) { wrapper.style.display='none'; return; }
    wrapper.style.display = 'block';
    Array.from(this.files).forEach(function(f){
      const ext = f.name.split('.').pop().toLowerCase();
      const isImg = ['jpg','jpeg','png','webp','gif'].includes(ext);
      const isPdf = ext === 'pdf';
      const isVideo = ['mp4','mov','avi','mkv','webm'].includes(ext);
      const url = URL.createObjectURL(f);
      const tipo = isImg?'imagen':isPdf?'pdf':isVideo?'video':'otro';
      grid.appendChild(makeFileCard(f.name, url, tipo, f.size));
    });
  });

  // ===== VISOR DE ARCHIVOS =====
  window.abrirVisorArchivo = function(url, nombre, tipo) {
    const overlay = document.getElementById('visorArchivoOverlay');
    const contenido = document.getElementById('visorArchivoContenido');
    if (!overlay) return;
    document.getElementById('visorArchivoNombre').textContent = nombre;
    const descarga = document.getElementById('visorArchivoDescarga');
    descarga.href = url; descarga.download = nombre;
    contenido.innerHTML = '';
    if (tipo === 'imagen') {
      const img = document.createElement('img');
      img.src = url;
      img.style.cssText = 'max-width:100%;max-height:calc(92vh - 70px);object-fit:contain;display:block;padding:16px;';
      contenido.appendChild(img);
    } else if (tipo === 'pdf') {
      const iframe = document.createElement('iframe');
      iframe.src = url;
      iframe.style.cssText = 'width:100%;height:calc(92vh - 70px);border:none;';
      contenido.appendChild(iframe);
    } else if (tipo === 'video') {
      const video = document.createElement('video');
      video.src = url; video.controls = true;
      video.style.cssText = 'max-width:100%;max-height:calc(92vh - 70px);display:block;';
      contenido.appendChild(video);
    } else {
      contenido.innerHTML = '<div style="text-align:center;padding:48px;color:var(--txt-soft);">'
        +'<svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" style="margin:0 auto 16px;display:block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'
        +'<p style="font-size:14px;margin:0 0 20px;">Vista previa no disponible para este tipo de archivo.</p>'
        +'<a href="'+url+'" download="'+nombre+'" style="padding:10px 22px;border-radius:8px;background:#3b82f6;color:#fff;text-decoration:none;font-size:13px;font-weight:600;">Descargar archivo</a>'
        +'</div>';
    }
    overlay.style.display = 'flex';
  };
  window.cerrarVisorArchivo = function() {
    const o = document.getElementById('visorArchivoOverlay');
    if (o) o.style.display = 'none';
    document.getElementById('visorArchivoContenido').innerHTML = '';
  };
  document.getElementById('visorArchivoOverlay')?.addEventListener('click', function(e){
    if (e.target === this) window.cerrarVisorArchivo();
  });
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') window.cerrarVisorArchivo();
  });

  // ===== AGENDAR CITA =====
  var _citaData = {};
  window.confirmarCita = function() {
    var fecha = document.getElementById('citaFecha').value;
    var hora  = document.getElementById('citaHora').value;
    var motivo = document.getElementById('citaMotivo').value.trim();
    if (!fecha || !hora) {
      alert('Por favor selecciona fecha y hora para la cita.');
      return;
    }
    // Formatear fecha legible
    var partes = fecha.split('-');
    var fechaLeg = partes[2]+'/'+partes[1]+'/'+partes[0];
    _citaData = { fecha: fechaLeg, hora: hora, motivo: motivo };
    document.getElementById('modalCita').classList.remove('active');
    // Mostrar toast
    document.getElementById('citaToastBody').textContent =
      fechaLeg + ' — ' + hora + (motivo ? '\n' + motivo : '');
    var toast = document.getElementById('citaToast');
    toast.classList.add('active');
    // Auto-ocultar en 8 seg
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(function(){ toast.classList.remove('active'); }, 8000);
  };
  window.verDetallesCita = function() {
    document.getElementById('citaToast').classList.remove('active');
    var d = _citaData;
    document.getElementById('detalleCitaTexto').innerHTML =
      '<strong>Fecha:</strong> ' + d.fecha + '<br>' +
      '<strong>Hora:</strong> ' + d.hora + (d.motivo ? '<br><strong>Motivo:</strong> ' + d.motivo : '');
    document.getElementById('modalDetalleCita').classList.add('active');
  };

})();


  // ===== FOLIO E IDENTIFICACIÓN AUTOMÁTICOS DESDE BACKEND =====
  const folioInput = document.getElementById('folioInput');
  const identificacionInput = document.getElementById('identificacionInput');

  if (folioInput && identificacionInput) {
    identificacionInput.value = folioInput.value;
  }

  // ===== EDAD EN TIEMPO REAL DESDE FECHA DE NACIMIENTO =====
  const fechaNacimiento = document.getElementById('fechaNacimiento');
  const edadCalculada = document.getElementById('edadCalculada');

  function calcularEdad(fechaNacimientoValor) {
    if (!fechaNacimientoValor) return '';

    const nacimiento = new Date(fechaNacimientoValor + 'T00:00:00');
    const hoy = new Date();

    if (isNaN(nacimiento.getTime()) || nacimiento > hoy) return '';

    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();

    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
      edad--;
    }

    return edad >= 0 ? edad : '';
  }

  function actualizarEdad() {
    if (!fechaNacimiento || !edadCalculada) return;
    edadCalculada.value = calcularEdad(fechaNacimiento.value);
  }

  if (fechaNacimiento) {
    fechaNacimiento.addEventListener('input', actualizarEdad);
    fechaNacimiento.addEventListener('change', actualizarEdad);
    fechaNacimiento.addEventListener('keyup', actualizarEdad);
    fechaNacimiento.addEventListener('click', function() {
      if (typeof fechaNacimiento.showPicker === 'function') fechaNacimiento.showPicker();
    });
    fechaNacimiento.addEventListener('focus', function() {
      if (typeof fechaNacimiento.showPicker === 'function') fechaNacimiento.showPicker();
    });
    actualizarEdad();
  }



  // ===== TELEFONO CON LADA =====
  const telefonoCompleto = document.getElementById('telefonoCompleto');
  const telefonoLada = document.getElementById('telefonoLada');
  const telefonoNumero = document.getElementById('telefonoNumero');

  function normalizarTelefonoLada() {
    if (!telefonoLada) return '';

    const digits = telefonoLada.value.replace(/\D/g, '').slice(0, 4);
    telefonoLada.value = digits ? '+' + digits : '';

    return telefonoLada.value;
  }

  function normalizarTelefonoNumero() {
    if (!telefonoNumero) return '';

    telefonoNumero.value = telefonoNumero.value
      .replace(/[^\d\s().-]/g, '')
      .slice(0, 20);

    return telefonoNumero.value.trim();
  }

  function syncTelefonoCompleto() {
    if (!telefonoCompleto || !telefonoLada || !telefonoNumero) return;

    const lada = normalizarTelefonoLada();
    const numero = normalizarTelefonoNumero();

    telefonoCompleto.value = numero ? [lada, numero].filter(Boolean).join(' ') : '';
  }

  if (telefonoLada && telefonoNumero) {
    telefonoLada.addEventListener('input', syncTelefonoCompleto);
    telefonoNumero.addEventListener('input', syncTelefonoCompleto);
    syncTelefonoCompleto();
  }

  // ===== FOTO DEL PACIENTE: GALERÍA Y CÁMARA REAL =====
  const btnAgregarFotoAuto = document.getElementById('btnAgregarFoto');
  const modalFotoAuto = document.getElementById('modalFoto');
  const btnCancelarFotoAuto = document.getElementById('btnCancelarFoto');
  const btnUsarFotoAuto = document.getElementById('btnUsarFoto');
  const inputFileFotoAuto = document.getElementById('inputFileFoto');
  const patientPhotoAuto = document.getElementById('patientPhoto');
  const patientPhotoPlaceholderAuto = document.getElementById('patientPhotoPlaceholder');
  const cameraFrameAuto = document.querySelector('.camera-frame');
  const avatarPreviewAuto = document.querySelector('.avatar-preview');

  let cameraStreamAuto = null;
  let currentPhotoDataAuto = null;

  function prepararVideoCamara() {
    if (!cameraFrameAuto) return null;

    let video = document.getElementById('cameraVideoPaciente');
    if (!video) {
      video = document.createElement('video');
      video.id = 'cameraVideoPaciente';
      video.autoplay = true;
      video.playsInline = true;
      video.muted = true;
      cameraFrameAuto.appendChild(video);
    }

    let canvas = document.getElementById('cameraCanvasPaciente');
    if (!canvas) {
      canvas = document.createElement('canvas');
      canvas.id = 'cameraCanvasPaciente';
      cameraFrameAuto.appendChild(canvas);
    }

    return { video, canvas };
  }

  async function iniciarCamaraPaciente() {
    const media = prepararVideoCamara();
    if (!media || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      alert('Tu navegador no permite abrir la cámara desde aquí. Usa la opción Subir imagen.');
      return;
    }

    try {
      detenerCamaraPaciente();
      cameraStreamAuto = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'user' },
        audio: false
      });

      media.video.srcObject = cameraStreamAuto;
      cameraFrameAuto.classList.add('camera-active');
      currentPhotoDataAuto = null;
    } catch (error) {
      alert('No se pudo abrir la cámara. Revisa permisos del navegador o usa Subir imagen.');
    }
  }

  function detenerCamaraPaciente() {
    if (cameraStreamAuto) {
      cameraStreamAuto.getTracks().forEach(track => track.stop());
      cameraStreamAuto = null;
    }

    if (cameraFrameAuto) {
      cameraFrameAuto.classList.remove('camera-active');
    }
  }

  function asignarArchivoDesdeBlob(blob) {
    if (!inputFileFotoAuto) return;

    const file = new File([blob], 'foto-paciente.png', { type: 'image/png' });
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    inputFileFotoAuto.files = dataTransfer.files;
  }

  function mostrarFotoSeleccionada(dataUrl) {
    if (patientPhotoAuto && patientPhotoPlaceholderAuto) {
      patientPhotoAuto.src = dataUrl;
      patientPhotoAuto.style.display = 'block';
      patientPhotoPlaceholderAuto.style.display = 'none';
    }

    if (avatarPreviewAuto) {
      avatarPreviewAuto.style.backgroundImage = `url(${dataUrl})`;
      avatarPreviewAuto.style.backgroundSize = 'cover';
      avatarPreviewAuto.style.backgroundPosition = 'center';
      avatarPreviewAuto.textContent = '';
    }
  }

  function capturarFotoDesdeCamara() {
    const media = prepararVideoCamara();
    if (!media || !cameraStreamAuto) return false;

    const video = media.video;
    const canvas = media.canvas;

    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;

    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    currentPhotoDataAuto = canvas.toDataURL('image/png');

    canvas.toBlob(function(blob) {
      if (blob) asignarArchivoDesdeBlob(blob);
    }, 'image/png');

    mostrarFotoSeleccionada(currentPhotoDataAuto);
    detenerCamaraPaciente();

    return true;
  }

  if (btnAgregarFotoAuto && modalFotoAuto) {
    btnAgregarFotoAuto.addEventListener('click', function() {
      modalFotoAuto.classList.add('active');

      if (avatarPreviewAuto && !currentPhotoDataAuto) {
        avatarPreviewAuto.textContent = '';
        avatarPreviewAuto.style.backgroundImage = '';
      }
    });
  }

  if (btnCancelarFotoAuto && modalFotoAuto) {
    btnCancelarFotoAuto.addEventListener('click', function() {
      detenerCamaraPaciente();
      modalFotoAuto.classList.remove('active');
    });
  }

  if (modalFotoAuto) {
    modalFotoAuto.addEventListener('click', function(e) {
      if (e.target === modalFotoAuto) {
        detenerCamaraPaciente();
        modalFotoAuto.classList.remove('active');
      }
    });
  }

  const sourceOptionsAuto = document.querySelectorAll('.source-option');
  sourceOptionsAuto.forEach(function(option, index) {
    option.addEventListener('click', function() {
      sourceOptionsAuto.forEach(o => o.classList.remove('active'));
      option.classList.add('active');

      if (index === 0) {
        iniciarCamaraPaciente();
      }

      if (index === 1 && inputFileFotoAuto) {
        detenerCamaraPaciente();
        inputFileFotoAuto.click();
      }
    });
  });

  const camBtnsAuto = document.querySelectorAll('.cam-btn');
  camBtnsAuto.forEach(function(btn, index) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();

      camBtnsAuto.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      if (index === 0 && inputFileFotoAuto) {
        detenerCamaraPaciente();
        inputFileFotoAuto.click();
      }

      if (index === 1) {
        if (cameraStreamAuto) {
          capturarFotoDesdeCamara();
        } else {
          iniciarCamaraPaciente();
        }
      }

      if (index === 2) {
        iniciarCamaraPaciente();
      }
    });
  });

  if (inputFileFotoAuto) {
    inputFileFotoAuto.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;

      detenerCamaraPaciente();

      const reader = new FileReader();
      reader.onload = function(event) {
        currentPhotoDataAuto = event.target.result;
        mostrarFotoSeleccionada(currentPhotoDataAuto);
      };
      reader.readAsDataURL(file);
    });
  }

  if (btnUsarFotoAuto && modalFotoAuto) {
    btnUsarFotoAuto.addEventListener('click', function() {
      if (!currentPhotoDataAuto) {
        showAppAlert('Aviso', 'Selecciona una imagen o toma una foto primero.');
        return;
      }

      detenerCamaraPaciente();
      modalFotoAuto.classList.remove('active');
    });
  }

  // ===== GUARDAR PACIENTE Y REDIRIGIR A AGENDA =====
  const pacienteForm = document.getElementById('pacienteForm');
  const btnGuardar = document.getElementById('btnGuardarPaciente');

  if (pacienteForm && btnGuardar) {
    pacienteForm.addEventListener('submit', function(e) {
      syncTelefonoCompleto();

      if (!pacienteForm.checkValidity()) {
        pacienteForm.reportValidity();
        e.preventDefault();
        return;
      }

      btnGuardar.disabled = true;
      btnGuardar.innerHTML = 'Guardando...';
    });
  }

</script>
@endpush
