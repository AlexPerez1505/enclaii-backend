<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
  /* Aplicar tema guardado antes del primer render (evita parpadeo) */
  document.documentElement.dataset.theme = localStorage.getItem('enclaii-theme') || 'dark';
  /* Aplicar idioma guardado al atributo lang antes del primer render */
  document.documentElement.lang = localStorage.getItem('enclaii-lang') || 'es';
  /* Aplicar preferencias de apariencia antes del primer render (evita parpadeo) */
  (function(){
    var pref = function(k, def){ try { var v = localStorage.getItem('enclaii-pref-' + k); return v === null ? def : v; } catch (e) { return def; } };
    document.documentElement.dataset.animations = pref('animations', '1') === '0' ? 'off' : 'on';
    document.documentElement.dataset.compact = pref('compact', '0') === '1' ? 'on' : 'off';
    document.documentElement.dataset.reading = pref('reading_mode', '0') === '1' ? 'on' : 'off';
  })();
</script>
<title>@yield('title', 'ENCLAII') — ENCLAII</title>
@auth
<script>window.enclaiiSettings = @json(auth()->user()->resolvedSettings());</script>
@endauth
<script defer src="{{ asset('js/i18n.js') }}"></script>
<script defer src="{{ asset('js/preferences.js') }}"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ================= TOKENS Y BASE (compartido por toda la app) ================= */
:root{
  --bg:#06081C;
  --panel:#0A0F2E;
  --panel-2:#0D1438;
  --card:#0E1740;
  --stroke:rgba(110,160,255,.16);
  --stroke-strong:rgba(110,160,255,.35);
  --txt:#EAF1FF;
  --txt-soft:#8FA3CF;
  --blue:#2E7BF6;
  --cyan:#38C7F4;
  --orange:#F59E2D;
  --green:#3DDC97;
  --red:#FF5A6E;
  --side-2:#080C24;
  --off:#3D4A75;
  --card-bg:#0D1433;
  --card-bg-2:#161D3F;
  --input-bg:#111830;
  --hover-bg:rgba(46,123,246,.07);
  --hover-bg-strong:rgba(46,123,246,.14);
  --modal-bg:linear-gradient(180deg,#0F172A 0%,#0B1126 100%);
  --shadow:rgba(0,0,0,.45);
  --r-lg:18px;
  --r-md:12px;
  --ease-out:cubic-bezier(0.23, 1, 0.32, 1);
  --ease-in-out:cubic-bezier(0.77, 0, 0.175, 1);
}

/* ================= TEMA CLARO ================= */
html[data-theme="light"]{
  --bg:#EEF2FB;
  --panel:#FFFFFF;
  --panel-2:#F6F8FE;
  --card:#FFFFFF;
  --stroke:rgba(20,50,120,.12);
  --stroke-strong:rgba(20,50,120,.28);
  --txt:#0E1530;
  --txt-soft:#5B6A99;
  --side-2:#F4F7FE;
  --off:#C2CCE8;
  --card-bg:#FFFFFF;
  --card-bg-2:#F6F8FE;
  --input-bg:#FFFFFF;
  --hover-bg:rgba(46,123,246,.08);
  --hover-bg-strong:rgba(46,123,246,.14);
  --modal-bg:linear-gradient(180deg,#FFFFFF 0%,#F6F8FE 100%);
  --shadow:rgba(20,50,120,.12);
}
html[data-theme="light"] .side-brand img{filter:none}
html[data-theme="light"] .nav-item.active{color:#fff}
html[data-theme="light"] .side-help .orb{box-shadow:0 0 18px rgba(46,123,246,.3)}
html[data-theme="light"] .bell .dot{color:#fff}

html[data-theme="light"] .nav-item:hover{background:rgba(46,123,246,.08)}
html[data-theme="light"] .nav-item.active{
  background:linear-gradient(135deg,#2563eb,var(--blue));
  box-shadow:0 8px 22px -8px rgba(46,123,246,.35);
}
html[data-theme="light"] .btn-ai{background:rgba(46,123,246,.12)}
html[data-theme="light"] .btn-ai:hover{background:rgba(46,123,246,.2)}
html[data-theme="light"] .profile-menu{box-shadow:0 18px 44px rgba(20,50,120,.18)}
html[data-theme="light"] .chip.wait{background:rgba(245,158,45,.12);border-color:rgba(245,158,45,.45)}
html[data-theme="light"] .chip.urgent{background:rgba(255,90,110,.12);border-color:rgba(255,90,110,.45)}
html[data-theme="light"] .chip.done{background:rgba(61,220,151,.12);border-color:rgba(61,220,151,.45)}
html[data-theme="light"] .btn-line:hover{background:rgba(46,123,246,.1)}
html[data-theme="light"] .pm-ico{background:rgba(56,199,244,.12)}
html[data-theme="light"] .pm-item.danger .pm-ico{background:rgba(255,90,110,.12)}
html[data-theme="light"] .side-help .orb{box-shadow:0 0 24px rgba(46,123,246,.25)}
html[data-theme="light"] .side-help .btn-ghost:hover{background:rgba(46,123,246,.1)}
html[data-theme="light"] .page-title small{color:var(--txt-soft)}
*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%;width:100%;min-height:100%}
body{
  font-family:'Hanken Grotesk',sans-serif;
  background:var(--bg);
  color:var(--txt);
  -webkit-font-smoothing:antialiased;
}
button{font:inherit;color:inherit;background:none;border:0;cursor:pointer}
a{color:inherit;text-decoration:none}
.muted{color:var(--txt-soft)}

/* ===== Preferencia: Animaciones y transiciones desactivadas ===== */
html[data-animations="off"] *,
html[data-animations="off"] *::before,
html[data-animations="off"] *::after{
  animation-duration:.001s !important;
  animation-delay:0s !important;
  transition-duration:.001s !important;
  transition-delay:0s !important;
  scroll-behavior:auto !important;
}

/* ===== Preferencia: Modo compacto (mayor densidad de información) ===== */
html[data-compact="on"] body{font-size:93%}
html[data-compact="on"] .card{padding:13px 15px}

/* ===== Preferencia: Modo lectura (filtro amarillo anti-fatiga visual) ===== */
html[data-reading="on"]::after{
  content:"";
  position:fixed;
  inset:0;
  background:rgba(255,201,71,.16);
  pointer-events:none;
  z-index:2147483647;
}

/* ================= LAYOUT ================= */
.dash{
  display:grid;
  grid-template-columns:264px 1fr;
  min-height:100vh;
  align-items:start;
}
.side{
  align-self:stretch;
}
.main{padding:28px 30px 36px;min-width:0;max-width:100%}

/* ================= SIDEBAR ================= */
.side{
  background:linear-gradient(180deg,var(--panel) 0%,var(--side-2) 100%);
  border-right:1px solid var(--stroke);
  padding:26px 18px;
  display:flex;
  flex-direction:column;
  gap:6px;
  position:sticky;
  top:0;
  height:100vh;
  overflow-y:auto;
}
.side-brand{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:6px;
  margin-bottom:26px;
}
.side-brand img{width:96px;height:auto;margin-bottom:-12px;filter:drop-shadow(0 0 18px rgba(56,199,244,.35))}
.side-brand-name{
  font-family:'Sora',sans-serif;
  font-weight:800;
  font-size:19px;
  letter-spacing:.38em;
}
.side-brand-name span{color:var(--blue)}
.side-brand-tag{
  font-size:7px;
  font-weight:600;
  letter-spacing:.26em;
  text-transform:uppercase;
  color:var(--txt-soft);
  white-space:nowrap;
}
.nav-item{
  display:flex;
  align-items:center;
  gap:14px;
  width:100%;
  padding:13px 16px;
  border-radius:var(--r-md);
  font-size:15px;
  font-weight:600;
  color:var(--txt-soft);
  transition:color 150ms ease, background-color 150ms ease, transform 160ms var(--ease-out);
}
.nav-item svg{width:20px;height:20px;flex:none}
.nav-item:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .nav-item:hover{color:var(--txt);background:rgba(110,160,255,.08)}
}
.nav-item.active{
  color:#fff;
  background:linear-gradient(135deg,#1668D9,var(--blue));
  box-shadow:0 8px 22px -8px rgba(46,123,246,.6);
}
.side-help{
  margin-top:auto;
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-lg);
  padding:18px 16px;
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:4px;
  text-align:center;
}
.side-help .orb{
  width:46px;height:46px;
  border-radius:50%;
  display:grid;place-items:center;
  background:radial-gradient(circle at 30% 30%, rgba(56,199,244,.5), rgba(46,123,246,.15));
  box-shadow:0 0 24px rgba(56,199,244,.45);
  margin-bottom:6px;
}
.side-help strong{font-size:14px}
.side-help span{font-size:12px;color:var(--txt-soft)}
.side-help .btn-ghost{
  margin-top:10px;
  padding:8px 16px;
  border-radius:99px;
  border:1px solid var(--stroke-strong);
  font-size:12.5px;
  font-weight:600;
  transition:background-color 150ms ease, transform 160ms var(--ease-out);
}
.side-help .btn-ghost:active{transform:scale(.96)}
@media (hover:hover) and (pointer:fine){
  .side-help .btn-ghost:hover{background:rgba(110,160,255,.1)}
}

/* ================= HEADER ================= */
.head{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:18px;
  margin-bottom:24px;
  flex-wrap:wrap;
  position:relative;
  z-index:1000;
}
.profile-wrap.open{z-index:2000}
.head h1{
  font-family:'Sora',sans-serif;
  font-size:26px;
  font-weight:700;
  letter-spacing:-0.01em;
}
.head .sub{margin-top:4px;font-size:14.5px;color:var(--txt-soft)}
.head .sub b{color:var(--cyan);font-weight:700}
.head-right{display:flex;align-items:center;gap:14px}
.btn-ai{
  display:flex;align-items:center;gap:10px;
  padding:11px 18px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:rgba(46,123,246,.1);
  font-weight:700;
  font-size:14.5px;
  color:var(--cyan);
  transition:background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-ai:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .btn-ai:hover{background:rgba(46,123,246,.2)}
}
.bell{
  position:relative;
  width:44px;height:44px;
  display:grid;place-items:center;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
  transition:transform 160ms var(--ease-out);
}
.bell:active{transform:scale(.94)}
.bell .dot{
  position:absolute;
  top:-6px;right:-6px;
  min-width:20px;height:20px;
  padding:0 5px;
  border-radius:99px;
  background:var(--blue);
  font-size:11.5px;
  font-weight:700;
  display:grid;place-items:center;
  box-shadow:0 0 0 3px var(--bg);
}
.profile{
  display:flex;align-items:center;gap:12px;
  padding:8px 16px 8px 8px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke);
  background:var(--panel-2);
}
.profile .avatar{
  width:38px;height:38px;
  border-radius:50%;
  background:linear-gradient(135deg,var(--blue),var(--cyan));
  display:grid;place-items:center;
  font-family:'Sora',sans-serif;
  font-weight:700;
  font-size:14px;
}
.profile strong{display:block;font-size:14px;line-height:1.2}
.profile span{font-size:11.5px;color:var(--txt-soft)}

/* ===== Menú desplegable del perfil ===== */
.profile-wrap{position:relative}
.profile{cursor:pointer;font:inherit;color:inherit;text-align:left}
.profile-meta{display:flex;flex-direction:column}
.profile-caret{color:var(--txt-soft);transition:transform .2s}
.profile-wrap.open .profile-caret{transform:rotate(180deg)}

.profile-menu{
  position:absolute;top:calc(100% + 10px);right:0;width:300px;max-width:88vw;
  background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-md);
  box-shadow:0 18px 44px rgba(0,0,0,.42);padding:8px;z-index:60;
  opacity:0;visibility:hidden;transform:translateY(-8px) scale(.98);transform-origin:top right;
  transition:opacity .16s var(--ease-out),transform .16s var(--ease-out),visibility .16s;
}
.profile-menu.open{opacity:1;visibility:visible;transform:translateY(0) scale(1)}
.pm-head{padding:8px 10px 12px;margin-bottom:4px;border-bottom:1px solid var(--stroke)}
.pm-head strong{display:block;font-family:'Sora',sans-serif;font-size:14px;font-weight:700}
.pm-head span{font-size:11.5px;color:var(--txt-soft)}
.pm-item{display:flex;align-items:center;gap:12px;width:100%;padding:10px;border-radius:10px;text-align:left;background:none;border:0;cursor:pointer;font:inherit;color:var(--txt);transition:background-color .15s}
.pm-item:hover{background:var(--panel-2)}
.pm-ico{width:34px;height:34px;flex:none;border-radius:9px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1)}
.pm-ico svg{width:17px;height:17px}
.pm-txt{display:flex;flex-direction:column;min-width:0}
.pm-txt .t{font-size:13px;font-weight:600}
.pm-txt .d{font-size:11px;color:var(--txt-soft);margin-top:1px}
.pm-sep{height:1px;background:var(--stroke);margin:6px 4px}
.pm-item.danger .pm-ico{color:var(--red);background:rgba(255,90,110,.1)}
.pm-item.danger .t{color:var(--red)}

