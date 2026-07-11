@extends('layouts.app')

@section('title', 'Pacientes')
@section('active', 'pacientes')
@section('header-title', 'Pacientes')
@section('header-sub')
  Gestiona y consulta la información de tus pacientes
@endsection
  
@push('styles')
<style>
/* ============ ESTILOS DE PACIENTES ============ */

/* Toolbar con buscador y botones */
.toolbar{
  display:flex;
  align-items:center;
  gap:14px;
  margin-bottom:22px;
  flex-wrap:wrap;
}
.search-box{
  display:flex;
  align-items:center;
  gap:10px;
  flex:1;
  min-width:280px;
  max-width:420px;
  padding:12px 16px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
}
.search-box svg{
  width:18px;
  height:18px;
  color:var(--txt-soft);
  flex:none;
}
.search-box input{
  flex:1;
  background:transparent;
  border:0;
  font:inherit;
  font-size:14px;
  color:var(--txt);
  outline:none;
}
.search-box input::placeholder{color:var(--txt-soft)}

.btn-filter, .btn-new{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:12px 18px;
  border-radius:var(--r-md);
  font-size:14px;
  font-weight:600;
  transition:background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-filter{
  border:1px solid var(--stroke);
  background:var(--panel-2);
  color:var(--txt);
}
.btn-new{
  border:1px solid var(--cyan);
  background:rgba(56,199,244,.12);
  color:var(--cyan);
}
.btn-filter:active, .btn-new:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .btn-filter:hover{background:var(--card)}
  .btn-new:hover{background:rgba(56,199,244,.2)}
}
.btn-filter.active{
  border-color:var(--cyan);
  color:var(--cyan);
  background:rgba(56,199,244,.1);
}

/* ============ PANEL FILTROS ============ */
.filter-overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.45);
  z-index:200;
  opacity:0;
  visibility:hidden;
  transition:opacity 250ms ease, visibility 250ms ease;
}
.filter-overlay.open{opacity:1;visibility:visible}
.filter-panel{
  position:fixed;
  top:0;right:0;
  width:320px;
  max-width:94vw;
  height:100vh;
  background:linear-gradient(180deg,var(--card) 0%,var(--panel-2) 100%);
  border-left:1px solid var(--stroke-strong);
  z-index:201;
  display:flex;
  flex-direction:column;
  transform:translateX(100%);
  transition:transform 300ms var(--ease-out);
  overflow:hidden;
}
.filter-panel.open{transform:translateX(0)}
.filter-panel-head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:22px 20px 16px;
  border-bottom:1px solid var(--stroke);
  flex:none;
}
.filter-panel-head h2{
  font-family:'Sora',sans-serif;
  font-size:18px;
  font-weight:700;
}
.filter-close{
  width:32px;height:32px;
  border-radius:50%;
  border:1px solid var(--stroke);
  display:grid;place-items:center;
  color:var(--txt-soft);
  transition:all 150ms ease;
}
.filter-close:hover{border-color:var(--stroke-strong);color:var(--txt)}
.filter-tabs-row{
  display:flex;
  gap:0;
  border-bottom:1px solid var(--stroke);
  flex:none;
}
.filter-tab{
  flex:1;
  padding:12px 0;
  font-size:13.5px;
  font-weight:600;
  color:var(--txt-soft);
  text-align:center;
  border-bottom:2px solid transparent;
  transition:color 150ms ease, border-color 150ms ease;
}
.filter-tab.active{color:var(--cyan);border-bottom-color:var(--cyan)}
.filter-body{
  flex:1;
  overflow-y:auto;
  padding:20px;
  display:flex;
  flex-direction:column;
  gap:16px;
  scrollbar-width:thin;
  scrollbar-color:var(--stroke) transparent;
}
.filter-group{
  display:flex;
  flex-direction:column;
  gap:6px;
}
.filter-group label{
  font-size:12.5px;
  font-weight:600;
  color:var(--txt-soft);
}
.filter-input{
  width:100%;
  padding:10px 14px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
  color:var(--txt);
  font:inherit;
  font-size:14px;
  outline:none;
  transition:border-color 150ms ease;
}
.filter-input:focus{border-color:var(--cyan)}
.filter-input::placeholder{color:var(--txt-soft)}
.filter-select{
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat:no-repeat;
  background-position:right 12px center;
  padding-right:36px;
  cursor:pointer;
}
.filter-date-btns{
  display:flex;
  gap:8px;
}
.filter-date-btn{
  flex:1;
  padding:9px 0;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
  font-size:13px;
  font-weight:600;
  color:var(--txt-soft);
  transition:all 150ms ease;
  cursor:pointer;
}
.filter-date-btn.active{
  background:var(--cyan);
  border-color:var(--cyan);
  color:#000;
}
.filter-age-row{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:10px;
}
.filter-age-input{
  position:relative;
}
.filter-age-input input{
  width:100%;
  padding:10px 14px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
  color:var(--txt);
  font:inherit;
  font-size:14px;
  outline:none;
  transition:border-color 150ms ease;
}
.filter-age-input input:focus{border-color:var(--cyan)}
.filter-age-label{
  position:absolute;
  left:10px;top:50%;
  transform:translateY(-50%);
  font-size:11px;
  font-weight:700;
  color:var(--txt-soft);
  pointer-events:none;
}
.filter-age-input input{padding-left:52px}
.filter-footer{
  padding:16px 20px;
  border-top:1px solid var(--stroke);
  display:flex;
  gap:10px;
  flex:none;
}
.filter-btn-apply{
  flex:1;
  padding:12px 0;
  border-radius:var(--r-md);
  background:var(--blue);
  color:#fff;
  font-size:14px;
  font-weight:700;
  border:none;
  cursor:pointer;
  transition:background-color 150ms ease, transform 160ms var(--ease-out);
}
.filter-btn-apply:hover{background:#1c6ae0}
.filter-btn-apply:active{transform:scale(.97)}
.filter-btn-clear{
  padding:12px 16px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:transparent;
  color:var(--txt-soft);
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  transition:all 150ms ease;
}
.filter-btn-clear:hover{color:var(--txt);border-color:var(--txt-soft)}
.filter-section-title{
  font-size:11px;
  font-weight:700;
  letter-spacing:.08em;
  text-transform:uppercase;
  color:var(--cyan);
  margin-bottom:4px;
}

/* Tabla de pacientes */
.patients-card{
  background:linear-gradient(180deg,var(--card),var(--panel-2));
  border:1px solid var(--stroke);
  border-radius:var(--r-lg);
  overflow:hidden;
  height:fit-content;
}
/* Mientras hay un menú abierto, no recortar para que el desplegable se vea completo */
.patients-card.menu-open{overflow:visible}
.content-with-panel.menu-open{overflow:visible}
.table-header{
  display:grid;
  grid-template-columns:2fr 1fr 1fr 1.5fr 1fr 100px;
  gap:12px;
  padding:16px 20px;
  background:var(--panel-2);
  border-bottom:1px solid var(--stroke);
}
.table-header span{
  font-size:12px;
  font-weight:600;
  color:var(--txt-soft);
  text-transform:uppercase;
  letter-spacing:0.05em;
  display:flex;
  align-items:center;
  gap:6px;
}
.table-header span svg{
  width:14px;
  height:14px;
}

.patient-row{
  display:grid;
  grid-template-columns:2fr 1fr 1fr 1.5fr 1fr 100px;
  gap:12px;
  padding:16px 20px;
  align-items:center;
  border-bottom:1px solid rgba(110,160,255,.08);
  transition:background-color 150ms ease;
  cursor:pointer;
}
.patient-row:last-child{border-bottom:0}
.patient-row:hover{background:rgba(110,160,255,.08)}
.patient-row.active{
  background:rgba(46,123,246,.15);
  border-left:3px solid var(--blue);
}

/* Celda paciente */
.patient-info{
  display:flex;
  align-items:center;
  gap:12px;
}
.patient-avatar{
  width:36px;
  height:36px;
  border-radius:50%;
  background:rgba(46,123,246,.2);
  border:1px solid var(--stroke-strong);
  display:grid;
  place-items:center;
  font-size:12px;
  font-weight:700;
  color:var(--cyan);
  flex:none;
}
.patient-name{
  font-weight:600;
  font-size:14px;
}
.patient-meta{
  font-size:12px;
  color:var(--txt-soft);
  margin-top:2px;
}

/* Celdas comunes */
.cell{
  font-size:13.5px;
  color:var(--txt);
}
.cell-muted{
  font-size:13px;
  color:var(--txt-soft);
}
.cell-study{
  display:flex;
  flex-direction:column;
  gap:2px;
}
.cell-study .date{
  font-size:13.5px;
  font-weight:500;
  color:var(--txt);
}
.cell-study .type{
  font-size:12px;
  color:var(--txt-soft);
}

/* Estados */
.status{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 12px;
  border-radius:8px;
  font-size:12px;
  font-weight:600;
}
.status::before{
  content:'';
  width:6px;
  height:6px;
  border-radius:50%;
}
.status.completed{
  background:rgba(61,220,151,.12);
  color:var(--green);
  border:1px solid rgba(61,220,151,.4);
}
.status.completed::before{background:var(--green)}
.status.waiting{
  background:rgba(245,158,45,.12);
  color:var(--orange);
  border:1px solid rgba(245,158,45,.4);
}
.status.waiting::before{background:var(--orange)}
.status.cancelled{
  background:rgba(255,90,110,.12);
  color:var(--red);
  border:1px solid rgba(255,90,110,.4);
}
.status.cancelled::before{background:var(--red)}

/* Acciones */
.actions{
  display:flex;
  align-items:center;
  gap:8px;
}
.btn-action{
  width:32px;
  height:32px;
  border-radius:8px;
  display:grid;
  place-items:center;
  border:1px solid var(--stroke);
  background:transparent;
  color:var(--txt-soft);
  transition:all 150ms ease;
}
.btn-action svg{width:16px;height:16px}
.btn-action:hover{
  border-color:var(--stroke-strong);
  color:var(--txt);
  background:rgba(110,160,255,.1);
}
.btn-more{
  width:32px;
  height:32px;
  border-radius:8px;
  display:grid;
  place-items:center;
  border:0;
  background:transparent;
  color:var(--txt-soft);
  font-size:16px;
  font-weight:700;
  cursor:pointer;
  transition:all 150ms ease;
  margin-left:8px;
}
.btn-more:hover{color:var(--txt)}

/* Menú desplegable de acciones */
.actions-dropdown{
  position:absolute;
  right:0;
  top:100%;
  margin-top:8px;
  min-width:260px;
  background:linear-gradient(180deg,var(--card),var(--panel-2));
  border:1px solid var(--stroke-strong);
  border-radius:var(--r-lg);
  padding:10px;
  z-index:100;
  box-shadow:0 10px 40px rgba(0,0,0,.4);
  opacity:0;
  visibility:hidden;
  transform:translateY(-10px);
  transition:all 200ms var(--ease-out);
}
.actions-dropdown.active{
  opacity:1;
  visibility:visible;
  transform:translateY(0);
}
.actions-dropdown a{
  display:flex;
  align-items:center;
  gap:12px;
  padding:12px 14px;
  border-radius:10px;
  font-size:14px;
  color:var(--txt);
  transition:all 150ms ease;
}
.actions-dropdown a:hover{
  background:rgba(110,160,255,.1);
}
.actions-dropdown a svg{
  width:18px;
  height:18px;
  flex:none;
}
.actions-dropdown .danger{
  color:var(--red);
}
.actions-dropdown .danger:hover{
  background:rgba(255,90,110,.1);
}
.actions-wrapper{
  position:relative;
}

/* Footer tabla */
.table-footer{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:14px 20px;
  border-top:1px solid var(--stroke);
  font-size:13px;
  color:var(--txt-soft);
  background:var(--card);
}

/* ============ PANEL LATERAL DETALLE PACIENTE ============ */
.content-with-panel{
  display:grid;
  grid-template-columns:1fr 0;
  gap:0;
  transition:grid-template-columns 300ms var(--ease-out);
  overflow:hidden;
  align-items:stretch;
}
.content-with-panel.panel-open{
  grid-template-columns:1fr 420px;
  gap:20px;
}

.patient-detail-panel{
  background:linear-gradient(180deg,var(--card) 0%,var(--panel-2) 100%);
  border:1px solid var(--stroke-strong);
  border-radius:var(--r-lg);
  overflow-y:auto;
  padding:24px;
  opacity:0;
  visibility:hidden;
  transform:translateX(20px);
  transition:all 300ms var(--ease-out);
  height:auto;
  align-self:stretch;
}
/* Ocultar columna ESTADO cuando el panel está abierto */
.content-with-panel.panel-open .col-status{
  display:none;
}
.content-with-panel.panel-open .table-header,
.content-with-panel.panel-open .patient-row{
  grid-template-columns:2fr 1fr 1fr 1.5fr 100px;
}
.content-with-panel.panel-open .patient-detail-panel{
  opacity:1;
  visibility:visible;
  transform:translateX(0);
}

/* Header del panel */
.panel-header{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  margin-bottom:20px;
}
.patient-identity{
  display:flex;
  align-items:center;
  gap:16px;
}
.panel-avatar{
  width:72px;
  height:72px;
  border-radius:50%;
  background:linear-gradient(135deg,var(--blue),var(--cyan));
  display:grid;
  place-items:center;
  font-family:'Sora',sans-serif;
  font-size:24px;
  font-weight:700;
  color:#fff;
  flex:none;
}
.patient-title h3{
  font-family:'Sora',sans-serif;
  font-size:18px;
  font-weight:700;
  margin-bottom:4px;
}
.patient-title .folio{
  font-size:13px;
  color:var(--txt-soft);
}
.btn-close-panel{
  width:32px;
  height:32px;
  border-radius:50%;
  border:1px solid var(--stroke);
  background:transparent;
  color:var(--txt-soft);
  display:grid;
  place-items:center;
  cursor:pointer;
  transition:all 150ms ease;
}
.btn-close-panel:hover{
  border-color:var(--red);
  color:var(--red);
  background:rgba(255,90,110,.1);
}

/* Meta info rápida */
.quick-meta{
  display:flex;
  gap:20px;
  margin-bottom:20px;
  padding-bottom:16px;
  border-bottom:1px solid var(--stroke);
}
.meta-item{
  display:flex;
  align-items:center;
  gap:8px;
  font-size:13px;
  color:var(--txt-soft);
}
.meta-item svg{
  width:16px;
  height:16px;
  color:var(--cyan);
}

/* Tabs */
.panel-tabs{
  display:flex;
  gap:0;
  margin-bottom:20px;
  border-bottom:1px solid var(--stroke);
}
.tab-btn{
  padding:10px 16px;
  border-radius:0;
  border:0;
  border-bottom:2px solid transparent;
  margin-bottom:-1px;
  background:transparent;
  color:var(--txt-soft);
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  transition:all 150ms ease;
}
.tab-btn:hover{
  color:var(--txt);
  background:transparent;
}
.tab-btn.active{
  color:#2B7FFF;
  background:transparent;
  border-bottom:2px solid #2B7FFF;
  font-weight:700;
}

/* Sección info general */
.info-section h4{
  font-size:14px;
  font-weight:600;
  margin-bottom:16px;
  color:var(--txt);
}
.info-list{
  display:flex;
  flex-direction:column;
  gap:10px;
}
.info-item{
  display:flex;
  flex-direction:column;
  gap:6px;
  padding:12px 14px;
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
}
.info-label{
  display:flex;
  align-items:center;
  gap:10px;
  font-size:12px;
  text-transform:uppercase;
  letter-spacing:.03em;
}
.info-label svg{
  width:16px;
  height:16px;
  color:var(--txt-soft);
}
.info-label span{
  color:var(--txt-soft);
}
.info-value{
  font-size:14px;
  color:var(--txt);
  font-weight:500;
  padding-left:26px;
}

/* Layout info */
.info-holo-container{
  display:block;
  margin-bottom:20px;
}

/* Cards inferiores */
.panel-cards{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:12px;
  margin-bottom:16px;
}
.mini-card{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:16px;
}
.mini-card-header{
  display:flex;
  align-items:center;
  gap:8px;
  font-size:12px;
  color:var(--txt-soft);
  margin-bottom:8px;
}
.mini-card-header svg{
  width:16px;
  height:16px;
}
.mini-card-value{
  font-size:14px;
  font-weight:600;
  color:var(--txt);
}
.mini-card-sub{
  font-size:12px;
  color:var(--txt-soft);
  margin-top:4px;
}
.status-badge{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 12px;
  border-radius:8px;
  font-size:12px;
  font-weight:600;
  background:rgba(61,220,151,.12);
  color:var(--green);
  border:1px solid rgba(61,220,151,.4);
}
.status-badge::before{
  content:'';
  width:6px;
  height:6px;
  border-radius:50%;
  background:var(--green);
}

/* Notas rápidas */
.notes-section{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:16px;
}
.notes-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin-bottom:12px;
}
.notes-header h4{
  font-size:14px;
  font-weight:600;
}
.notes-header button{
  font-size:12px;
  color:var(--cyan);
  background:transparent;
  border:0;
  cursor:pointer;
}
.notes-text{
  font-size:13px;
  color:var(--txt-soft);
  line-height:1.5;
}

