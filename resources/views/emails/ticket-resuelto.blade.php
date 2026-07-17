@php
  $logoUrl = asset('images/logo-dark.png');
  $userName = trim(($ticket->user?->name ?? '') . ' ' . ($ticket->user?->apellido_paterno ?? ''));
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ticket resuelto: {{ $ticket->subject }}</title>
<style>
  body { margin:0; padding:0; background:#06081C; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
  .outer { padding: 40px 16px; }
  .wrapper {
    max-width: 620px;
    margin: 0 auto;
    background: #0A0F2E;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid rgba(34,197,94,.2);
  }
  .header {
    padding: 32px 28px;
    background: linear-gradient(135deg, rgba(34,197,94,.15), rgba(59,130,246,.08));
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .header-icon {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: rgba(34,197,94,.15);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .header-icon svg { width: 26px; height: 26px; }
  .header h1 { margin: 0; font-size: 20px; color: #e2e8f0; font-weight: 800; }
  .header p { margin: 4px 0 0; font-size: 13px; color: #94a3b8; }
  .body { padding: 28px; }
  .field { margin-bottom: 22px; }
  .field-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
  .field-value { font-size: 15px; color: #e2e8f0; line-height: 1.6; word-break: break-word; }
  .badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 99px;
    font-size: 12px; font-weight: 700;
    background: rgba(34,197,94,.15); color: #4ade80;
  }
  .sep { border: 0; border-top: 1px solid rgba(255,255,255,.08); margin: 22px 0; }
  .footer { padding: 20px 28px; text-align: center; font-size: 12px; color: #475569; }
  .footer a { color: #3b82f6; text-decoration: none; }
</style>
</head>
<body>
<div class="outer">
  <div class="wrapper">
    <div class="header">
      <div class="header-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      </div>
      <div>
        <h1>Tu ticket ha sido resuelto</h1>
        <p>{{ $ticket->operation_folio }}</p>
      </div>
    </div>
    <div class="body">
      <div class="field">
        <div class="field-label">Asunto</div>
        <div class="field-value">{{ $ticket->subject }}</div>
      </div>
      <div class="field">
        <div class="field-label">Estado</div>
        <div class="field-value"><span class="badge">Resuelto</span></div>
      </div>
      <div class="field">
        <div class="field-label">Tipo de solución</div>
        <div class="field-value">{{ $typeLabel }}</div>
      </div>
      <div class="field">
        <div class="field-label">Solución aplicada</div>
        <div class="field-value">{{ $ticket->resolution_summary }}</div>
      </div>
      <div class="field">
        <div class="field-label">Resuelto por</div>
        <div class="field-value">{{ $ticket->resolver?->name }} {{ $ticket->resolver?->apellido_paterno }}</div>
      </div>
      <div class="field">
        <div class="field-label">Fecha</div>
        <div class="field-value">{{ $ticket->resolved_at?->format('d M Y \a \l\a\s h:i A') }}</div>
      </div>
    </div>
    <div class="footer">
      <p>Este correo fue enviado automáticamente por el sistema de soporte de Enclaii.</p>
      <p><a href="{{ route('soporte.tickets.show', $ticket) }}">Ver ticket en la plataforma</a></p>
    </div>
  </div>
</div>
</body>
</html>
