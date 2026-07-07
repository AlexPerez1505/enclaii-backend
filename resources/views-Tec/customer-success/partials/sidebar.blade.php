@php
  $active = trim($__env->yieldContent('active'));
@endphp
<aside class="side">
  <div class="side-top">
    <div class="side-brand-row">
      <div class="side-brand">
        <img class="logo-dark" src="{{ asset('images/logo-dark.png') }}" alt="Logotipo ENCLAII">
        <img class="logo-light" src="{{ asset('images/logo.png') }}" alt="Logotipo ENCLAII">
        <div class="side-brand-copy">
          <div class="side-brand-name">ENCLA<span>II</span></div>
          <div class="side-brand-tag">Customer Success</div>
        </div>
      </div>
    </div>
  </div>

  <a class="nav-item {{ $active === 'customer-success-dashboard' ? 'active' : '' }}" href="{{ route('customer-success.dashboard') }}" title="Inicio">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
    <span class="nav-label">Inicio</span>
  </a>

  <a class="nav-item {{ $active === 'customer-success' ? 'active' : '' }}" href="{{ route('customer-success.anuncios') }}" title="Anuncios">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 6"/></svg>
    <span class="nav-label">Anuncios</span>
  </a>

  <a class="nav-item {{ $active === 'customer-success-usuarios' ? 'active' : '' }}" href="{{ route('customer-success.gestion-usuarios') }}" title="Gestión de usuarios">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    <span class="nav-label">Gestión de usuarios</span>
  </a>

  <a class="nav-item {{ $active === 'customer-success-comunicaciones' ? 'active' : '' }}" href="{{ route('customer-success.anuncios') }}" title="Comunicaciones">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    <span class="nav-label">Comunicaciones</span>
  </a>

  <a class="nav-item {{ $active === 'customer-success-auditoria' ? 'active' : '' }}" href="{{ route('customer-success.dashboard') }}#auditoria" title="Soporte / Auditoría">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15l3-3 3 3"/></svg>
    <span class="nav-label">Auditoría</span>
  </a>

  <div class="side-help">
    <div class="orb">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
    </div>
    <strong>Panel Customer Success</strong>
    <span>Gestión de comunicaciones y roles</span>
  </div>
</aside>