/* Sección de Historial */
.tab-content{
  display:none;
}
.tab-content.active{
  display:block;
}
.historial-section h4{
  font-size:14px;
  font-weight:600;
  margin-bottom:16px;
  color:var(--txt);
}
.historial-list{
  display:flex;
  flex-direction:column;
  gap:12px;
  margin-bottom:20px;
}
.historial-item{
  display:flex;
  align-items:flex-start;
  gap:12px;
  padding:14px;
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  transition:all 150ms ease;
}
.historial-item:hover{
  border-color:var(--stroke-strong);
  background:var(--card);
}
.historial-icon{
  width:36px;
  height:36px;
  border-radius:50%;
  background:rgba(56,199,244,.15);
  display:grid;
  place-items:center;
  flex:none;
}
.historial-icon svg{
  width:18px;
  height:18px;
  color:var(--cyan);
}
.historial-icon.green{
  background:rgba(61,220,151,.15);
}
.historial-icon.green svg{
  color:var(--green);
}
.historial-icon.orange{
  background:rgba(245,158,45,.15);
}
.historial-icon.orange svg{
  color:var(--orange);
}
.historial-icon.purple{
  background:rgba(139,92,246,.15);
}
.historial-icon.purple svg{
  color:#8b5cf6;
}
.historial-info{
  flex:1;
}
.historial-title{
  font-size:13px;
  font-weight:600;
  color:var(--txt);
  margin-bottom:4px;
}
.historial-doctor{
  font-size:12px;
  color:var(--txt-soft);
}
.historial-right{
  display:flex;
  flex-direction:column;
  align-items:flex-end;
  gap:6px;
}
.historial-date{
  font-size:12px;
  color:var(--txt-soft);
}
.status-tag{
  padding:4px 10px;
  border-radius:6px;
  font-size:11px;
  font-weight:600;
}
.status-tag.urgente{
  background:rgba(255,90,110,.15);
  color:var(--red);
}
.status-tag.espera{
  background:rgba(245,158,45,.15);
  color:var(--orange);
}
.status-tag.completado{
  background:rgba(61,220,151,.15);
  color:var(--green);
}
.btn-view-all{
  width:100%;
  padding:12px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:transparent;
  color:var(--cyan);
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  transition:all 150ms ease;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
}
.btn-view-all:hover{
  background:rgba(56,199,244,.1);
  border-color:var(--cyan);
}

/* Filtro de Estado en Header */
.estado-filter-container{
  position:relative;
  display:flex;
  align-items:center;
  gap:6px;
}
.estado-filter-btn{
  background:none;
  border:none;
  color:var(--txt-soft);
  cursor:pointer;
  padding:2px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:4px;
  transition:all 150ms ease;
}
.estado-filter-btn:hover{
  background:rgba(255,255,255,.1);
  color:var(--txt);
}
.estado-filter-btn svg{
  width:14px;
  height:14px;
}
.estado-filter-dropdown{
  position:absolute;
  top:calc(100% + 8px);
  right:0;
  background:var(--panel);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:8px 0;
  min-width:160px;
  box-shadow:0 8px 24px rgba(0,0,0,.3);
  z-index:100;
  display:none;
  opacity:0;
  transform:translateY(-10px);
  transition:opacity 200ms ease, transform 200ms var(--ease-out);
}
.estado-filter-dropdown.active{
  display:block;
  opacity:1;
  transform:translateY(0);
}
.filter-option{
  display:flex;
  align-items:center;
  gap:10px;
  padding:8px 16px;
  cursor:pointer;
  font-size:12px;
  color:var(--txt);
  transition:background 150ms ease;
}
.filter-option:hover{
  background:var(--panel-2);
}
.status-dot{
  width:8px;
  height:8px;
  border-radius:50%;
  flex:none;
}
.status-dot.green{
  background:var(--green);
}
.status-dot.yellow{
  background:var(--orange);
}
.status-dot.red{
  background:var(--red);
}
.status-dot.gray{
  background:var(--txt-soft);
}

