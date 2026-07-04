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
    .privacy{display:flex;align-items:flex-start;gap:11px;margin-top:24px;padding:14px;border:1px solid var(--line);border-radius:12px;background:rgba(110,160,255,.05)}.privacy input{width:18px;height:18px;flex:none;margin-top:2px}.privacy label{font-size:11.5px;line-height:1.55;color:var(--muted)}
    .submit{width:100%;margin-top:18px;padding:13px;border:0;border-radius:11px;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-size:14px;font-weight:800;cursor:pointer}.foot{text-align:center;margin-top:15px;color:var(--muted);font-size:10.5px}
    @media(max-width:620px){.wrap{margin-top:18px}.grid{grid-template-columns:1fr}.field.wide{grid-column:span 1}.card{border-radius:17px}}
  </style>
</head>
<body>
<main class="wrap">
  <div class="brand"><b>ENCLA<span>II</span></b><p>{{ $clinicName }}</p></div>
  <form class="card" method="POST" action="{{ route('qr.public.store', ['token' => $token]) }}">
    @csrf
    <h1>Pre-registro de paciente</h1>
    <p class="intro">Completa tus datos con calma. La clínica los revisará antes de crear tu expediente. El enlace vence el {{ format_user_date($expiresAt).' a las '.format_user_time($expiresAt) }}.</p>

    @if($errors->any())
      <div class="errors"><strong>Revisa la información:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="grid">
      <div class="field wide"><label>Nombre completo <span class="req">*</span></label><input name="nombre_completo" value="{{ old('nombre_completo') }}" autocomplete="name" required></div>
      <div class="field"><label>Fecha de nacimiento <span class="req">*</span></label><input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" max="{{ now()->toDateString() }}" required></div>
      <div class="field"><label>Sexo</label><select name="sexo"><option value="">Seleccionar</option><option value="femenino" @selected(old('sexo') === 'femenino')>Femenino</option><option value="masculino" @selected(old('sexo') === 'masculino')>Masculino</option><option value="otro" @selected(old('sexo') === 'otro')>Otro</option></select></div>
      <div class="field"><label>Teléfono <span class="req">*</span></label><input type="tel" name="telefono" value="{{ old('telefono') }}" autocomplete="tel" required></div>
      <div class="field"><label>Correo electrónico</label><input type="email" name="email" value="{{ old('email') }}" autocomplete="email"></div>
      <div class="field"><label>Identificación</label><input name="identificacion" value="{{ old('identificacion') }}"></div>
      <div class="field"><label>Dirección</label><input name="direccion" value="{{ old('direccion') }}" autocomplete="street-address"></div>
      <div class="field"><label>Peso aproximado (kg)</label><input type="number" step=".01" min="1" max="999.99" name="peso" value="{{ old('peso') }}"></div>
      <div class="field"><label>Altura (metros)</label><input type="number" step=".01" min=".3" max="2.99" name="altura" value="{{ old('altura') }}"></div>
    </div>

    <div class="section">
      <h2>Información para la atención</h2>
      <div class="grid">
        <div class="field wide"><label>Procedimiento o estudio solicitado</label><input name="procedimiento" value="{{ old('procedimiento') }}" placeholder="Ej. Endoscopia digestiva alta"></div>
        <div class="field wide"><label>Motivo de consulta</label><textarea name="motivo_consulta">{{ old('motivo_consulta') }}</textarea></div>
        <div class="field wide"><label>Alergias conocidas</label><textarea name="alergias" placeholder="Escribe “Ninguna” si no tienes alergias conocidas">{{ old('alergias') }}</textarea></div>
        <div class="field wide"><label>Enfermedades o diagnósticos actuales</label><textarea name="enfermedades">{{ old('enfermedades') }}</textarea></div>
        <div class="field wide"><label>Medicamentos que tomas actualmente</label><textarea name="medicamentos_actuales">{{ old('medicamentos_actuales') }}</textarea></div>
        <div class="field wide"><label>Antecedentes médicos, cirugías u hospitalizaciones</label><textarea name="antecedentes_medicos">{{ old('antecedentes_medicos') }}</textarea></div>
        <div class="field wide"><label>Observaciones adicionales</label><textarea name="observaciones">{{ old('observaciones') }}</textarea></div>
      </div>
    </div>

    <div class="privacy">
      <input type="checkbox" id="privacyConsent" name="privacy_consent" value="1" @checked(old('privacy_consent')) required>
      <label for="privacyConsent">Autorizo el envío de estos datos a <strong>{{ $clinicName }}</strong> para preparar mi atención y crear mi expediente después de que el personal médico revise la información. <span class="req">*</span></label>
    </div>
    <button class="submit" type="submit">Enviar información de forma segura</button>
    <div class="foot">Este formulario no sustituye una consulta médica ni debe utilizarse para emergencias.</div>
  </form>
</main>
</body>
</html>
