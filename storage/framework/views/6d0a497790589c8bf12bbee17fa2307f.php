<script>
(function(){
  /* Play / Pausa toggle (simulado) */
  let playing = false;
  const speeds = ['0.5x','0.75x','1.0x','1.25x','1.5x','2.0x'];
  let sIdx = 2;

  function togglePlay(){
    playing = !playing;
    [document.getElementById('vvPlayBig'), document.getElementById('vvPlayBtn')]
      .forEach(btn => {
        btn.querySelector('.play-icon').style.display  = playing ? 'none'  : '';
        btn.querySelector('.pause-icon').style.display = playing ? ''      : 'none';
      });
    document.getElementById('vvCenterLabel').style.display = playing ? 'none' : '';
  }

  document.getElementById('vvPlayBig').addEventListener('click', togglePlay);
  document.getElementById('vvPlayBtn').addEventListener('click', togglePlay);

  /* Velocidad */
  document.getElementById('vvSpeed').addEventListener('click', function(){
    sIdx = (sIdx + 1) % speeds.length;
    this.textContent = speeds[sIdx];
  });

  /* Selección de miniatura */
  document.querySelectorAll('.vv-cap-item').forEach(item => {
    item.addEventListener('click', function(){
      document.querySelectorAll('.vv-cap-item').forEach(i => i.classList.remove('sel'));
      this.classList.add('sel');
      document.getElementById('vvTime').textContent = this.dataset.ts + ' / 00:15:42';
    });
  });
  /* ── Modal descarga video ── */
  const vvDlOv = document.getElementById('vvDlOverlay');
  function abrirVvDl(){ vvDlOv.classList.add('open'); document.body.style.overflow='hidden'; }
  function cerrarVvDl(){ vvDlOv.classList.remove('open'); document.body.style.overflow=''; }

  document.querySelector('.vv-btn.dl').addEventListener('click', abrirVvDl);
  document.getElementById('vvDlClose') .addEventListener('click', cerrarVvDl);
  document.getElementById('vvDlCancel').addEventListener('click', cerrarVvDl);
  vvDlOv.addEventListener('click', function(e){ if(e.target===this) cerrarVvDl(); });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') cerrarVvDl(); });

  /* Rango */
  document.querySelectorAll('.vv-rng-row').forEach(row => {
    row.addEventListener('click', function(){
      document.querySelectorAll('.vv-rng-row').forEach(r => r.classList.remove('sel'));
      this.classList.add('sel');
      const custom = document.getElementById('vvRngCustom');
      custom.classList.toggle('show', this.dataset.rng === 'custom');
    });
  });

  /* Calidad → resolución */
  const qualRes = {'1080':'Resolución: 1920 x 1080','720':'Resolución: 1280 x 720','480':'Resolución: 854 x 480'};
  document.getElementById('vvDlQual').addEventListener('change', function(){
    document.getElementById('vvQualRes').textContent = qualRes[this.value];
  });

  /* Formato */
  document.querySelectorAll('.vv-fmt-card').forEach(card => {
    card.addEventListener('click', function(){
      document.querySelectorAll('.vv-fmt-card').forEach(c => c.classList.remove('sel'));
      this.classList.add('sel');
    });
  });

  /* Checkboxes incluir */
  document.querySelectorAll('.vv-inc-row').forEach(row => {
    row.addEventListener('click', function(){ this.classList.toggle('checked'); });
  });

  /* Confirmar (simulado) */
  document.getElementById('vvDlConfirm').addEventListener('click', function(){
    this.textContent = 'Descargando...';
    this.style.background = 'var(--green)';
    setTimeout(() => {
      this.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Descargar video';
      this.style.background = '';
      cerrarVvDl();
    }, 2000);
  });

})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\galeria\vervideo\_scripts.blade.php ENDPATH**/ ?>