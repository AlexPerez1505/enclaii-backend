@php
  $estudio = $archivo->estudio;
  $paciente = $estudio?->paciente;
  $patientName = $paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'Paciente';
  $studyName = $estudio?->tipo ?? 'Estudio';
  $studyFolio = $estudio?->folio ?? ('Video #'.$archivo->id);
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#172033">
  <div style="max-width:640px;margin:0 auto;padding:28px 18px">
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden">
      <div style="padding:22px 24px;border-bottom:1px solid #e2e8f0">
        <div style="font-size:12px;font-weight:700;color:#2563eb;letter-spacing:.08em;text-transform:uppercase">ENCLAII</div>
        <h1 style="margin:8px 0 0;font-size:20px;line-height:1.35;color:#111827">Video de estudio compartido</h1>
      </div>

      <div style="padding:24px">
        <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#334155">{!! nl2br(e($messageBody)) !!}</p>

        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin:18px 0">
          <div style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:8px">Detalle del archivo</div>
          <div style="font-size:14px;line-height:1.7;color:#111827">
            <strong>Paciente:</strong> {{ $patientName }}<br>
            <strong>Estudio:</strong> {{ $studyName }} - {{ $studyFolio }}<br>
            <strong>Archivo:</strong> {{ $downloadName }}
          </div>
        </div>

        <p style="margin:18px 0 22px;font-size:13px;line-height:1.6;color:#64748b">
          El enlace puede abrirse desde el navegador para visualizar o descargar el video.
        </p>

        <a href="{{ $videoUrl }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;font-weight:700;font-size:14px;padding:12px 18px;border-radius:10px">
          Abrir video
        </a>

        <p style="margin:22px 0 0;font-size:12px;line-height:1.6;color:#64748b">
          Enviado por {{ $sender->name }} &lt;{{ $sender->email }}&gt;. Para responder, usa este mismo correo.
        </p>
      </div>
    </div>
  </div>
</body>
</html>
