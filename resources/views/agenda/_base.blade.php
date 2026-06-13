{{-- ============================================================
     AGENDA / _base.blade.php
     Estilos globales: layout, toolbar, tarjetas resumen, tema claro
     ============================================================ --}}
<style>
/* ---- Layout ---- */
.agenda-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.agenda-header-left h2{font-family:'Sora',sans-serif;font-size:22px;font-weight:700}
.agenda-header-left p{font-size:13.5px;color:var(--txt-soft);margin-top:3px}
.btn-primary{
  display:inline-flex;align-items:center;gap:8px;padding:11px 20px;
  border-radius:var(--r-md);background:linear-gradient(135deg,#1668D9,var(--blue));
  color:#fff;font-size:14px;font-weight:700;
  box-shadow:0 6px 20px -6px rgba(46,123,246,.6);
  transition:opacity 150ms ease,transform 160ms var(--ease-out);
}
.btn-primary:active{transform:scale(.97)}
@media(hover:hover) and (pointer:fine){.btn-primary:hover{opacity:.88}}

.agenda-body{display:grid;grid-template-columns:1fr 260px;gap:18px;align-items:start}
.agenda-left{
  background:#000B1E;border:1.84px solid #168BD9;
  box-shadow:inset 0 0 0 1.84px rgba(0,0,0,.47);
  border-radius:var(--r-lg);padding:22px 22px 26px;color:#EAF1FF;
  min-width:0;
}
.agenda-right{display:flex;flex-direction:column;gap:16px}
.agenda-left.day-view-active{padding:18px;align-self:start;height:fit-content}

/* Botón expandir y estado expandido */
.toolbar-right{display:flex;align-items:center;gap:10px}
.agenda-expand-btn{width:34px;height:34px;border-radius:8px;border:1.5px solid rgba(22,139,217,.5);background:rgba(0,11,30,.8);color:#8FA3CF;cursor:pointer;display:grid;place-items:center;transition:all 150ms ease;flex:none}
.agenda-expand-btn:hover{background:rgba(22,139,217,.2);color:#EAF1FF;border-color:rgba(22,139,217,.8)}
.agenda-expand-btn:active{transform:scale(.95)}
.agenda-expand-btn svg{width:18px;height:18px}

/* Botón filtro en toolbar - solo visible cuando está expandido */
.toolbar-filter-btn{width:34px;height:34px;border-radius:8px;border:1.5px solid rgba(22,139,217,.5);background:rgba(0,11,30,.8);color:#8FA3CF;cursor:pointer;display:none;place-items:center;transition:all 150ms ease;flex:none}
.toolbar-filter-btn:hover{background:rgba(22,139,217,.2);color:#EAF1FF;border-color:rgba(22,139,217,.8)}
.toolbar-filter-btn:active{transform:scale(.95)}
.agenda-left.expanded .toolbar-filter-btn{display:grid}

/* Dropdown de filtros desde toolbar - mismo estilo que filter-card */
.filter-dropdown{display:none;position:absolute;top:calc(100% + 8px);right:0;width:220px;background:#001525;border:1.84px solid #168BD9;box-shadow:inset 0 0 0 1.84px rgba(0,0,0,.47),0 8px 32px rgba(0,0,0,.6);border-radius:11.06px;padding:18px;z-index:100}
.filter-dropdown.open{display:block}
.filter-dropdown h4{font-family:'Sora',sans-serif;font-size:13px;font-weight:700;color:#EAF1FF;margin-bottom:14px}
.toolbar-right{position:relative}
html[data-theme="light"] .toolbar-filter-btn{background:#FFFFFF;color:#5B6A99;border-color:rgba(20,50,120,.35);box-shadow:0 2px 8px rgba(20,50,120,.15)}
html[data-theme="light"] .toolbar-filter-btn:hover{background:#F0F4FA;color:#0E1530;border-color:rgba(20,50,120,.55)}
html[data-theme="light"] .filter-dropdown{background:#FFFFFF;border-color:rgba(20,50,120,.2);box-shadow:inset 0 0 0 1.84px rgba(0,0,0,.05),0 8px 32px rgba(20,50,120,.15)}
html[data-theme="light"] .filter-dropdown h4{color:#0E1530}

/* Opciones de filtro en dropdown - lista con checkboxes */
.filter-list{display:flex;flex-direction:column;gap:10px}
.filter-row{display:flex;align-items:center;gap:10px;cursor:pointer;padding:6px 8px;border-radius:8px;transition:background 150ms ease}
.filter-row:hover{background:rgba(22,139,217,.15)}
.filter-row input{width:16px;height:16px;accent-color:#168BD9;cursor:pointer;flex:none}
.filter-indicator{width:12px;height:12px;border-radius:50%;flex:none;border:1.5px solid transparent}
.filter-indicator.done{background:#4C9242;border-color:#4C9242}
.filter-indicator.wait{background:#E75D01;border-color:#E75D01}
.filter-indicator.cancel{background:#D90000;border-color:#D90000}
.filter-indicator.soon{background:#B263FF;border-color:#B263FF}
.filter-text{font-size:11px;color:#EAF1FF;font-weight:500}

/* Degradados cuando agenda está expandida (tema oscuro) */
.agenda-left.expanded .filter-indicator.done{background:linear-gradient(to top,#042226 20%,#4C9242 100%);border-color:#284D23}
.agenda-left.expanded .filter-indicator.wait{background:linear-gradient(to top,#351909 29%,#9B491A 100%);border-color:#E75D01}
.agenda-left.expanded .filter-indicator.cancel{background:linear-gradient(to top,#251117 38%,#D90000 100%);border-color:#D90000}
.agenda-left.expanded .filter-indicator.soon{background:linear-gradient(to top,#0B1331 43%,#B263FF 100%);border-color:#B263FF}
html[data-theme="light"] .filter-row:hover{background:rgba(20,50,120,.1)}
html[data-theme="light"] .filter-text{color:#0E1530}
html[data-theme="light"] .filter-indicator.done{background:#4C9242;border-color:#4C9242}
html[data-theme="light"] .filter-indicator.wait{background:#E75D01;border-color:#E75D01}
html[data-theme="light"] .filter-indicator.cancel{background:#D90000;border-color:#D90000}
html[data-theme="light"] .filter-indicator.soon{background:#B263FF;border-color:#B263FF}
.agenda-body:has(.agenda-left.expanded){grid-template-columns:1fr 0}
.agenda-left.expanded .agenda-right{display:none}
.agenda-body:has(.agenda-left.expanded) .agenda-right{display:none}
html[data-theme="light"] .agenda-expand-btn{background:#FFFFFF;color:#5B6A99;border-color:rgba(20,50,120,.35);box-shadow:0 2px 8px rgba(20,50,120,.15)}
html[data-theme="light"] .agenda-expand-btn:hover{background:#F0F4FA;color:#0E1530;border-color:rgba(20,50,120,.55)}
.filter-card,.proximas-card{
  background:#001525;border:1.84px solid #168BD9;
  box-shadow:inset 0 0 0 1.84px rgba(0,0,0,.47);
  border-radius:11.06px;padding:18px;color:#EAF1FF;
}

/* ---- Toolbar ---- */
.cal-toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.view-tabs{display:flex;gap:6px}
.view-tab{
  padding:8px 20px;border-radius:var(--r-md);font-size:13.5px;font-weight:600;
  color:var(--txt-soft);border:1px solid var(--stroke);background:transparent;
  transition:background-color 150ms ease,color 150ms ease,border-color 150ms ease;
}
.view-tab.active{background:linear-gradient(135deg,#1668D9,var(--blue));color:#fff;border-color:transparent;box-shadow:0 4px 14px -4px rgba(46,123,246,.5)}
@media(hover:hover) and (pointer:fine){.view-tab:not(.active):hover{border-color:var(--stroke-strong);color:var(--txt)}}

.month-nav{display:flex;align-items:center;gap:10px}
.month-nav button{width:32px;height:32px;border-radius:8px;border:1px solid var(--stroke);display:grid;place-items:center;color:var(--txt-soft);transition:border-color 150ms ease,color 150ms ease}
@media(hover:hover) and (pointer:fine){.month-nav button:hover{border-color:var(--stroke-strong);color:var(--txt)}}
.month-label{display:flex;align-items:center;gap:8px;padding:7px 14px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:14px;font-weight:700;color:var(--txt)}
.month-label svg{color:var(--cyan)}
.month-label .year{color:var(--txt-soft);font-weight:500}
.month-label span{cursor:pointer;transition:color 120ms ease}
.month-label span:hover{color:#EAF1FF}

/* ---- Picker mes/año ---- */
.picker-wrap{position:relative;display:inline-flex}
.picker-dropdown{
  display:none;position:absolute;top:calc(100% + 6px);left:50%;transform:translateX(-50%);
  z-index:200;background:#001525;border:1.84px solid #168BD9;
  border-radius:11.06px;padding:8px;width:160px;max-height:280px;overflow-y:auto;
  box-shadow:0 8px 32px rgba(0,0,0,.6);
}
.picker-dropdown.open{display:flex;flex-direction:column}
.picker-dropdown::-webkit-scrollbar{width:4px}
.picker-dropdown::-webkit-scrollbar-track{background:transparent}
.picker-dropdown::-webkit-scrollbar-thumb{background:rgba(22,139,217,.4);border-radius:4px}
.picker-title{font-size:10.5px;font-weight:600;color:var(--txt-soft);padding:2px 6px 8px;letter-spacing:.06em;text-transform:uppercase}
.picker-item{
  display:block !important;width:100% !important;box-sizing:border-box;text-align:left;
  padding:8px 12px;border-radius:7px;font-size:13px;font-weight:500;color:#EAF1FF;
  background:transparent;border:none;cursor:pointer;transition:background 120ms ease;
}
.picker-item:hover{background:rgba(22,139,217,.18)}
.picker-item.active{background:linear-gradient(135deg,#1668D9,var(--blue));color:#fff;font-weight:700;box-shadow:0 4px 14px -4px rgba(46,123,246,.5)}

/* ---- Tarjetas resumen ---- */
.summary-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px}
.sum-card{border-radius:6.91px;padding:14px 16px;display:flex;align-items:center;gap:12px;min-width:0}
.sum-card .sum-icon{width:52px;height:52px;flex:none;border-radius:10px;display:grid;place-items:center}
.sum-card .sum-icon svg{width:32px;height:32px}
.sum-card .sum-num{font-family:'Sora',sans-serif;font-size:26px;font-weight:800;line-height:1}
.sum-card .sum-lbl{font-size:11.5px;color:var(--txt-soft);margin-top:3px;line-height:1.3}
.sum-card .sum-title{font-size:13px;font-weight:700;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sum-card .sum-text{min-width:0;flex:1;overflow:hidden}
.sum-done{background:linear-gradient(to bottom,#042226 20%,#4C9242 80%);border:1.38px solid #284D23;box-shadow:inset 0 0 0 1.38px rgba(0,0,0,.3)}
.sum-done .sum-icon{background:transparent;color:#4C9242}
.sum-done .sum-title{color:#4C9242}
.sum-done .sum-num{color:#fff}
.sum-done .sum-lbl{color:rgba(255,255,255,.65)}
.sum-wait{background:linear-gradient(to bottom,#351909 29%,#9B491A 100%);border:1.24px solid #E75D01;box-shadow:inset 0 0 0 1.24px rgba(0,0,0,.3);border-radius:6.21px}
.sum-wait .sum-icon{background:transparent;color:#E75D01}
.sum-wait .sum-title{color:#E75D01}
.sum-wait .sum-num{color:#fff}
.sum-wait .sum-lbl{color:rgba(255,255,255,.65)}
.sum-cancel{background:linear-gradient(to bottom,#251117 38%,#D90000 100%);border:1.27px solid #D90000;box-shadow:inset 0 0 0 1.27px rgba(6,6,6,.20);border-radius:6.21px}
.sum-cancel .sum-icon{background:transparent;color:#D90000}
.sum-cancel .sum-title{color:#D90000}
.sum-cancel .sum-num{color:#fff}
.sum-cancel .sum-lbl{color:rgba(255,255,255,.65)}
.sum-soon{background:linear-gradient(to bottom,#0B1331 43%,#B263FF 100%);border:1.27px solid #B263FF;box-shadow:inset 0 0 0 1.27px rgba(6,6,6,.20);border-radius:6.21px}
.sum-soon .sum-icon{background:transparent;color:#B263FF}
.sum-soon .sum-title{color:#B263FF}
.sum-soon .sum-num{color:#fff}
.sum-soon .sum-lbl{color:rgba(255,255,255,.65)}

/* ---- Panel sidebar (filtros + próximas) ---- */
.filter-card h4,.proximas-card h4{font-family:'Sora',sans-serif;font-size:13.5px;font-weight:700;margin-bottom:14px}
.filter-item{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:500;padding:5px 0;cursor:pointer;user-select:none}
.filter-item input[type=checkbox]{width:15px;height:15px;accent-color:var(--blue);flex:none}
.filter-dot{width:9px;height:9px;border-radius:50%;flex:none}
.prox-item{display:flex;align-items:center;gap:12px;padding:10px 12px;background:#001525;border:1.84px solid #168BD9;box-shadow:inset 0 0 0 1.84px rgba(0,0,0,.47);border-radius:11.06px;margin-bottom:8px}
.prox-item:last-of-type{margin-bottom:0}
.prox-avatar{width:42px;height:42px;flex:none;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,var(--blue),var(--cyan));display:grid;place-items:center;font-family:'Sora',sans-serif;font-size:12px;font-weight:700;color:#fff;border:2px solid rgba(22,139,217,.4)}
.prox-avatar img{width:100%;height:100%;object-fit:cover;border-radius:50%}
.prox-info strong{display:block;font-size:13px;font-weight:700;color:#EAF1FF;line-height:1.3;margin-bottom:1px}
.prox-info span{display:block;font-size:11px;line-height:1.4}
.prox-info span:first-of-type{color:rgba(234,241,255,.7)}
.prox-info span:last-of-type{color:rgba(234,241,255,.45)}
.more-link{display:block;text-align:center;margin-top:12px;font-size:13px;font-weight:700;color:var(--blue);transition:color 150ms ease}
@media(hover:hover) and (pointer:fine){.more-link:hover{color:var(--cyan)}}

/* ---- Responsive ---- */
@media(max-width:1200px){
  .agenda-body{grid-template-columns:1fr}
  .agenda-body:has(.agenda-left.expanded){grid-template-columns:1fr}
  .agenda-left.expanded .agenda-right{opacity:0;pointer-events:none}
  .agenda-left.expanded .filter-card,
  .agenda-left.expanded .proximas-card{display:none}
  .agenda-left.expanded .cal-wrap,
  .agenda-left.expanded .week-grid{max-height:none;overflow:visible}
  .agenda-left.expanded .day-view-body{grid-template-columns:1fr}
  .agenda-left.expanded .day-panel{display:none}
  .agenda-right{flex-direction:row;flex-wrap:wrap}
  .filter-card,.proximas-card{flex:1;min-width:220px}
  .summary-cards{grid-template-columns:repeat(2,1fr)}
  .agenda-expand-btn{display:none}
}
@media(max-width:900px){
  .agenda-left{padding:16px 14px 20px;border-radius:12px}
  .agenda-body{gap:12px}
  .summary-cards{grid-template-columns:1fr 1fr}
  .sum-card{padding:12px 10px;gap:8px}
  .sum-card .sum-icon{width:38px;height:38px}
  .sum-card .sum-icon svg{width:20px;height:20px}
  .sum-card .sum-num{font-size:20px}
  .sum-card .sum-title{font-size:11px}
  .sum-card .sum-lbl{font-size:9.5px}
}
@media(max-width:720px){
  /* agenda-left */
  .agenda-left{padding:16px 14px 20px;border-radius:12px}
  .agenda-body{gap:12px}
  /* Header */
  .agenda-header{flex-direction:column;align-items:stretch;gap:10px}
  .agenda-header .btn-primary{justify-content:center}
  /* Tarjetas 2x2 */
  .summary-cards{grid-template-columns:1fr 1fr}
  .sum-card{padding:12px 10px;gap:8px}
  .sum-card .sum-icon{width:38px;height:38px}
  .sum-card .sum-icon svg{width:20px;height:20px}
  .sum-card .sum-num{font-size:20px}
  .sum-card .sum-title{font-size:11px}
  .sum-card .sum-lbl{font-size:9.5px}
  /* Toolbar */
  .cal-toolbar{flex-wrap:wrap;gap:8px}
  .view-tabs{flex:1;justify-content:center}
  .month-nav{justify-content:center;flex:1}
  /* Sidebar apilado */
  .agenda-right{flex-direction:column}
  .filter-card,.proximas-card{min-width:unset}
  /* Mes: scroll horizontal con buen ancho */
  .cal-wrap{overflow-x:auto;overflow-y:auto;-webkit-overflow-scrolling:touch}
  .cal-grid{min-width:580px;table-layout:fixed}
  .cal-grid th{font-size:10px;padding:6px 3px}
  .cal-grid td{min-height:72px;padding:4px 3px;vertical-align:top}
  .day-num{font-size:11px;width:21px;height:21px}
  .cal-event{font-size:9px;padding:2px 5px;white-space:normal;line-height:1.3}
}
@media(max-width:540px){
  /* agenda-left más compacto */
  .agenda-left{padding:12px 10px 16px;border-radius:10px}
  .agenda-body{gap:8px}
  /* Tarjetas compactas */
  .sum-card{padding:10px 12px;gap:8px}
  /* Mes */
  .cal-grid{min-width:500px}
  .cal-grid th{font-size:9px;padding:5px 2px}
  .cal-grid td{min-height:60px;padding:3px 2px}
  .day-num{font-size:10px;width:18px;height:18px}
  .cal-event{font-size:8.5px;padding:2px 4px}
  /* Tabs */
  .view-tab{padding:6px 10px;font-size:11.5px}
  .month-label{font-size:12px;padding:5px 10px}
  .month-nav button{width:28px;height:28px}
  /* Vista día */
  .day-view.active{grid-template-columns:1fr}
  .day-panel{display:none}
  .day-nav-btn{padding:6px 10px;font-size:11px}
  .day-title-btn{font-size:12px;padding:5px 10px}
  /* Vista semana */
  .week-grid{overflow-x:auto;-webkit-overflow-scrolling:touch}
  .week-table{min-width:500px}
}

/* ---- Tema claro — base ---- */
html[data-theme="light"] .agenda-left{background:#FFFFFF;border-color:rgba(20,50,120,.2);box-shadow:0 4px 24px rgba(20,50,120,.08);color:#0E1530}
html[data-theme="light"] .filter-card,
html[data-theme="light"] .proximas-card{background:#FFFFFF;border-color:rgba(20,50,120,.2);box-shadow:0 4px 16px rgba(20,50,120,.07);color:#0E1530}
html[data-theme="light"] .filter-card h4,
html[data-theme="light"] .proximas-card h4{color:#0E1530}
html[data-theme="light"] .filter-item{color:#0E1530}
html[data-theme="light"] .month-label{color:#0E1530;border-color:rgba(20,50,120,.25)}
html[data-theme="light"] .month-label .year{color:#5B6A99}
html[data-theme="light"] .month-nav button{border-color:rgba(20,50,120,.2);color:#5B6A99}
html[data-theme="light"] .picker-dropdown{background:#FFFFFF;border-color:rgba(20,50,120,.25);box-shadow:0 8px 32px rgba(20,50,120,.15)}
html[data-theme="light"] .picker-title{color:#5B6A99;font-size:11px;font-weight:700}
html[data-theme="light"] .picker-item{color:#0E1530;background:#F6F8FE;border:1px solid rgba(20,50,120,.1);margin-bottom:4px;border-radius:8px}
html[data-theme="light"] .picker-item:last-of-type{margin-bottom:0}
html[data-theme="light"] .picker-item:hover{background:#E4EDFF;border-color:rgba(46,123,246,.3)}
html[data-theme="light"] .picker-item.active{background:linear-gradient(135deg,#1668D9,#2E7BF6);border-color:transparent;color:#fff}
html[data-theme="light"] .prox-item{background:#F0F5FF;border-color:rgba(20,50,120,.15);box-shadow:0 2px 8px rgba(20,50,120,.07)}
html[data-theme="light"] .prox-info strong{color:#0E1530}
html[data-theme="light"] .prox-info span:first-of-type{color:rgba(14,21,48,.6)}
html[data-theme="light"] .prox-info span:last-of-type{color:rgba(14,21,48,.4)}
html[data-theme="light"] .view-tab{color:#3A5CA8;border-color:rgba(20,50,120,.3);background:#EEF4FF;font-weight:600}
html[data-theme="light"] .view-tab.active{background:linear-gradient(135deg,#1668D9,#2E7BF6);color:#fff;border-color:transparent}
html[data-theme="light"] .fi-done .filter-dot{background:#4C9242 !important;border-color:#2E6E27 !important}
html[data-theme="light"] .fi-wait .filter-dot{background:#E75D01 !important;border-color:#B84700 !important}
html[data-theme="light"] .fi-cancel .filter-dot{background:#D90000 !important;border-color:#A80000 !important}
html[data-theme="light"] .fi-soon .filter-dot{background:#B263FF !important;border-color:#7B30D4 !important}
html[data-theme="light"] .sum-done{background:#EBF7EA;border-color:#4C9242;box-shadow:none}
html[data-theme="light"] .sum-done .sum-title{color:#2E6E27}
html[data-theme="light"] .sum-done .sum-num{color:#1B4518}
html[data-theme="light"] .sum-done .sum-lbl{color:rgba(27,69,24,.55)}
html[data-theme="light"] .sum-done .sum-icon{color:#4C9242}
html[data-theme="light"] .sum-wait{background:#FEF3E7;border-color:#E75D01;box-shadow:none}
html[data-theme="light"] .sum-wait .sum-title{color:#B84700}
html[data-theme="light"] .sum-wait .sum-num{color:#7A2F00}
html[data-theme="light"] .sum-wait .sum-lbl{color:rgba(122,47,0,.55)}
html[data-theme="light"] .sum-wait .sum-icon{color:#E75D01}
html[data-theme="light"] .sum-cancel{background:#FDE8E8;border-color:#D90000;box-shadow:none}
html[data-theme="light"] .sum-cancel .sum-title{color:#A80000}
html[data-theme="light"] .sum-cancel .sum-num{color:#6B0000}
html[data-theme="light"] .sum-cancel .sum-lbl{color:rgba(107,0,0,.5)}
html[data-theme="light"] .sum-cancel .sum-icon{color:#D90000}
html[data-theme="light"] .sum-soon{background:#F3ECFF;border-color:#B263FF;box-shadow:none}
html[data-theme="light"] .sum-soon .sum-title{color:#7B30D4}
html[data-theme="light"] .sum-soon .sum-num{color:#4A1A8A}
html[data-theme="light"] .sum-soon .sum-lbl{color:rgba(74,26,138,.5)}
html[data-theme="light"] .sum-soon .sum-icon{color:#B263FF}
</style>
