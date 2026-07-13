{{-- ============ PANEL: PERFIL ============ --}}
@push('styles')
<style>
.pf-head h2{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.pf-head p{font-size:13px;color:var(--txt-soft);margin:3px 0 18px}

.pf-top{display:grid;grid-template-columns:.85fr 1.5fr 1.1fr;gap:18px;align-items:stretch;margin-bottom:18px}
@media (max-width:1100px){.pf-top{grid-template-columns:1fr 1fr}}
@media (max-width:760px){.pf-top{grid-template-columns:1fr}}
.pf-top > .card{display:flex;flex-direction:column}
.pf-bottom{display:grid;grid-template-columns:1.3fr 1fr;gap:18px;align-items:stretch}
@media (max-width:900px){.pf-bottom{grid-template-columns:1fr}}
.pf-bottom > .card{display:flex;flex-direction:column}

.pf-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:700;margin-bottom:18px}
.pf-title small{font-weight:500;color:var(--txt-soft);font-size:12px}

.pf-field{margin-bottom:15px}
.pf-field:last-child{margin-bottom:0}
.pf-field label{display:block;font-size:12px;color:var(--txt-soft);margin-bottom:7px}
.pf-input,.pf-select select{width:100%;font:inherit;font-size:13px;color:var(--txt);background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:10px;padding:11px 13px;transition:border-color .15s}
.pf-input::placeholder{color:var(--txt-soft)}
.pf-input:focus,.pf-select select:focus{outline:none;border-color:var(--cyan)}
.pf-select{position:relative}
.pf-select select{appearance:none;-webkit-appearance:none;cursor:pointer;padding-right:36px}
.pf-select svg{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--txt-soft);pointer-events:none}
.pf-ico-field{position:relative}
.pf-ico-field .pf-input{padding-left:38px;cursor:pointer}
.pf-ico-field svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--txt-soft);pointer-events:none}
.pf-input[type="date"]{color-scheme:dark}
html[data-theme="light"] .pf-input[type="date"]{color-scheme:light}
.pf-input[type="date"]::-webkit-calendar-picker-indicator{opacity:0;position:absolute;right:0;top:0;width:100%;height:100%;cursor:pointer}

.pf-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media (max-width:520px){.pf-2{grid-template-columns:1fr}}

/* Foto de perfil */
.pf-photo{display:flex;flex-direction:column;align-items:center;text-align:center}
.pf-photo .pf-ava-wrap{position:relative;width:min(230px,80%);aspect-ratio:1/1;margin:10px 0 22px}
.pf-photo .pf-ava{display:block;width:100%;height:100%;aspect-ratio:1/1;border-radius:50%;object-fit:cover;border:3px solid var(--stroke-strong);background:var(--panel-2)}
.pf-photo-btns{display:flex;flex-direction:column;align-items:stretch;gap:10px;width:100%;max-width:230px}
/* Constancia fiscal */
.csf-wrap{margin-top:8px}
.csf-upload-area{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:22px 16px;border:2px dashed var(--stroke-strong);border-radius:12px;text-align:center;cursor:pointer;transition:border-color .15s,background .15s}
.csf-upload-area:hover{border-color:var(--cyan);background:rgba(0,200,255,.04)}
.csf-upload-area svg{color:var(--txt-soft)}
.csf-upload-area p{font-size:13px;color:var(--txt-soft);margin:0}
.csf-upload-area span{font-size:11px;color:var(--txt-soft);opacity:.7}
.csf-link-btn{background:none;border:none;color:var(--cyan);font:inherit;font-size:13px;font-weight:600;cursor:pointer;padding:0;text-decoration:underline}
.csf-pdf-card{display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:12px}
.csf-pdf-card svg{flex-shrink:0;color:var(--blue)}
.csf-pdf-info{display:flex;flex-direction:column;gap:4px;min-width:0}
.csf-pdf-info span{font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.csf-view-link{font-size:12px;color:var(--cyan);text-decoration:none;font-weight:600}
.csf-view-link:hover{text-decoration:underline}
#csfImg{width:100%;max-height:260px;object-fit:contain;border-radius:10px;border:1px solid var(--stroke-strong);background:var(--panel-2)}
.csf-actions{display:flex;gap:10px;margin-top:10px}
.csf-action-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font:inherit;font-size:12px;font-weight:700;cursor:pointer;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt);transition:background .15s}
.csf-action-btn:hover{background:var(--panel-3)}
.csf-action-del{color:var(--red);border-color:var(--red)}
.csf-action-del:hover{background:rgba(255,80,80,.08)}
.csf-status{font-size:12px;margin-top:8px;padding:6px 10px;border-radius:8px}
.csf-status.ok{background:rgba(0,200,100,.12);color:var(--green)}
.csf-status.err{background:rgba(255,80,80,.12);color:var(--red)}

