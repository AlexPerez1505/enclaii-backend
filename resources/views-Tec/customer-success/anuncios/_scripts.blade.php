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
    anuncios_internos: { cls: 'theme-anuncios_internos', badge: '📋 Comunicado Interno', icon: null },
    mejoras:          { cls: 'theme-mejoras',           badge: '🚀 Mejoras en Enclaii',  icon: null },
    mantenimiento:    { cls: 'theme-mantenimiento',     badge: '⚠️ Aviso de Mantenimiento', icon: '🔧' },
    politicas:        { cls: 'theme-politicas',         badge: '📄 Documento de Política', icon: null },
  };

  const BOILERPLATES = {
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

  tipoSelect.addEventListener('change', function(){
    const boilerplate = BOILERPLATES[this.value];
    if (!boilerplate) return;
    if (editor.innerHTML.trim() === '' || confirm('¿Reemplazar el contenido con la plantilla de "' + this.options[this.selectedIndex].text + '"?')) {
      editor.innerHTML = boilerplate;
      hiddenInput.value = boilerplate;
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
      fecha_publicacion: document.getElementById('csFecha').value || null,
    };

    try {
      const res = await fetch('/api/customer-success/anuncios', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(payload),
      });

      if (res.ok) {
        showAlert('Anuncio enviado correctamente.', 'success');
        form.reset();
        editor.innerHTML = '';
        setTimeout(() => location.reload(), 800);
      } else {
        const data = await res.json();
        showAlert(data.message || 'Error al publicar.', 'error');
      }
    } catch (err) {
      showAlert('Error de conexión.', 'error');
    }
  });

  document.querySelectorAll('.cs-delete').forEach(btn => {
    btn.addEventListener('click', async function(){
      const row = this.closest('tr');
      const id = row.dataset.id;
      if (!confirm('¿Eliminar este anuncio?')) return;

      try {
        const res = await fetch('/api/customer-success/anuncios/' + id, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
        });

        if (res.ok) {
          row.remove();
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

  // Calendario personalizado para fecha de publicación
  if (typeof flatpickr !== 'undefined') {
    flatpickr('#csFecha', {
      enableTime: true,
      dateFormat: 'Y-m-d\\TH:i',
      altInput: true,
      altFormat: 'd/m/Y h:i K',
      locale: 'es',
      time_24hr: false,
      minuteIncrement: 1,
      allowInput: true,
      disableMobile: false,
    });
  }
})();
</script>
