<?php $__env->startSection('active', 'soporte'); ?>
<?php $__env->startSection('title', 'Ticket '.$ticket->operation_folio); ?>
<?php $__env->startSection('header-title', 'Detalle del ticket'); ?>
<?php $__env->startSection('header-sub', $ticket->subject); ?>

<?php $__env->startPush('styles'); ?>
<style>
.tk-page{--tk-bg:#060b14;--tk-panel:#0f1629;--tk-panel-2:#131b32;--tk-border:#1e293b;--tk-border-soft:#253047;--tk-text:#e2e8f0;--tk-text-soft:#94a3b8;--tk-blue:#3b82f6;--tk-blue-soft:#1d4ed8;--tk-cyan:#06b6d4;--tk-amber:#f59e0b;--tk-green:#22c55e;--tk-red:#ef4444;--tk-radius:18px;--tk-shadow:0 10px 30px rgba(0,0,0,.25)}
.tk-page{display:grid;gap:22px;grid-template-columns:1fr;max-width:900px;margin:0 auto}
.tk-col{min-width:0}
.tk-card{background:var(--tk-panel);border:1px solid var(--tk-border);border-radius:var(--tk-radius);box-shadow:var(--tk-shadow);overflow:hidden;position:relative;margin-bottom:22px}
.tk-card:last-child{margin-bottom:0}
.tk-card-glow::before{content:'';position:absolute;left:0;top:0;width:3px;height:100%;background:linear-gradient(180deg,var(--tk-blue),transparent);border-radius:var(--tk-radius) 0 0 var(--tk-radius)}
.tk-card-header{padding:22px 24px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--tk-border-soft)}
.tk-card-icon{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,rgba(59,130,246,.25),rgba(37,99,235,.12));display:grid;place-items:center;color:var(--tk-blue);flex-shrink:0}
.tk-card-icon.green{background:linear-gradient(135deg,rgba(34,197,94,.25),rgba(22,163,74,.12));color:var(--tk-green)}
.tk-card-icon.amber{background:linear-gradient(135deg,rgba(245,158,11,.25),rgba(180,83,9,.12));color:var(--tk-amber)}
.tk-card-title{font-size:16px;font-weight:700;color:var(--tk-text);margin:0}
.tk-card-body{padding:24px}
.tk-field{margin-bottom:22px}
.tk-field:last-child{margin-bottom:0}
.tk-field-label{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--tk-text-soft);margin-bottom:8px;text-transform:uppercase;letter-spacing:.03em}
.tk-field-label svg{color:var(--tk-blue)}
.tk-field-value{font-size:15px;color:var(--tk-text);line-height:1.5;word-break:break-word;overflow-wrap:break-word}
.tk-field-value pre{white-space:pre-wrap;word-break:break-word;overflow-wrap:break-word;font-family:inherit;margin:0;font-size:15px;line-height:1.5}
.tk-field-value .method{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;background:var(--tk-panel-2);border:1px solid var(--tk-border);border-radius:10px;font-size:14px;font-weight:600;color:var(--tk-text)}

