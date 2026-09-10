<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=20260627-2">
<script>
  document.documentElement.dataset.theme = localStorage.getItem('enclaii-theme') || 'dark';
  document.documentElement.lang = localStorage.getItem('enclaii-lang') || 'es';
</script>
<title>@yield('title', 'Selecciona tu plan') — ENCLAII</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://js.stripe.com/v3/"></script>
<style>
:root{
  --bg:#06081C; --panel:#0A0F2E; --panel-2:#0D1438; --card:#0E1740;
  --stroke:rgba(110,160,255,.12); --stroke-strong:rgba(110,160,255,.25);
  --txt:#EAF1FF; --txt-soft:#8FA3CF; --blue:#3B82F6; --cyan:#0EA5E9;
  --orange:#F59E2D; --green:#10B981; --red:#EF4444;
  --r-lg:18px; --r-md:12px;
}
html[data-theme="light"]{
  --bg:#F8FAFC; --panel:#FFFFFF; --panel-2:#FFFFFF; --card:#FFFFFF;
  --stroke:rgba(0,0,0,.06); --stroke-strong:rgba(0,0,0,.12);
  --txt:#0F172A; --txt-soft:#64748B;
}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Hanken Grotesk',system-ui,sans-serif;background:var(--bg);color:var(--txt);min-height:100vh}

.po-wrap{min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:40px 20px}
.po-header{text-align:center;margin-bottom:40px;max-width:600px}
.po-logo{display:flex;align-items:center;justify-content:center;gap:12px;margin-bottom:24px}
.po-logo img{width:52px;height:52px}
.po-logo .po-brand{font-family:'Sora',sans-serif;font-size:22px;font-weight:800;color:var(--txt)}
.po-logo .po-brand span{color:var(--cyan)}
.po-title{font-family:'Sora',sans-serif;font-size:28px;font-weight:800;margin-bottom:8px}
.po-sub{font-size:15px;color:var(--txt-soft);line-height:1.5}
.po-alert{margin:20px auto 0;max-width:500px;padding:14px 20px;border-radius:var(--r-md);background:rgba(245,158,45,.1);border:1px solid rgba(245,158,45,.3);color:var(--orange);font-size:13px;font-weight:600;text-align:center}
.po-alert.info{background:rgba(59,130,246,.1);border-color:rgba(59,130,246,.28);color:var(--blue)}
.po-alert.success{background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.28);color:var(--green)}
.po-alert.error{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.28);color:var(--red)}
.po-content{width:100%;max-width:1100px}
.po-logout{margin-top:30px}
.po-logout button{background:none;border:1px solid var(--stroke-strong);color:var(--txt-soft);padding:10px 24px;border-radius:var(--r-md);font-size:13px;font-weight:600;cursor:pointer;transition:all .15s}
.po-logout button:hover{background:rgba(239,68,68,.1);color:var(--red);border-color:var(--red)}
</style>
@stack('styles')
</head>
<body>
<div class="po-wrap">
  <div class="po-header">
    <div class="po-logo">
      <img src="{{ asset('images/logo-dark.png') }}" alt="ENCLAII">
      <div class="po-brand">ENCLA<span>II</span></div>
    </div>
    <h1 class="po-title">@yield('po-title', 'Selecciona tu plan')</h1>
    <p class="po-sub">@yield('po-sub', 'Elige el plan que mejor se adapte a tus necesidades para comenzar a usar EndoCare.')</p>
    @if(session('warning'))
      <div class="po-alert">{{ session('warning') }}</div>
    @endif
    @if(session('info'))
      <div class="po-alert info">{{ session('info') }}</div>
    @endif
    @if(session('success'))
      <div class="po-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="po-alert error">{{ session('error') }}</div>
    @endif
  </div>

  <div class="po-content">
    @yield('content')
  </div>

  <div class="po-logout">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">Cerrar sesión</button>
    </form>
  </div>
</div>
@stack('scripts')
</body>
</html>
