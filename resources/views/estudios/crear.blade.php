@extends('layouts.app')

@section('title', 'Nuevo Estudio')
@section('active', 'nuevo-estudio')
@section('header-title', 'Nuevo Estudio')
@section('header-sub')
  Datos nuevos
@endsection

@push('styles')
<style>
/* ============ CREAR ESTUDIO ============ */

/* Toolbar superior */
.crear-toolbar {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 22px;
  flex-wrap: wrap;
}
.btn-tool {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
  font-size: 14px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  text-decoration: none;
  transition: background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-tool svg { color: var(--cyan); }
.btn-tool:hover { background: var(--card); }
.btn-tool:active { transform: scale(.97); }
.btn-back {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: var(--r-md);
  border: 1px solid var(--stroke-strong);
  background: var(--panel-2);
  font-size: 14px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  text-decoration: none;
  margin-left: auto;
  transition: background-color 150ms ease, transform 160ms var(--ease-out);
}
.btn-back:hover { background: var(--card); }
.btn-back:active { transform: scale(.97); }

/* Layout principal */
.crear-layout {
  display: grid;
  grid-template-columns: 1fr 220px;
  gap: 22px;
  align-items: start;
}

/* Secciones del formulario */
.crear-card {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 28px 28px 24px;
}

.sec-title {
  font-family: 'Sora', sans-serif;
  font-size: 19px;
  font-weight: 700;
  margin-bottom: 22px;
  line-height: 1.2;
}

/* Grid de campos */
.fields-grid {
  display: grid;
  gap: 18px;
}
.cols-2 { grid-template-columns: 1fr 1fr; }
.cols-3 { grid-template-columns: 1fr 1fr 1fr; }
.cols-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }

.field {
  display: flex;
  flex-direction: column;
  gap: 7px;
}
.field label {
  font-size: 13px;
  font-weight: 600;
  color: var(--txt-soft);
}
.field input,
.field select,
.field textarea {
  background: var(--panel-2);
  border: 1px solid var(--stroke-strong);
  border-radius: var(--r-md);
  padding: 11px 14px;
  font-family: inherit;
  font-size: 14px;
  color: var(--txt);
  outline: none;
  width: 100%;
  transition: border-color 150ms ease, box-shadow 150ms ease;
}
.field input::placeholder,
.field textarea::placeholder { color: var(--off); }
.field input:focus,
.field select:focus,
.field textarea:focus {
  border-color: var(--blue);
  box-shadow: 0 0 0 3px rgba(46,123,246,.18);
}
.field select option { background: var(--panel); color: var(--txt); }
.field textarea { resize: vertical; min-height: 100px; }

/* Input con icono a la derecha */
.input-icon {
  position: relative;
}
.input-icon input { padding-right: 40px; }
.input-icon .ico {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--txt-soft);
  pointer-events: none;
}

/* Separador entre secciones */
.sec-divider {
  border: none;
  border-top: 1px solid var(--stroke);
  margin: 26px 0 22px;
}

/* Panel derecho: foto + botones */
.side-panel {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.foto-box {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  padding: 20px 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
}
.foto-circle {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  border: 2px dashed var(--stroke-strong);
  display: grid;
  place-items: center;
  overflow: hidden;
  cursor: pointer;
  transition: border-color 150ms ease;
  position: relative;
  background: var(--panel-2);
}
.foto-circle:hover { border-color: var(--blue); }
.foto-circle img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: none;
}
.foto-circle .foto-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  color: var(--txt-soft);
  font-size: 12px;
  text-align: center;
}
.btn-add-foto {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  font-weight: 600;
  color: var(--cyan);
  cursor: pointer;
  background: none;
  border: none;
}

.action-btns {
  background: linear-gradient(180deg, var(--card), var(--panel-2));
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  overflow: hidden;
}
.action-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  padding: 13px 16px;
  border: none;
  border-bottom: 1px solid var(--stroke);
  background: none;
  font: inherit;
  font-size: 13.5px;
  font-weight: 600;
  color: var(--txt);
  cursor: pointer;
  transition: background-color 150ms ease;
  text-align: left;
}
.action-btn:last-child { border-bottom: 0; }
.action-btn:hover { background: rgba(110,160,255,.07); }
.action-btn .ab-icon {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  border: 1px solid var(--stroke-strong);
  display: grid;
  place-items: center;
  flex: none;
  color: var(--cyan);
  background: rgba(56,199,244,.08);
}

