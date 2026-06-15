{{-- ============ PANEL: SEGURIDAD ============ --}}
@push('styles')
<style>
.sec-head h2{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.sec-head p{font-size:13px;color:var(--txt-soft);margin:3px 0 18px}

.sec-top{display:grid;grid-template-columns:1fr 1.35fr;gap:18px;align-items:stretch;margin-bottom:18px}
@media (max-width:1000px){.sec-top{grid-template-columns:1fr}}
.sec-top > .card{display:flex;flex-direction:column}
.sec-bottom{display:grid;grid-template-columns:1.5fr 1fr 1fr;gap:18px;align-items:stretch}
@media (max-width:1100px){.sec-bottom{grid-template-columns:1fr 1fr}}
@media (max-width:760px){.sec-bottom{grid-template-columns:1fr}}
.sec-bottom > .card{display:flex;flex-direction:column}

/* filas de acceso */
.sec-row{display:flex;align-items:center;gap:13px;padding:15px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.sec-row:last-child{border-bottom:0}
.sec-ico{width:40px;height:40px;flex:none;border-radius:11px;display:grid;place-items:center}
.sec-ico svg{width:20px;height:20px}
.sec-ico.b{color:var(--cyan);background:rgba(56,199,244,.12)}
.sec-ico.g{color:var(--green);background:rgba(61,220,151,.14)}
.sec-ico.p{color:#a47bff;background:rgba(124,92,255,.14)}
.sec-info{flex:1;min-width:0}
.sec-info .t{font-size:13.5px;font-weight:600}
.sec-info .d{font-size:11.5px;color:var(--txt-soft);margin-top:2px}
.sec-btn{padding:9px 15px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:12px;font-weight:700;color:var(--txt);white-space:nowrap;transition:background-color .15s}
@media (hover:hover){.sec-btn:hover{background:rgba(110,160,255,.1)}}

/* tablas */
.sec-table-wrap{overflow-x:auto}
.sec-table{width:100%;border-collapse:collapse}
.sec-table th{text-align:left;font-size:11.5px;font-weight:600;color:var(--txt-soft);padding:11px 10px;border-bottom:1px solid var(--stroke)}
.sec-table td{font-size:12.5px;padding:13px 10px;border-bottom:1px solid rgba(110,160,255,.08)}
.sec-table tr:last-child td{border-bottom:0}
.sec-dev{display:flex;align-items:center;gap:10px}
.sec-dev svg{width:22px;height:22px;flex:none;color:#0078d4}
.sec-dev b{font-size:12.5px;font-weight:600;display:block}
.sec-dev span{font-size:10.5px;color:var(--txt-soft)}
.sec-on{font-size:10.5px;font-weight:700;color:var(--green);background:rgba(61,220,151,.12);border:1px solid rgba(61,220,151,.3);padding:3px 11px;border-radius:7px}
.sec-link{font-size:12px;font-weight:700;color:var(--cyan)}
.sec-foot{text-align:center;padding-top:14px}
.sec-foot a{font-size:12.5px;font-weight:700;color:var(--cyan)}

/* registro de actividad */
.sec-search{position:relative;margin:4px 0 8px}
.sec-search input{width:100%;font:inherit;font-size:12.5px;color:var(--txt);background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:10px;padding:10px 14px 10px 38px}
.sec-search input::placeholder{color:var(--txt-soft)}
.sec-search svg{position:absolute;left:13px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--txt-soft)}
.sec-act{display:inline-flex;align-items:center;gap:8px}
.sec-act i{width:8px;height:8px;border-radius:50%;flex:none;display:inline-block}
.dot-blue{background:var(--cyan)}.dot-red{background:var(--red)}.dot-green{background:var(--green)}.dot-amber{background:var(--orange)}

/* permisos críticos */
.sec-checks{display:flex;flex-direction:column;gap:16px;margin-top:6px}
.sec-check{display:flex;align-items:flex-start;gap:11px;font-size:12.5px;color:var(--txt);cursor:pointer;line-height:1.4}
.sec-check input{appearance:none;-webkit-appearance:none;width:18px;height:18px;flex:none;margin-top:1px;border-radius:5px;border:1.5px solid var(--stroke-strong);background:var(--panel-2);position:relative;cursor:pointer;transition:background .15s,border-color .15s}
.sec-check input:checked{background:linear-gradient(135deg,var(--blue),var(--cyan));border-color:transparent}
.sec-check input:checked::after{content:"";position:absolute;left:6px;top:2px;width:4px;height:9px;border:solid #fff;border-width:0 2px 2px 0;transform:rotate(45deg)}

/* respaldo */
.sec-backup{display:flex;align-items:center;gap:12px;padding:14px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);margin-bottom:12px}
.sec-backup .bk-ico{width:40px;height:40px;flex:none;border-radius:11px;display:grid;place-items:center}
.sec-backup .bk-ico svg{width:20px;height:20px}
.sec-backup .bk-t{font-size:11.5px;color:var(--txt-soft)}
.sec-backup .bk-v{font-size:13.5px;font-weight:700;margin-top:2px}
.sec-backup-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:11px;border-radius:var(--r-md);font-size:12.5px;font-weight:700;margin-top:4px;transition:background-color .15s,opacity .15s}
.sec-backup-btn svg{width:15px;height:15px}
.sec-backup-btn.primary{color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan))}
.sec-backup-btn.primary:hover{opacity:.92}
.sec-backup-btn.ghost{color:var(--txt);border:1px solid var(--stroke-strong);margin-top:12px}
.sec-backup-btn.ghost:hover{background:rgba(110,160,255,.1)}
</style>
@endpush

