
<?php
  $qrSettings = $userSettings ?? auth()->user()->resolvedSettings();
  $qrRequiredFields = collect($qrSettings['qr_required_fields'] ?? []);
  $qrFieldOptions = [
    'identificacion' => ['Identificación', 'Documento, folio externo o referencia del paciente.'],
    'sexo' => ['Sexo', 'Dato útil para expediente y atención clínica.'],
    'email' => ['Correo electrónico', 'Contacto alterno para confirmaciones o avisos.'],
    'direccion' => ['Dirección', 'Domicilio del paciente.'],
    'peso' => ['Peso', 'Peso aproximado en kilogramos.'],
    'altura' => ['Altura', 'Altura en metros.'],
    'procedimiento' => ['Procedimiento solicitado', 'Estudio o procedimiento que se realizará.'],
    'motivo_consulta' => ['Motivo de consulta', 'Razón principal de la atención.'],
    'alergias' => ['Alergias', 'Alergias conocidas o “ninguna”.'],
    'enfermedades' => ['Enfermedades actuales', 'Diagnósticos o padecimientos actuales.'],
    'medicamentos_actuales' => ['Medicamentos actuales', 'Tratamientos que toma el paciente.'],
    'antecedentes_medicos' => ['Antecedentes médicos', 'Cirugías, hospitalizaciones o antecedentes relevantes.'],
    'observaciones' => ['Observaciones', 'Notas adicionales del paciente.'],
  ];
?>

