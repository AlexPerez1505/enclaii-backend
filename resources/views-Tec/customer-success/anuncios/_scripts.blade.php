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

  // ── nc-card: contador título ──────────────────────────────
  const ncTituloInput = document.getElementById('csTitulo');
  const ncTituloCount = document.getElementById('ncTituloCount');
  if (ncTituloInput && ncTituloCount) {
    ncTituloInput.addEventListener('input', () => {
      ncTituloCount.textContent = ncTituloInput.value.length;
    });
  }

  // ── nc-card: contador contenido ───────────────────────────
  const ncContentCount = document.getElementById('ncContentCount');
  if (editor && ncContentCount) {
    editor.addEventListener('input', () => {
      ncContentCount.textContent = editor.innerText.replace(/\n$/, '').length;
    });
  }

  // ── nc-card: público objetivo ─────────────────────────────
  document.querySelectorAll('.nc-radio-opt').forEach(opt => {
    opt.addEventListener('click', () => {
      document.querySelectorAll('.nc-radio-opt').forEach(o => o.classList.remove('nc-radio-opt--active'));
      opt.classList.add('nc-radio-opt--active');
      const radio = opt.querySelector('input[type=radio]');
      if (radio) {
        radio.checked = true;
        const hidden = document.getElementById('csPublico');
        if (hidden) hidden.value = radio.value;
      }
    });
  });

  // ── nc-card: canales toggle visual ───────────────────────
  document.querySelectorAll('.nc-channel-opt').forEach(opt => {
    opt.addEventListener('click', () => {
      const cb = opt.querySelector('input[type=checkbox]');
      if (!cb) return;
      // Evita doble toggle (el click ya cambia el checkbox hidden)
      // Solo actualizamos la advertencia email
      const emailWarn = document.getElementById('csEmailWarning');
      if (emailWarn) {
        const emailCb = document.getElementById('csCanalesEmail');
        emailWarn.style.display = emailCb && emailCb.checked ? 'block' : 'none';
      }
    });
  });

  // ── nc-card: programar publicación ───────────────────────
  const ncSchedInm  = document.getElementById('ncSchedInm');
  const ncSchedProg = document.getElementById('ncSchedProg');
  const ncFechaWrap = document.getElementById('ncFechaWrap');

  function setSchedActive(el) {
    [ncSchedInm, ncSchedProg].forEach(o => {
      if (o) o.classList.remove('nc-sched-opt--active');
    });
    if (el) el.classList.add('nc-sched-opt--active');
  }

  if (ncSchedInm) {
    ncSchedInm.addEventListener('click', () => {
      setSchedActive(ncSchedInm);
      if (ncFechaWrap) ncFechaWrap.style.display = 'none';
      const fi = document.getElementById('csFecha');
      if (fi) fi.value = '';
    });
  }
  if (ncSchedProg) {
    ncSchedProg.addEventListener('click', () => {
      setSchedActive(ncSchedProg);
      if (ncFechaWrap) ncFechaWrap.style.display = 'block';
      const fi = document.getElementById('csFecha');
      if (fi) fi.focus();
    });
  }

  // ── nc-card: botón cerrar/mostrar ─────────────────────────
  const ncCard       = document.querySelector('.nc-card');
  const ncHeaderClose = document.getElementById('ncHeaderClose');
  const ncBody        = document.querySelector('.nc-body');
  const ncFooter      = document.querySelector('.nc-footer');
  let ncCollapsed = false;
  if (ncHeaderClose && ncCard) {
    ncHeaderClose.addEventListener('click', () => {
      ncCollapsed = !ncCollapsed;
      if (ncBody)   ncBody.style.display   = ncCollapsed ? 'none' : '';
      if (ncFooter) ncFooter.style.display = ncCollapsed ? 'none' : '';
      ncHeaderClose.style.transform = ncCollapsed ? 'rotate(45deg)' : '';
      ncHeaderClose.title = ncCollapsed ? 'Abrir formulario' : 'Cerrar';
    });
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
  const tipoWrap = tipoSelect.closest('.cs-tipo-wrap');
  const tipoTrigger = document.getElementById('csTipoTrigger');
  const tipoLabel = document.getElementById('csTipoLabel');
  const tipoMenu = document.getElementById('csTipoMenu');

  function syncTipoVisual(){
    const selected = tipoSelect.options[tipoSelect.selectedIndex];
    if(tipoLabel && selected) tipoLabel.textContent = selected.text;
    document.querySelectorAll('.nc-tipo-option').forEach(option => {
      option.classList.toggle('is-selected', option.dataset.value === tipoSelect.value);
    });
  }

  function closeTipoMenu(){
    tipoWrap.classList.remove('is-open');
    tipoTrigger.setAttribute('aria-expanded', 'false');
  }

  if(tipoTrigger && tipoMenu){
    tipoTrigger.addEventListener('click', function(){
      if(tipoSelect.disabled) return;
      const isOpen = tipoWrap.classList.toggle('is-open');
      tipoTrigger.setAttribute('aria-expanded', String(isOpen));
    });
    tipoMenu.querySelectorAll('.nc-tipo-option').forEach(option => {
      option.addEventListener('click', function(){
        tipoSelect.value = option.dataset.value;
        syncTipoVisual();
        closeTipoMenu();
        tipoSelect.dispatchEvent(new Event('change'));
      });
    });
    document.addEventListener('click', function(event){
      if(!tipoWrap.contains(event.target)) closeTipoMenu();
    });
  }
  syncTipoVisual();

  tipoSelect.addEventListener('change', async function(){
    syncTipoVisual();
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
    const publicoMap = { todos: 'Todos', doctores: 'Doctores', administradores: 'Administradores', segmentos: 'Segmentos personalizados' };
    const publicoLabel = publicoMap[publico?.value] ?? publico?.value ?? 'Todos';
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
    tipoTrigger.disabled = true;
    tipoSelect.closest('.cs-tipo-wrap')?.classList.add('locked');
    closeTipoMenu();
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
    tipoTrigger.disabled = false;
    tipoSelect.closest('.cs-tipo-wrap')?.classList.remove('locked');
    if (editBanner)  editBanner.classList.remove('visible');
    if (emailWarning) emailWarning.style.display = 'none';
    if (bannerTitle) bannerTitle.textContent = '—';
    form.reset();
    syncTipoVisual();
    editor.innerHTML = '';
    hiddenInput.value = '';
    if (flatpickrInstance) flatpickrInstance.clear();
    // Reset canales
    document.querySelectorAll('input[name="csCanales"]').forEach(cb => {
      cb.checked = cb.value === 'web';
    });
    // Reset público objetivo visual
    document.querySelectorAll('.nc-radio-opt').forEach(opt => {
      const r = opt.querySelector('input[type=radio]');
      opt.classList.toggle('nc-radio-opt--active', r?.value === 'todos');
      if (r) r.checked = r.value === 'todos';
    });
    const hiddenPub = document.getElementById('csPublico');
    if (hiddenPub) hiddenPub.value = 'todos';
    // Reset programación visual
    const ncSchedInmReset  = document.getElementById('ncSchedInm');
    const ncSchedProgReset = document.getElementById('ncSchedProg');
    const ncFechaWrapReset = document.getElementById('ncFechaWrap');
    if (ncSchedInmReset)  ncSchedInmReset.classList.add('nc-sched-opt--active');
    if (ncSchedProgReset) ncSchedProgReset.classList.remove('nc-sched-opt--active');
    if (ncFechaWrapReset) ncFechaWrapReset.style.display = 'none';
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
        const anuncio = await res.json();
        console.info(
          isEdit ? '[Anuncios] Anuncio actualizado en la tabla anuncios.' : '[Anuncios] Anuncio creado en la tabla anuncios.',
          { id: anuncio.id, anuncio }
        );
        showAlert(isEdit ? 'Anuncio actualizado correctamente.' : 'Anuncio enviado correctamente.', 'success');
        exitEditMode();
        setTimeout(() => {
          window.location.assign(isEdit ? window.location.href : window.location.pathname);
        }, 800);
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
    listaWrap.querySelectorAll('[data-pagination] a, nav a, .pagination a, .paginacion-item[href]').forEach(a => {
      a.addEventListener('click', async function(e) {
        e.preventDefault();
        // Fusionar filtros activos con los parámetros de la página
        const pageUrl   = new URL(this.href, window.location.origin);
        const filterParams = typeof getFilterParams === 'function' ? getFilterParams() : new URLSearchParams();
        filterParams.forEach((v, k) => pageUrl.searchParams.set(k, v));
        const url = pageUrl.toString();
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
              if (typeof restoreFilterValues === 'function') restoreFilterValues();
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

        // Público objetivo: hidden + visual
        const pubVal = row.dataset.publico || 'todos';
        const hiddenPub = document.getElementById('csPublico');
        if (hiddenPub) hiddenPub.value = pubVal;
        document.querySelectorAll('.nc-radio-opt').forEach(opt => {
          const r = opt.querySelector('input[type=radio]');
          const active = r?.value === pubVal;
          opt.classList.toggle('nc-radio-opt--active', active);
          if (r) r.checked = active;
        });

        // Canales: checked + visual (CSS :has lo maneja)
        const canales = (row.dataset.canales || 'web').split(',').map(s => s.trim()).filter(Boolean);
        document.querySelectorAll('input[name="csCanales"]').forEach(cb => {
          cb.checked = canales.includes(cb.value);
        });

        // Programación: mostrar fecha si existe
        const ncSchedInmE  = document.getElementById('ncSchedInm');
        const ncSchedProgE = document.getElementById('ncSchedProg');
        const ncFechaWrapE = document.getElementById('ncFechaWrap');
        if (row.dataset.fecha) {
          if (ncSchedInmE)  ncSchedInmE.classList.remove('nc-sched-opt--active');
          if (ncSchedProgE) ncSchedProgE.classList.add('nc-sched-opt--active');
          if (ncFechaWrapE) ncFechaWrapE.style.display = 'block';
          if (flatpickrInstance) flatpickrInstance.setDate(row.dataset.fecha, false);
        } else {
          if (ncSchedInmE)  ncSchedInmE.classList.add('nc-sched-opt--active');
          if (ncSchedProgE) ncSchedProgE.classList.remove('nc-sched-opt--active');
          if (ncFechaWrapE) ncFechaWrapE.style.display = 'none';
          if (flatpickrInstance) flatpickrInstance.clear();
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

  // Barra de filtros — lectura siempre en vivo para sobrevivir reemplazos de innerHTML
  const fEl = (id) => document.getElementById(id);
  let filterTimer = null;

  function getFilterParams() {
    const params = new URLSearchParams();
    const q      = fEl('csFilterQ')?.value.trim();
    const tipo   = fEl('csFilterTipo')?.value;
    const canal  = fEl('csFilterCanal')?.value;
    const estado = fEl('csFilterEstado')?.value;
    if (q)      params.set('q', q);
    if (tipo)   params.set('tipo', tipo);
    if (canal)  params.set('canal', canal);
    if (estado) params.set('estado', estado);
    return params;
  }

  const filterIcons = {
    csFilterTipo: {
      '': '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
      notificacion: '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>',
      anuncios_internos: '<path d="M3 11l19-9-9 19-2-8-8-2z"/>',
      mejoras: '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
      mantenimiento: '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
      politicas: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'
    },
    csFilterCanal: {
      '': '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
      web: '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
      email: '<rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/>'
    },
    csFilterEstado: {
      '': '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
      activo: '<circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/>',
      inactivo: '<circle cx="12" cy="12" r="9"/><line x1="8" y1="8" x2="16" y2="16"/><line x1="16" y1="8" x2="8" y2="16"/>',
      programado: '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'
    }
  };

  function filterIconMarkup(select) {
    const paths = filterIcons[select.id]?.[select.value] ?? '';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + paths + '</svg>';
  }

  function syncFilterIcon(select) {
    const wrap = select.closest('.cs-filter-select-wrap');
    const triggerIcon = wrap?.querySelector('.cs-filter-trigger-icon');
    const triggerLabel = wrap?.querySelector('.cs-filter-trigger-label');
    if (triggerIcon) triggerIcon.innerHTML = filterIconMarkup(select);
    if (triggerLabel) triggerLabel.textContent = select.options[select.selectedIndex].text;
    wrap?.querySelectorAll('.cs-filter-option').forEach(option => {
      option.classList.toggle('is-selected', option.dataset.value === select.value);
    });
  }

  function enhanceFilterSelects() {
    ['csFilterTipo', 'csFilterCanal', 'csFilterEstado'].forEach(id => {
      const select = fEl(id);
      const wrap = select?.closest('.cs-filter-select-wrap');
      if (!select || !wrap || wrap.classList.contains('is-enhanced')) return;

      wrap.classList.add('is-enhanced');
      const trigger = document.createElement('button');
      trigger.type = 'button';
      trigger.className = 'cs-filter-trigger';
      trigger.setAttribute('aria-haspopup', 'listbox');
      trigger.setAttribute('aria-expanded', 'false');
      trigger.innerHTML = '<span class="cs-filter-trigger-icon"></span><span class="cs-filter-trigger-label"></span><svg class="cs-filter-trigger-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>';

      const menu = document.createElement('div');
      menu.className = 'cs-filter-menu';
      menu.setAttribute('role', 'listbox');
      Array.from(select.options).forEach(option => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'cs-filter-option';
        item.dataset.value = option.value;
        item.innerHTML = '<span class="cs-filter-option-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + (filterIcons[id]?.[option.value] ?? '') + '</svg></span><span>' + option.text + '</span>';
        item.addEventListener('click', function(){
          select.value = option.value;
          wrap.classList.remove('is-open');
          trigger.setAttribute('aria-expanded', 'false');
          select.dispatchEvent(new Event('change', { bubbles: true }));
        });
        menu.appendChild(item);
      });

      trigger.addEventListener('click', function(){
        const isOpen = wrap.classList.toggle('is-open');
        trigger.setAttribute('aria-expanded', String(isOpen));
      });
      wrap.append(trigger, menu);
      syncFilterIcon(select);
    });
  }

  function syncFilterIcons() {
    enhanceFilterSelects();
    ['csFilterTipo', 'csFilterCanal', 'csFilterEstado'].forEach(id => {
      const select = fEl(id);
      if (select) syncFilterIcon(select);
    });
  }

  function restoreFilterValues() {
    const params = new URLSearchParams(window.location.search);
    const fq = fEl('csFilterQ');      if (fq      && params.get('q'))      fq.value      = params.get('q');
    const ft = fEl('csFilterTipo');   if (ft      && params.get('tipo'))   ft.value      = params.get('tipo');
    const fc = fEl('csFilterCanal');  if (fc      && params.get('canal'))  fc.value      = params.get('canal');
    const fe = fEl('csFilterEstado'); if (fe      && params.get('estado')) fe.value      = params.get('estado');
    syncFilterIcons();
  }

  async function applyFilters() {
    const params = getFilterParams();
    const base = window.location.pathname;
    const url  = base + (params.toString() ? '?' + params.toString() : '');

    try {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } });
      if (!res.ok) return;
      const html = await res.text();
      const tmp  = document.createElement('div');
      tmp.innerHTML = html;
      const newWrap = tmp.querySelector('#csListaWrap');
      if (newWrap && listaWrap) {
        listaWrap.innerHTML = newWrap.innerHTML;
        restoreFilterValues();
        bindDeleteButtons();
        bindViewButtons();
        bindEditButtons();
        bindPaginationLinks();
        window.history.replaceState({}, '', url);
      }
    } catch {}
  }

  // Delegación de eventos en document para sobrevivir reemplazos de innerHTML
  document.addEventListener('input', (e) => {
    if (e.target.id === 'csFilterQ') {
      clearTimeout(filterTimer);
      filterTimer = setTimeout(applyFilters, 380);
    }
  });
  document.addEventListener('change', (e) => {
    if (['csFilterTipo','csFilterCanal','csFilterEstado'].includes(e.target.id)) {
      syncFilterIcon(e.target);
      applyFilters();
    }
  });
  document.addEventListener('click', (e) => {
    document.querySelectorAll('.cs-filter-select-wrap.is-open').forEach(wrap => {
      if (!wrap.contains(e.target)) {
        wrap.classList.remove('is-open');
        wrap.querySelector('.cs-filter-trigger')?.setAttribute('aria-expanded', 'false');
      }
    });
    if (e.target.closest('#csFilterClear')) {
      const fq = fEl('csFilterQ');      if (fq)  fq.value  = '';
      const ft = fEl('csFilterTipo');   if (ft)  ft.value  = '';
      const fc = fEl('csFilterCanal');  if (fc)  fc.value  = '';
      const fe = fEl('csFilterEstado'); if (fe)  fe.value  = '';
      applyFilters();
    }
  });

  syncFilterIcons();

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
