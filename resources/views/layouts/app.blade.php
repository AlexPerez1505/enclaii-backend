<!DOCTYPE html>
<html lang="es" class="notranslate" translate="no">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="google" content="notranslate">
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=20260627-2">
<script>
  /* Aplicar tema guardado antes del primer render (evita parpadeo) */
  document.documentElement.dataset.theme = localStorage.getItem('enclaii-theme') || 'dark';
  /* Aplicar idioma guardado al atributo lang antes del primer render */
  document.documentElement.lang = localStorage.getItem('enclaii-lang') || 'es';
  /* El selector interno debe funcionar aunque otro script de la página falle. */
  window.enclaiiSetLanguage = function (lang) {
    lang = lang === 'en' ? 'en' : 'es';
    try { localStorage.setItem('enclaii-lang', lang); } catch (e) {}
    document.documentElement.lang = lang;
    window.location.reload();
  };
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
<script>
window.enclaiiSettings = @json(array_merge(auth()->user()->resolvedSettings(), ['user_id' => auth()->id()]));
</script>
@endauth
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap"></noscript>
<script defer src="{{ asset('js/i18n.js') }}?v=20260702-1"></script>
<script defer src="{{ asset('js/preferences.js') }}"></script>
@vite(['resources/js/app.js'])
<style>
/* ================= TOKENS Y BASE (compartido por toda la app) ================= */
:root{
  --bg:#06081C;
  --panel:#0A0F2E;
  --panel-2:#0D1438;
  --card:#0E1740;
  --stroke:rgba(110,160,255,.12);
  --stroke-strong:rgba(110,160,255,.25);
  --txt:#EAF1FF;
  --txt-soft:#8FA3CF;
  --blue:#3B82F6;
  --cyan:#0EA5E9;
  --orange:#F59E2D;
  --green:#10B981;
  --red:#EF4444;
  --side-2:#080C24;
  --off:#3D4A75;
  --card-bg:#0D1433;
  --card-bg-2:#161D3F;
  --input-bg:#111830;
  --hover-bg:rgba(59,130,246,.1);
  --hover-bg-strong:rgba(59,130,246,.2);
  --modal-bg:linear-gradient(180deg,#0F172A 0%,#0B1126 100%);
  --shadow:rgba(0,0,0,.45);
  --r-lg:18px;
  --r-md:12px;
  --ease-out:cubic-bezier(0.23, 1, 0.32, 1);
  --ease-in-out:cubic-bezier(0.77, 0, 0.175, 1);
}

/* ================= TEMA CLARO (DISEÑO PRO, LIMPIO Y PURO BLANCO) ================= */
html[data-theme="light"]{
  --bg:#F8FAFC; 
  --panel:#FFFFFF; 
  --panel-2:#FFFFFF; 
  --card:#FFFFFF;
  --stroke:rgba(0,0,0,.06); 
  --stroke-strong:rgba(0,0,0,.12);
  --txt:#0F172A; 
  --txt-soft:#64748B; 
  --side-2:#FFFFFF; 
  --off:#CBD5E1;
  --card-bg:#FFFFFF;
  --card-bg-2:#FFFFFF;
  --input-bg:#F1F5F9; 
  --hover-bg:#EFF6FF; 
  --hover-bg-strong:#DBEAFE;
  --modal-bg:#FFFFFF;
  --shadow:rgba(15, 23, 42, 0.05);
}
html[data-theme="light"] .side-brand img{filter:none}
html[data-theme="light"] .nav-item.active{color:#fff}
html[data-theme="light"] .side-help .orb{box-shadow:0 0 16px rgba(59,130,246,.15)}
html[data-theme="light"] .bell .dot{color:#fff; box-shadow:0 0 0 3px #FFFFFF;}

html[data-theme="light"] .nav-item:hover{
  background:var(--hover-bg);
  color: #1E293B; 
}
html[data-theme="light"] .nav-item:hover svg{
  color: #2563EB; 
}
html[data-theme="light"] .nav-item.active{
  background:linear-gradient(135deg,#3B82F6,#1D4ED8); 
  box-shadow:0 6px 16px -4px rgba(37,99,235,.4); 
}
html[data-theme="light"] .btn-ai{background:var(--hover-bg); color: #2563EB; border-color: transparent;}
html[data-theme="light"] .btn-ai:hover{background:var(--hover-bg-strong);}
html[data-theme="light"] .profile-menu{box-shadow:0 20px 40px -10px rgba(15,23,42,.12); border:1px solid var(--stroke);}
html[data-theme="light"] .chip.wait{background:#FEF3C7; color:#D97706; border-color:transparent;}
html[data-theme="light"] .chip.urgent{background:#FEE2E2; color:#DC2626; border-color:transparent;}
html[data-theme="light"] .chip.done{background:#D1FAE5; color:#059669; border-color:transparent;}
html[data-theme="light"] .btn-line:hover{background:var(--hover-bg);}
html[data-theme="light"] .pm-ico{background:#F0F9FF; color:#0EA5E9;}
html[data-theme="light"] .pm-item.danger .pm-ico{background:#FEF2F2; color:#EF4444;}
html[data-theme="light"] .side-help .orb{box-shadow:0 0 20px rgba(59,130,246,.1)}
html[data-theme="light"] .side-help .btn-ghost:hover{background:var(--hover-bg)}
html[data-theme="light"] .page-title small{color:var(--txt-soft)}

*{margin:0;padding:0;box-sizing:border-box}
html{font-size:clamp(13px, 0.875rem + 0.2vw, 16px)}
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
  grid-template-columns:240px 1fr; 
  min-height:100vh;
  align-items:start;
  transition:grid-template-columns .24s var(--ease-out);
}
.side{
  align-self:stretch;
}
.main{padding:28px 30px 36px;min-width:0;max-width:100%}
.app-alert{display:flex;align-items:center;gap:9px;margin:0 0 16px;padding:11px 14px;border-radius:11px;border:1px solid rgba(245,158,45,.32);background:rgba(245,158,45,.1);color:var(--txt);font-size:12.5px;font-weight:700}
.app-alert svg{width:16px;height:16px;flex:none;color:var(--orange)}

/* ================= SIDEBAR ================= */
.side{
  background:linear-gradient(180deg,var(--panel) 0%,var(--side-2) 100%);
  border-right:1px solid var(--stroke);
  padding:24px 16px;
  display:flex;
  flex-direction:column;
  gap:6px;
  position:sticky;
  top:0;
  height:100vh;
  overflow-y:auto;
  scrollbar-width: none; 
}
.side::-webkit-scrollbar {
  display: none; 
}
.side-brand{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:6px;
  margin-bottom:0;
}
.side-brand img{
  width:64px; 
  height:64px;
  object-fit:contain;
  margin-bottom:2px;
  filter:drop-shadow(0 0 16px rgba(56,199,244,.15));
}
.side-brand .logo-dark{display:block;}
.side-brand .logo-light{display:none;}
html[data-theme="light"] .side-brand .logo-dark{display:none;}
html[data-theme="light"] .side-brand .logo-light{display:block;}
html[data-theme="light"] .side-brand img {filter:none;} 

.side-brand-name{
  font-family:'Sora',sans-serif;
  font-weight:800;
  font-size:17px; 
  letter-spacing:.38em;
}
.side-brand-name span{color:var(--blue)}
.side-brand-tag{
  font-size:7px;
  font-weight:700;
  letter-spacing:.26em;
  text-transform:uppercase;
  color:var(--txt-soft);
  white-space:nowrap;
}
.nav-item{
  display:flex;
  align-items:center;
  gap:12px;
  width:100%;
  padding:10px 14px; 
  border-radius:10px; 
  font-size:13.5px; 
  font-weight:600;
  color:var(--txt-soft);
  transition:all 150ms ease;
}
.nav-item svg{width:18px;height:18px;flex:none; transition:color 150ms ease;} 
.nav-item:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .nav-item:hover{color:var(--txt);background:rgba(110,160,255,.08)}
}
.nav-item.active{
  color:#fff;
  background:linear-gradient(135deg,#1668D9,var(--blue));
  box-shadow:0 6px 16px -4px rgba(46,123,246,.4);
}
.nav-item.active svg { color: #fff; }

.side-help{
  margin-top:auto;
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:16px;
  padding:16px 14px; 
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:4px;
  text-align:center;
}
.side-help .orb{
  width:38px;height:38px; 
  border-radius:50%;
  display:grid;place-items:center;
  background:radial-gradient(circle at 30% 30%, rgba(56,199,244,.5), rgba(46,123,246,.15));
  box-shadow:0 0 20px rgba(56,199,244,.35);
  margin-bottom:6px;
}
.side-help .orb svg { width: 18px; height: 18px; }
html[data-theme="light"] .side-help { background: var(--bg); border:none; } 
.side-help strong{font-size:13px} 
.side-help span{font-size:11.5px;color:var(--txt-soft)}
.side-help .btn-ghost{
  margin-top:10px;
  padding:8px 14px;
  border-radius:99px;
  border:1px solid var(--stroke-strong);
  font-size:12px; 
  font-weight:700;
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
  margin-bottom:28px;
  flex-wrap:wrap;
  position:relative;
  z-index:1000;
}
.profile-wrap.open{z-index:2000}
.head > div:first-child{flex:1;min-width:0}
.head h1{
  font-family:'Sora',sans-serif;
  font-size:24px; 
  font-weight:700;
  letter-spacing:-0.01em;
  overflow:hidden;
  text-overflow:ellipsis;
  white-space:nowrap;
}
.head .sub{margin-top:4px;font-size:14px;color:var(--txt-soft);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:600px}
.head .sub b{color:var(--cyan);font-weight:700}
.head-right{display:flex;align-items:center;gap:12px;flex-shrink:0}
.btn-ai{
  display:flex;align-items:center;gap:8px;
  padding:10px 16px;
  border-radius:var(--r-md);
  border:1px solid var(--stroke-strong);
  background:rgba(46,123,246,.1);
  font-weight:700;
  font-size:13.5px;
  color:var(--cyan);
  transition:background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-ai svg { width: 17px; height: 17px; }
.btn-ai:active{transform:scale(.97)}
@media (hover:hover) and (pointer:fine){
  .btn-ai:hover{background:rgba(46,123,246,.2)}
}
.bell{
  position:relative;
  width:42px;height:42px;
  display:grid;place-items:center;
  border-radius:10px;
  border:1px solid var(--stroke);
  background:var(--panel-2);
  color: var(--txt-soft);
  transition:transform 160ms var(--ease-out), color 150ms ease;
}
.bell svg { width: 18px; height: 18px; }
.bell:hover { color: var(--txt); }
.bell:active{transform:scale(.94)}
.bell .dot{
  position:absolute;
  top:-4px;right:-4px;
  min-width:20px;height:20px;
  padding:0 5px;
  border-radius:99px;
  background:var(--red);
  color: #fff;
  font-size:11px;
  font-weight:800;
  display:grid;place-items:center;
  box-shadow:0 0 0 3px var(--bg);
}

/* ===== Panel de notificaciones ===== */
.notif-wrap{position:relative}
.notif-panel{
  position:absolute;top:calc(100% + 10px);right:0;width:320px;max-width:90vw;
  background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-lg);
  box-shadow:0 24px 48px rgba(0,0,0,.42);z-index:2100;
  opacity:0;visibility:hidden;transform:translateY(-10px) scale(.98);transform-origin:top right;
  transition:opacity .2s var(--ease-out),transform .2s var(--ease-out),visibility .2s;
  overflow:hidden;
}
.notif-panel.open{opacity:1;visibility:visible;transform:translateY(0) scale(1)}
.notif-panel-head{
  display:flex;align-items:center;justify-content:space-between;
  padding:14px 16px 10px;border-bottom:1px solid var(--stroke);
}
.notif-panel-title{font-family:'Sora',sans-serif;font-size:13.5px;font-weight:700;color:var(--txt)}
.notif-clear-btn{
  font-size:11.5px;font-weight:700;color:var(--txt-soft);
  padding:4px 10px;border-radius:99px;
  transition:background .15s,color .15s;
}
.notif-clear-btn:hover{background:var(--hover-bg);color:var(--blue)}
.notif-list{max-height:340px;overflow-y:auto}
.notif-empty{
  padding:28px 16px;text-align:center;
  font-size:13px;color:var(--txt-soft);
}
.notif-item{
  display:flex;align-items:flex-start;gap:12px;
  padding:12px 16px;border-bottom:1px solid var(--stroke);
  animation:notif-in .25s var(--ease-out);
  position:relative;transition:background .15s ease;
}
.notif-item:not(.read){background:linear-gradient(90deg, rgba(59,130,246,.10) 0%, rgba(59,130,246,.04) 60%, transparent 100%);border-left:3px solid var(--blue)}
.notif-item:not(.read):hover{background:linear-gradient(90deg, rgba(59,130,246,.16) 0%, rgba(59,130,246,.07) 60%, transparent 100%)}
.notif-item.read{background:linear-gradient(90deg, rgba(255,255,255,.04) 0%, rgba(255,255,255,.02) 60%, transparent 100%);border-left:3px solid transparent;opacity:.72}
.notif-item.read:hover{background:linear-gradient(90deg, rgba(255,255,255,.08) 0%, rgba(255,255,255,.04) 60%, transparent 100%)}
.notif-item:last-child{border-bottom:0}
.notif-item::before{
  content:'';position:absolute;left:5px;top:50%;transform:translateY(-50%);
  width:6px;height:6px;border-radius:50%;background:var(--blue);
  box-shadow:0 0 0 2px rgba(59,130,246,.25);opacity:0;transition:opacity .15s ease
}
.notif-item:not(.read)::before{opacity:1}
@keyframes notif-in{from{opacity:0;transform:translateX(8px)}to{opacity:1;transform:none}}
.notif-ico{
  width:34px;height:34px;flex:none;border-radius:10px;
  display:grid;place-items:center;
  background:rgba(59,130,246,.15);color:var(--blue);
  margin-left:4px
}
.notif-ico.amber{background:rgba(245,158,45,.15);color:var(--orange)}
.notif-ico.red{background:rgba(239,68,68,.15);color:var(--red)}
.notif-ico.green{background:rgba(16,185,129,.15);color:var(--green)}
.notif-ico svg{width:16px;height:16px}
.notif-item.read .notif-ico{opacity:.55}
.notif-item.read .notif-body strong{color:var(--txt-soft);font-weight:600}
.notif-body{flex:1;min-width:0}
.notif-body strong{display:block;font-size:13px;font-weight:700;color:var(--txt);line-height:1.3;max-width:100%;word-break:break-word;overflow-wrap:break-word;hyphens:auto}
.notif-body span{display:block;font-size:11.5px;color:var(--txt-soft);margin-top:2px;line-height:1.4;max-width:100%;word-break:break-word;overflow-wrap:break-word}
.notif-body time{display:block;font-size:10.5px;color:var(--txt-soft);margin-top:4px;opacity:.7}
.notif-item[data-preview="1"]{cursor:pointer}
html[data-theme="light"] .notif-item:not(.read){background:linear-gradient(90deg, rgba(37,99,235,.12) 0%, rgba(37,99,235,.05) 60%, transparent 100%);border-left-color:#2563eb}
html[data-theme="light"] .notif-item:not(.read):hover{background:linear-gradient(90deg, rgba(37,99,235,.18) 0%, rgba(37,99,235,.08) 60%, transparent 100%)}
html[data-theme="light"] .notif-item.read{background:linear-gradient(90deg, rgba(15,23,42,.05) 0%, rgba(15,23,42,.02) 60%, transparent 100%);opacity:.75}
html[data-theme="light"] .notif-item.read:hover{background:linear-gradient(90deg, rgba(15,23,42,.09) 0%, rgba(15,23,42,.04) 60%, transparent 100%)}
.notif-preview-ov{
  position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:3000;
  display:flex;align-items:center;justify-content:center;
  opacity:0;visibility:hidden;pointer-events:none;transition:opacity .2s,visibility .2s;
}
.notif-preview-ov.open{opacity:1;visibility:visible;pointer-events:auto}
.notif-preview-box{
  width:min(640px,92vw);max-height:85vh;background:transparent;border:none;border-radius:var(--r-lg);
  display:flex;flex-direction:column;overflow:hidden;
}
.notif-preview-body{
  overflow-y:auto;font-size:13px;line-height:1.65;color:var(--txt);position:relative;
}
.notif-preview-body *{max-width:100%}
.notif-preview-body img{height:auto;border-radius:8px}
.notif-preview-body a{color:var(--blue)}
.notif-pv-close{
  position:absolute;top:12px;right:12px;z-index:10;
  width:32px;height:32px;border-radius:50%;border:none;cursor:pointer;
  display:grid;place-items:center;font-size:18px;line-height:1;
  background:rgba(0,0,0,.3);color:#fff;transition:background .15s;
}
.notif-pv-close:hover{background:rgba(0,0,0,.5)}
html[data-theme="light"] .notif-pv-close{background:rgba(0,0,0,.08);color:#1e293b}
html[data-theme="light"] .notif-pv-close:hover{background:rgba(0,0,0,.15)}
.notif-pv-card{width:100%;border-radius:16px;padding:28px;color:var(--txt);transition:all .2s;user-select:none}
.notif-pv-card h2{
  margin:0 0 12px;font-size:18px;font-weight:800;
  max-width:100%;word-break:break-word;overflow-wrap:break-word;hyphens:auto;line-height:1.25
}
.notif-pv-card .meta{font-size:12px;margin-bottom:16px;background:none !important}
.notif-pv-card .body{font-size:13px;line-height:1.7;word-break:break-word;overflow-wrap:break-word}
.notif-pv-card .body ul,.notif-pv-card .body ol{padding-left:20px}
.notif-pv-card .pv-badge{display:inline-flex;align-items:center;gap:6px;border-radius:20px;font-size:11px;font-weight:700;padding:4px 12px;margin-bottom:14px}
.notif-pv-card .pv-icon{font-size:28px;margin-bottom:12px;display:block}

/* ===== TEMA OSCURO (default) — neón intenso ===== */
.notif-pv-card.theme-notificacion{background:linear-gradient(135deg,#1e1030 0%,#0f0720 100%);border:2.5px solid #a855f7;box-shadow:0 0 0 4px rgba(139,92,246,.25),0 0 32px rgba(168,85,247,.4)}
.notif-pv-card.theme-notificacion .pv-badge{background:rgba(139,92,246,.25);color:#ddd6fe;border:1.5px solid #a855f7}
.notif-pv-card.theme-notificacion h2{color:#f5f3ff;border-bottom:2.5px solid #a855f7;padding-bottom:10px}
.notif-pv-card.theme-notificacion .meta{color:#a78bfa}.notif-pv-card.theme-notificacion .body{color:#ede9fe}

.notif-pv-card.theme-anuncios_internos{background:linear-gradient(135deg,#071025 0%,#030712 100%);border:2.5px solid #2563eb;box-shadow:0 0 0 4px rgba(59,130,246,.28),0 0 32px rgba(37,99,235,.4)}
.notif-pv-card.theme-anuncios_internos .pv-badge{background:rgba(59,130,246,.28);color:#bfdbfe;border:1.5px solid #2563eb}
.notif-pv-card.theme-anuncios_internos h2{color:#f8fafc;border-bottom:2.5px solid #2563eb;padding-bottom:10px}
.notif-pv-card.theme-anuncios_internos .meta{color:#60a5fa}.notif-pv-card.theme-anuncios_internos .body{color:#dbeafe}

.notif-pv-card.theme-mejoras{background:linear-gradient(135deg,#022c22 0%,#011613 100%);border:2.5px solid #059669;box-shadow:0 0 0 4px rgba(16,185,129,.25),0 0 32px rgba(5,150,105,.4)}
.notif-pv-card.theme-mejoras .pv-badge{background:rgba(16,185,129,.25);color:#a7f3d0;border:1.5px solid #059669}
.notif-pv-card.theme-mejoras h2{color:#ecfdf5;border-bottom:2.5px solid #059669;padding-bottom:10px}
.notif-pv-card.theme-mejoras .meta{color:#34d399}.notif-pv-card.theme-mejoras .body{color:#a7f3d0}

.notif-pv-card.theme-mantenimiento{background:linear-gradient(135deg,#281b02 0%,#1a1200 100%);border:2.5px solid #d97706;box-shadow:0 0 0 4px rgba(245,158,11,.25),0 0 32px rgba(217,119,6,.4)}
.notif-pv-card.theme-mantenimiento .pv-badge{background:rgba(245,158,11,.25);color:#fde68a;border:1.5px solid #d97706}
.notif-pv-card.theme-mantenimiento h2{color:#fffbeb;border-bottom:2.5px solid #d97706;padding-bottom:10px}
.notif-pv-card.theme-mantenimiento .meta{color:#fbbf24}.notif-pv-card.theme-mantenimiento .body{color:#fde68a}
.notif-pv-card.theme-mantenimiento .body strong{color:#fbbf24}

.notif-pv-card.theme-politicas{background:linear-gradient(135deg,#1a1a1a 0%,#0d0d0d 100%);border:2.5px solid #9ca3af;box-shadow:0 0 0 4px rgba(156,163,175,.2),0 0 28px rgba(156,163,175,.3)}
.notif-pv-card.theme-politicas .pv-badge{background:rgba(156,163,175,.18);color:#d1d5db;border:1.5px solid #9ca3af;border-radius:4px}
.notif-pv-card.theme-politicas h2{color:#f9fafb;border-bottom:2.5px solid #9ca3af;padding-bottom:8px}
.notif-pv-card.theme-politicas .meta{color:#9ca3af;background:none}
.notif-pv-card.theme-politicas .body{color:#e5e7eb;text-align:justify}

/* ===== TEMA CLARO — neón adaptado a fondo claro ===== */
html[data-theme="light"] .notif-pv-card.theme-notificacion{background:linear-gradient(135deg,#faf5ff 0%,#f3e8ff 100%);border:2.5px solid #7c3aed;box-shadow:0 0 0 4px rgba(139,92,246,.18),0 0 32px rgba(124,58,237,.3)}
html[data-theme="light"] .notif-pv-card.theme-notificacion .pv-badge{background:rgba(139,92,246,.18);color:#6d28d9;border:1.5px solid #7c3aed}
html[data-theme="light"] .notif-pv-card.theme-notificacion h2{color:#4c1d95;border-bottom:2.5px solid #7c3aed;padding-bottom:10px}
html[data-theme="light"] .notif-pv-card.theme-notificacion .meta{color:#7c3aed}
html[data-theme="light"] .notif-pv-card.theme-notificacion .body{color:#3b0764}

html[data-theme="light"] .notif-pv-card.theme-anuncios_internos{background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);border:2.5px solid #1d4ed8;box-shadow:0 0 0 4px rgba(59,130,246,.18),0 0 32px rgba(29,78,216,.3)}
html[data-theme="light"] .notif-pv-card.theme-anuncios_internos .pv-badge{background:rgba(59,130,246,.18);color:#1d4ed8;border:1.5px solid #1d4ed8}
html[data-theme="light"] .notif-pv-card.theme-anuncios_internos h2{color:#1e3a8a;border-bottom:2.5px solid #1d4ed8;padding-bottom:10px}
html[data-theme="light"] .notif-pv-card.theme-anuncios_internos .meta{color:#2563eb}
html[data-theme="light"] .notif-pv-card.theme-anuncios_internos .body{color:#1e293b}

html[data-theme="light"] .notif-pv-card.theme-mejoras{background:linear-gradient(135deg,#ecfdf5 0%,#d1fae5 100%);border:2.5px solid #047857;box-shadow:0 0 0 4px rgba(16,185,129,.18),0 0 32px rgba(4,120,87,.3)}
html[data-theme="light"] .notif-pv-card.theme-mejoras .pv-badge{background:rgba(16,185,129,.18);color:#047857;border:1.5px solid #047857}
html[data-theme="light"] .notif-pv-card.theme-mejoras h2{color:#064e3b;border-bottom:2.5px solid #047857;padding-bottom:10px}
html[data-theme="light"] .notif-pv-card.theme-mejoras .meta{color:#059669}
html[data-theme="light"] .notif-pv-card.theme-mejoras .body{color:#064e3b}

html[data-theme="light"] .notif-pv-card.theme-mantenimiento{background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);border:2.5px solid #b45309;box-shadow:0 0 0 4px rgba(245,158,11,.18),0 0 32px rgba(180,83,9,.3)}
html[data-theme="light"] .notif-pv-card.theme-mantenimiento .pv-badge{background:rgba(245,158,11,.18);color:#b45309;border:1.5px solid #b45309}
html[data-theme="light"] .notif-pv-card.theme-mantenimiento h2{color:#78350f;border-bottom:2.5px solid #b45309;padding-bottom:10px}
html[data-theme="light"] .notif-pv-card.theme-mantenimiento .meta{color:#d97706}
html[data-theme="light"] .notif-pv-card.theme-mantenimiento .body{color:#78350f}
html[data-theme="light"] .notif-pv-card.theme-mantenimiento .body strong{color:#b45309}

html[data-theme="light"] .notif-pv-card.theme-politicas{background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);border:2.5px solid #334155;box-shadow:0 0 0 4px rgba(51,65,85,.15),0 0 28px rgba(51,65,85,.25)}
html[data-theme="light"] .notif-pv-card.theme-politicas .pv-badge{background:#f1f5f9;color:#1e293b;border:1.5px solid #334155;border-radius:4px}
html[data-theme="light"] .notif-pv-card.theme-politicas h2{color:#0f172a;border-bottom:2.5px solid #334155;padding-bottom:8px}
html[data-theme="light"] .notif-pv-card.theme-politicas .meta{color:#475569;background:none}
html[data-theme="light"] .notif-pv-card.theme-politicas .body{color:#1e293b;text-align:justify}

.notif-pv-card .meta::selection{background:transparent}
.notif-pv-card .body{user-select:text}
.profile{
  display:flex;align-items:center;gap:10px;
  padding:6px 14px 6px 6px;
  border-radius:10px;
  border:1px solid var(--stroke);
  background:var(--panel-2);
  transition: background 150ms ease;
}
html[data-theme="light"] .profile:hover { background: var(--hover-bg); }
.profile .avatar{
  width:34px;height:34px;
  border-radius:50%;
  background:linear-gradient(135deg,var(--blue),var(--cyan));
  display:grid;place-items:center;
  font-family:'Sora',sans-serif;
  font-weight:700;
  font-size:13px;
  color: #fff;
  overflow:hidden;
  flex:none;
}
.profile .avatar.has-photo{font-size:0;background:var(--panel)}
.profile .avatar img{width:100%;height:100%;object-fit:cover;display:block}
.profile strong{display:block;font-size:13.5px;line-height:1.2; color: var(--txt);}
.profile span{font-size:11.5px;color:var(--txt-soft)}

/* ===== Menú desplegable del perfil ===== */
.profile-wrap{position:relative}
.profile{cursor:pointer;font:inherit;color:inherit;text-align:left}
.profile-meta{display:flex;flex-direction:column}
.profile-caret{color:var(--txt-soft);transition:transform .2s}
.profile-wrap.open .profile-caret{transform:rotate(180deg)}

.profile-menu{
  position:absolute;top:calc(100% + 10px);right:0;width:280px;max-width:88vw;
  background:var(--card);border:1px solid var(--stroke);border-radius:var(--r-lg);
  box-shadow:0 24px 48px rgba(0,0,0,.42);padding:10px;z-index:60;
  opacity:0;visibility:hidden;transform:translateY(-10px) scale(.98);transform-origin:top right;
  transition:opacity .2s var(--ease-out),transform .2s var(--ease-out),visibility .2s;
}
.profile-menu.open{opacity:1;visibility:visible;transform:translateY(0) scale(1)}
.pm-head{padding:8px 10px 14px;margin-bottom:6px;border-bottom:1px solid var(--stroke)}
.pm-head strong{display:block;font-family:'Sora',sans-serif;font-size:14.5px;font-weight:700; color:var(--txt);}
.pm-head span{font-size:11.5px;color:var(--txt-soft)}
.pm-item{display:flex;align-items:center;gap:12px;width:100%;padding:10px;border-radius:10px;text-align:left;background:none;border:0;cursor:pointer;font:inherit;color:var(--txt);transition:background-color .15s}
.pm-item:hover{background:var(--panel-2)}
html[data-theme="light"] .pm-item:hover { background: var(--bg); }
.pm-ico{width:32px;height:32px;flex:none;border-radius:8px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1)}
.pm-ico svg{width:16px;height:16px}
.pm-txt{display:flex;flex-direction:column;min-width:0}
.pm-txt .t{font-size:13px;font-weight:600}
.pm-txt .d{font-size:11px;color:var(--txt-soft);margin-top:2px}
.pm-sep{height:1px;background:var(--stroke);margin:6px 4px}
.pm-item.danger .pm-ico{color:var(--red);background:rgba(255,90,110,.1)}
.pm-item.danger .t{color:var(--red)}
.pm-item.edit-db .pm-ico{color:#B263FF;background:rgba(178,99,255,.1)}
.pm-item.edit-db .t{color:#B263FF}

/* ================= SIDEBAR COLAPSABLE ================= */
.side-top{margin-bottom:16px}
.side-brand-row{
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:16px; 
  width: 100%;
}
.side-brand-copy{display:flex;flex-direction:column;align-items:center}
.side-collapse-btn{
  height:34px; 
  padding: 0 14px;
  border-radius:10px;
  border:1px solid var(--stroke);
  background:var(--panel-2);
  display:flex;
  align-items:center;
  justify-content:center;
  gap: 6px;
  color:var(--txt-soft);
  transition:all .18s ease;
  flex:none;
  font-size: 12.5px; 
  font-weight: 700;
  width: 100%;
}
html[data-theme="light"] .side-collapse-btn { background: var(--bg); border: 1px solid transparent; }
.side-collapse-btn:hover{background:var(--hover-bg); color:var(--blue)}
.side-collapse-btn svg{width:16px;height:16px} 
.side-collapse-btn .ico-close{display:none}
.dash.sidebar-collapsed .side-collapse-btn .ico-open{display:none}
.dash.sidebar-collapsed .side-collapse-btn .ico-close{display:block}
.nav-label{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

@media (min-width:1025px){
  .dash.sidebar-collapsed{grid-template-columns:84px 1fr} 
  .dash.sidebar-collapsed .side{padding:24px 10px;align-items:center}
  .dash.sidebar-collapsed .side-brand-row{width:100%;flex-direction:column;align-items:center}
  .dash.sidebar-collapsed .side-brand{margin-bottom:8px}
  .dash.sidebar-collapsed .side-brand img{width:44px;height:44px} 
  .dash.sidebar-collapsed .side-brand-copy,
  .dash.sidebar-collapsed .nav-label,
  .dash.sidebar-collapsed .side-help strong,
  .dash.sidebar-collapsed .side-help span,
  .dash.sidebar-collapsed .side-help .btn-ghost{display:none}
  .dash.sidebar-collapsed .side-collapse-btn{
    margin-top:2px;
    width: 36px;
    height: 36px;
    padding: 0;
  }
  .dash.sidebar-collapsed .collapse-text {
    display: none;
  }
  .dash.sidebar-collapsed .nav-item{justify-content:center;gap:0;padding:12px 0;width:48px;align-self:center; border-radius:12px;}
  .dash.sidebar-collapsed .nav-item svg{width:20px;height:20px} 
  .dash.sidebar-collapsed .side-help{padding:14px 10px;width:100%}
  .dash.sidebar-collapsed .side-help .orb{margin-bottom:0}
}

.db-editor-panels{flex:1;display:flex;flex-direction:column;overflow:hidden}
.db-editor-panel{display:none;flex:1;flex-direction:column;overflow:hidden}
.db-editor-panel.active{display:flex}

.db-mode-switch-editor{display:flex;background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:var(--r-md);padding:3px;gap:3px}
.db-mode-switch-editor button{flex:1;padding:10px 12px;border:none;border-radius:8px;background:transparent;font:inherit;font-size:13px;font-weight:600;color:var(--txt-soft);cursor:pointer;transition:background .15s,color .15s}
.db-mode-switch-editor button:hover{color:var(--txt)}
.db-mode-switch-editor button.active{background:linear-gradient(135deg,#7B3FE4,#B263FF);color:#fff}

.db-editor-hint{font-size:11.5px;color:rgba(234,241,255,.45);margin-top:12px;line-height:1.45}

.db-editor-section{margin-bottom:22px}
.db-editor-section-title{font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(234,241,255,.35);margin-bottom:10px}
.db-widget-item{display:flex;align-items:center;gap:12px;padding:11px 12px;border-radius:10px;border:1px solid var(--stroke);background:var(--panel);margin-bottom:8px;cursor:grab;user-select:none;transition:border-color .15s,background .15s}
.db-widget-item:hover{border-color:rgba(178,99,255,.4);background:rgba(178,99,255,.06)}
.db-widget-item.dragging{opacity:.5;cursor:grabbing}
.db-widget-dot{width:10px;height:10px;border-radius:50%;flex:none}
.db-widget-dot.blue{background:#2E7BF6}
.db-widget-dot.purple{background:#B263FF}
.db-widget-dot.teal{background:#168BD9}
.db-widget-dot.green{background:#3DDC97}
.db-widget-dot.green2{background:#22C55E}
.db-widget-dot.orange{background:#F59E2D}
.db-widget-info{flex:1;min-width:0}
.db-widget-name{font-size:13px;font-weight:600;color:#EAF1FF}
.db-widget-desc{font-size:11px;color:rgba(234,241,255,.45);margin-top:1px}
.db-widget-toggle{position:relative;width:36px;height:20px;flex:none}
.db-widget-toggle input{opacity:0;width:0;height:0;position:absolute}
.db-widget-slider{position:absolute;inset:0;border-radius:20px;background:rgba(110,160,255,.2);cursor:pointer;transition:background .2s}
.db-widget-slider::before{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:#8FA3CF;top:3px;left:3px;transition:transform .2s,background .2s}
.db-widget-toggle input:checked + .db-widget-slider{background:rgba(178,99,255,.4)}
.db-widget-toggle input:checked + .db-widget-slider::before{transform:translateX(16px);background:#B263FF}
.db-editor-footer{padding:14px 18px;border-top:1px solid rgba(178,99,255,.2);display:flex;gap:10px;flex:none}
.db-editor-btn{flex:1;padding:11px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;border:none;transition:all .15s}
.db-editor-btn.save{background:linear-gradient(135deg,#7B3FE4,#B263FF);color:#fff}
.db-editor-btn.save:hover{opacity:.88}
.db-editor-btn.reset{background:transparent;border:1px solid rgba(178,99,255,.3);color:rgba(234,241,255,.6)}
.db-editor-btn.reset:hover{border-color:rgba(178,99,255,.6);color:#EAF1FF}
html[data-theme="light"] .db-editor-overlay{background:rgba(14,21,48,.25)}
html[data-theme="light"] .db-editor{background:#F6F8FE;border-left-color:rgba(120,60,220,.3)}
html[data-theme="light"] .db-editor-head{border-bottom-color:rgba(120,60,220,.2)}
html[data-theme="light"] .db-editor-title{color:#0E1530}
html[data-theme="light"] .db-editor-subtitle{color:rgba(14,21,48,.5)}
html[data-theme="light"] .db-editor-tabs{background:rgba(120,60,220,.06);border-bottom-color:rgba(120,60,220,.2)}
html[data-theme="light"] .db-editor-tab{color:rgba(14,21,48,.5)}
html[data-theme="light"] .db-editor-tab:hover{color:rgba(14,21,48,.75)}
html[data-theme="light"] .db-editor-tab.active{color:#0E1530}
html[data-theme="light"] .db-editor-section-title{color:rgba(14,21,48,.45)}
html[data-theme="light"] .db-editor-hint{color:rgba(14,21,48,.45)}
html[data-theme="light"] .db-editor-footer{border-top-color:rgba(120,60,220,.2)}
html[data-theme="light"] .db-editor-btn.reset{border-color:rgba(120,60,220,.3);color:rgba(14,21,48,.55)}
html[data-theme="light"] .db-editor-btn.reset:hover{border-color:rgba(120,60,220,.55);color:#0E1530}
html[data-theme="light"] .db-editor-close{color:#5B6A99}
html[data-theme="light"] .db-editor-close:hover{background:rgba(120,60,220,.1);color:#0E1530}
html[data-theme="light"] .db-widget-item{background:#fff;border-color:rgba(20,50,120,.12)}
html[data-theme="light"] .db-widget-item:hover{border-color:rgba(120,60,220,.35);background:rgba(120,60,220,.04)}
html[data-theme="light"] .db-widget-name{color:#0E1530}
html[data-theme="light"] .db-widget-desc{color:rgba(14,21,48,.45)}
html[data-theme="light"] .db-widget-slider{background:rgba(20,50,120,.15)}
html[data-theme="light"] .db-mode-switch-editor{background:#fff;border-color:rgba(20,50,120,.15)}
html[data-theme="light"] .db-mode-switch-editor button{color:rgba(14,21,48,.6)}
html[data-theme="light"] .db-mode-switch-editor button:hover{color:#0E1530}
html[data-theme="light"] .db-editor-body::-webkit-scrollbar-thumb{background:rgba(120,60,220,.25)}

/* ================= COMPONENTES COMPARTIDOS ================= */
.card{
  background:var(--card);
  border:1px solid var(--stroke);
  border-radius:var(--r-lg);
  padding:24px;
  box-shadow: var(--shadow);
}
.card h3{
  font-family:'Sora',sans-serif;
  font-size:13.5px;
  font-weight:700;
  letter-spacing:.04em;
  margin-bottom:16px;
  color: var(--txt);
}
.chip{
  display:inline-block;
  padding:5px 12px;
  border-radius:8px;
  font-size:11.5px;
  font-weight:700;
  letter-spacing:.02em;
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
  padding:8px 0 env(safe-area-inset-bottom, 8px);
  justify-content:space-around;
  align-items:center;
}
.mobile-nav-item{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:4px;
  padding:6px 12px;
  border-radius:var(--r-md);
  color:var(--txt-soft);
  font-size:10px;
  font-weight:700;
  transition:color 150ms ease;
  min-width:56px;
}
.mobile-nav-item svg{width:22px;height:22px;flex:none}
.mobile-nav-item.active{color:var(--blue)}
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
    border-right: none;
    border-bottom: 1px solid var(--stroke);
  }
  .side::-webkit-scrollbar{display:none}
  .side-brand{flex-direction:row;margin-bottom:0;gap:10px;flex:none}
  .side-brand-row { flex-direction:row; width: auto; gap: 16px; }
  .side-brand img{width:40px;margin-bottom:0}
  .side-brand-tag{display:none}
  .side-brand-name{font-size:14px;letter-spacing:.15em}
  .nav-item{flex:none;padding:8px 14px;font-size:13.5px}
  .side-help{display:none}
  .main{padding:20px 16px 32px}
  .head h1{font-size:20px}
  .head .sub{font-size:13.5px}
  .profile strong,.profile span{display:none}
  .profile{padding:10px}
  .side-collapse-btn { display:none; }
}
@media (max-width:720px){
  .btn-ai span{display:none}
  .btn-ai{padding:10px 14px}
  .head{gap:12px;margin-bottom:20px}
  .head h1{font-size:19px}
  .head .sub{font-size:13px}
  .head-right{gap:10px}
}
@media (max-width:600px){
  .side{display:none}
  .mobile-nav{display:flex}
  .dash{min-height:unset}
  .main{padding:16px 14px calc(80px + env(safe-area-inset-bottom, 0px)) 14px}
  .head h1{font-size:18px}
  .head .sub{font-size:12.5px}
  .head-right{gap:8px}
  .btn-ai{padding:10px 12px;font-size:12.5px}
  .bell{width:40px;height:40px}
  .profile{padding:8px}
  .head{margin-bottom:18px}
}
@media (max-width:400px){
  .head{gap:8px}
  .head-right{gap:6px}
  .btn-ai{padding:9px}
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

/* ================= AI DRAWER Y NUEVO INPUT "PILL" ================= */
.ai-overlay{position:fixed;inset:0;z-index:2200;background:rgba(15,23,42,.6);backdrop-filter:blur(4px);opacity:0;visibility:hidden;transition:opacity .25s ease, visibility .25s ease;}
.ai-overlay.open{opacity:1;visibility:visible}
.ai-drawer{position:fixed;top:0;right:-460px;width:430px;max-width:100vw;height:100vh;z-index:2201;display:flex;flex-direction:column;background:var(--panel);border-left:1px solid var(--stroke);box-shadow:-20px 0 44px var(--shadow);transition:right .28s cubic-bezier(.4,0,.2,1);}
.ai-drawer.open{right:0}
.ai-drawer-head{padding:20px 20px 16px;border-bottom:1px solid var(--stroke);display:flex;align-items:flex-start;justify-content:space-between;gap:12px;}
.ai-drawer-head-left{display:flex;align-items:flex-start;gap:12px}
.ai-orb{width:42px;height:42px;border-radius:50%;display:grid;place-items:center;background:var(--hover-bg);color:var(--blue);flex:none;}
.ai-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:700}
.ai-subtitle{font-size:12px;color:var(--txt-soft);margin-top:2px}
.ai-close{width:34px;height:34px;border-radius:10px;border:1px solid var(--stroke);background:transparent;display:grid;place-items:center;color:var(--txt-soft);}
.ai-close:hover{background:var(--hover-bg);color:var(--txt)}
.ai-drawer-body{flex:1;display:flex;flex-direction:column;min-height:0}
.ai-messages{flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:14px}
.ai-msg{
  max-width:80%;
  padding:13px 18px;
  line-height:1.5;
  font-size:14px;
  font-weight:500;
  border:none;
  opacity:0;
  transform:translateY(10px) scale(.96);
  animation:ai-pop .32s var(--ease-out) forwards;
}
@keyframes ai-pop{to{opacity:1;transform:translateY(0) scale(1)}}
.ai-msg.assistant{
  align-self:flex-start;
  background:#1C9FF1;
  color:#fff;
  border-radius:24px 24px 24px 8px;
  box-shadow:0 12px 24px -8px rgba(28,159,241,.55);
}
.ai-msg.user{
  align-self:flex-end;
  background:#fff;
  color:#111827;
  border-radius:24px 24px 8px 24px;
  box-shadow:0 12px 26px -10px rgba(15,23,42,.18);
}

/* Formato del Markdown dentro de las burbujas */
.ai-msg strong{font-weight:700}
.ai-msg ol,.ai-msg ul{margin:8px 0 4px;padding-left:20px;display:flex;flex-direction:column;gap:4px}
.ai-msg ol li,.ai-msg ul li{line-height:1.45}
.ai-msg p{margin:0 0 8px}
.ai-msg p:last-child{margin-bottom:0}

/* Indicador "escribiendo / pensando" de la IA */
.ai-typing{
  align-self:flex-start;
  display:flex;
  align-items:center;
  gap:6px;
  padding:16px 20px;
  background:#1C9FF1;
  border-radius:24px 24px 24px 8px;
  box-shadow:0 12px 24px -8px rgba(28,159,241,.55);
  opacity:0;
  transform:translateY(10px) scale(.96);
  animation:ai-pop .32s var(--ease-out) forwards;
}
.ai-typing span{
  width:8px;height:8px;border-radius:50%;
  background:rgba(255,255,255,.9);
  animation:ai-dot 1.2s infinite ease-in-out;
}
.ai-typing span:nth-child(2){animation-delay:.18s}
.ai-typing span:nth-child(3){animation-delay:.36s}
@keyframes ai-dot{
  0%,60%,100%{transform:translateY(0);opacity:.5}
  30%{transform:translateY(-5px);opacity:1}
}
.ai-suggestions{padding:0 20px 16px;display:flex;flex-wrap:wrap;gap:8px}
.ai-suggestions.is-hidden{display:none}
.ai-chip{padding:8px 14px;border-radius:999px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);font-size:12.5px;font-weight:600;transition:all .15s ease;}
.ai-chip:hover{background:var(--hover-bg);color:var(--blue); border-color:var(--blue);}

/* Nuevo diseño de Input IA */
.ai-form{padding:10px 20px 24px;background:transparent;}
.ai-input-wrap{display:flex;align-items:center;gap:10px;background:var(--card);border:1px solid var(--stroke);border-radius:999px;padding:6px 8px;box-shadow:0 8px 24px rgba(0,0,0,0.06);}
.ai-btn-icon{width:38px;height:38px;border-radius:14px;background:var(--input-bg);color:var(--txt-soft);border:none;display:grid;place-items:center;flex:none;cursor:pointer;transition:all .15s ease;}
.ai-btn-icon:hover{background:var(--hover-bg-strong);color:var(--blue);}
.ai-btn-icon:active{transform:scale(.95);}
.ai-input{flex:1;background:transparent;border:none;color:var(--txt);outline:none;font:inherit;font-size:14.5px;padding:0 6px;}
.ai-input::placeholder{color:var(--txt-soft);}

/* Flecha del botón enviar: apunta a la izquierda cuando no hay texto, hacia arriba cuando sí */
.ai-send .ico-up{display:none}
.ai-input-wrap.has-text .ai-send .ico-back{display:none}
.ai-input-wrap.has-text .ai-send .ico-up{display:block}
.ai-input-wrap.has-text .ai-send{background:var(--blue);color:#fff}

html[data-theme="light"] .ai-input-wrap{background:#FFFFFF;border:none;box-shadow:0 8px 30px rgba(0,0,0,0.08);}
html[data-theme="light"] .ai-btn-icon{background:#F3F4F6;color:#4B5563;}
html[data-theme="light"] .ai-btn-icon:hover{background:#E5E7EB;color:#111827;}
html[data-theme="light"] .ai-input::placeholder{color:#9CA3AF;}



/* ================= AI CHAT / HISTORIAL ================= */
.ai-tabs{
  display:flex;
  gap:6px;
  padding:12px 20px 8px;
  border-bottom:1px solid var(--stroke);
}
.ai-tab{
  flex:1;
  height:42px;
  border-radius:999px;
  background:transparent;
  color:var(--txt-soft);
  font-size:14px;
  font-weight:800;
  transition:all .15s ease;
}
.ai-tab.active{
  background:var(--card);
  color:var(--txt);
  box-shadow:0 8px 24px rgba(0,0,0,.08);
}
.ai-panel{
  display:none;
  flex:1;
  min-height:0;
  flex-direction:column;
}
.ai-panel.active{display:flex}
.ai-history-panel{padding:14px 16px 20px;overflow-y:auto}
.ai-history-list{display:flex;flex-direction:column;gap:8px}
.ai-history-empty{padding:18px;text-align:center;color:var(--txt-soft);font-size:13px}
.ai-history-item{
  width:100%;
  display:flex;
  align-items:center;
  gap:12px;
  padding:12px 10px;
  border-radius:16px;
  background:transparent;
  text-align:left;
  transition:background .15s ease;
}
.ai-history-item:hover{background:var(--hover-bg)}
.ai-history-logo{
  width:42px;
  height:42px;
  border-radius:50%;
  flex:none;
  display:grid;
  place-items:center;
  background:var(--input-bg);
  color:var(--blue);
}
.ai-history-main{min-width:0;flex:1}
.ai-history-title{
  color:var(--txt);
  font-size:14px;
  font-weight:700;
  overflow:hidden;
  white-space:nowrap;
  text-overflow:ellipsis;
}
.ai-history-status{font-size:13px;margin-top:2px;color:var(--txt-soft)}
.ai-history-status.active{color:#059669}
.ai-history-time{font-size:13px;color:var(--txt-soft);white-space:nowrap;align-self:flex-start;margin-top:2px}
html[data-theme="light"] .ai-tab.active{background:#fff}
html[data-theme="light"] .ai-history-logo{background:#F3F4F6}


/* ================= AI ADJUNTOS / PREVIEW ================= */
.ai-attachments-preview{
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  margin-bottom:10px;
}
.ai-file-preview{
  position:relative;
  display:flex;
  align-items:center;
  gap:10px;
  max-width:100%;
  padding:8px 10px;
  border-radius:18px;
  background:var(--card);
  border:1px solid var(--stroke);
  box-shadow:0 8px 24px rgba(0,0,0,.06);
}
.ai-file-preview img{
  width:58px;
  height:58px;
  border-radius:14px;
  object-fit:cover;
  display:block;
  background:var(--input-bg);
}
.ai-file-preview .ai-file-icon{
  width:42px;
  height:42px;
  border-radius:14px;
  display:grid;
  place-items:center;
  background:var(--input-bg);
  color:var(--blue);
  font-size:20px;
  flex:none;
}
.ai-file-preview .ai-file-info{
  min-width:0;
  display:flex;
  flex-direction:column;
  gap:2px;
}
.ai-file-preview .ai-file-name{
  max-width:220px;
  overflow:hidden;
  text-overflow:ellipsis;
  white-space:nowrap;
  font-size:12.5px;
  font-weight:800;
  color:var(--txt);
}
.ai-file-preview .ai-file-type{
  font-size:11px;
  color:var(--txt-soft);
}
.ai-file-preview .ai-file-remove{
  width:24px;
  height:24px;
  border-radius:50%;
  display:grid;
  place-items:center;
  background:var(--hover-bg);
  color:var(--txt-soft);
  font-size:16px;
  font-weight:800;
  flex:none;
}
.ai-file-preview .ai-file-remove:hover{
  color:var(--red);
}
.ai-msg .ai-uploaded-images{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
  margin-top:10px;
}
.ai-msg .ai-uploaded-images img{
  width:120px;
  max-width:100%;
  height:auto;
  border-radius:16px;
  display:block;
  box-shadow:0 8px 24px rgba(0,0,0,.12);
}
.ai-msg .ai-file-note{
  margin-top:8px;
  font-size:12px;
  opacity:.8;
}

@media (max-width:640px){.ai-drawer{width:100%;right:-100%}}

/* Db Editor - Hereda las nuevas variables blancas */
.db-editor-overlay{position:fixed;inset:0;z-index:2100;background:rgba(15,23,42,.6);backdrop-filter:blur(3px);opacity:0;visibility:hidden;transition:opacity .25s,visibility .25s}
.db-editor-overlay.open{opacity:1;visibility:visible}
.db-editor{position:fixed;top:0;right:-420px;width:400px;max-width:100vw;height:100%;z-index:2101;background:var(--panel);border-left:1px solid var(--stroke);box-shadow:-16px 0 48px var(--shadow);display:flex;flex-direction:column;transition:right .28s cubic-bezier(.4,0,.2,1);overflow:hidden}
.db-editor.open{right:0}
.db-editor-head{padding:20px 20px 16px;border-bottom:1px solid var(--stroke);display:flex;align-items:center;justify-content:space-between;flex:none}
.db-editor-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:700;color:var(--txt)}
.db-editor-subtitle{font-size:12px;color:var(--txt-soft);margin-top:2px}
.db-editor-close{width:32px;height:32px;border-radius:10px;border:none;background:transparent;color:var(--txt-soft);cursor:pointer;display:grid;place-items:center;transition:all .15s}
.db-editor-close:hover{background:var(--hover-bg);color:var(--txt)}
.db-editor-body{flex:1;overflow-y:auto;padding:20px}
.db-editor-tabs{display:flex;gap:0;padding:0 20px;border-bottom:1px solid var(--stroke);background:var(--bg)}
.db-editor-tab{flex:1;padding:12px 0;border:none;background:transparent;font-family:'Sora',sans-serif;font-size:13px;font-weight:700;color:var(--txt-soft);cursor:pointer;position:relative;transition:color .15s}
.db-editor-tab:hover{color:var(--txt)}
.db-editor-tab.active{color:var(--blue)}
.db-editor-tab.active::after{content:'';position:absolute;bottom:0;left:10%;right:10%;height:3px;background:var(--blue);border-radius:3px 3px 0 0}
.db-editor-panels{flex:1;display:flex;flex-direction:column;overflow:hidden}
.db-editor-panel{display:none;flex:1;flex-direction:column;overflow:hidden}
.db-editor-panel.active{display:flex}
.db-mode-switch-editor{display:flex;background:var(--bg);border:1px solid var(--stroke);border-radius:var(--r-md);padding:4px;gap:4px}
.db-mode-switch-editor button{flex:1;padding:10px;border:none;border-radius:8px;background:transparent;font:inherit;font-size:13px;font-weight:700;color:var(--txt-soft);cursor:pointer;transition:background .15s,color .15s}
.db-mode-switch-editor button:hover{color:var(--txt)}
.db-mode-switch-editor button.active{background:var(--panel);color:var(--blue);box-shadow:0 2px 8px var(--shadow)}
.db-editor-hint{font-size:12px;color:var(--txt-soft);margin-top:12px;line-height:1.5}
.db-editor-section{margin-bottom:24px}
.db-editor-section-title{font-size:10.5px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:var(--txt-soft);margin-bottom:10px}
.db-widget-item{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:12px;border:1px solid var(--stroke);background:var(--panel);margin-bottom:8px;cursor:grab;user-select:none;transition:border-color .15s,box-shadow .15s}
.db-widget-item:hover{border-color:var(--blue);box-shadow:0 4px 12px var(--hover-bg)}
.db-widget-dot{width:10px;height:10px;border-radius:50%;flex:none}
.db-widget-dot.blue{background:#3B82F6}
.db-widget-dot.purple{background:#8B5CF6}
.db-widget-dot.teal{background:#14B8A6}
.db-widget-dot.green{background:#10B981}
.db-widget-dot.green2{background:#22C55E}
.db-widget-dot.orange{background:#F59E2D}
.db-widget-info{flex:1;min-width:0}
.db-widget-name{font-size:13.5px;font-weight:700;color:var(--txt)}
.db-widget-desc{font-size:11.5px;color:var(--txt-soft);margin-top:2px}
.db-widget-toggle{position:relative;width:38px;height:22px;flex:none}
.db-widget-toggle input{opacity:0;width:0;height:0;position:absolute}
.db-widget-slider{position:absolute;inset:0;border-radius:24px;background:var(--off);cursor:pointer;transition:background .2s}
.db-widget-slider::before{content:'';position:absolute;width:16px;height:16px;border-radius:50%;background:#fff;top:3px;left:3px;transition:transform .2s; box-shadow:0 2px 4px rgba(0,0,0,.15)}
.db-widget-toggle input:checked + .db-widget-slider{background:var(--blue)}
.db-widget-toggle input:checked + .db-widget-slider::before{transform:translateX(16px);}
.db-editor-footer{padding:16px 20px;border-top:1px solid var(--stroke);display:flex;gap:12px;flex:none; background:var(--bg)}
.db-editor-btn{flex:1;padding:10px;border-radius:10px;font-size:13.5px;font-weight:700;cursor:pointer;border:none;transition:all .15s}
.db-editor-btn.save{background:var(--blue);color:#fff}
.db-editor-btn.save:hover{opacity:.9}
.db-editor-btn.reset{background:transparent;border:1px solid var(--stroke-strong);color:var(--txt-soft)}
.db-editor-btn.reset:hover{border-color:var(--txt);color:var(--txt)}

/* Modal de alerta genérico */
.app-alert-overlay{
  position:fixed;
  inset:0;
  z-index:9999;
  background:rgba(0,0,0,.55);
  backdrop-filter:blur(4px);
  display:flex;
  align-items:center;
  justify-content:center;
  padding:20px;
  opacity:0;
  visibility:hidden;
  transition:opacity 200ms ease, visibility 200ms ease;
}
.app-alert-overlay.open{
  opacity:1;
  visibility:visible;
}
.app-alert-modal{
  background:var(--modal-bg);
  border:1px solid var(--stroke-strong);
  border-radius:var(--r-lg);
  box-shadow:0 24px 60px rgba(0,0,0,.5);
  max-width:420px;
  width:100%;
  padding:28px;
  text-align:center;
  transform:scale(.95);
  transition:transform 200ms var(--ease-out);
}
.app-alert-overlay.open .app-alert-modal{
  transform:scale(1);
}
.app-alert-icon{
  width:56px;
  height:56px;
  border-radius:50%;
  background:rgba(245,158,45,.15);
  color:var(--orange);
  display:grid;
  place-items:center;
  margin:0 auto 16px;
}
.app-alert-icon svg{width:28px;height:28px}
.app-alert-title{
  font-size:18px;
  font-weight:700;
  margin-bottom:8px;
}
.app-alert-message{
  font-size:14px;
  color:var(--txt-soft);
  line-height:1.5;
  margin-bottom:24px;
}
.app-alert-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:12px 28px;
  border-radius:var(--r-md);
  background:var(--blue);
  color:#fff;
  font-size:14px;
  font-weight:600;
  border:none;
  cursor:pointer;
  transition:opacity 150ms ease;
}
.app-alert-btn:hover{opacity:.9}
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
  @hasSection('sidebar')
    @yield('sidebar')
  @else
  <aside class="side">
    <div class="side-top">
      <div class="side-brand-row">
        <div class="side-brand">
          <img class="logo-dark" src="{{ asset('images/logo-dark.png') }}" alt="Logotipo ENCLAII">
          <img class="logo-light" src="{{ asset('images/logo.png') }}" alt="Logotipo ENCLAII">
          <div class="side-brand-copy">
            <div class="side-brand-name">ENCLA<span>II</span></div>
            <div class="side-brand-tag">Endoscopia · Nube · IA</div>
          </div>
        </div>

        <button type="button" class="side-collapse-btn" id="sidebarCollapseBtn" aria-label="Contraer menú" title="Contraer menú">
          <svg class="ico-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
          <svg class="ico-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
          <span class="collapse-text">Contraer</span>
        </button>
      </div>
    </div>

    <a class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ url('/dashboard') }}" title="Dashboard">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
      <span class="nav-label">Dashboard</span>
    </a>

    <a class="nav-item {{ $active === 'agenda' ? 'active' : '' }}" href="{{ route('agenda') }}" title="Agenda">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      <span class="nav-label">Agenda</span>
    </a>

    <a class="nav-item {{ $active === 'pacientes' ? 'active' : '' }}" href="{{ route('pacientes.index') }}" title="Pacientes">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      <span class="nav-label">Pacientes</span>
    </a>

    <a class="nav-item {{ $active === 'qr' ? 'active' : '' }}" href="{{ route('qr.index') }}" title="QR">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1"/></svg>
      <span class="nav-label">QR</span>
    </a>

    <a class="nav-item {{ $active === 'ia-reportes' ? 'active' : '' }}" href="{{ url('/ia-reportes') }}" title="Reportes">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/><path d="M12.5 12.5 14 11l1.5 1.5"/><path d="M14 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
      <span class="nav-label">Reportes</span>
    </a>

    {{-- <a class="nav-item {{ $active === 'mensajes' ? 'active' : '' }}" href="{{ route('mensajes') }}" title="Mensajes">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      <span class="nav-label">Mensajes</span>
    </a> --}}

    <a class="nav-item {{ $active === 'galeria' ? 'active' : '' }}" href="{{ route('galeria') }}" title="Galería">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
      <span class="nav-label">Galería</span>
    </a>

    {{--<a class="nav-item {{ $active === 'finanzas' ? 'active' : '' }}" href="{{ url('/finanzas') }}" title="Finanzas">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>
      <span class="nav-label">Finanzas</span>
    </a> --}}

    <a class="nav-item {{ $active === 'configuracion' ? 'active' : '' }}" href="{{ url('/configuracion') }}" title="Configuración">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
      <span class="nav-label">Configuración</span>
    </a>

    <div class="side-help">
      <div class="orb">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
      </div>
      <strong>¿Necesitas ayuda?</strong>
      <span>Soporte 24/7</span>
      <a class="btn-ghost" href="{{ route('soporte') }}" style="text-decoration:none;color:inherit;display:inline-block">Contactar soporte</a>
    </div>
  </aside>
  @endif

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
        <button class="btn-ai" id="openAiAssistantBtn" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/></svg>
          <span>Asistente IA</span>
        </button>
        <button class="bell" id="themeToggle" aria-label="Cambiar tema">
          <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>
          <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        </button>
        <div class="notif-wrap" id="notifWrap">
          <button class="bell" id="notifBell" aria-label="Notificaciones" type="button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
            <span class="dot" id="notifDot" style="display:none">0</span>
          </button>
          <div class="notif-panel" id="notifPanel">
            <div class="notif-panel-head">
              <span class="notif-panel-title">Notificaciones</span>
              <button class="notif-clear-btn" id="notifClear" type="button">Limpiar</button>
            </div>
            <div class="notif-list" id="notifList">
              <div class="notif-empty" id="notifEmpty">Sin notificaciones nuevas</div>
            </div>
          </div>
        </div>

        {{-- Preview modal para anuncios (se renderiza al final del body via JS) --}}
        @php
          $userName = auth()->check() ? trim(auth()->user()->name ?? 'Doctor') : 'Doctor';
          $userParts = preg_split('/\s+/', $userName);
          $userInitials = collect($userParts)->take(2)->map(fn($p) => mb_substr($p, 0, 1))->join('');
          $userInitials = mb_strtoupper($userInitials ?: mb_substr($userName, 0, 2));
          $userFoto = auth()->check() && auth()->user()->foto_perfil
              ? media_url(auth()->user()->foto_perfil)
              : null;
        @endphp
        <div class="profile-wrap">
          <button type="button" class="profile" id="profileBtn" aria-haspopup="true" aria-expanded="false">
            <div class="avatar {{ $userFoto ? 'has-photo' : '' }}" id="headerAvatar" data-initials="{{ $userInitials }}">
              @if($userFoto)
                <img id="headerAvatarImg" src="{{ $userFoto }}" alt="Foto de perfil" loading="eager" onerror="this.remove();this.parentElement.classList.remove('has-photo');this.parentElement.textContent=this.parentElement.dataset.initials || '';">
              @else
                {{ $userInitials }}
              @endif
            </div>
            <div class="profile-meta">
              <strong>{{ $userName }}</strong>
            </div>
            <svg class="profile-caret" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>

          <div class="profile-menu" id="profileMenu" role="menu">
            <div class="pm-head"><strong>Acciones rápidas</strong><span>Acciones y herramientas</span></div>

            <a href="{{ route('configuracion') }}?tab=perfil" class="pm-item" role="menuitem">
              <span class="pm-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
              <span class="pm-txt"><span class="t">Editar perfil</span><span class="d">Actualiza tu información personal</span></span>
            </a>
            @if(request()->routeIs('dashboard'))
            <div class="pm-sep"></div>
            <button type="button" class="pm-item edit-db" id="editDashboardBtn" role="menuitem">
              <span class="pm-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></span>
              <span class="pm-txt"><span class="t">Editar Dashboard</span><span class="d">Configura y organiza tus widgets</span></span>
            </button>
            @endif
            <div class="pm-sep"></div>
            @auth
            <form method="POST" action="{{ auth()->user()->hasRole('Customer Success') ? route('customer-success.logout') : route('logout') }}">
              @csrf
              <button type="submit" class="pm-item danger" role="menuitem">
                <span class="pm-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
                <span class="pm-txt"><span class="t">Cerrar sesión</span><span class="d">Cerrar sesión en tu cuenta actual</span></span>
              </button>
            </form>
            @endauth
          </div>
        </div>
      </div>
    </header>

    @if(session('session_limit_notice'))
      <div class="app-alert" role="status">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.6 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.6a2 2 0 0 0-3.4 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span>{{ session('session_limit_notice') }}</span>
      </div>
    @endif

    @yield('content')

  </main>

  {{-- Bottom nav para móvil --}}
  @hasSection('bottom-nav')
    @yield('bottom-nav')
  @else
  <nav class="mobile-nav">
    <a class="mobile-nav-item {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ url('/dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
      Inicio
    </a>
    <a class="mobile-nav-item {{ $active === 'pacientes' ? 'active' : '' }}" href="{{ route('pacientes.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Pacientes
    </a>
    <a class="mobile-nav-item {{ $active === 'agenda' ? 'active' : '' }}" href="{{ route('agenda') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Agenda
    </a>
    <a class="mobile-nav-item {{ $active === 'configuracion' ? 'active' : '' }}" href="{{ url('/configuracion') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
      Config
    </a>
  </nav>
  @endif

</div>

@auth
  @include('components.critical-password-modal')
@endauth

{{-- Dashboard Editor Panel --}}
<div class="db-editor-overlay" id="dbEditorOverlay"></div>
<div class="db-editor" id="dbEditorPanel" role="dialog" aria-label="Editar Dashboard">
  <div class="db-editor-head">
    <div>
      <div class="db-editor-title">Editar Dashboard</div>
      <div class="db-editor-subtitle">Personaliza widgets y vista</div>
    </div>
    <button class="db-editor-close" id="dbEditorClose" aria-label="Cerrar">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>

  <div class="db-editor-tabs">
    <button type="button" class="db-editor-tab active" data-tab="widgets" id="dbEditorTabWidgets">Widgets</button>
    <button type="button" class="db-editor-tab" data-tab="vista" id="dbEditorTabVista">Vista</button>
  </div>

  <div class="db-editor-panels">
    <div class="db-editor-panel active" id="dbEditorPanelWidgets">
      <div class="db-editor-body" id="dbEditorBody"></div>
    </div>
    <div class="db-editor-panel" id="dbEditorPanelVista">
      <div class="db-editor-body">
        <div class="db-editor-section">
          <div class="db-editor-section-title">Modo de visualización</div>
          <div class="db-mode-switch-editor" id="dbModeSwitchEditor" role="group" aria-label="Vista de dashboard">
            <button type="button" id="dbModeEditorOriginal" class="active" aria-pressed="true">Original</button>
            <button type="button" id="dbModeEditorMinimal" aria-pressed="false">Minimalista</button>
          </div>
          <p class="db-editor-hint">El modo Original muestra todos los widgets con su diseño completo. El modo Minimalista compacta la información en tarjetas reducidas.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="db-editor-footer">
    <button class="db-editor-btn reset" id="dbEditorReset">Restablecer</button>
    <button class="db-editor-btn save" id="dbEditorSave">Guardar cambios</button>
  </div>
</div>


{{-- AI Assistant Drawer --}}
<div class="ai-overlay" id="aiOverlay"></div>
<aside class="ai-drawer" id="aiDrawer" role="dialog" aria-modal="true" aria-label="Asistente IA">
  <div class="ai-drawer-head">
    <div class="ai-drawer-head-left">
      <div class="ai-orb">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/>
        </svg>
      </div>
      <div>
        <div class="ai-title">Asistente IA</div>
        <div class="ai-subtitle">Ayuda rápida para agenda, pacientes, reportes y tareas del sistema</div>
      </div>
    </div>
    <div class="ai-drawer-head-actions" style="display:flex;align-items:center;gap:6px;flex:none">
    <button class="ai-close" id="aiResetBtn" type="button" aria-label="Reiniciar conversación" title="Reiniciar conversación">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="1 4 1 10 7 10"></polyline>
        <path d="M3.5 15a9 9 0 1 0 .5-8L1 10"></path>
      </svg>
    </button>
    <button class="ai-close" id="aiDrawerClose" type="button" aria-label="Cerrar asistente">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>
    </div>
  </div>

  <div class="ai-drawer-body">
    <div class="ai-tabs" role="tablist" aria-label="Asistente IA">
      <button type="button" class="ai-tab active" id="aiChatTab" role="tab" aria-selected="true">Chat</button>
      <button type="button" class="ai-tab" id="aiHistoryTab" role="tab" aria-selected="false">Historial</button>
    </div>

    <div class="ai-panel active" id="aiChatPanel" role="tabpanel" aria-labelledby="aiChatTab">
      <div class="ai-messages" id="aiMessages">
        <div class="ai-msg assistant">
          Hola, soy tu asistente de ENCLAII. Puedo ayudarte a navegar el sistema, redactar mensajes, sugerir acciones rápidas y resolver dudas sobre módulos.
        </div>
      </div>

      <div class="ai-suggestions" id="aiSuggestions">
        <button type="button" class="ai-chip" data-prompt="Muéstrame qué puedo hacer en Agenda">Agenda</button>
        <button type="button" class="ai-chip" data-prompt="Ayúdame con pacientes pendientes">Pacientes pendientes</button>
        <button type="button" class="ai-chip" data-prompt="Genera ideas para reportes IA">Reportes IA</button>
        <button type="button" class="ai-chip" data-prompt="Explícame cómo usar este dashboard">Dashboard</button>
      </div>

      <form class="ai-form" id="aiAssistantForm">
        <div class="ai-attachments-preview" id="aiAttachmentsPreview"></div>

        <div class="ai-input-wrap">
          <button type="button" class="ai-btn-icon" id="aiAttachBtn" aria-label="Adjuntar archivo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          </button>

          <input
            type="file"
            id="aiFileInput"
            multiple
            hidden
            accept="image/*,video/*,.pdf,.doc,.docx,.txt,.csv,.xlsx,.xls"
          >

          <input type="text" class="ai-input" id="aiInput" placeholder="Send Message" autocomplete="off">

          <button class="ai-btn-icon ai-send" type="submit" aria-label="Enviar">
            <svg class="ico-back" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            <svg class="ico-up" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
          </button>
        </div>
      </form>
    </div>

    <div class="ai-panel ai-history-panel" id="aiHistoryPanel" role="tabpanel" aria-labelledby="aiHistoryTab">
      <div class="ai-history-list" id="aiHistoryList">
        <div class="ai-history-empty">Cargando historial...</div>
      </div>
    </div>
  </div>
</aside>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
  /* Cambiar tema y recordarlo */
  document.getElementById('themeToggle').addEventListener('click', () => {
    const html = document.documentElement;
    const next = html.dataset.theme === 'light' ? 'dark' : 'light';
    html.dataset.theme = next;
    localStorage.setItem('enclaii-theme', next);
  });


  /* Sidebar colapsable */
  (function(){
    const dash = document.querySelector('.dash');
    const btn  = document.getElementById('sidebarCollapseBtn');
    if (!dash || !btn) return;

    const KEY = 'enclaii-sidebar-collapsed';

    function applyState(collapsed){
      dash.classList.toggle('sidebar-collapsed', collapsed);
      btn.setAttribute('aria-label', collapsed ? 'Expandir menú' : 'Contraer menú');
      btn.setAttribute('title', collapsed ? 'Expandir menú' : 'Contraer menú');
    }

    applyState(localStorage.getItem(KEY) === '1');

    btn.addEventListener('click', function(){
      const collapsed = !dash.classList.contains('sidebar-collapsed');
      applyState(collapsed);
      localStorage.setItem(KEY, collapsed ? '1' : '0');
    });
  })();

  /* Drawer Asistente IA */
  (function(){
    const openBtn  = document.getElementById('openAiAssistantBtn');
    const closeBtn = document.getElementById('aiDrawerClose');
    const overlay  = document.getElementById('aiOverlay');
    const drawer   = document.getElementById('aiDrawer');
    const form     = document.getElementById('aiAssistantForm');
    const input    = document.getElementById('aiInput');
    const messages = document.getElementById('aiMessages');
    const chips    = document.querySelectorAll('.ai-chip');
    const inputWrap = document.querySelector('.ai-input-wrap');
    const resetBtn = document.getElementById('aiResetBtn');
    const suggestions = document.getElementById('aiSuggestions');
    const chatTab = document.getElementById('aiChatTab');
    const historyTab = document.getElementById('aiHistoryTab');
    const chatPanel = document.getElementById('aiChatPanel');
    const historyPanel = document.getElementById('aiHistoryPanel');
    const historyList = document.getElementById('aiHistoryList');

    const attachBtn = document.getElementById('aiAttachBtn');
    const fileInput = document.getElementById('aiFileInput');
    const preview = document.getElementById('aiAttachmentsPreview');

    if (!drawer || !overlay) return;

    const AI_ENDPOINT = '{{ route("ia.chat") }}';
    const AI_START = '{{ route("ia.conversations.start") }}';
    const AI_CONVERSATIONS = '{{ route("ia.conversations") }}';
    const AI_SHOW_BASE = '{{ url("/ia/conversations") }}';
    const AI_RESET = '{{ route("ia.reset") }}';
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    let currentConversationId = null;
    let selectedFiles = [];

    const welcomeHTML = messages.innerHTML;

    function hideSuggestions(){
      suggestions?.classList.add('is-hidden');
    }

    function showSuggestions(){
      suggestions?.classList.remove('is-hidden');
    }

    function updateSendState(){
      inputWrap?.classList.toggle(
        'has-text',
        input.value.trim().length > 0 || selectedFiles.length > 0
      );
    }

    input?.addEventListener('input', updateSendState);

    function showTab(tab){
      const isHistory = tab === 'history';

      chatTab?.classList.toggle('active', !isHistory);
      historyTab?.classList.toggle('active', isHistory);

      chatTab?.setAttribute('aria-selected', String(!isHistory));
      historyTab?.setAttribute('aria-selected', String(isHistory));

      chatPanel?.classList.toggle('active', !isHistory);
      historyPanel?.classList.toggle('active', isHistory);

      if (isHistory) {
        loadAndRenderConversations();
      } else {
        setTimeout(() => input?.focus(), 80);
      }
    }

    chatTab?.addEventListener('click', () => showTab('chat'));
    historyTab?.addEventListener('click', () => showTab('history'));

    function clearMessages(){
      messages.innerHTML = welcomeHTML;
      messages.scrollTop = 0;
      selectedFiles = [];
      renderFilePreview();
      showSuggestions();
    }

    function fileToPreview(file){
      return new Promise(resolve => {
        const isImage = file.type.startsWith('image/');

        if (!isImage) {
          resolve(null);
          return;
        }

        const reader = new FileReader();
        reader.onload = e => resolve(e.target.result);
        reader.onerror = () => resolve(null);
        reader.readAsDataURL(file);
      });
    }

    async function renderFilePreview(){
      if (!preview) return;

      preview.innerHTML = '';

      for (let index = 0; index < selectedFiles.length; index++) {
        const file = selectedFiles[index];
        const url = await fileToPreview(file);
        const item = document.createElement('div');
        item.className = 'ai-file-preview';

        const typeLabel = file.type.startsWith('image/')
          ? 'Imagen'
          : file.type.startsWith('video/')
            ? 'Video'
            : 'Archivo';

        item.innerHTML = `
          ${url ? `<img src="${url}" alt="${escapeHtml(file.name)}">` : `<span class="ai-file-icon"></span>`}
          <span class="ai-file-info">
            <span class="ai-file-name">${escapeHtml(file.name)}</span>
            <span class="ai-file-type">${typeLabel}</span>
          </span>
          <button type="button" class="ai-file-remove" aria-label="Quitar archivo">×</button>
        `;

        item.querySelector('.ai-file-remove')?.addEventListener('click', () => {
          selectedFiles.splice(index, 1);
          renderFilePreview();
          updateSendState();
        });

        preview.appendChild(item);
      }

      updateSendState();
    }

    attachBtn?.addEventListener('click', () => {
      fileInput?.click();
    });

    fileInput?.addEventListener('change', () => {
      const files = Array.from(fileInput.files || []);
      selectedFiles = selectedFiles.concat(files).slice(0, 5);
      fileInput.value = '';
      renderFilePreview();
    });

    input?.addEventListener('paste', async function(e){
      const items = Array.from(e.clipboardData?.items || []);
      const pastedFiles = [];

      items.forEach(item => {
        if (item.kind === 'file') {
          const file = item.getAsFile();
          if (file) pastedFiles.push(file);
        }
      });

      if (!pastedFiles.length) return;

      e.preventDefault();

      selectedFiles = selectedFiles.concat(pastedFiles).slice(0, 5);
      await renderFilePreview();
      updateSendState();
    });

    async function startNewConversation(){
      selectedFiles = [];

      if (typeof renderFilePreview === 'function') {
        await renderFilePreview();
      }

      try {
        const res = await fetch(AI_START, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
          },
          body: JSON.stringify({}),
        });

        const data = await res.json();
        currentConversationId = data?.conversation?.id || null;

        messages.innerHTML = '';

        if (Array.isArray(data.messages) && data.messages.length) {
          data.messages.forEach(m => renderSaved(m.role, m.content, m.attachments || []));
          hideSuggestions();
        } else {
          messages.innerHTML = welcomeHTML;
          showSuggestions();
        }

        messages.scrollTop = messages.scrollHeight;
      } catch (e) {
        currentConversationId = null;
        messages.innerHTML = welcomeHTML;
        showSuggestions();
      }
    }

    async function loadConversations(){
      try {
        const res = await fetch(AI_CONVERSATIONS, {
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF,
          },
        });

        const data = await res.json();

        return Array.isArray(data.conversations) ? data.conversations : [];
      } catch (e) {
        return [];
      }
    }

    function renderConversations(conversations){
      if (!historyList) return;

      if (!conversations.length){
        historyList.innerHTML = '<div class="ai-history-empty">Aún no hay chats guardados.</div>';
        return;
      }

      historyList.innerHTML = conversations.map(conv => {
        const title = escapeHtml(conv.title || conv.snippet || 'Nuevo chat');
        const status = conv.status === 'active' ? 'Activo' : 'Cerrado';
        const time = escapeHtml(conv.time || '');
        const statusClass = conv.status === 'active' ? 'active' : '';

        return `
          <button type="button" class="ai-history-item" data-conversation-id="${conv.id}">
            <span class="ai-history-logo">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/>
              </svg>
            </span>
            <span class="ai-history-main">
              <span class="ai-history-title">${title}</span>
              <span class="ai-history-status ${statusClass}">${status}</span>
            </span>
            <span class="ai-history-time">${time}</span>
          </button>`;
      }).join('');

      historyList.querySelectorAll('.ai-history-item').forEach(item => {
        item.addEventListener('click', async () => {
          const id = item.dataset.conversationId;
          if (!id) return;

          await openConversation(id);
          showTab('chat');
        });
      });
    }

    async function loadAndRenderConversations(){
      if (historyList) {
        historyList.innerHTML = '<div class="ai-history-empty">Cargando historial...</div>';
      }

      const conversations = await loadConversations();
      renderConversations(conversations);
    }

    async function openConversation(id){
      try {
        const res = await fetch(`${AI_SHOW_BASE}/${id}`, {
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF,
          },
        });

        const data = await res.json();

        currentConversationId = data?.conversation?.id || id;
        messages.innerHTML = '';

        if (Array.isArray(data.messages) && data.messages.length) {
          data.messages.forEach(m => renderSaved(m.role, m.content));
          hideSuggestions();
        } else {
          messages.innerHTML = welcomeHTML;
          showSuggestions();
        }

        messages.scrollTop = messages.scrollHeight;
      } catch (e) {
        messages.innerHTML = welcomeHTML;
        showSuggestions();
      }
    }

    function renderSaved(role, text){
      const msg = document.createElement('div');
      msg.className = `ai-msg ${role}`;
      msg.style.animation = 'none';
      msg.style.opacity = '1';
      msg.style.transform = 'none';

      if (role === 'assistant') {
        msg.innerHTML = mdToHtml(text);
      } else {
        msg.textContent = text;
      }

      messages.appendChild(msg);
    }

    async function resetConversation(){
      try {
        await fetch(AI_RESET, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            conversation_id: currentConversationId,
          }),
        });
      } catch (e) {}

      clearMessages();
    }

    resetBtn?.addEventListener('click', resetConversation);

    async function openDrawer(){
      overlay.classList.add('open');
      drawer.classList.add('open');

      showTab('chat');

      await startNewConversation();

      setTimeout(() => input?.focus(), 140);
    }

    function closeDrawer(){
      overlay.classList.remove('open');
      drawer.classList.remove('open');
    }

    async function addMessage(role, text, files = []){
      const msg = document.createElement('div');
      msg.className = `ai-msg ${role}`;

      if (role === 'assistant') {
        msg.innerHTML = mdToHtml(text || '');
      } else {
        msg.textContent = text || 'Analiza este archivo.';
      }

      const imageFiles = files.filter(file => file.type.startsWith('image/'));
      const otherFiles = files.filter(file => !file.type.startsWith('image/'));

      if (imageFiles.length) {
        const box = document.createElement('div');
        box.className = 'ai-uploaded-images';

        for (const file of imageFiles) {
          const url = await fileToPreview(file);

          if (url) {
            const img = document.createElement('img');
            img.src = url;
            img.alt = file.name;
            box.appendChild(img);
          }
        }

        msg.appendChild(box);
      }

      if (otherFiles.length) {
        const note = document.createElement('div');
        note.className = 'ai-file-note';
        note.textContent = otherFiles.map(f => f.name).join(' · ');
        msg.appendChild(note);
      }

      messages.appendChild(msg);
      messages.scrollTop = messages.scrollHeight;
    }

    function escapeHtml(text){
      return String(text)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#039;');
    }

    function mdToHtml(text){
      const esc = s => escapeHtml(s);
      const lines = text.split('\n');
      let html = '', list = null;

      const flush = () => {
        if (list) {
          html += `</${list}>`;
          list = null;
        }
      };

      function fmt(s){
        return s
          .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
          .replace(/__(.+?)__/g, '<strong>$1</strong>');
      }

      lines.forEach(raw => {
        let line = raw.trim();

        if (!line) {
          flush();
          return;
        }

        const ol = line.match(/^\d+[.)]\s+(.*)$/);
        const ul = line.match(/^[-*]\s+(.*)$/);

        if (ol) {
          if (list !== 'ol') {
            flush();
            html += '<ol>';
            list = 'ol';
          }

          html += `<li>${fmt(esc(ol[1]))}</li>`;
        } else if (ul) {
          if (list !== 'ul') {
            flush();
            html += '<ul>';
            list = 'ul';
          }

          html += `<li>${fmt(esc(ul[1]))}</li>`;
        } else {
          flush();
          html += `<p>${fmt(esc(line))}</p>`;
        }
      });

      flush();

      return html;
    }

    function typeMessage(text){
      const msg = document.createElement('div');
      msg.className = 'ai-msg assistant';
      msg.innerHTML = mdToHtml(text);
      messages.appendChild(msg);
      messages.scrollTop = messages.scrollHeight;

      const walker = document.createTreeWalker(msg, NodeFilter.SHOW_TEXT);
      const nodes = [];
      let total = 0;

      while (walker.nextNode()) {
        const n = walker.currentNode;

        nodes.push({
          node: n,
          full: n.nodeValue,
          len: n.nodeValue.length,
        });

        total += n.nodeValue.length;
        n.nodeValue = '';
      }

      let shown = 0;
      const speed = 10;
      const step = total > 400 ? 3 : 1;

      (function tick(){
        if (shown >= total) return;

        shown = Math.min(total, shown + step);

        let acc = 0;

        for (const item of nodes) {
          if (shown <= acc) {
            item.node.nodeValue = '';
          } else if (shown >= acc + item.len) {
            item.node.nodeValue = item.full;
          } else {
            item.node.nodeValue = item.full.slice(0, shown - acc);
          }

          acc += item.len;
        }

        messages.scrollTop = messages.scrollHeight;
        setTimeout(tick, speed);
      })();
    }

    async function sendToAI(text, files = []){
      const typing = document.createElement('div');
      typing.className = 'ai-typing';
      typing.innerHTML = '<span></span><span></span><span></span>';
      messages.appendChild(typing);
      messages.scrollTop = messages.scrollHeight;

      try {
        const formData = new FormData();

        formData.append('message', text || 'Analiza este archivo y dime para qué sirve dentro del sistema.');

        if (currentConversationId) {
          formData.append('conversation_id', currentConversationId);
        }

        files.forEach(file => {
          formData.append('attachments[]', file);
        });

        const res = await fetch(AI_ENDPOINT, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
          },
          body: formData,
        });

        let data = null;

        try {
          data = await res.json();
        } catch (e) {
          data = null;
        }

        typing.remove();

        if (!res.ok) {
          const errorMessage =
            data?.message ||
            data?.reply ||
            'El servidor rechazó la solicitud. Revisa el controlador, validación o logs de Laravel.';

          typeMessage(errorMessage);
          return;
        }

        if (data?.conversation_id) {
          currentConversationId = data.conversation_id;
        }

        typeMessage(data?.reply || 'No recibí respuesta. Intenta de nuevo.');
      } catch (e) {
        typing.remove();
        typeMessage('No pude conectar con la IA. Revisa la ruta ia.chat o los logs de Laravel.');
      }
    }

    openBtn?.addEventListener('click', openDrawer);
    closeBtn?.addEventListener('click', closeDrawer);

    overlay?.addEventListener('click', function(e){
      if (e.target === overlay) closeDrawer();
    });

    document.addEventListener('keydown', function(e){
      if (e.key === 'Escape') closeDrawer();
    });

    chips.forEach(chip => {
      chip.addEventListener('click', async function(){
        const prompt = this.dataset.prompt || '';

        if (!prompt) return;

        overlay.classList.add('open');
        drawer.classList.add('open');

        showTab('chat');

        await startNewConversation();

        input.value = prompt;
        updateSendState();
        hideSuggestions();

        form.dispatchEvent(new Event('submit', {
          cancelable: true,
          bubbles: true,
        }));
      });
    });

    form?.addEventListener('submit', async function(e){
      e.preventDefault();

      const text = input.value.trim();
      const filesToSend = [...selectedFiles];

      if (!text && !filesToSend.length) return;

      await addMessage('user', text || 'Analiza este archivo.', filesToSend);

      hideSuggestions();

      input.value = '';
      selectedFiles = [];
      await renderFilePreview();
      updateSendState();

      sendToAI(text, filesToSend);
    });

    window.enclaiiAiLoadConversations = loadConversations;
    window.enclaiiAiOpenConversation = openConversation;
  })();

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

  /* Dashboard Editor */
  (function(){
    const WIDGETS = [
      {id:'next-patient',    name:'Próximo Paciente',   desc:'Paciente actual y hora',     group:'Pacientes', color:'blue',   default:true},
      {id:'next-list',       name:'Pacientes Pendientes', desc:'Lista de pacientes de hoy', group:'Pacientes', color:'blue',   default:true},
      {id:'ia-pending',      name:'Reporte IA',         desc:'Reportes pendientes de IA',   group:'IA',         color:'purple', default:true},
      {id:'ia-recs',         name:'Recomendaciones IA', desc:'Sugerencias clínicas',        group:'IA',         color:'purple', default:false},
      {id:'agenda-today',    name:'Agenda del día',     desc:'Calendario y citas',          group:'Agenda',     color:'teal',   default:true},
      {id:'agenda-summary',  name:'Resumen de estudios', desc:'Dona y próximos estudios',   group:'Agenda',     color:'teal',   default:true},
      {id:'new-study',       name:'Acciones rápidas',   desc:'Nuevo estudio, WhatsApp…',    group:'Estudio',    color:'green',  default:true},
      {id:'gallery-recent',  name:'Últimos Estudios',   desc:'Galería reciente',            group:'Galería',    color:'green2', default:false},
      {id:'system-status',   name:'Estado General',     desc:'Cámara, IA, Nube',            group:'Sistema',    color:'orange', default:false},
      {id:'reminders',       name:'Recordatorios',      desc:'Avisos del sistema',          group:'Sistema',    color:'orange', default:false},
    ];

    function loadPrefs() {
      try { return JSON.parse(localStorage.getItem('dbWidgetPrefs') || '{}'); } catch(e) { return {}; }
    }
    function savePrefs(prefs) {
      try { localStorage.setItem('dbWidgetPrefs', JSON.stringify(prefs)); } catch(e) {}
    }

    /* Pestañas del editor */
    (function(){
      const tabs = document.querySelectorAll('.db-editor-tab');
      const panels = document.querySelectorAll('.db-editor-panel');
      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          const target = tab.dataset.tab;
          tabs.forEach(t => t.classList.remove('active'));
          panels.forEach(p => p.classList.remove('active'));
          tab.classList.add('active');
          document.getElementById('dbEditorPanel' + target.charAt(0).toUpperCase() + target.slice(1))?.classList.add('active');
        });
      });
    })();

    /* Modo de vista del dashboard */
    (function(){
      const grid = document.getElementById('widgetGrid');
      const originalBtn = document.getElementById('dbModeEditorOriginal');
      const minimalBtn = document.getElementById('dbModeEditorMinimal');
      if (!grid || !originalBtn || !minimalBtn) return;

      function applyMode(mode) {
        const isMinimal = mode === 'minimal';
        const originalGrid = document.getElementById('widgetGrid');
        const minimalGrid = document.getElementById('widgetGridMinimal');
        if (originalGrid) {
          originalGrid.classList.toggle('dashboard-mode-min', isMinimal);
          originalGrid.style.display = isMinimal ? 'none' : '';
        }
        if (minimalGrid) {
          minimalGrid.classList.toggle('dashboard-mode-min', !isMinimal);
          minimalGrid.style.display = !isMinimal ? 'none' : '';
        }
        originalBtn.classList.toggle('active', !isMinimal);
        originalBtn.setAttribute('aria-pressed', String(!isMinimal));
        minimalBtn.classList.toggle('active', isMinimal);
        minimalBtn.setAttribute('aria-pressed', String(isMinimal));
        try { localStorage.setItem('dbMode', mode); } catch(e) {}
        if (originalGrid) originalGrid.offsetHeight;
        if (minimalGrid) minimalGrid.offsetHeight;
        if (window.applyWidgetSizeLimits) window.applyWidgetSizeLimits();
        requestAnimationFrame(() => {
          if (window.applyWidgetSizeLimits) window.applyWidgetSizeLimits();
        });
        setTimeout(() => {
          if (originalGrid) originalGrid.offsetHeight;
          if (minimalGrid) minimalGrid.offsetHeight;
          if (window.applyWidgetSizeLimits) window.applyWidgetSizeLimits();
        }, 120);
        setTimeout(() => {
          if (window.applyWidgetSizeLimits) window.applyWidgetSizeLimits();
        }, 350);

        // Asegura que las preferencias de visibilidad se apliquen al grid visible.
        try {
          const prefs = JSON.parse(localStorage.getItem('dbWidgetPrefs') || '{}');
          window.dispatchEvent(new CustomEvent('dbWidgetsChanged', {detail: prefs}));
        } catch(e) {}
      }

      originalBtn.addEventListener('click', () => {
        try { localStorage.setItem('dbMode', 'original'); } catch(e) {}
        window.location.reload();
      });
      minimalBtn.addEventListener('click', () => {
        try { localStorage.setItem('dbMode', 'minimal'); } catch(e) {}
        window.location.reload();
      });

      let savedMode = 'original';
      try { savedMode = localStorage.getItem('dbMode') || 'original'; } catch(e) {}
      applyMode(savedMode);
    })();

    function openEditor() {
      document.getElementById('profileMenu').classList.remove('open');
      document.querySelector('.profile-wrap')?.classList.remove('open');

      const overlay = document.getElementById('dbEditorOverlay');
      const panel   = document.getElementById('dbEditorPanel');
      if (!overlay || !panel) return;

      const prefs = loadPrefs();
      const body  = document.getElementById('dbEditorBody');
      body.innerHTML = '';

      /* Leer widgets reales del DOM en tiempo real (solo originales) */
      const ids = Array.from(new Set(
        Array.from(document.querySelectorAll('#widgetGrid .widget:not(.widget-minimal)'))
          .map(w => w.dataset.widgetId)
          .filter(Boolean)
      ));
      const presentWidgets = ids.map(id => {
        const meta = WIDGETS.find(w => w.id === id);
        return meta || {id, name: id, desc: '', group: 'Otros', color: 'blue', default: true};
      });

      const groupOrder = [];
      const groups = {};
      presentWidgets.forEach(w => {
        const g = w.group;
        if (!groups[g]) { groups[g] = []; groupOrder.push(g); }
        groups[g].push(w);
      });

      groupOrder.forEach(group => {
        const widgets = groups[group];
        const sec = document.createElement('div');
        sec.className = 'db-editor-section';
        sec.innerHTML = `<div class="db-editor-section-title">${group}</div>`;
        widgets.forEach(w => {
          const enabled = prefs[w.id] !== undefined ? prefs[w.id] : w.default;
          const item = document.createElement('div');
          item.className = 'db-widget-item';
          item.dataset.wid = w.id;
          item.innerHTML = `
            <span class="db-widget-dot ${w.color}"></span>
            <div class="db-widget-info">
              <div class="db-widget-name">${w.name}</div>
              <div class="db-widget-desc">${w.desc}</div>
            </div>
            <label class="db-widget-toggle">
              <input type="checkbox" ${enabled ? 'checked' : ''} data-wid="${w.id}">
              <span class="db-widget-slider"></span>
            </label>`;
          sec.appendChild(item);
        });
        body.appendChild(sec);
      });

      /* Actualizar dashboard en tiempo real al togglear */
      body.querySelectorAll('input[data-wid]').forEach(cb => {
        cb.addEventListener('change', () => {
          const livePrefs = {};
          body.querySelectorAll('input[data-wid]').forEach(i => {
            livePrefs[i.dataset.wid] = i.checked;
          });
          window.dispatchEvent(new CustomEvent('dbWidgetsChanged', {detail: livePrefs}));
        });
      });

      overlay.classList.add('open');
      panel.classList.add('open');
    }

    function closeEditor() {
      document.getElementById('dbEditorOverlay')?.classList.remove('open');
      document.getElementById('dbEditorPanel')?.classList.remove('open');
    }

    function saveEditor() {
      const prefs = {};
      document.querySelectorAll('#dbEditorBody input[data-wid]').forEach(cb => {
        prefs[cb.dataset.wid] = cb.checked;
      });
      savePrefs(prefs);
      closeEditor();
      window.dispatchEvent(new CustomEvent('dbWidgetsChanged', {detail: prefs}));
    }

    function resetEditor() {
      savePrefs({});
      const prefs = {};
      document.querySelectorAll('#dbEditorBody input[data-wid]').forEach(cb => {
        const w = WIDGETS.find(x => x.id === cb.dataset.wid);
        if (w) cb.checked = w.default;
        prefs[cb.dataset.wid] = w ? w.default : true;
      });
      window.dispatchEvent(new CustomEvent('dbWidgetsChanged', {detail: prefs}));
    }

    document.getElementById('editDashboardBtn')?.addEventListener('click', openEditor);
    document.getElementById('dbEditorClose')?.addEventListener('click', closeEditor);
    document.getElementById('dbEditorSave')?.addEventListener('click', saveEditor);
    document.getElementById('dbEditorReset')?.addEventListener('click', resetEditor);
    document.getElementById('dbEditorOverlay')?.addEventListener('click', function(e) {
      if (e.target === this) closeEditor();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEditor(); });
  })();
</script>

{{-- Modal de alerta genérico --}}
<div class="app-alert-overlay" id="appAlertOverlay" onclick="if(event.target===this) hideAppAlert();">
  <div class="app-alert-modal" role="alertdialog" aria-modal="true" aria-labelledby="appAlertTitle" aria-describedby="appAlertMessage">
    <div class="app-alert-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <h3 class="app-alert-title" id="appAlertTitle">Aviso</h3>
    <p class="app-alert-message" id="appAlertMessage">Mensaje</p>
    <button class="app-alert-btn" id="appAlertBtn" onclick="hideAppAlert()">Aceptar</button>
  </div>
</div>
<script>
  function showAppAlert(title, message, callback) {
    const overlay = document.getElementById('appAlertOverlay');
    const titleEl = document.getElementById('appAlertTitle');
    const messageEl = document.getElementById('appAlertMessage');
    const btn = document.getElementById('appAlertBtn');
    if (!overlay) return;
    window._appAlertCallback = callback || null;
    if (titleEl) titleEl.textContent = title || 'Aviso';
    if (messageEl) messageEl.textContent = message || '';
    if (btn) btn.textContent = 'Aceptar';
    overlay.classList.add('open');
  }
  function hideAppAlert() {
    const overlay = document.getElementById('appAlertOverlay');
    if (overlay) overlay.classList.remove('open');
    if (typeof window._appAlertCallback === 'function') {
      window._appAlertCallback();
      window._appAlertCallback = null;
    }
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('appAlertOverlay')?.classList.contains('open')) {
      hideAppAlert();
    }
  });
</script>

@stack('scripts')

<script>
(function(){
  const bell     = document.getElementById('notifBell');
  const panel    = document.getElementById('notifPanel');
  const dot      = document.getElementById('notifDot');
  const list     = document.getElementById('notifList');
  const empty    = document.getElementById('notifEmpty');
  const clearBtn = document.getElementById('notifClear');
  if (!bell || !panel) return;

  let unread = 0;
  const pendingIds = new Set();
  const notifData = new Map();
  let clearedByUser = sessionStorage.getItem('notif_cleared') === '1';

  function $pv(id) { return document.getElementById(id); }

  function openPanel(){ panel.classList.add('open'); }
  function closePanel(){ panel.classList.remove('open'); }

  bell.addEventListener('click', (e) => {
    e.stopPropagation();
    panel.classList.toggle('open');
    if (panel.classList.contains('open')) markAllAsRead();
  });

  document.addEventListener('click', (e) => {
    if (!document.getElementById('notifWrap')?.contains(e.target)) closePanel();
  });

  clearBtn?.addEventListener('click', () => {
    const ids = Array.from(list.querySelectorAll('.notif-item')).map(el => parseInt(el.dataset.id, 10)).filter(Boolean);
    if (ids.length) {
      fetch('/notifications', {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ ids }),
      }).catch(() => {});
    }
    list.querySelectorAll('.notif-item').forEach(n => n.remove());
    pendingIds.clear();
    unread = 0;
    clearedByUser = true;
    sessionStorage.setItem('notif_cleared', '1');
    updateDot();
    if (empty) empty.style.display = '';
  });

  function updateDot(){
    if (!dot) return;
    if (unread > 0){ dot.textContent = unread > 99 ? '99+' : unread; dot.style.display = ''; }
    else { dot.style.display = 'none'; }
  }

  const NOTIF_ICONS = {
    bell:        '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/>',
    plus:        '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
    check:       '<polyline points="20 6 9 17 4 12"/>',
    x:           '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
    trash:       '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>',
    megaphone:   '<path d="M3 11l19-9-9 19-2-8-8-2z"/>',
    radio:       '<path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"/><path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"/><circle cx="12" cy="12" r="2"/><path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"/><path d="M19.1 4.9C23 8.8 23 15.2 19.1 19.1"/>',
    wrench:      '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
    doc:         '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
    rocket:      '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>',
    download:    '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/>',
    ticket:      '<path d="M2 12h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H2V6h2a2 2 0 0 0 2-2V2"/><path d="M22 12h-2a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2V6h-2a2 2 0 0 1-2-2V2"/><path d="M7 2h10"/><path d="M7 22h10"/><rect x="7" y="6" width="10" height="12" rx="1"/>',
  };

  function cfgFor(e) {
    const tipo = e.categoria || e.tipo;
    return {
      nueva:              { title: 'Nueva cita agendada',        type: 'blue',   icon: 'plus' },
      pendiente:          { title: 'Cita en espera',             type: 'amber',  icon: 'bell' },
      cancelada:          { title: 'Cita cancelada',             type: 'red',    icon: 'x' },
      completada:         { title: 'Cita completada',            type: 'green',  icon: 'check' },
      eliminada:          { title: 'Cita eliminada',             type: 'red',    icon: 'trash' },
      estudio_completado: { title: 'Estudio completado',         type: 'green',  icon: 'check' },
      estado:             { title: 'Estado de cita cambiado',    type: 'blue',   icon: 'bell' },
      reprogramada:       { title: 'Cita reprogramada',          type: 'amber',  icon: 'bell' },
      recordatorio_1h:    { title: 'Recordatorio: cita en 1h',  type: 'amber',  icon: 'bell' },
      recordatorio_24h:   { title: 'Recordatorio: cita mañana', type: 'blue',   icon: 'bell' },
      anuncio:            { title: e.titulo || 'Nuevo anuncio',  type: 'blue',   icon: 'megaphone' },
      desktop_app_update: { title: e.titulo || 'Nueva app de escritorio', type: 'blue', icon: 'download' },
      ticket_resuelto:    { title: e.subject || 'Ticket resuelto', type: 'green', icon: 'ticket' },
      ticket:             { title: e.titulo || 'Nuevo ticket',   type: 'amber',  icon: 'ticket' },
      notificacion:       { title: e.titulo || 'Notificación',   type: 'blue',   icon: 'bell' },
      anuncios_internos:  { title: e.titulo || 'Comunicado',     type: 'blue',   icon: 'radio' },
      mejoras:            { title: e.titulo || 'Mejoras',        type: 'green',  icon: 'rocket' },
      mantenimiento:      { title: e.titulo || 'Mantenimiento',  type: 'amber',  icon: 'wrench' },
      politicas:          { title: e.titulo || 'Política',       type: 'blue',   icon: 'doc' },
    }[tipo] ?? { title: e.titulo || 'Notificación', type: 'blue', icon: 'bell' };
  }

  const TIPO_LABELS = {
    notificacion: 'Notificación', anuncios_internos: 'Comunicado interno',
    mejoras: 'Mejoras en Enclaii', mantenimiento: 'Mantenimiento', politicas: 'Política',
  };

  function bodyFor(item) {
    const tipo = item.categoria || item.tipo;
    if (tipo === 'anuncio' || ['notificacion','anuncios_internos','mejoras','mantenimiento','politicas'].includes(tipo)) {
      return TIPO_LABELS[tipo] || TIPO_LABELS[item.categoria] || 'Anuncio';
    }
    if (tipo === 'ticket') {
      const parts = [item.user_name, item.folio, item.subject].filter(Boolean);
      return parts.length ? parts.join(' — ') : 'Nuevo ticket recibido';
    }
    if (tipo === 'ticket_resuelto') {
      const parts = [item.resolution_summary, item.resolver_name].filter(Boolean);
      return parts.length ? parts.join(' — ') : 'Tu ticket ha sido resuelto';
    }
    if (tipo === 'desktop_app_update') {
      const version = item.version ? `v${item.version}` : 'nueva version';
      const meta = [version, item.platform, item.size].filter(Boolean).join(' — ');
      return meta ? `${meta} — Descargar actualizacion` : (item.message || 'Descarga la nueva aplicacion de escritorio');
    }
    if (tipo === 'reprogramada') {
      const paciente = item.paciente || 'Paciente';
      const fecha = item.fecha || '';
      const hora = item.hora || '';
      const horaAnterior = item.hora_anterior || '';
      const fechaAnterior = item.fecha_anterior || '';
      return `${paciente} — Fecha: ${fecha} — Hora anterior: ${horaAnterior} — Nueva: ${hora}`;
    }
    const parts = [item.paciente, item.fecha, item.hora].filter(Boolean);
    return parts.length ? parts.join(' — ') : (item.message || item.body || '');
  }

  function timeFrom(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return isNaN(d) ? '' : d.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
  }

  function addNotif({ title, body, type = 'blue', icon = 'bell', id = null, read = false, time = null, prepend = true, data = null }){
    if (empty) empty.style.display = 'none';

    const isPreview = data && (data.contenido || data.categoria || data.tipo === 'anuncio');
    const svgPath = NOTIF_ICONS[icon] ?? NOTIF_ICONS.bell;
    const item = document.createElement('div');
    item.className = 'notif-item' + (read ? ' read' : '');
    if (id) item.dataset.id = id;
    if (isPreview) item.dataset.preview = '1';
    item.innerHTML = `
      <div class="notif-ico ${type}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">${svgPath}</svg>
      </div>
      <div class="notif-body">
        <strong>${title}</strong>
        <span>${body}</span>
        <time>${time || timeFrom(new Date())}</time>
      </div>`;

    if (id && data) notifData.set(id, data);

    item.addEventListener('click', () => {
      if (isPreview) openPreview(id);
      else if (data && data.ticket_url) window.location.href = data.ticket_url + '#respuesta';
      else if (data && data.download_url) window.location.href = data.download_url;
    });

    if (prepend) list.prepend(item);
    else list.appendChild(item);

    if (!read && !pendingIds.has(id)) {
      unread++;
      if (id) pendingIds.add(id);
      updateDot();
    }
    return item;
  }

  const PV_THEMES = {
    notificacion:      { cls: 'theme-notificacion',       badge: '🔔 Notificación',            icon: '' },
    anuncios_internos: { cls: 'theme-anuncios_internos',  badge: '📋 Comunicado Interno',       icon: '' },
    mejoras:           { cls: 'theme-mejoras',            badge: '🚀 Mejoras en Enclaii',       icon: '' },
    mantenimiento:     { cls: 'theme-mantenimiento',      badge: '⚠️ Aviso de Mantenimiento',  icon: '🔧' },
    politicas:         { cls: 'theme-politicas',          badge: '📄 Documento de Política',    icon: '' },
  };

  function openPreview(id) {
    const data = notifData.get(id);
    if (!data) return;
    const cat = data.categoria || data.tipo || 'notificacion';
    const theme = PV_THEMES[cat] || PV_THEMES.notificacion;
    const b = $pv('notifPreviewBody');
    const o = $pv('notifPreviewOv');
    if (b) {
      b.innerHTML =
        '<div class="notif-pv-card ' + theme.cls + '" style="position:relative">'
        + '<button class="notif-pv-close" id="notifPreviewClose" type="button" aria-label="Cerrar">✕</button>'
        + (theme.icon ? '<span class="pv-icon">' + theme.icon + '</span>' : '')
        + '<span class="pv-badge">' + theme.badge + '</span>'
        + '<h2>' + (data.titulo || 'Sin título') + '</h2>'
        + '<div class="meta">' + (theme.badge.replace(/^[^\s]+\s/, '') || cat) + ' • Todos</div>'
        + '<div class="body">' + (data.contenido || data.message || 'Sin contenido') + '</div>'
        + '</div>';
    }
    if (o) o.classList.add('open');
  }

  function closePreview() {
    const o = $pv('notifPreviewOv');
    if (o) o.classList.remove('open');
  }

  document.addEventListener('click', (e) => {
    if (e.target.closest('#notifPreviewClose')) closePreview();
    if (e.target.id === 'notifPreviewOv') closePreview();
  });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closePreview(); });

  function markAllAsRead() {
    if (!unread && !pendingIds.size) return;
    fetch('/notifications/read-all', {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        'Accept': 'application/json',
      },
    }).catch(() => {});
    pendingIds.clear();
    unread = 0;
    updateDot();
    list.querySelectorAll('.notif-item').forEach(el => el.classList.add('read'));
  }

  function renderNotifications(items) {
    if (!Array.isArray(items) || !items.length) {
      if (empty) empty.style.display = '';
      return;
    }
    if (empty) empty.style.display = 'none';
    items.forEach(item => {
      if (pendingIds.has(item.id)) return;
      const cfg = cfgFor(item);
      addNotif({
        id: item.id,
        title: cfg.title,
        body: bodyFor(item),
        type: cfg.type,
        icon: cfg.icon,
        read: item.read,
        time: timeFrom(item.created_at),
        prepend: false,
        data: item,
      });
      if (!item.read) pendingIds.add(item.id);
    });
  }

  function pollNotifications() {
    fetch('/notifications', { headers: { 'Accept': 'application/json' } })
      .then(r => r.ok ? r.json() : [])
      .then(items => {
        if (clearedByUser) {
          if (!items.length) return;
          clearedByUser = false;
          sessionStorage.removeItem('notif_cleared');
        }
        list.querySelectorAll('.notif-item').forEach(n => n.remove());
        pendingIds.clear();
        unread = 0;
        updateDot();
        renderNotifications(items);
      })
      .catch(() => {});
  }

  @auth
  let _pollInterval = null;

  function startPolling() {
    if (_pollInterval) return;
    pollNotifications();
    _pollInterval = setInterval(pollNotifications, 30000);
  }

  function stopPolling() {
    clearInterval(_pollInterval);
    _pollInterval = null;
    list.querySelectorAll('.notif-item').forEach(n => n.remove());
    pendingIds.clear();
    unread = 0;
    updateDot();
    if (empty) empty.style.display = '';
  }

  startPolling();
  @endauth
})();
</script>
{{-- Preview modal para anuncios --}}
<div class="notif-preview-ov" id="notifPreviewOv">
  <div class="notif-preview-box">
    <div class="notif-preview-body" id="notifPreviewBody"></div>
  </div>
</div>
</body>
</html>
