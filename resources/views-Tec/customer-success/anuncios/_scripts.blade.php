<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
(function(){
  const form = document.getElementById('csForm');
  const alert = document.getElementById('csAlert');
  const editor = document.getElementById('csContenido');
  const hiddenInput = document.getElementById('csContenidoHtml');
  const toolbar = document.getElementById('csToolbar');
  const pvOverlay = document.getElementById('pvOverlay');
  const pvClose = document.getElementById('pvClose');
  const pvBtn = document.getElementById('csPreview');

  const _confirmOv  = document.getElementById('csConfirmOv');
  const _confirmMsg  = document.getElementById('csConfirmMsg');
  const _confirmOk   = document.getElementById('csConfirmOk');
  const _confirmCancel = document.getElementById('csConfirmCancel');

  function csConfirm(msg) {
    return new Promise(resolve => {
      _confirmMsg.textContent = msg;
      _confirmOv.classList.add('open');
      function cleanup(result) {
        _confirmOv.classList.remove('open');
        _confirmOk.removeEventListener('click', onOk);
        _confirmCancel.removeEventListener('click', onCancel);
        resolve(result);
      }
      function onOk()     { cleanup(true);  }
      function onCancel() { cleanup(false); }
      _confirmOk.addEventListener('click', onOk);
      _confirmCancel.addEventListener('click', onCancel);
    });
  }

  function showAlert(msg, type){
    alert.textContent = msg;
    alert.className = 'cs-alert ' + type;
    alert.style.display = 'block';
    setTimeout(() => { alert.style.display = 'none'; }, 4000);
  }

  function refreshToolbar(){
    toolbar.querySelectorAll('button[data-cmd]').forEach(btn => {
      const cmd = btn.dataset.cmd;
      if (['bold','italic','underline','insertUnorderedList','insertOrderedList'].includes(cmd)) {
        try { btn.classList.toggle('active', document.queryCommandState(cmd)); } catch(e){}
      }
    });
  }

  toolbar.querySelectorAll('button[data-cmd]').forEach(btn => {
    btn.addEventListener('mousedown', (e) => {
      e.preventDefault();
      const cmd = btn.dataset.cmd;
      if (cmd === 'createLink') {
        const url = prompt('Ingresa la URL del enlace:');
        if (url) document.execCommand('createLink', false, url);
      } else {
        document.execCommand(cmd, false, null);
      }
      editor.focus();
      refreshToolbar();
    });
  });

  editor.addEventListener('keyup', refreshToolbar);
  editor.addEventListener('mouseup', refreshToolbar);
  editor.addEventListener('blur', () => hiddenInput.value = editor.innerHTML);
  editor.addEventListener('input', () => hiddenInput.value = editor.innerHTML);

  const THEMES = {
    notificacion:     { cls: 'theme-notificacion',      badge: '🔔 Notificación',           icon: null },
    anuncios_internos: { cls: 'theme-anuncios_internos', badge: '📋 Comunicado Interno', icon: null },
    mejoras:          { cls: 'theme-mejoras',           badge: '🚀 Mejoras en Enclaii',  icon: null },
    mantenimiento:    { cls: 'theme-mantenimiento',     badge: '⚠️ Aviso de Mantenimiento', icon: '🔧' },
    politicas:        { cls: 'theme-politicas',         badge: '📄 Documento de Política', icon: null },
  };

  const BOILERPLATES = {
    notificacion:
      '<p>Estimado usuario,</p>'
      + '<br>'
      + '<p>[Escribe aquí el mensaje de tu notificación.]</p>'
      + '<br>'
      + '<p>Si tienes alguna duda, no dudes en contactarnos.</p>'
      + '<p><strong>Equipo Enclaii</strong></p>',

    anuncios_internos:
      '<p><strong>Fecha:</strong> [Fecha]</p>'
      + '<p><strong>Asunto:</strong> [Asunto del comunicado]</p>'
      + '<p><strong>Dirigido a:</strong> Equipo Enclaii</p>'
      + '<br>'
      + '<p>Estimado equipo,</p>'
      + '<p>[Cuerpo del mensaje. Describe el anuncio, cambio de proceso o bienvenida al nuevo miembro del equipo que colaborará con Ricardo y Yesica.]</p>'
      + '<br>'
      + '<p>Para cualquier duda, pueden contactarnos directamente.</p>'
      + '<p><strong>Atentamente,<br>Equipo Customer Success</strong></p>',

    mejoras:
      '<p><strong>Versión:</strong> [v1.x.x] — [Fecha de lanzamiento]</p>'
      + '<br>'
      + '<p>🎉 Estamos emocionados de anunciar las siguientes mejoras:</p>'
      + '<br>'
      + '<p><strong>✨ Nuevas funcionalidades</strong></p>'
      + '<ul><li>[Descripción del nuevo feature 1]</li><li>[Descripción del nuevo feature 2]</li></ul>'
      + '<br>'
      + '<p><strong>🐛 Correcciones de errores</strong></p>'
      + '<ul><li>[Bug corregido 1]</li><li>[Bug corregido 2]</li></ul>'
      + '<br>'
      + '<p><em>Gracias por seguir usando Enclaii. ¡Seguimos mejorando para ti!</em></p>',

    mantenimiento:
      '<p>⚠️ Se realizará un mantenimiento programado en la plataforma.</p>'
      + '<br>'
      + '<p><strong>📅 Fecha de inicio:</strong> [Fecha y hora]</p>'
      + '<p><strong>⏱ Duración estimada:</strong> [X horas]</p>'
      + '<p><strong>📦 Módulos afectados:</strong> [Lista de módulos, ej: Agenda, Estudios, Reportes]</p>'
      + '<br>'
      + '<p><strong>⚡ Acciones requeridas:</strong></p>'
      + '<ul><li>Guarda tu trabajo antes de la hora indicada.</li><li>[Acción adicional si aplica]</li></ul>'
      + '<br>'
      + '<p>Lamentamos los inconvenientes. La plataforma estará disponible al finalizar el mantenimiento.</p>',

    politicas:
      '<p><strong>Documento:</strong> [Nombre de la política]</p>'
      + '<p><strong>Versión:</strong> [1.0] &nbsp;|&nbsp; <strong>Vigente desde:</strong> [Fecha]</p>'
      + '<br>'
      + '<ol>'
      + '<li><strong>Objetivo</strong><p>[Descripción del objetivo de esta política.]</p></li>'
      + '<li><strong>Alcance</strong><p>[A quién aplica esta política.]</p></li>'
      + '<li><strong>Disposiciones generales</strong><p>[Texto de las disposiciones.]</p></li>'
      + '<li><strong>Sanciones</strong><p>[Consecuencias por incumplimiento.]</p></li>'
      + '</ol>'
      + '<br>'
      + '<p>📎 Documento completo: <a href="#">[Enlace al PDF]</a></p>',
  };

  const tipoSelect = document.getElementById('csTipo');

  tipoSelect.addEventListener('change', async function(){
    const boilerplate = BOILERPLATES[this.value];
    if (!boilerplate) {
      if (editor.innerHTML.trim() !== '') {
        const ok = await csConfirm('Este tipo no tiene plantilla. ¿Deseas limpiar el contenido actual?');
        if (ok) { editor.innerHTML = ''; hiddenInput.value = ''; }
      }
      return;
    }
    if (editor.innerHTML.trim() === '') {
      editor.innerHTML = boilerplate;
      hiddenInput.value = boilerplate;
    } else {
      const ok = await csConfirm('¿Reemplazar el contenido con la plantilla de "' + this.options[this.selectedIndex].text + '"?');
      if (ok) { editor.innerHTML = boilerplate; hiddenInput.value = boilerplate; }
    }
  });

  function openPreview(){
    const titulo = document.getElementById('csTitulo').value || 'Sin título';
    const tipo = tipoSelect;
    const tipoVal = tipo.value;
    const tipoLabel = tipo.options[tipo.selectedIndex].text;
    const publico = document.getElementById('csPublico');
    const publicoLabel = publico.options[publico.selectedIndex].text;
    const theme = THEMES[tipoVal] || { cls: '', badge: tipoLabel, icon: null };

    const pvCard = document.getElementById('pvCard');
    pvCard.className = 'pv-card ' + theme.cls;

    const pvIcon = document.getElementById('pvIcon');
    if (theme.icon) { pvIcon.textContent = theme.icon; pvIcon.style.display = 'block'; }
    else { pvIcon.style.display = 'none'; }

    document.getElementById('pvBadge').textContent = theme.badge;
    document.getElementById('pvTitle').textContent = titulo;
    document.getElementById('pvMeta').textContent = tipoLabel + ' • ' + publicoLabel;
    document.getElementById('pvBody').innerHTML = editor.innerHTML || '<p>Sin contenido</p>';
    pvOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closePreview(){
    pvOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  pvBtn.addEventListener('click', openPreview);
  pvClose.addEventListener('click', closePreview);
  pvOverlay.addEventListener('click', (e) => { if (e.target === pvOverlay) closePreview(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && pvOverlay.classList.contains('open')) closePreview(); });

  let editingId = null;
  let flatpickrInstance = null;
  const submitBtn   = form.querySelector('button[type="submit"]');
  const editBanner  = document.getElementById('csEditBanner');
  const bannerTitle = document.getElementById('csEditBannerTitle');
  const cancelBtn   = document.getElementById('csCancelEdit');

  function enterEditMode(id, titulo) {
    editingId = id;
    submitBtn.textContent = 'Guardar cambios';
    tipoSelect.disabled = true;
    tipoSelect.closest('.cs-tipo-wrap')?.classList.add('locked');
    if (bannerTitle) bannerTitle.textContent = titulo || '—';
    if (editBanner)  editBanner.classList.add('visible');
    if (emailWarning) emailWarning.style.display = 'none';
    setTimeout(() => {
      const top = (editBanner?.getBoundingClientRect().top ?? 0) + window.scrollY - 16;
      window.scrollTo({ top, behavior: 'smooth' });
    }, 30);
  }

  function exitEditMode() {
    editingId = null;
    submitBtn.textContent = 'Publicar anuncio';
    tipoSelect.disabled = false;
    tipoSelect.closest('.cs-tipo-wrap')?.classList.remove('locked');
    if (editBanner)  editBanner.classList.remove('visible');
    if (emailWarning) emailWarning.style.display = 'none';
    if (bannerTitle) bannerTitle.textContent = '—';
    form.reset();
    editor.innerHTML = '';
    hiddenInput.value = '';
    if (flatpickrInstance) flatpickrInstance.clear();
    document.querySelectorAll('input[name="csCanales"]').forEach(cb => {
      cb.checked = cb.value === 'web';
    });
  }

  if (cancelBtn) cancelBtn.addEventListener('click', exitEditMode);

  const emailCheckbox = document.getElementById('csCanalesEmail');
  const emailWarning  = document.getElementById('csEmailWarning');
  if (emailCheckbox && emailWarning) {
    emailCheckbox.addEventListener('change', function() {
      emailWarning.style.display = this.checked ? 'block' : 'none';
    });
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    hiddenInput.value = editor.innerHTML;

    const canales = Array.from(document.querySelectorAll('input[name="csCanales"]:checked')).map(cb => cb.value);

    const payload = {
      titulo: document.getElementById('csTitulo').value,
      contenido: editor.innerHTML,
      tipo: document.getElementById('csTipo').value,
      publico_objetivo: document.getElementById('csPublico').value,
      canales: canales,
      fecha_publicacion: (flatpickrInstance ? flatpickrInstance.input.value : document.getElementById('csFecha').value) || null,
    };


    const isEdit = editingId !== null;
    const url    = isEdit ? '/api/customer-success/anuncios/' + editingId : '/api/customer-success/anuncios';
    const method = isEdit ? 'PUT' : 'POST';

    try {
      const res = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(payload),
      });

      if (res.status === 419) {
        showAlert('Tu sesión expiró. Redirigiendo...', 'error');
        setTimeout(() => { window.location.href = '/cerrar-sesion'; }, 1200);
        return;
      }
      if (res.ok) {
        showAlert(isEdit ? 'Anuncio actualizado correctamente.' : 'Anuncio enviado correctamente.', 'success');
        exitEditMode();
        setTimeout(() => location.reload(), 800);
      } else {
        const data = await res.json();
        if (res.status === 422 && data.errors) {
          const msgs = Object.values(data.errors).flat().join(' | ');
          showAlert(msgs, 'error');
        } else {
          showAlert(data.message || 'Error al guardar.', 'error');
        }
      }
    } catch (err) {
      showAlert('Error de conexión.', 'error');
    }
  });

  // Paginación AJAX — intercepta clicks en links de paginación
  const listaWrap = document.getElementById('csListaWrap');

  function bindPaginationLinks() {
    if (!listaWrap) return;
    listaWrap.querySelectorAll('[data-pagination] a, nav a, .pagination a').forEach(a => {
      a.addEventListener('click', async function(e) {
        e.preventDefault();
        const url = this.href;
        if (!url) return;
        try {
          const res = await fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'text/html',
            }
          });
          if (res.status === 419) {
            showAlert('Tu sesión expiró. Redirigiendo...', 'error');
            setTimeout(() => { window.location.href = '/cerrar-sesion'; }, 1200);
            return;
          }
          if (res.ok) {
            const html = await res.text();
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            const newWrap = tmp.querySelector('#csListaWrap');
            if (newWrap) {
              listaWrap.innerHTML = newWrap.innerHTML;
              bindDeleteButtons();
              bindViewButtons();
              bindEditButtons();
              bindPaginationLinks();
              window.history.pushState({}, '', url);
            }
          }
        } catch(err) {}
      });
    });
  }

  function bindDeleteButtons() {
    if (!listaWrap) return;
    listaWrap.querySelectorAll('.cs-delete').forEach(btn => {
      if (btn.dataset.bound) return;
      btn.dataset.bound = '1';
      btn.addEventListener('click', async function(){
        const row = this.closest('tr');
        const id = row.dataset.id;
        if (!await csConfirm('¿Eliminar este anuncio? Esta acción no se puede deshacer.')) return;
        try {
          const res = await fetch('/api/customer-success/anuncios/' + id, {
            method: 'DELETE',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
          });
          if (res.status === 419) {
            showAlert('Tu sesión expiró. Redirigiendo...', 'error');
            setTimeout(() => { window.location.href = '/cerrar-sesion'; }, 1200);
            return;
          }
          if (res.ok) {
            row.remove();
            if (editingId == id) exitEditMode();
            showAlert('Anuncio eliminado.', 'success');
          } else {
            const data = await res.json();
            showAlert(data.message || 'Error al eliminar.', 'error');
          }
        } catch (err) {
          showAlert('Error de conexión.', 'error');
        }
      });
    });
  }

  function bindViewButtons() {
    if (!listaWrap) return;
    listaWrap.querySelectorAll('.cs-view').forEach(btn => {
      if (btn.dataset.bound) return;
      btn.dataset.bound = '1';
      btn.addEventListener('click', function() {
        const row   = this.closest('tr');
        const tipo  = row.dataset.tipo;
        const theme = THEMES[tipo] || { cls: '', badge: tipo, icon: null };

        const pvCard = document.getElementById('pvCard');
        pvCard.className = 'pv-card ' + theme.cls;

        const pvIcon = document.getElementById('pvIcon');
        if (theme.icon) { pvIcon.textContent = theme.icon; pvIcon.style.display = 'block'; }
        else { pvIcon.style.display = 'none'; }

        document.getElementById('pvBadge').textContent = theme.badge;
        document.getElementById('pvTitle').textContent = row.dataset.titulo;

        const tipoOpts = tipoSelect.options;
        let tipoLabel = tipo;
        for (let i = 0; i < tipoOpts.length; i++) {
          if (tipoOpts[i].value === tipo) { tipoLabel = tipoOpts[i].text; break; }
        }
        const publicoMap = { todos: 'Todos', doctores: 'Doctores', administradores: 'Administradores' };
        document.getElementById('pvMeta').textContent = tipoLabel + ' • ' + (publicoMap[row.dataset.publico] ?? row.dataset.publico);
        const contenidoView = (() => { try { return JSON.parse(row.dataset.contenido || '""'); } catch { return row.dataset.contenido || ''; } })();
        document.getElementById('pvBody').innerHTML = contenidoView || '<p>Sin contenido</p>';

        pvOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
      });
    });
  }

  function bindEditButtons() {
    if (!listaWrap) return;
    listaWrap.querySelectorAll('.cs-edit').forEach(btn => {
      if (btn.dataset.bound) return;
      btn.dataset.bound = '1';
      btn.addEventListener('click', function() {
        const row = this.closest('tr');

        document.getElementById('csTitulo').value = row.dataset.titulo;

        tipoSelect.disabled = true;
        tipoSelect.value = row.dataset.tipo;
        document.getElementById('csPublico').value = row.dataset.publico;

        const canales = (row.dataset.canales || 'web').split(',').map(s => s.trim()).filter(Boolean);
        document.querySelectorAll('input[name="csCanales"]').forEach(cb => {
          cb.checked = canales.includes(cb.value);
        });

        if (flatpickrInstance && row.dataset.fecha) {
          flatpickrInstance.setDate(row.dataset.fecha, false);
        } else if (flatpickrInstance) {
          flatpickrInstance.clear();
        }

        const contenidoEdit = (() => { try { return JSON.parse(row.dataset.contenido || '""'); } catch { return row.dataset.contenido || ''; } })();
        editor.innerHTML = contenidoEdit;
        hiddenInput.value = contenidoEdit;

        enterEditMode(row.dataset.id, row.dataset.titulo);
      });
    });
  }

  bindDeleteButtons();
  bindViewButtons();
  bindEditButtons();
  bindPaginationLinks();

  // Auto-refresh lista cuando hay anuncios programados pendientes
  (function startScheduledPoll() {
    const hasProgramados = !!document.querySelector('#csListaWrap [data-fecha]');
    if (!hasProgramados) return;

    let lastSnapshot = document.querySelector('#csListaWrap tbody')?.innerHTML || '';

    async function refreshLista() {
      try {
        const url = window.location.href.split('?')[0];
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
        if (!res.ok) return;
        const html = await res.text();
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        const newWrap = tmp.querySelector('#csListaWrap');
        if (!newWrap) return;
        const newSnapshot = newWrap.querySelector('tbody')?.innerHTML || '';
        if (newSnapshot !== lastSnapshot) {
          lastSnapshot = newSnapshot;
          listaWrap.innerHTML = newWrap.innerHTML;
          bindDeleteButtons();
          bindViewButtons();
          bindEditButtons();
          bindPaginationLinks();
        }
      } catch {}
    }

    setInterval(refreshLista, 30000);
  })();

  document.querySelectorAll('input[name="csCanales"]').forEach(cb => {
    cb.checked = cb.value === 'web';
  });

  // Calendario personalizado para fecha de publicación
  if (typeof flatpickr !== 'undefined') {
    flatpickrInstance = flatpickr('#csFecha', {
      enableTime: true,
      dateFormat: 'Y-m-d H:i:S',
      altInput: true,
      altFormat: 'd/m/Y h:i K',
      locale: 'es',
      time_24hr: false,
      minuteIncrement: 1,
      allowInput: true,
      disableMobile: false,
      defaultSeconds: 0,
    });
  }
})();
</script>
