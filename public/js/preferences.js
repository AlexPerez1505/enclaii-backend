/**
 * preferences.js
 * Aplica en el cliente las preferencias del usuario guardadas en la base de datos
 * (window.enclaiiSettings): formato de fecha, formato de hora y elementos por página.
 *
 * Las vistas actuales usan datos estáticos, por lo que estas transformaciones
 * operan sobre el DOM ya renderizado. Cuando existan datos reales en BD seguirán
 * funcionando sin cambios.
 */
(function () {
  'use strict';

  var S = window.enclaiiSettings || {};
  var dateFmt = S.date_format || 'DD/MM/YYYY';
  var timeFmt = S.time_format || '12 horas (AM/PM)';
  var perPage = parseInt(S.items_per_page, 10);
  if (isNaN(perPage) || perPage <= 0) perPage = 25;

  /* ---------- Formato de fecha ---------- */
  function reformatDate(str) {
    var m = str.match(/(\d{2})\/(\d{2})\/(\d{4})/);
    if (!m) return str;
    var d = m[1], mo = m[2], y = m[3];
    switch (dateFmt) {
      case 'MM/DD/YYYY': return mo + '/' + d + '/' + y;
      case 'YYYY-MM-DD': return y + '-' + mo + '-' + d;
      default: return d + '/' + mo + '/' + y;
    }
  }

  /* ---------- Formato de hora ---------- */
  function reformatTime(str) {
    var m = str.match(/(\d{1,2}):(\d{2})\s*(AM|PM)?/i);
    if (!m) return str;
    var h = parseInt(m[1], 10);
    var min = m[2];
    var ap = m[3] ? m[3].toUpperCase() : null;

    // Normaliza a 24h
    if (ap === 'PM' && h < 12) h += 12;
    if (ap === 'AM' && h === 12) h = 0;

    if (timeFmt.indexOf('24') >= 0) {
      return (h < 10 ? '0' + h : h) + ':' + min;
    }
    var ap2 = h >= 12 ? 'PM' : 'AM';
    var h12 = h % 12;
    if (h12 === 0) h12 = 12;
    return h12 + ':' + min + ' ' + ap2;
  }

  function applyDateFormats() {
    var cells = document.querySelectorAll('.date');
    cells.forEach(function (cell) {
      // Guarda los valores originales una sola vez.
      if (cell.dataset.prefInit === undefined) {
        var small = cell.querySelector('small');
        var dateText = '';
        cell.childNodes.forEach(function (n) {
          if (n.nodeType === 3) dateText += n.textContent;
        });
        cell.dataset.rawDate = dateText.trim();
        cell.dataset.rawTime = small ? small.textContent.trim() : '';
        cell.dataset.hasSmall = small ? '1' : '0';
        cell.dataset.prefInit = '1';
      }

      var newDate = reformatDate(cell.dataset.rawDate);
      var html = newDate;
      if (cell.dataset.hasSmall === '1' && cell.dataset.rawTime) {
        html += ' <small>' + reformatTime(cell.dataset.rawTime) + '</small>';
      }
      cell.innerHTML = html;
    });
  }

  /* ---------- Elementos por página (paginación de tablas) ---------- */
  function applyPagination() {
    document.querySelectorAll('table.tbl').forEach(function (table) {
      var tbody = table.tBodies[0];
      if (!tbody) return;
      var rows = Array.prototype.slice.call(tbody.rows);
      if (rows.length <= perPage) {
        // Sin paginación necesaria: asegura que todo esté visible.
        rows.forEach(function (r) { r.style.display = ''; });
        var oldNav = table.parentNode.querySelector('.pref-pager');
        if (oldNav) oldNav.remove();
        return;
      }

      var page = 0;
      var pages = Math.ceil(rows.length / perPage);

      function render() {
        rows.forEach(function (r, i) {
          var p = Math.floor(i / perPage);
          r.style.display = p === page ? '' : 'none';
        });
        label.textContent = 'Página ' + (page + 1) + ' de ' + pages;
        prev.disabled = page === 0;
        next.disabled = page === pages - 1;
      }

      // Construye la barra de paginación (una vez).
      var nav = table.parentNode.querySelector('.pref-pager');
      if (nav) nav.remove();
      nav = document.createElement('div');
      nav.className = 'pref-pager';
      nav.style.cssText = 'display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:12px;font-size:12.5px;color:var(--txt-soft)';

      var prev = document.createElement('button');
      var next = document.createElement('button');
      var label = document.createElement('span');
      [prev, next].forEach(function (b) {
        b.type = 'button';
        b.style.cssText = 'padding:6px 12px;border-radius:8px;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt);font-weight:600;cursor:pointer';
      });
      prev.textContent = 'Anterior';
      next.textContent = 'Siguiente';
      prev.addEventListener('click', function () { if (page > 0) { page--; render(); } });
      next.addEventListener('click', function () { if (page < pages - 1) { page++; render(); } });

      nav.appendChild(label);
      nav.appendChild(prev);
      nav.appendChild(next);
      table.parentNode.appendChild(nav);

      render();
    });
  }

  function applyAll() {
    try { applyDateFormats(); } catch (e) {}
    try { applyPagination(); } catch (e) {}
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyAll);
  } else {
    applyAll();
  }
})();
