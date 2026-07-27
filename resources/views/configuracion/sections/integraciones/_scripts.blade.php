@push('scripts')
<script>
(function(){
  const modal = document.getElementById('intBackupModal');
  const form = document.getElementById('intBackupForm');
  const openButton = document.getElementById('intBackupOpen');
  const closeButton = document.getElementById('intBackupClose');
  const cancelButton = document.getElementById('intBackupCancel');
  const submitButton = document.getElementById('intBackupSubmit');
  const scopes = document.getElementById('intBackupScopes');
  const csrf = form?.querySelector('input[name="_token"]')?.value;
  const restoreModal = document.getElementById('intRestoreModal');
  const restoreMessage = document.getElementById('intRestoreMessage');
  const restoreConfirmBtn = document.getElementById('intRestoreConfirm');
  const restoreCancelBtn = document.getElementById('intRestoreCancel');
  const deleteModal = document.getElementById('intDeleteModal');
  const deleteMessage = document.getElementById('intDeleteMessage');
  const deleteConfirmBtn = document.getElementById('intDeleteConfirm');
  const deleteCancelBtn = document.getElementById('intDeleteCancel');
  const configUrl = @json(route('configuracion'));

  if (!modal || !form || !csrf) return;

  function toast(message, isError = false) {
    let element = document.getElementById('intBackupToast');
    if (!element) {
      element = document.createElement('div');
      element.id = 'intBackupToast';
      element.style.cssText = 'position:fixed;left:50%;bottom:24px;z-index:9999;'
        + 'transform:translate(-50%,12px);padding:11px 17px;border-radius:11px;'
        + 'background:var(--card);border:1px solid var(--stroke-strong);color:var(--txt);'
        + 'font-size:12.5px;font-weight:700;box-shadow:0 16px 40px rgba(0,0,0,.4);'
        + 'opacity:0;transition:opacity .2s,transform .2s;pointer-events:none';
      document.body.appendChild(element);
    }
    element.textContent = message;
    element.style.borderColor = isError ? 'rgba(255,90,110,.45)' : 'rgba(61,220,151,.4)';
    requestAnimationFrame(() => {
      element.style.opacity = '1';
      element.style.transform = 'translate(-50%,0)';
    });
    clearTimeout(element._timer);
    element._timer = setTimeout(() => {
      element.style.opacity = '0';
      element.style.transform = 'translate(-50%,12px)';
    }, 2600);
  }

  function openModal() {
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('intBackupName')?.select(), 80);
  }

  function closeModal() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  function reloadIntegrations(message) {
    try { sessionStorage.setItem('enclaii-backup-message', message); } catch (error) {}
    window.location.assign(configUrl + '?tab=integraciones');
  }

  async function request(url, method, body = null) {
    const response = await fetch(url, {
      method,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: body ? JSON.stringify(body) : null,
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
      const validation = data.errors ? Object.values(data.errors).flat()[0] : null;
      throw new Error(validation || data.message || 'No se pudo completar la operación.');
    }
    return data;
  }

  function confirmRestore(name) {
    return new Promise(resolve => {
      restoreMessage.textContent = `¿Restaurar “${name}”?`;
      restoreModal.classList.add('open');
      restoreModal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';

      const onConfirm = () => cleanup(false);
      const onCancel = () => cleanup(true);

      function cleanup(cancel) {
        restoreConfirmBtn.removeEventListener('click', onConfirm);
        restoreCancelBtn.removeEventListener('click', onCancel);
        restoreModal.removeEventListener('click', onOverlay);
        document.removeEventListener('keydown', onKeydown);
        restoreModal.classList.remove('open');
        restoreModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        resolve(!cancel);
      }

      function onOverlay(event) { if (event.target === restoreModal) onCancel(); }
      function onKeydown(event) { if (event.key === 'Escape' && restoreModal.classList.contains('open')) onCancel(); }

      restoreConfirmBtn.addEventListener('click', onConfirm);
      restoreCancelBtn.addEventListener('click', onCancel);
      restoreModal.addEventListener('click', onOverlay);
      document.addEventListener('keydown', onKeydown);
    });
  }

  function confirmDelete(name) {
    return new Promise(resolve => {
      deleteMessage.textContent = `¿Eliminar definitivamente la copia "${name}"?`;
      deleteModal.classList.add('open');
      deleteModal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';

      const onConfirm = () => cleanup(false);
      const onCancel = () => cleanup(true);

      function cleanup(cancel) {
        deleteConfirmBtn.removeEventListener('click', onConfirm);
        deleteCancelBtn.removeEventListener('click', onCancel);
        deleteModal.removeEventListener('click', onOverlay);
        document.removeEventListener('keydown', onKeydown);
        deleteModal.classList.remove('open');
        deleteModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        resolve(!cancel);
      }

      function onOverlay(event) { if (event.target === deleteModal) onCancel(); }
      function onKeydown(event) { if (event.key === 'Escape' && deleteModal.classList.contains('open')) onCancel(); }

      deleteConfirmBtn.addEventListener('click', onConfirm);
      deleteCancelBtn.addEventListener('click', onCancel);
      deleteModal.addEventListener('click', onOverlay);
      document.addEventListener('keydown', onKeydown);
    });
  }

  openButton?.addEventListener('click', openModal);
  closeButton?.addEventListener('click', closeModal);
  cancelButton?.addEventListener('click', closeModal);
  modal.addEventListener('click', event => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && modal.classList.contains('open')) closeModal();
  });

  form.querySelectorAll('input[name="mode"]').forEach(input => {
    input.addEventListener('change', () => {
      scopes?.classList.toggle('show', input.value === 'custom' && input.checked);
    });
  });

  form.addEventListener('submit', async event => {
    event.preventDefault();
    const mode = form.querySelector('input[name="mode"]:checked')?.value || 'complete';
    const selectedScopes = [...form.querySelectorAll('input[name="scope[]"]:checked')]
      .map(input => input.value);

    if (mode === 'custom' && selectedScopes.length === 0) {
      toast('Selecciona al menos una sección.', true);
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Creando...';

    try {
      const data = await request(form.dataset.storeUrl, 'POST', {
        name: document.getElementById('intBackupName').value.trim(),
        mode,
        scope: selectedScopes,
      });
      reloadIntegrations(data.message);
    } catch (error) {
      toast(error.message, true);
      submitButton.disabled = false;
      submitButton.textContent = 'Crear copia';
    }
  });

  document.querySelectorAll('[data-backup-restore]').forEach(button => {
    button.addEventListener('click', async () => {
      const name = button.dataset.backupName;
      const accepted = await confirmRestore(name);
      if (!accepted) return;

      button.disabled = true;
      try {
        const url = form.dataset.restoreUrl.replace('__ID__', button.dataset.backupRestore);
        const data = await request(url, 'POST');
        reloadIntegrations(data.message);
      } catch (error) {
        toast(error.message, true);
        button.disabled = false;
      }
    });
  });

  document.querySelectorAll('[data-backup-delete]').forEach(button => {
    button.addEventListener('click', async () => {
      const name = button.dataset.backupName;
      const accepted = await confirmDelete(name);
      if (!accepted) return;

      button.disabled = true;
      try {
        const url = form.dataset.deleteUrl.replace('__ID__', button.dataset.backupDelete);
        const data = await request(url, 'DELETE');
        reloadIntegrations(data.message);
      } catch (error) {
        toast(error.message, true);
        button.disabled = false;
      }
    });
  });

  try {
    const pendingMessage = sessionStorage.getItem('enclaii-backup-message');
    if (pendingMessage) {
      sessionStorage.removeItem('enclaii-backup-message');
      setTimeout(() => toast(pendingMessage), 120);
    }
  } catch (error) {}

  /* Firma digital */
  const signatureModal = document.getElementById('intSignatureModal');
  const signatureView = document.getElementById('intSignatureView');
  const signatureEdit = document.getElementById('intSignatureEdit');
  const signatureClose = document.getElementById('intSignatureClose');
  const signatureCancel = document.getElementById('intSignatureCancel');
  const signatureStartEdit = document.getElementById('intSignatureStartEdit');
  const signatureSave = document.getElementById('intSignatureSave');
  const signatureDelete = document.getElementById('intSignatureDelete');
  const signatureEditor = document.getElementById('intSignatureEditor');
  const signatureCanvas = document.getElementById('intSignatureCanvas');
  const signatureHint = document.getElementById('intSignatureHint');
  const signatureClear = document.getElementById('intSignatureClear');
  const signatureFile = document.getElementById('intSignatureFile');
  const signatureUploadPreview = document.getElementById('intSignatureUploadPreview');
  let signatureTab = 'draw';
  let signatureHasInk = false;
  let signatureDrawing = false;
  let signatureUploadUrl = null;

  function openSignatureModal(editing = false) {
    if (!signatureModal) return;
    signatureModal.classList.add('open');
    signatureModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setSignatureEditing(editing);
  }

  function closeSignatureModal() {
    if (!signatureModal) return;
    signatureModal.classList.remove('open');
    signatureModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    setSignatureEditing(false);
  }

  function setSignatureEditing(editing) {
    signatureEditor?.classList.toggle('open', editing);
    if (signatureStartEdit) signatureStartEdit.style.display = editing ? 'none' : '';
    if (signatureSave) signatureSave.style.display = editing ? '' : 'none';
    if (signatureCancel) signatureCancel.textContent = editing ? 'Cancelar' : 'Cerrar';
    if (editing) clearSignatureCanvas();
  }

  function signaturePoint(event) {
    const rect = signatureCanvas.getBoundingClientRect();
    return {
      x: (event.clientX - rect.left) * (signatureCanvas.width / rect.width),
      y: (event.clientY - rect.top) * (signatureCanvas.height / rect.height),
    };
  }

  function beginSignature(event) {
    if (!signatureCanvas || signatureTab !== 'draw') return;
    signatureDrawing = true;
    signatureCanvas.setPointerCapture(event.pointerId);
    const point = signaturePoint(event);
    const context = signatureCanvas.getContext('2d');
    context.beginPath();
    context.moveTo(point.x, point.y);
    event.preventDefault();
  }

  function drawSignature(event) {
    if (!signatureDrawing || !signatureCanvas) return;
    const point = signaturePoint(event);
    const context = signatureCanvas.getContext('2d');
    context.lineWidth = 7;
    context.lineCap = 'round';
    context.lineJoin = 'round';
    context.strokeStyle = '#111827';
    context.lineTo(point.x, point.y);
    context.stroke();
    signatureHasInk = true;
    if (signatureHint) signatureHint.style.display = 'none';
    event.preventDefault();
  }

  function endSignature(event) {
    if (!signatureDrawing) return;
    signatureDrawing = false;
    if (signatureCanvas?.hasPointerCapture(event.pointerId)) {
      signatureCanvas.releasePointerCapture(event.pointerId);
    }
  }

  function clearSignatureCanvas() {
    if (!signatureCanvas) return;
    signatureCanvas.getContext('2d').clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
    signatureHasInk = false;
    if (signatureHint) signatureHint.style.display = '';
  }

  signatureCanvas?.addEventListener('pointerdown', beginSignature);
  signatureCanvas?.addEventListener('pointermove', drawSignature);
  signatureCanvas?.addEventListener('pointerup', endSignature);
  signatureCanvas?.addEventListener('pointercancel', endSignature);
  signatureCanvas?.addEventListener('pointerleave', endSignature);
  signatureClear?.addEventListener('click', clearSignatureCanvas);

  document.querySelectorAll('[data-signature-tab]').forEach(tab => {
    tab.addEventListener('click', () => {
      signatureTab = tab.dataset.signatureTab;
      document.querySelectorAll('[data-signature-tab]').forEach(item => item.classList.toggle('active', item === tab));
      document.querySelectorAll('[data-signature-panel]').forEach(panel => {
        panel.classList.toggle('active', panel.dataset.signaturePanel === signatureTab);
      });
    });
  });

  signatureFile?.addEventListener('change', () => {
    const file = signatureFile.files?.[0];
    if (!file) return;
    if (!['image/png', 'image/jpeg', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) {
      signatureFile.value = '';
      toast('Selecciona una imagen PNG, JPG o WEBP de máximo 2 MB.', true);
      return;
    }
    if (signatureUploadUrl) URL.revokeObjectURL(signatureUploadUrl);
    signatureUploadUrl = URL.createObjectURL(file);
    signatureUploadPreview.src = signatureUploadUrl;
    signatureUploadPreview.style.display = 'block';
  });

  signatureView?.addEventListener('click', () => openSignatureModal(false));
  signatureEdit?.addEventListener('click', () => openSignatureModal(true));
  signatureStartEdit?.addEventListener('click', () => setSignatureEditing(true));
  signatureClose?.addEventListener('click', closeSignatureModal);
  signatureCancel?.addEventListener('click', () => {
    if (signatureEditor?.classList.contains('open')) setSignatureEditing(false);
    else closeSignatureModal();
  });
  signatureModal?.addEventListener('click', event => {
    if (event.target === signatureModal) closeSignatureModal();
  });

  signatureSave?.addEventListener('click', async () => {
    let file = null;
    let filename = 'firma.png';

    if (signatureTab === 'draw') {
      if (!signatureHasInk) {
        toast('Dibuja tu firma antes de guardarla.', true);
        return;
      }
      file = await new Promise(resolve => signatureCanvas.toBlob(resolve, 'image/png'));
    } else {
      file = signatureFile?.files?.[0] || null;
      filename = file?.name || filename;
      if (!file) {
        toast('Selecciona una imagen de firma.', true);
        return;
      }
    }

    const data = new FormData();
    data.append('signature', file, filename);
    signatureSave.disabled = true;
    signatureSave.textContent = 'Guardando...';

    try {
      const response = await fetch(signatureModal.dataset.storeUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: data,
      });
      const result = await response.json().catch(() => ({}));
      if (!response.ok) {
        const validation = result.errors ? Object.values(result.errors).flat()[0] : null;
        throw new Error(validation || result.message || 'No se pudo guardar la firma.');
      }
      reloadIntegrations(result.message);
    } catch (error) {
      toast(error.message, true);
      signatureSave.disabled = false;
      signatureSave.textContent = 'Guardar firma';
    }
  });

  signatureDelete?.addEventListener('click', async () => {
    if (!window.confirm('¿Eliminar definitivamente tu firma digital?')) return;
    signatureDelete.disabled = true;

    try {
      const result = await request(signatureModal.dataset.deleteUrl, 'DELETE');
      reloadIntegrations(result.message);
    } catch (error) {
      toast(error.message, true);
      signatureDelete.disabled = false;
    }
  });

  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && signatureModal?.classList.contains('open')) {
      closeSignatureModal();
    }
  });

})();
</script>
@endpush
