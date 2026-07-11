{{-- ============================================================
     AGENDA / _bloqueos.blade.php
     Modal bloqueo de tiempo (crear / eliminar): CSS + HTML + JS
     ============================================================ --}}

{{-- ---- CSS ---- --}}
<style>
.block-overlay{display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.55);backdrop-filter:blur(3px);align-items:center;justify-content:center}
.block-overlay.open{display:flex}
.block-modal{background:#001A2E;border:1.5px solid rgba(22,139,217,.4);border-radius:14px;padding:24px;width:340px;box-shadow:0 12px 48px rgba(0,0,0,.6);position:relative}
.block-modal h3{font-family:'Sora',sans-serif;font-size:15px;font-weight:700;color:#EAF1FF;margin:0 0 4px}
.block-modal p{font-size:12px;color:rgba(234,241,255,.5);margin:0 0 20px}
.block-modal label{display:block;font-size:11.5px;font-weight:600;color:rgba(234,241,255,.7);margin-bottom:5px}
.block-modal input[type=text]{width:100%;box-sizing:border-box;background:#001525;border:1px solid rgba(22,139,217,.3);border-radius:8px;padding:9px 12px;font-size:13px;color:#EAF1FF;outline:none;margin-bottom:14px}
.block-modal input[type=text]:focus{border-color:rgba(22,139,217,.7)}
.block-recur{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px}
.block-recur-btn{flex:1;min-width:60px;padding:7px 4px;border-radius:8px;font-size:11.5px;font-weight:600;background:#001525;border:1px solid rgba(22,139,217,.25);color:rgba(234,241,255,.65);cursor:pointer;transition:all 140ms ease;text-align:center}
.block-recur-btn:hover{background:#002540;border-color:rgba(22,139,217,.5);color:#EAF1FF}
.block-recur-btn.selected{background:linear-gradient(135deg,#1668D9,#2E7BF6);border-color:transparent;color:#fff}
.block-recur-extra{margin-bottom:16px;animation:fadeInDown 150ms ease}
@keyframes fadeInDown{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
.block-dow-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:4px;margin-top:8px}
.block-dow-btn{padding:5px 2px;border-radius:7px;font-size:11px;font-weight:700;background:#001525;border:1px solid rgba(22,139,217,.2);color:rgba(234,241,255,.55);cursor:pointer;transition:all 120ms ease;text-align:center}
.block-dow-btn:hover{background:#002540;color:#EAF1FF}
.block-dow-btn.sel{background:linear-gradient(135deg,#1668D9,#2E7BF6);border-color:transparent;color:#fff}
.block-recur-range{display:flex;align-items:center;gap:8px;margin-top:8px;font-size:12px;color:rgba(234,241,255,.65)}
.block-recur-range input[type=number]{width:60px;background:#001525;border:1px solid rgba(22,139,217,.3);border-radius:7px;padding:5px 8px;font-size:12px;color:#EAF1FF;outline:none;text-align:center}
.block-recur-range input[type=number]:focus{border-color:rgba(22,139,217,.7)}
html[data-theme="light"] .block-dow-btn{background:#EEF4FF;border-color:rgba(20,50,120,.2);color:#3A5CA8}
html[data-theme="light"] .block-dow-btn:hover{background:#D8E8FF}
html[data-theme="light"] .block-recur-range{color:#5B6A99}
html[data-theme="light"] .block-recur-range input[type=number]{background:#fff;border-color:rgba(20,50,120,.25);color:#0E1530}
.block-modal-footer{display:flex;gap:8px;justify-content:flex-end}
.block-close{position:absolute;top:14px;right:14px;width:26px;height:26px;background:transparent;border:none;cursor:pointer;color:rgba(234,241,255,.4);font-size:18px;line-height:1;display:flex;align-items:center;justify-content:center;border-radius:6px;transition:color 140ms ease}
.block-close:hover{color:#EAF1FF}
.block-confirm{display:none;position:fixed;inset:0;z-index:1001;background:rgba(0,0,0,.55);backdrop-filter:blur(3px);align-items:center;justify-content:center}
.block-confirm.open{display:flex}
.block-confirm-box{background:#001A2E;border:1.5px solid rgba(217,0,0,.4);border-radius:14px;padding:24px;width:300px;box-shadow:0 12px 48px rgba(0,0,0,.6);text-align:center}
.block-confirm-box h3{font-family:'Sora',sans-serif;font-size:14px;font-weight:700;color:#EAF1FF;margin:0 0 6px}
.block-confirm-box p{font-size:12px;color:rgba(234,241,255,.5);margin:0 0 20px}
.block-confirm-footer{display:flex;gap:8px;justify-content:center}

/* Tema claro */
html[data-theme="light"] .block-modal{background:#F0F5FF;border-color:rgba(20,50,120,.3);box-shadow:0 12px 40px rgba(20,50,120,.18)}
html[data-theme="light"] .block-modal h3{color:#0E1530}
html[data-theme="light"] .block-modal p{color:rgba(14,21,48,.5)}
html[data-theme="light"] .block-modal label{color:rgba(14,21,48,.65)}
html[data-theme="light"] .block-modal input[type=text]{background:#fff;border-color:rgba(20,50,120,.25);color:#0E1530}
html[data-theme="light"] .block-recur-btn{background:#EEF4FF;border-color:rgba(20,50,120,.2);color:#3A5CA8}
html[data-theme="light"] .block-recur-btn:hover{background:#D8E8FF}
html[data-theme="light"] .block-modal input[type=number]{background:#fff;border-color:rgba(20,50,120,.25);color:#0E1530}
html[data-theme="light"] .block-confirm-box{background:#F0F5FF;border-color:rgba(217,0,0,.3)}
html[data-theme="light"] .block-confirm-box h3{color:#0E1530}
html[data-theme="light"] .block-confirm-box p{color:rgba(14,21,48,.5)}

/* ============================================================
   FIX: confirmación eliminar bloqueo en tema claro
   ============================================================ */
html[data-theme="light"] .block-confirm{
  background:rgba(15,23,42,.28) !important;
  backdrop-filter:blur(5px) !important;
}

html[data-theme="light"] .block-confirm-box{
  background:#FFFFFF !important;
  border:1.5px solid rgba(220,38,38,.28) !important;
  box-shadow:0 22px 55px rgba(15,23,42,.18) !important;
}

html[data-theme="light"] .block-confirm-box h3{
  color:#0E1530 !important;
}

html[data-theme="light"] .block-confirm-box p{
  color:#5B6A99 !important;
}

html[data-theme="light"] .block-confirm-footer .ev-pop-btn.secondary,
html[data-theme="light"] #blockConfirmNo{
  background:#F4F7FF !important;
  border-color:rgba(20,50,120,.18) !important;
  color:#33456F !important;
}

html[data-theme="light"] #blockConfirmYes{
  background:linear-gradient(135deg,#B91C1C,#DC2626) !important;
  color:#FFFFFF !important;
  box-shadow:0 8px 20px rgba(220,38,38,.22) !important;
}

</style>

{{-- ---- HTML ---- --}}
<div class="block-overlay" id="blockOverlay">
  <div class="block-modal">
    <button class="block-close" id="blockClose">&#x2715;</button>
    <h3>Bloqueo de Tiempo</h3>
    <p id="blockSubtitle">Hora seleccionada</p>
    <div id="blockManualFields" style="display:none">
      <label>Fecha</label>
      <input type="date" id="blockDateInput" style="width:100%;box-sizing:border-box;background:#001525;border:1px solid rgba(22,139,217,.3);border-radius:8px;padding:9px 12px;font-size:13px;color:#EAF1FF;outline:none;margin-bottom:14px;color-scheme:dark">
      <div style="display:flex;gap:10px;margin-bottom:14px">
        <div style="flex:1">
          <label style="display:block;margin-bottom:5px">Hora de inicio</label>
          <input type="time" id="blockTimeInput" style="width:100%;box-sizing:border-box;background:#001525;border:1px solid rgba(22,139,217,.3);border-radius:8px;padding:9px 12px;font-size:13px;color:#EAF1FF;outline:none;color-scheme:dark">
        </div>
        <div style="flex:1">
          <label style="display:block;margin-bottom:5px">Hora de fin</label>
          <input type="time" id="blockTimeFinInput" style="width:100%;box-sizing:border-box;background:#001525;border:1px solid rgba(22,139,217,.3);border-radius:8px;padding:9px 12px;font-size:13px;color:#EAF1FF;outline:none;color-scheme:dark">
        </div>
      </div>
    </div>
    <div id="blockFixedTimeRange" style="display:none;margin-bottom:12px">
      <div style="display:flex;gap:10px">
        <div style="flex:1">
          <label style="display:block;margin-bottom:5px">Hora de fin</label>
          <input type="time" id="blockTimeFinFixed" style="width:100%;box-sizing:border-box;background:#001525;border:1px solid rgba(22,139,217,.3);border-radius:8px;padding:9px 12px;font-size:13px;color:#EAF1FF;outline:none;color-scheme:dark">
        </div>
      </div>
    </div>
    <label>Motivo / Etiqueta</label>
    <input type="text" id="blockLabel" placeholder="Ej: Almuerzo, Reunión...">
    <label>Repetir</label>
    <div class="block-recur">
      <button class="block-recur-btn selected" data-recur="none">Sin repetición</button>
      <button class="block-recur-btn" data-recur="daily">Todos los días</button>
      <button class="block-recur-btn" data-recur="custom">Días específicos</button>
    </div>
    <div class="block-recur-extra" id="recurExtraDaily" style="display:none">
      <div class="block-recur-range">
        <span>Repetir durante</span>
        <input type="number" id="recurDailyCount" value="7" min="1" max="365">
        <span>días</span>
      </div>
    </div>
    <div class="block-recur-extra" id="recurExtraCustom" style="display:none">
      <div class="block-dow-grid">
        <button class="block-dow-btn" data-dow="1">Lun</button>
        <button class="block-dow-btn" data-dow="2">Mar</button>
        <button class="block-dow-btn" data-dow="3">Mié</button>
        <button class="block-dow-btn" data-dow="4">Jue</button>
        <button class="block-dow-btn" data-dow="5">Vie</button>
        <button class="block-dow-btn" data-dow="6">Sáb</button>
        <button class="block-dow-btn" data-dow="0">Dom</button>
      </div>
      <div class="block-recur-range" style="margin-top:10px">
        <span>Durante</span>
        <input type="number" id="recurCustomWeeks" value="4" min="1" max="52">
        <span>semanas</span>
      </div>
    </div>
    <div class="block-modal-footer">
      <button class="ev-pop-btn secondary" id="blockCancel">Cancelar</button>
      <button class="ev-pop-btn primary" id="blockSave">Guardar bloqueo</button>
    </div>
  </div>
</div>

<div class="block-confirm" id="blockConfirm">
  <div class="block-confirm-box">
    <h3>¿Eliminar bloqueo?</h3>
    <p id="blockConfirmMsg">Se eliminará este bloqueo de tiempo.</p>
    <div class="block-confirm-footer">
      <button class="ev-pop-btn secondary" id="blockConfirmNo">Cancelar</button>
      <button class="ev-pop-btn primary" style="background:linear-gradient(135deg,#8B0000,#D90000)" id="blockConfirmYes">Eliminar</button>
    </div>
  </div>
</div>

{{-- ---- JS ---- --}}
<script>
(function(){
  window.__initBloqueos = function(EVENTS, MESES, DIAS_ES, buildDayFn) {
    const BLOCKS = {};
    let blockIdCounter = 1;
    let pendingBlockHour = null, pendingBlockDate = null, selectedRecur = 'none', isManualMode = false;
    let selectedDows = new Set(); // días de semana seleccionados para recur=custom
    let pendingDeleteId = null, pendingDeleteKey = null;
    let lastBuiltDate = null;

    const blockOverlay    = document.getElementById('blockOverlay');
    const blockClose      = document.getElementById('blockClose');
    const blockCancel     = document.getElementById('blockCancel');
    const blockSave       = document.getElementById('blockSave');
    const blockLabelEl    = document.getElementById('blockLabel');
    const blockSubtitle   = document.getElementById('blockSubtitle');
    const blockConfirm    = document.getElementById('blockConfirm');
    const blockConfirmNo  = document.getElementById('blockConfirmNo');
    const blockConfirmYes = document.getElementById('blockConfirmYes');

    function showRecurExtra(recur) {
      document.getElementById('recurExtraDaily').style.display  = recur === 'daily'   ? 'block' : 'none';
      document.getElementById('recurExtraCustom').style.display = recur === 'custom'  ? 'block' : 'none';
    }

    document.querySelectorAll('.block-recur-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.block-recur-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedRecur = btn.dataset.recur;
        showRecurExtra(selectedRecur);
        if (selectedRecur !== 'custom') {
          selectedDows = new Set();
        }
      });
    });

    document.querySelectorAll('.block-dow-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const dow = parseInt(btn.dataset.dow);
        if (selectedDows.has(dow)) { selectedDows.delete(dow); btn.classList.remove('sel'); }
        else { selectedDows.add(dow); btn.classList.add('sel'); }
      });
    });

    function resetRecur() {
      selectedRecur = 'none';
      selectedDows = new Set();
      document.querySelectorAll('.block-recur-btn').forEach(b => b.classList.toggle('selected', b.dataset.recur === 'none'));
      document.querySelectorAll('.block-dow-btn').forEach(b => b.classList.remove('sel'));
      showRecurExtra('none');
    }

    function buildFechasList(startDateStr) {
      const start = new Date(startDateStr + 'T00:00:00');
      const fechas = [];
      const pad = n => String(n).padStart(2,'0');
      const fmt = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;

      if (selectedRecur === 'none') {
        fechas.push(startDateStr);
      } else if (selectedRecur === 'daily') {
        const count = Math.max(1, Math.min(365, parseInt(document.getElementById('recurDailyCount').value) || 7));
        for (let i = 0; i < count; i++) {
          const d = new Date(start); d.setDate(d.getDate() + i);
          fechas.push(fmt(d));
        }
      } else if (selectedRecur === 'custom') {
        if (selectedDows.size === 0) { fechas.push(startDateStr); }
        else {
          const weeks = Math.max(1, Math.min(52, parseInt(document.getElementById('recurCustomWeeks').value) || 4));
          for (let i = 0; i < weeks * 7; i++) {
            const d = new Date(start); d.setDate(d.getDate() + i);
            if (selectedDows.has(d.getDay())) fechas.push(fmt(d));
          }
        }
      }
      return fechas;
    }

    function dateKey(y, m, d) { return `${y}-${m+1}-${d}`; }

    function addBlockToEvents(key, blockObj) {
      if (!EVENTS[key]) EVENTS[key] = [];
      EVENTS[key] = EVENTS[key].filter(ev => !(ev.cls === 'ev-block' && ev.h === blockObj.h));
      EVENTS[key].push({ t: blockObj.label, cls: 'ev-block', h: blockObj.h, blockId: blockObj.id });
      if (!BLOCKS[key]) BLOCKS[key] = [];
      BLOCKS[key].push(blockObj);
    }

    function openBlockModal(hr, y, m, d) {
      isManualMode = false;
      pendingBlockHour = hr;
      pendingBlockDate = {y, m, d};
      resetRecur();
      blockLabelEl.value = '';
      blockSubtitle.textContent = `De ${hr}:00 a ${hr + 1}:00`;
      document.getElementById('blockManualFields').style.display = 'none';
      const fixedRange = document.getElementById('blockFixedTimeRange');
      fixedRange.style.display = 'block';
      const pad = n => String(n).padStart(2,'0');
      document.getElementById('blockTimeFinFixed').value = `${pad(hr + 1)}:00`;
      blockOverlay.classList.add('open');
      setTimeout(() => blockLabelEl.focus(), 80);
    }

    function openBlockModalManual() {
      isManualMode = true;
      resetRecur();
      blockLabelEl.value = '';
      blockSubtitle.textContent = 'Selecciona la fecha y hora del bloqueo';
      document.getElementById('blockManualFields').style.display = 'block';
      document.getElementById('blockFixedTimeRange').style.display = 'none';
      const today = new Date();
      const pad = n => String(n).padStart(2, '0');
      document.getElementById('blockDateInput').value =
        `${today.getFullYear()}-${pad(today.getMonth()+1)}-${pad(today.getDate())}`;
      document.getElementById('blockTimeInput').value = `${pad(today.getHours())}:00`;
      document.getElementById('blockTimeFinInput').value = `${pad(today.getHours() + 1)}:00`;
      blockOverlay.classList.add('open');
      setTimeout(() => blockLabelEl.focus(), 80);
    }

    window.__openBlockModalManual = openBlockModalManual;

    function closeBlockModal() { blockOverlay.classList.remove('open'); }

    blockClose.addEventListener('click', closeBlockModal);
    blockCancel.addEventListener('click', closeBlockModal);
    blockOverlay.addEventListener('click', e => { if (e.target === blockOverlay) closeBlockModal(); });

    blockSave.addEventListener('click', async () => {
      const label = blockLabelEl.value.trim() || 'Bloqueo de Tiempo';
      let fechaStr, horaStr, y, m, d, hr;
      let horaFinStr = null;
      if (isManualMode) {
        fechaStr   = document.getElementById('blockDateInput').value;
        horaStr    = document.getElementById('blockTimeInput').value;
        horaFinStr = document.getElementById('blockTimeFinInput').value || null;
        if (!fechaStr || !horaStr) {
          alert('Por favor selecciona fecha y hora.');
          return;
        }
        const parts = fechaStr.split('-');
        y = parseInt(parts[0]); m = parseInt(parts[1]) - 1; d = parseInt(parts[2]);
        hr = parseInt(horaStr.split(':')[0]);
      } else {
        ({ y, m, d } = pendingBlockDate);
        hr = pendingBlockHour;
        fechaStr   = `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        horaStr    = `${String(hr).padStart(2,'0')}:00`;
        horaFinStr = document.getElementById('blockTimeFinFixed').value || null;
      }

      const fechas = buildFechasList(fechaStr);

      blockSave.disabled = true;
      blockSave.textContent = `Guardando ${fechas.length > 1 ? fechas.length + ' bloqueos' : 'bloqueo'}...`;

      try {
        const resp = await fetch('{{ route('agenda.bloqueos.store') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ label, fechas, hora: horaStr, hora_fin: horaFinStr || undefined })
        });
        const data = await resp.json();
        if (!resp.ok || !data.ok) throw new Error(data.message || 'Error al guardar');

        (data.bloqueos || []).forEach(b => {
          const key = b.fecha;
          if (!EVENTS[key]) EVENTS[key] = [];
          EVENTS[key] = EVENTS[key].filter(ev => !(ev.cls === 'ev-block' && ev.h === b.h));
          EVENTS[key].push({ t: `${b.h} ${b.label}`, name: b.label, cls: 'ev-block', h: b.h, blockId: b.id, hora: b.hora, hora_fin: b.hora_fin, duracion: b.duracion || 60 });
        });
      } catch (err) {
        console.error('Error guardando bloqueo:', err);
      } finally {
        blockSave.disabled = false;
        blockSave.textContent = 'Guardar bloqueo';
      }
      closeBlockModal();
      lastBuiltDate = {y, m, d};
      if (typeof window.__rebuildCurrentView === 'function') window.__rebuildCurrentView();
      else buildDayFn(new Date(y, m, d));
    });

    document.addEventListener('dblclick', e => {
      const slot = e.target.closest('.day-slot');
      if (!slot) return;
      const blockEl = e.target.closest('.day-event.ev-block');
      if (blockEl) {
        pendingDeleteId  = parseInt(blockEl.dataset.blockid);
        pendingDeleteKey = blockEl.dataset.blockkey;
        const lbl = blockEl.dataset.blocklabel || 'este bloqueo';
        document.getElementById('blockConfirmMsg').textContent = `Se eliminará "${lbl}".`;
        blockConfirm.classList.add('open');
        return;
      }
      const row = slot.closest('.day-row');
      const hourEl = row ? row.querySelector('.day-hour') : null;
      const hr = hourEl ? parseInt(hourEl.textContent) : 8;
      // Si ya hay un evento (cita real) en este slot, no permitir bloqueo
      const hasEvent = slot.querySelector('.day-event:not(.ev-block)');
      if (hasEvent) return;
      // Parsear fecha del título solo una vez y guardarla
      const t = document.getElementById('dayTitleText').textContent;
      const parts = t.split(' ');
      const dy = parseInt(parts[parts.length-1]);
      const dm = MESES.indexOf(parts[parts.length-3]);
      const dd = parseInt(parts[1]);
      lastBuiltDate = {y: dy, m: dm, d: dd};
      openBlockModal(hr, dy, dm, dd);
    });

    window.__setBloqueoDate = function(date) {
      lastBuiltDate = {y: date.getFullYear(), m: date.getMonth(), d: date.getDate()};
    };

    window.__openDeleteBloqueoConfirm = function(blockId, blockKey, blockLabel) {
      pendingDeleteId  = parseInt(blockId);
      pendingDeleteKey = blockKey || null;
      const lbl = blockLabel || 'este bloqueo';
      document.getElementById('blockConfirmMsg').textContent = `Se eliminará "${lbl}".`;
      blockConfirm.classList.add('open');
    };

    blockConfirmNo.addEventListener('click', () => blockConfirm.classList.remove('open'));

    blockConfirmYes.addEventListener('click', async () => {
      if (pendingDeleteId !== null) {
        blockConfirmYes.disabled = true;
        try {
          const resp = await fetch('{{ route('agenda.bloqueos.destroy', ['bloqueo' => '__ID__']) }}'.replace('__ID__', pendingDeleteId), {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          });
          if (!resp.ok) {
            const data = await resp.json().catch(() => ({}));
            throw new Error(data.message || 'Error al eliminar');
          }
        } catch (err) {
          console.error('Error eliminando bloqueo:', err);
        } finally {
          blockConfirmYes.disabled = false;
        }
        if (pendingDeleteKey && EVENTS[pendingDeleteKey]) {
          EVENTS[pendingDeleteKey] = EVENTS[pendingDeleteKey].filter(ev => ev.blockId !== pendingDeleteId);
        } else {
          Object.keys(EVENTS).forEach(k => {
            EVENTS[k] = EVENTS[k].filter(ev => ev.blockId !== pendingDeleteId);
          });
        }
      }
      blockConfirm.classList.remove('open');
      if (typeof window.__rebuildCurrentView === 'function') {
        window.__rebuildCurrentView();
      } else if (lastBuiltDate) {
        buildDayFn(new Date(lastBuiltDate.y, lastBuiltDate.m, lastBuiltDate.d));
      }
    });
  };
})();
</script>
