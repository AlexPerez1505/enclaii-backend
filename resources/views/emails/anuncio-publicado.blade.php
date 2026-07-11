@php
  $tipoConfig = [
    'notificacion'      => ['color' => '#6366f1', 'badge' => '🔔 Notificación'],
    'anuncios_internos' => ['color' => '#0ea5e9', 'badge' => '📋 Comunicado Interno'],
    'mejoras'           => ['color' => '#10b981', 'badge' => '🚀 Mejoras en Enclaii'],
    'mantenimiento'     => ['color' => '#f59e0b', 'badge' => '⚠️ Mantenimiento'],
    'politicas'         => ['color' => '#8b5cf6', 'badge' => '📄 Política'],
  ];
  $cfg   = $tipoConfig[$anuncio->tipo] ?? ['color' => '#6366f1', 'badge' => '📢 Anuncio'];
  $color = $cfg['color'];
  $badge = $cfg['badge'];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $anuncio->titulo }}</title>
<style>
  body { margin: 0; padding: 0; background: #f4f6f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
  .wrapper { max-width: 620px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
  .header { background: {{ $color }}; padding: 28px 40px 24px; }
  .header .badge { display: inline-block; background: rgba(255,255,255,0.2); color: #fff; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; margin-bottom: 12px; letter-spacing: 0.3px; }
  .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; line-height: 1.3; }
  .header .meta { margin: 8px 0 0; color: rgba(255,255,255,0.75); font-size: 12px; }
  .body { padding: 32px 40px; color: #374151; font-size: 15px; line-height: 1.75; }
  .body p { margin: 0 0 12px; }
  .body ul, .body ol { padding-left: 20px; margin: 0 0 12px; }
  .body strong { color: #111827; }
  .body em { color: #6b7280; }
  .btn-wrap { text-align: center; margin: 28px 0 12px; }
  .btn { display: inline-block; background: {{ $color }}; color: #ffffff !important; text-decoration: none; padding: 13px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; }
  .footer { padding: 20px 40px; background: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 12px; }
</style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <div class="badge">{{ $badge }}</div>
      <h1>{{ $anuncio->titulo }}</h1>
      <div class="meta">{{ config('app.name') }} &mdash; {{ now()->format('d/m/Y') }}</div>
    </div>
    <div class="body">
      {!! $anuncio->contenido !!}
    </div>
    <div class="btn-wrap">
      <a href="{{ url('/dashboard') }}" class="btn">Ver en la plataforma</a>
    </div>
    <div class="footer">
      &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.<br>
      Recibes este correo porque tienes una cuenta activa en Enclaii.
    </div>
  </div>
</body>
</html>
