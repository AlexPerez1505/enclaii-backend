<?php $__env->startSection('title', 'Ticket '.$ticket->operation_folio); ?>
<?php $__env->startSection('active', 'customer-success-tickets'); ?>
<?php $__env->startSection('header-title', 'Ticket '.$ticket->operation_folio); ?>
<?php $__env->startSection('header-sub', $ticket->subject); ?>

<?php $__env->startSection('sidebar'); ?>
  <?php echo $__env->make('customer-success.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('bottom-nav'); ?>
  <?php echo $__env->make('customer-success.partials.bottom-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.tk-page{--tk-bg:#060b14;--tk-panel:#0f1629;--tk-panel-2:#131b32;--tk-border:#1e293b;--tk-border-soft:#253047;--tk-text:#e2e8f0;--tk-text-soft:#94a3b8;--tk-blue:#3b82f6;--tk-blue-soft:#1d4ed8;--tk-cyan:#06b6d4;--tk-amber:#f59e0b;--tk-green:#22c55e;--tk-red:#ef4444;--tk-radius:18px;--tk-shadow:0 10px 30px rgba(0,0,0,.25)}
.tk-page{display:grid;gap:22px;grid-template-columns:1fr;max-width:1200px;margin:0 auto}
<<<<<<< HEAD
=======
.tk-col{min-width:0}
>>>>>>> origin/Carlos-Soporte
@media(min-width:900px){.tk-page{grid-template-columns:1.4fr .6fr}}
.tk-card{background:var(--tk-panel);border:1px solid var(--tk-border);border-radius:var(--tk-radius);box-shadow:var(--tk-shadow);overflow:hidden;position:relative;margin-bottom:22px}
.tk-card:last-child{margin-bottom:0}
.tk-card-glow::before{content:'';position:absolute;left:0;top:0;width:3px;height:100%;background:linear-gradient(180deg,var(--tk-blue),transparent);border-radius:var(--tk-radius) 0 0 var(--tk-radius)}
.tk-card-header{padding:22px 24px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--tk-border-soft)}
.tk-card-icon{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,rgba(59,130,246,.25),rgba(37,99,235,.12));display:grid;place-items:center;color:var(--tk-blue);flex-shrink:0}
.tk-card-icon.green{background:linear-gradient(135deg,rgba(34,197,94,.25),rgba(22,163,74,.12));color:var(--tk-green)}
.tk-card-title{font-size:16px;font-weight:700;color:var(--tk-text);margin:0}
.tk-card-body{padding:24px}
.tk-field{margin-bottom:22px}
.tk-field:last-child{margin-bottom:0}
.tk-field-label{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--tk-text-soft);margin-bottom:8px;text-transform:uppercase;letter-spacing:.03em}
.tk-field-label svg{color:var(--tk-blue)}
<<<<<<< HEAD
.tk-field-value{font-size:15px;color:var(--tk-text);line-height:1.5}
.tk-field-value pre{white-space:pre-wrap;word-break:break-word;font-family:inherit;margin:0;font-size:15px;line-height:1.5}
=======
.tk-field-value{font-size:15px;color:var(--tk-text);line-height:1.5;word-break:break-word;overflow-wrap:break-word}
.tk-field-value pre{white-space:pre-wrap;word-break:break-word;overflow-wrap:break-word;font-family:inherit;margin:0;font-size:15px;line-height:1.5}
>>>>>>> origin/Carlos-Soporte
.tk-field-value .method{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;background:var(--tk-panel-2);border:1px solid var(--tk-border);border-radius:10px;font-size:14px;font-weight:600;color:var(--tk-text)}

