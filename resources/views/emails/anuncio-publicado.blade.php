@php
  $logoUrl = asset('images/logo-dark.png');

  $tipoConfig = [
    'notificacion'      => ['color' => '#6366f1', 'glow' => 'rgba(99,102,241,0.45)',  'badge' => 'Notificación'],
    'anuncios_internos' => ['color' => '#0ea5e9', 'glow' => 'rgba(14,165,233,0.45)',  'badge' => 'Comunicado Interno'],
    'mejoras'           => ['color' => '#10b981', 'glow' => 'rgba(16,185,129,0.45)',  'badge' => 'Mejoras en Enclaii'],
    'mantenimiento'     => ['color' => '#f59e0b', 'glow' => 'rgba(245,158,11,0.45)',  'badge' => 'Aviso de Mantenimiento'],
    'politicas'         => ['color' => '#8b5cf6', 'glow' => 'rgba(139,92,246,0.45)', 'badge' => 'Documento de Política'],
  ];
  $cfg   = $tipoConfig[$anuncio->tipo] ?? ['color' => '#6366f1', 'glow' => 'rgba(99,102,241,0.45)', 'badge' => 'Anuncio'];
  $color = $cfg['color'];
  $glow  = $cfg['glow'];
  $badge = $cfg['badge'];
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $anuncio->titulo }}</title>
<style>
  body { margin:0; padding:0; background:#06081C; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
  .outer { padding: 40px 16px; }
  .wrapper {
    max-width: 620px;
    margin: 0 auto;
    background: #0A0F2E;
    border-radius: 16px;
    overflow: hidden;
    border: 2.5px solid {{ $color }};
    box-shadow: 0 0 48px {{ $glow }}, 0 0 16px {{ $glow }}, 0 0 80px {{ $glow }}, inset 0 0 20px rgba(0,0,0,0.4);
  }
  .header {
    padding: 32px 36px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
  }
  .badge {
    display: inline-block;
    color: {{ $color }};
    font-size: 12px;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 20px;
    border: 2px solid {{ $color }};
    background: rgba(255,255,255,0.04);
    margin-bottom: 14px;
    letter-spacing: 0.4px;
  }
  .header h1 {
    margin: 0 0 10px;
    color: #ffffff;
    font-size: 22px;
    font-weight: 800;
    line-height: 1.3;
    letter-spacing: -0.3px;
  }
  .divider {
    height: 2px;
    background: linear-gradient(90deg, {{ $color }}, rgba(255,255,255,0.05), transparent);
    margin: 4px 0 0;
    border-radius: 2px;
    box-shadow: 0 0 8px {{ $glow }};
  }
  .meta {
    margin-top: 10px;
    color: {{ $color }};
    font-size: 12px;
    font-weight: 500;
  }
  .body {
    padding: 28px 36px;
    color: #c9d4e8;
    font-size: 15px;
    line-height: 1.8;
  }
  .body p { margin: 0 0 12px; }
  .body ul, .body ol { padding-left: 22px; margin: 0 0 12px; }
  .body li { margin-bottom: 4px; }
  .body strong { color: #ffffff; }
  .body em { color: #94a3b8; }
  .body a { color: {{ $color }}; text-decoration: underline; }
  .btn-wrap { text-align: center; padding: 8px 36px 32px; }
  .btn {
    display: inline-block;
    background: transparent;
    color: {{ $color }} !important;
    text-decoration: none;
    padding: 12px 32px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    border: 2px solid {{ $color }};
    box-shadow: 0 0 12px {{ $glow }};
    letter-spacing: 0.3px;
  }
  .footer {
    padding: 18px 36px;
    background: rgba(0,0,0,0.25);
    border-top: 1px solid rgba(255,255,255,0.06);
    text-align: center;
    color: #4b5a7a;
    font-size: 11px;
    line-height: 1.6;
  }
  .footer span { color: {{ $color }}; font-weight: 600; }
  .brand {
    padding: 20px 36px;
    background: #0D1438;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    display: table;
    width: 100%;
    box-sizing: border-box;
  }
  .brand-left  { display: table-cell; vertical-align: middle; }
  .brand-right { display: table-cell; vertical-align: middle; text-align: right; width: 56px; }
  .brand-right img { width: 44px; height: auto; opacity: 0.92; }
  .brand-wordmark {
    font-size: 24px;
    font-weight: 900;
    letter-spacing: 6px;
    text-transform: uppercase;
    color: #ffffff;
    text-decoration: none;
    line-height: 1;
  }
  .brand-wordmark span { color: #3b82f6; }
  .brand-sub {
    font-size: 10px;
    color: #4b5a7a;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    margin-top: 4px;
  }
</style>
</head>
<body>
  <div class="outer">
    <div class="wrapper">
      <div class="brand">
        <div class="brand-left">
          <div class="brand-wordmark">ENCLA<span>II</span></div>
          <div class="brand-sub">ENDOSCOPIA &bull; NUBE &bull; IA</div>
        </div>
        <div class="brand-right">
          <img src="{{ $logoUrl }}" alt="Enclaii">
        </div>
      </div>
      <div class="header">
        <div class="badge">{{ $badge }}</div>
        <h1>{{ $anuncio->titulo }}</h1>
        <div class="divider"></div>
        <div class="meta">{{ now()->format('d/m/Y') }}</div>
      </div>
      <div class="body">
        {!! $anuncio->contenido !!}
      </div>
      <div class="btn-wrap">
        <a href="{{ url('/dashboard') }}" class="btn">Ver en la plataforma →</a>
      </div>
      <div class="footer">
        &copy; {{ date('Y') }} <span>{{ config('app.name') }}</span>. Todos los derechos reservados.<br>
        Recibes este correo porque tienes una cuenta activa en la plataforma.
      </div>
    </div>
  </div>
</body>
</html>
