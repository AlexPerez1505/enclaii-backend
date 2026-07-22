<?php $__env->startSection('title', 'Pre-registro QR'); ?>
<?php $__env->startSection('active', 'qr'); ?>
<?php $__env->startSection('header-title', 'Pre-registro QR'); ?>
<?php $__env->startSection('header-sub', 'Genera códigos seguros y recibe los datos del paciente antes de su cita'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.qr-page{display:grid;gap:18px}
.qr-alert{padding:12px 15px;border-radius:12px;font-size:13px;font-weight:650;border:1px solid}
.qr-alert.ok{color:#3ddc97;background:rgba(61,220,151,.09);border-color:rgba(61,220,151,.24)}
.qr-alert.err{color:#ff7183;background:rgba(255,90,110,.09);border-color:rgba(255,90,110,.24)}
.qr-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.qr-stat{padding:17px 18px;border:1px solid var(--stroke);border-radius:16px;background:var(--card)}
.qr-stat span{font-size:12px;color:var(--txt-soft)}
.qr-stat strong{display:block;margin-top:5px;font:800 25px 'Sora',sans-serif}
.qr-grid{display:grid;grid-template-columns:minmax(440px,.95fr) minmax(0,1.45fr);gap:18px;align-items:start}
.qr-card{padding:20px;border:1px solid var(--stroke);border-radius:18px;background:var(--card)}
.qr-card h2{font:700 16px 'Sora',sans-serif}
.qr-card-sub{margin-top:4px;font-size:12.5px;line-height:1.5;color:var(--txt-soft)}
.qr-status{display:inline-flex;padding:3px 8px;border-radius:99px;font-size:9.5px;font-weight:750}
.qr-status.active,.qr-status.accepted{color:#3ddc97;background:rgba(61,220,151,.12)}
.qr-status.pending,.qr-status.submitted{color:#f9b34b;background:rgba(245,158,45,.12)}
.qr-status.expired,.qr-status.revoked,.qr-status.rejected{color:#ff7183;background:rgba(255,90,110,.12)}
.qr-builder{display:grid;grid-template-columns:180px minmax(0,1fr);gap:18px;margin-top:18px;align-items:start}
.qr-preview{display:grid;gap:10px;justify-items:center}.qr-preview-box{position:relative;width:180px;height:180px;padding:9px;border:1px dashed var(--stroke-strong);border-radius:14px;background:#fff}.qr-preview-box img{width:100%;height:100%;display:block}.qr-preview-box.unavailable img{opacity:.3;filter:grayscale(1)}.qr-preview-state{position:absolute;inset:9px;display:grid;place-items:center;border-radius:8px;background:rgba(6,8,28,.62);color:#fff;font-size:11px;font-weight:800;text-transform:uppercase}.qr-ready{display:inline-flex;align-items:center;gap:5px;padding:4px 9px;border-radius:99px;color:#1fc882;background:rgba(31,200,130,.12);font-size:9.5px;font-weight:800}.qr-no-code{height:100%;display:grid;place-items:center;padding:18px;text-align:center;color:#62769e;font-size:11px;line-height:1.4}
.qr-create{display:grid;gap:13px}.qr-create fieldset{display:grid;gap:8px;margin:0;padding:0;border:0}.qr-create legend,.qr-field-label{margin-bottom:7px;color:var(--txt-soft);font-size:11px;font-weight:750}.qr-expiration{display:grid;grid-template-columns:repeat(3,1fr);gap:6px}.qr-expiration input{position:absolute;opacity:0;pointer-events:none}.qr-expiration label{padding:9px 5px;border:1px solid var(--stroke-strong);border-radius:9px;text-align:center;color:var(--txt-soft);font-size:10px;font-weight:700;cursor:pointer}.qr-expiration input:checked+label{border-color:var(--blue);color:var(--blue);background:rgba(46,123,246,.1);box-shadow:0 0 0 2px rgba(46,123,246,.08)}
.qr-message-wrap{position:relative}.qr-message{width:100%;min-height:78px;padding:10px 11px 24px;border:1px solid var(--stroke-strong);border-radius:10px;resize:vertical;background:var(--panel-2);color:var(--txt);font:inherit;font-size:11px;line-height:1.45;outline:none}.qr-message:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.1)}.qr-message-count{position:absolute;right:9px;bottom:7px;color:var(--txt-soft);font-size:9px}.qr-primary{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 12px;border:0;border-radius:10px;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-size:11.5px;font-weight:800;cursor:pointer}
.qr-current-info{display:grid;gap:8px;margin-top:16px}.qr-code-line{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:10px 11px;border-radius:10px;background:rgba(46,123,246,.07);font-size:11px}.qr-code-line strong{color:var(--blue)}.qr-icon-btn{padding:3px;border:0;color:var(--blue);background:transparent;cursor:pointer;font-size:15px}.qr-dates{display:grid;gap:5px;padding:0 3px;color:var(--txt-soft);font-size:10.5px}.qr-dates b{color:var(--txt)}.qr-dates .expires{color:#ff996e}
.qr-share-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:15px}.qr-action{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:9px;border:1px solid var(--stroke-strong);border-radius:9px;background:transparent;color:var(--cyan);font:inherit;font-size:10px;font-weight:750;cursor:pointer;text-decoration:none}.qr-action.whatsapp{color:#28d878}.qr-action.pdf{color:#ff7183}.qr-action.print{grid-column:span 2}.qr-action[disabled],.qr-action.disabled{opacity:.42;pointer-events:none}
.qr-action.danger{color:var(--red)}
.qr-note{display:flex;gap:8px;margin-top:15px;padding:11px;border-radius:11px;background:rgba(14,165,233,.07);color:var(--txt-soft);font-size:10.5px;line-height:1.5}
.qr-history{margin-top:18px;padding-top:17px;border-top:1px solid var(--stroke)}.qr-history-title{font:700 12px 'Sora',sans-serif}.qr-history-tabs{display:flex;gap:5px;margin-top:11px;overflow-x:auto;border-bottom:1px solid var(--stroke)}.qr-history-tab{display:flex;align-items:center;gap:5px;padding:8px 7px;border:0;border-bottom:2px solid transparent;color:var(--txt-soft);background:transparent;font-size:9.5px;font-weight:750;white-space:nowrap;cursor:pointer}.qr-history-tab.active{border-color:var(--blue);color:var(--blue)}.qr-history-tab span{min-width:17px;padding:2px 5px;border-radius:99px;background:rgba(110,160,255,.1);font-size:8px}
.qr-history-scroll{overflow-x:auto}.qr-history-table{width:100%;border-collapse:collapse;font-size:9.5px}.qr-history-table th{padding:9px 6px;text-align:left;color:var(--txt-soft);font-size:8.5px;font-weight:700}.qr-history-table td{padding:9px 6px;border-top:1px solid var(--stroke);white-space:nowrap}.qr-history-code{color:var(--txt);font-weight:750;text-decoration:none}.qr-history-empty{padding:22px 8px;text-align:center;color:var(--txt-soft);font-size:10.5px}.qr-history-menu{position:relative}.qr-history-menu summary{list-style:none;cursor:pointer;color:var(--txt-soft);font-size:16px}.qr-history-menu summary::-webkit-details-marker{display:none}.qr-history-menu-pop{position:absolute;right:0;top:24px;z-index:10;display:grid;min-width:125px;padding:5px;border:1px solid var(--stroke-strong);border-radius:9px;background:var(--panel);box-shadow:0 12px 30px rgba(0,0,0,.28)}.qr-history-menu-pop a,.qr-history-menu-pop button{width:100%;padding:7px 8px;border:0;text-align:left;color:var(--txt-soft);background:transparent;font:inherit;font-size:9.5px;cursor:pointer;text-decoration:none}.qr-history-menu-pop button.danger{color:var(--red)}
.qr-empty{padding:25px 10px;text-align:center;color:var(--txt-soft);font-size:12.5px}
.qr-prereg-list{display:grid;gap:12px;margin-top:16px}
.qr-prereg{border:1px solid var(--stroke);border-radius:14px;background:var(--panel-2);overflow:hidden}
.qr-prereg summary{list-style:none;display:grid;grid-template-columns:minmax(160px,1.3fr) 1fr auto;gap:12px;align-items:center;padding:14px;cursor:pointer}
.qr-prereg summary::-webkit-details-marker{display:none}
.qr-person{display:flex;align-items:center;gap:10px;min-width:0}.qr-person-avatar{width:38px;height:38px;flex:none;border-radius:11px;display:grid;place-items:center;overflow:hidden;background:rgba(14,165,233,.12);color:var(--cyan);font-size:13px;font-weight:800}.qr-person-avatar img{width:100%;height:100%;object-fit:cover}.qr-person-info{min-width:0}
.qr-person strong{display:block;font-size:13px}
.qr-person span,.qr-meta{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:10.5px;color:var(--txt-soft)}
.qr-detail{padding:0 14px 15px;border-top:1px solid var(--stroke)}
.qr-photo-review{display:flex;align-items:center;gap:13px;margin-top:14px;padding:11px;border:1px solid rgba(14,165,233,.18);border-radius:12px;background:rgba(14,165,233,.055)}.qr-photo-review img{width:92px;height:92px;flex:none;border-radius:12px;object-fit:cover}.qr-photo-review strong{display:block;margin-bottom:4px;font-size:12px}.qr-photo-review span{font-size:10.5px;line-height:1.45;color:var(--txt-soft)}
.qr-data{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:14px}
.qr-data div{padding:10px;border-radius:10px;background:rgba(110,160,255,.055)}
.qr-data b{display:block;margin-bottom:3px;font-size:10px;color:var(--txt-soft)}
.qr-data span{font-size:11.5px;white-space:pre-wrap}
.qr-data .wide{grid-column:span 3}
.qr-warning{margin-top:12px;padding:9px 11px;border-radius:9px;color:#f9b34b;background:rgba(245,158,45,.09);font-size:11px}
.qr-review-actions{display:flex;justify-content:flex-end;gap:9px;margin-top:14px}
.qr-review-actions button{padding:9px 13px;border-radius:9px;font-size:11.5px;font-weight:750;cursor:pointer}
.qr-accept{border:0;background:#16a86b;color:#fff}
.qr-reject{border:1px solid rgba(255,90,110,.35);color:var(--red)}
@media(max-width:1250px){.qr-grid{grid-template-columns:1fr}.qr-builder{grid-template-columns:180px minmax(0,1fr)}}
@media(max-width:720px){.qr-stats{grid-template-columns:1fr}.qr-builder{grid-template-columns:1fr}.qr-preview-box{width:min(210px,70vw);height:min(210px,70vw)}.qr-prereg summary{grid-template-columns:1fr auto}.qr-meta{display:none}.qr-data{grid-template-columns:1fr 1fr}.qr-data .wide{grid-column:span 2}}
@media(max-width:480px){.qr-expiration{grid-template-columns:1fr}.qr-share-actions{grid-template-columns:1fr}.qr-action.print{grid-column:span 1}.qr-data{grid-template-columns:1fr}.qr-data .wide{grid-column:span 1}}
@media print{body *{visibility:hidden!important}.qr-print-target,.qr-print-target *{visibility:visible!important}.qr-print-target{position:fixed;inset:0;display:grid;place-items:center;background:#fff}.qr-print-target .qr-preview-box{width:420px;height:420px;border:0}.qr-print-target .qr-preview-state,.qr-print-target .qr-ready{display:none!important}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
  $historyStatus = fn($link) => $link->status === 'active' && $link->expires_at->isPast() ? 'expired' : $link->status;
  $activeLinks = $links->filter(fn($link) => $historyStatus($link) === 'active');
  $usedLinks = $links->filter(fn($link) => $historyStatus($link) === 'submitted');
  $expiredLinks = $links->filter(fn($link) => $historyStatus($link) === 'expired');
  $revokedLinks = $links->filter(fn($link) => $historyStatus($link) === 'revoked');
  $pendingCount = $preregistrations->where('status', 'pending')->count();
  $acceptedCount = $preregistrations->where('status', 'accepted')->count();
  $statusLabels = [
    'active' => 'Activo',
    'submitted' => 'Utilizado',
    'expired' => 'Vencido',
    'revoked' => 'Cancelado',
    'pending' => 'Pendiente',
    'accepted' => 'Aceptado',
    'rejected' => 'Rechazado',
  ];
  $currentStatus = $currentLink ? $historyStatus($currentLink) : null;
  $currentAvailable = $currentLink && $currentStatus === 'active' && !$currentLink->preregistration;
  $currentPublicUrl = $currentLink ? route('qr.public.show', ['token' => $currentLink->token]) : null;
  $currentCode = $currentLink ? 'QR-'.$currentLink->created_at->format('Y').'-'.str_pad((string) $currentLink->id, 4, '0', STR_PAD_LEFT) : null;
  $defaultHistoryStatus = $activeLinks->isNotEmpty() ? 'active' : ($usedLinks->isNotEmpty() ? 'submitted' : ($expiredLinks->isNotEmpty() ? 'expired' : 'revoked'));
  $qrSettings = $qrSettings ?? auth()->user()->resolvedSettings();
  $qrDefaultExpiration = (string) old('expires_in_hours', $qrSettings['qr_default_expiration_hours'] ?? '48');
  $qrDefaultPatientMessage = (string) old('patient_message', $qrSettings['qr_default_patient_message'] ?? '');
?>

<div class="qr-page">
  <?php if(session('success')): ?><div class="qr-alert ok"><?php echo e(session('success')); ?></div><?php endif; ?>
  <?php if(session('error')): ?><div class="qr-alert err"><?php echo e(session('error')); ?></div><?php endif; ?>

  <div class="qr-stats">
    <div class="qr-stat"><span>QR activos</span><strong><?php echo e($activeLinks->count()); ?></strong></div>
    <div class="qr-stat"><span>Pre-registros pendientes</span><strong><?php echo e($pendingCount); ?></strong></div>
    <div class="qr-stat"><span>Expedientes aceptados</span><strong><?php echo e($acceptedCount); ?></strong></div>
  </div>

  <div class="qr-grid">
    <div class="qr-card">
      <h2>Generar nuevo QR</h2>
      <p class="qr-card-sub">Configura un enlace seguro y compártelo con el paciente para que complete sus datos.</p>

      <div class="qr-builder">
        <div class="qr-preview qr-print-target">
          <div class="qr-preview-box <?php echo e($currentAvailable ? '' : 'unavailable'); ?>">
            <?php if($currentLink): ?>
              <img src="<?php echo e(route('qr.links.image', $currentLink)); ?>" alt="Código QR <?php echo e($currentCode); ?>">
              <?php if (! ($currentAvailable)): ?>
                <span class="qr-preview-state"><?php echo e($statusLabels[$currentStatus] ?? 'No disponible'); ?></span>
              <?php endif; ?>
            <?php else: ?>
              <div class="qr-no-code">Genera tu primer código QR para comenzar.</div>
            <?php endif; ?>
          </div>
          <?php if($currentAvailable): ?>
            <span class="qr-ready">● QR listo para usar</span>
          <?php elseif($currentLink): ?>
            <span class="qr-status <?php echo e($currentStatus); ?>"><?php echo e($statusLabels[$currentStatus] ?? ucfirst($currentStatus)); ?></span>
          <?php endif; ?>
        </div>

        <form class="qr-create" method="POST" action="<?php echo e(route('qr.links.store')); ?>">
          <?php echo csrf_field(); ?>
          <fieldset>
            <legend>Vigencia del enlace</legend>
            <div class="qr-expiration">
              <input type="radio" id="qr24" name="expires_in_hours" value="24" <?php if($qrDefaultExpiration === '24'): echo 'checked'; endif; ?>>
              <label for="qr24">○ 24 horas</label>
              <input type="radio" id="qr48" name="expires_in_hours" value="48" <?php if($qrDefaultExpiration === '48'): echo 'checked'; endif; ?>>
              <label for="qr48">○ 48 horas</label>
              <input type="radio" id="qr168" name="expires_in_hours" value="168" <?php if($qrDefaultExpiration === '168'): echo 'checked'; endif; ?>>
              <label for="qr168">○ 7 días</label>
            </div>
          </fieldset>
          <div>
            <div class="qr-field-label">Mensaje para el paciente <span style="font-weight:500">(opcional)</span></div>
            <div class="qr-message-wrap">
              <textarea class="qr-message" id="qrPatientMessage" name="patient_message" maxlength="150" placeholder="Por favor completa tus datos con la mayor información posible. Gracias."><?php echo e($qrDefaultPatientMessage); ?></textarea>
              <span class="qr-message-count"><span id="qrMessageCount"><?php echo e(mb_strlen($qrDefaultPatientMessage)); ?></span>/150</span>
            </div>
          </div>
          <button class="qr-primary" type="submit"><span style="font-size:16px">＋</span> <?php echo e($currentLink && !$currentAvailable ? 'Generar reemplazo' : 'Generar nuevo código'); ?></button>
        </form>
      </div>

      <?php if($currentLink): ?>
        <div class="qr-current-info">
          <div class="qr-code-line">
            <span>Código: <strong><?php echo e($currentCode); ?></strong></span>
            <button class="qr-icon-btn" type="button" title="Copiar código" data-copy-text="<?php echo e($currentCode); ?>">▣</button>
          </div>
          <div class="qr-dates">
            <span><b>Creado:</b> <?php echo e(format_user_date($currentLink->created_at).' · '.format_user_time($currentLink->created_at)); ?></span>
            <span class="expires"><b>Expira:</b> <?php echo e(format_user_date($currentLink->expires_at).' · '.format_user_time($currentLink->expires_at)); ?></span>
          </div>
        </div>

        <?php
          $template = $qrSettings['qr_whatsapp_template'] ?? 'Hola, te comparto tu enlace de pre-registro de ENCLAII: {enlace}';
          $shareText = strtr($template, [
            '{enlace}' => $currentPublicUrl,
            '{codigo}' => $currentCode,
            '{mensaje}' => $currentLink->patient_message ?: '',
            '{clinica}' => auth()->user()->clinica?->nombre ?? 'ENCLAII',
          ]);
          if (! str_contains($shareText, $currentPublicUrl)) {
            $shareText = trim($shareText.' '.$currentPublicUrl);
          }
          $imageUrl = route('qr.links.image', $currentLink);
        ?>
        <div class="qr-share-actions">
          <?php if($currentAvailable): ?>
            <a class="qr-action whatsapp" href="https://wa.me/?text=<?php echo e(urlencode($shareText)); ?>" target="_blank" rel="noopener">◉ Enviar por WhatsApp</a>
            <button class="qr-action" type="button" data-copy-url="<?php echo e($currentPublicUrl); ?>">Copiar enlace</button>
          <?php endif; ?>
          <button class="qr-action" type="button" data-download-png="<?php echo e($imageUrl); ?>" data-filename="<?php echo e($currentCode); ?>">Descargar PNG</button>
          <button class="qr-action pdf" type="button" data-download-pdf="<?php echo e($imageUrl); ?>" data-filename="<?php echo e($currentCode); ?>">▧ Descargar PDF</button>
          <button class="qr-action print" type="button" data-print-qr>▣ Imprimir QR</button>
        </div>
      <?php endif; ?>

      <div class="qr-note"><span>ⓘ</span><span>Cada código acepta un solo envío. Una vez utilizado, el QR queda inactivo automáticamente.</span></div>

      <section class="qr-history">
        <h3 class="qr-history-title">Historial de QR</h3>
        <div class="qr-history-tabs" role="tablist" aria-label="Filtrar historial de QR">
          <button class="qr-history-tab <?php echo e($defaultHistoryStatus === 'active' ? 'active' : ''); ?>" type="button" data-history-filter="active">Activos <span><?php echo e($activeLinks->count()); ?></span></button>
          <button class="qr-history-tab <?php echo e($defaultHistoryStatus === 'submitted' ? 'active' : ''); ?>" type="button" data-history-filter="submitted">Utilizados <span><?php echo e($usedLinks->count()); ?></span></button>
          <button class="qr-history-tab <?php echo e($defaultHistoryStatus === 'expired' ? 'active' : ''); ?>" type="button" data-history-filter="expired">Expirados <span><?php echo e($expiredLinks->count()); ?></span></button>
          <button class="qr-history-tab <?php echo e($defaultHistoryStatus === 'revoked' ? 'active' : ''); ?>" type="button" data-history-filter="revoked">Cancelados <span><?php echo e($revokedLinks->count()); ?></span></button>
        </div>
        <div class="qr-history-scroll">
          <table class="qr-history-table">
            <thead><tr><th>Código</th><th>Vigencia</th><th>Creado</th><th>Expira</th><th>Estado</th><th>Registros</th><th></th></tr></thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                  $displayStatus = $historyStatus($link);
                  $code = 'QR-'.$link->created_at->format('Y').'-'.str_pad((string) $link->id, 4, '0', STR_PAD_LEFT);
                  $validityHours = (int) round($link->created_at->diffInHours($link->expires_at));
                  $validityLabel = $validityHours === 168 ? '7 días' : $validityHours.' horas';
                ?>
                <tr data-history-status="<?php echo e($displayStatus); ?>" <?php if($displayStatus !== $defaultHistoryStatus): ?> hidden <?php endif; ?>>
                  <td><a class="qr-history-code" href="<?php echo e(route('qr.index', ['qr' => $link->id])); ?>"><?php echo e($code); ?></a></td>
                  <td><?php echo e($validityLabel); ?></td>
                  <td><?php echo e(format_user_date($link->created_at)); ?></td>
                  <td><?php echo e(format_user_date($link->expires_at)); ?></td>
                  <td><span class="qr-status <?php echo e($displayStatus); ?>"><?php echo e($statusLabels[$displayStatus] ?? ucfirst($displayStatus)); ?></span></td>
                  <td><?php echo e($link->preregistration ? 1 : 0); ?></td>
                  <td>
                    <details class="qr-history-menu">
                      <summary aria-label="Acciones del código">⋮</summary>
                      <div class="qr-history-menu-pop">
                        <a href="<?php echo e(route('qr.index', ['qr' => $link->id])); ?>">Ver código</a>
                        <?php if($displayStatus === 'active'): ?>
                          <form method="POST" action="<?php echo e(route('qr.links.destroy', $link)); ?>" data-delete-confirmed="true">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="danger" type="submit" onclick="return confirm('¿Invalidar este QR?')">Invalidar</button>
                          </form>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('qr.links.archive', $link)); ?>" data-delete-confirmed="true">
                          <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                          <button class="danger" type="submit" onclick="return confirm('¿Eliminar este QR del historial visible?')">Eliminar</button>
                        </form>
                      </div>
                    </details>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7"><div class="qr-history-empty">Todavía no has generado códigos QR.</div></td></tr>
              <?php endif; ?>
              <?php if($links->isNotEmpty()): ?>
                <tr id="qrHistoryEmpty" hidden><td colspan="7"><div class="qr-history-empty">No hay códigos con este estado.</div></td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>

    <div class="qr-card">
      <h2>Pre-registros recibidos</h2>
      <p class="qr-card-sub">Revisa la información antes de crear el expediente definitivo.</p>
      <div class="qr-prereg-list">
        <?php $__empty_1 = true; $__currentLoopData = $preregistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <details class="qr-prereg" <?php if(session('new_qr_link_id') === $item->registration_link_id): ?> open <?php endif; ?>>
            <summary>
              <div class="qr-person">
                <div class="qr-person-avatar">
                  <?php if($item->foto): ?>
                    <img src="<?php echo e(media_url($item->foto)); ?>" alt="Foto de <?php echo e($item->nombre_completo); ?>">
                  <?php else: ?>
                    <?php echo e(mb_strtoupper(mb_substr($item->nombre_completo, 0, 1))); ?>

                  <?php endif; ?>
                </div>
                <div class="qr-person-info">
                  <strong><?php echo e($item->nombre_completo); ?></strong>
                  <span><?php echo e($item->telefono); ?> · <?php echo e($item->email ?: 'Sin correo'); ?></span>
                </div>
              </div>
              <div class="qr-meta">Recibido <?php echo e($item->created_at->diffForHumans()); ?></div>
              <span class="qr-status <?php echo e($item->status); ?>"><?php echo e($statusLabels[$item->status] ?? ucfirst($item->status)); ?></span>
            </summary>
            <div class="qr-detail">
              <?php if($possibleDuplicates[$item->id] ?? false): ?>
                <div class="qr-warning">Existe un paciente con el mismo teléfono o correo. Revisa posibles duplicados antes de aceptar.</div>
              <?php endif; ?>
              <?php if($item->foto): ?>
                <div class="qr-photo-review">
                  <img src="<?php echo e(media_url($item->foto)); ?>" alt="Fotografía enviada por <?php echo e($item->nombre_completo); ?>">
                  <div><strong>Fotografía enviada por el paciente</strong><span>Al aceptar este pre-registro, la fotografía se agregará automáticamente a su expediente.</span></div>
                </div>
              <?php endif; ?>
              <div class="qr-data">
                <div><b>Fecha de nacimiento</b><span><?php echo e(format_user_date($item->fecha_nacimiento)); ?> (<?php echo e($item->edad); ?> años)</span></div>
                <div><b>Sexo</b><span><?php echo e(ucfirst($item->sexo ?: 'No indicado')); ?></span></div>
                <div><b>Peso / altura</b><span><?php echo e($item->peso ?: '—'); ?> kg · <?php echo e($item->altura ?: '—'); ?> m</span></div>
                <div class="wide"><b>Dirección</b><span><?php echo e($item->direccion ?: 'No indicada'); ?></span></div>
                <div><b>Procedimiento</b><span><?php echo e($item->procedimiento ?: 'No indicado'); ?></span></div>
                <div><b>Identificación</b><span><?php echo e($item->identificacion ?: 'No indicada'); ?></span></div>
                <div><b>Consentimiento</b><span><?php echo e(format_user_date($item->consent_accepted_at)); ?></span></div>
                <div class="wide"><b>Motivo de consulta</b><span><?php echo e($item->motivo_consulta ?: 'No indicado'); ?></span></div>
                <div class="wide"><b>Alergias</b><span><?php echo e($item->alergias ?: 'Ninguna indicada'); ?></span></div>
                <div class="wide"><b>Enfermedades</b><span><?php echo e($item->enfermedades ?: 'Ninguna indicada'); ?></span></div>
                <div class="wide"><b>Medicamentos actuales</b><span><?php echo e($item->medicamentos_actuales ?: 'Ninguno indicado'); ?></span></div>
                <div class="wide"><b>Antecedentes médicos</b><span><?php echo e($item->antecedentes_medicos ?: 'No indicados'); ?></span></div>
                <?php if($item->observaciones): ?><div class="wide"><b>Observaciones</b><span><?php echo e($item->observaciones); ?></span></div><?php endif; ?>
              </div>
              <?php if($item->status === 'pending'): ?>
                <div class="qr-review-actions">
                  <form method="POST" action="<?php echo e(route('qr.preregistrations.reject', $item)); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="qr-reject" type="submit" onclick="return confirm('¿Rechazar este pre-registro?')">Rechazar</button>
                  </form>
                  <form method="POST" action="<?php echo e(route('qr.preregistrations.accept', $item)); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="qr-accept" type="submit" onclick="return confirm('¿Crear el expediente de este paciente?')">Aceptar y crear paciente</button>
                  </form>
                </div>
              <?php elseif($item->patient): ?>
                <div class="qr-review-actions"><a class="qr-action" href="<?php echo e(route('pacientes.edit', $item->patient)); ?>">Abrir expediente <?php echo e($item->patient->folio); ?></a></div>
              <?php endif; ?>
            </div>
          </details>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div class="qr-empty">Los formularios enviados por pacientes aparecerán aquí.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const qrMessage = document.getElementById('qrPatientMessage');
const qrMessageCount = document.getElementById('qrMessageCount');
qrMessage?.addEventListener('input', () => {
  qrMessageCount.textContent = qrMessage.value.length;
});

async function copyQrValue(button, value) {
  try {
    await navigator.clipboard.writeText(value);
    const original = button.textContent;
    button.textContent = 'Copiado';
    setTimeout(() => { button.textContent = original; }, 1500);
  } catch (error) {
    window.prompt('Copia este valor:', value);
  }
}

document.querySelectorAll('[data-copy-url]').forEach(button => {
  button.addEventListener('click', () => copyQrValue(button, button.dataset.copyUrl));
});
document.querySelectorAll('[data-copy-text]').forEach(button => {
  button.addEventListener('click', () => copyQrValue(button, button.dataset.copyText));
});

const historyRows = [...document.querySelectorAll('[data-history-status]')];
const historyEmpty = document.getElementById('qrHistoryEmpty');
document.querySelectorAll('[data-history-filter]').forEach(button => {
  button.addEventListener('click', () => {
    document.querySelectorAll('[data-history-filter]').forEach(tab => tab.classList.remove('active'));
    button.classList.add('active');
    let visible = 0;
    historyRows.forEach(row => {
      const show = row.dataset.historyStatus === button.dataset.historyFilter;
      row.hidden = !show;
      if (show) visible++;
    });
    if (historyEmpty) historyEmpty.hidden = visible !== 0;
  });
});

async function renderQrCanvas(url) {
  const response = await fetch(url, { credentials: 'same-origin' });
  if (!response.ok) throw new Error('No se pudo cargar el código QR.');
  const svg = await response.text();
  const objectUrl = URL.createObjectURL(new Blob([svg], { type: 'image/svg+xml' }));
  const image = new Image();

  try {
    await new Promise((resolve, reject) => {
      image.onload = resolve;
      image.onerror = reject;
      image.src = objectUrl;
    });

    const canvas = document.createElement('canvas');
    canvas.width = 1200;
    canvas.height = 1200;
    const context = canvas.getContext('2d');
    context.fillStyle = '#ffffff';
    context.fillRect(0, 0, canvas.width, canvas.height);
    context.drawImage(image, 0, 0, canvas.width, canvas.height);
    return canvas;
  } finally {
    URL.revokeObjectURL(objectUrl);
  }
}

function downloadBlob(blob, filename) {
  const url = URL.createObjectURL(blob);
  const anchor = document.createElement('a');
  anchor.href = url;
  anchor.download = filename;
  document.body.appendChild(anchor);
  anchor.click();
  anchor.remove();
  setTimeout(() => URL.revokeObjectURL(url), 1000);
}

function combineBytes(chunks) {
  const length = chunks.reduce((total, chunk) => total + chunk.length, 0);
  const result = new Uint8Array(length);
  let offset = 0;
  chunks.forEach(chunk => {
    result.set(chunk, offset);
    offset += chunk.length;
  });
  return result;
}

async function canvasToPdf(canvas) {
  const jpegBlob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', .95));
  if (!jpegBlob) throw new Error('No se pudo preparar el PDF.');
  const jpeg = new Uint8Array(await jpegBlob.arrayBuffer());
  const encode = value => new TextEncoder().encode(value);
  const parts = [encode('%PDF-1.4\n')];
  const offsets = [0];
  let byteLength = parts[0].length;

  const addObject = object => {
    offsets.push(byteLength);
    parts.push(object);
    byteLength += object.length;
  };

  addObject(encode('1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n'));
  addObject(encode('2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n'));
  addObject(encode('3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /XObject << /Im0 4 0 R >> >> /Contents 5 0 R >>\nendobj\n'));
  addObject(combineBytes([
    encode(`4 0 obj\n<< /Type /XObject /Subtype /Image /Width ${canvas.width} /Height ${canvas.height} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ${jpeg.length} >>\nstream\n`),
    jpeg,
    encode('\nendstream\nendobj\n'),
  ]));
  const content = 'q\n420 0 0 420 87.5 315 cm\n/Im0 Do\nQ\n';
  addObject(encode(`5 0 obj\n<< /Length ${content.length} >>\nstream\n${content}endstream\nendobj\n`));

  const xrefOffset = byteLength;
  const rows = offsets.slice(1).map(offset => `${String(offset).padStart(10, '0')} 00000 n \n`).join('');
  parts.push(encode(`xref\n0 6\n0000000000 65535 f \n${rows}trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n${xrefOffset}\n%%EOF`));

  return new Blob([combineBytes(parts)], { type: 'application/pdf' });
}

document.querySelectorAll('[data-download-png]').forEach(button => {
  button.addEventListener('click', async () => {
    const original = button.textContent;
    button.disabled = true;
    button.textContent = 'Preparando…';
    try {
      const canvas = await renderQrCanvas(button.dataset.downloadPng);
      const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
      if (!blob) throw new Error('No se pudo preparar el PNG.');
      downloadBlob(blob, `${button.dataset.filename}.png`);
    } catch (error) {
      alert(error.message || 'No se pudo descargar el código.');
    } finally {
      button.disabled = false;
      button.textContent = original;
    }
  });
});

document.querySelectorAll('[data-download-pdf]').forEach(button => {
  button.addEventListener('click', async () => {
    const original = button.textContent;
    button.disabled = true;
    button.textContent = 'Preparando…';
    try {
      const canvas = await renderQrCanvas(button.dataset.downloadPdf);
      downloadBlob(await canvasToPdf(canvas), `${button.dataset.filename}.pdf`);
    } catch (error) {
      alert(error.message || 'No se pudo descargar el PDF.');
    } finally {
      button.disabled = false;
      button.textContent = original;
    }
  });
});

document.querySelector('[data-print-qr]')?.addEventListener('click', () => window.print());
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\enclaii-backend\resources\views/qr/index.blade.php ENDPATH**/ ?>