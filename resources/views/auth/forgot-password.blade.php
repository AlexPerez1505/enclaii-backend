<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="{{ asset('images/enclaii-favicon.png') }}?v=4">
<link rel="shortcut icon" type="image/png" href="{{ asset('images/enclaii-favicon.png') }}?v=4">
<title>Recuperar contraseña — ENCLAII</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --navy-900:#070F2E;
  --navy-800:#0B1A4A;
  --navy-700:#12266B;
  --blue-600:#1E5AE8;
  --blue-500:#2E7BF6;
  --cyan-400:#38C7F4;
  --ink:#0E1530;
  --ink-soft:#4A5578;
  --surface:#F7F9FE;
  --field:#EEF3FC;
  --field-border:#D7E2F5;
  --error:#D93B4A;
  --error-soft:#ffebeb;
  --success:#15803d;
  --success-soft:#e6ffe6;
  --r-lg:22px;
  --r-md:14px;
  --ease-out:cubic-bezier(0.23,1,0.32,1);
  --ease-in-out:cubic-bezier(0.77,0,0.175,1);
}
*{margin:0;padding:0;box-sizing:border-box}
html,body{min-height:100%}
body{
  font-family:'Hanken Grotesk',sans-serif;
  background:var(--navy-900);
  color:var(--ink);
  -webkit-font-smoothing:antialiased;
}
.ec-shell{
  display:grid;
  grid-template-columns:minmax(420px,560px) 1fr;
  min-height:100vh;
}
.ec-form-panel{
  background:#ffffff;
  border-radius:0 28px 28px 0;
  display:flex;
  flex-direction:column;
  justify-content:center;
  padding:56px clamp(32px,6vw,88px);
  position:relative;
  z-index:2;
}
.ec-brand{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:10px;
  margin-bottom:30px;
}
.ec-brand img{width:190px;height:auto;margin-bottom:-25px}
.ec-brand-name{
  font-family:'Sora',sans-serif;
  font-weight:800;
  font-size:26px;
  letter-spacing:.42em;
  color:var(--ink);
  text-align:center;
}
.ec-brand-name span{color:var(--blue-500)}
.ec-brand-tag{
  font-size:10px;font-weight:600;letter-spacing:.28em;
  text-transform:uppercase;color:var(--ink-soft);text-align:center;
}
.ec-title{
  font-family:'Sora',sans-serif;font-weight:700;
  font-size:clamp(24px,3vw,34px);letter-spacing:-0.02em;color:var(--ink);
}
.ec-subtitle{
  margin-top:8px;font-size:15px;color:var(--ink-soft);
  margin-bottom:32px;line-height:1.55;
}
.ec-field{margin-bottom:22px}
.ec-label{display:block;font-weight:600;font-size:14px;margin-bottom:8px;color:var(--ink)}
.ec-input-wrap{position:relative}
.ec-input{
  width:100%;height:54px;padding:0 16px;font:inherit;font-size:15px;
  color:var(--ink);background:var(--field);border:1.5px solid var(--field-border);
  border-radius:var(--r-md);outline:none;
  transition:border-color 150ms var(--ease-out),box-shadow 150ms var(--ease-out),background-color 150ms var(--ease-out);
}
.ec-input::placeholder{color:#9AA6C4}
.ec-input:focus{
  background:#ffffff;border-color:var(--blue-500);
  box-shadow:0 0 0 4px rgba(46,123,246,.14);
}
.ec-input.is-invalid{border-color:var(--error)}
.ec-input.is-invalid:focus{box-shadow:0 0 0 4px rgba(217,59,74,.12)}
.ec-error{
  display:flex;align-items:center;gap:6px;margin-top:8px;
  font-size:13px;font-weight:600;color:var(--error);
}
.ec-alert{
  border-radius:14px;padding:12px 14px;margin-bottom:18px;
  font-size:14px;line-height:1.45;font-weight:600;
}
.ec-alert.success{background:var(--success-soft);color:var(--success);border:1px solid #c9f7d0}
.ec-alert.error{background:var(--error-soft);color:var(--error);border:1px solid #ffd3d8}
.ec-btn{
  position:relative;width:100%;height:56px;border:0;border-radius:var(--r-md);
  font-family:'Sora',sans-serif;font-weight:700;font-size:15px;letter-spacing:.04em;
  color:#ffffff;cursor:pointer;
  background:linear-gradient(135deg,var(--navy-800) 0%,var(--navy-700) 55%,var(--blue-600) 130%);
  box-shadow:0 10px 24px -10px rgba(11,26,74,.55);
  transition:transform 160ms var(--ease-out);overflow:hidden;
}
.ec-btn:active{transform:scale(.97)}
.ec-btn::after{
  content:'';position:absolute;inset:0;border-radius:inherit;
  box-shadow:0 14px 30px -10px rgba(30,90,232,.5);
  opacity:0;transition:opacity 200ms var(--ease-out);pointer-events:none;
}
@media(hover:hover) and (pointer:fine){.ec-btn:hover::after{opacity:1}}
.ec-btn[data-loading="true"]{pointer-events:none}
.ec-btn-content{display:inline-flex;align-items:center;gap:10px;transition:opacity 200ms ease,filter 200ms ease}
.ec-btn[data-loading="true"] .ec-btn-content{opacity:.55;filter:blur(2px)}
.ec-spinner{position:absolute;inset:0;display:grid;place-items:center;opacity:0;transition:opacity 200ms ease}
.ec-btn[data-loading="true"] .ec-spinner{opacity:1}
.ec-spinner svg{width:22px;height:22px;animation:ec-spin .6s linear infinite}
@keyframes ec-spin{to{transform:rotate(360deg)}}
.ec-back-link{
  display:inline-flex;align-items:center;gap:6px;
  margin-top:20px;font-size:14px;font-weight:700;
  color:var(--blue-600);text-decoration:none;
  transition:color 150ms ease;
}
.ec-back-link:hover{text-decoration:underline}
.ec-back-link svg{width:16px;height:16px}
.ec-stagger{opacity:0;transform:translateY(12px);animation:ec-rise 450ms var(--ease-out) forwards}
.ec-stagger:nth-child(1){animation-delay:0ms}
.ec-stagger:nth-child(2){animation-delay:55ms}
.ec-stagger:nth-child(3){animation-delay:110ms}
.ec-stagger:nth-child(4){animation-delay:165ms}
.ec-stagger:nth-child(5){animation-delay:220ms}
@keyframes ec-rise{to{opacity:1;transform:translateY(0)}}
.ec-visual{
  position:relative;overflow:hidden;
  background:
    radial-gradient(900px 600px at 80% 15%,rgba(56,199,244,.18),transparent 60%),
    radial-gradient(700px 700px at 15% 85%,rgba(30,90,232,.25),transparent 65%),
    linear-gradient(160deg,var(--navy-800),var(--navy-900) 70%);
  display:grid;place-items:center;
}
.ec-visual::after{
  content:'';position:absolute;inset:0;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
  pointer-events:none;mix-blend-mode:overlay;
}
.ec-ring{
  position:absolute;border:1.5px solid rgba(120,170,255,.55);border-radius:50%;
  box-shadow:0 0 24px rgba(56,199,244,.25),inset 0 0 24px rgba(56,199,244,.15);
  opacity:.12;animation:ec-ring-pulse 5s var(--ease-in-out) infinite;
}
.ec-ring.r1{width:540px;height:540px}
.ec-ring.r2{width:760px;height:760px;border-style:dashed;animation-delay:2.5s}
@keyframes ec-ring-pulse{0%,100%{opacity:.12}50%{opacity:.85}}
.ec-visual-label{
  position:relative;z-index:1;text-align:center;
  color:rgba(255,255,255,.72);font-size:15px;line-height:1.7;
  max-width:320px;
}
.ec-visual-label strong{
  display:block;font-family:'Sora',sans-serif;font-weight:700;
  font-size:22px;color:#fff;letter-spacing:-.01em;margin-bottom:8px;
}
@media(max-width:1024px){
  html,body{background:#ffffff}
  .ec-shell{display:block;min-height:100svh}
  .ec-visual{display:none!important}
  .ec-form-panel{border-radius:0;min-height:100svh;padding:20px 24px;justify-content:center}
  .ec-brand{margin-bottom:18px;gap:7px}
  .ec-brand img{width:104px;margin-bottom:-14px}
  .ec-brand-name{font-size:17px;letter-spacing:.34em}
  .ec-brand-tag{font-size:7.5px;letter-spacing:.24em;white-space:nowrap}
  .ec-title{font-size:24px}
  .ec-subtitle{font-size:13.5px;margin-top:4px;margin-bottom:20px}
  .ec-field{margin-bottom:16px}
}
</style>
</head>
<body>
<div class="ec-shell">

  <div class="ec-form-panel">
    <div class="ec-stagger">
      <div class="ec-brand">
        <img src="{{ asset('img/enclaii-logo.svg') }}" alt="Enclaii" onerror="this.style.display='none'">
        <div class="ec-brand-name">ENCL<span>AI</span>I</div>
        <div class="ec-brand-tag">Plataforma médica inteligente</div>
      </div>
    </div>

    <div class="ec-stagger">
      <h1 class="ec-title">Recuperar contraseña</h1>
      <p class="ec-subtitle">Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.</p>
    </div>

    @if(session('status'))
      <div class="ec-alert success ec-stagger">
        {{ session('status') }}
      </div>
    @endif

    @if($errors->any())
      <div class="ec-alert error ec-stagger">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="forgotForm" class="ec-stagger">
      @csrf
      <div class="ec-field">
        <label class="ec-label" for="email">Correo electrónico</label>
        <div class="ec-input-wrap">
          <input
            class="ec-input @error('email') is-invalid @enderror"
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="tu@correo.com"
            autocomplete="email"
            autofocus
            required
          >
        </div>
        @error('email')
          <div class="ec-error">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $message }}
          </div>
        @enderror
      </div>

      <button class="ec-btn" type="submit" id="submitBtn">
        <span class="ec-btn-content">Enviar enlace de recuperación</span>
        <span class="ec-spinner">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
          </svg>
        </span>
      </button>
    </form>

    <div class="ec-stagger">
      <a href="{{ route('login') }}" class="ec-back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Volver al inicio de sesión
      </a>
    </div>
  </div>

  <div class="ec-visual" aria-hidden="true">
    <div class="ec-ring r1"></div>
    <div class="ec-ring r2"></div>
    <div class="ec-visual-label">
      <strong>Recupera tu acceso</strong>
      Te enviaremos un correo con instrucciones para restablecer tu contraseña de forma segura.
    </div>
  </div>

</div>

<script>
document.getElementById('forgotForm').addEventListener('submit', function(){
  const btn = document.getElementById('submitBtn');
  btn.setAttribute('data-loading', 'true');
});
</script>
</body>
</html>