/* Ordenar dropdown (Paciente y Último Estudio) */
.ordenar-container{
  position:relative;
  display:flex;
  align-items:center;
  gap:6px;
}
.ordenar-btn{
  background:none;
  border:none;
  color:var(--txt-soft);
  cursor:pointer;
  padding:2px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:4px;
  transition:all 150ms ease;
}
.ordenar-btn:hover{
  background:rgba(255,255,255,.1);
  color:var(--txt);
}
.ordenar-btn svg{
  width:14px;
  height:14px;
}
.ordenar-dropdown{
  position:absolute;
  top:calc(100% + 8px);
  right:0;
  background:var(--panel);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:8px 0;
  min-width:180px;
  box-shadow:0 8px 24px rgba(0,0,0,.3);
  z-index:100;
  display:none;
  opacity:0;
  transform:translateY(-10px);
  transition:opacity 200ms ease, transform 200ms var(--ease-out);
}
.ordenar-dropdown.active{
  display:block;
  opacity:1;
  transform:translateY(0);
}
.ordenar-option{
  padding:8px 16px;
  cursor:pointer;
  font-size:12px;
  color:var(--txt);
  transition:background 150ms ease;
}
.ordenar-option:hover{
  background:var(--panel-2);
}
.ordenar-option.active{
  background:var(--blue);
  color:var(--white);
}

/* Sección de Estudios / Informes */
.estudios-section h4{
  font-size:14px;
  font-weight:600;
  margin-bottom:16px;
  color:var(--txt);
}

/* Stats rápidas */
.informe-stats-row{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:10px;
  margin-bottom:20px;
}
.informe-stat{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:12px 8px;
  text-align:center;
}
.informe-stat-num{
  font-size:22px;
  font-weight:700;
  line-height:1.1;
}
.informe-stat-lbl{
  font-size:10px;
  color:var(--txt-soft);
  margin-top:3px;
}

/* Lista de informes */
.informe-lista-title{
  font-size:12px;
  font-weight:600;
  color:var(--txt-soft);
  text-transform:uppercase;
  letter-spacing:.5px;
  margin-bottom:10px;
}
.informe-lista{
  display:flex;
  flex-direction:column;
  gap:8px;
  margin-bottom:4px;
}
.informe-item{
  display:flex;
  align-items:center;
  gap:10px;
  padding:10px 12px;
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  transition:border-color 150ms;
}
.informe-item:hover{border-color:var(--stroke-strong);}
.informe-item-icon{
  width:32px;
  height:32px;
  border-radius:8px;
  display:grid;
  place-items:center;
  flex:none;
}
.informe-item-info{flex:1;min-width:0;}
.informe-item-title{
  font-size:12px;
  font-weight:600;
  color:var(--txt);
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}
.informe-item-meta{
  font-size:11px;
  color:var(--txt-soft);
  margin-top:2px;
}
.informe-badge{
  font-size:10px;
  font-weight:600;
  padding:3px 8px;
  border-radius:20px;
  white-space:nowrap;
  flex:none;
}
.informe-badge.completado{background:rgba(61,220,151,.15);color:var(--green);}
.informe-badge.pendiente{background:rgba(245,200,45,.15);color:#f5c82d;}
.informe-badge.urgente{background:rgba(255,90,110,.15);color:var(--red);}

.categorias-grid{
  display:grid;
  grid-template-columns:repeat(2,1fr);
  gap:12px;
  margin-bottom:24px;
}
.categoria-card{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:14px;
  display:flex;
  align-items:center;
  gap:12px;
  transition:all 150ms ease;
  cursor:pointer;
}
.categoria-card:hover{
  border-color:var(--stroke-strong);
  background:var(--card);
}
.categoria-icon{
  width:44px;
  height:44px;
  border-radius:50%;
  display:grid;
  place-items:center;
  flex:none;
}
.categoria-icon.blue{
  background:rgba(56,199,244,.15);
}
.categoria-icon.blue svg{
  color:var(--cyan);
}
.categoria-icon.beige{
  background:rgba(210,170,130,.2);
}
.categoria-icon.beige svg{
  color:#d2aa82;
}
.categoria-icon.yellow{
  background:rgba(245,200,45,.15);
}
.categoria-icon.yellow svg{
  color:#f5c82d;
}
.categoria-icon.green{
  background:rgba(61,220,151,.15);
}
.categoria-icon.green svg{
  color:var(--green);
}
.categoria-icon.purple{
  background:rgba(139,92,246,.15);
}
.categoria-icon.purple svg{
  color:#8b5cf6;
}
.categoria-icon.red{
  background:rgba(255,90,110,.15);
}
.categoria-icon.red svg{
  color:var(--red);
}
.categoria-info{
  flex:1;
}
.categoria-title{
  font-size:13px;
  font-weight:600;
  color:var(--txt);
  margin-bottom:2px;
}
.categoria-count{
  font-size:12px;
  color:var(--txt-soft);
}
.ultimo-estudio{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:14px;
  margin-bottom:20px;
}
.ultimo-estudio h4{
  margin-bottom:12px;
}
.estudio-card{
  display:flex;
  gap:14px;
  align-items:center;
  padding:10px 0;
  border-bottom:1px solid var(--stroke);
}
.estudio-card:last-child{
  border-bottom:none;
  padding-bottom:0;
}
.estudio-img{
  width:80px;
  height:60px;
  border-radius:var(--r-sm);
  background:linear-gradient(135deg,#1a2a3a 0%,#0f1a24 100%);
  display:flex;
  align-items:center;
  justify-content:center;
  overflow:hidden;
  flex:none;
}
.estudio-img img{
  width:100%;
  height:100%;
  object-fit:cover;
}
.estudio-img svg{
  width:32px;
  height:32px;
  color:var(--cyan);
  opacity:.7;
}
.estudio-info{
  flex:1;
}
.estudio-title{
  font-size:13px;
  font-weight:600;
  color:var(--txt);
  margin-bottom:4px;
}
.estudio-meta{
  font-size:12px;
  color:var(--txt-soft);
  margin-bottom:2px;
}
.estudio-doctor{
  font-size:12px;
  color:var(--txt-soft);
}
.btn-ver-detalles{
  font-size:12px;
  color:var(--cyan);
  font-weight:600;
  white-space:nowrap;
}

/* Sección de Reportes IA */
.reportes-section h4{
  font-size:14px;
  font-weight:600;
  margin-bottom:16px;
  color:var(--txt);
}
.ia-summary-card{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:16px;
  margin-bottom:20px;
}
.ia-patient-header{
  display:flex;
  align-items:center;
  gap:12px;
  margin-bottom:16px;
}
.ia-patient-avatar{
  width:44px;
  height:44px;
  border-radius:50%;
  background:var(--blue);
  display:grid;
  place-items:center;
  font-size:14px;
  font-weight:700;
  color:#fff;
  flex:none;
}
.ia-patient-info{
  flex:1;
}
.ia-patient-label{
  font-size:11px;
  color:var(--txt-soft);
  text-transform:uppercase;
  letter-spacing:.5px;
  margin-bottom:2px;
}
.ia-patient-name{
  font-size:14px;
  font-weight:600;
  color:var(--txt);
}
.ia-study-meta{
  font-size:12px;
  color:var(--txt-soft);
  margin-top:4px;
}
.ia-stomach-icon{
  width:60px;
  height:60px;
  opacity:.8;
}
.ia-stomach-icon svg{
  width:100%;
  height:100%;
  color:var(--cyan);
}
.ia-probability-section{
  background:var(--card);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:20px;
  display:flex;
  align-items:center;
  gap:20px;
}
.ia-probability-left{
  flex:1;
}
.ia-probability-label{
  font-size:12px;
  color:var(--txt-soft);
  margin-bottom:8px;
}
.ia-probability-value{
  font-size:42px;
  font-weight:800;
  color:var(--blue);
  line-height:1;
}
.ia-probability-desc{
  font-size:11px;
  color:var(--txt-soft);
  margin-top:8px;
  line-height:1.4;
}
.ia-gauge-container{
  width:100px;
  height:100px;
  position:relative;
  flex:none;
  display:flex;
  align-items:center;
  justify-content:center;
}
.ia-gauge-svg{
  width:100%;
  height:100%;
  transform:rotate(-90deg);
}
.ia-gauge-bg{
  fill:none;
  stroke:rgba(56,199,244,.15);
  stroke-width:8;
  stroke-linecap:round;
  stroke-dasharray:283;
}
.ia-gauge-fill{
  fill:none;
  stroke:var(--blue);
  stroke-width:8;
  stroke-linecap:round;
  stroke-dasharray:283;
  stroke-dashoffset:283;
  transition:stroke-dashoffset 1.5s ease-out;
}
.ia-gauge-percentage{
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%,-50%);
  font-size:20px;
  font-weight:700;
  color:var(--blue);
  line-height:1;
}
.ia-recommendations{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-md);
  padding:16px;
  margin-bottom:20px;
}
.ia-recommendations h5{
  font-size:13px;
  font-weight:600;
  color:var(--txt);
  margin-bottom:12px;
}
.ia-rec-item{
  display:flex;
  align-items:center;
  gap:10px;
  font-size:12px;
  color:var(--txt-soft);
  margin-bottom:8px;
}
.ia-rec-item:last-child{
  margin-bottom:0;
}
.ia-rec-icon{
  width:20px;
  height:20px;
  border-radius:50%;
  background:rgba(61,220,151,.15);
  display:grid;
  place-items:center;
  flex:none;
}
.ia-rec-icon svg{
  width:12px;
  height:12px;
  color:var(--green);
}

@media (max-width:1200px){
  .content-with-panel.panel-open{
    grid-template-columns:1fr;
  }
  .patient-detail-panel{
    right:0;
    top:0;
    bottom:0;
    width:100%;
    max-width:420px;
    border-radius:0;
    border-left:1px solid var(--stroke-strong);
    z-index:200;
    transform:translateX(100%);
  }
  .content-with-panel.panel-open .patient-detail-panel{
    transform:translateX(0);
  }
}
.table-footer a{
  color:var(--blue);
  font-weight:600;
  transition:color 150ms ease;
}
.table-footer a:hover{color:var(--cyan)}