.pf-edit-btn,.pf-del{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px 16px;border-radius:var(--r-md);font-size:12.5px;font-weight:700;cursor:pointer;transition:background-color .15s}
.pf-edit-btn svg,.pf-del svg{width:15px;height:15px}
.pf-edit-btn{border:1px solid rgba(56,199,244,.35);color:var(--cyan);background:rgba(56,199,244,.08)}
.pf-del{border:1px solid rgba(255,90,110,.35);color:var(--red);background:rgba(255,90,110,.08)}
@media (hover:hover){.pf-edit-btn:hover{background:rgba(56,199,244,.18)}.pf-del:hover{background:rgba(255,90,110,.18)}}

/* ===== MODAL FOTO PERFIL ===== */
.pf-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.72);backdrop-filter:blur(6px);z-index:9999;display:none;align-items:center;justify-content:center}
.pf-modal-overlay.active{display:flex}
.pf-modal-photo{background:linear-gradient(180deg,var(--card),var(--panel-2));border:1px solid var(--stroke-strong);border-radius:var(--r-lg);width:100%;max-width:900px;max-height:90vh;overflow-y:auto;padding:28px 32px;animation:pfModalIn 300ms ease;box-sizing:border-box}
@keyframes pfModalIn{from{opacity:0;transform:scale(.95) translateY(20px)}to{opacity:1;transform:scale(1) translateY(0)}}
.pf-modal-header{margin-bottom:24px}
.pf-modal-header h2{font-family:'Sora',sans-serif;font-size:20px;font-weight:700;margin-bottom:8px}
.pf-modal-header p{font-size:14px;color:var(--txt-soft)}
.pf-modal-body{display:grid;grid-template-columns:1.2fr 1fr;gap:24px}
@media(max-width:680px){.pf-modal-body{grid-template-columns:1fr}}
.pf-preview-panel{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);padding:20px}
.pf-preview-panel h3{font-size:14px;font-weight:600;margin-bottom:14px}
.pf-camera-frame{aspect-ratio:4/3;background:#d1d5db;border-radius:var(--r-md);position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center}
.pf-camera-frame video,.pf-camera-frame canvas{width:100%;height:100%;object-fit:cover;display:none}
.pf-camera-frame.camera-active video{display:block}
.pf-camera-frame.camera-active .pf-avatar-preview{display:none}
.pf-corner{position:absolute;width:24px;height:24px;border:3px solid var(--blue)}
.pf-corner-tl{top:12px;left:12px;border-right:0;border-bottom:0;border-radius:8px 0 0 0}
.pf-corner-tr{top:12px;right:12px;border-left:0;border-bottom:0;border-radius:0 8px 0 0}
.pf-corner-bl{bottom:12px;left:12px;border-right:0;border-top:0;border-radius:0 0 0 8px}
.pf-corner-br{bottom:12px;right:12px;border-left:0;border-top:0;border-radius:0 0 8px 0}
.pf-avatar-preview{width:140px;height:140px;border-radius:50%;background:linear-gradient(135deg,#e0e0e0,#f5f5f5);display:flex;align-items:center;justify-content:center;font-size:60px}
.pf-camera-controls{display:flex;align-items:center;justify-content:center;gap:32px;margin-top:16px}
.pf-cam-btn{display:flex;flex-direction:column;align-items:center;gap:6px;background:transparent;border:0;color:var(--txt-soft);font-size:12px;font-weight:500;cursor:pointer;transition:color 150ms}
.pf-cam-btn:hover{color:var(--txt)}
.pf-cam-btn.active{color:var(--cyan)}
.pf-cam-btn .icon{width:44px;height:44px;border-radius:50%;display:grid;place-items:center;background:var(--panel);border:1px solid var(--stroke)}
.pf-cam-btn.active .icon{background:var(--blue);border-color:var(--blue);color:#fff}
.pf-cam-btn svg{width:20px;height:20px}
.pf-options-panel{display:flex;flex-direction:column;gap:18px}
.pf-source-options{display:flex;flex-direction:column;gap:10px}
.pf-source-option{display:flex;align-items:center;gap:14px;padding:14px 16px;border-radius:var(--r-md);border:1px solid var(--stroke);background:var(--panel-2);cursor:pointer;transition:border-color .15s,background .15s}
.pf-source-option.active{border-color:var(--blue);background:rgba(56,132,255,.08)}
.pf-source-option .icon{width:40px;height:40px;border-radius:10px;display:grid;place-items:center;flex-shrink:0;background:rgba(56,132,255,.15);color:var(--blue)}
.pf-source-option .info strong{display:block;font-size:13px;font-weight:700}
.pf-source-option .info span{font-size:12px;color:var(--txt-soft)}
.pf-option-section h4{font-size:13px;font-weight:600;margin-bottom:12px;color:var(--txt-soft)}
.pf-resolution-select{width:100%;padding:12px 14px;border-radius:10px;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt);font:inherit;font-size:13px;cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:36px}
.pf-recommendations{background:rgba(56,132,255,.07);border:1px solid rgba(56,132,255,.2);border-radius:10px;padding:14px 16px}
.pf-rec-header{display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:var(--cyan);margin-bottom:10px}
.pf-rec-header svg{width:16px;height:16px}
.pf-recommendations ul{margin:0;padding-left:18px;font-size:12.5px;color:var(--txt-soft);line-height:1.7}
.pf-modal-footer{display:flex;justify-content:flex-end;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid var(--stroke)}
.pf-btn-cancel{padding:11px 24px;border-radius:var(--r-md);border:1px solid var(--stroke);background:transparent;color:var(--txt);font:inherit;font-size:14px;font-weight:600;cursor:pointer}
.pf-btn-confirm{padding:11px 28px;border-radius:var(--r-md);border:none;background:var(--blue);color:#fff;font:inherit;font-size:14px;font-weight:700;cursor:pointer}
</style>
@endpush

<div class="cfg-panel" data-panel="perfil">

  <div class="pf-head">
    <h2>Perfil</h2>
    <p>Gestiona tu cuenta del sistema</p>
  </div>

  {{-- Fila superior --}}
  <div class="pf-top">
    <article class="card rise d2">
      <div class="pf-title">Foto de perfil</div>
      <div class="pf-photo">
        @php
          $fotoPerfil = auth()->user()->foto_perfil
              ? asset('storage/' . auth()->user()->foto_perfil)
              : null;
        @endphp
        <div class="pf-ava-wrap">
          <img class="pf-ava" id="pfAva" src="{{ $fotoPerfil ?? '' }}" alt="Foto de perfil" style="{{ $fotoPerfil ? '' : 'display:none' }}">
          <div class="pf-ava pf-ava-empty" id="pfEmpty" style="display:{{ $fotoPerfil ? 'none' : 'grid' }};place-items:center;color:var(--txt-soft)">
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
        </div>
        <div class="pf-photo-btns">
          <button type="button" class="pf-edit-btn" id="pfEdit">Editar foto <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
          <button type="button" class="pf-del" id="pfDel">Eliminar foto <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
        </div>
        <input type="file" id="pfPhoto" accept="image/*" hidden>
      </div>
    </article>

    @php $u = auth()->user(); @endphp
    <article class="card rise d3">
      <div class="pf-title">1.- Información de la cuenta</div>
      <div class="pf-2">
        <div class="pf-field"><label>Nombre(s)</label><input class="pf-input" type="text" name="name" value="{{ $u->name }}"></div>
        <div class="pf-field"><label>Apellido paterno</label><input class="pf-input" type="text" name="apellido_paterno" value="{{ $u->apellido_paterno }}"></div>
        <div class="pf-field"><label>Apellido materno</label><input class="pf-input" type="text" name="apellido_materno" value="{{ $u->apellido_materno }}"></div>
        <div class="pf-field"><label>Correo electrónico</label><input class="pf-input" type="email" name="email" value="{{ $u->email }}"></div>
        <div class="pf-field"><label>Teléfono</label><input class="pf-input" type="tel" name="phone" value="{{ $u->phone }}"></div>
        <div class="pf-field"><label>Fecha de nacimiento</label>
          <div class="pf-ico-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <input class="pf-input" type="date" name="fecha_nacimiento" value="{{ $u->fecha_nacimiento ? \Carbon\Carbon::parse($u->fecha_nacimiento)->format('Y-m-d') : '' }}">
          </div>
        </div>
      </div>
      <div class="pf-field" style="margin-top:14px;max-width:calc(50% - 7px)">
        <label>Sexo</label>
        <div class="pf-select">
          <select name="sexo">
            <option {{ $u->sexo === 'Masculino' ? 'selected' : '' }}>Masculino</option>
            <option {{ $u->sexo === 'Femenino'  ? 'selected' : '' }}>Femenino</option>
            <option {{ $u->sexo === 'Otro'      ? 'selected' : '' }}>Otro</option>
          </select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>
    </article>

    <article class="card rise d4">
      <div class="pf-title">2.- Información profesional</div>
      <div class="pf-field"><label>Especialidad</label><input class="pf-input" type="text" name="specialty" value="{{ $u->specialty }}"></div>
      <div class="pf-field"><label>Subespecialidad (opcional)</label><input class="pf-input" type="text" name="subespecialidad" value="{{ $u->subespecialidad }}"></div>
      <div class="pf-field"><label>Cédula profesional</label><input class="pf-input" type="text" name="professional_license" value="{{ $u->professional_license }}"></div>
      <div class="pf-field"><label>Universidad</label><input class="pf-input" type="text" name="universidad" value="{{ $u->universidad }}"></div>
    </article>
  </div>

  {{-- Fila inferior --}}
  <div class="pf-bottom">
    <article class="card rise d4">
      <div class="pf-title">3.- Información de la clínica</div>
      <div class="pf-2">
<<<<<<< HEAD
        <div class="pf-field"><label>Nombre de la clínica</label><input class="pf-input" type="text" name="clinica_nombre" value="{{ $u->clinica_nombre }}"></div>
        <div class="pf-field"><label>Ciudad</label><input class="pf-input" type="text" name="clinica_ciudad" value="{{ $u->clinica_ciudad }}"></div>
        <div class="pf-field"><label>Dirección</label><input class="pf-input" type="text" name="clinica_direccion" value="{{ $u->clinica_direccion }}"></div>
        <div class="pf-field"><label>Código postal</label><input class="pf-input" type="text" name="clinica_codigo_postal" value="{{ $u->clinica_codigo_postal }}"></div>
        <div class="pf-field"><label>Teléfono de la clínica u hospital</label><input class="pf-input" type="tel" name="clinica_telefono" value="{{ $u->clinica_telefono }}"></div>
        <div class="pf-field"><label>Estado</label><input class="pf-input" type="text" name="clinica_estado" value="{{ $u->clinica_estado }}"></div>
=======
        <div class="pf-field"><label>Nombre de la clínica</label><input class="pf-input" type="text" value="{{ auth()->user()->clinica?->nombre }}" readonly></div>
        <div class="pf-field"><label>Rol en la clínica</label><input class="pf-input" type="text" value="{{ ucfirst(auth()->user()->clinica_rol) }}" readonly></div>
        <div class="pf-field"><label>Dirección</label><input class="pf-input" type="text" value="Av. Partidas Las torres 123, Consultorio 121"></div>
        <div class="pf-field"><label>Código postal</label><input class="pf-input" type="text" value="50080"></div>
        <div class="pf-field"><label>Teléfono de la clínica u hospital</label><input class="pf-input" type="tel" value="722 364 4758"></div>
        <div class="pf-field"><label>Estado</label><input class="pf-input" type="text" value="Estado de Mexico"></div>
>>>>>>> Ricardo-Galeria
      </div>
    </article>

    <article class="card rise d5">
      <div class="pf-title">4.- Información fiscal <small>(opcional)</small></div>
      <div class="pf-2">
        <div class="pf-field"><label>RFC</label><input class="pf-input" type="text" name="rfc" value="{{ $u->rfc }}"></div>
        <div class="pf-field"><label>Razón social</label><input class="pf-input" type="text" name="razon_social" value="{{ $u->razon_social }}"></div>
      </div>
      <div class="pf-field" style="margin-top:14px">
        <label>Régimen fiscal</label>
        <div class="pf-select">
          <select name="regimen_fiscal">
            <option value="" {{ !$u->regimen_fiscal ? 'selected' : '' }}>-- Selecciona tu régimen --</option>
            <option value="601 - General de Ley Personas Morales"                             {{ $u->regimen_fiscal === '601 - General de Ley Personas Morales' ? 'selected' : '' }}>601 - General de Ley Personas Morales</option>
            <option value="605 - Sueldos y Salarios e Ingresos Asimilados"                    {{ $u->regimen_fiscal === '605 - Sueldos y Salarios e Ingresos Asimilados' ? 'selected' : '' }}>605 - Sueldos y Salarios e Ingresos Asimilados</option>
            <option value="606 - Arrendamiento"                                               {{ $u->regimen_fiscal === '606 - Arrendamiento' ? 'selected' : '' }}>606 - Arrendamiento</option>
            <option value="608 - Demás Ingresos"                                              {{ $u->regimen_fiscal === '608 - Demás Ingresos' ? 'selected' : '' }}>608 - Demás Ingresos</option>
            <option value="612 - Personas Físicas con Actividades Empresariales y Profesionales" {{ $u->regimen_fiscal === '612 - Personas Físicas con Actividades Empresariales y Profesionales' ? 'selected' : '' }}>612 - Personas Físicas con Actividades Empresariales y Profesionales</option>
            <option value="616 - Sin Obligaciones Fiscales"                                   {{ $u->regimen_fiscal === '616 - Sin Obligaciones Fiscales' ? 'selected' : '' }}>616 - Sin Obligaciones Fiscales</option>
            <option value="621 - Incorporación Fiscal"                                        {{ $u->regimen_fiscal === '621 - Incorporación Fiscal' ? 'selected' : '' }}>621 - Incorporación Fiscal</option>
            <option value="626 - Régimen Simplificado de Confianza (RESICO)"                  {{ $u->regimen_fiscal === '626 - Régimen Simplificado de Confianza (RESICO)' ? 'selected' : '' }}>626 - Régimen Simplificado de Confianza (RESICO)</option>
          </select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>
      <div class="pf-field"><label>Correo de facturación</label><input class="pf-input" type="email" name="correo_facturacion" value="{{ $u->correo_facturacion }}"></div>

      {{-- Constancia de situación fiscal --}}
      @php
        $constancia = $u->constancia_fiscal ? asset('storage/' . $u->constancia_fiscal) : null;
        $constanciaExt = $u->constancia_fiscal ? strtolower(pathinfo($u->constancia_fiscal, PATHINFO_EXTENSION)) : null;
        $constanciaNombre = $u->constancia_fiscal ? basename($u->constancia_fiscal) : null;
      @endphp
      <div class="pf-field" style="margin-top:18px">
        <label>Constancia de situación fiscal</label>
        <div class="csf-wrap" id="csfWrap">
          {{-- Vista previa si ya hay archivo --}}
          <div id="csfPreview" style="{{ $constancia ? '' : 'display:none' }}">
            @if($constanciaExt === 'pdf')
              <div class="csf-pdf-card" id="csfPdfCard">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                <div class="csf-pdf-info">
                  <span id="csfFileName">{{ $constanciaNombre }}</span>
                  <a href="{{ $constancia }}" target="_blank" class="csf-view-link">Ver documento</a>
                </div>
              </div>
            @else
              <img id="csfImg" src="{{ $constancia ?? '' }}" alt="Constancia fiscal" style="{{ $constancia ? '' : 'display:none' }}">
            @endif
          </div>
          {{-- Área de subida --}}
          <div id="csfUploadArea" class="csf-upload-area" style="{{ $constancia ? 'display:none' : '' }}">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <p>Arrastra aquí o <button type="button" id="csfPickBtn" class="csf-link-btn">selecciona el archivo</button></p>
            <span>PDF, JPG o PNG · máx. 8 MB</span>
          </div>
          {{-- Botones acción --}}
          <div id="csfActions" class="csf-actions" style="{{ $constancia ? '' : 'display:none' }}">
            <button type="button" id="csfChangeBtn" class="csf-action-btn">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Cambiar
            </button>
            <button type="button" id="csfDeleteBtn" class="csf-action-btn csf-action-del">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              Eliminar
            </button>
          </div>
          <input type="file" id="csfInput" accept=".pdf,.jpg,.jpeg,.png" hidden>
          <div id="csfStatus" class="csf-status" style="display:none"></div>
        </div>
      </div>
    </article>
  </div>

  {{-- Botón guardar --}}
  <div style="display:flex;justify-content:flex-end;margin-top:18px">
    <button type="button" id="pfSaveBtn" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--blue);color:#fff;border:none;border-radius:var(--r-md);font:inherit;font-size:14px;font-weight:700;cursor:pointer;transition:opacity .15s">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      <span id="pfSaveTxt">Guardar cambios</span>
    </button>
  </div>

</div>

{{-- Modal captura foto perfil --}}
<div class="pf-modal-overlay" id="pfModalFoto">
  <div class="pf-modal-photo">
    <div class="pf-modal-header">
      <h2>Inserta tu foto</h2>
      <p>Capture una fotografía o sube una imagen para tu perfil</p>
    </div>
    <div class="pf-modal-body">
      <div class="pf-preview-panel">
        <h3>Vista previa</h3>
        <div class="pf-camera-frame" id="pfCameraFrame">
          <span class="pf-corner pf-corner-tl"></span>
          <span class="pf-corner pf-corner-tr"></span>
          <span class="pf-corner pf-corner-bl"></span>
          <span class="pf-corner pf-corner-br"></span>
          <div class="pf-avatar-preview" id="pfAvatarPreview"></div>
        </div>
        <div class="pf-camera-controls">
          <button type="button" class="pf-cam-btn" id="pfBtnGaleria">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
            </span>
            Galería
          </button>
          <button type="button" class="pf-cam-btn active" id="pfBtnTomarFoto">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </span>
            Tomar foto
          </button>
          <button type="button" class="pf-cam-btn" id="pfBtnRotar">
            <span class="icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 12"/><path d="M3 3v9h9"/></svg>
            </span>
            Rotar cámara
          </button>
        </div>
      </div>
      <div class="pf-options-panel">
        <div class="pf-option-section">
          <h4>Seleccionar fuente</h4>
          <div class="pf-source-options">
            <div class="pf-source-option active" id="pfSrcCamera">
              <div class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              </div>
              <div class="info"><strong>Cámara en vivo</strong><span>usar cámara del dispositivo</span></div>
            </div>
            <div class="pf-source-option" id="pfSrcFile">
              <div class="icon" style="background:rgba(245,158,45,.15);color:var(--orange)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15h6"/></svg>
              </div>
              <div class="info"><strong>Subir imagen</strong><span>Seleccionar desde archivos</span></div>
            </div>
          </div>
        </div>
        <div class="pf-option-section">
          <h4>Seleccionar resolución</h4>
          <select class="pf-resolution-select">
            <option>Alta (1920 x 1080)</option>
            <option>Media (1280 x 720)</option>
            <option>Baja (640 x 480)</option>
          </select>
        </div>
        <div class="pf-recommendations">
          <div class="pf-rec-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Recomendaciones
          </div>
          <ul>
            <li>Asegúrese de que el rostro esté centrado.</li>
            <li>Buena iluminación y fondo claro.</li>
            <li>Evite sombras o reflejos en el rostro.</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="pf-modal-footer">
      <button type="button" class="pf-btn-cancel" id="pfBtnCancelar">Cancelar</button>
      <button type="button" class="pf-btn-confirm" id="pfBtnUsarFoto">Usar esta foto</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const saveBtn = document.getElementById('pfSaveBtn');
  const saveTxt = document.getElementById('pfSaveTxt');
  if (!saveBtn) return;

  saveBtn.addEventListener('click', async function() {
    saveBtn.disabled = true;
    saveTxt.textContent = 'Guardando...';

    const data = {};
    document.querySelectorAll('.cfg-panel[data-panel="perfil"] input[name], .cfg-panel[data-panel="perfil"] select[name]')
      .forEach(function(el) {
        data[el.name] = el.value;
      });

    try {
      const res = await fetch('{{ route("configuracion.perfil.update") }}', {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      });

      const json = await res.json();
      if (json.ok) {
        saveTxt.textContent = '¡Guardado!';
        saveBtn.style.background = 'var(--green)';
        setTimeout(function() {
          saveTxt.textContent = 'Guardar cambios';
          saveBtn.style.background = '';
          saveBtn.disabled = false;
        }, 2000);

        const nameEl = document.querySelector('.profile strong');
        if (nameEl && data.name) nameEl.textContent = data.name;
      } else {
        saveTxt.textContent = 'Error al guardar';
        saveBtn.style.background = 'var(--red)';
        setTimeout(function() {
          saveTxt.textContent = 'Guardar cambios';
          saveBtn.style.background = '';
          saveBtn.disabled = false;
        }, 2500);
      }
    } catch(e) {
      saveTxt.textContent = 'Error de conexión';
      saveBtn.style.background = 'var(--red)';
      setTimeout(function() {
        saveTxt.textContent = 'Guardar cambios';
        saveBtn.style.background = '';
        saveBtn.disabled = false;
      }, 2500);
    }
  });
})();

(function(){
  const modal      = document.getElementById('pfModalFoto');
  const ava        = document.getElementById('pfAva');
  const empty      = document.getElementById('pfEmpty');
  const fileInput  = document.getElementById('pfPhoto');
  const delBtn     = document.getElementById('pfDel');
  const editBtn    = document.getElementById('pfEdit');
  const avatarPrev = document.getElementById('pfAvatarPreview');
  const frame      = document.getElementById('pfCameraFrame');
  const btnCancelar= document.getElementById('pfBtnCancelar');
  const btnUsar    = document.getElementById('pfBtnUsarFoto');
  const btnGaleria = document.getElementById('pfBtnGaleria');
  const btnTomar   = document.getElementById('pfBtnTomarFoto');
  const btnRotar   = document.getElementById('pfBtnRotar');
  const srcCamera  = document.getElementById('pfSrcCamera');
  const srcFile    = document.getElementById('pfSrcFile');
  if (!modal || !ava) return;

  const showImg   = () => { ava.style.display='block'; if(empty) empty.style.display='none'; };
  const showEmpty = () => { ava.removeAttribute('src'); ava.style.display='none'; if(empty) empty.style.display='grid'; };

  let stream = null;
  let photoData = ava && ava.src && ava.style.display !== 'none' ? ava.src : null;

  function getVideo() {
    let v = document.getElementById('pfVideo');
    if (!v) { v=document.createElement('video'); v.id='pfVideo'; v.autoplay=true; v.playsInline=true; v.muted=true; frame.appendChild(v); }
    let c = document.getElementById('pfCanvas');
    if (!c) { c=document.createElement('canvas'); c.id='pfCanvas'; frame.appendChild(c); }
    return {v,c};
  }

  async function startCamera() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      alert('Tu navegador no permite abrir la cámara. Usa la opción Subir imagen.'); return;
    }
    try {
      stopCamera();
      stream = await navigator.mediaDevices.getUserMedia({video:{facingMode:'user'},audio:false});
      const {v} = getVideo();
      v.srcObject = stream;
      frame.classList.add('camera-active');
      photoData = null;
    } catch(e) { alert('No se pudo abrir la cámara. Revisa los permisos o usa Subir imagen.'); }
  }

  function stopCamera() {
    if (stream) { stream.getTracks().forEach(t=>t.stop()); stream=null; }
    frame.classList.remove('camera-active');
  }

  function capturePhoto() {
    const {v,c} = getVideo();
    if (!stream) return false;
    c.width = v.videoWidth||640; c.height = v.videoHeight||480;
    c.getContext('2d').drawImage(v,0,0,c.width,c.height);
    photoData = c.toDataURL('image/png');
    setPreview(photoData);
    c.toBlob(blob=>{ if(blob){ const f=new File([blob],'foto-perfil.png',{type:'image/png'}); const dt=new DataTransfer(); dt.items.add(f); fileInput.files=dt.files; } },'image/png');
    stopCamera();
    return true;
  }

  function setPreview(dataUrl) {
    if (avatarPrev) { avatarPrev.style.backgroundImage='url('+dataUrl+')'; avatarPrev.style.backgroundSize='cover'; avatarPrev.style.backgroundPosition='center'; avatarPrev.textContent=''; }
  }

  function openModal() {
    modal.classList.add('active');
    if (avatarPrev && !photoData) { avatarPrev.style.backgroundImage=''; avatarPrev.textContent=''; }
  }
  function closeModal() { stopCamera(); modal.classList.remove('active'); }

  editBtn.addEventListener('click', openModal);
  btnCancelar.addEventListener('click', closeModal);
  modal.addEventListener('click', e=>{ if(e.target===modal) closeModal(); });
  document.addEventListener('keydown', e=>{ if(e.key==='Escape') closeModal(); });

  [btnGaleria,btnTomar,btnRotar].forEach(function(btn,i){
    btn.addEventListener('click', function(e){
      e.preventDefault();
      [btnGaleria,btnTomar,btnRotar].forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      if (i===0) { stopCamera(); fileInput.click(); }
      if (i===1) { stream ? capturePhoto() : startCamera(); }
      if (i===2) { startCamera(); }
    });
  });

  srcCamera.addEventListener('click', function(){
    srcCamera.classList.add('active'); srcFile.classList.remove('active');
    startCamera();
  });
  srcFile.addEventListener('click', function(){
    srcFile.classList.add('active'); srcCamera.classList.remove('active');
    stopCamera(); fileInput.click();
  });

  fileInput.addEventListener('change', function(){
    const f = fileInput.files[0];
    if (!f) return;
    stopCamera();
    const reader = new FileReader();
    reader.onload = function(ev){ photoData=ev.target.result; setPreview(photoData); };
    reader.readAsDataURL(f);
  });

  btnUsar.addEventListener('click', async function(){
    if (!photoData) { alert('Selecciona una imagen o toma una foto primero.'); return; }

    ava.src = photoData; showImg();

    const headerAva = document.getElementById('headerAvatar');
    if (headerAva) {
      headerAva.style.backgroundImage = 'url(' + photoData + ')';
      headerAva.style.backgroundSize = 'cover';
      headerAva.style.backgroundPosition = 'center';
      headerAva.style.fontSize = '0';
      headerAva.textContent = '';
    }

    closeModal();

    if (fileInput.files && fileInput.files[0]) {
      try {
        const fd = new FormData();
        fd.append('foto', fileInput.files[0]);
        fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content
          || '{{ csrf_token() }}');
        const res = await fetch('{{ route("configuracion.foto.update") }}', {
          method: 'POST',
          body: fd,
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.ok && data.url && headerAva) {
          headerAva.style.backgroundImage = 'url(' + data.url + '?' + Date.now() + ')';
        }
      } catch(e) { console.error('Error subiendo foto:', e); }
    }
  });

  if (delBtn) delBtn.addEventListener('click', async function(){
    photoData = null;
    showEmpty();

    const headerAva = document.getElementById('headerAvatar');
    if (headerAva) {
      headerAva.style.backgroundImage = '';
      headerAva.style.fontSize = '';
      headerAva.textContent = headerAva.dataset.initials || '';
    }

    try {
      await fetch('{{ route("configuracion.foto.delete") }}', {
        method: 'DELETE',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      });
    } catch(e) { console.error('Error eliminando foto:', e); }
  });
})();

