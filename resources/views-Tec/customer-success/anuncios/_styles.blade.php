<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.cs-shell{max-width:1200px}
.cs-card{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:16px;
  padding:24px;margin-bottom:20px;
}
.cs-card-title{font-size:16px;font-weight:700;color:var(--txt);margin-bottom:16px}
.cs-form{display:grid;gap:14px}
.cs-field{display:grid;gap:6px}
.cs-label{font-size:12px;font-weight:600;color:var(--txt-soft)}
.cs-input,.cs-textarea{
  width:100%;background:var(--panel);border:1px solid var(--stroke-strong);border-radius:10px;
  padding:10px 12px;color:var(--txt);font-size:13px;outline:none;box-sizing:border-box;
}
.cs-textarea{min-height:120px;resize:vertical}
.cs-input:focus,.cs-textarea:focus{border-color:var(--blue)}
.cs-row{display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap}
.cs-field-group{display:flex;gap:12px;align-items:flex-start;flex-wrap:wrap}
.cs-btn{
  display:inline-flex;align-items:center;gap:8px;height:40px;padding:0 18px;
  border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all 150ms;
  border:none;text-decoration:none;
}
.cs-btn-primary{background:var(--blue);color:#fff}
.cs-btn-primary:hover{background:var(--cyan)}
.cs-btn-danger{background:rgba(220,38,38,.12);color:#ef4444}
.cs-btn-danger:hover{background:rgba(220,38,38,.2)}
.cs-btn-secondary{background:var(--panel);border:1px solid var(--stroke);color:var(--txt-soft)}
.cs-btn-secondary:hover{border-color:var(--blue);color:var(--blue)}

/* Botones de acción en tabla (ícono) */
.cs-action-btn{
  position:relative;
  display:inline-flex;align-items:center;justify-content:center;
  width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;
  transition:background 140ms,color 140ms,transform 100ms;
  background:transparent;
}
.cs-action-btn svg{width:15px;height:15px;stroke-width:2;pointer-events:none}
.cs-action-btn:active{transform:scale(.9)}
.cs-action-btn[data-tip]{position:relative}
.cs-action-btn[data-tip]::after{
  content:attr(data-tip);
  position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);
  background:#1e2530;color:#f1f5f9;font-size:11px;font-weight:600;
  white-space:nowrap;padding:3px 8px;border-radius:6px;pointer-events:none;
  opacity:0;transition:opacity 120ms;
}
.cs-action-btn[data-tip]:hover::after{opacity:1}
.cs-action-view{color:#60a5fa}
.cs-action-view:hover{background:rgba(59,130,246,.12)}
.cs-action-edit{color:#34d399}
.cs-action-edit:hover{background:rgba(52,211,153,.12)}
.cs-action-delete{color:#f87171}
.cs-action-delete:hover{background:rgba(248,113,113,.12)}

/* Wrapper tipo + candado */
.cs-tipo-wrap{position:relative;display:flex;align-items:center}
.cs-tipo-wrap .cs-input{flex:1;padding-right:36px}
.cs-tipo-lock{
  display:none;position:absolute;right:10px;
  color:var(--txt-soft);pointer-events:none;
}
.cs-tipo-lock svg{width:15px;height:15px}
.cs-tipo-wrap.locked .cs-tipo-lock{display:flex;align-items:center}
.cs-tipo-wrap.locked .cs-input{
  opacity:.65;cursor:not-allowed;
  background:repeating-linear-gradient(135deg,transparent,transparent 4px,rgba(0,0,0,.03) 4px,rgba(0,0,0,.03) 8px);
  appearance:none;-webkit-appearance:none;-moz-appearance:none;
}

/* Banner de edición activa */
.cs-edit-banner{
  display:none;align-items:center;gap:12px;
  background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.35);
  border-radius:10px;padding:10px 16px;margin-bottom:12px;
  font-size:13px;color:var(--txt);
}
.cs-edit-banner.visible{display:flex}
.cs-edit-banner-icon{font-size:18px;flex:none}
.cs-edit-banner-text{flex:1;line-height:1.4}
.cs-edit-banner-text strong{color:#34d399}
.cs-edit-banner-close{
  background:none;border:none;cursor:pointer;color:var(--txt-soft);
  font-size:18px;line-height:1;padding:0 4px;
  transition:color 140ms;flex:none;
}
.cs-edit-banner-close:hover{color:#ef4444}
.cs-table{width:100%;border-collapse:collapse}
.cs-table th,.cs-table td{padding:12px;text-align:left;font-size:13px;border-bottom:1px solid var(--stroke)}
.cs-table th{color:var(--txt-soft);font-weight:600}
.cs-table td{color:var(--txt)}
.cs-empty{text-align:center;padding:40px;color:var(--txt-soft)}

/* Paginación */
.paginacion-laravel{margin-top:16px}
.paginacion-mobile{display:none;gap:10px;justify-content:space-between}
.paginacion-desktop{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.paginacion-boton,.paginacion-item{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 10px;border-radius:8px;font-size:13px;color:var(--txt-soft);text-decoration:none;border:1px solid var(--stroke);background:var(--panel);transition:all 150ms}
.paginacion-boton:hover,.paginacion-item:hover{background:var(--panel-2);color:var(--txt);border-color:var(--blue)}
.paginacion-item.active{background:var(--blue);color:#fff;border-color:var(--blue)}
.paginacion-item.disabled{opacity:.5;cursor:not-allowed}
.paginacion-item.disabled:hover{background:var(--panel);color:var(--txt-soft);border-color:var(--stroke)}
.paginacion-item svg{width:16px;height:16px}
.paginacion-item.dots{border:none;background:transparent;cursor:default}
.paginacion-info{font-size:13px;color:var(--txt-soft)}
.paginacion-info strong{color:var(--txt);font-weight:600}
.paginacion-links{display:inline-flex;align-items:center;gap:6px;flex-wrap:wrap}

@media (max-width:640px){
  .paginacion-mobile{display:flex}
  .paginacion-desktop{display:none}
}
.cs-alert{padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:13px;display:none}
.cs-alert.success{background:rgba(34,197,94,.12);color:#22c55e}
.cs-alert.error{background:rgba(220,38,38,.12);color:#ef4444}

/* Editor WYSIWYG */
.cs-editor-wrap{border:1px solid var(--stroke-strong);border-radius:10px;background:var(--panel);overflow:hidden}
.cs-editor-toolbar{display:flex;align-items:center;gap:4px;padding:8px 12px;border-bottom:1px solid var(--stroke);background:var(--panel-2);flex-wrap:wrap}
.cs-editor-toolbar button{width:26px;height:26px;display:grid;place-items:center;border-radius:6px;border:0;background:transparent;color:var(--txt-soft);cursor:pointer;transition:all 150ms}
.cs-editor-toolbar button:hover{color:var(--cyan);background:rgba(56,199,244,.1)}
.cs-editor-toolbar button.active{color:var(--cyan);background:rgba(56,199,244,.18);box-shadow:inset 0 0 0 1px rgba(56,199,244,.45)}
.cs-editor-toolbar button svg{width:12px;height:12px;stroke-width:1.5}
.cs-editor-toolbar .sep{width:1px;height:18px;background:var(--stroke);margin:0 4px}
.cs-editor-content{min-height:160px;max-height:320px;overflow-y:auto;padding:14px 16px;font-size:13px;line-height:1.6;color:var(--txt);outline:none}
.cs-editor-content:empty:before{content:attr(data-placeholder);color:var(--txt-soft);opacity:.7}
.cs-editor-content ul{list-style:disc;padding-left:20px}
.cs-editor-content ol{list-style:decimal;padding-left:20px}
.cs-editor-content a{color:var(--cyan);text-decoration:underline}

/* Canales */
.cs-channels{display:flex;gap:16px;flex-wrap:wrap}
.cs-channel{display:flex;align-items:center;gap:8px;cursor:pointer}
.cs-channel input{width:16px;height:16px;cursor:pointer}

/* Mini-modal de confirmación */
.cs-confirm-ov{
  position:fixed;inset:0;z-index:4000;
  background:rgba(0,0,0,.55);backdrop-filter:blur(3px);
  display:flex;align-items:center;justify-content:center;
  opacity:0;visibility:hidden;transition:opacity 180ms ease,visibility 180ms ease;
}
.cs-confirm-ov.open{opacity:1;visibility:visible}
.cs-confirm-box{
  background:var(--panel);border:1px solid var(--stroke);
  border-radius:14px;padding:22px 24px;max-width:360px;width:90%;
  box-shadow:0 8px 32px rgba(0,0,0,.35);
  transform:scale(.94);transition:transform 180ms var(--ease-out,cubic-bezier(.16,1,.3,1));
}
.cs-confirm-ov.open .cs-confirm-box{transform:scale(1)}
.cs-confirm-msg{font-size:14px;color:var(--txt);margin:0 0 18px;line-height:1.5}
.cs-confirm-btns{display:flex;gap:10px;justify-content:flex-end}

/* Vista previa base */
.pv-ov{position:fixed;inset:0;z-index:3000;background:rgba(8,12,18,.78);backdrop-filter:blur(4px);display:none;flex-direction:column}
.pv-ov.open{display:flex}
.pv-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:13px 20px;background:var(--panel);border-bottom:1px solid var(--stroke);flex:none}
.pv-title{font-size:14px;font-weight:700;color:var(--txt)}
.pv-scroll{flex:1;overflow:auto;padding:26px 16px;display:flex;justify-content:center;align-items:flex-start}
.pv-card{width:100%;max-width:640px;border-radius:16px;padding:28px;color:var(--txt);transition:all .2s}
.pv-card h2{margin:0 0 12px;font-size:20px;font-weight:800}
.pv-card .meta{font-size:12px;margin-bottom:16px}
.pv-card .body{font-size:13px;line-height:1.7}
.pv-card .body ul,.pv-card .body ol{padding-left:20px}
.pv-card .pv-badge{display:inline-flex;align-items:center;gap:6px;border-radius:20px;font-size:11px;font-weight:700;padding:4px 12px;margin-bottom:14px}
.pv-card .pv-icon{font-size:28px;margin-bottom:12px;display:block}

/* ===== TEMA OSCURO (default) — neón intenso ===== */
.theme-notificacion{
  background:linear-gradient(135deg,#1e1030 0%,#0f0720 100%);
  border:2.5px solid #a855f7;
  box-shadow:0 0 0 4px rgba(139,92,246,.25),0 0 32px rgba(168,85,247,.4);
}
.theme-notificacion .pv-badge{
  background:rgba(139,92,246,.25);color:#ddd6fe;border:1.5px solid #a855f7;
}
.theme-notificacion h2{color:#f5f3ff;font-size:20px;border-bottom:2.5px solid #a855f7;padding-bottom:10px;margin-bottom:8px}
.theme-notificacion .meta{color:#a78bfa;font-weight:500}
.theme-notificacion .body{color:#ede9fe}

.theme-anuncios_internos{
  background:linear-gradient(135deg,#071025 0%,#030712 100%);
  border:2.5px solid #2563eb;
  box-shadow:0 0 0 4px rgba(59,130,246,.28),0 0 32px rgba(37,99,235,.4);
}
.theme-anuncios_internos .pv-badge{
  background:rgba(59,130,246,.28);color:#bfdbfe;border:1.5px solid #2563eb;
}
.theme-anuncios_internos h2{color:#f8fafc;font-size:20px;border-bottom:2.5px solid #2563eb;padding-bottom:10px;margin-bottom:8px}
.theme-anuncios_internos .meta{color:#60a5fa;font-weight:500}
.theme-anuncios_internos .body{color:#dbeafe}

.theme-mejoras{
  background:linear-gradient(135deg,#022c22 0%,#011613 100%);
  border:2.5px solid #059669;
  box-shadow:0 0 0 4px rgba(16,185,129,.25),0 0 32px rgba(5,150,105,.4);
}
.theme-mejoras .pv-badge{
  background:rgba(16,185,129,.25);color:#a7f3d0;border:1.5px solid #059669;
}
.theme-mejoras h2{color:#ecfdf5;font-size:20px;border-bottom:2.5px solid #059669;padding-bottom:10px;margin-bottom:8px}
.theme-mejoras .meta{color:#34d399;font-weight:500}
.theme-mejoras .body{color:#a7f3d0}
.theme-mejoras .body ul{list-style:none;padding-left:0}
.theme-mejoras .body ul li::before{content:'✦ ';color:#34d399}

.theme-mantenimiento{
  background:linear-gradient(135deg,#281b02 0%,#1a1200 100%);
  border:2.5px solid #d97706;
  box-shadow:0 0 0 4px rgba(245,158,11,.25),0 0 32px rgba(217,119,6,.4);
}
.theme-mantenimiento .pv-badge{
  background:rgba(245,158,11,.25);color:#fde68a;border:1.5px solid #d97706;
}
.theme-mantenimiento .pv-icon{font-size:28px;margin-bottom:12px;display:block}
.theme-mantenimiento h2{color:#fffbeb;font-size:20px;border-bottom:2.5px solid #d97706;padding-bottom:10px;margin-bottom:8px}
.theme-mantenimiento .meta{color:#fbbf24;font-weight:500}
.theme-mantenimiento .body{color:#fde68a}
.theme-mantenimiento .body strong{color:#fbbf24}

.theme-politicas{
  background:linear-gradient(135deg,#1a1a1a 0%,#0d0d0d 100%);
  border:2.5px solid #9ca3af;
  box-shadow:0 0 0 4px rgba(156,163,175,.2),0 0 28px rgba(156,163,175,.3);
}
.theme-politicas .pv-badge{
  background:rgba(156,163,175,.18);color:#d1d5db;border:1.5px solid #9ca3af;border-radius:4px;
}
.theme-politicas h2{color:#f9fafb;font-size:20px;border-bottom:2.5px solid #9ca3af;padding-bottom:8px;margin-bottom:12px}
.theme-politicas .meta{color:#9ca3af;font-weight:500}
.theme-politicas .body{color:#e5e7eb;text-align:justify}
.theme-politicas .body h3{color:#f9fafb;margin-top:16px}
.theme-politicas .body ol{list-style:decimal;padding-left:20px}

/* ===== TEMA CLARO — neón adaptado a fondo claro ===== */
html[data-theme="light"] .theme-notificacion{
  background:linear-gradient(135deg,#faf5ff 0%,#f3e8ff 100%);
  border:2.5px solid #7c3aed;
  box-shadow:0 0 0 4px rgba(139,92,246,.18),0 0 32px rgba(124,58,237,.3);
}
html[data-theme="light"] .theme-notificacion .pv-badge{
  background:rgba(139,92,246,.18);color:#6d28d9;border:1.5px solid #7c3aed;
}
html[data-theme="light"] .theme-notificacion h2{color:#4c1d95;font-size:20px;border-bottom:2.5px solid #7c3aed;padding-bottom:10px;margin-bottom:8px}
html[data-theme="light"] .theme-notificacion .meta{color:#7c3aed;font-weight:500}
html[data-theme="light"] .theme-notificacion .body{color:#3b0764}

html[data-theme="light"] .theme-anuncios_internos{
  background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);
  border:2.5px solid #1d4ed8;
  box-shadow:0 0 0 4px rgba(59,130,246,.18),0 0 32px rgba(29,78,216,.3);
}
html[data-theme="light"] .theme-anuncios_internos .pv-badge{
  background:rgba(59,130,246,.18);color:#1d4ed8;border:1.5px solid #1d4ed8;
}
html[data-theme="light"] .theme-anuncios_internos h2{color:#1e3a8a;font-size:20px;border-bottom:2.5px solid #1d4ed8;padding-bottom:10px;margin-bottom:8px}
html[data-theme="light"] .theme-anuncios_internos .meta{color:#2563eb;font-weight:500}
html[data-theme="light"] .theme-anuncios_internos .body{color:#1e293b}

html[data-theme="light"] .theme-mejoras{
  background:linear-gradient(135deg,#ecfdf5 0%,#d1fae5 100%);
  border:2.5px solid #047857;
  box-shadow:0 0 0 4px rgba(16,185,129,.18),0 0 32px rgba(4,120,87,.3);
}
html[data-theme="light"] .theme-mejoras .pv-badge{
  background:rgba(16,185,129,.18);color:#047857;border:1.5px solid #047857;
}
html[data-theme="light"] .theme-mejoras h2{color:#064e3b;font-size:20px;border-bottom:2.5px solid #047857;padding-bottom:10px;margin-bottom:8px}
html[data-theme="light"] .theme-mejoras .meta{color:#059669;font-weight:500}
html[data-theme="light"] .theme-mejoras .body{color:#064e3b}
html[data-theme="light"] .theme-mejoras .body ul{list-style:none;padding-left:0}
html[data-theme="light"] .theme-mejoras .body ul li::before{content:'✦ ';color:#059669}

html[data-theme="light"] .theme-mantenimiento{
  background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);
  border:2.5px solid #b45309;
  box-shadow:0 0 0 4px rgba(245,158,11,.18),0 0 32px rgba(180,83,9,.3);
}
html[data-theme="light"] .theme-mantenimiento .pv-badge{
  background:rgba(245,158,11,.18);color:#b45309;border:1.5px solid #b45309;
}
html[data-theme="light"] .theme-mantenimiento .pv-icon{font-size:28px;margin-bottom:12px;display:block}
html[data-theme="light"] .theme-mantenimiento h2{color:#78350f;font-size:20px;border-bottom:2.5px solid #b45309;padding-bottom:10px;margin-bottom:8px}
html[data-theme="light"] .theme-mantenimiento .meta{color:#d97706;font-weight:500}
html[data-theme="light"] .theme-mantenimiento .body{color:#78350f}
html[data-theme="light"] .theme-mantenimiento .body strong{color:#b45309}

html[data-theme="light"] .theme-politicas{
  background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);
  border:2.5px solid #334155;
  box-shadow:0 0 0 4px rgba(51,65,85,.15),0 0 28px rgba(51,65,85,.25);
}
html[data-theme="light"] .theme-politicas .pv-badge{
  background:#f1f5f9;color:#1e293b;border:1.5px solid #334155;border-radius:4px;
}
html[data-theme="light"] .theme-politicas h2{color:#0f172a;font-size:20px;border-bottom:2.5px solid #334155;padding-bottom:8px;margin-bottom:12px}
html[data-theme="light"] .theme-politicas .meta{color:#475569;font-weight:500}
html[data-theme="light"] .theme-politicas .body{color:#1e293b;text-align:justify}
html[data-theme="light"] .theme-politicas .body h3{color:#0f172a;margin-top:16px}
html[data-theme="light"] .theme-politicas .body ol{list-style:decimal;padding-left:20px}

/* Flatpickr custom theme — CLARO */
.flatpickr-calendar{
  background:#ffffff;
  border:1px solid #d1d5db;
  border-radius:12px;
  box-shadow:0 10px 30px rgba(0,0,0,.25);
  overflow:hidden;
}
.flatpickr-months{
  background:#f8fafc;
  border-bottom:1px solid #e5e7eb;
}
.flatpickr-month,
.flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year,
.flatpickr-current-month .flatpickr-monthDropdown-months{
  color:#111827;
  font-weight:700;
}
.flatpickr-weekday{
  color:#6b7280;
  font-weight:700;
}
.flatpickr-day{
  color:#1f2937;
  border-radius:8px;
  font-weight:500;
}
.flatpickr-day:hover,
.flatpickr-day:focus{
  background:#3b82f6;
  border-color:#3b82f6;
  color:#fff;
}
.flatpickr-day.selected,
.flatpickr-day.selected:hover,
.flatpickr-day.selected:focus{
  background:#2563eb;
  border-color:#2563eb;
  color:#fff;
  font-weight:800;
}
.flatpickr-day.today{
  border-color:#2563eb;
  color:#2563eb;
  font-weight:700;
}
.flatpickr-day.today:hover{
  background:#2563eb;
  color:#fff;
}
.flatpickr-day.prevMonthDay,
.flatpickr-day.nextMonthDay,
.flatpickr-day.notAllowed{
  color:#9ca3af;
  opacity:1;
}
.flatpickr-time{
  border-top:1px solid #e5e7eb;
}
.flatpickr-time input,
.flatpickr-time .flatpickr-am-pm{
  color:#111827;
  font-weight:600;
}
.flatpickr-time input:hover,
.flatpickr-time .flatpickr-am-pm:hover,
.flatpickr-time input:focus,
.flatpickr-time .flatpickr-am-pm:focus{
  background:#f3f4f6;
}
.flatpickr-time .numInputWrapper span.arrowUp:after,
.flatpickr-time .numInputWrapper span.arrowDown:after{
  border-bottom-color:#6b7280;
  border-top-color:#6b7280;
}
.flatpickr-months .flatpickr-prev-month,
.flatpickr-months .flatpickr-next-month{
  fill:#6b7280;
  color:#6b7280;
}
.flatpickr-months .flatpickr-prev-month:hover,
.flatpickr-months .flatpickr-next-month:hover{
  fill:#2563eb;
  color:#2563eb;
}
.flatpickr-day.inRange,
.flatpickr-day.prevMonthDay.inRange,
.flatpickr-day.nextMonthDay.inRange,
.flatpickr-day.today.inRange,
.flatpickr-day.prevMonthDay.today.inRange,
.flatpickr-day.nextMonthDay.today.inRange{
  background:#dbeafe;
  border-color:#dbeafe;
  color:#1e40af;
}
.flatpickr-day.inRange:hover{
  background:#3b82f6;
  border-color:#3b82f6;
  color:#fff;
}
.flatpickr-input.cs-input{
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%2360a5fa' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
  background-repeat:no-repeat;
  background-position:right 10px center;
  background-size:18px;
}
</style>