/* ================= COMPONENTES COMPARTIDOS ================= */
.card{
  background:linear-gradient(180deg,var(--card),var(--panel-2));
  border:1px solid var(--stroke);
  border-radius:var(--r-lg);
  padding:20px;
}
.card h3{
  font-family:'Sora',sans-serif;
  font-size:13px;
  font-weight:600;
  letter-spacing:.06em;
  margin-bottom:14px;
}
.chip{
  display:inline-block;
  padding:4px 10px;
  border-radius:7px;
  font-size:11.5px;
  font-weight:700;
  letter-spacing:.02em;
}
.chip.wait{color:var(--orange);border:1px solid rgba(245,158,45,.55);background:rgba(245,158,45,.1)}
.chip.urgent{color:var(--red);border:1px solid rgba(255,90,110,.55);background:rgba(255,90,110,.1)}
.chip.done{color:var(--green);border:1px solid rgba(61,220,151,.55);background:rgba(61,220,151,.1)}
.btn-line{
  display:inline-flex;align-items:center;gap:8px;
  padding:10px 16px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  font-size:13.5px;
  font-weight:700;
  color:var(--cyan);
  transition:background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-line:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .btn-line:hover{background:rgba(56,199,244,.1)}
}
.tbl-link{
  display:flex;align-items:center;justify-content:center;gap:8px;
  margin-top:14px;
  font-size:14px;
  font-weight:700;
  color:var(--blue);
}
@media (hover:hover) and (pointer:fine){
  .tbl-link:hover{color:var(--cyan)}
}

