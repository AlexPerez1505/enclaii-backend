{{-- ============ PANEL: INTEGRACIONES ============ --}}
@push('styles')
<style>
.int-head h2{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.int-head p{font-size:13px;color:var(--txt-soft);margin:3px 0 18px}

/* Acciones rápidas superiores */
.int-actions{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:18px}
@media (max-width:900px){.int-actions{grid-template-columns:1fr}}
.int-act{display:flex;flex-direction:column}
.int-act .ia-top{display:flex;gap:13px;margin-bottom:14px}
.int-act .ia-ico{width:42px;height:42px;flex:none;border-radius:11px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1);border:1px solid rgba(56,199,244,.22)}
.int-act .ia-ico svg{width:21px;height:21px}
.int-act .ia-t{font-size:14px;font-weight:700}
.int-act .ia-d{font-size:11.5px;color:var(--txt-soft);margin-top:3px;line-height:1.45}
.int-act .ia-btn{margin-top:auto;display:block;text-align:center;padding:9px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:12.5px;font-weight:700;color:var(--cyan);transition:background-color .15s}
@media (hover:hover){.int-act .ia-btn:hover{background:rgba(56,199,244,.1)}}

/* Bloque catálogos + info */
.int-main{display:grid;grid-template-columns:1fr .42fr;gap:18px;align-items:stretch;margin-bottom:18px}
@media (max-width:980px){.int-main{grid-template-columns:1fr}}
.int-info-card{display:flex;flex-direction:column}
.int-info-card .pl-wide-btn{margin-top:auto}

.int-cat-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}
.int-cat-head h2{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.int-cat-head p{font-size:12px;color:var(--txt-soft);margin-top:3px}
.int-add{display:inline-flex;align-items:center;gap:7px;padding:9px 15px;border-radius:var(--r-md);font-size:12.5px;font-weight:700;color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan));white-space:nowrap}
.int-add svg{width:15px;height:15px}

.int-subtabs{display:flex;gap:22px;border-bottom:1px solid var(--stroke);margin:16px 0 4px;flex-wrap:wrap}
.int-subtab{position:relative;display:flex;align-items:center;gap:7px;padding:0 2px 11px;font-size:12.5px;font-weight:600;color:var(--txt-soft);background:none;border:0;cursor:pointer;transition:color .15s}
.int-subtab svg{width:15px;height:15px}
.int-subtab.active{color:var(--cyan)}
.int-subtab.active::after{content:"";position:absolute;left:0;right:0;bottom:-1px;height:2px;border-radius:2px;background:var(--cyan)}
@media (hover:hover){.int-subtab:hover{color:var(--txt)}}

.int-table{width:100%;border-collapse:collapse;margin-top:6px}
.int-table th{text-align:left;font-size:11.5px;font-weight:600;color:var(--txt-soft);padding:12px 10px;border-bottom:1px solid var(--stroke)}
.int-table td{font-size:12.5px;padding:14px 10px;border-bottom:1px solid rgba(110,160,255,.08)}
.int-table tr:last-child td{border-bottom:0}
.int-name{display:inline-flex;align-items:center;gap:8px;font-weight:600}
.int-you{font-size:9.5px;font-weight:700;color:var(--cyan);background:rgba(56,199,244,.14);padding:1px 7px;border-radius:5px}
.int-badge-on{font-size:10.5px;font-weight:700;color:var(--green);background:rgba(61,220,151,.12);border:1px solid rgba(61,220,151,.3);padding:3px 11px;border-radius:7px}
.int-del{width:30px;height:30px;border-radius:8px;display:grid;place-items:center;color:var(--red);background:rgba(255,90,110,.1);border:1px solid rgba(255,90,110,.25);transition:background-color .15s}
.int-del svg{width:15px;height:15px}
@media (hover:hover){.int-del:hover{background:rgba(255,90,110,.2)}}
.int-table-wrap{overflow-x:auto}

/* Info del sistema */
.int-info-row{padding:15px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.int-info-row:last-of-type{border-bottom:0}
.int-info-row .k{font-size:12.5px;color:var(--txt-soft)}
.int-info-row .v{font-size:13px;font-weight:600;float:right}
.int-info-row .on{color:var(--green);display:inline-flex;align-items:center;gap:6px}
.int-info-row .on::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--green)}

/* Tarjetas inferiores */
.int-bottom{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
@media (max-width:1100px){.int-bottom{grid-template-columns:repeat(2,1fr)}}
@media (max-width:560px){.int-bottom{grid-template-columns:1fr}}
.int-dev-head{display:flex;align-items:flex-start;gap:11px;margin-bottom:12px}
.int-dev-ico{width:40px;height:40px;flex:none;border-radius:10px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1);border:1px solid rgba(56,199,244,.2)}
.int-dev-ico svg{width:20px;height:20px}
.int-dev-t{font-size:13.5px;font-weight:700}
.int-chip-on{display:inline-block;margin-top:5px;font-size:9.5px;font-weight:700;color:var(--green);background:rgba(61,220,151,.14);padding:2px 8px;border-radius:6px}
.int-dev-meta{font-size:11.5px;color:var(--txt-soft);margin-top:4px}
.int-dev-meta b{color:var(--txt);font-weight:600}

