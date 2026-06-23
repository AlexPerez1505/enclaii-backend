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
.block-recur{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:20px}
.block-recur-btn{flex:1;min-width:60px;padding:7px 4px;border-radius:8px;font-size:11.5px;font-weight:600;background:#001525;border:1px solid rgba(22,139,217,.25);color:rgba(234,241,255,.65);cursor:pointer;transition:all 140ms ease;text-align:center}
.block-recur-btn:hover{background:#002540;border-color:rgba(22,139,217,.5);color:#EAF1FF}
.block-recur-btn.selected{background:linear-gradient(135deg,#1668D9,#2E7BF6);border-color:transparent;color:#fff}
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
html[data-theme="light"] .block-confirm-box{background:#F0F5FF;border-color:rgba(217,0,0,.3)}
html[data-theme="light"] .block-confirm-box h3{color:#0E1530}
html[data-theme="light"] .block-confirm-box p{color:rgba(14,21,48,.5)}
</style>

{{-- ---- HTML ---- --}}
<div class="block-overlay" id="blockOverlay">
  <div class="block-modal">
    <button class="block-close" id="blockClose">&#x2715;</button>
    <h3>Bloqueo de Tiempo</h3>
    <p id="blockSubtitle">Hora seleccionada</p>
    <label>Motivo / Etiqueta</label>
    <input type="text" id="blockLabel" placeholder="Ej: Almuerzo, Reunión...">
    <label>Repetir</label>
    <div class="block-recur">
      <button class="block-recur-btn selected" data-recur="none">Solo este día</button>
      <button class="block-recur-btn" data-recur="week">1 semana</button>
      <button class="block-recur-btn" data-recur="month">1 mes</button>
      <button class="block-recur-btn" data-recur="year">1 año</button>
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
    let pendingBlockHour = null, pendingBlockDate = null, selectedRecur = 'none';
    let pendingDeleteId = null, pendingDeleteKey = null;
    let lastBuiltDate = null; // fecha actual visible en la vista día

    const blockOverlay    = document.getElementById('blockOverlay');
    const blockClose      = document.getElementById('blockClose');
    const blockCancel     = document.getElementById('blockCancel');
    const blockSave       = document.getElementById('blockSave');
    const blockLabelEl    = document.getElementById('blockLabel');
    const blockSubtitle   = document.getElementById('blockSubtitle');
    const blockConfirm    = document.getElementById('blockConfirm');
    const blockConfirmNo  = document.getElementById('blockConfirmNo');
    const blockConfirmYes = document.getElementById('blockConfirmYes');

    document.querySelectorAll('.block-recur-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.block-recur-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedRecur = btn.dataset.recur;
      });
    });

    function dateKey(y, m, d) { return `${y}-${m+1}-${d}`; }

    function addBlockToEvents(key, blockObj) {
      if (!EVENTS[key]) EVENTS[key] = [];
      EVENTS[key] = EVENTS[key].filter(ev => !(ev.cls === 'ev-block' && ev.h === blockObj.h));
      EVENTS[key].push({ t: blockObj.label, cls: 'ev-block', h: blockObj.h, blockId: blockObj.id });
      if (!BLOCKS[key]) BLOCKS[key] = [];
      BLOCKS[key].push(blockObj);
    }

    function openBlockModal(hr, y, m, d) {
      pendingBlockHour = hr;
      pendingBlockDate = {y, m, d};
      selectedRecur = 'none';
      document.querySelectorAll('.block-recur-btn').forEach(b => {
        b.classList.toggle('selected', b.dataset.recur === 'none');
      });
      blockLabelEl.value = '';
      blockSubtitle.textContent = `De ${hr}:00 a ${hr + 1}:00`;
      blockOverlay.classList.add('open');
      setTimeout(() => blockLabelEl.focus(), 80);
    }

    function closeBlockModal() { blockOverlay.classList.remove('open'); }

    blockClose.addEventListener('click', closeBlockModal);
    blockCancel.addEventListener('click', closeBlockModal);
    blockOverlay.addEventListener('click', e => { if (e.target === blockOverlay) closeBlockModal(); });

    blockSave.addEventListener('click', () => {
      const label = blockLabelEl.value.trim() || 'Bloqueo de Tiempo';
      const {y, m, d} = pendingBlockDate;
      const hr = pendingBlockHour;
      const id = blockIdCounter++;
      const blockObj = {h: hr, label: `${hr}:00 ${label}`, displayLabel: label, id};

      if (selectedRecur === 'none') {
        addBlockToEvents(dateKey(y,m,d), blockObj);
      } else {
        const start = new Date(y, m, d);
        let end;
        if (selectedRecur === 'week')  end = new Date(y, m, d + 7);
        if (selectedRecur === 'month') end = new Date(y, m + 1, d);
        if (selectedRecur === 'year')  end = new Date(y + 1, m, d);
        const cur2 = new Date(start);
        while (cur2 <= end) {
          addBlockToEvents(dateKey(cur2.getFullYear(), cur2.getMonth(), cur2.getDate()),
            Object.assign({}, blockObj, {id: blockIdCounter++}));
          cur2.setDate(cur2.getDate() + 1);
        }
      }
      closeBlockModal();
      lastBuiltDate = {y, m, d};
      buildDayFn(new Date(y, m, d));
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

    blockConfirmNo.addEventListener('click', () => blockConfirm.classList.remove('open'));

    blockConfirmYes.addEventListener('click', () => {
      if (pendingDeleteKey && pendingDeleteId !== null) {
        if (EVENTS[pendingDeleteKey]) {
          EVENTS[pendingDeleteKey] = EVENTS[pendingDeleteKey].filter(ev => ev.blockId !== pendingDeleteId);
        }
        if (BLOCKS[pendingDeleteKey]) {
          BLOCKS[pendingDeleteKey] = BLOCKS[pendingDeleteKey].filter(b => b.id !== pendingDeleteId);
        }
      }
      blockConfirm.classList.remove('open');
      if (lastBuiltDate) {
        buildDayFn(new Date(lastBuiltDate.y, lastBuiltDate.m, lastBuiltDate.d));
      }
    });
  };
})();
</script>
