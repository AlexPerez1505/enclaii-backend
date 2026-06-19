{{-- ============================================================
     AGENDA / _events.blade.php
     Datos de ejemplo compartidos entre la agenda y el agendador
     ============================================================ --}}
<script>
(function(){
  const _today = new Date();
  function _k(offset) {
    const d = new Date(_today);
    d.setDate(_today.getDate() + offset);
    return `${d.getFullYear()}-${d.getMonth()+1}-${d.getDate()}`;
  }
  function _t(h, min, name, proc) {
    const hh = String(h).padStart(2,'0');
    const mm = String(min).padStart(2,'0');
    return { t: `${hh}:${mm} ${name} · ${proc}`, name, proc };
  }
  function _displayName(name) {
    const parts = String(name).trim().split(/\s+/);
    return parts.slice(0,2).join(' ');
  }

  const EVENTS = {
    [_k(-6)]: [{..._t(9,0,'Sofía Lozano','Colonoscopía'),      cls:'ev-done',  h:9}],
    [_k(-5)]: [{..._t(10,30,'Ricardo Martínez','Dudoescopía'),  cls:'ev-done',  h:10},
               {..._t(15,0,'Habib Pérez','Gastroscopía'),       cls:'ev-cancel',h:15}],
    [_k(-4)]: [{..._t(8,0,'Grabiela Torres','Broncoscopia'),    cls:'ev-done',  h:8},
               {..._t(11,0,'Perla Flores','Dudoescopía'),        cls:'ev-done',  h:11}],
    [_k(-3)]: [{..._t(9,30,'Dulce Martínez','Dudoescopía'),     cls:'ev-done',  h:9},
               {..._t(14,0,'Luis Arellano','Colonoscopía'),     cls:'ev-cancel',h:14}],
    [_k(-2)]: [{..._t(11,0,'Yessica Torres','Gastroscopía'),   cls:'ev-done',  h:11},
               {..._t(16,0,'Irvin Rocha','Dudoescopía'),         cls:'ev-done',  h:16}],
    [_k(-1)]: [{..._t(10,0,'Paula Gómez','Colonoscopía'),      cls:'ev-done',  h:10},
               {..._t(13,0,'Yukary Huerta','Broncoscopia'),      cls:'ev-done',  h:13}],
    [_k(0)]:  [{..._t(9,0,'Erik Esquivel','Dudoescopía'),       cls:'ev-wait',  h:9},
               {..._t(11,30,'Grabiela Torres','Colonoscopía'),  cls:'ev-wait',  h:11},
               {..._t(14,0,'Paulina Gómez','Gastroscopía'),     cls:'ev-soon',  h:14},
               {..._t(16,30,'Ricardo Martínez','Dudoescopía'),   cls:'ev-soon',  h:16}],
    [_k(1)]:  [{..._t(10,0,'Sofía Lozano','Broncoscopia'),      cls:'ev-soon',  h:10},
               {..._t(15,0,'Pelet Gómez','Dudoescopía'),         cls:'ev-soon',  h:15}],
    [_k(2)]:  [{..._t(9,0,'Habib Pérez','Gastroscopía'),       cls:'ev-soon',  h:9},
               {..._t(12,0,'Dulce Martínez','Colonoscopía'),    cls:'ev-soon',  h:12}],
    [_k(3)]:  [{..._t(11,0,'Irvin Rocha','Broncoscopia'),       cls:'ev-soon',  h:11}],
    [_k(5)]:  [{..._t(10,0,'Luis Arellano','Dudoescopía'),      cls:'ev-soon',  h:10}],
    [_k(7)]:  [{..._t(9,30,'Yessica Torres','Colonoscopía'),   cls:'ev-soon',  h:9}],
    [_k(-10)]:[{..._t(11,0,'Perla Flores','Dudoescopía'),       cls:'ev-done',  h:11}],
    [_k(-14)]:[{..._t(8,30,'Paula Gómez','Gastroscopía'),      cls:'ev-cancel',h:8}],
  };

  // Mezclar citas guardadas en localStorage
  try {
    const citas = JSON.parse(localStorage.getItem('agendaCitas') || '[]');
    citas.forEach(c => {
      if (!c.fecha || !c.hora) return;
      const [d, m, y] = c.fecha.split('/').map(Number);
      const key = `${y}-${m}-${d}`;
      const hMatch = c.hora.match(/(\d+):(\d+)\s*(AM|PM)?/i);
      let h = hMatch ? parseInt(hMatch[1], 10) : 10;
      const ampm = hMatch ? hMatch[3] : 'AM';
      if (ampm && ampm.toUpperCase() === 'PM' && h < 12) h += 12;
      if (ampm && ampm.toUpperCase() === 'AM' && h === 12) h = 0;
      EVENTS[key] = EVENTS[key] || [];
      const exists = EVENTS[key].some(ev => ev.name === c.paciente && ev.h === h);
      if (!exists) {
        EVENTS[key].push({
          t: `${c.hora} ${c.paciente} · ${c.procedimiento}`,
          name: c.paciente,
          proc: c.procedimiento,
          cls: 'ev-soon',
          h: h
        });
      }
    });
  } catch(e) {}

  window.__AGENDA_EVENTS = EVENTS;
  window.__displayName = _displayName;
})();
</script>
