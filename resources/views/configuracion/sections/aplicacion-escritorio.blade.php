@php
  $desktopAppRelease = \App\Support\DesktopAppRelease::current();
  $desktopAppVersion = $desktopAppRelease['version'];
  $desktopAppArchitecture = $desktopAppRelease['architecture'];
  $desktopAppSize = $desktopAppRelease['size'];
  $desktopAppManualUrl = asset('docs/manual-instalacion-enclaii-endoscopy.pdf');
@endphp

@push('styles')
<style>
.desktop-app-shell{display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:14px;align-items:start}
.desktop-app-main{padding:0;overflow:hidden;background:
  radial-gradient(circle at 70% 20%,rgba(56,199,244,.18),transparent 28%),
  linear-gradient(145deg,rgba(8,22,52,.98),rgba(5,10,30,.98));border-color:rgba(77,142,255,.25)}
.desktop-app-hero{position:relative;min-height:430px;padding:26px 28px 22px;overflow:hidden}
.desktop-app-hero::before{content:"";position:absolute;inset:0;background:
  linear-gradient(135deg,rgba(32,150,255,.12),transparent 42%),
  repeating-linear-gradient(145deg,rgba(56,199,244,.06) 0 1px,transparent 1px 58px);opacity:.7;pointer-events:none}