/* Paginación */
.pagination{
  display:flex;
  align-items:center;
  gap:4px;
}
.page-btn{
  min-width:32px;
  height:32px;
  padding:0 8px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:transparent;
  color:var(--txt-soft);
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  transition:all 150ms ease;
  display:grid;
  place-items:center;
}
.page-btn:hover{background:var(--panel-2);color:var(--txt);border-color:var(--stroke-strong)}
.page-btn.active{background:var(--blue);border-color:var(--blue);color:#fff;}
.page-btn:disabled{opacity:.35;cursor:not-allowed;}

/* Responsive */
@media (max-width:1024px){
  .table-header,
  .patient-row{
    grid-template-columns:2fr 1fr 1fr 1fr 80px;
  }
  .table-header span:nth-child(4),
  .patient-row .cell-study{display:none}
}
@media (max-width:768px){
  .table-header,
  .patient-row{
    grid-template-columns:2fr 1fr 1fr 60px;
  }
  .table-header span:nth-child(3),
  .patient-row .cell-fecha{display:none}
  .toolbar{gap:8px}
  .search-box{min-width:0;flex:1}
  .btn-filter span{display:none}
  .table-footer{flex-direction:column;gap:8px;text-align:center;padding:12px 16px}
}
@media (max-width:540px){
  .table-header,
  .patient-row{
    grid-template-columns:1fr 1fr 44px;
  }
  .table-header span:nth-child(2),
  .patient-row .cell-estado{display:none}
  .toolbar{flex-wrap:nowrap}
  .btn-new span{font-size:12px}
  .patient-name{font-size:13px}
  .patient-initials{width:32px;height:32px;font-size:11px}
  .panel-tabs{overflow-x:auto;-webkit-overflow-scrolling:touch;}
  .panel-tabs .tab-btn{white-space:nowrap;font-size:12px;padding:8px 12px}
  .content-with-panel.panel-open{
    grid-template-columns:1fr;
  }
  .detail-panel{
    position:fixed;
    inset:0;
    z-index:200;
    border-radius:0;
    overflow-y:auto;
  }
}

.patient-avatar img,
.panel-avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
  border-radius:50%;
  display:block;
}
.panel-avatar{
  overflow:hidden;
}
.patient-avatar{
  overflow:hidden;
}

</style>
@endpush

@section('content')

  {{-- Panel de filtros --}}
  <div class="filter-overlay" id="filterOverlay" onclick="closeFilters()"></div>
  <aside class="filter-panel" id="filterPanel">
    <div class="filter-panel-head">
      <h2>Filtros</h2>
      <button class="filter-close" onclick="closeFilters()" aria-label="Cerrar filtros">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="filter-tabs-row">
      <button class="filter-tab active" id="ftab-basic" onclick="switchFilterTab('basic')">Filtros</button>
      <button class="filter-tab" id="ftab-advanced" onclick="switchFilterTab('advanced')">Avanzados</button>
    </div>
    <div class="filter-body">
      {{-- Tab básico --}}
      <div id="ftab-content-basic">
        <div class="filter-group">
          <label>Buscar</label>
          <input type="text" class="filter-input" placeholder="Buscar pacientes" id="fBuscar">
        </div>
        <div class="filter-group">
          <label>Número de seguro social</label>
          <input type="text" class="filter-input" placeholder="Buscar pacientes" id="fSeguro">
        </div>
        <div class="filter-group">
          <label>Número de folio</label>
          <input type="text" class="filter-input" placeholder="Buscar pacientes" id="fFolio">
        </div>
        <div class="filter-group">
          <label>Tipo de estudio</label>
          <select class="filter-input filter-select" id="fTipoEstudio">
            <option value="">Todos los estudios</option>
            <option>Endoscopia diagnóstica</option>
            <option>Endoscopia alta</option>
            <option>Colonoscopia</option>
            <option>CPRE</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Médico</label>
          <select class="filter-input filter-select" id="fMedico">
            <option>Ricardo Martínez</option>
            <option>Dr. Victor</option>
            <option>Dra. López</option>
          </select>
        </div>
      </div>
      {{-- Tab avanzado --}}
      <div id="ftab-content-advanced" style="display:none">
        <div class="filter-group">
          <label>Buscar</label>
          <input type="text" class="filter-input" placeholder="Buscar pacientes" id="fBuscarAdv">
        </div>
        <div class="filter-group">
          <label>Número de seguro social</label>
          <input type="text" class="filter-input" placeholder="Buscar pacientes" id="fSeguroAdv">
        </div>
        <div class="filter-group">
          <label>Número de folio</label>
          <input type="text" class="filter-input" placeholder="Buscar pacientes" id="fFolioAdv">
        </div>
        <div class="filter-group">
          <label>Tipo de estudio</label>
          <select class="filter-input filter-select" id="fTipoEstudioAdv">
            <option value="">Todos los estudios</option>
            <option>Endoscopia diagnóstica</option>
            <option>Endoscopia alta</option>
            <option>Colonoscopia</option>
            <option>CPRE</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Médico</label>
          <select class="filter-input filter-select" id="fMedicoAdv">
            <option>Ricardo Martínez</option>
            <option>Dr. Victor</option>
            <option>Dra. López</option>
          </select>
        </div>
        <p class="filter-section-title" style="margin-top:8px">Filtros avanzados</p>
        <div class="filter-group">
          <label>Fecha</label>
          <div class="filter-date-btns">
            <button class="filter-date-btn" onclick="setDateFilter(this,'hoy')">Hoy</button>
            <button class="filter-date-btn active" onclick="setDateFilter(this,'semana')">Semana</button>
            <button class="filter-date-btn" onclick="setDateFilter(this,'mes')">Mes</button>
          </div>
        </div>
        <div class="filter-group">
          <label>Sexo</label>
          <select class="filter-input filter-select" id="fSexo">
            <option value="">Todos</option>
            <option>Masculino</option>
            <option>Femenino</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Rango de edad</label>
          <div class="filter-age-row">
            <div class="filter-age-input">
              <span class="filter-age-label">Desde</span>
              <input type="number" placeholder="19" id="fEdadDesde" min="0" max="120">
            </div>
            <div class="filter-age-input">
              <span class="filter-age-label">Hasta</span>
              <input type="number" placeholder="45" id="fEdadHasta" min="0" max="120">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="filter-footer">
      <button class="filter-btn-clear" onclick="clearFilters()">Limpiar</button>
      <button class="filter-btn-apply" onclick="applyFilters()">Aplicar</button>
    </div>
  </aside>

  {{-- Toolbar con búsqueda y acciones --}}
  <section class="toolbar rise d2">
    <div class="search-box">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="searchInput" placeholder="Busca por nombre del paciente" oninput="filterPatients()">
    </div>
    <button class="btn-filter" id="btnFiltros" onclick="openFilters()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      <span>Filtros</span>
    </button>
    <a href="{{ route('pacientes.create') }}" class="btn-new">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo paciente
    </a>
  </section>

{{-- Wrapper con layout grid para tabla + panel --}}
<div class="content-with-panel" id="contentWrapper">

  {{-- Tabla de pacientes --}}
  <section class="patients-card rise d3">
    <div class="table-header">
      <span class="col-paciente ordenar-container">
        PACIENTE
        <button type="button" class="ordenar-btn" onclick="toggleOrdenar('paciente')" title="Ordenar pacientes">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div id="ordenarPacienteDropdown" class="ordenar-dropdown">
          <div class="ordenar-option" onclick="ordenarPor('paciente', 'default')">
            Predeterminado
          </div>
          <div class="ordenar-option" onclick="ordenarPor('paciente', 'nombre-asc')">
            Nombre: A-Z
          </div>
          <div class="ordenar-option" onclick="ordenarPor('paciente', 'nombre-desc')">
            Nombre: Z-A
          </div>
        </div>
      </span>
      <span>FOLIO</span>
      <span>FECHA DE NAC.</span>
      <span class="col-estudio ordenar-container">
        ÚLTIMO ESTUDIO
        <button type="button" class="ordenar-btn" onclick="toggleOrdenar('estudio')" title="Ordenar estudios">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div id="ordenarEstudioDropdown" class="ordenar-dropdown">
          <div class="ordenar-option" onclick="ordenarPor('estudio', 'default')">
            Predeterminado
          </div>
          <div class="ordenar-option" onclick="ordenarPor('estudio', 'fecha-reciente')">
            Fecha: más reciente
          </div>
          <div class="ordenar-option" onclick="ordenarPor('estudio', 'fecha-antigua')">
            Fecha: más antigua
          </div>
        </div>
      </span>
      <span class="col-status estado-filter-container">
        ESTADO
        <button type="button" class="estado-filter-btn" onclick="toggleEstadoFilter()" title="Filtrar por estado">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div id="estadoFilterDropdown" class="estado-filter-dropdown">
          <div class="filter-option" onclick="filterByEstado('all')">
            <span class="status-dot gray"></span> Todos
          </div>
          <div class="filter-option" onclick="filterByEstado('completado')">
            <span class="status-dot green"></span> Completado
          </div>
          <div class="filter-option" onclick="filterByEstado('espera')">
            <span class="status-dot yellow"></span> En espera
          </div>
          <div class="filter-option" onclick="filterByEstado('cancelado')">
            <span class="status-dot red"></span> Cancelado
          </div>
        </div>
      </span>
      <span>ACCIONES</span>
    </div>

    <div id="patientsTableBody"></div>

    <div class="table-footer">
      <span id="paginationInfo">Mostrando 1 a 15 de 128 pacientes</span>
      <div class="pagination" id="paginationControls"></div>
    </div>
  </section>

  {{-- Panel lateral de detalle del paciente --}}
  <aside class="patient-detail-panel" id="patientPanel">
    <div class="panel-header">
      <div class="patient-identity">
        <div class="panel-avatar" id="panelAvatar">MG</div>
        <div class="patient-title">
          <h3 id="panelName">María Gonzales</h3>
          <span class="folio" id="panelFolio">Folio: 00045</span>
        </div>
      </div>
      <button class="btn-close-panel" onclick="closePanel()" aria-label="Cerrar panel">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="quick-meta">
      <div class="meta-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span id="panelAge">45 años</span>
      </div>
      <div class="meta-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <span id="panelGender">Femenino</span>
      </div>
      <div class="meta-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span id="panelDob">16/04/1979</span>
      </div>
    </div>

    <div class="panel-tabs">
      <button class="tab-btn active" onclick="showTab('resumen')">Resumen</button>
      <button class="tab-btn" onclick="showTab('historial')">Historial</button>
      <button class="tab-btn" onclick="showTab('reportes')">Reportes IA</button>
    </div>

    {{-- Contenido Tab Resumen --}}
    <div id="tab-resumen" class="tab-content active">
      <div class="info-section">
        <h4>Información general</h4>
        <div class="info-list">
          <div class="info-item">
            <div class="info-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <span>Teléfono</span>
            </div>
            <div class="info-value" id="panelPhone">+52 722 162 0815</div>
          </div>
          <div class="info-item">
            <div class="info-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <span>Correo</span>
            </div>
            <div class="info-value" id="panelEmail">carlos@gmail.com</div>
          </div>
          <div class="info-item">
            <div class="info-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <span>Dirección</span>
            </div>
            <div class="info-value" id="panelAddress">Temaya, Francisco 01</div>
          </div>
          <div class="info-item">
            <div class="info-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <span>Médico</span>
            </div>
            <div class="info-value" id="panelMedicoInfo">Sin médico</div>
          </div>
        </div>
      </div>

      <div class="panel-cards" id="panelCards" style="display:none;">
        <div class="mini-card">
          <div class="mini-card-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Próxima cita
          </div>
          <div class="mini-card-value" id="panelProximaCitaFecha">—</div>
          <div class="mini-card-sub" id="panelProximaCitaHora">—</div>
        </div>
      </div>
    </div>

    {{-- Contenido Tab Historial --}}
    <div id="tab-historial" class="tab-content">
      <div class="historial-section">
        <h4>Historial de estudios</h4>
        <div class="historial-list" id="historialList">
          {{-- Se llena dinámicamente con JavaScript --}}
        </div>
        <div class="historial-empty" id="historialEmpty" style="display:none;">
          <p style="color:var(--txt-soft);font-size:13px;text-align:center;padding:24px 0;">Este paciente aún no tiene estudios registrados.</p>
        </div>
        <a href="#" id="btnVerTodoHistorial" class="btn-view-all">
          Ver todo el historial de estudios
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>

    {{-- Contenido Tab Reportes IA --}}
    <div id="tab-reportes" class="tab-content">
      <div class="reportes-section">
        <h4>Resumen de análisis IA</h4>
        
        <div class="ia-summary-card">
          <div class="ia-patient-header">
            <div class="ia-patient-avatar">MG</div>
            <div class="ia-patient-info">
              <div class="ia-patient-label">paciente</div>
              <div class="ia-patient-name">María Gonzales</div>
              <div class="ia-study-meta">Estudio: Endoscopia digestiva alta</div>
              <div class="ia-study-meta">Fecha: 08/05/2025</div>
            </div>
            <div class="ia-stomach-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            </div>
          </div>
          
          <div class="ia-probability-section">
            <div class="ia-probability-left">
              <div class="ia-probability-label">Probabilidad de gastritis</div>
              <div class="ia-probability-value">82%</div>
              <div class="ia-probability-desc">Basado en patrones<br>detectados por IA</div>
            </div>
            <div class="ia-gauge-container">
              <svg class="ia-gauge-svg" viewBox="0 0 100 100">
                <circle class="ia-gauge-bg" cx="50" cy="50" r="45"/>
                <circle id="gaugeFill" class="ia-gauge-fill" cx="50" cy="50" r="45" data-percentage="82"/>
              </svg>
              <div class="ia-gauge-percentage" id="gaugePercentage">0%</div>
            </div>
          </div>
        </div>
        
        <div class="ia-recommendations">
          <h5>Recomendaciones IA</h5>
          <div class="ia-rec-item">
            <div class="ia-rec-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
            </div>
            <span>Comparar con estudio previo del 2024</span>
          </div>
          <div class="ia-rec-item">
            <div class="ia-rec-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
            </div>
            <span>Seguimiento en 3 meses</span>
          </div>
        </div>
        
        <a href="#" class="btn-view-all">
          Ver reporte de IA
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>
  </aside>

