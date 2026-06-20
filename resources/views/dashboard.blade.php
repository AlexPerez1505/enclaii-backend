@extends('layouts.app')

@section('title', 'Dashboard')
@section('active', 'dashboard')
@section('header-title', 'Buenos dias, Dr. Victor 👋')
@section('header-sub')
  Resumen general de tu actividad clinica
@endsection

@push('styles')
<style>
/* ============ DASHBOARD CLINICO ============ */
body{
  background:
    radial-gradient(circle at 14% -8%, rgba(46,123,246,.18), transparent 34%),
    radial-gradient(circle at 82% 18%, rgba(56,199,244,.08), transparent 32%),
    #050B18;
}
.dash{grid-template-columns:300px 1fr}
.main{padding:34px 32px 12px}
.side{
  background:linear-gradient(180deg,#07101f 0%,#071426 56%,#060d1a 100%);
  border-color:rgba(72,119,190,.24);
  box-shadow:inset -1px 0 0 rgba(255,255,255,.02);
}
.side-brand{margin-bottom:34px}
.side-brand img{width:110px}
.side-brand-name{font-size:20px}
.side-help{
  background:linear-gradient(145deg,rgba(20,45,95,.8),rgba(7,18,38,.96));
  border-color:rgba(74,132,236,.34);
}
.head{margin-bottom:28px}
.head h1{font-size:28px}
.head-right{gap:16px}
.btn-ai,.bell,.profile{
  background:linear-gradient(180deg,rgba(16,35,66,.92),rgba(9,22,42,.96));
  border-color:rgba(73,132,224,.28);
}
.profile{min-width:188px}
html[data-theme="light"] body{
  background:#eef2fb;
}

.dashboard-shell{
  --dash-card:rgba(11,24,45,.84);
  --dash-card-2:rgba(8,19,36,.94);
  --dash-line:rgba(92,143,215,.22);
  --dash-line-strong:rgba(90,153,245,.42);
  display:flex;
  flex-direction:column;
  gap:16px;
}
.top-grid{
  display:grid;
  grid-template-columns:1.28fr 1.24fr 1.16fr 1.1fr;
  gap:16px;
}
.main-grid{
  display:grid;
  grid-template-columns:1.12fr 1.55fr 1.28fr;
  gap:16px;
  align-items:start;
}
.side-stack{display:flex;flex-direction:column;gap:16px}
.clinical-card{
  background:
    linear-gradient(145deg,rgba(14,31,58,.88),rgba(8,18,34,.96)),
    radial-gradient(circle at 90% 0%,rgba(46,123,246,.12),transparent 42%);
  border:1px solid var(--dash-line);
  border-radius:14px;
  box-shadow:0 20px 46px rgba(0,0,0,.18), inset 0 1px 0 rgba(255,255,255,.03);
  padding:18px 20px;
}
.card-head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:16px;
}
.title{
  display:flex;
  align-items:center;
  gap:10px;
  font-family:'Sora',sans-serif;
  font-size:13px;
  font-weight:700;
  letter-spacing:.01em;
  text-transform:uppercase;
}
.title svg{width:19px;height:19px;color:var(--blue)}
.title.purple svg{color:#a968ff}
.title.cyan svg{color:var(--cyan)}
.title.orange svg{color:#ff7a2f}
.title.red svg{color:#ff706f}
.soft-action,.count{
  color:#55a4ff;
  font-size:13px;
  font-weight:700;
}
.count{color:var(--txt-soft);font-weight:600}
.arrow-link{
  display:inline-flex;
  align-items:center;
  gap:10px;
  color:#2fa2ff;
  font-weight:700;
  font-size:14px;
  margin-top:14px;
}
.arrow-link svg{width:17px;height:17px}

.study-list,.finding-list,.activity-list,.priority-list,.day-list{display:flex;flex-direction:column;gap:12px}
.study-item{
  display:grid;
  grid-template-columns:42px 1fr auto;
  gap:12px;
  align-items:center;
  padding:10px;
  border:1px solid rgba(93,143,220,.16);
  border-radius:11px;
  background:rgba(5,15,30,.34);
}
.icon-badge,.activity-ico,.patient-avatar{
  width:36px;height:36px;
  border-radius:50%;
  display:grid;
  place-items:center;
  font-weight:800;
  font-size:12px;
}
.icon-badge.green{color:#42e38d;background:rgba(45,205,118,.16)}
.icon-badge.orange{color:#ffad31;background:rgba(245,158,45,.15)}
.study-item strong,.finding strong,.activity-copy strong,.priority-copy strong,.day-copy strong{display:block;font-size:14px}
.study-item span,.finding span,.activity-copy span,.priority-copy span,.day-copy span{color:var(--txt-soft);font-size:13px}
.study-time{font-size:12px;font-weight:800}
.study-time.green{color:#33db84}.study-time.orange{color:#ff9d2e}

.finding{
  display:grid;
  grid-template-columns:40px 1fr;
  gap:12px;
  align-items:center;
}
.finding .icon-badge{width:38px;height:38px}
.bar{
  height:5px;
  margin-top:8px;
  border-radius:99px;
  background:rgba(112,152,211,.12);
  overflow:hidden;
}
.bar i{display:block;height:100%;border-radius:inherit}
.bar.green i{width:92%;background:#43c76b}
.bar.yellow i{width:88%;background:#f5bd2d}
.bar.red i{width:85%;background:#ef5b65}

.storage-layout{
  display:grid;
  grid-template-columns:1fr 86px;
  gap:16px;
  align-items:center;
}
.storage-main{font-size:15px;color:var(--txt-soft)}
.storage-main b{
  color:var(--txt);
  font-family:'Sora',sans-serif;
  font-size:23px;
}
.progress{
  height:10px;
  border-radius:99px;
  background:rgba(92,130,190,.13);
  overflow:hidden;
  margin-top:28px;
}
.progress i{
  display:block;
  width:43%;
  height:100%;
  border-radius:inherit;
  background:linear-gradient(90deg,#5dd4ff,#2e7bf6);
}
.ring{position:relative;width:82px;height:82px}
.ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.ring circle{fill:none;stroke-width:9;stroke-linecap:round}
.ring .track{stroke:rgba(88,128,190,.18)}
.ring .value{stroke:#38c7f4;stroke-dasharray:226;stroke-dashoffset:145}
.ring span{
  position:absolute;
  inset:0;
  display:grid;
  place-items:center;
  font-family:'Sora',sans-serif;
  font-size:20px;
  font-weight:800;
}

.month-stat .big{
  font-family:'Sora',sans-serif;
  font-size:38px;
  font-weight:800;
  line-height:1;
}
.trend{
  display:inline-flex;
  margin-top:12px;
  padding:4px 8px;
  border-radius:8px;
  color:#20dda2;
  background:rgba(32,221,162,.1);
  font-size:12px;
  font-weight:800;
}
.spark{width:100%;height:82px;margin-top:14px}
.spark path{fill:none;stroke:#28b7ff;stroke-width:3;stroke-linecap:round}

.calendar-nav{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:24px;
  margin-bottom:16px;
  font-family:'Sora',sans-serif;
  font-size:13px;
}
.calendar-nav button{color:var(--txt-soft);font-size:22px;line-height:1}
.week{
  display:grid;
  grid-template-columns:repeat(7,1fr);
  gap:8px;
  margin-bottom:10px;
  text-align:center;
}
.day{
  color:var(--txt-soft);
  font-size:13px;
  padding:8px 0;
  border-radius:12px;
}
.day b{display:block;color:var(--txt);font-size:16px}
.day.active{
  color:#fff;
  background:linear-gradient(180deg,#3a8cff,#1f65df);
  box-shadow:0 12px 26px rgba(46,123,246,.34);
}
.day.active b{color:#fff}
.day-item{
  display:grid;
  grid-template-columns:70px 38px 1fr auto;
  gap:12px;
  align-items:center;
  padding:12px;
  border-bottom:1px solid rgba(96,137,202,.12);
}
.day-item:last-child{border-bottom:0}
.day-time{font-size:13px;color:var(--txt)}
.patient-avatar.purple{background:rgba(151,87,255,.2);color:#bd96ff}
.patient-avatar.blue{background:rgba(46,123,246,.18);color:#4fb4ff}
.patient-avatar.cyan{background:rgba(34,207,211,.16);color:#23dce0}
.patient-avatar.orange{background:rgba(245,121,45,.18);color:#ff9a42}

.tbl-wrap{overflow-x:auto}
.clinical-table{
  width:100%;
  min-width:700px;
  border-collapse:collapse;
  font-size:13px;
}
.clinical-table th{
  color:var(--txt-soft);
  font-size:12px;
  font-weight:600;
  padding:12px 10px;
  border-bottom:1px solid rgba(96,137,202,.16);
}
.clinical-table td{
  padding:14px 10px;
  border-bottom:1px solid rgba(96,137,202,.12);
}
.clinical-table tr:last-child td{border-bottom:0}
.patient-cell{display:flex;align-items:center;gap:10px;font-weight:700}
.patient-mini{
  width:32px;height:32px;border-radius:50%;
  display:grid;place-items:center;
  color:#fff;font-size:11px;font-weight:800;
  background:linear-gradient(135deg,#334a67,#81642a);
}
.patient-mini.blue{background:linear-gradient(135deg,#135c9d,#153d71)}
.patient-mini.cyan{background:linear-gradient(135deg,#0b7478,#154d6e)}
.patient-mini.red{background:linear-gradient(135deg,#7a362d,#563247)}
.patient-mini.teal{background:linear-gradient(135deg,#0b7375,#24537a)}
.dots{color:var(--txt-soft);font-size:22px;line-height:1}

.activity-item,.priority-item{
  display:grid;
  grid-template-columns:38px 1fr auto;
  gap:12px;
  align-items:center;
}
.activity-ico.green{background:rgba(54,218,117,.18);color:#54e47d}
.activity-ico.blue{background:rgba(46,123,246,.18);color:#4fb4ff}
.activity-ico.purple{background:rgba(151,87,255,.2);color:#c090ff}
.activity-ico.yellow{background:rgba(245,158,45,.16);color:#f9b332}
.activity-ico.pink{background:rgba(255,90,110,.17);color:#ff7590}
.activity-time{color:var(--txt-soft);font-size:12px;white-space:nowrap}
.priority-card{
  background:
    linear-gradient(145deg,rgba(43,17,27,.86),rgba(14,18,34,.94)),
    radial-gradient(circle at 0% 0%,rgba(255,90,110,.18),transparent 45%);
  border-color:rgba(255,90,110,.28);
}
.priority-item{padding:3px 0}
.priority-ico{
  width:38px;height:38px;border-radius:50%;
  display:grid;place-items:center;
  color:#ff7d7d;
  background:rgba(255,90,110,.19);
}

.status-strip{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:0;
  margin-top:8px;
  padding:16px 20px;
  border:1px solid var(--dash-line);
  border-radius:14px;
  background:linear-gradient(145deg,rgba(12,28,52,.88),rgba(7,16,31,.96));
}
.status-item{
  display:flex;
  align-items:center;
  gap:12px;
  padding:0 20px;
  border-right:1px solid rgba(96,137,202,.16);
}
.status-item:first-child{padding-left:0}
.status-item:last-child{border-right:0}
.status-ico{
  width:38px;height:38px;border-radius:50%;
  display:grid;place-items:center;
  border:1px solid var(--dash-line-strong);
  color:var(--txt-soft);
}
.status-item strong{display:block;font-size:14px}
.status-item span{display:block;color:#35db82;font-size:13px}
.status-item:nth-child(2) span{color:#25d46b}
.status-item:nth-child(3) span{color:var(--txt-soft)}
.status-item:nth-child(4) span{color:#35db82}

@media (max-width:1500px){
  .dash{grid-template-columns:264px 1fr}
  .top-grid{grid-template-columns:repeat(2,1fr)}
  .main-grid{grid-template-columns:1fr}
}
@media (max-width:1024px){
  .dash{grid-template-columns:1fr}
  .main{padding:18px 16px 24px}
  .top-grid{grid-template-columns:1fr}
  .status-strip{grid-template-columns:1fr 1fr}
  .status-item{border-right:0;border-bottom:1px solid rgba(96,137,202,.16);padding:14px 0}
  .status-item:nth-last-child(-n+2){border-bottom:0}
}
@media (max-width:720px){
  .main-grid,.top-grid,.status-strip{grid-template-columns:1fr}
  .day-item{grid-template-columns:58px 34px 1fr;gap:9px}
  .day-item .chip{grid-column:3}
  .storage-layout{grid-template-columns:1fr}
  .ring{margin:auto}
}
</style>
@endpush

@section('content')
<section class="dashboard-shell">
  <div class="top-grid">
    <article class="clinical-card rise d2">
      <div class="card-head">
        <div class="title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
          Estudios en curso
        </div>
        <span class="count">2 activos</span>
      </div>
      <div class="study-list">
        <div class="study-item">
          <span class="icon-badge green">▣</span>
          <div><strong>Sala 1 - Colonoscopia</strong><span>Dr. Ricardo</span></div>
          <span class="study-time green">10:23 AM</span>
        </div>
        <div class="study-item">
          <span class="icon-badge orange">▣</span>
          <div><strong>Sala 2 - Endoscopia Alta</strong><span>Dra. Ana</span></div>
          <span class="study-time orange">10:18 AM</span>
        </div>
      </div>
      <a class="arrow-link" href="#">Ver todos en curso
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </article>

    <article class="clinical-card rise d3">
      <div class="card-head">
        <div class="title purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3Z"/><path d="M8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Z"/><path d="M2 19c0-2.2 2.7-4 6-4s6 1.8 6 4"/><path d="M10 19c0-2.2 2.7-4 6-4s6 1.8 6 4"/></svg>
          Ultimos hallazgos IA
        </div>
        <a class="soft-action" href="#">Ver todos</a>
      </div>
      <div class="finding-list">
        <div class="finding"><span class="icon-badge green">↻</span><div><strong>Gastritis antral</strong><span>Confianza: 92%</span><div class="bar green"><i></i></div></div></div>
        <div class="finding"><span class="icon-badge orange">Ω</span><div><strong>Polipo</strong><span>Confianza: 88%</span><div class="bar yellow"><i></i></div></div></div>
        <div class="finding"><span class="icon-badge red" style="color:#ff626b;background:rgba(255,90,110,.16)">Φ</span><div><strong>Esofagitis</strong><span>Confianza: 85%</span><div class="bar red"><i></i></div></div></div>
      </div>
    </article>

    <article class="clinical-card rise d4">
      <div class="card-head">
        <div class="title cyan">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.5 19H7a5 5 0 1 1 1.1-9.88A7 7 0 0 1 21 13a3 3 0 0 1-3.5 6Z"/></svg>
          Almacenamiento en nube
        </div>
      </div>
      <div class="storage-layout">
        <div class="storage-main"><b>1.8 TB</b> usados<br>de <strong>5 TB</strong> disponibles</div>
        <div class="ring"><svg viewBox="0 0 82 82"><circle class="track" cx="41" cy="41" r="36"/><circle class="value" cx="41" cy="41" r="36"/></svg><span>36%</span></div>
      </div>
      <div class="progress"><i></i></div>
      <a class="arrow-link" href="#">Gestionar almacenamiento
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </article>

    <article class="clinical-card month-stat rise d5">
      <div class="card-head">
        <div class="title purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          Este mes
        </div>
      </div>
      <div class="big">128</div>
      <div class="muted">Estudios realizados</div>
      <span class="trend">↑ 15% <span class="muted" style="margin-left:6px">vs mes anterior</span></span>
      <svg class="spark" viewBox="0 0 260 82" preserveAspectRatio="none"><path d="M2 66 C24 50,36 76,56 54 S91 48,110 46 S132 20,153 33 S180 20,199 34 S228 4,258 14"/></svg>
    </article>
  </div>

  <div class="main-grid">
    <article class="clinical-card rise d5">
      <div class="card-head">
        <div class="title cyan">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          Agenda del dia
        </div>
      </div>
      <div class="calendar-nav"><button>‹</button><span>JUNIO 2026</span><button>›</button></div>
      <div class="week">
        <div class="day">Lun<b>9</b></div><div class="day">Mar<b>10</b></div><div class="day">Mie<b>11</b></div><div class="day">Jue<b>12</b></div><div class="day active">Vie<b>13</b></div><div class="day">Sab<b>14</b></div><div class="day">Dom<b>15</b></div>
      </div>
      <div class="day-list">
        <div class="day-item"><span class="day-time">10:30 AM</span><span class="patient-avatar purple">♙</span><div class="day-copy"><strong>Maria Gonzalez</strong><span>Endoscopia diagnostica</span></div><span class="chip wait">En espera</span></div>
        <div class="day-item"><span class="day-time">11:15 AM</span><span class="patient-avatar blue">♙</span><div class="day-copy"><strong>Jorge Lopez</strong><span>Colonoscopia</span></div><span class="chip urgent">Urgente</span></div>
        <div class="day-item"><span class="day-time">12:00 PM</span><span class="patient-avatar cyan">♙</span><div class="day-copy"><strong>Ana Ramirez</strong><span>Endoscopia diagnostica</span></div><span class="chip done">Completado</span></div>
        <div class="day-item"><span class="day-time">12:45 PM</span><span class="patient-avatar orange">♙</span><div class="day-copy"><strong>Pedro Torres</strong><span>Gastroscopia</span></div><span class="chip wait">En espera</span></div>
      </div>
      <a class="arrow-link" href="#">Ver agenda completa
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </article>

    <article class="clinical-card rise d6">
      <div class="card-head">
        <div class="title purple">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Pacientes pendientes hoy
        </div>
        <a class="soft-action" href="#">Ver todos</a>
      </div>
      <div class="tbl-wrap">
        <table class="clinical-table tbl">
          <thead><tr><th>Paciente</th><th>Hora</th><th>Tipo de estudio</th><th>Estado</th><th>Medico</th><th></th></tr></thead>
          <tbody>
            <tr><td><span class="patient-cell"><span class="patient-mini">MG</span>Maria Gonzalez</span></td><td>10:30 AM</td><td>Endoscopia diagnostica</td><td><span class="chip wait">En espera</span></td><td>Dr. Ricardo</td><td><button class="dots">⋮</button></td></tr>
            <tr><td><span class="patient-cell"><span class="patient-mini blue">JL</span>Jorge Lopez</span></td><td>11:15 AM</td><td>Colonoscopia</td><td><span class="chip urgent">Urgente</span></td><td>Dr. Ricardo</td><td><button class="dots">⋮</button></td></tr>
            <tr><td><span class="patient-cell"><span class="patient-mini cyan">AR</span>Ana Ramirez</span></td><td>12:00 PM</td><td>Endoscopia diagnostica</td><td><span class="chip done">Completado</span></td><td>Dr. Ricardo</td><td><button class="dots">⋮</button></td></tr>
            <tr><td><span class="patient-cell"><span class="patient-mini red">PT</span>Pedro Torres</span></td><td>12:45 PM</td><td>Gastroscopia</td><td><span class="chip wait">En espera</span></td><td>Dr. Ricardo</td><td><button class="dots">⋮</button></td></tr>
            <tr><td><span class="patient-cell"><span class="patient-mini teal">LM</span>Luis Mendoza</span></td><td>02:00 PM</td><td>Colonoscopia</td><td><span class="chip wait">En espera</span></td><td>Dra. Ana</td><td><button class="dots">⋮</button></td></tr>
          </tbody>
        </table>
      </div>
      <a class="arrow-link" href="#">Ver todos los pacientes
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </a>
    </article>

    <div class="side-stack">
      <article class="clinical-card rise d6">
        <div class="card-head">
          <div class="title orange">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Actividad reciente
          </div>
          <a class="soft-action" href="#">Ver todas</a>
        </div>
        <div class="activity-list">
          <div class="activity-item"><span class="activity-ico green">✓</span><div class="activity-copy"><strong>Estudio completado</strong><span>Maria Gonzalez - Endoscopia diagnostica</span></div><span class="activity-time">10:30 AM</span></div>
          <div class="activity-item"><span class="activity-ico blue">▤</span><div class="activity-copy"><strong>Informe generado</strong><span>Jorge Lopez - Colonoscopia</span></div><span class="activity-time">10:28 AM</span></div>
          <div class="activity-item"><span class="activity-ico purple">▧</span><div class="activity-copy"><strong>Imagen agregada a estudio</strong><span>Ana Ramirez - Imagen #23</span></div><span class="activity-time">10:25 AM</span></div>
          <div class="activity-item"><span class="activity-ico yellow">▣</span><div class="activity-copy"><strong>Video exportado</strong><span>Pedro Torres - Colonoscopia</span></div><span class="activity-time">10:20 AM</span></div>
          <div class="activity-item"><span class="activity-ico pink">ψ</span><div class="activity-copy"><strong>Hallazgo IA detectado</strong><span>Luis Mendoza - Polipo (88%)</span></div><span class="activity-time">10:15 AM</span></div>
        </div>
      </article>

      <article class="clinical-card priority-card rise d7">
        <div class="card-head">
          <div class="title red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/><path d="M4 22a8 8 0 0 1 16 0"/></svg>
            Pacientes prioritarios
          </div>
          <a class="soft-action" href="#">Ver todos</a>
        </div>
        <div class="priority-list">
          <div class="priority-item"><span class="priority-ico">●</span><div class="priority-copy"><strong>Jorge Lopez</strong><span>Sangrado digestivo - Evaluacion urgente</span></div><span class="chip urgent">Urgente</span></div>
          <div class="priority-item"><span class="priority-ico">●</span><div class="priority-copy"><strong>Ana Ramirez</strong><span>Control postoperatorio - Prioridad alta</span></div><span class="chip wait">Alta</span></div>
        </div>
      </article>
    </div>
  </div>

  <footer class="status-strip rise d7">
    <div class="status-item"><span class="status-ico">✓</span><div><strong>Sistema</strong><span>Todos los sistemas operativos</span></div></div>
    <div class="status-item"><span class="status-ico">ψ</span><div><strong>IA ENCLAII</strong><span>Activa y analizando</span></div></div>
    <div class="status-item"><span class="status-ico">☁</span><div><strong>Sincronizacion</strong><span>Ultima: 10:28 AM</span></div></div>
    <div class="status-item"><span class="status-ico">⌁</span><div><strong>Conexion</strong><span>Excelente</span></div></div>
  </footer>
</section>
@endsection
