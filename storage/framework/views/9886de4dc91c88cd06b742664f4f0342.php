<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>?v=20260627-2">
<title>Crea tu cuenta — ENCLAII</title>
<script>document.documentElement.lang = localStorage.getItem('enclaii-lang') || 'es';</script>
<script defer src="<?php echo e(asset('js/i18n.js')); ?>"></script>
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
  --r-lg:22px;
  --r-md:14px;
  /* Easings fuertes (no usar las nativas débiles) */
  --ease-out:cubic-bezier(0.23, 1, 0.32, 1);
  --ease-in-out:cubic-bezier(0.77, 0, 0.175, 1);
}
*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%}
body{
  font-family:'Hanken Grotesk',sans-serif;
  background:var(--navy-900);
  color:var(--ink);
  -webkit-font-smoothing:antialiased;
}

/* ============ LAYOUT ============ */
.ec-shell{
  display:grid;
  grid-template-columns:minmax(420px,560px) 1fr;
  min-height:100vh;
}

/* ============ PANEL IZQUIERDO (FORM) ============ */
.ec-form-panel{
  background:#fff;
  border-radius:0 28px 28px 0;
  display:flex;
  flex-direction:column;
  justify-content:center;
  padding:44px clamp(32px,6vw,88px);
  position:relative;
  z-index:2;
}

.ec-brand{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:10px;
  margin-bottom:24px;
}
.ec-brand img{width:170px;height:auto; margin-bottom:-22px; }
.ec-brand-name{
  font-family:'Sora',sans-serif;
  font-weight:800;
  font-size:24px;
  letter-spacing:.42em;
  color:var(--ink);
}
.ec-brand-name span{color:var(--blue-500)}
.ec-brand-tag{
  font-size:10px;
  font-weight:600;
  letter-spacing:.28em;
  text-transform:uppercase;
  color:var(--ink-soft);
}

.ec-title{
  font-family:'Sora',sans-serif;
  font-weight:700;
  font-size:clamp(26px,3vw,34px);
  letter-spacing:-0.02em;
  color:var(--ink);
}
.ec-subtitle{
  margin-top:8px;
  font-size:15px;
  color:var(--ink-soft);
  margin-bottom:26px;
}