</div>

{{-- Modal Eliminar Paciente --}}
<div id="modalEliminar" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;">
  <div style="background:var(--card,#1a2035);border:1px solid var(--stroke-strong,#2e3a55);border-radius:16px;padding:32px 28px;max-width:400px;width:90%;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.5);">
    <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,90,110,.12);border:1px solid rgba(255,90,110,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ff5a6e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
    </div>
    <h3 style="font-size:17px;font-weight:700;color:var(--txt,#e2e8f0);margin:0 0 8px;">Eliminar paciente</h3>
    <p style="font-size:13px;color:var(--txt-soft,#8896ae);margin:0 0 24px;line-height:1.6;">¿Estás seguro de que deseas eliminar a <strong id="modalEliminarNombre" style="color:var(--txt,#e2e8f0);"></strong>?<br>Esta acción no se puede deshacer.</p>
    <div style="display:flex;gap:10px;justify-content:center;">
      <button onclick="cancelarEliminar()" style="flex:1;padding:10px 0;border-radius:10px;border:1px solid var(--stroke,#2e3a55);background:transparent;color:var(--txt,#e2e8f0);font-size:14px;font-weight:600;cursor:pointer;">Cancelar</button>
      <button onclick="confirmarEliminar()" style="flex:1;padding:10px 0;border-radius:10px;border:none;background:#ff5a6e;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">Eliminar</button>
    </div>
  </div>
</div>

{{-- Modal Opciones de Contacto --}}
<div id="modalContacto" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;">
  <div style="background:var(--card,#1a2035);border:1px solid var(--stroke-strong,#2e3a55);border-radius:16px;padding:32px 28px;max-width:400px;width:90%;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.5);">
    <div style="width:60px;height:60px;border-radius:50%;background:rgba(56,199,244,.12);border:1px solid rgba(56,199,244,.3);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#38c7f4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    </div>
    <h3 style="font-size:17px;font-weight:700;color:var(--txt,#e2e8f0);margin:0 0 8px;">Enviar mensaje</h3>
    <p style="font-size:13px;color:var(--txt-soft,#8896ae);margin:0 0 24px;line-height:1.6;">Selecciona cómo deseas contactar a <strong id="modalContactoNombre" style="color:var(--txt,#e2e8f0);"></strong></p>
    <div style="display:flex;flex-direction:column;gap:10px;">
      <button onclick="enviarWhatsApp()" style="display:flex;align-items:center;justify-content:center;gap:10px;padding:12px 0;border-radius:10px;border:none;background:#25D366;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.292-.995-.69-2.058-.997a4.88 4.88 0 0 0-.82-.166c-.197.233-.486.652-.675.99-.785 1.4-2.055 1.412-2.839 0-.189-.338-.478-.757-.675-.99a4.88 4.88 0 0 0-.82.166c-1.063.307-1.761.705-2.058.997-.09.092-.09.242 0 .333.297.298.995.705 2.058 1.012.82.236 1.638.178 2.189-.089.12-.055.235-.117.345-.185.11.068.225.13.345.185.55.267 1.369.325 2.189.089 1.063-.307-1.761-.714 2.058-1.012.09-.091.09-.241 0-.333zM12 2C6.486 2 2 6.486 2 12s4.486 10 10 10c1.468 0 2.861-.332 4.113-.912 1.29-.596 2.4-1.476 3.245-2.563a9.95 9.95 0 0 0 1.542-4.06A9.95 9.95 0 0 0 22 12c0-5.514-4.486-10-10-10zm0 18c-4.411 0-8-3.589-8-8 0-1.473.403-2.85 1.105-4.033a2 2 0 0 1 2.034-.967c.96.13 1.846.516 2.555 1.098a5.96 5.96 0 0 1 2.612 0c.709-.582 1.595-.968 2.555-1.098a2 2 0 0 1 2.034.967A7.963 7.963 0 0 1 20 12c0 4.411-3.589 8-8 8z"/></svg>
        WhatsApp
      </button>
      <button onclick="enviarCorreo()" style="display:flex;align-items:center;justify-content:center;gap:10px;padding:12px 0;border-radius:10px;border:none;background:#38c7f4;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        Correo electrónico
      </button>
      <button onclick="cerrarModalContacto()" style="padding:10px 0;border-radius:10px;border:1px solid var(--stroke,#2e3a55);background:transparent;color:var(--txt,#e2e8f0);font-size:14px;font-weight:600;cursor:pointer;">Cancelar</button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
@php
  $pacientesColeccion = isset($pacientes) ? collect($pacientes) : collect();
  $pacientesJs = $pacientesColeccion->values()->map(function ($paciente) {
      $nombre = trim($paciente->nombre_completo ?? 'Paciente sin nombre');
      $partes = preg_split('/\s+/', $nombre);
      $iniciales = '';

      if (count($partes) >= 2) {
          $iniciales = mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1);
      } else {
          $iniciales = mb_substr($nombre, 0, 2);
      }

      $estudios = \App\Models\Estudio::where('paciente_id', $paciente->id)->get();
      $tieneEstudios = $estudios->count() > 0;
      $ultimoEstudio = $estudios->sortByDesc('fecha')->first();

      $proximaCita = \App\Models\Cita::where('paciente_id', $paciente->id)
          ->whereDate('fecha', '>=', today())
          ->where('estado', '!=', 'cancelado')
          ->orderBy('fecha')
          ->orderBy('hora')
          ->first();

      $estudiosLista = $estudios->map(function($est) {
          return [
              'id' => $est->id,
              'tipo' => $est->tipo ?? 'Sin tipo',
              'fecha' => $est->fecha ? format_user_date($est->fecha) : 'Sin fecha',
              'reporte_path' => $est->reporte_path,
              'video_path' => $est->video_path,
          ];
      })->toArray();

      $edad = $paciente->edad;
      if (!$edad && $paciente->fecha_nacimiento) {
          $edad = $paciente->fecha_nacimiento->age;
      }

      return [
          'id' => $paciente->id,
          'name' => $nombre,
          'initials' => mb_strtoupper($iniciales),
          'age' => $edad ? $edad . ' años' : 'Sin edad',
          'gender' => $paciente->sexo ? ucfirst($paciente->sexo) : 'No especificado',
          'folio' => $paciente->folio ?? 'Sin folio',
          'dob' => $paciente->fecha_nacimiento ? format_user_date($paciente->fecha_nacimiento) : 'Sin fecha',
          'phone' => $paciente->telefono ?? 'Sin teléfono',
          'email' => $paciente->email ?? 'Sin correo',
          'address' => $paciente->direccion ?? 'Sin dirección',
          'medico' => $paciente->medico ?? 'Sin médico',
          'study_date' => $ultimoEstudio && $ultimoEstudio->fecha ? format_user_date($ultimoEstudio->fecha) : '',
          'study_type' => $ultimoEstudio ? ($ultimoEstudio->tipo ?? 'Sin estudio') : '',
          'status' => $ultimoEstudio ? ($ultimoEstudio->estado ?? 'completed') : '',
          'tiene_estudios' => $tieneEstudios,
          'estudios' => $estudiosLista,
          'foto_url' => $paciente->foto ? media_url($paciente->foto) : null,
          'proxima_cita' => $proximaCita ? [
              'fecha' => format_user_date($proximaCita->fecha),
              'hora' => $proximaCita->hora
                  ? (function ($hora) {
                      try {
                          return format_user_time(\Illuminate\Support\Carbon::parse($hora));
                      } catch (\Exception $e) {
                          return $hora;
                      }
                  })($proximaCita->hora)
                  : '',
          ] : null,
      ];
  });
@endphp