.int-checks{display:flex;flex-direction:column;gap:10px;margin-top:4px}
.int-check{display:flex;align-items:center;gap:9px;font-size:11.5px;color:var(--txt-soft);cursor:pointer}
.int-check input{appearance:none;-webkit-appearance:none;width:16px;height:16px;flex:none;border-radius:4px;border:1.5px solid var(--stroke-strong);background:var(--panel-2);position:relative;cursor:pointer;transition:background .15s,border-color .15s}
.int-check input:checked{background:linear-gradient(135deg,var(--blue),var(--cyan));border-color:transparent}
.int-check input:checked::after{content:"";position:absolute;left:5px;top:1.5px;width:4px;height:8px;border:solid #fff;border-width:0 2px 2px 0;transform:rotate(45deg)}

.int-dev-btns{display:flex;gap:8px;margin-top:13px}
.int-dev-btn{flex:1;text-align:center;padding:8px;border-radius:9px;border:1px solid var(--stroke-strong);font-size:11.5px;font-weight:700;color:var(--cyan);transition:background-color .15s}
@media (hover:hover){.int-dev-btn:hover{background:rgba(56,199,244,.1)}}
</style>
@endpush

<div class="cfg-panel" data-panel="integraciones">

  <div class="int-head">
    <h2>Integraciones</h2>
    <p>Administra catálogos, personal y configuración del sistema.</p>
  </div>

  {{-- Acciones superiores --}}
  <div class="int-actions">
    <article class="card int-act rise d2">
      <div class="ia-top">
        <span class="ia-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></span>
        <div><div class="ia-t">Crear una copia de configuración</div><div class="ia-d">Genera una copia de seguridad de la configuración actual.</div></div>
      </div>
      <a href="#" class="ia-btn">Crear copia</a>
    </article>

    <article class="card int-act rise d3">
      <div class="ia-top">
        <span class="ia-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20a15 15 0 0 1 0-20z"/></svg></span>
        <div><div class="ia-t">Restaurar web</div><div class="ia-d">Restaura la configuración previamente guardada en la nube.</div></div>
      </div>
      <a href="#" class="ia-btn">Restaurar</a>
    </article>

    <article class="card int-act rise d4">
      <div class="ia-top">
        <span class="ia-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg></span>
        <div><div class="ia-t">Prueba de impresión</div><div class="ia-d">Imprime una página de prueba para verificar la configuración.</div></div>
      </div>
      <a href="#" class="ia-btn">Imprimir prueba</a>
    </article>
  </div>

  {{-- Catálogos + Info --}}
  <div class="int-main">
    <article class="card rise d3">
      <div class="int-cat-head">
        <div>
          <h2>Catálogos del sistema</h2>
          <p>Administra los catálogos que utiliza el sistema</p>
        </div>
        <a href="#" class="int-add"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Agregar personal</a>
      </div>

      <div class="int-subtabs">
        <button class="int-subtab active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg> Personal</button>
        <button class="int-subtab"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Diagnósticos</button>
        <button class="int-subtab"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> Pre. Diagnósticos</button>
        <button class="int-subtab"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 3 3-9 9-3-1 1-3 8-8z"/><path d="m18 8 4 4-8 8H10v-4z"/></svg> Sedantes</button>
        <button class="int-subtab"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg> Estudios</button>
      </div>

      <div class="int-table-wrap">
        <table class="int-table">
          <thead>
            <tr><th>Nombre</th><th>Especialidad</th><th>Rol</th><th>Correo electrónico</th><th>Estado</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            <tr>
              <td><span class="int-name">Dr. Victor <span class="int-you">Tú</span></span></td>
              <td>Endoscopista</td><td>Administrador</td><td>victor@endoclinic.com</td>
              <td><span class="int-badge-on">Activo</span></td>
              <td><button class="int-del" aria-label="Eliminar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></td>
            </tr>
            <tr>
              <td><span class="int-name">Dr. Victor</span></td>
              <td>Endoscopista</td><td>Administrador</td><td>victor@endoclinic.com</td>
              <td><span class="int-badge-on">Activo</span></td>
              <td><button class="int-del" aria-label="Eliminar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></td>
            </tr>
            <tr>
              <td><span class="int-name">Dr. Victor</span></td>
              <td>Endoscopista</td><td>Administrador</td><td>victor@endoclinic.com</td>
              <td><span class="int-badge-on">Activo</span></td>
              <td><button class="int-del" aria-label="Eliminar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </article>

    <article class="card int-info-card rise d4">
      <div class="cfg-card-head"><h2>Información del sistema</h2></div>
      <div class="int-info-row"><span class="k">Versión actual</span><span class="v">2.41</span></div>
      <div class="int-info-row"><span class="k">Última actualización</span><span class="v">15/05/2025 10:30AM</span></div>
      <div class="int-info-row"><span class="k">Estado de la conexión</span><span class="v on">En línea</span></div>
      <a href="#" class="pl-wide-btn">Ver historial de cambios</a>
    </article>
  </div>

  {{-- Tarjetas de dispositivos / servicios --}}
  <div class="int-bottom">
    <article class="card rise d3">
      <div class="int-dev-head">
        <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/></svg></span>
        <div><div class="int-dev-t">Procesador de endoscopia</div><span class="int-chip-on">Conectado</span></div>
      </div>
      <div class="int-dev-meta"><b>Olympus EVIS X1</b></div>
      <div class="int-dev-meta">Número de serie: X1-24567</div>
    </article>

    <article class="card rise d3">
      <div class="int-dev-head">
        <span class="int-dev-ico" style="color:#a47bff;background:rgba(124,92,255,.12);border-color:rgba(124,92,255,.25)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></span>
        <div class="int-dev-t">Captura Multimedia</div>
      </div>
      <div class="int-checks">
        <label class="int-check"><input type="checkbox" checked> Autocapturas de imágenes</label>
        <label class="int-check"><input type="checkbox" checked> Guardar imágenes automáticamente</label>
        <label class="int-check"><input type="checkbox" checked> Capturar imagen con pedal</label>
      </div>
    </article>

    <article class="card rise d4">
      <div class="int-dev-head">
        <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></span>
        <div><div class="int-dev-t">Correo electrónico <span class="int-chip-on">Conectado</span></div></div>
      </div>
      <div class="int-dev-meta">Usuario: David1212@gmail.com</div>
      <div class="int-dev-meta">Contacto: ENCLAII@gmail.com</div>
      <div class="int-dev-btns"><a href="#" class="int-dev-btn">Configurar correo</a></div>
    </article>

    <article class="card rise d4">
      <div class="int-dev-head">
        <span class="int-dev-ico" style="color:#25d366;background:rgba(37,211,102,.12);border-color:rgba(37,211,102,.25)"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.5A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.9.9-2.7-.2-.3A8 8 0 1 1 12 20zm4.4-5.9c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2a.5.5 0 0 0 0-.5c0-.1-.5-1.3-.7-1.8s-.4-.4-.5-.4h-.5a.9.9 0 0 0-.7.3 2.8 2.8 0 0 0-.9 2.1 5 5 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c1.6.7 1.9.6 2.3.5a2.3 2.3 0 0 0 1.5-1.1 1.9 1.9 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3z"/></svg></span>
        <div><div class="int-dev-t">WhatsApp Business <span class="int-chip-on">Conectado</span></div></div>
      </div>
      <div class="int-checks">
        <label class="int-check"><input type="checkbox" checked> Enviar informe PDF</label>
        <label class="int-check"><input type="checkbox" checked> Responder con AI</label>
        <label class="int-check"><input type="checkbox" checked> Enviar recordatorio de cita</label>
      </div>
      <div class="int-dev-btns"><a href="#" class="int-dev-btn">Configurar</a></div>
    </article>

    <article class="card rise d5">
      <div class="int-dev-head">
        <span class="int-dev-ico" style="color:var(--orange);background:rgba(245,158,45,.12);border-color:rgba(245,158,45,.25)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17c3-1 5-5 5-9 0 4 2 8 5 9-3 0-7 0-10 0z"/><path d="M14 11l6-6a1.5 1.5 0 0 0-2-2l-6 6"/></svg></span>
        <div><div class="int-dev-t">Firma digital</div><div class="int-dev-meta">Firma: Dr. Victor</div></div>
      </div>
      <div class="int-dev-meta">Actualizada: 12/05/2026</div>
      <div class="int-dev-btns"><a href="#" class="int-dev-btn">Ver firma</a><a href="#" class="int-dev-btn">Actualizar firma</a></div>
    </article>

    <article class="card rise d5">
      <div class="int-dev-head">
        <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a4 4 0 0 0-4 4 4 4 0 0 0 0 8 4 4 0 0 0 8 0 4 4 0 0 0 0-8 4 4 0 0 0-4-4z"/><path d="M12 2v20"/></svg></span>
        <div class="int-dev-t">Integración de AI</div>
      </div>
      <div class="int-checks">
        <label class="int-check"><input type="checkbox" checked> Generar borradores automáticos</label>
        <label class="int-check"><input type="checkbox" checked> Analizar fotos</label>
        <label class="int-check"><input type="checkbox" checked> Sugerir diagnósticos</label>
        <label class="int-check"><input type="checkbox" checked> Recomendar procedimientos</label>
      </div>
    </article>
  </div>

</div>

@push('scripts')
<script>
(function(){
  const subtabs = document.querySelectorAll('.int-subtab');
  subtabs.forEach(s => s.addEventListener('click', () => {
    subtabs.forEach(x => x.classList.remove('active'));
    s.classList.add('active');
  }));
})();
</script>
@endpush