(function(){
  const uploadArea = document.getElementById('csfUploadArea');
  const pickBtn    = document.getElementById('csfPickBtn');
  const input      = document.getElementById('csfInput');
  const preview    = document.getElementById('csfPreview');
  const actions    = document.getElementById('csfActions');
  const changeBtn  = document.getElementById('csfChangeBtn');
  const deleteBtn  = document.getElementById('csfDeleteBtn');
  const status     = document.getElementById('csfStatus');
  if (!uploadArea || !input) return;

  function showStatus(msg, type) {
    status.textContent = msg;
    status.className = 'csf-status ' + type;
    status.style.display = 'block';
    setTimeout(function(){ status.style.display = 'none'; }, 3500);
  }

  function showPreviewFromData(url, ext, name) {
    preview.innerHTML = '';
    if (ext === 'pdf') {
      preview.innerHTML =
        '<div class="csf-pdf-card">' +
        '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>' +
        '<div class="csf-pdf-info"><span>' + name + '</span><a href="' + url + '" target="_blank" class="csf-view-link">Ver documento</a></div></div>';
    } else {
      preview.innerHTML = '<img id="csfImg" src="' + url + '" alt="Constancia fiscal">';
    }
    preview.style.display = '';
    uploadArea.style.display = 'none';
    actions.style.display = 'flex';
  }

  async function uploadFile(file) {
    const ext = file.name.split('.').pop().toLowerCase();
    showStatus('Subiendo...', 'ok');
    const fd = new FormData();
    fd.append('constancia', file);
    fd.append('_token', '{{ csrf_token() }}');
    try {
      const res  = await fetch('{{ route("configuracion.constancia.upload") }}', {
        method: 'POST', body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      });
      const data = await res.json();
      if (data.ok) {
        showPreviewFromData(data.url, data.ext, data.name);
        showStatus('Constancia guardada correctamente', 'ok');
      } else {
        showStatus('Error al subir el archivo', 'err');
      }
    } catch(e) {
      showStatus('Error de conexión', 'err');
    }
  }

  uploadArea.addEventListener('click', function(){ input.click(); });
  pickBtn.addEventListener('click', function(e){ e.stopPropagation(); input.click(); });
  changeBtn.addEventListener('click', function(){ input.click(); });

  input.addEventListener('change', function(){
    if (input.files[0]) uploadFile(input.files[0]);
  });

  uploadArea.addEventListener('dragover', function(e){ e.preventDefault(); uploadArea.style.borderColor='var(--cyan)'; });
  uploadArea.addEventListener('dragleave', function(){ uploadArea.style.borderColor=''; });
  uploadArea.addEventListener('drop', function(e){
    e.preventDefault(); uploadArea.style.borderColor='';
    if (e.dataTransfer.files[0]) uploadFile(e.dataTransfer.files[0]);
  });

  deleteBtn.addEventListener('click', async function(){
    showStatus('Eliminando...', 'ok');
    try {
      const res  = await fetch('{{ route("configuracion.constancia.delete") }}', {
        method: 'DELETE',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
      });
      const data = await res.json();
      if (data.ok) {
        preview.innerHTML = '';
        preview.style.display = 'none';
        actions.style.display = 'none';
        uploadArea.style.display = '';
        showStatus('Constancia eliminada', 'ok');
      }
    } catch(e) { showStatus('Error de conexión', 'err'); }
  });
})();
</script>
@endpush