<script>
// Datos de pacientes enviados desde el controlador
const routes = {
  edit: "{{ route('pacientes.edit', ':id') }}",
  destroy: "{{ route('pacientes.destroy', ':id') }}",
  nuevoEstudio: "{{ route('nuevo-estudio') }}",
  agendar: "{{ route('agendar') }}",
  mensajes: "{{ route('mensajes') }}",
  iaReportes: "{{ route('ia-reportes.generar') }}",
  iaReportesTodos: "{{ route('ia-reportes.todos') }}",
  iaReportesRedactar: "{{ route('ia-reportes.redactar') }}"
};

const patientsData = @json($pacientesJs);
let patientsDataFiltered = [...patientsData];
const statusTexts = {completed:'Completado',waiting:'En espera',cancelled:'Cancelado'};

// ============ PAGINACIÓN ============
const PAGE_SIZE = 15;
let currentPage = 1;

function rowHTML(patient, globalIndex) {
  const estadosMap = {
    'en_proceso': 'waiting',
    'completado': 'completed',
    'cancelado': 'cancelled',
    'archivado': 'completed'
  };
  const st = patient.status ? (estadosMap[patient.status] || patient.status) : '';
  const stText = st ? (statusTexts[st] || st) : '';
  const editUrl = routes.edit.replace(':id', patient.id);
  const nuevoEstudioUrl = `${routes.nuevoEstudio}?paciente=${encodeURIComponent(patient.id)}`;
  const agendarUrl = `${routes.agendar}?paciente_id=${encodeURIComponent(patient.id)}`;
  const primerEstudio = patient.estudios && patient.estudios.length > 0 ? patient.estudios[0] : null;
  const iaReportesUrl = primerEstudio
    ? `${routes.iaReportes}?paciente=${encodeURIComponent(patient.name)}&folio=${encodeURIComponent(patient.folio || '')}&estudio=${encodeURIComponent(primerEstudio.id)}`
    : `${routes.iaReportes}?paciente=${encodeURIComponent(patient.name)}&folio=${encodeURIComponent(patient.folio || '')}`;
  const iaReportesTodosUrl = `${routes.iaReportesTodos}?paciente=${encodeURIComponent(patient.name)}&folio=${encodeURIComponent(patient.folio || '')}`;

  return `<div class="patient-row" onclick="openPanel(${globalIndex})" data-index="${globalIndex}" data-status="${st || 'none'}">
    <div class="patient-info">
      <div class="patient-avatar">${patient.foto_url ? `<img src="${patient.foto_url}" alt="${patient.name || 'Paciente'}">` : (patient.initials || 'PX')}</div>
      <div>
        <div class="patient-name">${patient.name || 'Paciente sin nombre'}</div>
        <div class="patient-meta">${patient.age || 'Sin edad'} · ${patient.gender || 'No especificado'}</div>
      </div>
    </div>
    <div class="cell">${patient.folio || 'Sin folio'}</div>
    <div class="cell cell-fecha cell-muted">${patient.dob || 'Sin fecha'}</div>
    <div class="cell-study">
      ${patient.study_date ? `<span class="date study-date">${patient.study_date}</span>` : ''}
      ${patient.study_type ? `<span class="type">${patient.study_type}</span>` : ''}
    </div>
    <div class="col-status">${st ? `<span class="status ${st}">${stText}</span>` : ''}</div>
    <div class="actions-wrapper">
      <div class="actions">
        <button class="btn-more" aria-label="Más opciones" onclick="event.stopPropagation();toggleMenu(this)">⋮</button>
      </div>
      <div class="actions-dropdown" onclick="event.stopPropagation()">
        <a href="#" onclick="event.stopPropagation(); crearInforme(${globalIndex}); return false;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Crear informe</a>
        <a href="${editUrl}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>Editar información</a>
        <a href="${nuevoEstudioUrl}" onclick="event.stopPropagation(); window.location.href=this.href; return false;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/><line x1="12" y1="17" x2="12" y2="22"/></svg>Iniciar estudio</a>
        <a href="#" onclick="event.stopPropagation(); generarReporteIA(${globalIndex}); return false;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><path d="M9 22h6"/><circle cx="12" cy="11" r="1" fill="currentColor"/></svg>Generar reporte IA</a>
        <a href="${agendarUrl}" onclick="event.stopPropagation(); window.location.href=this.href; return false;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Programar cita</a>
        <a href="#" onclick="event.stopPropagation(); abrirMensajesPaciente(${globalIndex}); return false;"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.292-.995-.69-2.058-.997a4.88 4.88 0 0 0-.82-.166c-.197.233-.486.652-.675.99-.785 1.4-2.055 1.412-2.839 0-.189-.338-.478-.757-.675-.99a4.88 4.88 0 0 0-.82.166c-1.063.307-1.761.705-2.058.997-.09.092-.09.242 0 .333.297.298.995.705 2.058 1.012.82.236 1.638.178 2.189-.089.12-.055.235-.117.345-.185.11.068.225.13.345.185.55.267 1.369.325 2.189.089 1.063-.307 1.761-.714 2.058-1.012.09-.091.09-.241 0-.333zM12 2C6.486 2 2 6.486 2 12s4.486 10 10 10c1.468 0 2.861-.332 4.113-.912 1.29-.596 2.4-1.476 3.245-2.563a9.95 9.95 0 0 0 1.542-4.06A9.95 9.95 0 0 0 22 12c0-5.514-4.486-10-10-10zm0 18c-4.411 0-8-3.589-8-8 0-1.473.403-2.85 1.105-4.033a2 2 0 0 1 2.034-.967c.96.13 1.846.516 2.555 1.098a5.96 5.96 0 0 1 2.612 0c.709-.582 1.595-.968 2.555-1.098a2 2 0 0 1 2.034.967A7.963 7.963 0 0 1 20 12c0 4.411-3.589 8-8 8z"/></svg>Enviar WhatsApp</a>
        <a href="#" onclick="event.stopPropagation(); mostrarExpediente(${globalIndex}); return false;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>Descargar expediente PDF</a>
        <a href="#" class="danger" onclick="deletePatient(${globalIndex})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>Eliminar paciente</a>
      </div>
    </div>
  </div>`;
}

let _deleteIndex = null;
let _contactoIndex = null;

function deletePatient(index) {
  const patient = patientsData[index];
  if (!patient) return;
  _deleteIndex = index;
  document.getElementById('modalEliminarNombre').textContent = patient.name;
  document.getElementById('modalEliminar').style.display = 'flex';
}

function cancelarEliminar() {
  _deleteIndex = null;
  document.getElementById('modalEliminar').style.display = 'none';
}

function mostrarOpcionesContacto(index) {
  const patient = patientsData[index];
  if (!patient) return;
  _contactoIndex = index;
  document.getElementById('modalContactoNombre').textContent = patient.name;
  document.getElementById('modalContacto').style.display = 'flex';
}

function abrirMensajesPaciente(index) {
  const patient = patientsData[index];
  if (!patient) return;

  const telefono = patient.phone || patient.telefono || '';
  if (!telefono || !String(telefono).replace(/\D/g, '')) {
    alert('El paciente no tiene número de teléfono registrado');
    return;
  }

  const params = new URLSearchParams({
    canal: 'whatsapp',
    paciente_id: String(patient.id)
  });
  window.location.href = `${routes.mensajes}?${params.toString()}`;
}

function cerrarModalContacto() {
  _contactoIndex = null;
  document.getElementById('modalContacto').style.display = 'none';
}

function enviarWhatsApp() {
  if (_contactoIndex === null) return;
  const patient = patientsData[_contactoIndex];
  if (!patient) return;

  let telefono = patient.phone || patient.telefono || '';
  if (!telefono) {
    alert('El paciente no tiene número de teléfono registrado');
    return;
  }

  telefono = telefono.replace(/\D/g, '');
  const mensaje = `Hola ${patient.name}, te contacto de EndoCare para confirmar tu cita.`;
  const url = `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`;
  window.open(url, '_blank');
  cerrarModalContacto();
}

function enviarCorreo() {
  if (_contactoIndex === null) return;
  const patient = patientsData[_contactoIndex];
  if (!patient) return;

  const email = patient.email || '';
  if (!email) {
    alert('El paciente no tiene correo electrónico registrado');
    return;
  }

  const asunto = 'Confirmación de cita - EndoCare';
  const cuerpo = `Hola ${patient.name},\n\nTe contactamos de EndoCare para confirmar tu cita.\n\nSaludos.`;
  const url = `mailto:${email}?subject=${encodeURIComponent(asunto)}&body=${encodeURIComponent(cuerpo)}`;
  window.location.href = url;
  cerrarModalContacto();
}

function mostrarExpediente(index) {
  const patient = patientsData[index];
  if (!patient) return;

  if (!patient.tiene_estudios || !patient.estudios || patient.estudios.length === 0) {
    alert(`El paciente ${patient.name} no tiene expediente disponible.`);
    return;
  }

  const estudioConReporte = patient.estudios.find(est => est.reporte_path && est.reporte_path.length > 0);
  
  if (estudioConReporte) {
    window.location.href = `/storage/${estudioConReporte.reporte_path}`;
  } else {
    alert(`El paciente ${patient.name} tiene estudios pero no hay reporte disponible para descargar.`);
  }
}

function crearInforme(index) {
  const patient = patientsData[index];
  if (!patient) return;

  if (!patient.tiene_estudios || !patient.estudios || patient.estudios.length === 0) {
    alert(`El paciente ${patient.name} no tiene estudios. Para crear un informe primero debe registrar un estudio.`);
    return;
  }

  const estudio = patient.estudios[0];
  const url = `${routes.iaReportesRedactar}?paciente=${encodeURIComponent(patient.id)}&estudio=${encodeURIComponent(estudio.id)}`;
  window.location.href = url;
}

function generarReporteIA(index) {
  const patient = patientsData[index];
  if (!patient) return;

  if (!patient.tiene_estudios || !patient.estudios || patient.estudios.length === 0) {
    alert(`El paciente ${patient.name} no tiene estudios. Para generar un reporte IA primero debe registrar un estudio.`);
    return;
  }

  const estudio = patient.estudios[0];
  const url = `${routes.iaReportes}?paciente=${encodeURIComponent(patient.name)}&folio=${encodeURIComponent(patient.folio || '')}&estudio=${encodeURIComponent(estudio.id)}`;
  window.location.href = url;
}
async function confirmarEliminar() {
  if (_deleteIndex === null) return;
  const patient = patientsData[_deleteIndex];
  if (!patient) return;

  try {
    const criticalToken = await window.CriticalSecurity.authorize(
      'patients',
      `Confirma tu contraseña para eliminar al paciente ${patient.name}.`
    );
    if (criticalToken === null) return;

    const response = await fetch(routes.destroy.replace(':id', patient.id), {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'X-Critical-Authorization': criticalToken
      },
      body: new URLSearchParams({'_method':'DELETE'})
    });

    const data = await response.json();

    if (!data.success) {
      alert('Error: ' + data.message);
      return;
    }

    patientsData.splice(_deleteIndex, 1);
    _deleteIndex = null;
    document.getElementById('modalEliminar').style.display = 'none';
    closePanel();

    const totalPages = Math.ceil(patientsData.length / PAGE_SIZE);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
    renderPage(currentPage || 1);
  } catch (error) {
    alert('Ocurrió un error al eliminar el paciente: ' + error.message);
  }
}

