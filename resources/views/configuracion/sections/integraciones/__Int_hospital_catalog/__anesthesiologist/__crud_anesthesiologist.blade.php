<div class="int-bk-overlay" id="catAnestModal" aria-hidden="true">
  <form class="int-bk-modal" id="catAnestForm" data-store-url="{{ route('anestesiologos.store') }}" data-update-url-template="{{ url('/anestesiologos/__ID__') }}">
    @csrf
    <input type="hidden" id="catAnestId" name="id">
    <div class="int-bk-hdr">
      <div>
        <div class="int-bk-title" id="catAnestTitle">Agregar anestesiólogo</div>
        <div class="int-bk-sub">Completa los datos del anestesiólogo.</div>
      </div>
      <button type="button" class="int-bk-close" id="catAnestClose" aria-label="Cerrar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <div class="int-bk-body">
      <label class="int-bk-label" for="catAnestNombres">Nombres</label>
      <input class="int-bk-input" id="catAnestNombres" name="nombres" type="text" maxlength="255" placeholder="Ej. Laura" required>

      <label class="int-bk-label" for="catAnestApellidoPaterno">Apellido paterno</label>
      <input class="int-bk-input" id="catAnestApellidoPaterno" name="apellido_paterno" type="text" maxlength="255" placeholder="Ej. Chávez">

      <label class="int-bk-label" for="catAnestApellidoMaterno">Apellido materno</label>
      <input class="int-bk-input" id="catAnestApellidoMaterno" name="apellido_materno" type="text" maxlength="255" placeholder="Ej. Herrera">

      <label class="int-bk-label" for="catAnestEspecialidad">Especialidad</label>
      <input class="int-bk-input" id="catAnestEspecialidad" name="especialidad" type="text" maxlength="255" placeholder="Ej. Anestesiología">

      <label class="int-bk-label" for="catAnestCedula">Cédula Profesional</label>
      <input class="int-bk-input" id="catAnestCedula" name="cedula_profesional" type="text" maxlength="255" placeholder="Ej. 7654321">

      <label class="int-bk-label" for="catAnestCorreo">Correo electrónico</label>
      <input class="int-bk-input" id="catAnestCorreo" name="correo" type="email" maxlength="255" placeholder="Ej. doctora@clinica.com">

      <label class="int-bk-label" for="catAnestTelefono">Teléfono (opcional)</label>
      <input class="int-bk-input" id="catAnestTelefono" name="telefono" type="text" maxlength="50" placeholder="Ej. 5512345678">

      <label class="int-check" style="margin-top:12px;display:inline-flex;">
        <input type="checkbox" id="catAnestActivo" name="activo" value="1" checked>
        Activo
      </label>
    </div>

    <div class="int-bk-footer">
      <button type="button" class="int-bk-btn cancel" id="catAnestCancel">Cancelar</button>
      <button type="submit" class="int-bk-btn submit" id="catAnestSubmit">Guardar</button>
    </div>
  </form>
</div>