.ec-field{margin-bottom:16px}
.ec-label{
  display:block;
  font-weight:600;
  font-size:14px;
  margin-bottom:6px;
  color:var(--ink);
}
.ec-input-wrap{position:relative}
.ec-input{
  width:100%;
  height:52px;
  padding:0 48px 0 16px;
  font:inherit;
  font-size:15px;
  color:var(--ink);
  background:var(--field);
  border:1.5px solid var(--field-border);
  border-radius:var(--r-md);
  outline:none;
  transition:border-color 150ms var(--ease-out), box-shadow 150ms var(--ease-out), background-color 150ms var(--ease-out);
}
.ec-input::placeholder{color:#9AA6C4}
.ec-input:focus{
  background:#fff;
  border-color:var(--blue-500);
  box-shadow:0 0 0 4px rgba(46,123,246,.14);
}
.ec-input.is-invalid{border-color:var(--error)}
.ec-input.is-invalid:focus{box-shadow:0 0 0 4px rgba(217,59,74,.12)}

.ec-eye{
  position:absolute;
  right:6px;
  top:50%;
  translate:0 -50%;
  width:40px;height:40px;
  display:grid;place-items:center;
  background:none;border:0;cursor:pointer;
  color:#8C99BC;
  border-radius:10px;
  transition:color 150ms ease, transform 160ms var(--ease-out);
}
.ec-eye:active{transform:scale(.92)}
@media (hover:hover) and (pointer:fine){
  .ec-eye:hover{color:var(--blue-500)}
}
.ec-eye .eye-closed{display:none}
.ec-eye[aria-pressed="true"] .eye-open{display:none}
.ec-eye[aria-pressed="true"] .eye-closed{display:block}

.ec-error{
  display:flex;
  align-items:center;
  gap:6px;
  margin-top:8px;
  font-size:13px;
  font-weight:500;
  color:var(--error);
}

.ec-link{
  color:var(--blue-600);
  font-weight:600;
  text-decoration:none;
}
@media (hover:hover) and (pointer:fine){
  .ec-link:hover{text-decoration:underline}
}

/* Botón */
.ec-btn{
  position:relative;
  width:100%;
  height:56px;
  margin-top:8px;
  border:0;
  border-radius:var(--r-md);
  font-family:'Sora',sans-serif;
  font-weight:700;
  font-size:15px;
  letter-spacing:.04em;
  color:#fff;
  cursor:pointer;
  background:linear-gradient(135deg,var(--navy-800) 0%,var(--navy-700) 55%,var(--blue-600) 130%);
  box-shadow:0 10px 24px -10px rgba(11,26,74,.55);
  transition:transform 160ms var(--ease-out);
}
.ec-btn:active{transform:scale(.97)}
/* Sombra de hover pre-renderizada: solo se anima opacity (GPU) */
.ec-btn::after{
  content:'';
  position:absolute;inset:0;
  border-radius:inherit;
  box-shadow:0 14px 30px -10px rgba(30,90,232,.5);
  opacity:0;
  transition:opacity 200ms var(--ease-out);
  pointer-events:none;
}
@media (hover:hover) and (pointer:fine){
  .ec-btn:hover::after{opacity:1}
}
.ec-btn[data-loading="true"]{pointer-events:none}
.ec-btn-content{
  display:inline-flex;align-items:center;gap:10px;
  transition:opacity 200ms ease, filter 200ms ease;
}
.ec-btn[data-loading="true"] .ec-btn-content{opacity:.55;filter:blur(2px)}
.ec-spinner{
  position:absolute;inset:0;
  display:grid;place-items:center;
  opacity:0;
  transition:opacity 200ms ease;
}
.ec-btn[data-loading="true"] .ec-spinner{opacity:1}
.ec-spinner svg{
  width:22px;height:22px;
  animation:ec-spin .6s linear infinite; /* rápido = sensación de velocidad */
}
@keyframes ec-spin{to{transform:rotate(360deg)}}

.ec-footer{
  margin-top:22px;
  text-align:center;
  font-size:14.5px;
  color:var(--ink-soft);
}

/* Entrada escalonada del formulario.
   OJO: <?php echo csrf_field(); ?> inserta un input oculto como PRIMER hijo del form,
   por eso los delays empiezan en nth-child(2). */
.ec-stagger{
  opacity:0;
  transform:translateY(12px);
  animation:ec-rise 450ms var(--ease-out) forwards;
}
.ec-stagger:nth-child(2){animation-delay:0ms}
.ec-stagger:nth-child(3){animation-delay:50ms}
.ec-stagger:nth-child(4){animation-delay:100ms}
.ec-stagger:nth-child(5){animation-delay:150ms}
.ec-stagger:nth-child(6){animation-delay:200ms}
.ec-stagger:nth-child(7){animation-delay:250ms}
.ec-stagger:nth-child(8){animation-delay:300ms}
.ec-stagger:nth-child(9){animation-delay:350ms}
@keyframes ec-rise{to{opacity:1;transform:translateY(0)}}

/* Shake en errores de validación */
@keyframes ec-shake{
  10%,90%{transform:translateX(-1px)}
  20%,80%{transform:translateX(2px)}
  30%,50%,70%{transform:translateX(-4px)}
  40%,60%{transform:translateX(4px)}
}
.ec-shake{animation:ec-shake 400ms var(--ease-in-out)}

/* ============ PANEL DERECHO (VISUAL ANIMADO) ============ */
.ec-visual{
  position:relative;
  overflow:hidden;
  background:
    radial-gradient(900px 600px at 80% 15%, rgba(56,199,244,.18), transparent 60%),
    radial-gradient(700px 700px at 15% 85%, rgba(30,90,232,.25), transparent 65%),
    linear-gradient(160deg,var(--navy-800),var(--navy-900) 70%);
  display:grid;
  place-items:center;
}
/* grano sutil para evitar banding */
.ec-visual::after{
  content:'';
  position:absolute;inset:0;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
  pointer-events:none;
  mix-blend-mode:overlay;
}
/* anillos orbitales: se encienden alternados (cuando uno brilla, el otro descansa) */
.ec-ring{
  position:absolute;
  border:1.5px solid rgba(120,170,255,.55);
  border-radius:50%;
  box-shadow:
    0 0 24px rgba(56,199,244,.25),
    inset 0 0 24px rgba(56,199,244,.15);
  opacity:.12;
  animation:ec-ring-pulse 5s var(--ease-in-out) infinite;
}
.ec-ring.r1{width:540px;height:540px}
.ec-ring.r2{
  width:760px;height:760px;
  border-style:dashed;
  animation-delay:2.5s; /* medio ciclo: alternan perfecto */
}
@keyframes ec-ring-pulse{
  0%,100%{opacity:.12}
  50%{opacity:.85}
}

.ec-scene{
  position:relative;
  width:min(560px,80%);
  aspect-ratio:1/1;
  will-change:transform;
}

/* Lottie central */
.ec-lottie{
  position:absolute;
  inset:17%;
  width:66%;
  height:66%;
  pointer-events:none;
  filter:drop-shadow(0 30px 60px rgba(0,0,0,.4));
}

/* Tarjetas de vidrio flotantes */
.ec-card{
  position:absolute;
  background:rgba(255,255,255,.07);
  border:1px solid rgba(255,255,255,.14);
  backdrop-filter:blur(14px);
  -webkit-backdrop-filter:blur(14px);
  border-radius:18px;
  padding:16px 18px;
  color:#EAF1FF;
  box-shadow:0 24px 60px -20px rgba(0,0,0,.5);
  will-change:transform;
}
.ec-card h4{
  font-family:'Sora',sans-serif;
  font-size:12px;
  font-weight:600;
  letter-spacing:.12em;
  text-transform:uppercase;
  color:#9FC4FF;
  margin-bottom:10px;
}
.ec-card .big{
  font-family:'Sora',sans-serif;
  font-size:28px;
  font-weight:700;
}
.ec-card .sub{font-size:12px;color:#8FA8D8;margin-top:2px}

/* Composición en diamante: una tarjeta por esquina, Lottie al centro sin que lo tapen */
.card-live{top:0;left:-3%;width:200px}
.card-cloud{top:2%;right:-3%;width:180px}
.card-ai{bottom:0;right:-4%;width:215px}
.card-reports{bottom:-2%;left:-3%;width:195px}

/* Pantallas medianas: tarjetas más compactas para que sigan sin tapar el centro */
@media (max-width:1366px){
  .ec-card{padding:12px 14px;border-radius:14px}
  .ec-card h4{font-size:10.5px;margin-bottom:8px}
  .ec-card .big{font-size:22px}
  .ec-card .sub{font-size:11px}
  .card-live{width:170px}
  .card-cloud{width:155px}
  .card-ai{width:180px}
  .card-reports{width:165px}
}

/* Pantallas angostas (laptop chica / ventana partida): solo 2 tarjetas en diagonal */
@media (max-width:1120px){
  .card-cloud,.card-reports{display:none}
  .card-live{top:2%;left:0}
  .card-ai{bottom:2%;right:0}
}

/* Señal del procedimiento en vivo (línea animada) */
.ecg-line{
  stroke:var(--cyan-400);
  stroke-width:2.5;
  fill:none;
  stroke-linecap:round;
  stroke-dasharray:240 600;
  animation:ec-dash 2.6s linear infinite;
  filter:drop-shadow(0 0 6px rgba(56,199,244,.7));
}
@keyframes ec-dash{to{stroke-dashoffset:-840}}

/* Punto "REC" parpadeante */
.ec-rec{
  display:inline-block;
  width:8px;height:8px;
  border-radius:50%;
  background:#FF5A6E;
  box-shadow:0 0 8px rgba(255,90,110,.8);
  animation:ec-glow 1.4s ease-in-out infinite;
  vertical-align:middle;
}

/* Nodos de la red AI */
.ai-node{fill:#7FB4FF;opacity:.9}
.ai-node.glow{animation:ec-glow 2s ease-in-out infinite}
.ai-node.glow:nth-of-type(2){animation-delay:.4s}
.ai-node.glow:nth-of-type(4){animation-delay:.9s}
@keyframes ec-glow{
  0%,100%{opacity:.4}
  50%{opacity:1}
}
.ai-edge{stroke:rgba(127,180,255,.35);stroke-width:1.2}

/* Barra de progreso "subiendo a la nube" */
.cloud-bar{
  height:6px;border-radius:99px;
  background:rgba(255,255,255,.12);
  overflow:hidden;margin-top:10px;
}
.cloud-bar i{
  display:block;height:100%;width:40%;
  border-radius:99px;
  background:linear-gradient(90deg,var(--blue-500),var(--cyan-400));
  animation:ec-upload 2.2s var(--ease-in-out) infinite;
}
@keyframes ec-upload{
  0%{transform:translateX(-110%)}
  60%,100%{transform:translateX(280%)}
}

/* ====== MÓVIL / TABLET: solo el registro, todo en una pantalla ====== */
@media (max-width:1024px){
  html,body{background:#fff}
  .ec-shell{display:block;min-height:100svh}
  .ec-visual{display:none !important}
  .ec-form-panel{
    border-radius:0;
    min-height:100svh;
    padding:18px 24px;
    padding-bottom:max(18px, env(safe-area-inset-bottom)); /* respeta la barra inferior del iPhone */
    justify-content:center;
  }
  /* Marca igual que en desktop (vertical), solo en versión reducida */
  .ec-brand{margin-bottom:12px;gap:6px}
  .ec-brand img{width:96px;margin-bottom:-13px}
  .ec-brand-name{font-size:16px;letter-spacing:.34em}
  .ec-brand-tag{
    font-size:7.5px;
    letter-spacing:.24em;
    white-space:nowrap;
  }
  .ec-title{font-size:24px}
  .ec-subtitle{font-size:13px;margin-top:4px;margin-bottom:14px}
  .ec-field{margin-bottom:11px}
  .ec-label{font-size:13px;margin-bottom:5px}
  .ec-input{height:46px;font-size:16px} /* 16px evita el zoom automático de iOS */
  .ec-btn{height:50px;margin-top:6px}
  .ec-footer{margin-top:14px;font-size:13.5px}
}

/* Pantallas muy bajitas (teléfonos chicos en horizontal o con barra grande) */
@media (max-width:1024px) and (max-height:700px){
  .ec-brand{margin-bottom:8px}
  .ec-subtitle{display:none}
  .ec-brand-tag{display:none}
}

/* Reduced motion: quitar movimiento, conservar fades */
@media (prefers-reduced-motion: reduce){
  .ec-stagger{animation:ec-fade 250ms ease forwards;transform:none}
  @keyframes ec-fade{to{opacity:1}}
  .ec-rec,.cloud-bar i,.ecg-line{animation:none}
  .ec-ring{animation:none;opacity:.25}
  .ec-card,.ec-scene{transform:none !important}
}
</style>
</head>
<body>

<div class="ec-shell">

  
  <main class="ec-form-panel">
    <form method="POST" action="<?php echo e(route('register.post')); ?>" id="registerForm" novalidate
          class="<?php if($errors->any()): ?> ec-shake <?php endif; ?>">
      <?php echo csrf_field(); ?>

      <div class="ec-brand ec-stagger">
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logotipo ENCLAII">
        <div class="ec-brand-name">ENCLA<span>II</span></div>
        <div class="ec-brand-tag">Endoscopia · Nube · Inteligencia Artificial</div>
      </div>

      <div class="ec-stagger">
        <h1 class="ec-title">Crea tu cuenta</h1>
        <p class="ec-subtitle">Únete a la plataforma de endoscopia inteligente.</p>
      </div>

      <div class="ec-field ec-stagger">
        <label class="ec-label" for="name">Nombre completo</label>
        <div class="ec-input-wrap">
          <input id="name" name="name" type="text" autocomplete="name" required autofocus
                 value="<?php echo e(old('name')); ?>"
                 placeholder="Ej. Juan Pérez"
                 class="ec-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        </div>
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <p class="ec-error">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?php echo e($message); ?>

          </p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="ec-field ec-stagger">
        <label class="ec-label" for="email">Correo electrónico</label>
        <div class="ec-input-wrap">
          <input id="email" name="email" type="email" autocomplete="email" required
                 value="<?php echo e(old('email')); ?>"
                 placeholder="tucorreo@ejemplo.com"
                 class="ec-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <p class="ec-error">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?php echo e($message); ?>

          </p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="ec-field ec-stagger">
        <label class="ec-label" for="password">Contraseña</label>
        <div class="ec-input-wrap">
          <input id="password" name="password" type="password" autocomplete="new-password" required
                 placeholder="••••••••"
                 class="ec-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <button type="button" class="ec-eye" data-eye-for="password" aria-label="Mostrar contraseña" aria-pressed="false">
            <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </button>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <p class="ec-error">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?php echo e($message); ?>

          </p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="ec-field ec-stagger">
        <label class="ec-label" for="password_confirmation">Confirmar contraseña</label>
        <div class="ec-input-wrap">
          <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                 placeholder="••••••••"
                 class="ec-input">
          <button type="button" class="ec-eye" data-eye-for="password_confirmation" aria-label="Mostrar contraseña" aria-pressed="false">
            <svg class="eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </button>
        </div>
      </div>

      <div class="ec-stagger">
        <button type="submit" class="ec-btn" id="btnRegister" data-loading="false">
          <span class="ec-btn-content">
            Crear cuenta
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </span>
          <span class="ec-spinner" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"><path d="M21 12a9 9 0 1 1-6.2-8.56"/></svg>
          </span>
        </button>
      </div>

      <p class="ec-footer ec-stagger">
        ¿Ya tienes cuenta? <a class="ec-link" href="<?php echo e(route('login')); ?>">Inicia sesión</a>
      </p>
    </form>
  </main>

  
  <aside class="ec-visual" aria-hidden="true">
    <div class="ec-ring r1"></div>
    <div class="ec-ring r2"></div>

    <div class="ec-scene" id="scene">

      
      <lottie-player
        class="ec-lottie"
        src="<?php echo e(asset('animations/medico2.json')); ?>"
        background="transparent"
        speed="1"
        loop
        autoplay>
      </lottie-player>

      
      <div class="ec-card card-live" data-depth="28">
        <h4><span class="ec-rec"></span>&nbsp; Endoscopia en vivo</h4>
        <svg width="100%" height="42" viewBox="0 0 220 46" preserveAspectRatio="none">
          <path class="ecg-line" d="M0 26 H38 L48 26 L56 8 L64 42 L72 18 L80 26 H120 L130 26 L138 10 L146 40 L154 20 L162 26 H220"/>
        </svg>
        <div class="sub">Video Full HD transmitido a la nube</div>
      </div>

      
      <div class="ec-card card-cloud" data-depth="18">
        <h4>Imágenes seguras</h4>
        <div class="big"><span class="ec-count" data-target="48920">0</span></div>
        <div class="sub">Capturas endoscópicas en la nube</div>
        <div class="cloud-bar"><i></i></div>
      </div>

      
      <div class="ec-card card-ai" data-depth="40">
        <h4>Detección con IA</h4>
        <svg width="100%" height="64" viewBox="0 0 190 64">
          <line class="ai-edge" x1="20" y1="14" x2="80" y2="32"/><line class="ai-edge" x1="20" y1="50" x2="80" y2="32"/>
          <line class="ai-edge" x1="80" y1="32" x2="140" y2="14"/><line class="ai-edge" x1="80" y1="32" x2="140" y2="50"/>
          <line class="ai-edge" x1="140" y1="14" x2="172" y2="32"/><line class="ai-edge" x1="140" y1="50" x2="172" y2="32"/>
          <circle class="ai-node glow" cx="20" cy="14" r="5"/>
          <circle class="ai-node glow" cx="20" cy="50" r="5"/>
          <circle class="ai-node" cx="80" cy="32" r="6"/>
          <circle class="ai-node glow" cx="140" cy="14" r="5"/>
          <circle class="ai-node glow" cx="140" cy="50" r="5"/>
          <circle class="ai-node" cx="172" cy="32" r="6"/>
        </svg>
        <div class="sub">Hallazgos identificados en la imagen</div>
      </div>

      
      <div class="ec-card card-reports" data-depth="34">
        <h4>Reportes generados</h4>
        <div class="big"><span class="ec-count" data-target="12480">0</span></div>
        <div class="sub">+320 esta semana</div>
      </div>

    </div>
  </aside>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script>
(function(){
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  /* ---- Pausar Lottie si el usuario pide menos movimiento ---- */
  if (reduced) {
    customElements.whenDefined('lottie-player').then(() => {
      document.querySelector('.ec-lottie')?.pause();
    });
  }

  /* ---- Mostrar / ocultar contraseña (genérico para ambos campos) ---- */
  document.querySelectorAll('.ec-eye').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.eyeFor);
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-pressed', show);
      btn.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
    });
  });

  /* ---- Estado de carga del botón ---- */
  const form = document.getElementById('registerForm');
  const btn  = document.getElementById('btnRegister');
  form.addEventListener('submit', () => { btn.dataset.loading = 'true'; });

  /* ---- Contadores: si hay reduced motion, mostrar el valor final directo ---- */
  const counters = document.querySelectorAll('.ec-count');
  if (reduced || typeof gsap === 'undefined') {
    counters.forEach(c => {
      c.textContent = parseInt(c.dataset.target, 10).toLocaleString('es-MX');
    });
    return;
  }

  /* ---- Flotación suave de las tarjetas (decorativo, GPU-friendly) ---- */
  document.querySelectorAll('.ec-card').forEach((card, i) => {
    gsap.to(card, {
      y: i % 2 === 0 ? -12 : 12,
      duration: 3 + i * 0.4,
      ease: 'sine.inOut',
      repeat: -1,
      yoyo: true,
      delay: i * 0.3
    });
  });

  /* ---- Entrada del panel visual ---- */
  gsap.from('.ec-card', {
    opacity: 0,
    scale: 0.95,            // nunca desde scale(0)
    y: 24,
    duration: 0.6,
    ease: 'expo.out',
    stagger: 0.07,
    delay: 0.25,
    clearProps: 'opacity'
  });
  gsap.from('.ec-lottie', { opacity: 0, scale: 0.96, duration: 0.9, ease: 'expo.out', delay: 0.15 });

  /* ---- Parallax con el mouse (solo pointer fino, con spring vía quickTo) ---- */
  if (finePointer) {
    const scene = document.getElementById('scene');
    const cards = [...document.querySelectorAll('.ec-card')];
    const movers = cards.map(c => ({
      x: gsap.quickTo(c, 'x', { duration: 0.8, ease: 'power3.out' }),
      depth: parseFloat(c.dataset.depth || 20)
    }));
    const sceneX = gsap.quickTo(scene, 'rotationY', { duration: 1, ease: 'power3.out' });
    const sceneY = gsap.quickTo(scene, 'rotationX', { duration: 1, ease: 'power3.out' });

    document.querySelector('.ec-visual').addEventListener('mousemove', (e) => {
      const r = e.currentTarget.getBoundingClientRect();
      const nx = (e.clientX - r.left) / r.width  - 0.5;  // -0.5 … 0.5
      const ny = (e.clientY - r.top)  / r.height - 0.5;
      movers.forEach(m => m.x(nx * m.depth));
      sceneX(nx * 5);
      sceneY(-ny * 4);
    });
  }

  /* ---- Contadores animados (imágenes y reportes) ---- */
  counters.forEach((counter, i) => {
    const target = parseInt(counter.dataset.target, 10);
    const obj = { v: 0 };
    gsap.to(obj, {
      v: target,
      duration: 1.6,
      ease: 'expo.out',
      delay: 0.5 + i * 0.15,
      onUpdate: () => { counter.textContent = Math.round(obj.v).toLocaleString('es-MX'); }
    });
  });
})();
</script>
</body>
</html>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\auth\endocare-register.blade.php ENDPATH**/ ?>