@extends('layouts.app')

@section('title', 'Editar Paciente')
@section('active', 'pacientes')
@section('header-title', 'Editar información del paciente')
@section('header-sub')
  Actualiza los datos personales, médicos y de contacto del paciente
@endsection

@push('styles')
<style>
/* ============ ESTILOS EDITAR PACIENTE ============ */

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
  font-size:20px;
  font-weight:700;
  margin-bottom:24px;
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

/* Input con icono */
.input-with-icon{
  position:relative;
}
.input-with-icon input{
  padding-right:40px;
}
.input-with-icon .icon{
  position:absolute;
  right:12px;
  top:50%;
  transform:translateY(-50%);
  width:18px;
  height:18px;
  color:var(--txt-soft);
  pointer-events:none;
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

/* Modal éxito */
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
.modal-success-icon svg{width:35px;height:35px;color:#fff;}
.modal-success h2{font-size:22px;font-weight:600;color:var(--txt);margin-bottom:12px;}
.modal-success p{font-size:14px;color:var(--txt-soft);margin-bottom:28px;}
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
.btn-aceptar:hover{background:linear-gradient(180deg,#2ecc71 0%,#27ae60 100%);transform:translateY(-1px);}

/* Modal overlay */
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

/* Textarea diagnóstico */
textarea{
  resize:vertical;
  min-height:100px;
}

/* Holograma */
.hologram-container{
  position:absolute;
  right:80px;
  top:72%;
  transform:translateY(-50%);
  width:260px;
  height:380px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  background:transparent;
  mix-blend-mode:lighten;
}
.hologram{
  width:100%;
  height:320px;
  position:relative;
}
.hologram svg{
  width:100%;
  height:100%;
  filter:drop-shadow(0 0 25px rgba(56,199,244,.5));
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
  border:0;
  background:transparent;
  font-size:15px;
  font-weight:600;
  color:var(--cyan);
  transition:all 150ms ease;
  cursor:pointer;
}
.btn-save:hover{
  color:var(--blue);
  transform:translateX(4px);
}
.btn-save svg{
  width:18px;
  height:18px;
}

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
  width:32px;
  height:32px;
  border-radius:8px;
  border:none;
  background:var(--blue);
  color:#fff;
  display:grid;
  place-items:center;
  cursor:pointer;
  transition:all 150ms ease;
  flex:none;
}
.btn-add-procedimiento:hover{
  opacity:0.9;
  transform:scale(1.05);
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
  padding:10px 12px;
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  background:var(--panel-2);
  color:var(--txt);
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
  background:var(--panel) !important;
  color:var(--txt) !important;
  font-size:13px !important;
  font-weight:500 !important;
  cursor:pointer;
  transform:none !important;
}
.mini-modal-footer .btn-cancel:hover{
  background:var(--panel-2) !important;
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
.cita-toast-header{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.cita-toast-icon{width:32px;height:32px;border-radius:50%;background:rgba(56,199,244,.15);display:flex;align-items:center;justify-content:center;flex:none;}
.cita-toast-icon svg{color:var(--cyan,#38c7f4);}
.cita-toast-title{font-size:14px;font-weight:700;color:var(--txt,#e0e6f0);}
.cita-toast-body{font-size:12px;color:var(--txt-soft,#8fa3cf);line-height:1.5;margin-bottom:14px;padding-left:42px;}
.cita-toast-actions{display:flex;justify-content:flex-end;gap:8px;}
.btn-toast-ver{padding:7px 14px;border-radius:8px;border:1px solid var(--cyan,#38c7f4);background:rgba(56,199,244,.1);color:var(--cyan,#38c7f4);font-size:12px;font-weight:600;cursor:pointer;}
.btn-toast-cerrar{padding:7px 14px;border-radius:8px;border:1px solid var(--stroke,#2e3650);background:transparent;color:var(--txt-soft,#8fa3cf);font-size:12px;cursor:pointer;}

/* Responsive */
@media (max-width:1200px){
  .hologram-container{display:none}
  .form-grid.personal{grid-template-columns:repeat(2, 1fr)}
  .form-group.span-2,
  .form-group.span-3{grid-column:span 2}
}
@media (max-width:768px){
  .form-grid.personal,
  .form-grid.medical{grid-template-columns:1fr}
  .form-group.span-2,
  .form-group.span-3{grid-column:span 1}
  .form-card{padding:20px}
  .patient-photo-container{position:static;margin:0 auto 20px;display:flex}
  .form-grid.personal{padding-right:0!important}
  .modal-body{grid-template-columns:1fr}
  .modal-photo{padding:20px}
  .form-footer{justify-content:center}
  .btn-save{width:100%;justify-content:center}
  .camera-controls{gap:20px}
  .cam-btn .icon{width:36px;height:36px}
}
@media (max-width:480px){
  .form-card{padding:16px;border-radius:12px}
  .section-title{font-size:14px;flex-wrap:wrap;gap:8px}
  .btn-photo{padding:8px 12px;font-size:12px}
  .form-group label{font-size:12px}
  .form-group input,.form-group select,.form-group textarea{font-size:13px;padding:10px 12px}
  .back-link{font-size:13px}
  .modal-photo{padding:16px}
  .camera-frame{height:160px}
  .btn-save{font-size:13px;padding:12px 20px}
  .camera-controls{gap:16px}
  .cam-btn{font-size:11px}
  .cam-btn .icon{width:32px;height:32px}
}

/* Select resolución modal foto */
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
.option-section h4{
  font-size:13px;
  font-weight:600;
  margin-bottom:12px;
  color:var(--txt-soft);
}

/* ===== MODAL FOTO ===== */
.modal-photo{
  background:linear-gradient(180deg,var(--card),var(--panel-2));
  border:1px solid var(--stroke-strong);
  border-radius:var(--r-lg);
  width:100%;
  max-width:900px;
  max-height:90vh;
  overflow-y:auto;
  overflow-x:hidden;
  padding:28px 32px;
  animation:modalIn 300ms var(--ease-out);
  box-sizing:border-box;
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

.camera-frame video,
.camera-frame canvas{
  width:100%;
  height:100%;
  object-fit:cover;
  display:none;
}
.camera-frame.camera-active video{display:block}
.camera-frame.camera-active .avatar-preview{display:none}

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
  margin:0;
  padding-left:18px;
  font-size:12px;
  color:var(--txt-soft);
  line-height:1.6;
}

/* Modal footer */
.modal-footer{
  display:flex;
  justify-content:flex-end;
  gap:12px;
  margin-top:24px;
  padding-top:20px;
  border-top:1px solid var(--stroke);
}
.btn-cancel{
  padding:10px 20px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:transparent;
  color:var(--txt-soft);
  font-size:13px;
  font-weight:600;
  cursor:pointer;
}
.btn-confirm{
  padding:10px 24px;
  border-radius:var(--r-md);
  border:none;
  background:var(--blue);
  color:#fff;
  font-size:13px;
  font-weight:600;
  cursor:pointer;
}

/* Hover de botones de galería y reportes */
.btn-outline.btn-galeria:hover{
  background:#f59e0b !important;
  color:#fff !important;
}
.btn-outline.btn-reportes:hover{
  background:var(--cyan) !important;
  color:#fff !important;
}
.btn-outline.btn-disabled:hover{
  background:transparent !important;
  color:#777 !important;
}
</style>
@endpush

@section('content')

  {{-- Link volver --}}
  <a href="{{ route('pacientes.index') }}" class="back-link rise d1">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
    Volver a pacientes
  </a>

  {{-- Formulario --}}
  <form id="pacienteForm" class="form-card rise d2" action="{{ route('pacientes.update', $paciente) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Sección Información Personal --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
      <h2 class="section-title" style="margin:0;">Información personal</h2>
      <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 12px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);font-size:13px;">
        <span style="color:var(--txt-soft);">Folio:</span>
        <span id="folioVisual" style="font-weight:700;color:var(--cyan);">{{ old('folio', $paciente->folio ?: $paciente->identificacion) }}</span>
        <input type="hidden" name="folio" id="folioInput" value="{{ old('folio', $paciente->folio ?: $paciente->identificacion) }}">
      </div>
    </div>

    <div class="personal-layout">
      {{-- Foto --}}
      <div class="personal-photo-col">
        <div class="patient-photo-container" id="patientPhotoContainer">
          @if($paciente->foto)
            <img id="patientPhoto" src="{{ asset('storage/' . $paciente->foto) }}" alt="Foto del paciente">
            <div class="patient-photo-placeholder" id="patientPhotoPlaceholder" style="display:none;">👤</div>
          @else
            <div class="patient-photo-placeholder" id="patientPhotoPlaceholder">👤</div>
            <img id="patientPhoto" style="display:none;" alt="Foto del paciente">
          @endif
        </div>
        <input type="file" name="foto" id="inputFileFoto" accept="image/*" style="display:none;">
        <button type="button" class="btn-photo" onclick="window.abrirModalFoto()">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          Modificar foto
        </button>
      </div>
      {{-- Campos personales --}}
      <div class="form-grid personal" style="flex:1;">
      <div class="form-group span-2">
        <label>Nombre completo</label>
        <input type="text" name="nombre_completo" value="{{ old('nombre_completo', $paciente->nombre_completo) }}" required>
      </div>
      <div class="form-group">
        <label>Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento" id="fechaNacimientoEdit" value="{{ old('fecha_nacimiento', optional($paciente->fecha_nacimiento)->format('Y-m-d')) }}" style="color-scheme:dark;">
      </div>
      <div class="form-group">
        <label>Edad</label>
        <input type="number" name="edad" id="edadCalculadaEdit" value="{{ old('edad', $paciente->edad) }}" readonly style="background:var(--panel-2);color:var(--txt-soft);">
      </div>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var fi = document.getElementById('fechaNacimientoEdit');
          var fe = document.getElementById('edadCalculadaEdit');
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
        <input type="number" step="0.01" name="peso" value="{{ old('peso', $paciente->peso) }}">
      </div>
      <div class="form-group">
        <label>Altura</label>
        <input type="number" step="0.01" name="altura" value="{{ old('altura', $paciente->altura) }}">
      </div>

      <div class="form-group">
        <label>Sexo</label>
        <select name="sexo">
          <option value="">Selecciona sexo</option>
          <option value="femenino" {{ old('sexo', $paciente->sexo) == 'femenino' ? 'selected' : '' }}>Femenino</option>
          <option value="masculino" {{ old('sexo', $paciente->sexo) == 'masculino' ? 'selected' : '' }}>Masculino</option>
          <option value="otro" {{ old('sexo', $paciente->sexo) == 'otro' ? 'selected' : '' }}>Otro</option>
        </select>
      </div>
      <div class="form-group span-2">
        <label>Dirección</label>
        <input type="text" name="direccion" value="{{ old('direccion', $paciente->direccion) }}">
      </div>

      <div class="form-group">
        <label>Teléfono</label>
        <input type="tel" name="telefono" value="{{ old('telefono', $paciente->telefono) }}">
      </div>
      <div class="form-group span-3">
        <label>e-mail</label>
        <input type="email" name="email" value="{{ old('email', $paciente->email) }}">
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
            <select id="medicoSelectMed" name="medico">
              <option value="dr-victor">Dr. Victor</option>
              <option value="dr-ricardo">Dr. Ricardo</option>
            </select>
            <button type="button" class="btn-add-procedimiento" onclick="addMedicoMed()" title="Agregar médico">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:18px;">
          <label>Procedimiento</label>
          <div class="select-with-add">
          <select id="procedimientoSelect" name="procedimiento">
            <option value="colonoscopia">Colonoscopia</option>
            <option value="panendoscopia">Panendoscopia</option>
            <option value="endoscopia" selected>Endoscopia diagnóstica</option>
            <option value="gastroscopia">Gastroscopia</option>
          </select>
            <button type="button" class="btn-add-procedimiento" onclick="addNuevoProcedimiento()" title="Agregar procedimiento">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:18px;">
          <label>Anestesiólogo</label>
          <div class="select-with-add">
            <select id="anestesiologoSelect" name="anestesiologo">
              <option value="dr-victor">Dr. Victor</option>
              <option value="dr-ricardo">Dr. Ricardo</option>
            </select>
            <button type="button" class="btn-add-procedimiento" onclick="addAnestesiologo()" title="Agregar anestesiólogo">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:18px;">
          <label>Referido por</label>
          <div class="select-with-add">
            <select id="referidoSelectMed" name="referido_por">
              <option value="externo">Externo</option>
              <option value="dr-victor">Dr. Victor</option>
              <option value="dr-ricardo">Dr. Ricardo</option>
            </select>
            <button type="button" class="btn-add-procedimiento" onclick="addReferidoMed()" title="Agregar referido">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>
        <div class="form-group">
          <label>Equipo utilizado</label>
          <div class="select-with-add">
            <select id="equipoSelect" name="equipo_utilizado">
              <option value="endoscopio-olympus">Endoscopio Olympus</option>
              <option value="endoscopio-fujifilm">Endoscopio Fujifilm</option>
              <option value="endoscopio-pentax">Endoscopio Pentax</option>
              <option value="torre-endoscopia">Torre de endoscopia</option>
            </select>
            <button type="button" class="btn-add-procedimiento" onclick="addEquipo()" title="Agregar equipo">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>
      </div>
      {{-- Columna derecha: Diagnóstico --}}
      <div style="flex:1;display:flex;flex-direction:column;">
        <div class="form-group" style="flex:1;">
          <label>Diagnóstico Preliminar</label>
          <textarea name="diagnostico_preliminar" placeholder="Define lo que podría tener" style="min-height:220px;width:100%;">{{ old('diagnostico_preliminar', $paciente->diagnostico_preliminar) }}</textarea>
        </div>
      </div>
    </div>

    {{-- Sección Estudios del paciente --}}
    <hr style="border:none;border-top:1px solid var(--stroke);margin:28px 0;">
    
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
      <div>
        <h2 class="section-title" style="margin-bottom:4px;">Estudios del paciente</h2>
        <p style="color:var(--txt-soft);font-size:13px;margin:0;">Gestiona los estudios, imágenes, videos y reportes del paciente.</p>
      </div>
    </div>

@php
  $tieneEstudios = $paciente->estudios()->count() > 0;
  $tieneArchivos = \App\Models\EstudioArchivo::where('paciente_id', $paciente->id)->count() > 0;
  $tieneReportes = \App\Models\Reporte::whereHas('estudio', fn ($q) => $q->where('paciente_id', $paciente->id))->count() > 0;
  $puedeVerGaleria = $tieneEstudios || $tieneArchivos;
  $puedeVerReportes = $tieneEstudios || $tieneReportes;
@endphp

    <div style="display:flex;gap:12px;margin-bottom:28px;flex-wrap:wrap;">
      <a href="{{ $puedeVerGaleria ? route('galeria.paciente', $paciente->id) : '#' }}"
         class="btn-outline {{ $puedeVerGaleria ? 'btn-galeria' : 'btn-disabled' }}"
         style="padding:12px 24px;font-size:14px;border:2px solid {{ $puedeVerGaleria ? '#f59e0b' : '#555' }};color:{{ $puedeVerGaleria ? '#f59e0b' : '#777' }};background:transparent;border-radius:var(--r-md);cursor:{{ $puedeVerGaleria ? 'pointer' : 'not-allowed' }};transition:all 150ms ease;display:inline-flex;align-items:center;text-decoration:none;"
         {{ $puedeVerGaleria ? '' : 'onclick="return false;"' }}>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;"><rect x="3" y="4" width="18" height="18" rx="2"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="4" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="20"/><line x1="4" y1="12" x2="2" y2="12"/><line x1="22" y1="12" x2="20" y2="12"/></svg>
        Editar galería
      </a>
      <a href="{{ $puedeVerReportes ? route('nuevo-estudio', ['paciente' => $paciente->id]) : '#' }}"
         class="btn-outline {{ $puedeVerReportes ? 'btn-reportes' : 'btn-disabled' }}"
         style="padding:12px 24px;font-size:14px;border:1px solid {{ $puedeVerReportes ? 'var(--cyan)' : '#555' }};color:{{ $puedeVerReportes ? 'var(--cyan)' : '#777' }};background:transparent;border-radius:var(--r-md);cursor:{{ $puedeVerReportes ? 'pointer' : 'not-allowed' }};transition:all 150ms ease;display:inline-flex;align-items:center;text-decoration:none;"
         {{ $puedeVerReportes ? '' : 'onclick="return false;"' }}>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Editar reportes
      </a>
    </div>

    {{-- Botón guardar --}}
    <div style="display:flex;justify-content:flex-end;margin-top:28px;">
      <button type="submit" class="btn-save" id="btnGuardarInfo" style="background:var(--green);color:#fff;border-color:var(--green);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Guardar cambios
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
      <button class="btn-toast-ver" onclick="verDetallesCitaEdit()">Ver detalles</button>
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
  <div class="modal-cita-overlay" id="modalCitaEdit">
    <div class="modal-cita">
      <h3>Agendar cita</h3>
      <p>Selecciona la fecha y hora para la cita del paciente</p>
      <div class="form-group">
        <label>Fecha de la cita</label>
        <input type="date" id="citaFechaEdit" style="width:100%;">
      </div>
      <div class="form-group">
        <label>Hora</label>
        <input type="time" id="citaHoraEdit" style="width:100%;">
      </div>
      <div class="form-group">
        <label>Motivo</label>
        <input type="text" id="citaMotivoEdit" placeholder="Consulta, seguimiento..." style="width:100%;">
      </div>
      <div class="modal-cita-footer">
        <button class="btn-cita-cancel" onclick="document.getElementById('modalCitaEdit').classList.remove('active')">Cancelar</button>
        <button class="btn-cita-confirm" onclick="confirmarCitaEdit()">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          Confirmar cita
        </button>
      </div>
    </div>
  </div>

  {{-- Modal éxito --}}
  <div class="modal-success-overlay" id="modalSuccessEdit">
    <div class="modal-success">
      <div class="modal-success-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h2>¡Datos Guardados!</h2>
      <p>La información del paciente ha sido actualizada correctamente.</p>
      <button class="btn-aceptar" onclick="window.location.href='{{ route('pacientes.index') }}'">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

  // ===== MINI MODAL AGREGAR OPCIÓN =====
  (function(){
    var _selId = null;
    var html = '<div id="_mm" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:99999;align-items:center;justify-content:center;">'
      +'<div style="background:#1a2035;border:1px solid #2e3d6b;border-radius:14px;padding:28px 24px 20px;width:340px;max-width:92vw;box-shadow:0 24px 64px rgba(0,0,0,.6);">'
      +'<h4 id="_mmT" style="margin:0 0 4px;font-size:15px;font-weight:700;color:#e0e6f0;">Agregar</h4>'
      +'<p id="_mmD" style="margin:0 0 14px;font-size:12px;color:#8fa3cf;">Escribe el nombre</p>'
      +'<input id="_mmI" type="text" placeholder="Nombre..." autocomplete="off" style="display:block;width:100%;box-sizing:border-box;margin-bottom:16px;padding:10px 12px;border-radius:8px;border:1px solid #3d4f7a;background:#252b40;color:#e0e6f0;font-size:14px;">'
      +'<div style="display:flex;justify-content:flex-end;gap:8px;">'
      +'<button id="_mmC" type="button" style="padding:8px 18px;border-radius:8px;border:1px solid #2e3d6b;background:transparent;color:#8fa3cf;font-size:13px;cursor:pointer;">Cancelar</button>'
      +'<button id="_mmO" type="button" style="padding:8px 18px;border-radius:8px;border:none;background:#2e7bf6;color:#fff;font-size:13px;font-weight:600;cursor:pointer;">Agregar</button>'
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
      var o = document.createElement('option');
      o.value = n.toLowerCase().replace(/\s+/g,'-');
      o.textContent = n;
      o.selected = true;
      s.insertBefore(o, s.lastElementChild);
      window.cerrarMiniModal();
    };
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

  // ===== GUARDAR INFORMACIÓN ASÍNCRONA =====
  const pacienteForm = document.getElementById('pacienteForm');
  const btnGuardarInfo = document.getElementById('btnGuardarInfo');
  const modalSuccessEdit = document.getElementById('modalSuccessEdit');

  if (pacienteForm) {
    pacienteForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      if (btnGuardarInfo) {
        btnGuardarInfo.disabled = true;
        btnGuardarInfo.style.opacity = '.7';
      }

      try {
        const response = await fetch(pacienteForm.action, {
          method: 'POST',
          body: new FormData(pacienteForm),
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });

        if (!response.ok && response.status !== 302) {
          const data = await response.json().catch(() => null);
          const message = data?.message || 'No se pudieron guardar los cambios. Revisa los campos.';
          alert(message);
          return;
        }

        if (modalSuccessEdit) {
          modalSuccessEdit.classList.add('active');
        } else {
          window.location.href = '{{ route('pacientes.index') }}';
        }
      } catch (error) {
        alert('Ocurrió un error al actualizar el paciente.');
      } finally {
        if (btnGuardarInfo) {
          btnGuardarInfo.disabled = false;
          btnGuardarInfo.style.opacity = '1';
        }
      }
    });
  }

  // ===== FOTO DEL PACIENTE: GALERÍA Y CÁMARA REAL =====
  const modalFoto = document.getElementById('modalFoto');
  const btnCancelarFoto = document.getElementById('btnCancelarFoto');
  const btnUsarFoto = document.getElementById('btnUsarFoto');
  const inputFileFoto = document.getElementById('inputFileFoto');
  const patientPhoto = document.getElementById('patientPhoto');
  const patientPhotoPlaceholder = document.getElementById('patientPhotoPlaceholder');
  const cameraFrame = document.querySelector('.camera-frame');
  const avatarPreview = document.querySelector('.avatar-preview');

  let cameraStream = null;
  let currentPhotoData = null;

  function prepararVideoCamara() {
    if (!cameraFrame) return null;

    let video = document.getElementById('cameraVideoPaciente');
    if (!video) {
      video = document.createElement('video');
      video.id = 'cameraVideoPaciente';
      video.autoplay = true;
      video.playsInline = true;
      video.muted = true;
      cameraFrame.appendChild(video);
    }

    let canvas = document.getElementById('cameraCanvasPaciente');
    if (!canvas) {
      canvas = document.createElement('canvas');
      canvas.id = 'cameraCanvasPaciente';
      cameraFrame.appendChild(canvas);
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
      cameraStream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'user' },
        audio: false
      });

      media.video.srcObject = cameraStream;
      cameraFrame.classList.add('camera-active');
      currentPhotoData = null;
    } catch (error) {
      alert('No se pudo abrir la cámara. Revisa permisos del navegador o usa Subir imagen.');
    }
  }

  function detenerCamaraPaciente() {
    if (cameraStream) {
      cameraStream.getTracks().forEach(track => track.stop());
      cameraStream = null;
    }

    if (cameraFrame) {
      cameraFrame.classList.remove('camera-active');
    }
  }

  function asignarArchivoDesdeBlob(blob) {
    if (!inputFileFoto) return;

    const file = new File([blob], 'foto-paciente.png', { type: 'image/png' });
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    inputFileFoto.files = dataTransfer.files;
  }

  function mostrarFotoSeleccionada(dataUrl) {
    if (patientPhoto && patientPhotoPlaceholder) {
      patientPhoto.src = dataUrl;
      patientPhoto.style.display = 'block';
      patientPhotoPlaceholder.style.display = 'none';
    }

    if (avatarPreview) {
      avatarPreview.style.backgroundImage = `url(${dataUrl})`;
      avatarPreview.style.backgroundSize = 'cover';
      avatarPreview.style.backgroundPosition = 'center';
      avatarPreview.textContent = '';
    }
  }

  function capturarFotoDesdeCamara() {
    const media = prepararVideoCamara();
    if (!media || !cameraStream) return false;

    const video = media.video;
    const canvas = media.canvas;

    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;

    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    currentPhotoData = canvas.toDataURL('image/png');

    canvas.toBlob(function(blob) {
      if (blob) asignarArchivoDesdeBlob(blob);
    }, 'image/png');

    mostrarFotoSeleccionada(currentPhotoData);
    detenerCamaraPaciente();

    return true;
  }

  // Función global para abrir modal desde botones inline
  window.abrirModalFoto = function() {
    if (!modalFoto) return;
    modalFoto.classList.add('active');

    if (avatarPreview && !currentPhotoData) {
      avatarPreview.textContent = '👤';
      avatarPreview.style.backgroundImage = '';
    }
  };

  if (btnCancelarFoto && modalFoto) {
    btnCancelarFoto.addEventListener('click', function() {
      detenerCamaraPaciente();
      modalFoto.classList.remove('active');
    });
  }

  if (modalFoto) {
    modalFoto.addEventListener('click', function(e) {
      if (e.target === modalFoto) {
        detenerCamaraPaciente();
        modalFoto.classList.remove('active');
      }
    });
  }

  const sourceOptions = document.querySelectorAll('.source-option');
  sourceOptions.forEach(function(option, index) {
    option.addEventListener('click', function() {
      sourceOptions.forEach(o => o.classList.remove('active'));
      option.classList.add('active');

      if (index === 0) {
        iniciarCamaraPaciente();
      }

      if (index === 1 && inputFileFoto) {
        detenerCamaraPaciente();
        inputFileFoto.click();
      }
    });
  });

  const camBtns = document.querySelectorAll('.cam-btn');
  camBtns.forEach(function(btn, index) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();

      camBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      if (index === 0 && inputFileFoto) {
        detenerCamaraPaciente();
        inputFileFoto.click();
      }

      if (index === 1) {
        iniciarCamaraPaciente();
      }

      if (index === 2) {
        iniciarCamaraPaciente();
      }
    });
  });

  if (inputFileFoto) {
    inputFileFoto.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;

      detenerCamaraPaciente();

      const reader = new FileReader();
      reader.onload = function(event) {
        currentPhotoData = event.target.result;
        mostrarFotoSeleccionada(currentPhotoData);
      };
      reader.readAsDataURL(file);
    });
  }

  if (btnUsarFoto && modalFoto) {
    btnUsarFoto.addEventListener('click', function() {
      if (cameraStream) {
        const capturada = capturarFotoDesdeCamara();
        if (!capturada) {
          alert('No se pudo capturar la foto.');
          return;
        }
      }

      if (!currentPhotoData && inputFileFoto && inputFileFoto.files.length > 0) {
        modalFoto.classList.remove('active');
        return;
      }

      if (!currentPhotoData && (!inputFileFoto || inputFileFoto.files.length === 0)) {
        alert('Selecciona una imagen o toma una foto primero.');
        return;
      }

      detenerCamaraPaciente();
      modalFoto.classList.remove('active');
    });
  }

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

  // ===== PROCEDIMIENTO / MÉDICO / ANESTESIÓLOGO / REFERIDO / EQUIPO =====
  window.addNuevoProcedimiento = function() { window.abrirMiniModal('procedimientoSelect','Agregar procedimiento','Escribe el nombre del procedimiento'); };
  window.addMedicoMed         = function() { window.abrirMiniModal('medicoSelectMed','Agregar médico','Escribe el nombre del médico'); };
  window.addAnestesiologo     = function() { window.abrirMiniModal('anestesiologoSelect','Agregar anestesiólogo','Escribe el nombre del anestesiólogo'); };
  window.addReferidoMed       = function() { window.abrirMiniModal('referidoSelectMed','Agregar referido','Escribe el nombre del referido'); };
  window.addEquipo            = function() { window.abrirMiniModal('equipoSelect','Agregar equipo','Escribe el nombre del equipo'); };

  // ===== AGENDAR CITA =====
  var _citaData = {};
  window.confirmarCitaEdit = function() {
    var fecha  = document.getElementById('citaFechaEdit').value;
    var hora   = document.getElementById('citaHoraEdit').value;
    var motivo = document.getElementById('citaMotivoEdit').value.trim();
    if (!fecha || !hora) {
      alert('Por favor selecciona fecha y hora para la cita.');
      return;
    }
    var partes = fecha.split('-');
    var fechaLeg = partes[2]+'/'+partes[1]+'/'+partes[0];
    _citaData = { fecha: fechaLeg, hora: hora, motivo: motivo };
    document.getElementById('modalCitaEdit').classList.remove('active');
    document.getElementById('citaToastBody').textContent =
      fechaLeg + ' — ' + hora + (motivo ? '  |  ' + motivo : '');
    var toast = document.getElementById('citaToast');
    toast.classList.add('active');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(function(){ toast.classList.remove('active'); }, 8000);
  };
  window.verDetallesCitaEdit = function() {
    document.getElementById('citaToast').classList.remove('active');
    var d = _citaData;
    document.getElementById('detalleCitaTexto').innerHTML =
      '<strong>Fecha:</strong> ' + d.fecha + '<br>' +
      '<strong>Hora:</strong> ' + d.hora + (d.motivo ? '<br><strong>Motivo:</strong> ' + d.motivo : '');
    document.getElementById('modalDetalleCita').classList.add('active');
  };

  // ===== EDAD AUTOMÁTICA DESDE FECHA DE NACIMIENTO =====
  const fechaNacimientoInput = document.getElementById('fechaNacimientoEdit');
  const edadInput = document.getElementById('edadCalculadaEdit');

  function calcularEdad(fechaNacimiento) {
    if (!fechaNacimiento) return '';

    const nacimiento = new Date(fechaNacimiento + 'T00:00:00');
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
    if (!fechaNacimientoInput || !edadInput) return;

    edadInput.value = calcularEdad(fechaNacimientoInput.value);
  }

  if (fechaNacimientoInput) {
    fechaNacimientoInput.addEventListener('change', actualizarEdad);
    fechaNacimientoInput.addEventListener('input', actualizarEdad);
    actualizarEdad();
  }
});
</script>
@endpush

@endsection
