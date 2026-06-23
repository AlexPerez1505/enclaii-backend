{{-- ============ PANEL: GENERAL ============ --}}
<div class="cfg-panel active" data-panel="general">
  <div class="cfg-col">

    {{-- Tarjeta combinada: ajustes --}}
    <article class="card rise d2">
      <div class="cfg-card-head">
        <h2>Preferencias generales</h2>
        <p>Configura los ajustes generales de la aplicación</p>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
        <div class="cfg-info"><div class="t">Modo lectura</div><div class="d">Aplica un filtro amarillo en toda la pantalla para reducir el esfuerzo visual</div></div>
        <label class="sw"><input type="checkbox" data-setting="reading_mode" data-effect="reading"><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20a15 15 0 0 1 0-20z"/></svg></span>
        <div class="cfg-info"><div class="t">Idioma</div><div class="d">Selecciona el idioma de la aplicación</div></div>
        <div class="cfg-select">
          <select id="cfgLang" data-no-i18n>
            <option value="es">Español</option>
            <option value="en">English</option>
          </select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
        <div class="cfg-info"><div class="t">Zona horaria</div><div class="d">Configura tu zona horaria local</div></div>
        <div class="cfg-select">
          <select data-setting="timezone"><option>(GMT-06:00) Ciudad de México</option><option>(GMT-05:00) Bogotá</option><option>(GMT-03:00) Buenos Aires</option></select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
        <div class="cfg-info"><div class="t">Formato de fecha</div><div class="d">Selecciona el formato de fecha</div></div>
        <div class="cfg-select">
          <select data-setting="date_format"><option>DD/MM/YYYY</option><option>MM/DD/YYYY</option><option>YYYY-MM-DD</option></select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
        <div class="cfg-info"><div class="t">Formato de hora</div><div class="d">Selecciona el formato de hora</div></div>
        <div class="cfg-select">
          <select data-setting="time_format"><option>12 horas (AM/PM)</option><option>24 horas</option></select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg></span>
        <div class="cfg-info"><div class="t">Guardar cambios automáticamente</div><div class="d">Guarda los cambios en formularios automáticamente</div></div>
        <label class="sw"><input type="checkbox" data-setting="autosave" checked><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.6 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.6a2 2 0 0 0-3.4 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
        <div class="cfg-info"><div class="t">Confirmar antes de eliminar</div><div class="d">Mostrar confirmación antes de eliminar elementos</div></div>
        <label class="sw"><input type="checkbox" data-setting="confirm_delete" checked><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-card-head cfg-sec">
        <h2>Apariencia y comportamiento</h2>
        <p>Personaliza cómo funciona la aplicación</p>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></span>
        <div class="cfg-info"><div class="t">Vista predeterminada al iniciar</div><div class="d">Selecciona la vista que se muestra al iniciar la aplicación</div></div>
        <div class="cfg-select">
          <select data-setting="default_view"><option>Dashboard</option><option>IA Reportes</option><option>Agenda</option><option>Mensajes</option><option>Nuevo estudio</option><option>Galería</option></select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></span>
        <div class="cfg-info"><div class="t">Elementos por página</div><div class="d">Número de elementos a mostrar en las tablas</div></div>
        <div class="cfg-select">
          <select data-setting="items_per_page"><option>25</option><option>50</option><option>100</option></select>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/></svg></span>
        <div class="cfg-info"><div class="t">Animaciones y transiciones</div><div class="d">Habilitar animaciones en la interfaz</div></div>
        <label class="sw"><input type="checkbox" data-setting="animations" data-effect="animations" checked><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="3" y2="18"/></svg></span>
        <div class="cfg-info"><div class="t">Modo compacto</div><div class="d">Reducir espacios y mostrar más información</div></div>
        <label class="sw"><input type="checkbox" data-setting="compact" data-effect="compact"><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-card-head cfg-sec">
        <h2>Notificaciones</h2>
        <p>Configura cuándo y cómo recibir notificaciones</p>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></span>
        <div class="cfg-info"><div class="t">Notificaciones por email</div><div class="d">Recibir notificaciones importantes por correo electrónico</div></div>
        <label class="sw"><input type="checkbox" data-setting="notif_email" checked><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg></span>
        <div class="cfg-info"><div class="t">Notificaciones push</div><div class="d">Recibir notificaciones push en el navegador</div></div>
        <label class="sw"><input type="checkbox" data-setting="notif_push" data-effect="push" checked><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>
        <div class="cfg-info"><div class="t">Nuevos estudios asignados</div><div class="d">Cuando se te asigna un nuevo estudio</div></div>
        <label class="sw"><input type="checkbox" data-setting="notif_new_studies" checked><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/></svg></span>
        <div class="cfg-info"><div class="t">Reportes generados</div><div class="d">Cuando la IA genera un nuevo reporte</div></div>
        <label class="sw"><input type="checkbox" data-setting="notif_reports" checked><span class="track"></span><span class="knob"></span></label>
      </div>

      <div class="cfg-row">
        <span class="cfg-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
        <div class="cfg-info"><div class="t">Recordatorios de citas</div><div class="d">Recordatorios de citas y seguimiento</div></div>
        <label class="sw"><input type="checkbox" data-setting="notif_reminders"><span class="track"></span><span class="knob"></span></label>
      </div>
    </article>

  </div>
</div>
