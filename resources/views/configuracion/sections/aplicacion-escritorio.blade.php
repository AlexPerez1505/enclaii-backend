@php
  $desktopAppVersion = '0.1.4';
  $desktopAppArchitecture = '64 bits';
@endphp

@push('styles')
<style>
.desktop-app-head h2{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.desktop-app-head p{font-size:13px;color:var(--txt-soft);margin:3px 0 14px}
.desktop-app-stack{display:flex;flex-direction:column;gap:14px;width:100%}
.desktop-app-card{padding:18px}
.desktop-app-card.compact{padding:0;overflow:hidden}
.desktop-app-title{font-family:'Sora',sans-serif;font-size:17px;font-weight:800}
.desktop-app-copy{font-size:13px;color:var(--txt);line-height:1.55;margin-top:18px;max-width:100%}
.desktop-app-download{display:flex;align-items:center;justify-content:space-between;gap:14px;width:100%;padding:16px 20px;color:var(--txt);transition:background-color .15s,transform .15s}
.desktop-app-download:active{transform:scale(.99)}
@media (hover:hover){.desktop-app-download:hover{background:rgba(56,199,244,.07)}}
.desktop-app-download-main{display:flex;flex-direction:column;gap:6px;min-width:0}
.desktop-app-download-title{font-size:14px;font-weight:800}
.desktop-app-download-meta{font-size:12.5px;color:var(--txt);font-weight:700}
.desktop-app-download-icon{width:38px;height:38px;flex:none;border-radius:10px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan))}
.desktop-app-download-icon svg{width:18px;height:18px}
.desktop-app-requirements{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:8px}
.desktop-app-requirements li{font-size:13px;color:var(--txt);line-height:1.35}
@media (max-width:620px){
  .desktop-app-card{padding:14px}
  .desktop-app-copy{margin-top:14px}
  .desktop-app-download{padding:14px;align-items:flex-start}
  .desktop-app-download-title{font-size:13px}
  .desktop-app-download-meta{font-size:11.5px}
}
</style>
@endpush

<div class="cfg-panel" data-panel="aplicacion-escritorio">
  <div class="desktop-app-head">
    <h2>Aplicación de escritorio</h2>
    <p>Descarga ENCLAII Desktop para instalarlo en una computadora de escritorio.</p>
  </div>

  <div class="desktop-app-stack">
    <article class="card desktop-app-card rise d2">
      <div class="desktop-app-title">ENCLAII Desktop</div>
      <p class="desktop-app-copy">
        Instala la aplicación de escritorio para conectar la cámara del endoscopio,
        capturar imágenes, grabar videos y sincronizar los estudios con la nube.
      </p>
    </article>

    <article class="card desktop-app-card compact rise d3">
      <a class="desktop-app-download" href="{{ route('desktop-app.download') }}">
        <span class="desktop-app-download-main">
          <span class="desktop-app-download-title">Descargar para Windows</span>
          <span class="desktop-app-download-meta">Versión {{ $desktopAppVersion }} &bull; {{ $desktopAppArchitecture }}</span>
        </span>
        <span class="desktop-app-download-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <path d="M7 10l5 5 5-5"/>
            <path d="M12 15V3"/>
          </svg>
        </span>
      </a>
    </article>

    <article class="card desktop-app-card rise d4">
      <div class="desktop-app-title">Requisitos del sistema</div>
      <ul class="desktop-app-requirements">
        <li>Windows 10 u 11 de 64 bits</li>
        <li>8 GB de RAM</li>
        <li>20 MB libres para descargar el instalador (19,357,696 bytes)</li>
        <li>Puerto USB disponible</li>
        <li>Conexión a internet para sincronización</li>
      </ul>
    </article>
  </div>
</div>
