{{-- ============================================================
     AGENDA / _events.blade.php
     Datos de citas reales desde Laravel / MySQL
     ============================================================ --}}

<script>
(function(){
  function _displayName(name) {
    const parts = String(name || '').trim().split(/\s+/).filter(Boolean);
    return parts.slice(0, 2).join(' ') || 'Paciente';
  }

  function _initials(name) {
    return _displayName(name)
      .split(/\s+/)
      .map(w => w[0])
      .join('')
      .slice(0, 2)
      .toUpperCase();
  }

  const citasBackend = @json($citasAgenda ?? []);
  const EVENTS = {};

  citasBackend.forEach(cita => {
    if (!cita.fecha_key) return;

    EVENTS[cita.fecha_key] = EVENTS[cita.fecha_key] || [];

    const horaLabel = cita.hora_label || cita.hora || '00:00';
    const paciente = cita.paciente || 'Paciente sin nombre';
    const procedimiento = cita.procedimiento || 'Procedimiento';

    EVENTS[cita.fecha_key].push({
      id: cita.id,
      paciente_id: cita.paciente_id || null,
      t: `${horaLabel} ${paciente} · ${procedimiento}`,
      name: paciente,
      proc: procedimiento,
      cls: cita.cls || 'ev-soon',
      h: parseInt(cita.hora_h ?? String(horaLabel).substring(0, 2), 10),
      duracion: cita.duracion_minutos ?? 60,
      hora: cita.hora,
      estado: cita.estado,
      estado_texto: cita.estado_texto,
      sala: cita.sala || 'Sala 3',
      notas: cita.notas || '',
      delete_url: cita.delete_url,
      update_url: cita.update_url,
      estado_url: cita.estado_url,
      reprogramar_url: cita.reprogramar_url,
      inits: _initials(paciente)
    });
  });

  Object.keys(EVENTS).forEach(key => {
    EVENTS[key].sort((a, b) => {
      const ah = parseInt(a.h || 0, 10);
      const bh = parseInt(b.h || 0, 10);

      if (ah !== bh) return ah - bh;

      return String(a.hora || '').localeCompare(String(b.hora || ''));
    });
  });

  @if(isset($bloqueosData) && !empty($bloqueosData))
  const bloqueosBackend = @json($bloqueosData);
  bloqueosBackend.forEach(b => {
    const key = b.fecha;
    if (!EVENTS[key]) EVENTS[key] = [];
    EVENTS[key].push({
      t: `${b.h} ${b.label}`,
      name: b.label,
      cls: 'ev-block',
      h: b.h,
      blockId: b.id,
      hora: b.hora,
      hora_fin: b.hora_fin || null,
      duracion: b.duracion || 60
    });
  });
  Object.keys(EVENTS).forEach(key => {
    EVENTS[key].sort((a, b) => {
      const ah = parseInt(a.h || 0, 10);
      const bh = parseInt(b.h || 0, 10);
      if (ah !== bh) return ah - bh;
      return String(a.hora || '').localeCompare(String(b.hora || ''));
    });
  });
  @endif

  window.__AGENDA_EVENTS = EVENTS;
  window.__displayName = _displayName;

  window.__removeAgendaEventById = function(id) {
    if (!id || !window.__AGENDA_EVENTS) return;
    Object.keys(window.__AGENDA_EVENTS).forEach(key => {
      window.__AGENDA_EVENTS[key] = window.__AGENDA_EVENTS[key].filter(ev => String(ev.id) !== String(id));
      if (!window.__AGENDA_EVENTS[key].length) delete window.__AGENDA_EVENTS[key];
    });
  };

  window.__deleteCita = async function(deleteUrl, callbacks = {}) {
    if (!deleteUrl) {
      if (callbacks.onError) callbacks.onError('No hay URL para eliminar la cita.');
      return;
    }
    try {
      const response = await fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });
      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        if (response.status === 404) {
          if (callbacks.onSuccess) callbacks.onSuccess({ already_deleted: true, message: data.message || 'La cita ya no existía.' });
          return;
        }
        throw new Error(data.message || 'No se pudo eliminar la cita.');
      }
      // Si la respuesta incluye la cita actualizada, la guardamos en el evento para reflejar cancelación.
      if (data.cita && callbacks.onUpdated) {
        callbacks.onUpdated(data.cita);
      } else if (callbacks.onSuccess) {
        callbacks.onSuccess(data);
      }
    } catch (err) {
      if (callbacks.onError) callbacks.onError(err.message || 'Error de red');
      else console.error(err);
    }
  };
})();
</script>