function renderPage(page) {
  currentPage = page;
  const total = patientsDataFiltered.length;
  const totalPages = Math.max(Math.ceil(total / PAGE_SIZE), 1);
  const start = (page-1)*PAGE_SIZE;
  const end = Math.min(start+PAGE_SIZE, total);
  const pageData = patientsDataFiltered.slice(start, end);

  const body = document.getElementById('patientsTableBody');
  if(body) {
    if (pageData.length === 0) {
      body.innerHTML = `<div style="padding:32px 20px;text-align:center;color:var(--txt-soft);">No se encontraron pacientes.</div>`;
    } else {
      body.innerHTML = pageData.map((p,i) => rowHTML(p, start+i)).join('');
    }
  }

  const info = document.getElementById('paginationInfo');
  if(info) {
    info.textContent = total === 0
      ? 'Mostrando 0 pacientes'
      : 'Mostrando '+(start+1)+' a '+end+' de '+total+' pacientes';
  }

  renderPaginationControls(page, totalPages);
}

function renderPaginationControls(page, totalPages) {
  const container = document.getElementById('paginationControls');
  if(!container) return;

  let html = '';
  html += `<button class="page-btn" onclick="renderPage(${page-1})" ${page===1?'disabled':''}>‹</button>`;

  const delta = 2;
  const pages = [];
  for(let i=1;i<=totalPages;i++){
    if(i===1||i===totalPages||Math.abs(i-page)<=delta) pages.push(i);
    else if(pages[pages.length-1]!=='...') pages.push('...');
  }
  pages.forEach(p => {
    if(p==='...') html += `<button class="page-btn" disabled>…</button>`;
    else html += `<button class="page-btn${p===page?' active':''}" onclick="renderPage(${p})">${p}</button>`;
  });

  html += `<button class="page-btn" onclick="renderPage(${page+1})" ${page===totalPages?'disabled':''}>›</button>`;
  container.innerHTML = html;
}

renderPage(1);

/* ============ Selección de paciente vía ?paciente=ID (desde el dashboard) ============ */
(function selectPatientFromQuery() {
  const pid = new URLSearchParams(window.location.search).get('paciente');
  if (!pid) return;

  const idx = patientsData.findIndex(p => String(p.id) === String(pid));
  if (idx < 0) return;

  const page = Math.floor(idx / PAGE_SIZE) + 1;
  renderPage(page);

  requestAnimationFrame(() => {
    const row = document.querySelector('[data-index="' + idx + '"]');
    if (!row) return;
    document.querySelectorAll('.patient-row').forEach(r => r.classList.remove('active'));
    row.classList.add('active');
    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
  });
})();


/* ============ PANEL FILTROS ============ */
function openFilters() {
  document.getElementById('filterPanel').classList.add('open');
  document.getElementById('filterOverlay').classList.add('open');
  document.getElementById('btnFiltros').classList.add('active');
  document.body.style.overflow = 'hidden';
}
function closeFilters() {
  document.getElementById('filterPanel').classList.remove('open');
  document.getElementById('filterOverlay').classList.remove('open');
  document.getElementById('btnFiltros').classList.remove('active');
  document.body.style.overflow = '';
}
function switchFilterTab(tab) {
  document.getElementById('ftab-content-basic').style.display    = tab === 'basic'    ? 'flex' : 'none';
  document.getElementById('ftab-content-advanced').style.display = tab === 'advanced' ? 'flex' : 'none';
  document.getElementById('ftab-content-basic').style.flexDirection    = 'column';
  document.getElementById('ftab-content-advanced').style.flexDirection = 'column';
  document.getElementById('ftab-basic').classList.toggle('active',    tab === 'basic');
  document.getElementById('ftab-advanced').classList.toggle('active', tab === 'advanced');
}
function setDateFilter(btn, val) {
  btn.closest('.filter-date-btns').querySelectorAll('.filter-date-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}
function applyFilters() {
  closeFilters();
}

function filterPatients() {
  const searchInput = document.getElementById('searchInput');
  const searchTerm = searchInput.value.toLowerCase().trim();

  if (searchTerm === '') {
    patientsDataFiltered = [...patientsData];
  } else {
    patientsDataFiltered = patientsData.filter(patient =>
      patient.name.toLowerCase().includes(searchTerm) ||
      patient.folio.toLowerCase().includes(searchTerm) ||
      (patient.phone && patient.phone.toLowerCase().includes(searchTerm)) ||
      (patient.email && patient.email.toLowerCase().includes(searchTerm))
    );
  }

  currentPage = 1;
  renderPage(1);
}
function clearFilters() {
  ['fBuscar','fSeguro','fFolio','fBuscarAdv','fSeguroAdv','fFolioAdv'].forEach(id => {
    const el = document.getElementById(id); if(el) el.value = '';
  });
  ['fTipoEstudio','fMedico','fTipoEstudioAdv','fMedicoAdv','fSexo'].forEach(id => {
    const el = document.getElementById(id); if(el) el.selectedIndex = 0;
  });
  const edD = document.getElementById('fEdadDesde'); if(edD) edD.value = '';
  const edH = document.getElementById('fEdadHasta'); if(edH) edH.value = '';
}
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeFilters(); });

// Quita el recorte de los contenedores mientras haya un menú abierto, para que
// el desplegable no se corte cuando hay pocas filas en la tabla.
function syncMenuOverflow() {
  const anyOpen = document.querySelector('.actions-dropdown.active') !== null;
  document.querySelector('.patients-card')?.classList.toggle('menu-open', anyOpen);
  document.querySelector('.content-with-panel')?.classList.toggle('menu-open', anyOpen);
}

function toggleMenu(btn) {
  document.querySelectorAll('.actions-dropdown.active').forEach(menu => {
    if (menu !== btn.closest('.actions-wrapper').querySelector('.actions-dropdown')) {
      menu.classList.remove('active');
    }
  });
  const dropdown = btn.closest('.actions-wrapper').querySelector('.actions-dropdown');
  dropdown.classList.toggle('active');
  syncMenuOverflow();
}

function savePatientToCache(patient) {
  try {
    localStorage.setItem('lastPatient', JSON.stringify(patient));
  } catch(e) {}
}

function openPanel(index) {
  const patient = patientsData[index] || patientsData[0];
  savePatientToCache(patient);

  const panelAvatar = document.getElementById('panelAvatar');
  if (panelAvatar) {
    if (patient.foto_url) {
      panelAvatar.innerHTML = `<img src="${patient.foto_url}" alt="${patient.name || 'Paciente'}">`;
    } else {
      panelAvatar.textContent = patient.initials || 'PX';
    }
  }
  document.getElementById('panelName').textContent = patient.name;
  document.getElementById('panelFolio').textContent = 'Folio: ' + patient.folio;
  document.getElementById('panelAge').textContent = patient.age;
  document.getElementById('panelGender').textContent = patient.gender;
  document.getElementById('panelDob').textContent = patient.dob;
  document.getElementById('panelPhone').textContent = patient.phone;
  document.getElementById('panelEmail').textContent = patient.email;
  document.getElementById('panelAddress').textContent = patient.address;
  const panelMedicoInfo = document.getElementById('panelMedicoInfo');
  if (panelMedicoInfo) panelMedicoInfo.textContent = patient.medico || 'Sin médico';

  const panelCards = document.getElementById('panelCards');
  const panelProximaCitaFecha = document.getElementById('panelProximaCitaFecha');
  const panelProximaCitaHora = document.getElementById('panelProximaCitaHora');
  if (panelCards && panelProximaCitaFecha && panelProximaCitaHora) {
    if (patient.proxima_cita) {
      panelProximaCitaFecha.textContent = patient.proxima_cita.fecha || '—';
      panelProximaCitaHora.textContent = patient.proxima_cita.hora || '—';
      panelCards.style.display = 'block';
    } else {
      panelCards.style.display = 'none';
    }
  }

  const historialList = document.getElementById('historialList');
  const historialEmpty = document.getElementById('historialEmpty');
  const btnVerTodoHistorial = document.getElementById('btnVerTodoHistorial');
  if (historialList && historialEmpty) {
    historialList.innerHTML = '';
    const estudios = patient.estudios || [];
    if (estudios.length > 0) {
      historialEmpty.style.display = 'none';
      const sorted = [...estudios].sort((a, b) => {
        const da = a.fecha ? a.fecha.split('/').reverse().join('-') : '';
        const db = b.fecha ? b.fecha.split('/').reverse().join('-') : '';
        return db.localeCompare(da);
      });
      sorted.slice(0, 5).forEach(est => {
        const item = document.createElement('div');
        item.className = 'historial-item';
        item.innerHTML = `
          <div class="historial-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          </div>
          <div class="historial-info">
            <div class="historial-title">${est.tipo || 'Estudio'}</div>
            <div class="historial-doctor">${patient.medico || 'Sin médico'}</div>
          </div>
          <div class="historial-right">
            <div class="historial-date">${est.fecha || 'Sin fecha'}</div>
          </div>
        `;
        historialList.appendChild(item);
      });
    } else {
      historialEmpty.style.display = 'block';
    }
  }
  if (btnVerTodoHistorial) {
    btnVerTodoHistorial.href = `${routes.nuevoEstudio}?paciente=${encodeURIComponent(patient.id)}`;
  }

  document.getElementById('contentWrapper').classList.add('panel-open');
  
  document.querySelectorAll('.patient-row').forEach(row => row.classList.remove('active'));
  const activeRow = document.querySelector('[data-index="' + index + '"]');
  if (activeRow) activeRow.classList.add('active');
}

function closePanel() {
  document.getElementById('contentWrapper').classList.remove('panel-open');
  document.querySelectorAll('.patient-row').forEach(row => row.classList.remove('active'));
}

// Tabs interactivos
function showTab(tabName) {
  // Actualizar botones
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  event.target.classList.add('active');
  
  // Mostrar contenido correspondiente
  document.querySelectorAll('.tab-content').forEach(content => {
    content.classList.remove('active');
  });
  document.getElementById('tab-' + tabName).classList.add('active');
  
  // Animar gauge si es la pestaña de Reportes IA
  if (tabName === 'reportes') {
    setTimeout(animateGauge, 100);
  }
}