/* Entrada escalonada (disponible para todas las páginas) */
.rise{
  opacity:0;
  transform:translateY(14px);
  animation:rise 500ms var(--ease-out) forwards;
}
.d1{animation-delay:0ms}.d2{animation-delay:60ms}.d3{animation-delay:120ms}
.d4{animation-delay:180ms}.d5{animation-delay:240ms}.d6{animation-delay:300ms}
.d7{animation-delay:360ms}
@keyframes rise{to{opacity:1;transform:translateY(0)}}

/* ================= BOTTOM NAV (móvil) ================= */
.mobile-nav{
  display:none;
  position:fixed;
  bottom:0;left:0;right:0;
  background:var(--panel);
  border-top:1px solid var(--stroke);
  z-index:500;
  padding:6px 0 env(safe-area-inset-bottom, 6px);
  justify-content:space-around;
  align-items:center;
}
.mobile-nav-item{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:3px;
  padding:6px 12px;
  border-radius:var(--r-md);
  color:var(--txt-soft);
  font-size:10px;
  font-weight:600;
  transition:color 150ms ease;
  min-width:52px;
}
.mobile-nav-item svg{width:22px;height:22px;flex:none}
.mobile-nav-item.active{color:var(--cyan)}
.mobile-nav-item:active{transform:scale(.93)}

