@php
$catIsClinicOwner = auth()->user()->clinica_rol === 'propietario';
@endphp

<div data-catalog="hospital">
  <div class="cat-hospital-card">

    <div class="cat-hospital-head">
      <div>
        <h2>Catálogos del sistema</h2>
        <p>Administra los catálogos que utiliza el sistema</p>
      </div>
      @if($catIsClinicOwner)
        <button type="button" id="catalogAddBtn" class="cat-add-btn">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
          </svg>
          Agregar personal
        </button>
      @endif
    </div>

    <div class="cat-tabs-bar">
      <nav class="cat-tabs" aria-label="Tabs">
        <button type="button" class="cat-tab active" data-tab="personal">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
          </svg>
          Personal
        </button>
        <button type="button" class="cat-tab" data-tab="procedimientos">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
          </svg>
          Procedimientos
        </button>
        <button type="button" class="cat-tab" data-tab="anestesiologo">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0Z" />
          </svg>
          Anestesiólogo
        
        <button type="button" class="cat-tab" data-tab="salas">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6v4.5H9v-4.5Zm0 6.75h6v4.5H9v-4.5Z" />
          </svg>
          Salas
        </button>
      </nav>
    </div>

    <div class="cat-panels">
      <div id="catalog-panel-personal" class="cat-panel">
        @include('configuracion.sections.integraciones.__Int_hospital_catalog.__personal.__personal_table')
      </div>
      <div id="catalog-panel-procedimientos" class="cat-panel hidden">
        @include('configuracion.sections.integraciones.__Int_hospital_catalog.__process.__process_table')
      </div>
      <div id="catalog-panel-anestesiologo" class="cat-panel hidden">
        @include('configuracion.sections.integraciones.__Int_hospital_catalog.__anesthesiologist.__table_anesthesiologist')
      </div>
      
      <div id="catalog-panel-salas" class="cat-panel hidden">
        @include('configuracion.sections.integraciones.__Int_hospital_catalog.__rooms.rooms_table')
      </div>
    </div>

    @include('configuracion.sections.integraciones.__Int_hospital_catalog.__process.__crud_process')
    @include('configuracion.sections.integraciones.__Int_hospital_catalog.__anesthesiologist.__crud_anesthesiologist')
    @include('configuracion.sections.integraciones.__Int_hospital_catalog.__rooms.__crud_rooms')
  </div>
</div>

