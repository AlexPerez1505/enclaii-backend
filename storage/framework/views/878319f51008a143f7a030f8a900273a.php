<div class="fil-overlay" id="filOverlay" aria-hidden="true"></div>

<aside class="fil-panel" id="filPanel" aria-label="Panel de filtros" aria-hidden="true">
  <div class="fil-head">
    <h2>Filtros</h2>
    <button class="fil-close" id="filClose" type="button" aria-label="Cerrar filtros">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
  </div>

  <div class="fil-body">
    <div class="fil-group">
      <label class="fil-label" for="filPaciente">Paciente</label>
      <div class="fil-select-wrap">
        <select class="fil-control" id="filPaciente">
          <option value="">Todos los pacientes</option>
          <?php $__currentLoopData = $pacientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paciente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($paciente['id']); ?>"><?php echo e($paciente['nombre']); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <div class="fil-group">
      <label class="fil-label" for="filMedico">Médico</label>
      <div class="fil-select-wrap">
        <select class="fil-control" id="filMedico">
          <option value="">Todos los médicos</option>
          <?php $__currentLoopData = $medicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($medico); ?>"><?php echo e($medico); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <div class="fil-group">
      <label class="fil-label" for="filProcedimiento">Tipo de procedimiento</label>
      <div class="fil-select-wrap">
        <select class="fil-control" id="filProcedimiento">
          <option value="">Todos los procedimientos</option>
          <?php $__currentLoopData = $procedimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $procedimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($procedimiento); ?>"><?php echo e($procedimiento); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <div class="fil-section">
      <span class="fil-label">Fecha del estudio</span>
      <div class="fil-periods" role="group" aria-label="Periodo del estudio">
        <button type="button" data-period="today">Hoy</button>
        <button type="button" data-period="week">Esta semana</button>
        <button type="button" data-period="month">Este mes</button>
        <button type="button" data-period="custom">Rango personalizado</button>
      </div>
      <div class="fil-date-grid">
        <label>
          <span>Desde</span>
          <span class="fil-date-wrap">
            <input class="fil-control" type="date" id="filDesde">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </span>
        </label>
        <label>
          <span>Hasta</span>
          <span class="fil-date-wrap">
            <input class="fil-control" type="date" id="filHasta">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </span>
        </label>
      </div>
    </div>

    <div class="fil-section">
      <span class="fil-label">Tipo de archivo</span>
      <div class="fil-options">
        <label><input type="radio" name="filArchivo" value="" checked><span>Todos</span></label>
        <label><input type="radio" name="filArchivo" value="imagen"><span>Imágenes</span></label>
        <label><input type="radio" name="filArchivo" value="video"><span>Videos</span></label>
      </div>
    </div>

    <div class="fil-section">
      <span class="fil-label">Estado del estudio</span>
      <div class="fil-options fil-options-wrap">
        <label><input type="radio" name="filEstado" value="" checked><span>Todos</span></label>
        <label><input type="radio" name="filEstado" value="pendiente"><span>Pendiente</span></label>
        <label><input type="radio" name="filEstado" value="en_proceso"><span>En proceso</span></label>
        <label><input type="radio" name="filEstado" value="completado"><span>Finalizado</span></label>
        <label><input type="radio" name="filEstado" value="cancelado"><span>Cancelado</span></label>
      </div>
    </div>

    <div class="fil-section">
      <span class="fil-label">Hallazgos IA</span>
      <div class="fil-options fil-options-wrap">
        <label><input type="radio" name="filIa" value="" checked><span>Todos</span></label>
        <label><input type="radio" name="filIa" value="con"><span>Con hallazgos IA</span></label>
        <label><input type="radio" name="filIa" value="sin"><span>Sin análisis IA</span></label>
      </div>
    </div>

    <div class="fil-section fil-tags">
      <label class="fil-label" for="filHallazgo">Etiquetas / Hallazgos</label>
      <div class="fil-select-wrap">
        <select class="fil-control" id="filHallazgo">
          <option value="">Seleccionar etiqueta</option>
          <?php $__currentLoopData = $hallazgos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hallazgo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($hallazgo->id); ?>"><?php echo e($hallazgo->nombre); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>
  </div>

  <div class="fil-footer">
    <button class="fil-btn fil-btn-clear" id="filClear" type="button">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="1 4 1 10 7 10"/>
        <path d="M3.5 15a9 9 0 1 0 .5-8L1 10"/>
      </svg>
      Limpiar filtros
    </button>
    <button class="fil-btn fil-btn-apply" id="filApply" type="button">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      Aplicar filtros
    </button>
  </div>
</aside>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/galeria/filtros.blade.php ENDPATH**/ ?>