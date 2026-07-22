
<?php $__env->startPush('styles'); ?>
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
.sec-on.current{color:var(--cyan);background:rgba(56,199,244,.1);border-color:rgba(56,199,244,.32)}
.sec-link{font-size:12px;font-weight:700;color:var(--cyan)}
.sec-link:disabled{color:var(--txt-soft);opacity:.6;cursor:not-allowed}
.sec-foot{text-align:center;padding-top:14px}
.sec-foot button{font-size:12.5px;font-weight:700;color:var(--cyan)}
.sec-foot button:disabled{color:var(--txt-soft);opacity:.55;cursor:not-allowed}

/* registro de actividad */
.sec-search{position:relative;margin:4px 0 8px}
.sec-search input{width:100%;font:inherit;font-size:12.5px;color:var(--txt);background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:10px;padding:10px 90px 10px 38px}
.sec-search input::placeholder{color:var(--txt-soft)}
.sec-search svg{position:absolute;left:13px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--txt-soft)}
.sec-search button{position:absolute;right:5px;top:5px;bottom:5px;padding:0 12px;border-radius:7px;color:var(--cyan);background:rgba(56,199,244,.08);font-size:10.5px;font-weight:700}
.sec-act{display:inline-flex;align-items:center;gap:8px}
.sec-act i{width:8px;height:8px;border-radius:50%;flex:none;display:inline-block}
.dot-blue{background:var(--cyan)}.dot-red{background:var(--red)}.dot-green{background:var(--green)}.dot-amber{background:var(--orange)}
.sec-empty{text-align:center!important;padding:24px 10px!important;color:var(--txt-soft);font-size:12px!important}
.sec-pagination{display:flex;align-items:center;justify-content:center;gap:10px;padding-top:13px}
.sec-pagination a,.sec-pagination span{padding:6px 10px;border:1px solid var(--stroke);border-radius:8px;color:var(--txt-soft);font-size:10.5px}
.sec-pagination a{color:var(--cyan)}
.sec-pagination .disabled{opacity:.45}

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