<?php $__env->startPush('styles'); ?>
<style>
.qrset-head h2{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.qrset-head p{font-size:13px;color:var(--txt-soft);margin:3px 0 18px}
.qrset-grid{display:grid;grid-template-columns:1.15fr .85fr;gap:18px;align-items:start}
@media(max-width:1020px){.qrset-grid{grid-template-columns:1fr}}
.qrset-stack{display:flex;flex-direction:column;gap:18px}
.qrset-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:700;margin-bottom:5px}
.qrset-sub{font-size:12px;color:var(--txt-soft);line-height:1.45;margin-bottom:14px}
.qrset-control{display:grid;gap:7px;margin-top:13px}
.qrset-control label{font-size:11.5px;font-weight:750;color:var(--txt-soft)}
.qrset-input,.qrset-textarea,.qrset-select{width:100%;font:inherit;font-size:12.5px;color:var(--txt);background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:10px;padding:10px 12px;outline:none}
.qrset-textarea{min-height:92px;resize:vertical;line-height:1.45}
.qrset-textarea.tall{min-height:120px}
.qrset-input:focus,.qrset-textarea:focus,.qrset-select:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(56,199,244,.1)}
.qrset-help{font-size:10.5px;color:var(--txt-soft);line-height:1.45}
.qrset-count{text-align:right;font-size:10px;color:var(--txt-soft);margin-top:-3px}
.qrset-row{display:flex;align-items:center;gap:12px;padding:13px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.qrset-row:last-child{border-bottom:0}
.qrset-row .info{flex:1;min-width:0}
.qrset-row .info b{display:block;font-size:13px}
.qrset-row .info span{display:block;margin-top:2px;font-size:11px;color:var(--txt-soft);line-height:1.35}
.qrset-fields{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:13px}
@media(max-width:680px){.qrset-fields{grid-template-columns:1fr}}
.qrset-check{display:flex;align-items:flex-start;gap:9px;padding:11px;border:1px solid var(--stroke);border-radius:11px;background:var(--panel-2);cursor:pointer}
.qrset-check input{appearance:none;-webkit-appearance:none;width:17px;height:17px;flex:none;margin-top:1px;border:1.5px solid var(--stroke-strong);border-radius:5px;background:var(--card);position:relative}
.qrset-check input:checked{border-color:transparent;background:linear-gradient(135deg,var(--blue),var(--cyan))}
.qrset-check input:checked::after{content:"";position:absolute;left:5px;top:2px;width:4px;height:8px;border:solid #fff;border-width:0 2px 2px 0;transform:rotate(45deg)}
.qrset-check b{display:block;font-size:11.5px}
.qrset-check span{display:block;margin-top:2px;color:var(--txt-soft);font-size:10.5px;line-height:1.35}
.qrset-preview{padding:14px;border:1px solid rgba(56,199,244,.2);border-radius:14px;background:linear-gradient(180deg,rgba(56,199,244,.08),rgba(110,160,255,.04))}
.qrset-preview .k{font-size:10.5px;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.06em}
.qrset-preview .v{font-size:12px;line-height:1.55;margin-top:5px;white-space:pre-wrap}
.qrset-pill{display:inline-flex;align-items:center;gap:6px;margin:5px 5px 0 0;padding:5px 8px;border-radius:99px;background:rgba(110,160,255,.1);font-size:10.5px;color:var(--txt-soft)}
.qrset-note{padding:12px 13px;border-radius:12px;border:1px solid rgba(245,158,45,.24);background:rgba(245,158,45,.08);color:#f6c177;font-size:11.5px;line-height:1.5}
</style>
<?php $__env->stopPush(); ?>

<div class="cfg-panel" data-panel="qr-preregistro">
  <div class="qrset-head">
    <h2>QR y Pre-registro</h2>
    <p>Define cómo se generan los enlaces QR y qué datos debe completar el paciente.</p>
  </div>

  <div class="qrset-grid">
    <div class="qrset-stack">
      <article class="card rise d2">
        <div class="qrset-title">Valores predeterminados del QR</div>
        <p class="qrset-sub">Estos datos se aplican automáticamente al generar un nuevo QR. En el módulo QR aún puedes modificarlos antes de crear el código.</p>

        <div class="qrset-control">
          <label for="cfgQrExpiration">Vigencia predeterminada</label>
          <select class="qrset-select" id="cfgQrExpiration" data-setting="qr_default_expiration_hours">
            <option value="24">24 horas</option>
            <option value="48">48 horas</option>
            <option value="168">7 días</option>
          </select>
        </div>

        <div class="qrset-control">
          <label for="cfgQrMessage">Mensaje predeterminado para el paciente</label>
          <textarea class="qrset-textarea" id="cfgQrMessage" data-setting="qr_default_patient_message" maxlength="150"><?php echo e($qrSettings['qr_default_patient_message'] ?? ''); ?></textarea>
          <div class="qrset-count"><span data-count-for="cfgQrMessage"><?php echo e(mb_strlen($qrSettings['qr_default_patient_message'] ?? '')); ?></span>/150</div>
        </div>

        <div class="qrset-control">
          <label for="cfgQrWhatsapp">Plantilla para WhatsApp</label>
          <textarea class="qrset-textarea" id="cfgQrWhatsapp" data-setting="qr_whatsapp_template" maxlength="500"><?php echo e($qrSettings['qr_whatsapp_template'] ?? ''); ?></textarea>
          <div class="qrset-help">Puedes usar: <strong>{enlace}</strong>, <strong>{codigo}</strong>, <strong>{mensaje}</strong> y <strong>{clinica}</strong>.</div>
        </div>
      </article>

      <article class="card rise d3">
        <div class="qrset-title">Formulario público del paciente</div>
        <p class="qrset-sub">Controla foto, consentimiento y campos obligatorios del formulario que abre el paciente desde su celular.</p>

        <div class="qrset-row">
          <div class="info"><b>Solicitar fotografía del paciente</b><span>Muestra la sección de foto en el formulario público.</span></div>
          <label class="sw"><input type="checkbox" data-setting="qr_patient_photo_enabled"><span class="track"></span><span class="knob"></span></label>
        </div>
        <div class="qrset-row">
          <div class="info"><b>Fotografía obligatoria</b><span>No permite enviar el pre-registro sin foto.</span></div>
          <label class="sw"><input type="checkbox" data-setting="qr_patient_photo_required"><span class="track"></span><span class="knob"></span></label>
        </div>
        <div class="qrset-row">
          <div class="info"><b>Permitir tomar foto con cámara</b><span>En celular abre la cámara frontal cuando el navegador lo permite.</span></div>
          <label class="sw"><input type="checkbox" data-setting="qr_allow_camera_photo"><span class="track"></span><span class="knob"></span></label>
        </div>
        <div class="qrset-row">
          <div class="info"><b>Permitir elegir foto de galería</b><span>Permite subir una imagen ya guardada en el dispositivo.</span></div>
          <label class="sw"><input type="checkbox" data-setting="qr_allow_gallery_photo"><span class="track"></span><span class="knob"></span></label>
        </div>

        <div class="qrset-control">
          <label for="cfgQrConsent">Texto del consentimiento</label>
          <textarea class="qrset-textarea tall" id="cfgQrConsent" data-setting="qr_consent_text" maxlength="700"><?php echo e($qrSettings['qr_consent_text'] ?? ''); ?></textarea>
          <div class="qrset-help">Usa <strong>{clinica}</strong> para insertar el nombre de la clínica automáticamente.</div>
        </div>

        <div class="qrset-control">
          <label>Campos obligatorios adicionales</label>
          <div class="qrset-fields">
            <?php $__currentLoopData = $qrFieldOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fieldKey => [$fieldLabel, $fieldHelp]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <label class="qrset-check">
                <input type="checkbox" value="<?php echo e($fieldKey); ?>" data-setting-list="qr_required_fields" <?php if($qrRequiredFields->contains($fieldKey)): echo 'checked'; endif; ?>>
                <span><b><?php echo e($fieldLabel); ?></b><span><?php echo e($fieldHelp); ?></span></span>
              </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      </article>
    </div>

    <div class="qrset-stack">
      <article class="card rise d4">
        <div class="qrset-title">Duplicados y revisión</div>
        <p class="qrset-sub">Evita crear expedientes repetidos cuando un pre-registro coincide con un paciente existente.</p>

        <div class="qrset-row">
          <div class="info"><b>Detectar pacientes duplicados</b><span>Compara teléfono y correo antes de aceptar un pre-registro.</span></div>
          <label class="sw"><input type="checkbox" data-setting="qr_duplicate_check"><span class="track"></span><span class="knob"></span></label>
        </div>

        <div class="qrset-control">
          <label for="cfgQrDuplicateAction">Acción cuando hay posible duplicado</label>
          <select class="qrset-select" id="cfgQrDuplicateAction" data-setting="qr_duplicate_action">
            <option value="warn">Solo mostrar advertencia</option>
            <option value="block_acceptance">Bloquear aceptación hasta revisar</option>
          </select>
        </div>
      </article>

      <article class="card rise d5">
        <div class="qrset-title">Vista previa de uso</div>
        <p class="qrset-sub">Así se usarán estos ajustes en el módulo QR.</p>
        <div class="qrset-preview">
          <div class="k">WhatsApp</div>
          <div class="v" id="cfgQrWhatsappPreview"></div>
        </div>
        <div style="margin-top:12px" class="qrset-preview">
          <div class="k">Consentimiento</div>
          <div class="v" id="cfgQrConsentPreview"></div>
        </div>
        <div style="margin-top:12px">
          <span class="qrset-pill">Nombre, nacimiento y teléfono siempre obligatorios</span>
          <span class="qrset-pill">QR de un solo uso</span>
          <span class="qrset-pill">Foto se copia al expediente al aceptar</span>
        </div>
      </article>

      <div class="qrset-note rise d5">
        Si marcas “fotografía obligatoria” pero desactivas cámara y galería, el sistema no exigirá foto para no bloquear al paciente. Lo ideal es dejar activa al menos una opción.
      </div>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const clinicName = <?php echo json_encode(auth()->user()->clinica?->nombre ?? 'la clínica', 15, 512) ?>;
  const sampleUrl = 'https://enclaii.app/registro-paciente/ejemplo';
  const sampleCode = 'QR-2026-0001';
  const sampleMessage = 'Por favor completa tus datos con la mayor información posible.';

  function bindCount(id) {
    const input = document.getElementById(id);
    const count = document.querySelector(`[data-count-for="${id}"]`);
    if (!input || !count) return;
    const update = () => { count.textContent = input.value.length; };
    input.addEventListener('input', update);
    update();
  }

  function renderTemplate(value) {
    return (value || '')
      .replaceAll('{enlace}', sampleUrl)
      .replaceAll('{codigo}', sampleCode)
      .replaceAll('{mensaje}', sampleMessage)
      .replaceAll('{clinica}', clinicName);
  }

  function bindPreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (!input || !preview) return;
    const update = () => { preview.textContent = renderTemplate(input.value) || 'Sin texto configurado.'; };
    input.addEventListener('input', update);
    setTimeout(update, 40);
  }

  bindCount('cfgQrMessage');
  bindPreview('cfgQrWhatsapp', 'cfgQrWhatsappPreview');
  bindPreview('cfgQrConsent', 'cfgQrConsentPreview');
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/configuracion/sections/qr-preregistro.blade.php ENDPATH**/ ?>