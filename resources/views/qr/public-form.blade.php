<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>Pre-registro de paciente · ENCLAII</title>
  <style>
    :root{color-scheme:dark;--bg:#06081c;--card:#0e1740;--panel:#0a1030;--line:rgba(110,160,255,.2);--txt:#edf4ff;--muted:#91a5ce;--cyan:#14b8f0;--blue:#3b82f6;--red:#ff7183}
    *{box-sizing:border-box}body{margin:0;background:radial-gradient(circle at 50% -10%,#17255d 0,transparent 35%),var(--bg);color:var(--txt);font-family:Inter,system-ui,-apple-system,sans-serif}
    .wrap{width:min(820px,calc(100% - 26px));margin:28px auto 50px}.brand{text-align:center;margin-bottom:22px}.brand b{font-size:24px;letter-spacing:8px}.brand b span{color:var(--cyan)}.brand p{margin:8px 0 0;color:var(--muted);font-size:12px}
    .card{padding:clamp(20px,4vw,34px);border:1px solid var(--line);border-radius:22px;background:rgba(14,23,64,.96);box-shadow:0 30px 80px rgba(0,0,0,.35)}
    h1{margin:0;font-size:clamp(22px,4vw,30px)}.intro{margin:8px 0 25px;color:var(--muted);line-height:1.55;font-size:13px}.section{margin-top:25px;padding-top:22px;border-top:1px solid var(--line)}.section h2{margin:0 0 15px;font-size:15px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}.field{display:grid;gap:7px}.field.wide{grid-column:span 2}.field label{font-size:11.5px;font-weight:700;color:var(--muted)}.req{color:var(--red)}
    input,select,textarea{width:100%;padding:12px 13px;border:1px solid var(--line);border-radius:10px;outline:none;background:var(--panel);color:var(--txt);font:inherit;font-size:14px}textarea{min-height:90px;resize:vertical}input:focus,select:focus,textarea:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(20,184,240,.1)}
    .errors{padding:13px 15px;margin-bottom:18px;border:1px solid rgba(255,113,131,.35);border-radius:12px;background:rgba(255,113,131,.08);color:#ff9eab;font-size:12px}.errors ul{margin:7px 0 0;padding-left:18px}
    .clinic-message{margin:-10px 0 22px;padding:13px 14px;border:1px solid rgba(20,184,240,.28);border-radius:12px;background:rgba(20,184,240,.07);color:#b8dcf3;font-size:12px;line-height:1.55}.clinic-message strong{display:block;margin-bottom:3px;color:var(--txt)}
    .photo-uploader{display:grid;grid-template-columns:132px 1fr;gap:18px;align-items:center}.photo-preview{position:relative;width:132px;aspect-ratio:1;border:1px dashed rgba(110,160,255,.4);border-radius:18px;overflow:hidden;background:var(--panel);display:grid;place-items:center}.photo-preview img{width:100%;height:100%;object-fit:cover}.photo-placeholder{display:grid;place-items:center;gap:7px;text-align:center;color:var(--muted);font-size:10.5px}.photo-placeholder span:first-child{font-size:34px}.photo-copy{margin:0 0 12px;color:var(--muted);font-size:11.5px;line-height:1.55}.photo-actions{display:flex;gap:9px;flex-wrap:wrap}.photo-button{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 12px;border:1px solid var(--line);border-radius:10px;background:rgba(110,160,255,.06);color:var(--txt);font-size:12px;font-weight:750;cursor:pointer}.photo-button.primary{border-color:rgba(20,184,240,.45);color:#7edcff}.photo-button.danger{color:#ff9eab}.photo-input{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0)}.photo-status{min-height:17px;margin:10px 0 0;color:#66dfa5;font-size:11px}.photo-status.error{color:#ff9eab}
    .privacy{display:flex;align-items:flex-start;gap:11px;margin-top:24px;padding:14px;border:1px solid var(--line);border-radius:12px;background:rgba(110,160,255,.05)}.privacy input{width:18px;height:18px;flex:none;margin-top:2px}.privacy label{font-size:11.5px;line-height:1.55;color:var(--muted)}
    .submit{width:100%;margin-top:18px;padding:13px;border:0;border-radius:11px;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-size:14px;font-weight:800;cursor:pointer}.foot{text-align:center;margin-top:15px;color:var(--muted);font-size:10.5px}
    @media(max-width:620px){.wrap{margin-top:18px}.grid{grid-template-columns:1fr}.field.wide{grid-column:span 1}.card{border-radius:17px}.photo-uploader{grid-template-columns:1fr}.photo-preview{width:min(180px,55vw);margin:auto}.photo-actions{display:grid;grid-template-columns:1fr 1fr}.photo-button.danger{grid-column:span 2}}
  </style>
</head>
<body>
@php
  $requiredFields = $requiredFields ?? collect();
  $fieldRequired = fn(string $field): bool => $requiredFields->contains($field);
  $qrSettings = $qrSettings ?? [];
  $photoEnabled = (bool) ($qrSettings['qr_patient_photo_enabled'] ?? true);
  $allowCameraPhoto = $photoEnabled && (bool) ($qrSettings['qr_allow_camera_photo'] ?? true);
  $allowGalleryPhoto = $photoEnabled && (bool) ($qrSettings['qr_allow_gallery_photo'] ?? true);
  $showPhotoSection = $photoEnabled && ($allowCameraPhoto || $allowGalleryPhoto);
  $photoRequired = $showPhotoSection && (bool) ($qrSettings['qr_patient_photo_required'] ?? false);
  $consentText = ($consentText ?? '') ?: 'Autorizo el envío de estos datos a '.$clinicName.' para preparar mi atención y crear mi expediente después de que el personal médico revise la información.';
@endphp
<main class="wrap">
  <div class="brand"><b>ENCLA<span>II</span></b><p>{{ $clinicName }}</p></div>
  <form class="card" method="POST" enctype="multipart/form-data" action="{{ route('qr.public.store', ['token' => $token]) }}">
    @csrf
    <h1>Pre-registro de paciente</h1>
    <p class="intro">Completa tus datos con calma. La clínica los revisará antes de crear tu expediente. El enlace vence el {{ format_user_date($expiresAt).' a las '.format_user_time($expiresAt) }}.</p>

    @if($patientMessage)
      <div class="clinic-message">
        <strong>Mensaje de la clínica</strong>
        {{ $patientMessage }}
      </div>
    @endif

    @if($errors->any())
      <div class="errors"><strong>Revisa la información:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="grid">
      <div class="field wide"><label>Nombre completo <span class="req">*</span></label><input name="nombre_completo" value="{{ old('nombre_completo') }}" autocomplete="name" required></div>
      <div class="field"><label>Fecha de nacimiento <span class="req">*</span></label><input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" max="{{ now()->toDateString() }}" required></div>
      <div class="field"><label>Sexo @if($fieldRequired('sexo'))<span class="req">*</span>@endif</label><select name="sexo" @required($fieldRequired('sexo'))><option value="">Seleccionar</option><option value="femenino" @selected(old('sexo') === 'femenino')>Femenino</option><option value="masculino" @selected(old('sexo') === 'masculino')>Masculino</option><option value="otro" @selected(old('sexo') === 'otro')>Otro</option></select></div>
      <div class="field"><label>Teléfono <span class="req">*</span></label><input type="tel" name="telefono" value="{{ old('telefono') }}" autocomplete="tel" required></div>
      <div class="field"><label>Correo electrónico @if($fieldRequired('email'))<span class="req">*</span>@endif</label><input type="email" name="email" value="{{ old('email') }}" autocomplete="email" @required($fieldRequired('email'))></div>
      <div class="field"><label>Identificación @if($fieldRequired('identificacion'))<span class="req">*</span>@endif</label><input name="identificacion" value="{{ old('identificacion') }}" @required($fieldRequired('identificacion'))></div>
      <div class="field"><label>Dirección @if($fieldRequired('direccion'))<span class="req">*</span>@endif</label><input name="direccion" value="{{ old('direccion') }}" autocomplete="street-address" @required($fieldRequired('direccion'))></div>
      <div class="field"><label>Peso aproximado (kg) @if($fieldRequired('peso'))<span class="req">*</span>@endif</label><input type="number" step=".01" min="1" max="999.99" name="peso" value="{{ old('peso') }}" @required($fieldRequired('peso'))></div>
      <div class="field"><label>Altura (metros) @if($fieldRequired('altura'))<span class="req">*</span>@endif</label><input type="number" step=".01" min=".3" max="2.99" name="altura" value="{{ old('altura') }}" @required($fieldRequired('altura'))></div>
    </div>

    @if($showPhotoSection)
      <div class="section">
        <h2>Foto del paciente <span style="color:var(--muted);font-weight:500">({{ $photoRequired ? 'obligatoria' : 'opcional' }})</span> @if($photoRequired)<span class="req">*</span>@endif</h2>
        <div class="photo-uploader">
          <div class="photo-preview">
            <img id="patientPhotoPreview" alt="Vista previa de tu foto" hidden>
            <div class="photo-placeholder" id="patientPhotoPlaceholder"><span></span><span>Tu foto aparecerá aquí</span></div>
          </div>
          <div>
            <p class="photo-copy">Puedes tomar una foto con la cámara frontal de tu teléfono o elegir una imagen de tu galería. Procura tener buena iluminación y mostrar claramente tu rostro.</p>
            @if($allowCameraPhoto)
              <input class="photo-input" type="file" id="patientPhotoCamera" accept="image/*" capture="user">
            @endif
            @if($allowGalleryPhoto)
              <input class="photo-input" type="file" id="patientPhotoGallery" accept="image/*">
            @endif
            <input class="photo-input" type="file" id="patientPhotoUpload" name="foto_upload" accept="image/jpeg,image/png,image/webp">
            <div class="photo-actions">
              @if($allowCameraPhoto)
                <label class="photo-button primary" for="patientPhotoCamera">Tomar foto</label>
              @endif
              @if($allowGalleryPhoto)
                <label class="photo-button" for="patientPhotoGallery">Elegir de galería</label>
              @endif
              <button class="photo-button danger" id="removePatientPhoto" type="button" hidden>Quitar foto</button>
            </div>
            <p class="photo-status" id="patientPhotoStatus" aria-live="polite">Formatos JPG, PNG o WebP. Máximo 4 MB.</p>
          </div>
        </div>
      </div>
    @endif

    <div class="section">
      <h2>Información para la atención</h2>
      <div class="grid">
        <div class="field wide"><label>Procedimiento o estudio solicitado @if($fieldRequired('procedimiento'))<span class="req">*</span>@endif</label><input name="procedimiento" value="{{ old('procedimiento') }}" placeholder="Ej. Endoscopia digestiva alta" @required($fieldRequired('procedimiento'))></div>
        <div class="field wide"><label>Motivo de consulta @if($fieldRequired('motivo_consulta'))<span class="req">*</span>@endif</label><textarea name="motivo_consulta" @required($fieldRequired('motivo_consulta'))>{{ old('motivo_consulta') }}</textarea></div>
        <div class="field wide"><label>Alergias conocidas @if($fieldRequired('alergias'))<span class="req">*</span>@endif</label><textarea name="alergias" placeholder="Escribe “Ninguna” si no tienes alergias conocidas" @required($fieldRequired('alergias'))>{{ old('alergias') }}</textarea></div>
        <div class="field wide"><label>Enfermedades o diagnósticos actuales @if($fieldRequired('enfermedades'))<span class="req">*</span>@endif</label><textarea name="enfermedades" @required($fieldRequired('enfermedades'))>{{ old('enfermedades') }}</textarea></div>
        <div class="field wide"><label>Medicamentos que tomas actualmente @if($fieldRequired('medicamentos_actuales'))<span class="req">*</span>@endif</label><textarea name="medicamentos_actuales" @required($fieldRequired('medicamentos_actuales'))>{{ old('medicamentos_actuales') }}</textarea></div>
        <div class="field wide"><label>Antecedentes médicos, cirugías u hospitalizaciones @if($fieldRequired('antecedentes_medicos'))<span class="req">*</span>@endif</label><textarea name="antecedentes_medicos" @required($fieldRequired('antecedentes_medicos'))>{{ old('antecedentes_medicos') }}</textarea></div>
        <div class="field wide"><label>Observaciones adicionales @if($fieldRequired('observaciones'))<span class="req">*</span>@endif</label><textarea name="observaciones" @required($fieldRequired('observaciones'))>{{ old('observaciones') }}</textarea></div>
      </div>
    </div>

    <div class="privacy">
      <input type="checkbox" id="privacyConsent" name="privacy_consent" value="1" @checked(old('privacy_consent')) required>
      <label for="privacyConsent">{{ $consentText }} <span class="req">*</span></label>
    </div>
    <button class="submit" type="submit">Enviar información de forma segura</button>
    <div class="foot">Este formulario no sustituye una consulta médica ni debe utilizarse para emergencias.</div>
  </form>
</main>
<script>
(() => {
  const cameraInput = document.getElementById('patientPhotoCamera');
  const galleryInput = document.getElementById('patientPhotoGallery');
  const uploadInput = document.getElementById('patientPhotoUpload');
  const form = document.querySelector('form');
  const submitButton = form?.querySelector('.submit');
  const preview = document.getElementById('patientPhotoPreview');
  const placeholder = document.getElementById('patientPhotoPlaceholder');
  const removeButton = document.getElementById('removePatientPhoto');
  const status = document.getElementById('patientPhotoStatus');
  if (!preview || !placeholder || !removeButton || !status || !uploadInput) return;

  const inputs = [cameraInput, galleryInput].filter(Boolean);
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/heic', 'image/heif', ''];
  const uploadMaxSize = 4 * 1024 * 1024;
  const maxSize = 1024 * 1024 * 1024;
  let previewUrl = null;
  let selectedFile = null;

  function clearPreview(message = 'Formatos JPG, PNG o WebP. Máximo 4 MB.') {
    inputs.forEach(input => { input.value = ''; });
    uploadInput.value = '';
    selectedFile = null;
    if (previewUrl) URL.revokeObjectURL(previewUrl);
    previewUrl = null;
    preview.removeAttribute('src');
    preview.hidden = true;
    placeholder.hidden = false;
    removeButton.hidden = true;
    status.textContent = message;
    status.classList.remove('error');
  }

  function showPhoto(input) {
    const file = input.files?.[0];
    if (!file) return;

    if (!allowedTypes.includes(file.type) || file.size > maxSize) {
      clearPreview(file.size > maxSize
        ? 'La foto supera el límite de 4 MB.'
        : 'Selecciona una imagen JPG, PNG o WebP.');
      status.classList.add('error');
      return;
    }

    inputs.filter(other => other !== input).forEach(other => { other.value = ''; });
    if (previewUrl) URL.revokeObjectURL(previewUrl);
    previewUrl = URL.createObjectURL(file);
    preview.src = previewUrl;
    preview.hidden = false;
    placeholder.hidden = true;
    removeButton.hidden = false;
    selectedFile = file;
    status.textContent = file.size > uploadMaxSize
      ? 'La foto se optimizara antes de enviarse.'
      : 'Foto lista para enviar.';
    status.classList.remove('error');
  }

  function canvasToBlob(canvas, quality) {
    return new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
  }

  async function normalizePhoto(file) {
    if (file.type === 'image/jpeg' && file.size <= uploadMaxSize) return file;

    const url = URL.createObjectURL(file);
    const image = new Image();
    image.decoding = 'async';

    try {
      await new Promise((resolve, reject) => {
        image.onload = resolve;
        image.onerror = reject;
        image.src = url;
      });

      const maxSide = 1600;
      const width = image.naturalWidth || image.width;
      const height = image.naturalHeight || image.height;
      const scale = Math.min(1, maxSide / Math.max(width, height));
      const canvas = document.createElement('canvas');
      canvas.width = Math.max(1, Math.round(width * scale));
      canvas.height = Math.max(1, Math.round(height * scale));
      canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);

      for (const quality of [0.86, 0.74, 0.62, 0.5, 0.42]) {
        const blob = await canvasToBlob(canvas, quality);
        if (blob && blob.size <= uploadMaxSize) {
          return new File([blob], 'foto-paciente.jpg', { type: 'image/jpeg' });
        }
      }

      const blob = await canvasToBlob(canvas, 0.36);
      if (!blob) throw new Error('No se pudo convertir la foto.');
      return new File([blob], 'foto-paciente.jpg', { type: 'image/jpeg' });
    } finally {
      URL.revokeObjectURL(url);
    }
  }

  async function prepareUpload() {
    if (!selectedFile) return true;

    try {
      status.textContent = 'Preparando foto...';
      status.classList.remove('error');
      const normalized = await normalizePhoto(selectedFile);

      if (normalized.size > uploadMaxSize) {
        clearPreview('La foto sigue superando el limite de 4 MB. Intenta con otra imagen.');
        status.classList.add('error');
        return false;
      }

      const transfer = new DataTransfer();
      transfer.items.add(normalized);
      uploadInput.files = transfer.files;
      status.textContent = 'Foto lista para enviar.';
      return true;
    } catch (error) {
      clearPreview('No se pudo preparar la foto. Intenta elegir otra imagen.');
      status.classList.add('error');
      return false;
    }
  }

  inputs.forEach(input => input.addEventListener('change', () => showPhoto(input)));
  removeButton.addEventListener('click', () => clearPreview());
  form?.addEventListener('submit', async (event) => {
    if (!selectedFile) return;

    event.preventDefault();
    submitButton?.setAttribute('disabled', 'disabled');

    const ready = await prepareUpload();
    if (ready) {
      form.submit();
      return;
    }

    submitButton?.removeAttribute('disabled');
  });
})();
</script>
</body>
</html>