/* Cambiar contraseña */
.sec-pw-overlay{
  position:fixed;inset:0;z-index:970;display:flex;align-items:center;justify-content:center;
  padding:20px;background:rgba(0,0,0,.68);backdrop-filter:blur(5px);
  opacity:0;visibility:hidden;transition:opacity .18s,visibility .18s
}
.sec-pw-overlay.open{opacity:1;visibility:visible}
.sec-pw-modal{
  width:min(500px,100%);max-height:calc(100vh - 40px);overflow:auto;
  background:var(--card);border:1px solid var(--stroke-strong);border-radius:18px;
  box-shadow:0 28px 75px rgba(0,0,0,.55);transform:translateY(10px) scale(.98);
  transition:transform .18s var(--ease-out)
}
.sec-pw-overlay.open .sec-pw-modal{transform:none}
.sec-pw-head{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding:20px 20px 0}
.sec-pw-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.sec-pw-sub{font-size:11.5px;color:var(--txt-soft);margin-top:4px}
.sec-pw-close{width:31px;height:31px;display:grid;place-items:center;flex:none;border:1px solid var(--stroke);border-radius:8px;color:var(--txt-soft)}
.sec-pw-close svg{width:14px;height:14px}
.sec-pw-body{padding:18px 20px;display:flex;flex-direction:column;gap:13px}
.sec-pw-field label{display:block;margin-bottom:7px;font-size:11.5px;font-weight:700}
.sec-pw-input-wrap{position:relative}
.sec-pw-input{
  width:100%;height:41px;padding:0 42px 0 12px;border:1px solid var(--stroke-strong);
  border-radius:10px;background:var(--panel-2);color:var(--txt);font:inherit;font-size:12.5px;outline:none
}
.sec-pw-input:focus{border-color:var(--cyan)}
.sec-pw-input.invalid{border-color:rgba(255,90,110,.7)}
.sec-pw-eye{position:absolute;right:6px;top:50%;transform:translateY(-50%);width:30px;height:30px;display:grid;place-items:center;border-radius:7px;color:var(--txt-soft)}
.sec-pw-eye svg{width:15px;height:15px}
.sec-pw-error{display:none;margin-top:5px;color:var(--red);font-size:10.5px}
.sec-pw-error.show{display:block}
.sec-pw-strength{display:grid;grid-template-columns:repeat(4,1fr);gap:5px;margin-top:7px}
.sec-pw-strength i{height:3px;border-radius:3px;background:var(--stroke)}
.sec-pw-strength[data-level="1"] i:nth-child(-n+1){background:var(--red)}
.sec-pw-strength[data-level="2"] i:nth-child(-n+2){background:var(--orange)}
.sec-pw-strength[data-level="3"] i:nth-child(-n+3){background:var(--cyan)}
.sec-pw-strength[data-level="4"] i:nth-child(-n+4){background:var(--green)}
.sec-pw-rules{display:grid;grid-template-columns:1fr 1fr;gap:6px;padding:11px;border:1px solid var(--stroke);border-radius:10px;background:var(--panel-2)}
.sec-pw-rule{display:flex;align-items:center;gap:6px;color:var(--txt-soft);font-size:10px}
.sec-pw-rule::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--stroke-strong)}
.sec-pw-rule.ok{color:var(--green)}
.sec-pw-rule.ok::before{background:var(--green)}
.sec-pw-warning{display:flex;gap:8px;padding:10px 11px;border-radius:10px;color:var(--txt-soft);background:rgba(245,158,45,.08);font-size:10.5px;line-height:1.45}
.sec-pw-warning svg{width:14px;height:14px;flex:none;color:var(--orange);margin-top:1px}
.sec-pw-footer{display:flex;justify-content:flex-end;gap:8px;padding:13px 20px 17px;border-top:1px solid var(--stroke)}
.sec-pw-action{height:38px;padding:0 16px;border-radius:10px;font:inherit;font-size:12px;font-weight:700}
.sec-pw-action.cancel{border:1px solid var(--stroke);color:var(--txt-soft)}
.sec-pw-action.submit{color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan))}
.sec-pw-action:disabled{opacity:.55;cursor:wait}
@media(max-width:520px){.sec-pw-rules{grid-template-columns:1fr}}
</style>
<?php $__env->stopPush(); ?>

