<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=20260627-2">
<title>Promocion de lanzamiento - ENCLAII</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --navy-900:#070F2E;
  --navy-800:#0B1A4A;
  --blue-600:#1E5AE8;
  --cyan-400:#38C7F4;
  --ink:#0E1530;
  --ink-soft:#4A5578;
  --field:#EEF3FC;
  --field-border:#D7E2F5;
  --success:#16803A;
  --warning:#A05A00;
  --error:#D93B4A;
}
*{box-sizing:border-box;margin:0;padding:0}
html,body{min-height:100%}
body{
  font-family:'Hanken Grotesk',sans-serif;
  background:
    radial-gradient(900px 620px at 78% 12%, rgba(56,199,244,.18), transparent 60%),
    radial-gradient(700px 700px at 15% 85%, rgba(30,90,232,.24), transparent 65%),
    linear-gradient(160deg,var(--navy-800),var(--navy-900) 72%);
  color:var(--ink);
  -webkit-font-smoothing:antialiased;
}
.shell{display:grid;grid-template-columns:minmax(390px,560px) 1fr;min-height:100svh}
.panel{
  background:#fff;
  border-radius:0 28px 28px 0;
  display:flex;
  align-items:center;
  padding:42px clamp(28px,6vw,82px);
}
.content{width:100%;max-width:450px}
.brand{display:flex;flex-direction:column;align-items:center;gap:8px;margin-bottom:22px}
.brand img{width:156px;height:auto;margin-bottom:-20px}
.brand-name{font-family:'Sora',sans-serif;font-size:23px;font-weight:800;letter-spacing:.42em;color:var(--ink)}
.brand-name span{color:#2E7BF6}
.brand-tag{font-size:10px;font-weight:700;letter-spacing:.24em;text-transform:uppercase;color:var(--ink-soft);text-align:center}
.eyebrow{
  display:inline-flex;
  align-items:center;
  gap:8px;
  min-height:32px;
  padding:7px 11px;
  border-radius:999px;
  background:rgba(46,123,246,.10);
  color:var(--blue-600);
  font-weight:800;
  font-size:12px;
  margin-bottom:14px;
}
.eyebrow i{width:8px;height:8px;border-radius:99px;background:var(--cyan-400)}
h1{font-family:'Sora',sans-serif;font-size:clamp(28px,4vw,38px);line-height:1.08;letter-spacing:0;color:var(--ink)}
.sub{margin-top:10px;margin-bottom:18px;color:var(--ink-soft);font-size:15px;line-height:1.55}
.notice{
  border-radius:14px;
  border:1px solid rgba(46,123,246,.16);
  background:#F7F9FE;
  padding:14px 15px;
  color:#213054;
  font-size:14px;
  line-height:1.5;
  margin:18px 0;
}
.notice strong{color:var(--ink)}
.alert{border-radius:12px;padding:12px 14px;margin:14px 0;font-size:14px;font-weight:700;line-height:1.45}
.alert.error{background:#FFF0F2;border:1px solid #FFD2D8;color:var(--error)}
.alert.info{background:#FFF8E8;border:1px solid #FFE1A8;color:var(--warning)}
.alert.success{background:#ECFFF2;border:1px solid #BDEFCB;color:var(--success)}
.field{margin-bottom:14px}
label{display:block;font-size:14px;font-weight:700;color:var(--ink);margin-bottom:6px}
.input-wrap{position:relative}
input{
  width:100%;
  height:52px;
  border:1.5px solid var(--field-border);
  border-radius:14px;
  background:var(--field);
  color:var(--ink);
  font:inherit;
  font-size:15px;
  padding:0 48px 0 16px;
  outline:none;
  transition:border-color .15s,box-shadow .15s,background .15s;
}
input:focus{background:#fff;border-color:#2E7BF6;box-shadow:0 0 0 4px rgba(46,123,246,.14)}
input.is-invalid{border-color:var(--error)}
.error-text{margin-top:7px;color:var(--error);font-size:13px;font-weight:700}
.eye{
  position:absolute;
  right:7px;
  top:50%;
  translate:0 -50%;
  width:38px;
  height:38px;
  border:0;
  border-radius:10px;
  background:transparent;
  color:#8794B7;
  display:grid;
  place-items:center;
  cursor:pointer;
}
.eye svg{width:20px;height:20px}
.btn,.link-btn{
  width:100%;
  height:54px;
  border:0;
  border-radius:14px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  font-family:'Sora',sans-serif;
  font-weight:800;
  font-size:14px;
  text-decoration:none;
  color:#fff;
  background:linear-gradient(135deg,#0B1A4A 0%,#12266B 55%,#1E5AE8 130%);
  box-shadow:0 12px 26px -13px rgba(11,26,74,.65);
  cursor:pointer;
}
.btn:active,.link-btn:active{transform:scale(.98)}
.footer{margin-top:18px;text-align:center;color:var(--ink-soft);font-size:14px}
.footer a{color:var(--blue-600);font-weight:800;text-decoration:none}
.visual{position:relative;display:grid;place-items:center;overflow:hidden;padding:42px}
.promo-card{
  width:min(520px,86%);
  border:1px solid rgba(255,255,255,.16);
  background:rgba(255,255,255,.08);
  color:#EAF1FF;
  border-radius:22px;
  padding:28px;
  box-shadow:0 34px 90px -28px rgba(0,0,0,.7);
  backdrop-filter:blur(14px);
}
.promo-card h2{font-family:'Sora',sans-serif;font-size:28px;letter-spacing:0;margin-bottom:14px}
.promo-card p{color:#AFC1EF;line-height:1.55;font-size:15px}
.steps{display:grid;gap:12px;margin-top:24px}
.step{display:flex;gap:12px;align-items:flex-start}
.step span{width:30px;height:30px;border-radius:10px;background:rgba(56,199,244,.16);color:#70D9FF;display:grid;place-items:center;font-weight:900;flex:none}
.step strong{display:block;color:#fff;margin-bottom:2px}
.code{margin-top:22px;padding:14px;border-radius:14px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);font-family:'Sora',sans-serif}
@media(max-width:960px){
  body{background:#fff}
  .shell{display:block}
  .panel{min-height:100svh;border-radius:0;padding:22px}
  .content{max-width:none}
  .visual{display:none}
  .brand img{width:110px;margin-bottom:-15px}
  .brand-name{font-size:17px}
  .brand-tag{font-size:7.5px;white-space:nowrap}
}
</style>
</head>
<body>
<div class="shell">
  <main class="panel">
    <div class="content">
      <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logotipo ENCLAII">
        <div class="brand-name">ENCLA<span>II</span></div>
        <div class="brand-tag">Endoscopia · Nube · Inteligencia Artificial</div>
      </div>

      <span class="eyebrow"><i></i> Promocion de lanzamiento</span>
      <h1>6 meses gratis</h1>
      <p class="sub">Crea tu cuenta, registra tu tarjeta en Stripe y comienza sin pagar hoy.</p>

      @if (session('error'))
        <div class="alert error">{{ session('error') }}</div>
      @endif

      @error('promo')
        <div class="alert error">{{ $message }}</div>
      @enderror

      @if ($state['message'])
        <div class="alert {{ auth()->check() && auth()->user()->subscribed() ? 'success' : ($state['can_resume'] ? 'info' : 'error') }}">{{ $state['message'] }}</div>
      @endif

      @if ($promoCode && ($state['can_register'] || $state['can_resume']))
        <div class="notice">
          <strong>No se cobrara hoy.</strong>
          Tu primer cobro sera al terminar la prueba,
          @if($trialEndsAt)
            aproximadamente el {{ $trialEndsAt->format('d/m/Y') }}.
          @else
            despues del periodo promocional.
          @endif
          Puedes cancelar antes desde tu plan.
        </div>
      @endif

      @if ($state['can_register'])
        <form method="POST" action="{{ route('promo.register.store', ['token' => $token]) }}" id="promoRegisterForm" novalidate>
          @csrf

          <div class="field">
            <label for="name">Nombre completo</label>
            <div class="input-wrap">
              <input id="name" name="name" type="text" autocomplete="name" required autofocus
                     value="{{ old('name') }}"
                     placeholder="Ej. Juan Perez"
                     class="@error('name') is-invalid @enderror">
            </div>
            @error('name')<p class="error-text">{{ $message }}</p>@enderror
          </div>

          <div class="field">
            <label for="email">Correo electronico</label>
            <div class="input-wrap">
              <input id="email" name="email" type="email" autocomplete="email" required
                     value="{{ old('email') }}"
                     placeholder="tucorreo@ejemplo.com"
                     class="@error('email') is-invalid @enderror">
            </div>
            @error('email')<p class="error-text">{{ $message }}</p>@enderror
          </div>

          <div class="field">
            <label for="password">Contrasena</label>
            <div class="input-wrap">
              <input id="password" name="password" type="password" autocomplete="new-password" required
                     placeholder="Minimo 8 caracteres"
                     class="@error('password') is-invalid @enderror">
              <button type="button" class="eye" data-eye-for="password" aria-label="Mostrar contrasena" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            @error('password')<p class="error-text">{{ $message }}</p>@enderror
          </div>

          <div class="field">
            <label for="password_confirmation">Confirmar contrasena</label>
            <div class="input-wrap">
              <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                     placeholder="Repite tu contrasena">
              <button type="button" class="eye" data-eye-for="password_confirmation" aria-label="Mostrar contrasena" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          <button type="submit" class="btn">
            Crear cuenta y validar tarjeta
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </button>
        </form>
      @elseif ($state['can_resume'])
        <form method="POST" action="{{ route('promo.register.checkout', ['token' => $token]) }}">
          @csrf
          <button type="submit" class="btn">Continuar a Stripe</button>
        </form>
      @elseif (auth()->check() && auth()->user()->subscribed())
        <a class="link-btn" href="{{ route('dashboard') }}">Ir al sistema</a>
      @endif

      <p class="footer">
        @auth
          <a href="{{ route('plan.only') }}">Ver planes</a>
        @else
          Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesion</a>
        @endauth
      </p>
    </div>
  </main>

  <aside class="visual" aria-hidden="true">
    <div class="promo-card">
      <h2>Acceso completo para arrancar</h2>
      <p>El QR queda unido a una sola cuenta. Stripe guarda la tarjeta y activa la prueba de seis meses sin cargo inicial.</p>
      <div class="steps">
        <div class="step"><span>1</span><div><strong>Cuenta</strong><p>Registro rapido con correo y contrasena.</p></div></div>
        <div class="step"><span>2</span><div><strong>Tarjeta</strong><p>Validacion segura dentro de Stripe Checkout.</p></div></div>
        <div class="step"><span>3</span><div><strong>Trial</strong><p>Seis meses gratis antes del primer cobro.</p></div></div>
      </div>
      @if($promoCode)
        <div class="code">{{ $promoCode->code }}</div>
      @endif
    </div>
  </aside>
</div>

<script>
(function(){
  document.querySelectorAll('.eye').forEach(function(btn){
    btn.addEventListener('click', function(){
      var input = document.getElementById(btn.dataset.eyeFor);
      if (!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-pressed', show ? 'true' : 'false');
      btn.setAttribute('aria-label', show ? 'Ocultar contrasena' : 'Mostrar contrasena');
    });
  });
})();
</script>
</body>
</html>
