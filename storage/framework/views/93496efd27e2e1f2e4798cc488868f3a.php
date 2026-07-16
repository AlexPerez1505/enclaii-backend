<?php $__env->startSection('active', 'soporte'); ?>
<?php $__env->startSection('title', 'Mis Tickets'); ?>
<?php $__env->startSection('header-title', 'Tickets'); ?>
<?php $__env->startSection('header-sub', '¿Cómo podemos ayudarte hoy?'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Tickets layout ── */
.tkt-grid{display:grid;grid-template-columns:1fr;gap:24px;align-items:start}
.tkt-main{display:flex;flex-direction:column;gap:24px}

/* Card base */
.tkt-card{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:24px}
.tkt-card h2{font-size:16px;font-weight:700;margin-bottom:4px}
.tkt-card .sub{font-size:13px;color:var(--txt-soft);margin-bottom:16px}

/* Tabla tickets activos */
.tkt-table-wrap{overflow-x:auto}
.tkt-table{width:100%;border-collapse:collapse;font-size:13px}
.tkt-table th{
  text-align:left;padding:10px 12px;font-size:12px;font-weight:600;
  color:var(--txt-soft);text-transform:uppercase;letter-spacing:.04em;
  border-bottom:1px solid var(--stroke);
}
.tkt-table td{padding:10px 12px;border-bottom:1px solid var(--stroke)}
.tkt-table tr:last-child td{border-bottom:0}
.tkt-table tr:hover td{background:rgba(110,160,255,.04)}
.tkt-id{color:var(--txt-soft);font-weight:600}
.tkt-badge{
  display:inline-block;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;
}
.tkt-badge.progreso{background:rgba(96,165,250,.15);color:#60a5fa}
.tkt-badge.abierto{background:rgba(168,130,255,.15);color:#a78bfa}
.tkt-badge.resuelto{background:rgba(74,222,128,.15);color:#4ade80}
.tkt-prioridad{display:flex;align-items:center;gap:6px}
.tkt-prioridad .dot{width:8px;height:8px;border-radius:50%}
.tkt-prioridad .dot.alta{background:#f87171}
.tkt-prioridad .dot.media{background:#fbbf24}
.tkt-prioridad .dot.baja{background:#60a5fa}
.tkt-arrow{color:var(--txt-soft);font-size:14px}
.tkt-ver-todos{font-size:13px;color:var(--blue);text-decoration:none;display:inline-block;margin-top:12px}
.tkt-ver-todos:hover{text-decoration:underline}

/* Tabs */
.tkt-tabs{
  display:flex;gap:0;border-bottom:1px solid var(--stroke);margin-bottom:20px;
}
.tkt-tab{
  display:flex;align-items:center;gap:8px;
  padding:12px 20px;font-size:14px;font-weight:600;
  border:0;background:transparent;color:var(--txt-soft);
  cursor:pointer;border-bottom:2px solid transparent;
  transition:color .15s,border-color .15s;
}
.tkt-tab:hover{color:var(--txt)}
.tkt-tab.active{color:var(--blue);border-bottom-color:var(--blue)}
.tkt-panel{margin-top:4px}

</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="tkt-grid">

  
  <div class="tkt-main">

    <div class="tkt-card">
      <div class="tkt-tabs">
        <button class="tkt-tab active" data-tab="activos" type="button">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          Tickets activos
        </button>
      </div>

      
      <div class="tkt-panel" id="panelActivos">
        <p class="sub">Tus tickets creados recientemente.</p>

        <div class="tkt-table-wrap">
          <table class="tkt-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Asunto</th>
                <th>Categoría</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Actualizado</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $categoryLabels = ['facturacion'=>'Facturación','tecnico'=>'Problema técnico','funcion'=>'Solicitud de función','como-hacer'=>'Cómo hacer','otro'=>'Otro'];
                $priorityLabels = ['alta'=>'Alta','media'=>'Media','baja'=>'Baja'];
                $statusLabels = ['nuevo'=>'Nuevo','abierto'=>'Abierto','en_proceso'=>'En proceso','en progreso'=>'En progreso','resuelto'=>'Resuelto','cerrado'=>'Cerrado'];
                $statusBadge = ['nuevo'=>'abierto','abierto'=>'abierto','en_proceso'=>'progreso','en progreso'=>'progreso','resuelto'=>'resuelto','cerrado'=>'resuelto'];
              ?>
              <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                  $catLabel = $categoryLabels[$ticket->category] ?? ucfirst(str_replace('-',' ',$ticket->category));
                  $prioLabel = $priorityLabels[$ticket->priority] ?? ucfirst($ticket->priority);
                  $statusLabel = $statusLabels[$ticket->status] ?? ucfirst($ticket->status);
                  $statusClass = $statusBadge[$ticket->status] ?? 'abierto';
                ?>
                <tr>
                  <td class="tkt-id">#<?php echo e($ticket->id); ?></td>
                  <td><?php echo e($ticket->subject); ?></td>
                  <td><?php echo e($catLabel); ?></td>
                  <td><span class="tkt-prioridad"><span class="dot <?php echo e($ticket->priority); ?>"></span> <?php echo e($prioLabel); ?></span></td>
                  <td><span class="tkt-badge <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span></td>
                  <td><?php echo e($ticket->updated_at->diffForHumans()); ?></td>
                  <td class="tkt-arrow">›</td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="7" style="text-align:center;color:var(--txt-soft);padding:24px">No tienes tickets creados aún.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <a class="tkt-ver-todos" href="<?php echo e(route('soporte')); ?>">Crear nuevo ticket →</a>
      </div>
    </div>

  </div>

</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views/soporte/tickets.blade.php ENDPATH**/ ?>