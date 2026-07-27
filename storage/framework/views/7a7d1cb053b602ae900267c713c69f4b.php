

<?php $__env->startSection('title', 'Historial de estudios'); ?>
<?php $__env->startSection('active', 'pacientes'); ?>
<?php $__env->startSection('header-title', 'Historial de estudios'); ?>
<?php $__env->startSection('header-sub'); ?>
  Historial completo de estudios de <?php echo e($paciente->nombre_completo); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.history-page {
  max-width: 1100px;
  margin: 0 auto;
  padding: 24px 0;
}

.history-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--txt-soft);
  font-size: 14px;
  font-weight: 500;
  text-decoration: none;
  margin-bottom: 20px;
  transition: color .2s var(--ease-out);
}
.history-back:hover { color: var(--blue); }

.history-patient-card {
  display: flex;
  align-items: center;
  gap: 16px;
  background: var(--card);
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 20px 24px;
  margin-bottom: 24px;
}
.history-patient-avatar {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  object-fit: cover;
  background: var(--input-bg);
  border: 2px solid var(--stroke-strong);
  flex-shrink: 0;
}
.history-patient-avatar.placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 700;
  color: var(--txt-soft);
}
.history-patient-info { flex: 1; min-width: 0; }
.history-patient-name {
  font-family: 'Sora', sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: var(--txt);
  margin: 0 0 4px;
}
.history-patient-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  font-size: 13px;
  color: var(--txt-soft);
}
.history-patient-meta span {
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.history-summary {
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}
.history-stat {
  background: var(--card);
  border: 1px solid var(--stroke);
  border-radius: var(--r-md);
  padding: 16px 20px;
  flex: 1;
  min-width: 140px;
}
.history-stat-value {
  font-family: 'Sora', sans-serif;
  font-size: 28px;
  font-weight: 800;
  color: var(--txt);
  line-height: 1;
}
.history-stat-label {
  font-size: 12px;
  color: var(--txt-soft);
  margin-top: 6px;
  text-transform: uppercase;
  letter-spacing: .04em;
}

.history-table-wrap {
  background: var(--card);
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  overflow: hidden;
}
.history-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}
.history-table thead th {
  text-align: left;
  padding: 14px 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .04em;
  color: var(--txt-soft);
  background: var(--panel-2);
  border-bottom: 1px solid var(--stroke);
  white-space: nowrap;
}
.history-table tbody td {
  padding: 14px 20px;
  color: var(--txt);
  border-bottom: 1px solid var(--stroke);
  vertical-align: middle;
}
.history-table tbody tr:last-child td { border-bottom: none; }
.history-table tbody tr {
  transition: background .15s var(--ease-out);
}
.history-table tbody tr:hover { background: var(--hover-bg); }

.history-type {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
}
.history-type-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: var(--hover-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  color: var(--blue);
}

.history-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}
.history-badge.completed { background: rgba(16,185,129,.15); color: var(--green); }
.history-badge.en_proceso { background: rgba(245,158,45,.15); color: var(--orange); }
.history-badge.cancelado { background: rgba(239,68,68,.15); color: var(--red); }
.history-badge.archivado { background: rgba(143,163,207,.15); color: var(--txt-soft); }

.history-empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--txt-soft);
}
.history-empty-state svg {
  margin-bottom: 16px;
  opacity: .4;
}
.history-empty-state p {
  font-size: 15px;
  margin: 0;
}

@media (max-width: 768px) {
  .history-table thead { display: none; }
  .history-table, .history-table tbody, .history-table tr, .history-table td {
    display: block;
    width: 100%;
  }
  .history-table tr {
    border-bottom: 1px solid var(--stroke);
    padding: 12px 0;
  }
  .history-table td {
    padding: 8px 20px;
    border: none;
  }
  .history-table td::before {
    content: attr(data-label);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--txt-soft);
    display: block;
    margin-bottom: 2px;
  }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="history-page">

  <a href="<?php echo e(route('pacientes.index')); ?>" class="history-back">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Volver a pacientes
  </a>

  <div class="history-patient-card">
    <?php if($paciente->foto): ?>
      <img src="<?php echo e(asset('storage/' . $paciente->foto)); ?>" alt="<?php echo e($paciente->nombre_completo); ?>" class="history-patient-avatar">
    <?php else: ?>
      <div class="history-patient-avatar placeholder">
        <?php echo e(strtoupper(substr($paciente->nombre_completo, 0, 1))); ?>

      </div>
    <?php endif; ?>
    <div class="history-patient-info">
      <h2 class="history-patient-name"><?php echo e($paciente->nombre_completo); ?></h2>
      <div class="history-patient-meta">
        <span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          Folio: <?php echo e($paciente->folio); ?>

        </span>
        <?php if($paciente->edad): ?>
        <span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/></svg>
          <?php echo e($paciente->edad); ?> años
        </span>
        <?php endif; ?>
        <?php if($paciente->procedimiento): ?>
        <span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"/></svg>
          <?php echo e($paciente->procedimiento); ?>

        </span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="history-summary">
    <div class="history-stat">
      <div class="history-stat-value"><?php echo e($paciente->estudios->count()); ?></div>
      <div class="history-stat-label">Total estudios</div>
    </div>
    <div class="history-stat">
      <div class="history-stat-value"><?php echo e($paciente->estudios->where('estado', 'completado')->count()); ?></div>
      <div class="history-stat-label">Completados</div>
    </div>
    <div class="history-stat">
      <div class="history-stat-value"><?php echo e($paciente->estudios->whereNotNull('reporte_path')->count()); ?></div>
      <div class="history-stat-label">Con reporte</div>
    </div>
  </div>

  <?php if($paciente->estudios->isEmpty()): ?>
    <div class="history-table-wrap">
      <div class="history-empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <p>Este paciente aún no tiene estudios registrados.</p>
      </div>
    </div>
  <?php else: ?>
    <div class="history-table-wrap">
      <table class="history-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Tipo de estudio</th>
            <th>Fecha</th>
            <th>Hora inicio</th>
            <th>Médico</th>
            <th>Sala</th>
            <th>Estado</th>
            <th>Reporte</th>
          </tr>
        </thead>
        <tbody>
          <?php $__currentLoopData = $paciente->estudios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $estudio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td data-label="#"><?php echo e($index + 1); ?></td>
              <td data-label="Tipo de estudio">
                <div class="history-type">
                  <div class="history-type-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/></svg>
                  </div>
                  <?php echo e($estudio->tipo ?? 'Estudio'); ?>

                </div>
              </td>
              <td data-label="Fecha"><?php echo e($estudio->fecha ? $estudio->fecha->format('d/m/Y') : '—'); ?></td>
              <td data-label="Hora inicio"><?php echo e($estudio->hora_inicio ? $estudio->hora_inicio->format('H:i') : '—'); ?></td>
              <td data-label="Médico"><?php echo e($estudio->medico ?? '—'); ?></td>
              <td data-label="Sala"><?php echo e($estudio->sala ?? '—'); ?></td>
              <td data-label="Estado">
                <span class="history-badge <?php echo e($estudio->estado); ?>">
                  <?php echo e($estudio->estado_texto); ?>

                </span>
              </td>
              <td data-label="Reporte">
                <?php if($estudio->reporte_path): ?>
                  <span class="history-badge completed">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Disponible
                  </span>
                <?php else: ?>
                  <span style="color:var(--txt-soft);font-size:13px;">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views/pacientes/table-history.blade.php ENDPATH**/ ?>