// Animar gauge circular
function animateGauge() {
  const gaugeFill = document.getElementById('gaugeFill');
  const percentageText = document.getElementById('gaugePercentage');
  
  if (!gaugeFill || !percentageText) return;
  
  // Obtener porcentaje objetivo
  const targetPercentage = parseInt(gaugeFill.dataset.percentage) || 0;
  const circumference = 283; // 2 * PI * 45
  
  // Resetear gauge
  gaugeFill.style.strokeDashoffset = circumference;
  percentageText.textContent = '0%';
  
  // Animar después de un pequeño delay
  setTimeout(() => {
    const offset = circumference - (targetPercentage / 100) * circumference;
    gaugeFill.style.strokeDashoffset = offset;
    
    // Animar el número
    animateNumber(percentageText, 0, targetPercentage, 1500);
  }, 100);
}

// Animar número de 0 a target
function animateNumber(element, start, target, duration) {
  const startTime = performance.now();
  
  function update(currentTime) {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);
    
    // Easing: ease-out
    const easeOut = 1 - Math.pow(1 - progress, 3);
    const current = Math.round(start + (target - start) * easeOut);
    
    element.textContent = current + '%';
    
    if (progress < 1) {
      requestAnimationFrame(update);
    }
  }
  
  requestAnimationFrame(update);
}

// Cerrar menús al hacer click fuera
document.addEventListener('click', function(e) {
  if (!e.target.closest('.actions-wrapper')) {
    document.querySelectorAll('.actions-dropdown.active').forEach(menu => {
      menu.classList.remove('active');
    });
    syncMenuOverflow();
  }

  // Cerrar filtro de estado al hacer clic fuera
  if (!e.target.closest('.estado-filter-container')) {
    const estadoDropdown = document.getElementById('estadoFilterDropdown');
    if (estadoDropdown) {
      estadoDropdown.classList.remove('active');
    }
  }
});

// ============ FUNCIONES DE FILTRO POR ESTADO ============

// Mostrar/ocultar dropdown de filtro
toggleEstadoFilter = function() {
  const dropdown = document.getElementById('estadoFilterDropdown');
  if (dropdown) {
    dropdown.classList.toggle('active');
  }
};

// Variable para guardar el filtro actual
let currentEstadoFilter = 'all';

// Filtrar pacientes por estado
filterByEstado = function(estado) {
  currentEstadoFilter = estado;
  
  // Cerrar dropdown
  const dropdown = document.getElementById('estadoFilterDropdown');
  if (dropdown) {
    dropdown.classList.remove('active');
  }
  
  // Obtener todas las filas de pacientes
  const rows = document.querySelectorAll('.patient-row');
  
  // Convertir NodeList a Array para ordenar
  const rowsArray = Array.from(rows);
  
  // Orden de prioridad: Completado primero, En espera segundo, Cancelado tercero
  const ordenEstados = {
    'completed': 1,
    'waiting': 2,
    'cancelled': 3
  };
  
  // Ordenar por estado según la selección
  if (estado === 'all') {
    // Mostrar todos y ordenar: Completados primero, En espera segundo, Cancelados tercero
    rowsArray.sort((a, b) => {
      const estadoA = a.dataset.status || '';
      const estadoB = b.dataset.status || '';
      return (ordenEstados[estadoA] || 99) - (ordenEstados[estadoB] || 99);
    });
  } else {
    // Filtrar por estado específico y ordenar
    const estadoMapping = {
      'completado': 'completed',
      'espera': 'waiting',
      'cancelado': 'cancelled'
    };
    const targetStatus = estadoMapping[estado];
    
    // Mostrar solo el estado seleccionado primero, luego los demás
    rowsArray.sort((a, b) => {
      const estadoA = a.dataset.status || '';
      const estadoB = b.dataset.status || '';
      
      if (estadoA === targetStatus && estadoB !== targetStatus) return -1;
      if (estadoB === targetStatus && estadoA !== targetStatus) return 1;
      
      // Si ambos son del mismo grupo, ordenar por orden de estados
      return (ordenEstados[estadoA] || 99) - (ordenEstados[estadoB] || 99);
    });
  }
  
  // Reordenar en el DOM
  const tableBody = document.querySelector('.patients-card');
  rowsArray.forEach(row => {
    // Insertar cada fila al final para reordenar
    tableBody.appendChild(row);
  });
  
  // Actualizar indicador visual en el header (opcional)
  updateEstadoFilterIndicator(estado);
};

// Actualizar indicador visual del filtro
updateEstadoFilterIndicator = function(estado) {
  const btn = document.querySelector('.estado-filter-btn');
  if (!btn) return;
  
  // Quitar clases anteriores
  btn.classList.remove('active-filter-green', 'active-filter-yellow', 'active-filter-red');
  
  // Agregar clase según el filtro activo
  if (estado === 'completado') {
    btn.style.color = 'var(--green)';
  } else if (estado === 'espera') {
    btn.style.color = 'var(--orange)';
  } else if (estado === 'cancelado') {
    btn.style.color = 'var(--red)';
  } else {
    btn.style.color = 'var(--txt-soft)';
  }
};

// ============ FUNCIONES DE ORDENAMIENTO ============

// Mostrar/ocultar dropdown de ordenamiento
toggleOrdenar = function(tipo) {
  const dropdownPaciente = document.getElementById('ordenarPacienteDropdown');
  const dropdownEstudio = document.getElementById('ordenarEstudioDropdown');
  
  if (tipo === 'paciente') {
    dropdownPaciente.classList.toggle('active');
    dropdownEstudio.classList.remove('active');
  } else if (tipo === 'estudio') {
    dropdownEstudio.classList.toggle('active');
    dropdownPaciente.classList.remove('active');
  }
};

// Variable para guardar el orden actual
let currentOrden = {
  paciente: 'default',
  estudio: 'default'
};

// Ordenar pacientes
ordenarPor = function(tipo, criterio) {
  // Guardar criterio actual
  currentOrden[tipo] = criterio;
  
  // Cerrar dropdown
  if (tipo === 'paciente') {
    document.getElementById('ordenarPacienteDropdown').classList.remove('active');
  } else if (tipo === 'estudio') {
    document.getElementById('ordenarEstudioDropdown').classList.remove('active');
  }
  
  // Actualizar opción activa visualmente
  updateOrdenActiveOption(tipo, criterio);
  
  // Obtener todas las filas
  const rows = document.querySelectorAll('.patient-row');
  const rowsArray = Array.from(rows);
  
  // Ordenar según el criterio
  if (tipo === 'paciente') {
    // Ordenar por nombre de paciente
    rowsArray.sort((a, b) => {
      const nombreA = a.querySelector('.patient-name')?.textContent?.toLowerCase() || '';
      const nombreB = b.querySelector('.patient-name')?.textContent?.toLowerCase() || '';
      
      if (criterio === 'default') {
        // Volver al orden por índice
        const indexA = parseInt(a.dataset.index || 0);
        const indexB = parseInt(b.dataset.index || 0);
        return indexA - indexB;
      } else if (criterio === 'nombre-asc') {
        return nombreA.localeCompare(nombreB, 'es');
      } else if (criterio === 'nombre-desc') {
        return nombreB.localeCompare(nombreA, 'es');
      }
      return 0;
    });
  } else if (tipo === 'estudio') {
    // Ordenar por fecha de estudio
    rowsArray.sort((a, b) => {
      const fechaA = a.querySelector('.study-date')?.textContent || '';
      const fechaB = b.querySelector('.study-date')?.textContent || '';
      
      if (criterio === 'default') {
        // Volver al orden por índice
        const indexA = parseInt(a.dataset.index || 0);
        const indexB = parseInt(b.dataset.index || 0);
        return indexA - indexB;
      } else {
        // Parsear fechas (formato: "22 Mayo 2024")
        const parseFecha = (fechaStr) => {
          if (!fechaStr) return 0;
          const meses = {
            'enero': 0, 'febrero': 1, 'marzo': 2, 'abril': 3, 'mayo': 4, 'junio': 5,
            'julio': 6, 'agosto': 7, 'septiembre': 8, 'octubre': 9, 'noviembre': 10, 'diciembre': 11
          };
          const partes = fechaStr.toLowerCase().split(' ');
          if (partes.length >= 3) {
            const dia = parseInt(partes[0]) || 1;
            const mes = meses[partes[1]] || 0;
            const año = parseInt(partes[2]) || 2000;
            return new Date(año, mes, dia).getTime();
          }
          return 0;
        };
        
        const timeA = parseFecha(fechaA);
        const timeB = parseFecha(fechaB);
        
        if (criterio === 'fecha-reciente') {
          return timeB - timeA; // Más reciente primero
        } else if (criterio === 'fecha-antigua') {
          return timeA - timeB; // Más antigua primero
        }
      }
      return 0;
    });
  }
  
  // Reordenar en el DOM
  const tableBody = document.querySelector('.patients-card');
  rowsArray.forEach(row => {
    tableBody.appendChild(row);
  });
};

// Actualizar opción activa visualmente
updateOrdenActiveOption = function(tipo, criterio) {
  const dropdown = tipo === 'paciente' 
    ? document.getElementById('ordenarPacienteDropdown')
    : document.getElementById('ordenarEstudioDropdown');
  
  if (!dropdown) return;
  
  // Quitar clase active de todas las opciones
  dropdown.querySelectorAll('.ordenar-option').forEach(opt => {
    opt.classList.remove('active');
  });
  
  // Agregar clase active a la opción seleccionada
  const opciones = dropdown.querySelectorAll('.ordenar-option');
  opciones.forEach(opt => {
    const onclick = opt.getAttribute('onclick') || '';
    if (onclick.includes(criterio)) {
      opt.classList.add('active');
    }
  });
};

// Cerrar dropdowns al hacer clic fuera (agregar al evento existente)
const originalClickHandler = document.onclick;
document.addEventListener('click', function(e) {
  if (!e.target.closest('.ordenar-container')) {
    document.getElementById('ordenarPacienteDropdown')?.classList.remove('active');
    document.getElementById('ordenarEstudioDropdown')?.classList.remove('active');
  }
});

// Abrir panel automáticamente si viene paciente_id en la URL
(function(){
  const params = new URLSearchParams(window.location.search);
  const patientId = params.get('paciente_id');
  if (!patientId) return;
  const index = patientsData.findIndex(p => String(p.id) === patientId);
  if (index < 0) return;
  const page = Math.floor(index / PAGE_SIZE) + 1;
  renderPage(page);
  setTimeout(() => openPanel(index), 150);
})();
</script>
@endpush