.tk-attachment{display:flex;align-items:center;gap:14px;padding:16px;background:var(--tk-panel-2);border:1px solid var(--tk-border);border-radius:14px}
.tk-attachment-icon{width:44px;height:44px;border-radius:10px;background:#dc2626;display:grid;place-items:center;color:#fff;flex-shrink:0}
.tk-attachment-info{flex:1;min-width:0}
.tk-attachment-name{font-size:14px;font-weight:600;color:var(--tk-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.tk-attachment-meta{font-size:12px;color:var(--tk-text-soft);margin-top:2px}
.tk-attachment-actions{display:flex;gap:8px}
.tk-icon-btn{width:36px;height:36px;border-radius:10px;background:var(--tk-panel);border:1px solid var(--tk-border);color:var(--tk-text-soft);display:grid;place-items:center;cursor:pointer;transition:all 150ms;text-decoration:none}
.tk-icon-btn:hover{border-color:var(--tk-blue);color:var(--tk-blue)}

.tk-actions-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:24px}
.tk-action-btn{position:relative;overflow:hidden;display:flex;align-items:center;gap:16px;padding:20px;border-radius:var(--tk-radius);border:1px solid var(--tk-border);background:var(--tk-panel-2);cursor:pointer;transition:all 150ms;text-align:left;text-decoration:none}
.tk-action-btn::after{content:'';position:absolute;right:-30px;bottom:-30px;width:120px;height:120px;border-radius:50%;filter:blur(40px);opacity:.25;pointer-events:none;background:var(--tk-green)}
.tk-action-btn.resolve::after{background:var(--tk-blue)}
.tk-action-btn.back-btn::after{background:var(--tk-text-soft)}
.tk-action-btn:hover{transform:translateY(-2px)}
.tk-action-icon{width:48px;height:48px;border-radius:50%;display:grid;place-items:center;background:rgba(34,197,94,.15);color:var(--tk-green);flex-shrink:0;box-shadow:0 0 20px rgba(34,197,94,.2)}
.tk-action-btn.resolve .tk-action-icon{background:rgba(59,130,246,.15);color:var(--tk-blue);box-shadow:0 0 20px rgba(59,130,246,.2)}
.tk-action-btn.back-btn .tk-action-icon{background:rgba(148,163,184,.15);color:var(--tk-text-soft);box-shadow:none}
.tk-action-text{flex:1}
.tk-action-text strong{display:block;font-size:15px;color:var(--tk-text);margin-bottom:4px}
.tk-action-text span{display:block;font-size:12px;color:var(--tk-text-soft)}
.tk-action-arrow{color:var(--tk-text-soft);flex-shrink:0}

.tk-info-list{display:grid;gap:18px}
.tk-info-row{display:flex;align-items:center;justify-content:space-between;gap:16px}
.tk-info-row > div:first-child{display:flex;align-items:center;gap:10px;font-size:13px;color:var(--tk-text-soft)}
.tk-info-row > div:first-child svg{color:var(--tk-blue);width:16px;height:16px}
<<<<<<< HEAD
.tk-info-row > div:last-child{font-size:13px;font-weight:600;color:var(--tk-text);text-align:right}
=======
.tk-info-row > div:last-child{font-size:13px;font-weight:600;color:var(--tk-text);text-align:right;word-break:break-word;overflow-wrap:break-word;min-width:0}
>>>>>>> origin/Carlos-Soporte
.tk-info-row .tk-user-email{font-size:11px;color:var(--tk-blue);font-weight:500}
.tk-estado{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:99px;font-size:12px;font-weight:700}
.tk-estado::before{content:'';width:7px;height:7px;border-radius:50%;background:currentColor}
.tk-estado.nuevo{background:rgba(168,85,247,.15);color:#c084fc}
.tk-estado.abierto{background:rgba(59,130,246,.15);color:#60a5fa}
.tk-estado.en_proceso{background:rgba(245,158,11,.15);color:#fbbf24}
.tk-estado.respondido{background:rgba(16,185,129,.15);color:#4ade80}
.tk-estado.cerrado{background:rgba(148,163,184,.15);color:#94a3b8}
.tk-estado.resuelto{background:rgba(34,197,94,.15);color:#4ade80}
.tk-select{width:100%;background:var(--tk-panel-2);border:1px solid var(--tk-border);border-radius:12px;padding:10px 12px;color:var(--tk-text);font-size:13px;outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:36px}
.tk-select:focus{border-color:var(--tk-blue)}
.tk-hint{font-size:12px;color:var(--tk-text-soft);margin-top:10px;min-height:18px}
.tk-hint.success{color:var(--tk-green)}
.tk-hint.error{color:var(--tk-red)}

.tk-resolved-banner{display:flex;align-items:center;gap:14px;padding:18px 24px;background:rgba(34,197,94,.08);border-bottom:1px solid rgba(34,197,94,.2)}
.tk-resolved-banner svg{color:var(--tk-green);flex-shrink:0}
.tk-resolved-banner strong{font-size:16px;color:var(--tk-green)}

.tk-resolution-detail{display:grid;gap:18px}
.tk-resolution-row{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;padding-bottom:18px;border-bottom:1px solid var(--tk-border-soft)}
.tk-resolution-row:last-child{border-bottom:none;padding-bottom:0}
.tk-resolution-label{font-size:12px;color:var(--tk-text-soft);text-transform:uppercase;letter-spacing:.03em;min-width:120px;flex-shrink:0}
<<<<<<< HEAD
.tk-resolution-value{font-size:14px;color:var(--tk-text);text-align:right;flex:1}
=======
.tk-resolution-value{font-size:14px;color:var(--tk-text);text-align:right;flex:1;word-break:break-word;overflow-wrap:break-word;min-width:0}
>>>>>>> origin/Carlos-Soporte
.tk-sent-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:rgba(34,197,94,.12);border-radius:8px;font-size:12px;font-weight:600;color:var(--tk-green)}
.tk-reopen-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 20px;border-radius:12px;border:1px solid var(--tk-border);background:var(--tk-panel-2);color:var(--tk-text-soft);font-size:13px;font-weight:600;cursor:pointer;transition:all 150ms;margin-top:16px}
.tk-reopen-btn:hover{border-color:var(--tk-amber);color:var(--tk-amber)}


<<<<<<< HEAD
=======
/* ===== TEMA CLARO ===== */
html[data-theme="light"] .tk-page{--tk-bg:#f8fafc;--tk-panel:#ffffff;--tk-panel-2:#f1f5f9;--tk-border:#e2e8f0;--tk-border-soft:#e2e8f0;--tk-text:#0f172a;--tk-text-soft:#64748b;--tk-shadow:0 4px 16px rgba(15,23,42,.06)}
html[data-theme="light"] .tk-card-glow::before{background:linear-gradient(180deg,rgba(59,130,246,.3),transparent)}
html[data-theme="light"] .tk-card-icon{background:rgba(59,130,246,.1)}
html[data-theme="light"] .tk-card-icon.green{background:rgba(34,197,94,.1)}
html[data-theme="light"] .tk-action-btn::after{opacity:.12}
html[data-theme="light"] .tk-action-icon{box-shadow:none}
html[data-theme="light"] .tk-action-btn.resolve .tk-action-icon{box-shadow:none}
html[data-theme="light"] .tk-estado.nuevo{background:rgba(147,51,234,.1);color:#7c3aed}
html[data-theme="light"] .tk-estado.abierto{background:rgba(37,99,235,.1);color:#2563eb}
html[data-theme="light"] .tk-estado.en_proceso{background:rgba(217,119,6,.1);color:#b45309}
html[data-theme="light"] .tk-estado.respondido{background:rgba(5,150,105,.1);color:#047857}
html[data-theme="light"] .tk-estado.cerrado{background:rgba(100,116,139,.1);color:#475569}
html[data-theme="light"] .tk-estado.resuelto{background:rgba(5,150,105,.1);color:#047857}
html[data-theme="light"] .tk-select{background-color:#f1f5f9;border-color:#e2e8f0;color:#0f172a}
html[data-theme="light"] .tk-select option{background:#fff;color:#0f172a}
html[data-theme="light"] .tk-resolved-banner{background:rgba(34,197,94,.06);border-bottom-color:rgba(34,197,94,.15)}
html[data-theme="light"] .tk-attachment{background:#f8fafc;border-color:#e2e8f0}
html[data-theme="light"] .tk-icon-btn{background:#fff;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .tk-icon-btn:hover{border-color:#3b82f6;color:#3b82f6}
html[data-theme="light"] .tk-reopen-btn{background:#f8fafc;border-color:#e2e8f0;color:#64748b}
html[data-theme="light"] .tk-reopen-btn:hover{border-color:#f59e0b;color:#b45309}

>>>>>>> origin/Carlos-Soporte
@media(max-width:900px){
  .tk-actions-grid{grid-template-columns:1fr}
  .tk-info-row{flex-direction:column;align-items:flex-start;gap:4px}
  .tk-info-row > div:last-child{text-align:left}
  .tk-resolution-row{flex-direction:column;gap:6px}
  .tk-resolution-value{text-align:left}
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="tk-page">

  <div class="tk-col">
    
    <div class="tk-card tk-card-glow">
      <?php if(in_array($ticket->status, ['resuelto', 'cerrado']) && $ticket->resolved_at): ?>
      <div class="tk-resolved-banner">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        <strong>Ticket resuelto</strong>
      </div>
      <?php endif; ?>

      <div class="tk-card-header">
        <div class="tk-card-icon">
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
        </div>
        <?php endif; ?>

        
        <?php if(!in_array($ticket->status, ['resuelto', 'cerrado']) || !$ticket->resolved_at): ?>
        <div class="tk-actions-grid">
          <a href="<?php echo e(route('customer-success.tickets')); ?>" class="tk-action-btn back-btn">
            <div class="tk-action-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </div>
            <div class="tk-action-text">
              <strong>Regresar</strong>
              <span>Volver al listado de tickets</span>
            </div>
            <div class="tk-action-arrow">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </div>
          </a>

          <a href="<?php echo e(route('customer-success.tickets.resolve.form', $ticket)); ?>" class="tk-action-btn resolve">
            <div class="tk-action-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <div class="tk-action-text">
              <strong>Resolver ticket</strong>
              <span>Marcar como resuelto</span>
            </div>
            <div class="tk-action-arrow">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </div>
          </a>
        </div>
        <?php endif; ?>
      </div>
    </div>

    
    <?php if(in_array($ticket->status, ['resuelto', 'cerrado']) && $ticket->resolved_at): ?>
    <div class="tk-card" id="resolvedCard">
      <div class="tk-card-header">
        <div class="tk-card-icon green">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
        <h2 class="tk-card-title">Resolución</h2>
      </div>
      <div class="tk-card-body">
        <div class="tk-resolution-detail">
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Estado</div>
            <div class="tk-resolution-value"><span class="tk-estado <?php echo e($ticket->status); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $ticket->status))); ?></span></div>
          </div>
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Resuelto por</div>
            <div class="tk-resolution-value"><?php echo e($ticket->resolver?->name); ?> <?php echo e($ticket->resolver?->apellido_paterno); ?></div>
          </div>
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Fecha</div>
            <div class="tk-resolution-value"><?php echo e($ticket->resolved_at->format('d M Y')); ?><br><span style="color:var(--tk-text-soft);font-size:12px"><?php echo e($ticket->resolved_at->format('h:i A')); ?></span></div>
          </div>
          <?php if($ticket->resolution_type): ?>
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Tipo de solución</div>
            <div class="tk-resolution-value"><?php echo e(str_replace('_', ' ', ucfirst($ticket->resolution_type))); ?></div>
          </div>
          <?php endif; ?>
          <?php if($ticket->resolution_summary): ?>
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Solución aplicada</div>
            <div class="tk-resolution-value"><?php echo e($ticket->resolution_summary); ?></div>
          </div>
          <?php endif; ?>
          <?php if($ticket->client_message): ?>
          <div class="tk-resolution-row">
            <div class="tk-resolution-label">Mensaje al cliente</div>
            <div class="tk-resolution-value">
              <span class="tk-sent-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                Enviado
              </span>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <button type="button" class="tk-reopen-btn" id="btnReopen">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
          Reabrir ticket
        </button>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <div class="tk-col">
    
    <div class="tk-card">
      <div class="tk-card-header">
        <div class="tk-card-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        </div>
        <h2 class="tk-card-title">Información</h2>
      </div>
      <div class="tk-card-body">
        <div class="tk-info-list">
          <div class="tk-info-row">
            <div>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              Folio
            </div>
            <div><?php echo e($ticket->operation_folio); ?></div>
          </div>
          <div class="tk-info-row">
            <div>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              Usuario
            </div>
            <div>
              <?php echo e($ticket->user?->name); ?> <?php echo e($ticket->user?->apellido_paterno); ?><br>
              <span class="tk-user-email"><?php echo e($ticket->user?->email); ?></span>
            </div>
          </div>
          <div class="tk-info-row">
            <div>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              Categoría
            </div>
            <div><?php echo e(ucfirst($ticket->category)); ?></div>
          </div>
          <div class="tk-info-row">
            <div>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              Negocio
            </div>
            <div><?php echo e($ticket->business_name ?? '—'); ?></div>
          </div>
          <div class="tk-info-row">
            <div>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              Estado
            </div>
            <div><span id="ticketStatusBadge" class="tk-estado <?php echo e($ticket->status); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $ticket->status))); ?></span></div>
          </div>
          <div class="tk-info-row">
            <div>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Creado
            </div>
            <div><?php echo e($ticket->created_at?->format('d/m/Y H:i')); ?></div>
          </div>
        </div>
      </div>
    </div>

    
    <?php if(!in_array($ticket->status, ['resuelto', 'cerrado']) || !$ticket->resolved_at): ?>
    <div class="tk-card">
      <div class="tk-card-header">
        <div class="tk-card-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        </div>
        <h2 class="tk-card-title">Gestionar ticket</h2>
      </div>
      <div class="tk-card-body">
        <div class="tk-field">
          <div class="tk-field-label">Cambiar estado</div>
          <select id="statusSelect" class="tk-select" data-ticket-id="<?php echo e($ticket->id); ?>">
            <option value="abierto" <?php echo e($ticket->status === 'abierto' ? 'selected' : ''); ?>>Abierto</option>
            <option value="en_proceso" <?php echo e($ticket->status === 'en_proceso' ? 'selected' : ''); ?>>En proceso</option>
            <option value="respondido" <?php echo e($ticket->status === 'respondido' ? 'selected' : ''); ?>>Respondido</option>
          </select>
        </div>
        <div class="tk-field">
          <div class="tk-field-label">Cambiar prioridad</div>
          <select id="prioritySelect" class="tk-select" data-ticket-id="<?php echo e($ticket->id); ?>">
            <option value="baja" <?php echo e($ticket->priority === 'baja' ? 'selected' : ''); ?>>Baja</option>
            <option value="media" <?php echo e($ticket->priority === 'media' ? 'selected' : ''); ?>>Media</option>
            <option value="alta" <?php echo e($ticket->priority === 'alta' ? 'selected' : ''); ?>>Alta</option>
            <option value="urgente" <?php echo e($ticket->priority === 'urgente' ? 'selected' : ''); ?>>Urgente</option>
          </select>
        </div>
        <div id="ticketUpdateMessage" class="tk-hint"></div>
      </div>
    </div>
    <?php endif; ?>
  </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  var csrfToken = "<?php echo e(csrf_token()); ?>";
  var updateUrl = "<?php echo e(route('customer-success.tickets.update', $ticket)); ?>";
  var reopenUrl = "<?php echo e(route('customer-success.tickets.reopen', $ticket)); ?>";

  var statusSelect = document.getElementById('statusSelect');
  var prioritySelect = document.getElementById('prioritySelect');
  var statusBadge = document.getElementById('ticketStatusBadge');
  var messageEl = document.getElementById('ticketUpdateMessage');

  function humanStatus(v){ return v.replace('_', ' ').replace(/\b\w/g, function(l){ return l.toUpperCase(); }); }

  function updateTicket(field, value){
    fetch(updateUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: JSON.stringify({ [field]: value })
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
      if(data.ok){
        if(messageEl){ messageEl.textContent = 'Guardado.'; messageEl.className = 'tk-hint success'; }
        setTimeout(function(){ if(messageEl){ messageEl.textContent = ''; messageEl.className = 'tk-hint'; } }, 2500);
        if(field === 'status' && statusBadge){
          statusBadge.textContent = humanStatus(value);
          statusBadge.className = 'tk-estado ' + value;
        }
      } else {
        if(messageEl){ messageEl.textContent = 'Error al guardar.'; messageEl.className = 'tk-hint error'; }
      }
    })
    .catch(function(){ if(messageEl){ messageEl.textContent = 'Error de conexión.'; messageEl.className = 'tk-hint error'; } });
  }

  if(statusSelect) statusSelect.addEventListener('change', function(){ updateTicket('status', this.value); });
  if(prioritySelect) prioritySelect.addEventListener('change', function(){ updateTicket('priority', this.value); });

  // ---- Reopen ----
  var btnReopen = document.getElementById('btnReopen');
  if(btnReopen){
    btnReopen.addEventListener('click', function(){
      if(!confirm('¿Reabrir este ticket?')) return;
      btnReopen.disabled = true;
      btnReopen.textContent = 'Reabriendo...';
      fetch(reopenUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({})
      })
      .then(function(r){ return r.json(); })
      .then(function(data){
        if(data.ok) window.location.reload();
        else { alert('Error al reabrir.'); btnReopen.disabled = false; btnReopen.textContent = 'Reabrir ticket'; }
      })
      .catch(function(){ alert('Error de conexión.'); btnReopen.disabled = false; btnReopen.textContent = 'Reabrir ticket'; });
    });
  }
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views-Tec/customer-success/tickets/show.blade.php ENDPATH**/ ?>