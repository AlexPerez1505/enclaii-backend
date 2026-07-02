<script>
(function () {

  /* Fecha por defecto */
  var now  = new Date();
  var pad  = function(n){ return String(n).padStart(2,'0'); };
  var fechaNac = document.getElementById('fecha_nac');
  var fechaReg = document.getElementById('fecha_registro');
  if (fechaNac && !fechaNac.value) fechaNac.value = '1998-12-25';
  if (fechaReg) fechaReg.value = now.getFullYear()+'-'+pad(now.getMonth()+1)+'-'+pad(now.getDate());

  /* Foto menu (formulario oculto, solo por seguridad) */
  var fotoMenu   = document.getElementById('npFotoMenu');
  var btnFotoMenu= document.getElementById('npBtnFotoMenu');
  var btnFotoTxt = document.getElementById('npBtnFotoTxt');
  var btnGaleria = document.getElementById('npBtnGaleria');
  var btnCamara  = document.getElementById('npBtnCamara');
  var fotoInput  = document.getElementById('npFotoInput');
  var fotoCamera = document.getElementById('npFotoCamera');

  if (btnFotoMenu && fotoMenu){
    btnFotoMenu.addEventListener('click', function(e){
      e.stopPropagation();
      fotoMenu.style.display = fotoMenu.style.display === 'none' ? 'block' : 'none';
    });
    document.addEventListener('click', function(){ fotoMenu.style.display = 'none'; });
  }
  if (btnGaleria && fotoInput){
    btnGaleria.addEventListener('click', function(){ fotoMenu.style.display = 'none'; fotoInput.click(); });
  }
  if (btnCamara && fotoCamera){
    btnCamara.addEventListener('click', function(){ fotoMenu.style.display = 'none'; fotoCamera.click(); });
  }

  function applyPreview(file){
    if (!file) return;
    var img = document.getElementById('npFotoPreview');
    var ph  = document.getElementById('npFotoPh');
    if (!img || !ph) return;
    var r = new FileReader();
    r.onload = function(e){
      img.src = e.target.result;
      img.style.display = 'block';
      ph.style.display  = 'none';
      if (btnFotoTxt) btnFotoTxt.textContent = 'Cambiar foto';
    };
    r.readAsDataURL(file);
  }
  if (fotoInput) fotoInput.addEventListener('change', function(){ applyPreview(this.files[0]); });
  if (fotoCamera) fotoCamera.addEventListener('change', function(){ applyPreview(this.files[0]); });

  /* Buscador de pacientes (solo al abrir desde el dashboard) */
  function setupPacienteSearch(){
    var PACIENTES = window.__NP_PACIENTES || [];
    var input   = document.getElementById('npSearch');
    var results = document.getElementById('npResults');
    var list    = document.getElementById('npResList');
    if (!input || !results || !list) return;

    function normalize(str){
      return (str || '').toString().normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    function renderItems(items){
      list.innerHTML = '';
      if (items.length === 0){
        list.innerHTML = '<div class="np-res-empty">No se encontraron pacientes.</div>';
        return;
      }
      items.forEach(function(p){
        var el = document.createElement('div');
        el.className = 'np-res-item';
        var avatar = p.foto
          ? '<img src="' + p.foto + '" alt="' + p.nombre + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%">'
          : p.iniciales;
        var meta = [p.folio ? 'Folio ' + p.folio : '', p.edad ? p.edad + ' años' : '', p.sexo, p.telefono].filter(Boolean).join(' · ');
        el.innerHTML = '<div class="np-res-av">' + avatar + '</div>'
          + '<div class="np-res-info"><div class="np-res-name">' + p.nombre + '</div>'
          + '<div class="np-res-meta">' + (meta || 'Sin información adicional') + '</div></div>';
        el.addEventListener('click', function(){
          window.location.href = '{{ route('nuevo-estudio') }}?paciente=' + encodeURIComponent(p.id);
        });
        list.appendChild(el);
      });
    }

    function search(q){
      var term = normalize(q).trim();
      if (!term){ results.classList.remove('open'); return; }
      var filtered = PACIENTES.filter(function(p){
        return normalize(p.nombre).includes(term)
          || normalize(p.folio).includes(term)
          || normalize(p.telefono).includes(term)
          || normalize(p.email).includes(term);
      });
      renderItems(filtered);
      results.classList.add('open');
    }

    var debounce;
    input.addEventListener('input', function(){
      clearTimeout(debounce);
      var val = this.value;
      debounce = setTimeout(function(){ search(val); }, 150);
    });
    input.addEventListener('focus', function(){ if (this.value.trim()) search(this.value); });
    document.addEventListener('click', function(e){
      if (!e.target.closest('#npSearchBar') && !e.target.closest('#npResults')){
        results.classList.remove('open');
      }
    });
  }

  function showForm(){
    var emptyState = document.getElementById('npEmptyState');
    if (emptyState) emptyState.style.display = 'none';
    document.getElementById('npFormLayout').style.display = 'grid';
    document.querySelectorAll('.np-tab.hidden').forEach(function(t){ t.classList.remove('hidden'); });
    const topBack = document.getElementById('npBackToPatientsTop');
    const topNew = document.getElementById('npNewStudyBtn');
    if (topBack) topBack.classList.add('visible');
    if (topNew) topNew.classList.add('visible');
  }


  /* Filtro de archivos en galeria */
  function setupMediaFilter(inputId, containerSelector){
    var input = document.getElementById(inputId);
    if (!input) return;
    var container = document.querySelector(containerSelector);
    if (!container) return;
    var cards = container.querySelectorAll('.pa-card');
    var empty = document.getElementById('npGalEmpty');
    input.addEventListener('input', function(){
      var q = input.value.trim().toLowerCase();
      var shown = 0;
      cards.forEach(function(card){
        var ok = !q || (card.dataset.title || '').toLowerCase().includes(q) || (card.dataset.kind || '').toLowerCase().includes(q);
        card.style.display = ok ? '' : 'none';
        if (ok) shown++;
      });
      if (empty) empty.style.display = shown ? 'none' : 'block';
    });
    input.addEventListener('keydown', function(e){
      if (e.key === 'Escape'){ input.value = ''; input.dispatchEvent(new Event('input')); }
    });
  }
  setupMediaFilter('npGalSearch', '#tab-galeria');

  /* Si hay paciente (abierto desde la seccion del paciente) se cargan sus datos.
     Si no (abierto desde el boton del dashboard) se muestra el buscador. */
  @if($paciente)
  showForm();
  @else
  setupPacienteSearch();
  @endif

  /* Modal Nuevo Estudio */
  const nsBackdrop = document.getElementById('nsModalBackdrop');
  const nsClose = document.getElementById('nsModalClose');
  const nsBtnTop = document.getElementById('npNewStudyBtn');
  const nsBtnGal = document.getElementById('npNewStudyBtnGal');
  const nsBtns = [nsBtnTop, nsBtnGal];

  function openNsModal() {
    if (nsBackdrop) {
      nsBackdrop.classList.add('open');
      document.body.style.overflow = 'hidden';
    }
  }
  function closeNsModal() {
    if (nsBackdrop) {
      nsBackdrop.classList.remove('open');
      document.body.style.overflow = '';
    }
  }

  nsBtns.forEach(function(btn){
    btn?.addEventListener('click', function(e){
      e.preventDefault();
      openNsModal();
    });
  });
  nsClose?.addEventListener('click', closeNsModal);
  nsBackdrop?.addEventListener('click', function(e){
    if (e.target === nsBackdrop) closeNsModal();
  });
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape' && nsBackdrop?.classList.contains('open')) closeNsModal();
  });

  /* Precarga datos del paciente (viene de Pacientes > Iniciar estudio) */
  @if($paciente)
  (function(){
    var elSearch = document.getElementById('npSearch');
    if (elSearch) elSearch.value = @json($paciente->nombre_completo);

    var emptyState = document.getElementById('npEmptyState');
    var formLayout = document.getElementById('npFormLayout');
    var npResults  = document.getElementById('npResults');
    if (emptyState) emptyState.style.display = 'none';
    if (formLayout) formLayout.style.display = 'grid';
    if (npResults)  npResults.classList.remove('open');
    document.querySelectorAll('.np-tab.hidden').forEach(function(t){ t.classList.remove('hidden'); });
    var topBack = document.getElementById('npBackToPatientsTop');
    var topNew  = document.getElementById('npNewStudyBtn');
    if (topBack) topBack.classList.add('visible');
    if (topNew)  topNew.classList.add('visible');
  })();
  @endif

  /* Pestañas */
  document.querySelectorAll('.np-tab[data-tab]').forEach(function(tab){
    tab.addEventListener('click', function(){
      document.querySelectorAll('.np-tab[data-tab]').forEach(function(t){ t.classList.remove('active'); });
      document.querySelectorAll('.np-tab-panel').forEach(function(p){ p.classList.remove('active'); });
      tab.classList.add('active');
      document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
    });
  });

  /* Modal de verificación de dispositivo */
  var dispositivoModalBackdrop = document.getElementById('dispositivoModalBackdrop');
  var dispositivoModalClose = document.getElementById('dispositivoModalClose');
  var selDispositivo = document.getElementById('selDispositivo');
  var dispositivoNombre = document.getElementById('dispositivoNombre');
  var chkDispositivoConectado = document.getElementById('chkDispositivoConectado');
  var dispositivoStatusIcon = document.getElementById('dispositivoStatusIcon');
  var dispositivoStatusText = document.getElementById('dispositivoStatusText');
  var btnComenzarGrabar = document.getElementById('btnComenzarGrabar');

  function openDispositivoModal() {
    if (dispositivoModalBackdrop) dispositivoModalBackdrop.classList.add('open');
    updateDispositivoStatus();
  }
  function closeDispositivoModal() {
    if (dispositivoModalBackdrop) dispositivoModalBackdrop.classList.remove('open');
  }
  function updateDispositivoStatus() {
    if (!chkDispositivoConectado) return;
    var conectado = chkDispositivoConectado.checked;
    if (conectado) {
      dispositivoStatusIcon.style.background = 'rgba(34,197,94,.12)';
      dispositivoStatusIcon.style.borderColor = 'rgba(34,197,94,.3)';
      dispositivoStatusIcon.style.color = '#16a34a';
      dispositivoStatusText.textContent = 'Dispositivo conectado';
      btnComenzarGrabar.style.opacity = '1';
      btnComenzarGrabar.style.pointerEvents = 'auto';
    } else {
      dispositivoStatusIcon.style.background = 'rgba(220,38,38,.12)';
      dispositivoStatusIcon.style.borderColor = 'rgba(220,38,38,.3)';
      dispositivoStatusIcon.style.color = '#dc2626';
      dispositivoStatusText.textContent = 'Dispositivo no conectado';
      btnComenzarGrabar.style.opacity = '.5';
      btnComenzarGrabar.style.pointerEvents = 'none';
    }
  }

  window.openDispositivoModal = openDispositivoModal;
  window.closeDispositivoModal = closeDispositivoModal;

  dispositivoModalClose?.addEventListener('click', closeDispositivoModal);
  dispositivoModalBackdrop?.addEventListener('click', function(e){
    if (e.target === dispositivoModalBackdrop) closeDispositivoModal();
  });
  chkDispositivoConectado?.addEventListener('change', updateDispositivoStatus);
  selDispositivo?.addEventListener('change', function(){
    if (dispositivoNombre) dispositivoNombre.textContent = selDispositivo.value;
  });

})();
</script>