<div class="cfg-panel" data-panel="seguridad">

  <div class="sec-head">
    <h2>Seguridad</h2>
    <p>Protege el acceso a tu cuenta, pacientes e información médica</p>
  </div>

  {{-- Fila superior --}}
  <div class="sec-top">
    <article class="card rise d2">
      <div class="cfg-card-head"><h2>Acceso y autenticación</h2></div>

      <div class="sec-row">
        <span class="sec-ico b"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
        <div class="sec-info"><div class="t">Contraseña</div><div class="d">Última actualización: hace 15 días</div></div>
        <a href="#" class="sec-btn">Cambiar contraseña</a>
      </div>

      <div class="sec-row">
        <span class="sec-ico g"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></span>
        <div class="sec-info"><div class="t">Autenticación de dos factores (2FA)</div><div class="d">Recibe un código por correo al iniciar sesión</div></div>
        <label class="sw"><input type="checkbox" checked><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="sec-row">
        <span class="sec-ico p"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
        <div class="sec-info"><div class="t">Tiempo de sesión</div><div class="d">Tiempo de inactividad antes de cerrar sesión</div></div>
        <div class="cfg-select">
          <select><option>30 minutos</option><option>15 minutos</option><option>1 hora</option><option>Nunca</option></select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>
    </article>

    <article class="card rise d3">
      <div class="cfg-card-head">
        <h2>Dispositivos conectados</h2>
        <p>Consulta las acciones más recientes realizadas en tu cuenta</p>
      </div>

      <div class="sec-table-wrap">
        <table class="sec-table">
          <thead>
            <tr><th>Dispositivo</th><th>Ubicación</th><th>Último acceso</th><th>Estado</th><th>Acciones</th></tr>
          </thead>
          <tbody>
            @for ($i = 0; $i < 4; $i++)
            <tr>
              <td><span class="sec-dev"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 5.5 10 4.5v7H3zM11 4.3 21 3v8.5H11zM3 12.5h7v7L3 18.5zM11 12.5h10V21l-10-1.3z"/></svg><span><b>Windows 11</b><span>Chrome 124</span></span></span></td>
              <td>Toluca, México</td>
              <td>Hoy, 10:25 AM</td>
              <td><span class="sec-on">Activo</span></td>
              <td><a href="#" class="sec-link">Cerrar sesión</a></td>
            </tr>
            @endfor
          </tbody>
        </table>
      </div>
      <div class="sec-foot"><a href="#">Cerrar todas las sesiones excepto la actual</a></div>
    </article>
  </div>

  {{-- Fila inferior --}}
  <div class="sec-bottom">
    <article class="card rise d3">
      <div class="cfg-card-head">
        <h2>Registro de actividad</h2>
        <p>Consulta las acciones más recientes realizadas en tu cuenta</p>
      </div>
      <div class="sec-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Buscar actividad...">
      </div>
      <div class="sec-table-wrap">
        <table class="sec-table">
          <thead>
            <tr><th>Fecha y hora</th><th>Usuario</th><th>Acción realizada</th><th>Dirección IP</th></tr>
          </thead>
          <tbody>
            <tr><td>Hoy, 10:25 AM</td><td>Dr.victor</td><td><span class="sec-act"><i class="dot-blue"></i>Generó informe</span></td><td>189.123.45.67</td></tr>
            <tr><td>Hoy, 10:25 AM</td><td>Dr.victor</td><td><span class="sec-act"><i class="dot-red"></i>Borró paciente</span></td><td>189.123.45.67</td></tr>
            <tr><td>Hoy, 10:25 AM</td><td>Dr.victor</td><td><span class="sec-act"><i class="dot-green"></i>Inicio de sesión</span></td><td>189.123.45.67</td></tr>
            <tr><td>Hoy, 10:25 AM</td><td>Dr.victor</td><td><span class="sec-act"><i class="dot-amber"></i>Editó paciente</span></td><td>189.123.45.67</td></tr>
            <tr><td>Hoy, 10:25 AM</td><td>Dr.victor</td><td><span class="sec-act"><i class="dot-blue"></i>Generó informe</span></td><td>189.123.45.67</td></tr>
          </tbody>
        </table>
      </div>
    </article>

    <article class="card rise d4">
      <div class="cfg-card-head">
        <h2>Permisos críticos</h2>
        <p>Confirma solicitudes para acciones sensibles</p>
      </div>
      <div class="sec-checks">
        <label class="sec-check"><input type="checkbox" checked> Solicitar contraseña para eliminar o editar estudios</label>
        <label class="sec-check"><input type="checkbox" checked> Solicitar contraseña para eliminar o editar paciente</label>
        <label class="sec-check"><input type="checkbox" checked> Solicitar contraseña para eliminar o editar informes</label>
        <label class="sec-check"><input type="checkbox" checked> Registrar todas las acciones en auditoría</label>
      </div>
    </article>

    <article class="card rise d5">
      <div class="cfg-card-head">
        <h2>Respaldo y recuperación</h2>
        <p>Administra los respaldos de tu información</p>
      </div>

      <div class="sec-backup">
        <span class="bk-ico" style="color:var(--cyan);background:rgba(56,199,244,.12)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/><polyline points="9 15 12 12 15 15"/><line x1="12" y1="12" x2="12" y2="20"/></svg></span>
        <div><div class="bk-t">Último respaldo</div><div class="bk-v">Hoy, 3:00</div></div>
      </div>

      <div class="sec-backup">
        <span class="bk-ico" style="color:#a47bff;background:rgba(124,92,255,.14)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
        <div><div class="bk-t">Frecuencia automática</div><div class="bk-v">Diaria a las 3:00am</div></div>
      </div>

      <a href="#" class="sec-backup-btn primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/><polyline points="9 15 12 12 15 15"/><line x1="12" y1="12" x2="12" y2="20"/></svg> Crear respaldo manual</a>
      <a href="#" class="sec-backup-btn ghost"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.5 15a9 9 0 1 0 .5-8L1 10"/></svg> Cambiar tiempo del respaldo</a>
    </article>
  </div>

</div>