/* Footer guardar */
.crear-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: 18px;
}
.btn-save {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 13px 28px;
  border-radius: var(--r-md);
  background: linear-gradient(135deg,#1668D9,var(--blue));
  color: #fff;
  font-size: 15px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  box-shadow: 0 8px 22px -8px rgba(46,123,246,.6);
  transition: opacity 150ms ease, transform 160ms var(--ease-out);
}
.btn-save:hover { opacity: .9; }
.btn-save:active { transform: scale(.97); }

@media (max-width:1100px) {
  .crear-layout { grid-template-columns: 1fr; }
  .cols-4 { grid-template-columns: 1fr 1fr; }
}
@media (max-width:640px) {
  .cols-2,.cols-3,.cols-4 { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

  {{-- Toolbar --}}
  <div class="crear-toolbar rise d1">
    <a class="btn-tool" href="{{ route('nuevo-estudio.crear') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nuevo paciente
    </a>
    <button class="btn-tool">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Buscar paciente
    </button>
    <a class="btn-back" href="{{ route('nuevo-estudio') }}">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  {{-- Layout principal --}}
  <div class="crear-layout">

    {{-- Formulario --}}
    <form class="crear-card rise d2" method="POST" action="#" id="formCrear">
      @csrf

      <h2 class="sec-title">Información del siguiente</h2>

      {{-- Fila 1: Nombre + Identificación --}}
      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" placeholder="María Fernanda López Ruiz" autocomplete="off">
        </div>
        <div class="field">
          <label for="identificacion">Identificación</label>
          <input type="text" id="identificacion" name="identificacion" placeholder="0256987450" autocomplete="off">
        </div>
      </div>

      {{-- Fila 2: Fecha nac + Edad + Peso + Altura --}}
      <div class="fields-grid cols-4" style="margin-bottom:18px">
        <div class="field">
          <label for="fecha_nac">Fecha de nacimiento</label>
          <div class="input-icon">
            <input type="date" id="fecha_nac" name="fecha_nac">
            <span class="ico"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
          </div>
        </div>
        <div class="field">
          <label for="edad">Edad</label>
          <input type="text" id="edad" name="edad" placeholder="28 años" autocomplete="off">
        </div>
        <div class="field">
          <label for="peso">Peso</label>
          <input type="text" id="peso" name="peso" placeholder="30 kg" autocomplete="off">
        </div>
        <div class="field">
          <label for="altura">Altura</label>
          <input type="text" id="altura" name="altura" placeholder="1.75 m" autocomplete="off">
        </div>
      </div>

      {{-- Fila 3: Sexo + NSS + Dirección --}}
      <div class="fields-grid cols-3" style="margin-bottom:18px">
        <div class="field">
          <label for="sexo">Sexo</label>
          <select id="sexo" name="sexo">
            <option value="" disabled selected>Elegir</option>
            <option value="F">Femenino</option>
            <option value="M">Masculino</option>
          </select>
        </div>
        <div class="field">
          <label for="nss">N.S.S</label>
          <input type="text" id="nss" name="nss" placeholder="25849563-9" autocomplete="off">
        </div>
        <div class="field">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" placeholder="CALLE, CP" autocomplete="off">
        </div>
      </div>

      {{-- Fila 4: Teléfono + Email --}}
      <div class="fields-grid cols-2" style="margin-bottom:0">
        <div class="field">
          <label for="telefono">Teléfono</label>
          <input type="tel" id="telefono" name="telefono" placeholder="722 162 0815" autocomplete="off">
        </div>
        <div class="field">
          <label for="email">e-mail</label>
          <input type="email" id="email" name="email" placeholder="@gmail.com" autocomplete="off">
        </div>
      </div>

      <hr class="sec-divider">

      <h2 class="sec-title">Información médica</h2>

      {{-- Fila 5: Procedimiento + Fecha y hora --}}
      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="procedimiento">Procedimiento</label>
          <select id="procedimiento" name="procedimiento">
            <option value="" disabled selected>Seleccione</option>
            <option value="endoscopia">Endoscopia diagnóstica</option>
            <option value="colonoscopia">Colonoscopia</option>
            <option value="gastroscopia">Gastroscopia</option>
            <option value="sigmoidoscopia">Sigmoidoscopia</option>
            <option value="cpre">CPRE</option>
            <option value="ecoendoscopia">Ecoendoscopia</option>
          </select>
        </div>
        <div class="field">
          <label for="fecha_hora">Fecha y hora</label>
          <div class="input-icon">
            <input type="datetime-local" id="fecha_hora" name="fecha_hora">
            <span class="ico"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
          </div>
        </div>
      </div>

      {{-- Fila 6: Médico + Referido por --}}
      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="medico">Médico</label>
          <select id="medico" name="medico">
            <option value="" disabled selected>Seleccione</option>
            <option value="dr_victor" selected>Dr. Victor</option>
            <option value="dr_ricardo">Dr. Ricardo</option>
          </select>
        </div>
        <div class="field">
          <label for="referido">Referido por</label>
          <select id="referido" name="referido">
            <option value="" disabled selected>Seleccione</option>
            <option value="externo">Médico externo</option>
            <option value="propio">Médico propio</option>
            <option value="paciente">Paciente directo</option>
          </select>
        </div>
      </div>

      {{-- Diagnóstico preliminar --}}
      <div class="field" style="margin-bottom:0">
        <label for="diagnostico">Diagnostico Preliminar</label>
        <textarea id="diagnostico" name="diagnostico" placeholder="Define lo que podría tener"></textarea>
      </div>

      {{-- Footer guardar --}}
      <div class="crear-footer">
        <button type="submit" class="btn-save">
          Guardar paciente
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
      </div>

    </form>

    {{-- Panel lateral: foto + botones --}}
    <div class="side-panel rise d3">

      <div class="foto-box">
        <div class="foto-circle" id="fotoCircle" onclick="document.getElementById('fotoInput').click()">
          <img id="fotoPreview" src="" alt="Foto del paciente">
          <div class="foto-placeholder" id="fotoPlaceholder">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Foto del<br>paciente</span>
          </div>
        </div>
        {{-- Input galería (sin capture) --}}
        <input type="file" id="fotoInput" accept="image/*" style="display:none">
        {{-- Input cámara --}}
        <input type="file" id="fotoCamera" accept="image/*" capture="environment" style="display:none">

        <div style="position:relative;width:100%">
          <button class="btn-add-foto" type="button" id="btnFotoMenu">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span id="btnFotoTxt">Agregar foto</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:2px"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div id="fotoMenu" style="display:none;position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--panel);border:1px solid var(--stroke-strong);border-radius:var(--r-md);overflow:hidden;min-width:170px;z-index:50;box-shadow:0 8px 24px rgba(0,0,0,.35)">
            <button type="button" id="btnGaleria" style="display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:none;border:none;border-bottom:1px solid var(--stroke);font:inherit;font-size:13.5px;font-weight:600;color:var(--txt);cursor:pointer;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              Abrir galería
            </button>
            <button type="button" id="btnCamara" style="display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:none;border:none;font:inherit;font-size:13.5px;font-weight:600;color:var(--txt);cursor:pointer;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              Tomar foto
            </button>
          </div>
        </div>
      </div>

      <div class="action-btns">
        <a class="action-btn" href="{{ route('nuevo-estudio.grabando') }}">
          <span class="ab-icon" style="background:rgba(255,59,59,.12);border-color:rgba(255,90,110,.4)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#ff5a6e"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="#ff5a6e" stroke="none"/></svg>
          </span>
          Iniciar Grabación
        </a>
        <a class="action-btn" href="{{ route('nuevo-estudio.capturas') }}">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          </span>
          Agregar Capturas
        </a>
        <a class="action-btn" href="{{ route('nuevo-estudio.importar') }}">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          </span>
          Importar Imágenes
        </a>
        <a class="action-btn" href="{{ route('nuevo-estudio.configuracion') }}">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
          </span>
          Configuración de Grabacion
        </a>
      </div>

    </div>

  </div>

@endsection

@push('scripts')
<script>
(function () {
  /* Fecha y hora actual por defecto */
  const now = new Date();
  const pad = n => String(n).padStart(2, '0');
  const local = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
  document.getElementById('fecha_hora').value = local;
  document.getElementById('fecha_nac').value  = '1998-12-25';

  /* ---- Menú foto ---- */
  const btnMenu   = document.getElementById('btnFotoMenu');
  const fotoMenu  = document.getElementById('fotoMenu');
  const btnTxt    = document.getElementById('btnFotoTxt');

  btnMenu.addEventListener('click', (e) => {
    e.stopPropagation();
    fotoMenu.style.display = fotoMenu.style.display === 'none' ? 'block' : 'none';
  });

  document.addEventListener('click', () => { fotoMenu.style.display = 'none'; });

  document.getElementById('btnGaleria').addEventListener('click', () => {
    fotoMenu.style.display = 'none';
    document.getElementById('fotoInput').click();
  });

  document.getElementById('btnCamara').addEventListener('click', () => {
    fotoMenu.style.display = 'none';
    document.getElementById('fotoCamera').click();
  });

  function applyPreview(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById('fotoPreview');
      const ph  = document.getElementById('fotoPlaceholder');
      img.src = e.target.result;
      img.style.display = 'block';
      ph.style.display  = 'none';
      btnTxt.textContent = 'Cambiar foto';
    };
    reader.readAsDataURL(file);
  }

  document.getElementById('fotoInput').addEventListener('change',  function () { applyPreview(this.files[0]); });
  document.getElementById('fotoCamera').addEventListener('change', function () { applyPreview(this.files[0]); });
})();
</script>
@endpush
