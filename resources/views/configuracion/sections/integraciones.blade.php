{{-- ============ PANEL: INTEGRACIONES ============ --}}
@include('configuracion.sections.integraciones._styles')

<div class="cfg-panel" data-panel="integraciones">
  <div class="int-head">
    <h2>Integraciones</h2>
    <p>Administra dispositivos, servicios y configuración del sistema.</p>
  </div>

  @include('configuracion.sections.integraciones._copias')
  @include('configuracion.sections.integraciones._informacion-sistema')
  @include('configuracion.sections.integraciones._servicios')
  @include('configuracion.sections.integraciones._modal-copia')
  @include('configuracion.sections.integraciones._modal-firma')
</div>

@include('configuracion.sections.integraciones._scripts')
