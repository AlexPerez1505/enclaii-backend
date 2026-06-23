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
.pf-ico-field .pf-input{padding-left:38px}
.pf-ico-field svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--txt-soft);pointer-events:none}

.pf-2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media (max-width:520px){.pf-2{grid-template-columns:1fr}}

/* Foto de perfil */
.pf-photo{display:flex;flex-direction:column;align-items:center;text-align:center}
.pf-photo .pf-ava-wrap{position:relative;width:min(230px,80%);aspect-ratio:1/1;margin:10px 0 22px}
.pf-photo .pf-ava{display:block;width:100%;height:100%;aspect-ratio:1/1;border-radius:50%;object-fit:cover;border:3px solid var(--stroke-strong);background:var(--panel-2)}
.pf-photo-btns{display:flex;flex-direction:column;align-items:stretch;gap:10px;width:100%;max-width:230px}
.pf-edit-btn,.pf-del{display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px 16px;border-radius:var(--r-md);font-size:12.5px;font-weight:700;cursor:pointer;transition:background-color .15s}
.pf-edit-btn svg,.pf-del svg{width:15px;height:15px}
.pf-edit-btn{border:1px solid rgba(56,199,244,.35);color:var(--cyan);background:rgba(56,199,244,.08)}
.pf-del{border:1px solid rgba(255,90,110,.35);color:var(--red);background:rgba(255,90,110,.08)}
@media (hover:hover){.pf-edit-btn:hover{background:rgba(56,199,244,.18)}.pf-del:hover{background:rgba(255,90,110,.18)}}
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
        <div class="pf-ava-wrap">
          <img class="pf-ava" id="pfAva" src="{{ asset('images/doctor.png') }}" alt="Foto de perfil">
          <div class="pf-ava pf-ava-empty" id="pfEmpty" style="display:none;place-items:center;color:var(--txt-soft)">
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

    <article class="card rise d3">
      <div class="pf-title">1.- Información de la cuenta</div>
      <div class="pf-2">
        <div class="pf-field"><label>Nombre(s)</label><input class="pf-input" type="text" value="Victor"></div>
        <div class="pf-field"><label>Apellido paterno</label><input class="pf-input" type="text" value="Hernandez"></div>
        <div class="pf-field"><label>Apellido materno</label><input class="pf-input" type="text" value="Mapa"></div>
        <div class="pf-field"><label>Correo electrónico</label><input class="pf-input" type="email" value="victor@gmail.com"></div>
        <div class="pf-field"><label>Teléfono</label><input class="pf-input" type="tel" value="+52 722 435 5385"></div>
        <div class="pf-field"><label>Fecha de nacimiento</label>
          <div class="pf-ico-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <input class="pf-input" type="text" value="12/05/85">
          </div>
        </div>
      </div>
      <div class="pf-field" style="margin-top:14px;max-width:calc(50% - 7px)">
        <label>Sexo</label>
        <div class="pf-select">
          <select><option>Masculino</option><option>Femenino</option><option>Otro</option></select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>
    </article>

    <article class="card rise d4">
      <div class="pf-title">2.- Información profesional</div>
      <div class="pf-field"><label>Especialidad</label><input class="pf-input" type="text" value="Endoscopia"></div>
      <div class="pf-field"><label>Subespecialidad (opcional)</label><input class="pf-input" type="text" value="Endoscopia terapeutica"></div>
      <div class="pf-field"><label>Cédula profesional</label><input class="pf-input" type="text" value="9876543"></div>
      <div class="pf-field"><label>Universidad</label><input class="pf-input" type="text" value="Universidad Nacional de Mexico"></div>
    </article>
  </div>

  {{-- Fila inferior --}}
  <div class="pf-bottom">
    <article class="card rise d4">
      <div class="pf-title">3.- Información de la clínica</div>
      <div class="pf-2">
        <div class="pf-field"><label>Nombre de la clínica</label><input class="pf-input" type="text" value="Medibuy - Centro de endoscopia"></div>
        <div class="pf-field"><label>Ciudad</label><input class="pf-input" type="text" value="Toluca"></div>
        <div class="pf-field"><label>Dirección</label><input class="pf-input" type="text" value="Av. Partidas Las torres 123, Consultorio 121"></div>
        <div class="pf-field"><label>Código postal</label><input class="pf-input" type="text" value="50080"></div>
        <div class="pf-field"><label>Teléfono de la clínica u hospital</label><input class="pf-input" type="tel" value="722 364 4758"></div>
        <div class="pf-field"><label>Estado</label><input class="pf-input" type="text" value="Estado de Mexico"></div>
      </div>
    </article>

    <article class="card rise d5">
      <div class="pf-title">4.- Información fiscal <small>(opcional)</small></div>
      <div class="pf-2">
        <div class="pf-field"><label>RFC</label><input class="pf-input" type="text" value="378274289"></div>
        <div class="pf-field"><label>Razón social</label><input class="pf-input" type="text" value="Victor Hernandez"></div>
      </div>
      <div class="pf-field" style="margin-top:14px">
        <label>Régimen fiscal</label>
        <div class="pf-select">
          <select>
            <option>612 - Personas Físicas con Actividades Empresariales</option>
            <option>626 - Régimen Simplificado de Confianza</option>
            <option>605 - Sueldos y Salarios</option>
          </select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>
      <div class="pf-field"><label>Correo de facturación</label><input class="pf-input" type="email" value="Facturacion@gmail.com"></div>
    </article>
  </div>

</div>

@push('scripts')
<script>
(function(){
  const edit = document.getElementById('pfEdit');
  const del = document.getElementById('pfDel');
  const file = document.getElementById('pfPhoto');
  const ava = document.getElementById('pfAva');
  const empty = document.getElementById('pfEmpty');
  if (!ava || !empty) return;

  const showImg = () => { ava.style.display = 'block'; empty.style.display = 'none'; };
  const showEmpty = () => { ava.style.display = 'none'; empty.style.display = 'grid'; };

  if (edit && file) {
    edit.addEventListener('click', () => file.click());
    file.addEventListener('change', () => {
      const f = file.files && file.files[0];
      if (!f || !f.type.startsWith('image/')) return;
      const reader = new FileReader();
      reader.onload = e => { ava.src = e.target.result; showImg(); };
      reader.readAsDataURL(f);
    });
  }
  if (del) del.addEventListener('click', () => { ava.removeAttribute('src'); showEmpty(); });
})();
</script>
@endpush