<script>
(function(){
  const wrapper = document.querySelector('[data-catalog="hospital"]');
  if (!wrapper) return;

  const tabs = wrapper.querySelectorAll('.cat-tab');
  const panels = wrapper.querySelectorAll('.cat-panel');
  const addBtn = wrapper.querySelector('#catalogAddBtn');
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  const labels = {
    personal: 'Agregar personal',
    procedimientos: 'Agregar procedimiento',
    anestesiologo: 'Agregar anestesiólogo',
    estudios: 'Agregar estudio',
    salas: 'Agregar sala',
  };

  let activeTab = 'personal';

  function activateTab(name) {
    activeTab = name;
    tabs.forEach(tab => tab.classList.toggle('active', tab.dataset.tab === name));
    panels.forEach(panel => panel.classList.toggle('hidden', panel.id !== 'catalog-panel-' + name));
    if (addBtn) {
      const textNode = addBtn.lastChild;
      if (textNode && textNode.nodeType === Node.TEXT_NODE) {
        textNode.textContent = ' ' + (labels[name] || 'Agregar');
      }
    }
  }

  tabs.forEach(tab => tab.addEventListener('click', () => activateTab(tab.dataset.tab)));

  const procModal = document.getElementById('catProcModal');
  const procForm = document.getElementById('catProcForm');
  const procId = document.getElementById('catProcId');
  const procName = document.getElementById('catProcName');
  const procTitle = document.getElementById('catProcTitle');
  const procSubmit = document.getElementById('catProcSubmit');

  function openProcModal(id = null, name = '') {
    if (!procModal || !procForm) return;
    procId.value = id || '';
    procName.value = name;
    procTitle.textContent = id ? 'Editar procedimiento' : 'Agregar procedimiento';
    procSubmit.textContent = id ? 'Actualizar' : 'Guardar';
    procModal.classList.add('open');
    procModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => procName.focus(), 80);
  }

  function closeProcModal() {
    if (!procModal) return;
    procModal.classList.remove('open');
    procModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (procForm) procForm.reset();
    if (procId) procId.value = '';
  }

  if (addBtn) {
    addBtn.addEventListener('click', () => {
      if (activeTab === 'procedimientos') {
        openProcModal();
        return;
      }
      if (activeTab === 'anestesiologo') {
        openAnestModal();
        return;
      }
      if (activeTab === 'salas') {
        openRoomModal();
        return;
      }
      if (activeTab !== 'personal') {
        alert('La opción de agregar estará disponible próximamente para esta pestaña.');
        return;
      }
      const inviteModal = document.getElementById('gpInviteModal');
      if (inviteModal) {
        inviteModal.classList.add('open');
        inviteModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      } else {
        alert('No se encontró el modal de invitación.');
      }
    });
  }

  document.getElementById('catProcClose')?.addEventListener('click', closeProcModal);
  document.getElementById('catProcCancel')?.addEventListener('click', closeProcModal);
  procModal?.addEventListener('click', event => { if (event.target === procModal) closeProcModal(); });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && procModal?.classList.contains('open')) closeProcModal();
  });

  async function catJson(url, method, body) {
    const res = await fetch(url, {
      method,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: body ? JSON.stringify(body) : null,
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      const validation = data.errors ? Object.values(data.errors).flat()[0] : null;
      throw new Error(validation || data.message || 'No se pudo completar la acción.');
    }
    return data;
  }

  procForm?.addEventListener('submit', async event => {
    event.preventDefault();
    const id = procId.value;
    const nombre = procName.value.trim();
    if (!nombre) {
      alert('Escribe el nombre del procedimiento.');
      return;
    }
    procSubmit.disabled = true;
    procSubmit.textContent = id ? 'Actualizando...' : 'Guardando...';
    try {
      const url = id
        ? procForm.dataset.updateUrlTemplate.replace('__ID__', id)
        : procForm.dataset.storeUrl;
      const data = await catJson(url, id ? 'PUT' : 'POST', { nombre });
      catReload(data.message || (id ? 'Procedimiento actualizado.' : 'Procedimiento guardado.'));
    } catch (err) {
      alert(err.message);
      procSubmit.disabled = false;
      procSubmit.textContent = id ? 'Actualizar' : 'Guardar';
    }
  });

  const anestModal = document.getElementById('catAnestModal');
  const anestForm = document.getElementById('catAnestForm');
  const anestId = document.getElementById('catAnestId');
  const anestNombres = document.getElementById('catAnestNombres');
  const anestApellidoPaterno = document.getElementById('catAnestApellidoPaterno');
  const anestApellidoMaterno = document.getElementById('catAnestApellidoMaterno');
  const anestEspecialidad = document.getElementById('catAnestEspecialidad');
  const anestCedula = document.getElementById('catAnestCedula');
  const anestCorreo = document.getElementById('catAnestCorreo');
  const anestTelefono = document.getElementById('catAnestTelefono');
  const anestActivo = document.getElementById('catAnestActivo');
  const anestTitle = document.getElementById('catAnestTitle');
  const anestSubmit = document.getElementById('catAnestSubmit');

  function openAnestModal(id = null, data = {}) {
    if (!anestModal || !anestForm) return;
    anestId.value = id || '';
    anestNombres.value = data.nombres || '';
    anestApellidoPaterno.value = data.apellidoPaterno || '';
    anestApellidoMaterno.value = data.apellidoMaterno || '';
    anestEspecialidad.value = data.especialidad || '';
    anestCedula.value = data.cedula || '';
    anestCorreo.value = data.correo || '';
    anestTelefono.value = data.telefono || '';
    anestActivo.checked = data.activo !== undefined ? data.activo : true;
    anestTitle.textContent = id ? 'Editar anestesiólogo' : 'Agregar anestesiólogo';
    anestSubmit.textContent = id ? 'Actualizar' : 'Guardar';
    anestModal.classList.add('open');
    anestModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => anestNombres.focus(), 80);
  }

  function closeAnestModal() {
    if (!anestModal) return;
    anestModal.classList.remove('open');
    anestModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (anestForm) anestForm.reset();
    if (anestId) anestId.value = '';
  }

  document.getElementById('catAnestClose')?.addEventListener('click', closeAnestModal);
  document.getElementById('catAnestCancel')?.addEventListener('click', closeAnestModal);
  anestModal?.addEventListener('click', event => { if (event.target === anestModal) closeAnestModal(); });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && anestModal?.classList.contains('open')) closeAnestModal();
  });

  const roomModal = document.getElementById('catRoomModal');
  const roomForm = document.getElementById('catRoomForm');
  const roomId = document.getElementById('catRoomId');
  const roomName = document.getElementById('catRoomName');
  const roomActivo = document.getElementById('catRoomActivo');
  const roomTitle = document.getElementById('catRoomTitle');
  const roomSubmit = document.getElementById('catRoomSubmit');

  function openRoomModal(id = null, data = {}) {
    if (!roomModal || !roomForm) return;
    roomId.value = id || '';
    roomName.value = data.nombre || '';
    roomActivo.checked = data.activo !== undefined ? data.activo : true;
    roomTitle.textContent = id ? 'Editar sala' : 'Agregar sala';
    roomSubmit.textContent = id ? 'Actualizar' : 'Guardar';
    roomModal.classList.add('open');
    roomModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => roomName.focus(), 80);
  }

  function closeRoomModal() {
    if (!roomModal) return;
    roomModal.classList.remove('open');
    roomModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (roomForm) roomForm.reset();
    if (roomId) roomId.value = '';
  }

  document.getElementById('catRoomClose')?.addEventListener('click', closeRoomModal);
  document.getElementById('catRoomCancel')?.addEventListener('click', closeRoomModal);
  roomModal?.addEventListener('click', event => { if (event.target === roomModal) closeRoomModal(); });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && roomModal?.classList.contains('open')) closeRoomModal();
  });

  anestForm?.addEventListener('submit', async event => {
    event.preventDefault();
    const id = anestId.value;
    const nombres = anestNombres.value.trim();
    if (!nombres) {
      alert('Escribe los nombres del anestesiólogo.');
      return;
    }
    anestSubmit.disabled = true;
    anestSubmit.textContent = id ? 'Actualizando...' : 'Guardando...';
    try {
      const url = id
        ? anestForm.dataset.updateUrlTemplate.replace('__ID__', id)
        : anestForm.dataset.storeUrl;
      const body = {
        nombres,
        apellido_paterno: anestApellidoPaterno.value.trim() || null,
        apellido_materno: anestApellidoMaterno.value.trim() || null,
        especialidad: anestEspecialidad.value.trim() || null,
        cedula_profesional: anestCedula.value.trim() || null,
        correo: anestCorreo.value.trim() || null,
        telefono: anestTelefono.value.trim() || null,
        activo: anestActivo.checked ? 1 : 0,
      };
      const data = await catJson(url, id ? 'PUT' : 'POST', body);
      catReload(data.message || (id ? 'Anestesiólogo actualizado.' : 'Anestesiólogo guardado.'));
    } catch (err) {
      alert(err.message);
      anestSubmit.disabled = false;
      anestSubmit.textContent = id ? 'Actualizar' : 'Guardar';
    }
  });

  roomForm?.addEventListener('submit', async event => {
    event.preventDefault();
    const id = roomId.value;
    const nombre = roomName.value.trim();
    if (!nombre) {
      alert('Escribe el nombre de la sala.');
      return;
    }
    roomSubmit.disabled = true;
    roomSubmit.textContent = id ? 'Actualizando...' : 'Guardando...';
    try {
      const url = id
        ? roomForm.dataset.updateUrlTemplate.replace('__ID__', id)
        : roomForm.dataset.storeUrl;
      const data = await catJson(url, id ? 'PUT' : 'POST', {
        nombre,
        activa: roomActivo.checked ? 1 : 0,
      });
      catReload(data.message || (id ? 'Sala actualizada.' : 'Sala guardada.'));
    } catch (err) {
      alert(err.message);
      roomSubmit.disabled = false;
      roomSubmit.textContent = id ? 'Actualizar' : 'Guardar';
    }
  });

  async function catApi(url, method) {
    const res = await fetch(url, {
      method,
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.message || 'No se pudo completar la acción.');
    return data;
  }

  function catReload(message) {
    try { sessionStorage.setItem('enclaii-catalog-message', message); } catch (e) {}
    window.location.reload();
  }

  wrapper.querySelectorAll('.cat-member-remove').forEach(button => {
    button.addEventListener('click', async () => {
      const url = button.dataset.action;
      const name = button.dataset.memberName;
      if (!url || !confirm(`¿Retirar a ${name} de la clínica?`)) return;
      button.disabled = true;
      try {
        const data = await catApi(url, 'DELETE');
        catReload(data.message || 'Integrante retirado.');
      } catch (err) {
        alert(err.message);
        button.disabled = false;
      }
    });
  });

  wrapper.querySelectorAll('.cat-invite-revoke').forEach(button => {
    button.addEventListener('click', async () => {
      const url = button.dataset.action;
      if (!url || !confirm('¿Cancelar esta invitación?')) return;
      button.disabled = true;
      try {
        const data = await catApi(url, 'DELETE');
        catReload(data.message || 'Invitación cancelada.');
      } catch (err) {
        alert(err.message);
        button.disabled = false;
      }
    });
  });

  wrapper.querySelectorAll('.cat-proc-edit').forEach(button => {
    button.addEventListener('click', () => {
      openProcModal(button.dataset.id, button.dataset.nombre);
    });
  });

  wrapper.querySelectorAll('.cat-anest-edit').forEach(button => {
    button.addEventListener('click', () => {
      openAnestModal(button.dataset.id, {
        nombres: button.dataset.nombres,
        apellidoPaterno: button.dataset.apellidoPaterno,
        apellidoMaterno: button.dataset.apellidoMaterno,
        especialidad: button.dataset.especialidad,
        cedula: button.dataset.cedula,
        correo: button.dataset.correo,
        telefono: button.dataset.telefono,
        activo: button.dataset.activo === '1',
      });
    });
  });

  wrapper.querySelectorAll('.cat-anest-remove').forEach(button => {
    button.addEventListener('click', async () => {
      const url = button.dataset.action;
      const name = button.dataset.anestName;
      if (!url || !confirm(`¿Eliminar al anestesiólogo "${name}"?`)) return;
      button.disabled = true;
      try {
        const data = await catApi(url, 'DELETE');
        catReload(data.message || 'Anestesiólogo eliminado.');
      } catch (err) {
        alert(err.message);
        button.disabled = false;
      }
    });
  });

  wrapper.querySelectorAll('.cat-proc-remove').forEach(button => {
    button.addEventListener('click', async () => {
      const url = button.dataset.action;
      const name = button.dataset.procName;
      if (!url || !confirm(`¿Eliminar el procedimiento "${name}"?`)) return;
      button.disabled = true;
      try {
        const data = await catApi(url, 'DELETE');
        catReload(data.message || 'Procedimiento eliminado.');
      } catch (err) {
        alert(err.message);
        button.disabled = false;
      }
    });
  });

  wrapper.querySelectorAll('.cat-room-edit').forEach(button => {
    button.addEventListener('click', () => {
      openRoomModal(button.dataset.id, {
        nombre: button.dataset.nombre,
        activo: button.dataset.activo === '1',
      });
    });
  });

  wrapper.querySelectorAll('.cat-room-remove').forEach(button => {
    button.addEventListener('click', async () => {
      const url = button.dataset.action;
      const name = button.dataset.roomName;
      if (!url || !confirm(`¿Eliminar la sala "${name}"?`)) return;
      button.disabled = true;
      try {
        const data = await catApi(url, 'DELETE');
        catReload(data.message || 'Sala eliminada.');
      } catch (err) {
        alert(err.message);
        button.disabled = false;
      }
    });
  });
})();
</script>
