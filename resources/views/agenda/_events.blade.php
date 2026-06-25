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
      t: `${horaLabel} ${paciente} · ${procedimiento}`,
      name: paciente,
      proc: procedimiento,
      cls: cita.cls || 'ev-soon',
      h: parseInt(cita.hora_h ?? String(horaLabel).substring(0, 2), 10),
      duracion: cita.duracion_minutos ?? 60,
      hora: cita.hora,
      paciente_id: cita.paciente_id,
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

  window.__AGENDA_EVENTS = EVENTS;
  window.__displayName = _displayName;
})();
</script>
