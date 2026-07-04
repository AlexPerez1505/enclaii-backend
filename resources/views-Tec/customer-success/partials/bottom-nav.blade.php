@php
  $active = trim($__env->yieldContent('active'));
@endphp
<nav class="mobile-nav">
  <a class="mobile-nav-item {{ $active === 'customer-success-dashboard' ? 'active' : '' }}" href="{{ route('customer-success.dashboard') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
    Inicio
  </a>
  <a class="mobile-nav-item {{ $active === 'customer-success' ? 'active' : '' }}" href="{{ route('customer-success.anuncios') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 6"/></svg>
    Anuncios
  </a>
  <a class="mobile-nav-item {{ $active === 'customer-success-usuarios' ? 'active' : '' }}" href="{{ route('customer-success.anuncios') }}#usuarios">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    Usuarios
  </a>
</nav>