.tk-attachment{display:flex;align-items:center;gap:14px;padding:16px;background:var(--tk-panel-2);border:1px solid var(--tk-border);border-radius:14px}
.tk-attachment-icon{width:44px;height:44px;border-radius:10px;background:#dc2626;display:grid;place-items:center;color:#fff;flex-shrink:0}
.tk-attachment-info{flex:1;min-width:0}
.tk-attachment-name{font-size:14px;font-weight:600;color:var(--tk-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tk-attachment-meta{font-size:12px;color:var(--tk-text-soft);margin-top:2px}
.tk-attachment-actions{display:flex;gap:8px}
.tk-attachment-preview{max-width:120px;margin:14px auto 0;border-radius:10px;overflow:hidden;border:1px solid var(--tk-border);background:var(--tk-panel-2)}
.tk-attachment-preview a{display:block}
.tk-attachment-img{width:100%;aspect-ratio:1/1;object-fit:cover;display:block;background:var(--tk-panel-2)}
.tk-icon-btn{width:36px;height:36px;border-radius:10px;background:var(--tk-panel);border:1px solid var(--tk-border);color:var(--tk-text-soft);display:grid;place-items:center;cursor:pointer;transition:all 150ms;text-decoration:none}
.tk-icon-btn:hover{border-color:var(--tk-blue);color:var(--tk-blue)}

.tk-action-btn{position:relative;overflow:hidden;display:inline-flex;align-items:center;gap:14px;padding:14px 20px;border-radius:var(--tk-radius);border:1px solid var(--tk-border);background:var(--tk-panel-2);color:var(--tk-text-soft);cursor:pointer;transition:all 150ms;text-align:left;text-decoration:none}
.tk-action-btn::after{content:'';position:absolute;right:-30px;bottom:-30px;width:120px;height:120px;border-radius:50%;filter:blur(40px);opacity:.25;pointer-events:none;background:var(--tk-text-soft)}
.tk-back-wrap{display:flex;justify-content:flex-start;gap:12px;margin-bottom:12px}
.tk-back-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:12px;border:1px solid var(--tk-border);background:var(--tk-panel);color:var(--tk-text);font-size:13px;font-weight:600;text-decoration:none;transition:all 150ms}
.tk-back-btn:hover{border-color:var(--tk-blue);color:var(--tk-blue)}
.tk-back-btn svg{color:var(--tk-text-soft);flex-shrink:0}
.tk-action-btn:hover{transform:translateY(-2px)}
.tk-action-icon{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;background:rgba(148,163,184,.15);color:var(--tk-text-soft);flex-shrink:0}
.tk-action-text{flex:1}
.tk-action-text strong{display:block;font-size:14px;color:var(--tk-text);margin-bottom:3px}
.tk-action-text span{display:block;font-size:12px;color:var(--tk-text-soft)}

.tk-info-list{display:grid;gap:18px}
.tk-info-row{display:flex;align-items:center;justify-content:space-between;gap:16px}
.tk-info-row > div:first-child{display:flex;align-items:center;gap:10px;font-size:13px;color:var(--tk-text-soft)}
.tk-info-row > div:first-child svg{color:var(--tk-blue);width:16px;height:16px}
.tk-info-row > div:last-child{font-size:13px;font-weight:600;color:var(--tk-text);text-align:right;word-break:break-word;overflow-wrap:break-word;min-width:0}
.tk-info-row .tk-user-email{font-size:11px;color:var(--tk-blue);font-weight:500}
.tk-estado{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:99px;font-size:12px;font-weight:700}
.tk-estado::before{content:'';width:7px;height:7px;border-radius:50%;background:currentColor}
.tk-estado.pendiente{background:rgba(245,158,11,.15);color:#fbbf24}
.tk-estado.nuevo{background:rgba(168,85,247,.15);color:#c084fc}
.tk-estado.abierto{background:rgba(59,130,246,.15);color:#60a5fa}
.tk-estado.en_proceso{background:rgba(245,158,11,.15);color:#fbbf24}
.tk-estado.respondido{background:rgba(16,185,129,.15);color:#4ade80}
.tk-estado.cerrado{background:rgba(148,163,184,.15);color:#94a3b8}
.tk-estado.resuelto{background:rgba(34,197,94,.15);color:#4ade80}

.tk-resolved-banner{display:flex;align-items:center;gap:14px;padding:18px 24px;background:rgba(34,197,94,.08);border-bottom:1px solid rgba(34,197,94,.2)}
.tk-resolved-banner.warning{background:rgba(245,158,11,.08);border-bottom-color:rgba(245,158,11,.2)}
.tk-resolved-banner svg{flex-shrink:0}
.tk-resolved-banner.warning svg{color:var(--tk-amber)}
.tk-resolved-banner strong{font-size:16px;color:var(--tk-green)}
.tk-resolved-banner.warning strong{color:var(--tk-amber)}
.tk-resolution-detail{display:grid;gap:18px}
.tk-resolution-row{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;padding-bottom:18px;border-bottom:1px solid var(--tk-border-soft)}
.tk-resolution-row:last-child{border-bottom:none;padding-bottom:0}
.tk-resolution-label{font-size:12px;color:var(--tk-text-soft);text-transform:uppercase;letter-spacing:.03em;min-width:120px;flex-shrink:0}
.tk-resolution-value{font-size:14px;color:var(--tk-text);text-align:right;flex:1;word-break:break-word;overflow-wrap:break-word;min-width:0}
.tk-resolution-value pre{font-family:inherit;white-space:pre-wrap;word-break:break-word;margin:0}

/* ===== TEMA CLARO ===== */
html[data-theme="light"] .tk-page{--tk-bg:#f8fafc;--tk-panel:#ffffff;--tk-panel-2:#f1f5f9;--tk-border:#e2e8f0;--tk-border-soft:#e2e8f0;--tk-text:#0f172a;--tk-text-soft:#64748b;--tk-shadow:0 4px 16px rgba(15,23,42,.06)}
html[data-theme="light"] .tk-card-glow::before{background:linear-gradient(180deg,rgba(59,130,246,.3),transparent)}
html[data-theme="light"] .tk-card-icon{background:rgba(59,130,246,.1)}
html[data-theme="light"] .tk-card-icon.green{background:rgba(34,197,94,.1)}
html[data-theme="light"] .tk-card-icon.amber{background:rgba(245,158,11,.1)}
html[data-theme="light"] .tk-action-btn::after{opacity:.12}
html[data-theme="light"] .tk-action-icon{box-shadow:none}
html[data-theme="light"] .tk-estado.pendiente{background:rgba(217,119,6,.1);color:#b45309}
html[data-theme="light"] .tk-estado.nuevo{background:rgba(147,51,234,.1);color:#7c3aed}
html[data-theme="light"] .tk-estado.abierto{background:rgba(37,99,235,.1);color:#2563eb}
html[data-theme="light"] .tk-estado.en_proceso{background:rgba(217,119,6,.1);color:#b45309}
html[data-theme="light"] .tk-estado.respondido{background:rgba(5,150,105,.1);color:#047857}
html[data-theme="light"] .tk-estado.cerrado{background:rgba(100,116,139,.1);color:#475569}
html[data-theme="light"] .tk-estado.resuelto{background:rgba(5,150,105,.1);color:#047857}
html[data-theme="light"] .tk-attachment{background:#f8fafc;border-color:#e2e8f0}
html[data-theme="light"] .tk-icon-btn{background:#fff;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .tk-icon-btn:hover{border-color:#3b82f6;color:#3b82f6}
html[data-theme="light"] .tk-resolved-banner{background:rgba(34,197,94,.06);border-bottom-color:rgba(34,197,94,.15)}
html[data-theme="light"] .tk-back-btn{background:#fff;border-color:#e2e8f0;color:#0f172a}
html[data-theme="light"] .tk-back-btn:hover{border-color:#2563eb;color:#2563eb}

@media(max-width:900px){
  .tk-info-row{flex-direction:column;align-items:flex-start;gap:4px}
  .tk-info-row > div:last-child{text-align:left}
  .tk-resolution-row{flex-direction:column;gap:6px}
  .tk-resolution-value{text-align:left}
}
@media(max-width:640px){
  .tk-card-header{padding:18px}
  .tk-card-body{padding:18px}
  .tk-action-btn{gap:12px;padding:14px 16px}
  .tk-action-icon{width:38px;height:38px}
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php ($isResolved = in_array($ticket->status, ['respondido', 'resuelto', 'cerrado'])); ?>
<div class="tk-page">
  <div class="tk-back-wrap">
    <a href="<?php echo e(route('soporte.tickets')); ?>" class="tk-back-btn">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      Regresar
    </a>
  </div>

  <div class="tk-col">
    <?php if(!$isResolved): ?>
    
    <div class="tk-card tk-card-glow">
      <?php if($isResolved && $ticket->resolved_at): ?>
      <div class="tk-resolved-banner">
        <div style="display:flex;align-items:center;gap:14px;flex:1">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          <strong>Ticket resuelto</strong>
        </div>
      </div>
      <?php else: ?>
      <div class="tk-resolved-banner warning">
        <div style="display:flex;align-items:center;gap:14px;flex:1">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <strong>Ticket en espera de respuesta</strong>
        </div>
      </div>
      <?php endif; ?>

      <div class="tk-card-header">
        <div class="tk-card-icon <?php echo e($isResolved ? 'green' : 'amber'); ?>">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <h2 class="tk-card-title">Detalle del ticket</h2>
      </div>
      <div class="tk-card-body">
        <div class="tk-field">
          <div class="tk-field-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Asunto
          </div>
          <div class="tk-field-value"><?php echo e($ticket->subject); ?></div>
        </div>

        <div class="tk-field">
          <div class="tk-field-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/></svg>
            Descripción
          </div>
          <div class="tk-field-value"><pre><?php echo e($ticket->description); ?></pre></div>
        </div>

        <?php if($ticket->payment_method): ?>
        <div class="tk-field">
          <div class="tk-field-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            Método de pago
          </div>
          <div class="tk-field-value"><span class="method"><?php echo e(ucfirst($ticket->payment_method)); ?></span></div>
        </div>
        <?php endif; ?>

        <?php if($ticket->attachment_path): ?>
        <?php ($attachmentExt = strtolower(pathinfo($ticket->attachment_path, PATHINFO_EXTENSION))); ?>
        <?php ($isImage = in_array($attachmentExt, ['jpg','jpeg','png','webp','gif','bmp','svg'])); ?>
        <div class="tk-field">
          <div class="tk-field-label">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
            Archivos adjuntos (1)
          </div>
          <div class="tk-attachment">
            <div class="tk-attachment-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div class="tk-attachment-info">
              <div class="tk-attachment-name"><?php echo e(basename($ticket->attachment_path)); ?></div>
              <div class="tk-attachment-meta"><?php echo e($attachmentSize ?? 'Adjunto'); ?></div>
            </div>
            <div class="tk-attachment-actions">
              <a href="<?php echo e(asset('storage/'.$ticket->attachment_path)); ?>" download class="tk-icon-btn" title="Descargar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              </a>
              <a href="<?php echo e(asset('storage/'.$ticket->attachment_path)); ?>" target="_blank" class="tk-icon-btn" title="Ver">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
            </div>
          </div>
          <?php if($isImage): ?>
          <div class="tk-attachment-preview">
            <a href="<?php echo e(asset('storage/'.$ticket->attachment_path)); ?>" target="_blank" title="Ver imagen completa">
              <img class="tk-attachment-img" src="<?php echo e(asset('storage/'.$ticket->attachment_path)); ?>" alt="Adjunto del ticket">
            </a>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <a href="<?php echo e(route('soporte.tickets')); ?>" class="tk-action-btn" style="margin-top:6px">
          <div class="tk-action-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
          </div>
          <div class="tk-action-text">
            <strong>Regresar</strong>
            <span>Volver al listado de tickets</span>
          </div>
        </a>
      </div>
    </div>
    <?php endif; ?>

    <?php if($isResolved && $ticket->resolution_summary): ?>
    <div class="tk-card" id="respuesta">
      <div class="tk-card-header">
        <div class="tk-card-icon green">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <h2 class="tk-card-title">Respuesta del técnico</h2>
      </div>
      <div class="tk-card-body">
        <div class="tk-resolution-detail">
          <?php if($ticket->resolution_type): ?>
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Tipo de solución</div>
            <div class="tk-resolution-value"><?php echo e(str_replace('_', ' ', ucfirst($ticket->resolution_type))); ?></div>
          </div>
          <?php endif; ?>
          <?php if($ticket->resolution_summary): ?>
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Solución aplicada</div>
            <div class="tk-resolution-value"><pre><?php echo e($ticket->resolution_summary); ?></pre></div>
          </div>
          <?php endif; ?>
          <?php if($ticket->resolved_at): ?>
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Resuelto el</div>
            <div class="tk-resolution-value"><?php echo e($ticket->resolved_at->format('d/m/Y')); ?><br><span style="color:var(--tk-text-soft);font-size:12px"><?php echo e($ticket->resolved_at->format('h:i A')); ?></span></div>
          </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
    <?php endif; ?>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\soporte\ticket-show.blade.php ENDPATH**/ ?>