<div class="cfg-panel" data-panel="seguridad">

  <div class="sec-head">
    <h2>Seguridad</h2>
    <p>Protege el acceso a tu cuenta, pacientes e información médica</p>
  </div>

  
  <div class="sec-top">
    <article class="card rise d2">
      <div class="cfg-card-head"><h2>Acceso y autenticación</h2></div>

      <div class="sec-row">
        <span class="sec-ico b"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
        <div class="sec-info">
          <div class="t">Contraseña</div>
          <div class="d" id="secPasswordUpdated">
            Última actualización:
            <?php echo e(format_user_date_time(auth()->user()->password_changed_at) ?: 'sin registro'); ?>

          </div>
        </div>
        <button type="button" class="sec-btn" id="secPasswordOpen">Cambiar contraseña</button>
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
        <p>Revisa y cierra las sesiones activas de tu cuenta</p>
        <p>Tu plan permite <?php echo e($sessionLimit ?? 1); ?> <?php echo e(($sessionLimit ?? 1) === 1 ? 'sesiÃ³n activa' : 'sesiones activas'); ?> por cuenta. Al superar el lÃ­mite se cerrarÃ¡ la sesiÃ³n mÃ¡s antigua.</p>
      </div>

      <div class="sec-table-wrap">
        <table class="sec-table">
          <thead>
            <tr><th>Dispositivo</th><th>Ubicación</th><th>Último acceso</th><th>Estado</th><th>Acciones</th></tr>
          </thead>
          <tbody id="secSessionsBody">
            <?php $__empty_1 = true; $__currentLoopData = $connectedSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $connectedSession): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
                $isCurrentSession = hash_equals($currentSessionId, $connectedSession->id);
              ?>
              <tr data-session-row="<?php echo e($connectedSession->id); ?>" data-current-session="<?php echo e($isCurrentSession ? '1' : '0'); ?>">
                <td>
                  <span class="sec-dev">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 5.5 10 4.5v7H3zM11 4.3 21 3v8.5H11zM3 12.5h7v7L3 18.5zM11 12.5h10V21l-10-1.3z"/></svg>
                    <span><b><?php echo e($connectedSession->deviceLabel()); ?></b><span><?php echo e($connectedSession->ip_address ?? 'IP no disponible'); ?></span></span>
                  </span>
                </td>
                <td><?php echo e($connectedSession->locationLabel()); ?></td>
                <td><?php echo e(format_user_date_time($connectedSession->lastActivityAt())); ?></td>
                <td><span class="sec-on <?php echo e($isCurrentSession ? 'current' : ''); ?>"><?php echo e($isCurrentSession ? 'Este dispositivo' : 'Activo'); ?></span></td>
                <td>
                  <button
                    type="button"
                    class="sec-link"
                    data-session-close="<?php echo e($connectedSession->id); ?>"
                    data-session-url="<?php echo e(route('configuracion.sessions.destroy', $connectedSession->id)); ?>"
                    <?php if($isCurrentSession): echo 'disabled'; endif; ?>
                  >
                    <?php echo e($isCurrentSession ? 'Sesión actual' : 'Cerrar sesión'); ?>

                  </button>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr id="secSessionsEmpty"><td class="sec-empty" colspan="5">No se encontraron sesiones activas.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="sec-foot">
        <button
          type="button"
          id="secCloseOtherSessions"
          data-url="<?php echo e(route('configuracion.sessions.destroy-others')); ?>"
          <?php if($connectedSessions->where('id', '!=', $currentSessionId)->isEmpty()): echo 'disabled'; endif; ?>
        >
          Cerrar todas las sesiones excepto la actual
        </button>
      </div>
    </article>
  </div>

  
  <div class="sec-bottom">
    <article class="card rise d3">
      <div class="cfg-card-head">
        <h2>Registro de actividad</h2>
        <p>Consulta acciones reales realizadas en tu cuenta</p>
      </div>
      <form class="sec-search" method="GET" action="<?php echo e(route('configuracion')); ?>">
        <input type="hidden" name="tab" value="seguridad">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="search" name="activity_search" value="<?php echo e(request('activity_search')); ?>" placeholder="Buscar acción, categoría o IP...">
        <button type="submit">Buscar</button>
      </form>
      <div class="sec-table-wrap">
        <table class="sec-table">
          <thead>
            <tr><th>Fecha y hora</th><th>Usuario</th><th>Acción realizada</th><th>Dispositivo</th><th>Dirección IP</th></tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
                $dotClass = match (true) {
                  str_contains($log->action, 'deleted') => 'dot-red',
                  $log->category === 'authentication' => 'dot-green',
                  $log->category === 'security' => 'dot-amber',
                  default => 'dot-blue',
                };
              ?>
              <tr>
                <td><?php echo e(format_user_date_time($log->created_at)); ?></td>
                <td><?php echo e($log->user?->name ?? 'Cuenta eliminada'); ?></td>
                <td><span class="sec-act"><i class="<?php echo e($dotClass); ?>"></i><?php echo e($log->description); ?></span></td>
                <td><?php echo e($log->deviceLabel()); ?></td>
                <td><?php echo e($log->ip_address ?? '—'); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr><td class="sec-empty" colspan="5">No hay actividad que coincida con la búsqueda.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php if($activityLogs->hasPages()): ?>
        <nav class="sec-pagination" aria-label="Paginación del registro de actividad">
          <?php if($activityLogs->onFirstPage()): ?>
            <span class="disabled">Anterior</span>
          <?php else: ?>
            <a href="<?php echo e($activityLogs->previousPageUrl()); ?>">Anterior</a>
          <?php endif; ?>
          <span>Página <?php echo e($activityLogs->currentPage()); ?> de <?php echo e($activityLogs->lastPage()); ?></span>
          <?php if($activityLogs->hasMorePages()): ?>
            <a href="<?php echo e($activityLogs->nextPageUrl()); ?>">Siguiente</a>
          <?php else: ?>
            <span class="disabled">Siguiente</span>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    </article>

    <article class="card rise d4">
      <div class="cfg-card-head">
        <h2>Permisos críticos</h2>
        <p>Confirma solicitudes para acciones sensibles</p>
      </div>
      <div class="sec-checks" id="criticalPermissions" data-update-url="<?php echo e(route('configuracion.security-settings.update')); ?>">
        <label class="sec-check"><input type="checkbox" data-critical-setting="require_password_for_studies" <?php if($securitySettings['require_password_for_studies']): echo 'checked'; endif; ?>> Solicitar contraseña para eliminar o editar estudios</label>
        <label class="sec-check"><input type="checkbox" data-critical-setting="require_password_for_patients" <?php if($securitySettings['require_password_for_patients']): echo 'checked'; endif; ?>> Solicitar contraseña para eliminar o editar paciente</label>
        <label class="sec-check"><input type="checkbox" data-critical-setting="audit_sensitive_actions" <?php if($securitySettings['audit_sensitive_actions']): echo 'checked'; endif; ?>> Registrar todas las acciones en auditoría</label>
        <span id="criticalPermissionsStatus" style="min-height:16px;font-size:11.5px;color:var(--txt-soft)"></span>
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

  <div class="sec-pw-overlay" id="secPasswordModal" aria-hidden="true">
    <form
      class="sec-pw-modal"
      id="secPasswordForm"
      data-update-url="<?php echo e(route('configuracion.password.update')); ?>"
    >
      <?php echo csrf_field(); ?>
      <div class="sec-pw-head">
        <div>
          <div class="sec-pw-title">Cambiar contraseña</div>
          <div class="sec-pw-sub">Utiliza una contraseña segura que no hayas usado anteriormente.</div>
        </div>
        <button type="button" class="sec-pw-close" id="secPasswordClose" aria-label="Cerrar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="sec-pw-body">
        <div class="sec-pw-field">
          <label for="secCurrentPassword">Contraseña actual</label>
          <div class="sec-pw-input-wrap">
            <input class="sec-pw-input" id="secCurrentPassword" name="current_password" type="password" autocomplete="current-password" required>
            <button type="button" class="sec-pw-eye" data-password-eye="secCurrentPassword" aria-label="Mostrar contraseña">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div class="sec-pw-error" data-password-error="current_password"></div>
        </div>

        <div class="sec-pw-field">
          <label for="secNewPassword">Contraseña nueva</label>
          <div class="sec-pw-input-wrap">
            <input class="sec-pw-input" id="secNewPassword" name="password" type="password" autocomplete="new-password" required>
            <button type="button" class="sec-pw-eye" data-password-eye="secNewPassword" aria-label="Mostrar contraseña">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div class="sec-pw-strength" id="secPasswordStrength" data-level="0"><i></i><i></i><i></i><i></i></div>
          <div class="sec-pw-error" data-password-error="password"></div>
        </div>

        <div class="sec-pw-rules">
          <span class="sec-pw-rule" data-password-rule="length">Mínimo 8 caracteres</span>
          <span class="sec-pw-rule" data-password-rule="lower">Una letra minúscula</span>
          <span class="sec-pw-rule" data-password-rule="upper">Una letra mayúscula</span>
          <span class="sec-pw-rule" data-password-rule="number">Un número</span>
        </div>

        <div class="sec-pw-field">
          <label for="secPasswordConfirmation">Confirmar contraseña nueva</label>
          <div class="sec-pw-input-wrap">
            <input class="sec-pw-input" id="secPasswordConfirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
            <button type="button" class="sec-pw-eye" data-password-eye="secPasswordConfirmation" aria-label="Mostrar contraseña">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div class="sec-pw-error" data-password-error="password_confirmation"></div>
        </div>

        <div class="sec-pw-warning">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Al guardar, las demás sesiones de tu cuenta se cerrarán. Este dispositivo permanecerá conectado.
        </div>
      </div>

      <div class="sec-pw-footer">
        <button type="button" class="sec-pw-action cancel" id="secPasswordCancel">Cancelar</button>
        <button type="submit" class="sec-pw-action submit" id="secPasswordSubmit">Actualizar contraseña</button>
      </div>
    </form>
  </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const modal = document.getElementById('secPasswordModal');
  const form = document.getElementById('secPasswordForm');
  const openButton = document.getElementById('secPasswordOpen');
  const closeButton = document.getElementById('secPasswordClose');
  const cancelButton = document.getElementById('secPasswordCancel');
  const submitButton = document.getElementById('secPasswordSubmit');
  const currentPassword = document.getElementById('secCurrentPassword');
  const newPassword = document.getElementById('secNewPassword');
  const confirmation = document.getElementById('secPasswordConfirmation');
  const strength = document.getElementById('secPasswordStrength');
  const csrf = form?.querySelector('input[name="_token"]')?.value;

  if (!modal || !form || !csrf) return;

  function openModal() {
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => currentPassword?.focus(), 80);
  }

  function closeModal() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    form.reset();
    clearErrors();
    updateStrength();
  }

  function clearErrors() {
    form.querySelectorAll('.sec-pw-input').forEach(input => input.classList.remove('invalid'));
    form.querySelectorAll('.sec-pw-error').forEach(error => {
      error.textContent = '';
      error.classList.remove('show');
    });
  }

  function showError(field, message) {
    const input = form.querySelector(`[name="${field}"]`);
    const error = form.querySelector(`[data-password-error="${field}"]`);
    input?.classList.add('invalid');
    if (error) {
      error.textContent = message;
      error.classList.add('show');
    }
  }

  function updateStrength() {
    const value = newPassword?.value || '';
    const checks = {
      length: value.length >= 8,
      lower: /[a-z]/.test(value),
      upper: /[A-Z]/.test(value),
      number: /\d/.test(value),
    };
    const level = Object.values(checks).filter(Boolean).length;
    if (strength) strength.dataset.level = String(level);
    Object.entries(checks).forEach(([rule, valid]) => {
      document.querySelector(`[data-password-rule="${rule}"]`)?.classList.toggle('ok', valid);
    });
  }

  function toast(message, error = false) {
    let element = document.getElementById('secPasswordToast');
    if (!element) {
      element = document.createElement('div');
      element.id = 'secPasswordToast';
      element.style.cssText = 'position:fixed;left:50%;bottom:24px;z-index:9999;'
        + 'transform:translate(-50%,12px);padding:11px 17px;border-radius:11px;'
        + 'background:var(--card);border:1px solid var(--stroke-strong);color:var(--txt);'
        + 'font-size:12.5px;font-weight:700;box-shadow:0 16px 40px rgba(0,0,0,.4);'
        + 'opacity:0;transition:opacity .2s,transform .2s;pointer-events:none';
      document.body.appendChild(element);
    }
    element.textContent = message;
    element.style.borderColor = error ? 'rgba(255,90,110,.45)' : 'rgba(61,220,151,.4)';
    requestAnimationFrame(() => {
      element.style.opacity = '1';
      element.style.transform = 'translate(-50%,0)';
    });
    clearTimeout(element._timer);
    element._timer = setTimeout(() => {
      element.style.opacity = '0';
      element.style.transform = 'translate(-50%,12px)';
    }, 2600);
  }

  openButton?.addEventListener('click', openModal);
  closeButton?.addEventListener('click', closeModal);
  cancelButton?.addEventListener('click', closeModal);
  modal.addEventListener('click', event => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && modal.classList.contains('open')) closeModal();
  });

  document.querySelectorAll('[data-password-eye]').forEach(button => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.passwordEye);
      if (!input) return;
      const visible = input.type === 'text';
      input.type = visible ? 'password' : 'text';
      button.setAttribute('aria-label', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
    });
  });

  newPassword?.addEventListener('input', updateStrength);

  form.addEventListener('submit', async event => {
    event.preventDefault();
    clearErrors();

    if (newPassword.value !== confirmation.value) {
      showError('password_confirmation', 'La confirmación no coincide con la contraseña nueva.');
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Actualizando...';

    try {
      const response = await fetch(form.dataset.updateUrl, {
        method: 'PATCH',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
          current_password: currentPassword.value,
          password: newPassword.value,
          password_confirmation: confirmation.value,
        }),
      });
      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        if (data.errors) {
          Object.entries(data.errors).forEach(([field, messages]) => {
            showError(field, messages[0]);
          });
        }
        throw new Error(data.message || 'Revisa los datos ingresados.');
      }

      const updated = document.getElementById('secPasswordUpdated');
      if (updated) updated.textContent = 'Última actualización: ahora';
      closeModal();
      toast(data.message);
    } catch (error) {
      if (!form.querySelector('.sec-pw-error.show')) toast(error.message, true);
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Actualizar contraseña';
    }
  });

  /* Dispositivos conectados */
  const sessionsBody = document.getElementById('secSessionsBody');
  const closeOtherSessions = document.getElementById('secCloseOtherSessions');

  async function closeSessionRequest(url) {
    const response = await fetch(url, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(data.message || 'No se pudo cerrar la sesión.');
    return data;
  }

  function updateSessionControls() {
    const otherRows = sessionsBody?.querySelectorAll('[data-current-session="0"]') || [];
    if (closeOtherSessions) closeOtherSessions.disabled = otherRows.length === 0;

    if (sessionsBody && sessionsBody.querySelectorAll('[data-session-row]').length === 0) {
      sessionsBody.innerHTML = '<tr id="secSessionsEmpty"><td class="sec-empty" colspan="5">No se encontraron sesiones activas.</td></tr>';
    }
  }

  document.querySelectorAll('[data-session-close]').forEach(button => {
    button.addEventListener('click', async () => {
      if (button.disabled || !window.confirm('¿Cerrar esta sesión? El dispositivo tendrá que iniciar sesión nuevamente.')) return;
      button.disabled = true;
      try {
        const data = await closeSessionRequest(button.dataset.sessionUrl);
        button.closest('[data-session-row]')?.remove();
        updateSessionControls();
        toast(data.message);
      } catch (error) {
        button.disabled = false;
        toast(error.message, true);
      }
    });
  });

  closeOtherSessions?.addEventListener('click', async () => {
    if (closeOtherSessions.disabled || !window.confirm('¿Cerrar todas las demás sesiones de tu cuenta?')) return;
    closeOtherSessions.disabled = true;
    try {
      const data = await closeSessionRequest(closeOtherSessions.dataset.url);
      sessionsBody?.querySelectorAll('[data-current-session="0"]').forEach(row => row.remove());
      updateSessionControls();
      toast(data.message);
    } catch (error) {
      closeOtherSessions.disabled = false;
      toast(error.message, true);
    }
  });
})();

