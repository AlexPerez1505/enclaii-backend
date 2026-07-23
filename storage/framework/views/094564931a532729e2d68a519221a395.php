<script>
(function(){
  const dropzone = document.getElementById('ipDropzone');
  const input = document.getElementById('ipFileInput');
  const grid = document.getElementById('ipPreviewGrid');
  const actions = document.getElementById('ipActions');
  const empty = document.getElementById('ipEmpty');
  const clearBtn = document.getElementById('ipClearBtn');
  const importBtn = document.getElementById('ipImportBtn');
  const countSpan = document.getElementById('ipCount');
  let files = [];

  function updateUI(){
    grid.innerHTML = '';
    if (files.length === 0){
      actions.style.display = 'none';
      empty.style.display = 'block';
      importBtn.disabled = true;
      countSpan.textContent = '0';
      return;
    }
    empty.style.display = 'none';
    actions.style.display = 'flex';
    importBtn.disabled = false;
    countSpan.textContent = files.length;
    files.forEach(function(file, idx){
      const item = document.createElement('div');
      item.className = 'ip-preview-item';
      const url = URL.createObjectURL(file);
      if (file.type.startsWith('image/')){
        item.innerHTML = '<img src="'+url+'" alt=""><div class="ip-file-name">'+file.name+'</div>';
      } else if (file.type.startsWith('video/')){
        item.innerHTML = '<video src="'+url+'"></video><div class="ip-file-name">'+file.name+'</div>';
      } else {
        item.innerHTML = '<div class="ip-file-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><span>Archivo</span></div><div class="ip-file-name">'+file.name+'</div>';
      }
      const remove = document.createElement('button');
      remove.className = 'ip-preview-remove';
      remove.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
      remove.type = 'button';
      remove.addEventListener('click', function(e){
        e.stopPropagation();
        files.splice(idx, 1);
        updateUI();
      });
      item.style.cursor = 'pointer';
      item.addEventListener('click', function(e){
        if (e.target === remove || e.target.closest && e.target.closest('.ip-preview-remove')) return;
        openLightbox(file, url);
      });
      item.appendChild(remove);
      grid.appendChild(item);
    });
  }

  function fileKey(file){
    return file.name + '|' + file.size + '|' + (file.lastModified || 0);
  }

  function addFiles(newFiles){
    var existing = new Set(files.map(fileKey));
    Array.from(newFiles).forEach(function(file){
      if (file.type.startsWith('image/') || file.type.startsWith('video/')) {
        var key = fileKey(file);
        if (!existing.has(key)) {
          files.push(file);
          existing.add(key);
        }
      }
    });
    updateUI();
  }

  dropzone.addEventListener('click', function(){ input.click(); });
  input.addEventListener('change', function(){ addFiles(this.files); this.value = ''; });

  clearBtn?.addEventListener('click', function(){
    files = [];
    updateUI();
  });

  importBtn?.addEventListener('click', async function(){
    if (files.length === 0 || importBtn.disabled) return;

    const originalContent = importBtn.innerHTML;
    importBtn.disabled = true;
    importBtn.textContent = 'Finalizando estudio...';

    const formData = new FormData();
    formData.append('paciente_id', '<?php echo e($paciente->id); ?>');
    files.forEach(function(file){ formData.append('files[]', file); });

    try {
      const response = await fetch('<?php echo e(route('nuevo-estudio.importar.store')); ?>', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: formData
      });
      const data = await response.json().catch(function(){ return {}; });

      if (!response.ok || !data.ok) {
        const validationMessage = data.errors ? Object.values(data.errors).flat().join('\n') : '';
        throw new Error(validationMessage || data.message || 'No se pudo finalizar el estudio.');
      }

      window.location.href = data.redirect;
    } catch (error) {
      alert(error.message || 'No se pudo finalizar el estudio.');
      importBtn.disabled = false;
      importBtn.innerHTML = originalContent;
    }
  });

  function openLightbox(file, url){
    const existing = document.getElementById('ipLightbox');
    if (existing) existing.remove();
    const overlay = document.createElement('div');
    overlay.id = 'ipLightbox';
    overlay.className = 'ip-lightbox';
    overlay.innerHTML = '<button class="ip-lightbox-close" type="button" aria-label="Cerrar"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button><div class="ip-lightbox-inner"></div>';
    const inner = overlay.querySelector('.ip-lightbox-inner');
    let media;
    if (file.type.startsWith('image/')) {
      media = document.createElement('img');
      media.src = url;
      media.alt = file.name;
    } else if (file.type.startsWith('video/')) {
      media = document.createElement('video');
      media.src = url;
      media.controls = true;
    } else {
      media = document.createElement('div');
      media.textContent = file.name;
    }
    inner.appendChild(media);
    document.body.appendChild(overlay);
    overlay.querySelector('.ip-lightbox-close').addEventListener('click', closeLightbox);
    overlay.addEventListener('click', function(e){ if (e.target === overlay) closeLightbox(); });
    document.addEventListener('keydown', function keydown(e){ if (e.key === 'Escape') { closeLightbox(); document.removeEventListener('keydown', keydown); } });
  }

  function closeLightbox(){
    const overlay = document.getElementById('ipLightbox');
    if (overlay) overlay.remove();
  }

  updateUI();
})();
</script>
<?php /**PATH C:\Users\gmedi\enclaii-backend\resources\views/estudios/importar/importar-js.blade.php ENDPATH**/ ?>