/* ================= RESPONSIVE BASE ================= */
@media (max-width:1024px){
  .dash{grid-template-columns:1fr}
  .side{
    position:static;height:auto;
    flex-direction:row;align-items:center;
    overflow-x:auto;gap:8px;
    padding:14px 16px;
    scrollbar-width:none;
  }
  .side::-webkit-scrollbar{display:none}
  .side-brand{flex-direction:row;margin-bottom:0;gap:10px;flex:none}
  .side-brand img{width:42px;margin-bottom:0}
  .side-brand-tag{display:none}
  .side-brand-name{font-size:13px;letter-spacing:.15em}
  .nav-item{flex:none;padding:8px 12px;font-size:13px}
  .side-help{display:none}
  .main{padding:16px 14px 28px}
  .head h1{font-size:20px}
  .head .sub{font-size:13px}
  .profile strong,.profile span{display:none}
  .profile{padding:8px}
}
@media (max-width:720px){
  .btn-ai span{display:none}
  .btn-ai{padding:10px 12px}
  .head{gap:10px;margin-bottom:16px}
  .head h1{font-size:18px}
  .head .sub{font-size:13px}
  .head-right{gap:8px}
}
@media (max-width:480px){
  /* Sidebar: solo iconos, sin texto */
  .nav-item{font-size:0;padding:10px 12px;gap:0;min-width:40px;justify-content:center}
  .nav-item svg{width:20px;height:20px;flex:none}
  .side-brand-name,.side-brand-tag{display:none}
  .side-brand img{width:36px}
  .side{padding:10px 12px;gap:4px}
  /* Header compacto: título arriba, acciones abajo */
  .head{flex-direction:column;align-items:flex-start;gap:6px;margin-bottom:14px}
  .head h1{font-size:17px}
  .head .sub{font-size:12px}
  .head-right{width:100%;justify-content:flex-end;gap:8px}
  .btn-ai{display:none}
  .main{padding:12px 12px 24px}
}
@media (max-width:600px){
  .side{display:none}
  .mobile-nav{display:flex}
  .dash{min-height:unset}
  .main{padding:14px 12px calc(70px + env(safe-area-inset-bottom, 0px)) 12px}
  .head h1{font-size:18px}
  .head .sub{font-size:12px}
  .head-right{gap:8px}
  .btn-ai{padding:9px 12px;font-size:13px}
  .bell{width:38px;height:38px}
  .profile{padding:6px}
  .head{margin-bottom:16px}
}

