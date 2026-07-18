<?php $__env->startSection('title', 'Agendar Cita'); ?>
<?php $__env->startSection('active', 'agenda'); ?>
<?php $__env->startSection('header-title', 'Buenos días, '.(auth()->user()?->name ?? 'Doctor')); ?>
<?php $__env->startSection('header-sub'); ?>
  Tiene <b><?php echo e($citasHoy ?? 0); ?></b> pacientes el día de hoy
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <?php echo $__env->make('agenda.agendar._base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('agenda._events', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('agenda.agendar._paciente', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('agenda.agendar._cita', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('agenda.agendar._calendario', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('agenda.agendar._motivo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('agenda.agendar._confirmacion', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="ag-wrap" id="agendarWrap">
  <div class="ag-header">
    <button class="ag-back" id="agBack" onclick="window.history.back()">Regresar</button>
    <h1 class="ag-title" id="agendarTitle">Agendar Nueva CITA</h1>
    <div style="width:80px"></div>
  </div>
  <div class="ag-grid-main">
    <div id="colPaciente"></div>
    <div id="colCita"></div>
    <div id="colCalendario"></div>
  </div>
  <div id="colMotivo"></div>
  <div id="colConfirmacion"></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const CITA_EDITAR = <?php echo json_encode($citaEditar ?? null, 15, 512) ?>;
  const ES_REPROGRAMACION = !!(CITA_EDITAR && CITA_EDITAR.id);

  window.__CITA_EDITAR_ID = ES_REPROGRAMACION ? CITA_EDITAR.id : null;
  window.__CITA_EDITAR_FECHA = ES_REPROGRAMACION ? CITA_EDITAR.fecha : null;
  window.__CITA_EDITAR_FECHA_TEXTO = ES_REPROGRAMACION ? CITA_EDITAR.fecha_formato : '';
  window.__CITA_EDITAR_HORA_TEXTO = ES_REPROGRAMACION ? CITA_EDITAR.hora_formato : '';

  document.getElementById('colPaciente')?.appendChild(document.getElementById('stepPaciente'));
  document.getElementById('colCita')?.appendChild(document.getElementById('stepCita'));
  document.getElementById('colCalendario')?.appendChild(document.getElementById('stepCalendario'));
  document.getElementById('colMotivo')?.appendChild(document.getElementById('stepMotivo'));
  document.getElementById('colConfirmacion')?.appendChild(document.getElementById('stepConfirmacion')?.closest('.ag-grid-confirm'));

  const title = document.getElementById('agendarTitle') || document.querySelector('.ag-title');
  if (title && ES_REPROGRAMACION) title.textContent = 'Reprogramar CITA';

  const btnAgendar = document.getElementById('cfmAgendar');
  if (btnAgendar && ES_REPROGRAMACION) {
    btnAgendar.innerHTML = 'Guardar cambios';
  }

  window.__agOnDateSelect = function(date) {
    const d = String(date.getDate()).padStart(2,'0');
    const m = String(date.getMonth()+1).padStart(2,'0');
    const y = date.getFullYear();

    document.getElementById('citaFecha').value = `${d}/${m}/${y}`;
    document.getElementById('cfmFecha').textContent = `${d}/${m}/${y}`;
    window.__agSelectedDate = date;
  };

  window.__agOnSlotSelect = function(slot) {
    document.getElementById('citaHora').value = slot;
    document.getElementById('cfmHora').textContent = slot;
  };

  document.getElementById('pacSearch')?.addEventListener('input', function() {
    document.getElementById('cfmPaciente').textContent = this.value || 'Paciente';
  });

  document.getElementById('citaEspecialista')?.addEventListener('change', function() {
    document.getElementById('cfmEspecialista').textContent = this.value;
  });

  document.getElementById('citaProcedimiento')?.addEventListener('change', function() {
    document.getElementById('cfmProcedimiento').textContent = this.value;
  });

  document.getElementById('citaSala')?.addEventListener('change', function() {
    document.getElementById('cfmSala').textContent = this.value;
  });

  function setSelectValue(selectId, value) {
    const select = document.getElementById(selectId);
    if (!select || !value) return;

    const exists = Array.from(select.options).some(opt => opt.value === value || opt.textContent.trim() === value);

    if (!exists) {
      const opt = document.createElement('option');
      opt.value = value;
      opt.textContent = value;
      select.appendChild(opt);
    }

    select.value = value;
    select.dispatchEvent(new Event('change'));
  }

  function initConfirmation() {
    const selEsp = document.getElementById('citaEspecialista');
    if (selEsp) selEsp.dispatchEvent(new Event('change'));

    const selProc = document.getElementById('citaProcedimiento');
    if (selProc) selProc.dispatchEvent(new Event('change'));

    const selSala = document.getElementById('citaSala');
    if (selSala) selSala.dispatchEvent(new Event('change'));

    const citaFecha = document.getElementById('citaFecha');
    const cfmFecha = document.getElementById('cfmFecha');
    if (citaFecha && cfmFecha && citaFecha.value) cfmFecha.textContent = citaFecha.value;

    const citaHora = document.getElementById('citaHora');
    const cfmHora = document.getElementById('cfmHora');
    if (citaHora && cfmHora && citaHora.value) cfmHora.textContent = citaHora.value;

    const pacSearch = document.getElementById('pacSearch');
    const cfmPaciente = document.getElementById('cfmPaciente');
    if (pacSearch && cfmPaciente && pacSearch.value) cfmPaciente.textContent = pacSearch.value;
  }

  function cargarCitaEditar() {
    if (!ES_REPROGRAMACION) return;

    window.__selectedPacienteId = CITA_EDITAR.paciente_id || null;

    const pacInput = document.getElementById('pacSearch');
    if (pacInput) {
      pacInput.value = CITA_EDITAR.paciente_nombre || '';
      pacInput.dispatchEvent(new Event('input'));
    }

    if (CITA_EDITAR.paciente_nombre && window.__findPatient && window.__updatePacResult) {
      const pac = window.__findPatient(CITA_EDITAR.paciente_nombre);
      if (pac) window.__updatePacResult(pac);
    }

    const cfmPaciente = document.getElementById('cfmPaciente');
    if (cfmPaciente) cfmPaciente.textContent = CITA_EDITAR.paciente_nombre || 'Paciente';

    setSelectValue('citaProcedimiento', CITA_EDITAR.procedimiento || 'Procedimiento');
    setSelectValue('citaSala', CITA_EDITAR.sala || 'Sala 3');

    const fecha = document.getElementById('citaFecha');
    if (fecha) fecha.value = CITA_EDITAR.fecha_formato || '';

    const hora = document.getElementById('citaHora');
    if (hora) hora.value = CITA_EDITAR.hora_formato || '';

    const duracion = document.getElementById('citaDuracion');
    if (duracion) {
      duracion.value = CITA_EDITAR.duracion_minutos || 60;
      duracion.dispatchEvent(new Event('input'));
    }

    const motivo = document.getElementById('motivoText');
    if (motivo) {
      motivo.value = CITA_EDITAR.notas || '';
      motivo.dispatchEvent(new Event('input'));
    }

    const cfmFecha = document.getElementById('cfmFecha');
    if (cfmFecha) cfmFecha.textContent = CITA_EDITAR.fecha_formato || 'Fecha';

    const cfmHora = document.getElementById('cfmHora');
    if (cfmHora) cfmHora.textContent = CITA_EDITAR.hora_formato || 'Hora';

    initConfirmation();
  }

  initConfirmation();
  cargarCitaEditar();

  document.getElementById('cfmCancelar')?.addEventListener('click', () => {
    window.location.href = '<?php echo e(route('agenda')); ?>';
  });

  function fechaDdMmYyyyToIso(fecha) {
    const match = String(fecha || '').match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
    if (!match) return '';
    return `${match[3]}-${String(match[2]).padStart(2, '0')}-${String(match[1]).padStart(2, '0')}`;
  }

  function horaTextoTo24(hora) {
    const value = String(hora || '').trim();
    const match = value.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i);
    if (!match) return '';

    let h = parseInt(match[1], 10);
    const min = match[2];
    const ampm = match[3] ? match[3].toUpperCase() : '';

    if (ampm === 'PM' && h < 12) h += 12;
    if (ampm === 'AM' && h === 12) h = 0;

    return String(h).padStart(2, '0') + ':' + min;
  }

  function duracionToMinutos(texto) {
    const n = parseInt(String(texto || '').replace(/[^0-9]/g, ''), 10);
    return Number.isFinite(n) && n > 0 ? n : 60;
  }

  document.getElementById('cfmAgendar')?.addEventListener('click', async () => {
    const btn = document.getElementById('cfmAgendar');

    const pacienteId = window.__selectedPacienteId || null;
    const paciente = document.getElementById('cfmPaciente').textContent.trim() || document.getElementById('pacSearch')?.value?.trim() || 'Paciente';
    const procedimiento = document.getElementById('cfmProcedimiento').textContent.trim() || document.getElementById('citaProcedimiento')?.value || 'Procedimiento';
    const fechaTexto = document.getElementById('cfmFecha').textContent.trim() || document.getElementById('citaFecha')?.value || '';
    const horaTexto = document.getElementById('cfmHora').textContent.trim() || document.getElementById('citaHora')?.value || '';
    const sala = document.getElementById('cfmSala').textContent.trim() || document.getElementById('citaSala')?.value || 'Sala 3';
    const notas = document.getElementById('motivoText')?.value || '';
    const duracion = document.getElementById('citaDuracion')?.value || '60';

    const payload = {
      paciente_id: pacienteId,
      paciente_nombre: paciente,
      procedimiento: procedimiento,
      fecha: fechaDdMmYyyyToIso(fechaTexto),
      hora: horaTextoTo24(horaTexto),
      duracion_minutos: duracionToMinutos(duracion),
      estado: ES_REPROGRAMACION && CITA_EDITAR.estado ? CITA_EDITAR.estado : 'proximo',
      sala: sala,
      notas: notas
    };

    if (!payload.fecha) {
      alert('Selecciona una fecha válida.');
      return;
    }

    const selectedDate = window.__agSelectedDate;
    if (selectedDate) {
      const now = new Date();
      const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
      if (selectedDate < today) {
        alert('No puedes agendar citas en días que ya pasaron.');
        return;
      }
    }

    if (!payload.hora) {
      alert('Selecciona una hora válida.');
      return;
    }

    try {
      if (btn) {
        btn.disabled = true;
        btn.style.opacity = '.65';
      }

      const url = ES_REPROGRAMACION ? CITA_EDITAR.update_url : "<?php echo e(route('agenda.citas.store')); ?>";
      const method = ES_REPROGRAMACION ? 'PUT' : 'POST';

      const response = await fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
        },
        body: JSON.stringify(payload)
      });

      const data = await response.json().catch(() => ({}));

      if (!response.ok || !data.ok) {
        console.error(data);
        alert(data.message || 'No se pudo guardar la cita. Revisa los campos.');
        return;
      }

      const overlay = document.getElementById('successOverlay');
      const txt = document.getElementById('successText');

      if (overlay) overlay.classList.add('open');

      if (txt) {
        txt.textContent = ES_REPROGRAMACION
          ? `La cita de ${paciente} se reprogramó correctamente.`
          : `La cita de ${paciente} se guardó correctamente en la agenda. Se enviará una notificación al paciente.`;
      }
    } catch (error) {
      console.error(error);
      alert('Error al guardar la cita.');
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.style.opacity = '';
      }
    }
  });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\gmedi\enclaii-backend\resources\views/agenda/agendar/index.blade.php ENDPATH**/ ?>