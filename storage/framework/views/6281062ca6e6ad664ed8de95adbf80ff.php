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
      item.appendChild(remove);
      grid.appendChild(item);
    });
  }

  function addFiles(newFiles){
    Array.from(newFiles).forEach(function(file){
      if (file.type.startsWith('image/') || file.type.startsWith('video/')) files.push(file);
    });
    updateUI();
  }

  dropzone.addEventListener('click', function(){ input.click(); });
  input.addEventListener('change', function(){ addFiles(this.files); this.value = ''; });

  ['dragenter','dragover','dragleave','drop'].forEach(function(evt){
    dropzone.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); });
  });
  ['dragenter','dragover'].forEach(function(evt){
    dropzone.addEventListener(evt, function(){ dropzone.classList.add('dragover'); });
  });
  ['dragleave','drop'].forEach(function(evt){
    dropzone.addEventListener(evt, function(){ dropzone.classList.remove('dragover'); });
  });
  dropzone.addEventListener('drop', function(e){ addFiles(e.dataTransfer.files); });

  clearBtn?.addEventListener('click', function(){
    files = [];
    updateUI();
  });

  importBtn?.addEventListener('click', function(){
    if (files.length === 0) return;
    alert('Importando ' + files.length + ' archivo(s)...');
  });

  updateUI();
})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\importar\importar-js.blade.php ENDPATH**/ ?>