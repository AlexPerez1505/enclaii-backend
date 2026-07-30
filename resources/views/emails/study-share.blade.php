@php
  $paciente = $estudio->paciente;
  $patientName = $paciente?->nombre_completo ?? $estudio->paciente_nombre ?? 'Paciente';
  $studyName = $estudio->tipo ?? 'Estudio';
  $studyFolio = $estudio->folio ?? ('Estudio #'.$estudio->id);
  $studyDate = format_user_date($estudio->fecha) ?: format_user_date($estudio->created_at);
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#172033">
  <div style="max-width:680px;margin:0 auto;padding:28px 18px">
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden">
      <div style="padding:22px 24px;border-bottom:1px solid #e2e8f0">
        <div style="font-size:12px;font-weight:700;color:#2563eb;letter-spacing:.08em;text-transform:uppercase">ENCLAII</div>
        <h1 style="margin:8px 0 0;font-size:20px;line-height:1.35;color:#111827">Estudio compartido</h1>
      </div>

      <div style="padding:24px">
        <p style="margin:0 0 18px;font-size:15px;line-height:1.7;color:#334155">{!! nl2br(e($messageBody)) !!}</p>

        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin:18px 0">
          <div style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:8px">Detalle del estudio</div>
          <div style="font-size:14px;line-height:1.7;color:#111827">
            <strong>Paciente:</strong> {{ $patientName }}<br>
            <strong>Estudio:</strong> {{ $studyName }} - {{ $studyFolio }}<br>
            <strong>Fecha:</strong> {{ $studyDate ?: 'Sin fecha' }}<br>
            <strong>Contenido:</strong> {{ $reportes->count() }} reporte(s), {{ $imagenes->count() }} captura(s), {{ $videos->count() }} video(s)
          </div>
        </div>

        @if($reportes->isNotEmpty())
          <h2 style="margin:24px 0 10px;font-size:16px;color:#111827">Reportes</h2>
          @foreach($reportes as $reporte)
            @php
              $reportText = $reporte->contenido_texto ?: trim(strip_tags((string) $reporte->contenido_html));
            @endphp
            <div style="border:1px solid #e2e8f0;border-radius:12px;padding:14px;margin-bottom:10px;background:#ffffff">
              <div style="font-size:12px;font-weight:700;color:#64748b;margin-bottom:8px">
                Reporte #{{ $reporte->id }} · {{ format_user_date($reporte->created_at) ?: 'Sin fecha' }}
              </div>
              <div style="font-size:14px;line-height:1.7;color:#334155">{!! nl2br(e($reportText ?: 'Sin contenido registrado.')) !!}</div>
            </div>
          @endforeach
        @endif

        @if($imagenes->isNotEmpty())
          <h2 style="margin:24px 0 10px;font-size:16px;color:#111827">Capturas</h2>
          @foreach($imagenes as $item)
            <p style="margin:0 0 10px;font-size:14px;line-height:1.6;color:#334155">
              <a href="{{ $item['url'] }}" style="color:#2563eb;font-weight:700;text-decoration:none">{{ $item['name'] }}</a>
              <span style="color:#64748b">· {{ format_user_date($item['capturado_en']) ?: 'Sin fecha' }}</span>
            </p>
          @endforeach
        @endif

        @if($videos->isNotEmpty())
          <h2 style="margin:24px 0 10px;font-size:16px;color:#111827">Videos</h2>
          @foreach($videos as $item)
            <p style="margin:0 0 10px;font-size:14px;line-height:1.6;color:#334155">
              <a href="{{ $item['url'] }}" style="color:#2563eb;font-weight:700;text-decoration:none">{{ $item['name'] }}</a>
              <span style="color:#64748b">· {{ format_user_date($item['capturado_en']) ?: 'Sin fecha' }}</span>
            </p>
          @endforeach
        @endif

        <p style="margin:24px 0 0;font-size:12px;line-height:1.6;color:#64748b">
          Los enlaces pueden abrirse desde el navegador para visualizar o descargar los archivos.
        </p>

        <p style="margin:18px 0 0;font-size:12px;line-height:1.6;color:#64748b">
          Enviado por {{ $sender->name }} &lt;{{ $sender->email }}&gt;. Para responder, usa este mismo correo.
        </p>
      </div>
    </div>
  </div>
</body>
</html>