/* Toggle de tema: luna en modo oscuro (ir a claro: sol), y viceversa */
#themeToggle .icon-moon{display:none}
#themeToggle .icon-sun{display:block}
html[data-theme="light"] #themeToggle .icon-sun{display:none}
html[data-theme="light"] #themeToggle .icon-moon{display:block}

/* Reduced motion */
@media (prefers-reduced-motion: reduce){
  .rise{animation:fade 250ms ease forwards;transform:none}
  @keyframes fade{to{opacity:1}}
}
</style>
@stack('styles')
</head>
<body>

@php
  // Página activa del menú: cada vista la declara con @section('active', 'nombre')
  $active = trim($__env->yieldContent('active'));
@endphp

<div class="dash">

  {{-- ============ SIDEBAR (compartido) ============ --}}
  <aside class="side">
    <div class="side-brand">
      <img src="{{ asset('images/logo.png') }}" alt="Logotipo ENCLAII">
      <div>
        <div class="side-brand-name">ENCLA<span>II</span></div>
        <div class="side-brand-tag">Endoscopia · Nube · IA</div>
      </div>
    </div>

    <a class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ url('/dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
      Dashboard
    </a>
    <a class="nav-item {{ $active === 'agenda' ? 'active' : '' }}" href="{{ route('agenda') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Agenda
    </a>
    <a class="nav-item {{ $active === 'pacientes' ? 'active' : '' }}" href="{{ route('pacientes') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Pacientes
    </a>
    <a class="nav-item {{ $active === 'ia-reportes' ? 'active' : '' }}" href="{{ url('/ia-reportes') }}">
<<<<<<< HEAD
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
=======
      <x-hugeicons-ai-folder-02 />
>>>>>>> d75dde5 (implementacion del informe normal)
      Reportes
    </a>
    <a class="nav-item {{ $active === 'mensajes' ? 'active' : '' }}" href="{{ route('mensajes') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Mensajes
    </a>
    <a class="nav-item {{ $active === 'nuevo-estudio' ? 'active' : '' }}" href="{{ route('nuevo-estudio') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
      Nuevo estudio
    </a>
    <a class="nav-item {{ $active === 'galeria' ? 'active' : '' }}" href="{{ route('galeria') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
      Galería
    </a>
    <a class="nav-item {{ $active === 'configuracion' ? 'active' : '' }}" href="{{ url('/configuracion') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
      Configuración
    </a>

    <div class="side-help">
      <div class="orb">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
      </div>
      <strong>¿Necesitas ayuda?</strong>
      <span>Soporte 24/7</span>
      <button class="btn-ghost">Contactar soporte</button>
    </div>
  </aside>

  {{-- ============ MAIN ============ --}}
  <main class="main">

    <header class="head rise d1">
      <div>
        <h1>@yield('header-title', 'Panel')</h1>
        @hasSection('header-sub')
          <p class="sub">@yield('header-sub')</p>
        @endif
      </div>
      <div class="head-right">
        @yield('header-extra')
        <button class="btn-ai">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/></svg>
          <span>Asistente IA</span>
        </button>
        <button class="bell" id="themeToggle" aria-label="Cambiar tema">
          <svg class="icon-moon" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
          <svg class="icon-sun" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
        <button class="bell" aria-label="Notificaciones">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
          <span class="dot">3</span>
        </button>
        <div class="profile-wrap">
          <button type="button" class="profile" id="profileBtn" aria-haspopup="true" aria-expanded="false">
            <div class="avatar">DV</div>
            <div class="profile-meta">
              <strong>Dr. Victor</strong>
              <span>Endoscopista</span>
            </div>
            <svg class="profile-caret" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>

          <div class="profile-menu" id="profileMenu" role="menu">
            <div class="pm-head"><strong>Acciones rápidas</strong><span>Acciones y herramientas</span></div>

            <a href="{{ route('configuracion') }}" class="pm-item" role="menuitem">
              <span class="pm-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
              <span class="pm-txt"><span class="t">Editar perfil</span><span class="d">Actualiza tu información personal</span></span>
            </a>
            <a href="#" class="pm-item" role="menuitem">
              <span class="pm-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></span>
              <span class="pm-txt"><span class="t">Exportar mis datos</span><span class="d">Descargar una copia tus datos</span></span>
            </a>
            <a href="#" class="pm-item" role="menuitem">
              <span class="pm-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></span>
              <span class="pm-txt"><span class="t">Importar mi configuración</span><span class="d">Importar configuración desde un archivo</span></span>
            </a>
            <a href="#" class="pm-item" role="menuitem">
              <span class="pm-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.5 15a9 9 0 1 0 .5-8L1 10"/></svg></span>
              <span class="pm-txt"><span class="t">Restablecer configuración</span><span class="d">Restaurar configuración predeterminada</span></span>
            </a>
            <div class="pm-sep"></div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="pm-item danger" role="menuitem">
                <span class="pm-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
                <span class="pm-txt"><span class="t">Cerrar sesión</span><span class="d">Cerrar sesión en tu cuenta actual</span></span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    @yield('content')

  </main>

  {{-- Bottom nav para móvil --}}
  <nav class="mobile-nav">
    <a class="mobile-nav-item {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ url('/dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
      Inicio
    </a>
    <a class="mobile-nav-item {{ $active === 'pacientes' ? 'active' : '' }}" href="{{ route('pacientes') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Pacientes
    </a>
    <a class="mobile-nav-item {{ $active === 'agenda' ? 'active' : '' }}" href="#">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Agenda
    </a>
    <a class="mobile-nav-item {{ $active === 'informes' ? 'active' : '' }}" href="#">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
      Informes
    </a>
    <a class="mobile-nav-item {{ $active === 'configuracion' ? 'active' : '' }}" href="#">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
      Config
    </a>
  </nav>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
  /* Cambiar tema y recordarlo */
  document.getElementById('themeToggle').addEventListener('click', () => {
    const html = document.documentElement;
    const next = html.dataset.theme === 'light' ? 'dark' : 'light';
    html.dataset.theme = next;
    localStorage.setItem('enclaii-theme', next);
  });

  /* Menú desplegable del perfil */
  (function(){
    const wrap = document.querySelector('.profile-wrap');
    const btn = document.getElementById('profileBtn');
    const menu = document.getElementById('profileMenu');
    if (!wrap || !btn || !menu) return;
    const close = () => { wrap.classList.remove('open'); menu.classList.remove('open'); btn.setAttribute('aria-expanded','false'); };
    btn.addEventListener('click', e => {
      e.stopPropagation();
      const isOpen = menu.classList.toggle('open');
      wrap.classList.toggle('open', isOpen);
      btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
    document.addEventListener('click', e => { if (!wrap.contains(e.target)) close(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
  })();
</script>
@stack('scripts')
</body>
</html>