(function(){
  const container = document.getElementById('criticalPermissions');
  const status = document.getElementById('criticalPermissionsStatus');
  const inputs = Array.from(container?.querySelectorAll('[data-critical-setting]') || []);
  if (!container || !inputs.length) return;

  const values = () => Object.fromEntries(inputs.map(input => [
    input.dataset.criticalSetting,
    input.checked
  ]));

  inputs.forEach(input => {
    input.addEventListener('change', async () => {
      const previous = !input.checked;
      inputs.forEach(item => { item.disabled = true; });
      status.textContent = 'Esperando confirmación...';

      try {
        const token = await window.CriticalSecurity.authorize(
          'security_settings',
          'Confirma tu contraseña para modificar tus permisos críticos.'
        );
        if (token === null) {
          input.checked = previous;
          status.textContent = 'Cambio cancelado.';
          return;
        }

        status.textContent = 'Guardando...';
        const response = await fetch(container.dataset.updateUrl, {
          method: 'PATCH',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'X-Critical-Authorization': token,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify(values())
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || 'No se pudieron guardar los permisos.');

        window.CriticalSecurity.setRequirement('studies', data.settings.require_password_for_studies);
        window.CriticalSecurity.setRequirement('patients', data.settings.require_password_for_patients);
        status.textContent = data.message;
      } catch (requestError) {
        input.checked = previous;
        status.textContent = requestError.message;
      } finally {
        inputs.forEach(item => { item.disabled = false; });
      }
    });
  });
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\LENOVO\enclaii-backend\resources\views/configuracion/sections/seguridad.blade.php ENDPATH**/ ?>