.desktop-app-head{position:relative;z-index:2;display:flex;align-items:flex-start;gap:14px;margin-bottom:22px}
.desktop-app-head-ico{width:43px;height:43px;border-radius:10px;display:grid;place-items:center;color:var(--cyan);border:1px solid rgba(56,199,244,.4);background:rgba(56,199,244,.1)}
.desktop-app-head-ico svg{width:25px;height:25px}
.desktop-app-head h2{font-family:'Sora',sans-serif;font-size:18px;font-weight:800;margin:0}
.desktop-app-head p{font-size:12.5px;color:var(--txt-soft);margin:4px 0 0;max-width:720px}
.desktop-app-copy{position:relative;z-index:2;width:min(360px,48%);padding-top:10px}
.desktop-app-badge{display:inline-flex;align-items:center;gap:7px;padding:6px 10px;border-radius:8px;background:rgba(0,179,255,.14);border:1px solid rgba(56,199,244,.28);color:var(--cyan);font-size:10px;font-weight:900;letter-spacing:.08em;text-transform:uppercase}
.desktop-app-badge svg{width:13px;height:13px}
.desktop-app-copy h3{font-family:'Sora',sans-serif;font-size:31px;line-height:1.06;font-weight:900;margin:18px 0 12px;letter-spacing:0;color:var(--txt)}
.desktop-app-copy h3 span{display:block;color:var(--cyan)}
.desktop-app-copy p{font-size:12.8px;line-height:1.62;color:var(--txt-soft);margin:0 0 17px}
.desktop-app-features{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:11px}
.desktop-app-features li{display:flex;align-items:center;gap:10px;font-size:12px;color:var(--txt)}
.desktop-app-features svg{width:15px;height:15px;color:var(--cyan);flex:none}
.desktop-app-visual{position:absolute;right:28px;bottom:112px;width:min(520px,56%);z-index:1;filter:drop-shadow(0 36px 38px rgba(0,0,0,.45))}
.desktop-laptop{position:relative;width:100%;aspect-ratio:1.95/1}
.desktop-laptop-screen{position:absolute;left:9%;right:9%;top:0;bottom:11%;border-radius:15px 15px 8px 8px;background:#03091b;border:2px solid rgba(125,174,255,.22);box-shadow:inset 0 0 0 1px rgba(255,255,255,.06);padding:12px}
.desktop-laptop-ui{width:100%;height:100%;border-radius:10px;background:linear-gradient(135deg,#061431,#020617);display:grid;grid-template-columns:82px 1fr 138px;gap:10px;padding:11px;overflow:hidden}
.desktop-laptop-sidebar{display:flex;flex-direction:column;gap:8px}
.desktop-laptop-logo{height:18px;border-radius:5px;background:rgba(56,199,244,.16);width:54px}
.desktop-laptop-nav{height:24px;border-radius:6px;background:rgba(45,120,255,.22)}
.desktop-laptop-nav:nth-child(n+3){background:rgba(120,160,255,.08)}
.desktop-laptop-capture{border-radius:12px;background:radial-gradient(circle at 52% 50%,#a35a55 0 12%,#75383f 13% 27%,#401d25 28% 50%,#1b1119 51% 100%);border:1px solid rgba(255,255,255,.08);box-shadow:inset 0 0 36px rgba(0,0,0,.55)}
.desktop-laptop-panel{display:flex;flex-direction:column;gap:8px}
.desktop-laptop-panel div{height:28px;border-radius:7px;background:rgba(110,160,255,.08);border:1px solid rgba(110,160,255,.08)}
.desktop-laptop-panel div:nth-child(4){margin-top:auto;height:38px;background:linear-gradient(90deg,rgba(61,220,151,.25),rgba(56,199,244,.12))}
.desktop-laptop-base{position:absolute;left:1%;right:1%;bottom:0;height:13%;border-radius:8px 8px 24px 24px;background:linear-gradient(180deg,#1d2940,#071123);border:1px solid rgba(125,174,255,.16)}
.desktop-laptop-base::after{content:"";position:absolute;left:38%;right:38%;top:0;height:5px;border-radius:0 0 8px 8px;background:rgba(255,255,255,.16)}
.desktop-app-downloads{position:relative;z-index:3;margin-top:34px;padding:0 28px 24px}
.desktop-app-downloads h3{font-family:'Sora',sans-serif;font-size:16px;font-weight:800;margin:0 0 6px}
.desktop-app-downloads p{font-size:12.2px;color:var(--txt-soft);margin:0 0 12px}
.desktop-download-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.desktop-download-card{display:grid;grid-template-columns:58px 1fr;gap:14px;padding:16px;border-radius:10px;border:1px solid rgba(110,160,255,.22);background:rgba(8,18,46,.76);min-width:0}
.desktop-download-card.disabled{opacity:.62}
.desktop-os-icon{width:54px;height:54px;border-radius:12px;display:grid;place-items:center;background:rgba(56,199,244,.1);color:var(--cyan)}
.desktop-os-icon svg{width:32px;height:32px}
.desktop-os-icon.apple{color:#f8fafc;background:rgba(255,255,255,.08)}
.desktop-download-title{display:flex;align-items:center;gap:9px;flex-wrap:wrap;font-family:'Sora',sans-serif;font-size:15px;font-weight:800}
.desktop-version-pill{font-size:10px;font-weight:800;color:var(--cyan);background:rgba(56,199,244,.13);border:1px solid rgba(56,199,244,.22);padding:3px 7px;border-radius:7px}
.desktop-download-meta{font-size:11.5px;color:var(--txt-soft);margin:3px 0 10px}
.desktop-download-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:min(230px,100%);height:36px;border-radius:7px;background:linear-gradient(135deg,var(--blue),#19a7ff);color:#fff;font-size:12px;font-weight:800;box-shadow:0 12px 24px rgba(45,120,255,.25);transition:transform .15s,filter .15s}
.desktop-download-btn svg{width:15px;height:15px}
.desktop-download-btn:active{transform:scale(.98)}
@media (hover:hover){.desktop-download-btn:hover{filter:brightness(1.08)}}
.desktop-download-btn.disabled{background:rgba(110,160,255,.14);color:var(--txt-soft);box-shadow:none;cursor:not-allowed}
.desktop-download-size{font-size:11px;color:var(--txt-soft);margin-top:9px;text-align:center;width:min(230px,100%)}
.desktop-security{margin-top:10px;padding:18px 22px;display:grid;grid-template-columns:50px 1fr auto;gap:16px;align-items:center;background:linear-gradient(135deg,rgba(8,28,66,.96),rgba(5,13,36,.96));border-color:rgba(77,142,255,.24)}
.desktop-security-icon{width:50px;height:50px;border-radius:15px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.11);border:1px solid rgba(56,199,244,.25)}
.desktop-security-icon svg{width:28px;height:28px}
.desktop-security b{font-family:'Sora',sans-serif;font-size:13.5px}
.desktop-security p{font-size:12px;color:var(--txt-soft);line-height:1.45;margin:4px 0 0}
.desktop-security-cloud{width:118px;height:60px;border-radius:26px;background:radial-gradient(circle at 65% 35%,rgba(56,199,244,.5),transparent 28%),linear-gradient(135deg,rgba(45,120,255,.26),rgba(56,199,244,.14));display:grid;place-items:center;color:var(--cyan);border:1px solid rgba(56,199,244,.18)}
.desktop-security-cloud svg{width:30px;height:30px}
.desktop-app-side{display:flex;flex-direction:column;gap:12px}
.desktop-side-card{padding:18px;background:linear-gradient(145deg,rgba(8,20,48,.96),rgba(5,10,30,.98));border-color:rgba(77,142,255,.22)}
.desktop-side-title{display:flex;align-items:center;justify-content:space-between;gap:10px;font-family:'Sora',sans-serif;font-size:13.5px;font-weight:800;margin-bottom:16px}
.desktop-side-title span{display:flex;align-items:center;gap:10px}
.desktop-side-title svg{width:19px;height:19px;color:var(--cyan)}
.desktop-side-section{display:grid;grid-template-columns:30px 1fr;gap:12px;padding:0 0 17px;border-bottom:1px solid rgba(110,160,255,.14);margin-bottom:16px}
.desktop-side-section:last-child{border-bottom:0;margin-bottom:0;padding-bottom:0}
.desktop-side-os{width:28px;height:28px;display:grid;place-items:center;color:var(--cyan)}
.desktop-side-os svg{width:23px;height:23px}
.desktop-side-section h4{font-family:'Sora',sans-serif;font-size:13px;font-weight:800;margin:0 0 8px}
.desktop-side-list{margin:0;padding-left:15px;display:flex;flex-direction:column;gap:7px}
.desktop-side-list li{font-size:11.6px;line-height:1.35;color:var(--txt-soft)}
.desktop-release-list{margin:0;padding-left:16px;display:flex;flex-direction:column;gap:8px}
.desktop-release-list li{font-size:11.8px;color:var(--txt-soft);line-height:1.35}
.desktop-side-link{display:inline-flex;align-items:center;gap:8px;margin-top:14px;color:var(--cyan);font-size:12px;font-weight:800}
.desktop-guide{display:grid;grid-template-columns:1fr 64px;gap:16px;align-items:center;color:inherit;text-decoration:none;cursor:pointer;transition:transform .15s,filter .15s,border-color .15s}
.desktop-guide p{font-size:12px;color:var(--txt-soft);line-height:1.45;margin:4px 0 0}
.desktop-guide-art{width:60px;height:72px;border-radius:14px;background:linear-gradient(135deg,#1a8cff,#36d5ff);display:grid;place-items:center;color:#fff;box-shadow:0 18px 32px rgba(45,120,255,.32)}
.desktop-guide-art svg{width:34px;height:34px}
.desktop-guide:focus-visible{outline:3px solid rgba(56,199,244,.35);outline-offset:3px}
@media (hover:hover){.desktop-guide:hover{filter:brightness(1.06);border-color:rgba(56,199,244,.38);transform:translateY(-1px)}}
html[data-theme="light"] .desktop-app-main{
  background:
    radial-gradient(circle at 76% 18%,rgba(14,165,233,.16),transparent 30%),
    linear-gradient(145deg,#fff,#f4f9ff);
  border-color:rgba(15,23,42,.08);
  box-shadow:0 18px 45px rgba(15,23,42,.08);
}
html[data-theme="light"] .desktop-app-hero::before{
  background:
    linear-gradient(135deg,rgba(14,165,233,.12),transparent 45%),
    repeating-linear-gradient(145deg,rgba(37,99,235,.07) 0 1px,transparent 1px 58px);
  opacity:.75;
}
html[data-theme="light"] .desktop-app-head h2,
html[data-theme="light"] .desktop-app-copy h3,
html[data-theme="light"] .desktop-app-downloads h3,
html[data-theme="light"] .desktop-side-title,
html[data-theme="light"] .desktop-side-section h4,
html[data-theme="light"] .desktop-security b,
html[data-theme="light"] .desktop-download-title{
  color:#0f172a;
}
html[data-theme="light"] .desktop-app-head p,
html[data-theme="light"] .desktop-app-copy p,
html[data-theme="light"] .desktop-app-downloads p,
html[data-theme="light"] .desktop-download-meta,
html[data-theme="light"] .desktop-download-size,
html[data-theme="light"] .desktop-security p,
html[data-theme="light"] .desktop-side-list li,
html[data-theme="light"] .desktop-release-list li,
html[data-theme="light"] .desktop-guide p{
  color:#64748b;
}
html[data-theme="light"] .desktop-app-copy h3 span,
html[data-theme="light"] .desktop-app-features svg,
html[data-theme="light"] .desktop-app-head-ico,
html[data-theme="light"] .desktop-side-title svg,
html[data-theme="light"] .desktop-side-os,
html[data-theme="light"] .desktop-security-icon,
html[data-theme="light"] .desktop-security-cloud,
html[data-theme="light"] .desktop-version-pill,
html[data-theme="light"] .desktop-app-badge{
  color:#0284c7;
}
html[data-theme="light"] .desktop-app-features li{
  color:#1e293b;
}
html[data-theme="light"] .desktop-app-badge,
html[data-theme="light"] .desktop-app-head-ico,
html[data-theme="light"] .desktop-os-icon,
html[data-theme="light"] .desktop-security-icon{
  background:rgba(14,165,233,.1);
  border-color:rgba(14,165,233,.24);
}
html[data-theme="light"] .desktop-download-card,
html[data-theme="light"] .desktop-side-card,
html[data-theme="light"] .desktop-security{
  background:linear-gradient(180deg,#fff,#f8fbff);
  border-color:rgba(15,23,42,.1);
  box-shadow:0 14px 34px rgba(15,23,42,.07);
}
html[data-theme="light"] .desktop-side-section{
  border-bottom-color:rgba(15,23,42,.1);
}
html[data-theme="light"] .desktop-version-pill{
  background:rgba(14,165,233,.1);
  border-color:rgba(14,165,233,.22);
}
html[data-theme="light"] .desktop-download-card.disabled{
  opacity:.72;
}
html[data-theme="light"] .desktop-os-icon.apple{
  color:#334155;
  background:rgba(15,23,42,.06);
}
html[data-theme="light"] .desktop-security-cloud{
  background:radial-gradient(circle at 65% 35%,rgba(14,165,233,.32),transparent 28%),linear-gradient(135deg,rgba(37,99,235,.12),rgba(14,165,233,.1));
  border-color:rgba(14,165,233,.2);
}
@media (max-width:1180px){
  .desktop-app-shell{grid-template-columns:1fr}
  .desktop-app-side{grid-template-columns:repeat(3,minmax(0,1fr));display:grid}
}
@media (max-width:920px){
  .desktop-app-visual{position:relative;right:auto;bottom:auto;width:100%;margin-top:24px}
  .desktop-app-copy{width:100%}
  .desktop-app-hero{min-height:auto}
  .desktop-download-grid,.desktop-app-side{grid-template-columns:1fr}
  .desktop-security{grid-template-columns:50px 1fr}
  .desktop-security-cloud{display:none}
}
@media (max-width:620px){
  .desktop-app-hero{padding:20px}
  .desktop-app-head{gap:11px}
  .desktop-app-copy h3{font-size:25px}
  .desktop-app-downloads{padding:0 20px 20px;margin-top:22px}
  .desktop-download-card{grid-template-columns:1fr}
  .desktop-os-icon{width:48px;height:48px}
  .desktop-laptop-ui{grid-template-columns:64px 1fr;padding:9px}
  .desktop-laptop-panel{display:none}
  .desktop-security{padding:16px;grid-template-columns:1fr}
}
</style>
@endpush

<div class="cfg-panel" data-panel="aplicacion-escritorio">
  <div class="desktop-app-shell">
    <div>
      <article class="card desktop-app-main rise d2">
        <section class="desktop-app-hero">
          <div class="desktop-app-head">
            <span class="desktop-app-head-ico" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="13" rx="2"/>
                <path d="M8 21h8"/>
                <path d="M12 17v4"/>
              </svg>
            </span>
            <div>
              <h2>Aplicación de escritorio</h2>
              <p>Descarga e instala ENCLAII Desktop para capturar, gestionar y sincronizar tus estudios de endoscopia.</p>
            </div>
          </div>

          <div class="desktop-app-copy">
            <span class="desktop-app-badge">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="13" rx="2"/>
                <path d="M8 21h8"/>
                <path d="M12 17v4"/>
              </svg>
              ENCLAII Desktop
            </span>
            <h3>Tu clínica,<span>siempre contigo.</span></h3>
            <p>Captura en vivo, graba procedimientos, toma imágenes y sincroniza todo con la nube de forma segura.</p>
            <ul class="desktop-app-features">
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                Captura en vivo de alta calidad
              </li>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 16.6A6 6 0 0 0 9.3 12H9a5 5 0 0 0 0 10h10a4 4 0 0 0 1-7.9"/></svg>
                Sincronización automática con la nube
              </li>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><path d="M21 12a9 9 0 1 1-6.2-8.56"/></svg>
                Gestión completa de estudios
              </li>
              <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                Seguridad y cifrado de extremo a extremo
              </li>
            </ul>
          </div>

          <div class="desktop-app-visual" aria-hidden="true">
            <div class="desktop-laptop">
              <div class="desktop-laptop-screen">
                <div class="desktop-laptop-ui">
                  <div class="desktop-laptop-sidebar">
                    <div class="desktop-laptop-logo"></div>
                    <div class="desktop-laptop-nav"></div>
                    <div class="desktop-laptop-nav"></div>
                    <div class="desktop-laptop-nav"></div>
                    <div class="desktop-laptop-nav"></div>
                    <div class="desktop-laptop-nav"></div>
                  </div>
                  <div class="desktop-laptop-capture"></div>
                  <div class="desktop-laptop-panel">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                  </div>
                </div>
              </div>
              <div class="desktop-laptop-base"></div>
            </div>
          </div>
        </section>

        <section class="desktop-app-downloads">
          <h3>Descargar ENCLAII Desktop</h3>
          <p>Selecciona tu sistema operativo para descargar la última versión disponible.</p>

          <div class="desktop-download-grid">
            <article class="desktop-download-card">
              <span class="desktop-os-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M3 5.2 10.8 4v7.6H3V5.2Zm9.2-1.4L21 2.5v9.1h-8.8V3.8ZM3 12.9h7.8v7.2L3 18.9v-6Zm9.2 0H21v8.6l-8.8-1.2v-7.4Z"/>
                </svg>
              </span>
              <div>
                <div class="desktop-download-title">Windows <span class="desktop-version-pill">v{{ $desktopAppVersion }}</span></div>
                <div class="desktop-download-meta">{{ $desktopAppArchitecture }} &bull; Instalador MSI recomendado</div>
                <a class="desktop-download-btn" href="{{ route('desktop-app.download') }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <path d="M7 10l5 5 5-5"/>
                    <path d="M12 15V3"/>
                  </svg>
                  Descargar para Windows
                </a>
                <div class="desktop-download-size">Tamaño: {{ $desktopAppSize }}</div>
              </div>
            </article>

            <article class="desktop-download-card disabled" aria-label="macOS próximamente">
              <span class="desktop-os-icon apple" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.7 12.8c0-2.2 1.8-3.2 1.9-3.3-1-1.5-2.6-1.7-3.2-1.7-1.4-.1-2.6.8-3.3.8-.7 0-1.8-.8-2.9-.8-1.5 0-2.9.9-3.7 2.2-1.6 2.8-.4 7 1.1 9.3.8 1.1 1.7 2.3 2.9 2.3 1.1 0 1.6-.7 3-.7s1.8.7 3 .7c1.2 0 2-.9 2.8-2 .9-1.3 1.2-2.5 1.2-2.6 0 0-2.8-1.1-2.8-4.2ZM15.5 6.3c.6-.8 1.1-1.9 1-3-.9 0-2 .6-2.7 1.4-.6.7-1.1 1.8-1 2.9 1 .1 2-.5 2.7-1.3Z"/>
                </svg>
              </span>
              <div>
                <div class="desktop-download-title">macOS <span class="desktop-version-pill">Próximamente</span></div>
                <div class="desktop-download-meta">Universal &bull; Para Intel y Apple Silicon</div>
                <span class="desktop-download-btn disabled">Descarga no disponible</span>
                <div class="desktop-download-size">En preparación</div>
              </div>
            </article>
          </div>
        </section>
      </article>

      <article class="card desktop-security rise d5">
        <span class="desktop-security-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <rect x="9" y="11" width="6" height="5" rx="1"/>
            <path d="M10 11V9a2 2 0 0 1 4 0v2"/>
          </svg>
        </span>
        <div>
          <b>Seguridad y privacidad garantizadas</b>
          <p>ENCLAII Desktop utiliza cifrado para proteger tus datos médicos. Toda la información se sincroniza de forma segura con la nube.</p>
        </div>
        <span class="desktop-security-cloud" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 16.6A6 6 0 0 0 9.3 12H9a5 5 0 0 0 0 10h10a4 4 0 0 0 1-7.9"/>
            <rect x="10" y="14" width="5" height="4" rx="1"/>
            <path d="M11 14v-1a1.5 1.5 0 0 1 3 0v1"/>
          </svg>
        </span>
      </article>
    </div>

    <aside class="desktop-app-side">
      <article class="card desktop-side-card rise d3">
        <div class="desktop-side-title">
          <span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="13" rx="2"/>
              <path d="M8 21h8"/>
              <path d="M12 17v4"/>
            </svg>
            Requisitos del sistema
          </span>
        </div>

        <section class="desktop-side-section">
          <span class="desktop-side-os" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M3 5.2 10.8 4v7.6H3V5.2Zm9.2-1.4L21 2.5v9.1h-8.8V3.8ZM3 12.9h7.8v7.2L3 18.9v-6Zm9.2 0H21v8.6l-8.8-1.2v-7.4Z"/>
            </svg>
          </span>
          <div>
            <h4>Windows</h4>
            <ul class="desktop-side-list">
              <li>Windows 10 u 11 de 64 bits</li>
              <li>8 GB de RAM recomendado</li>
              <li>20 MB libres para descargar el instalador</li>
              <li>Conexión a internet para sincronización</li>
            </ul>
          </div>
        </section>

        <section class="desktop-side-section">
          <span class="desktop-side-os" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.7 12.8c0-2.2 1.8-3.2 1.9-3.3-1-1.5-2.6-1.7-3.2-1.7-1.4-.1-2.6.8-3.3.8-.7 0-1.8-.8-2.9-.8-1.5 0-2.9.9-3.7 2.2-1.6 2.8-.4 7 1.1 9.3.8 1.1 1.7 2.3 2.9 2.3 1.1 0 1.6-.7 3-.7s1.8.7 3 .7c1.2 0 2-.9 2.8-2 .9-1.3 1.2-2.5 1.2-2.6 0 0-2.8-1.1-2.8-4.2ZM15.5 6.3c.6-.8 1.1-1.9 1-3-.9 0-2 .6-2.7 1.4-.6.7-1.1 1.8-1 2.9 1 .1 2-.5 2.7-1.3Z"/>
            </svg>
          </span>
          <div>
            <h4>macOS</h4>
            <ul class="desktop-side-list">
              <li>Soporte en preparación</li>
              <li>Compatible con Intel y Apple Silicon</li>
              <li>8 GB de RAM recomendado</li>
              <li>Conexión a internet para sincronización</li>
            </ul>
          </div>
        </section>
      </article>

      <article class="card desktop-side-card rise d4">
        <div class="desktop-side-title">
          <span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2l2.4 6.8H22l-6.1 4.4 2.4 6.8L12 15.8 5.7 20l2.4-6.8L2 8.8h7.6L12 2z"/>
            </svg>
            Novedades de esta versión
          </span>
          <span class="desktop-version-pill">v{{ $desktopAppVersion }}</span>
        </div>
        <ul class="desktop-release-list">
          <li>Mejoras en captura y estabilidad del instalador.</li>
          <li>Sincronización más rápida con la nube.</li>
          <li>Optimización del flujo de descarga para Windows.</li>
          <li>Corrección de errores y mejoras generales.</li>
        </ul>
      </article>

      <a class="card desktop-side-card desktop-guide rise d5" href="{{ $desktopAppManualUrl }}" target="_blank" rel="noopener" aria-label="Abrir manual de instalación de ENCLAII Endoscopy">
        <div>
          <div class="desktop-side-title" style="margin-bottom:8px">
            <span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/>
              </svg>
              Guía de instalación
            </span>
          </div>
          <p>Descarga el instalador MSI, ejecútalo en Windows y sigue los pasos para conectar tu equipo.</p>
        </div>
        <span class="desktop-guide-art" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <path d="M14 2v6h6"/>
            <path d="M9 13h6"/>
            <path d="M9 17h4"/>
          </svg>
        </span>
      </a>
    </aside>
  </div>
</div>
