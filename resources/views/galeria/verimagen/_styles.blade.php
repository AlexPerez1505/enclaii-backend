@push('styles')
<style>
/* ===== VER IMAGEN ===== */
.main{padding-top:18px}
.head{margin-bottom:14px}
.vi-wrap{display:grid;grid-template-columns:1fr 280px;gap:18px;align-items:start}

/* Topbar */
.vi-topbar{display:flex;align-items:center;gap:8px;margin-bottom:14px}
.vi-btn{
  display:flex;align-items:center;gap:7px;
  height:38px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;
  text-decoration:none;
  transition:background-color 150ms ease,transform 160ms var(--ease-out);
}
.vi-btn:active{transform:scale(.97)}
.vi-btn.back{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.vi-btn.back:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vi-topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
.vi-btn.report{background:rgba(46,123,246,.14);border:1px solid rgba(46,123,246,.35);color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vi-btn.report:hover{background:rgba(46,123,246,.25)}}
.vi-btn.share{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.vi-btn.share:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vi-btn.wa{background:rgba(61,220,151,.12);border:1px solid rgba(61,220,151,.35);color:var(--green)}
@media(hover:hover)and(pointer:fine){.vi-btn.wa:hover{background:rgba(61,220,151,.22)}}
.vi-btn.dl{background:var(--blue);border:none;color:#fff}
@media(hover:hover)and(pointer:fine){.vi-btn.dl:hover{opacity:.88}}

/* Visor de imagen */
.vi-viewer-box{
  background:#000;border-radius:14px;overflow:hidden;
  position:relative;aspect-ratio:4/3;
  display:flex;align-items:center;justify-content:center;
}
.vi-img-bg{
  position:absolute;inset:0;
  background:radial-gradient(ellipse at 50% 45%,#6a1a10 0%,#3a0808 35%,#0a0410 70%,#06081c 100%);
}
.vi-main-image{
  position:relative;z-index:2;
  max-width:100%;max-height:100%;
  object-fit:contain;display:none;
  transform-origin:center center;
  transition:transform 160ms var(--ease-out);
}
.vi-img-placeholder{
  position:relative;z-index:2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
  color:rgba(255,255,255,.25);font-size:13px;
}
.vi-annotation-canvas{
  position:absolute;inset:0;z-index:3;
  width:100%;height:100%;
  pointer-events:none;touch-action:none;
}
.vi-measure-canvas{
  position:absolute;inset:0;z-index:3;
  width:100%;height:100%;
  pointer-events:none;touch-action:none;
}
.vi-viewer-box.annotating .vi-annotation-canvas{pointer-events:auto;cursor:crosshair}
.vi-viewer-box.measuring .vi-measure-canvas{pointer-events:auto;cursor:crosshair}
.vi-viewer-box.annotating .vi-img-placeholder{pointer-events:none}
.vi-viewer-box.measuring .vi-img-placeholder{pointer-events:none}
/* Badge contador */
.vi-counter-badge{
  position:absolute;top:14px;left:14px;z-index:4;
  background:rgba(0,0,0,.55);backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.12);
  border-radius:8px;padding:5px 12px;
  font-size:12.5px;font-weight:600;color:rgba(255,255,255,.85);
}
/* Thumb preview */
.vi-thumb-preview{
  position:absolute;top:14px;right:14px;z-index:4;
  width:72px;height:54px;border-radius:8px;overflow:hidden;
  border:2px solid rgba(255,255,255,.2);
  background:radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a0610 100%);
  display:grid;place-items:center;
}
/* Meta overlay */
.vi-meta-overlay{
  position:absolute;bottom:10px;left:14px;z-index:4;
  background:rgba(0,0,0,.55);backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.1);border-radius:8px;
  padding:5px 12px;
}
.vi-meta-res{font-size:11.5px;font-weight:700;color:rgba(255,255,255,.75)}
.vi-meta-ts{font-size:10.5px;color:rgba(255,255,255,.45)}
/* Controles zoom */
.vi-zoom-ctrl{
  position:absolute;left:14px;top:50%;translate:0 -50%;z-index:4;
  display:flex;flex-direction:column;gap:4px;
}
.vi-zoom-btn{
  width:30px;height:30px;border-radius:8px;
  background:rgba(0,0,0,.55);backdrop-filter:blur(6px);
  border:1px solid rgba(255,255,255,.14);
  display:grid;place-items:center;
  color:rgba(255,255,255,.8);font-size:15px;font-weight:700;
  cursor:pointer;transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-zoom-btn:hover{background:rgba(46,123,246,.5)}}
.vi-zoom-pct{
  width:30px;text-align:center;font-size:10px;font-weight:700;
  color:rgba(255,255,255,.6);padding:2px 0;
}

/* Barra de herramientas */
.vi-toolbar{
  display:flex;align-items:center;gap:6px;flex-wrap:wrap;
  padding:10px 0;border-bottom:1px solid var(--stroke);margin-bottom:14px;
}
.vi-tool-btn{
  display:flex;align-items:center;gap:6px;
  height:34px;padding:0 13px;border-radius:var(--r-md);
  font:inherit;font-size:12.5px;font-weight:600;color:var(--txt-soft);
  background:transparent;border:1px solid transparent;
  transition:background-color 150ms ease,border-color 150ms ease,color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-tool-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.35);color:var(--blue)}}
.vi-tool-btn.on{background:rgba(46,123,246,.15);border-color:rgba(46,123,246,.5);color:var(--blue)}

/* Anotaciones */
.vi-annotation-toolbar{
  display:none;align-items:stretch;gap:14px;flex-direction:column;
  padding:16px;margin:0;
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);
}
.vi-annotation-toolbar.open{display:flex}
.vi-side-tool-title{
  font-family:'Sora',sans-serif;font-size:13px;font-weight:800;
  padding-bottom:10px;border-bottom:1px solid var(--stroke);
}
.vi-annotation-control{display:grid;grid-template-columns:72px 1fr auto;align-items:center;gap:9px}
.vi-annotation-control.wide{min-width:0}
.vi-annotation-control label{font-size:13px;font-weight:700;color:var(--txt)}
.vi-annotation-control input[type="color"]{
  width:48px;height:40px;padding:3px;border-radius:8px;
  background:var(--card);border:1px solid var(--stroke);cursor:pointer;
}
.vi-annotation-control input[type="range"]{
  width:100%;height:4px;accent-color:var(--blue);cursor:pointer;
}
#viAnnoSizeVal{min-width:24px;font-size:13px;font-weight:800;color:var(--txt)}
.vi-annotation-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;width:100%}
.vi-annotation-btn{
  display:flex;align-items:center;justify-content:center;
  height:38px;padding:0 16px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  font:inherit;font-size:13px;font-weight:700;color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease,color 150ms ease,transform 160ms var(--ease-out);
}
.vi-annotation-btn:active{transform:scale(.97)}
.vi-annotation-btn.active{background:rgba(118,75,162,.25);border-color:rgba(118,75,162,.55);color:#fff}
.vi-annotation-btn.danger{background:rgba(255,90,110,.14);border-color:rgba(255,90,110,.35);color:var(--red)}
@media(hover:hover)and(pointer:fine){
  .vi-annotation-btn:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}
  .vi-annotation-btn.danger:hover{background:rgba(255,90,110,.22);border-color:rgba(255,90,110,.5);color:var(--red)}
}

/* Mediciones */
.vi-measure-toolbar{
  display:none;align-items:center;justify-content:center;gap:18px;flex-wrap:wrap;
  padding:16px;margin:0 0 14px;
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);
}
.vi-measure-toolbar.open{display:flex}
.vi-measure-control{display:flex;align-items:center;gap:9px}
.vi-measure-control.wide{min-width:220px}
.vi-measure-control label{font-size:13px;font-weight:700;color:var(--txt)}
.vi-measure-control input[type="color"]{
  width:48px;height:40px;padding:3px;border-radius:8px;
  background:var(--card);border:1px solid var(--stroke);cursor:pointer;
}
.vi-measure-control input[type="range"]{
  width:140px;height:4px;accent-color:var(--blue);cursor:pointer;
}
#viMeasureWidthVal{min-width:24px;font-size:13px;font-weight:800;color:var(--txt)}
.vi-measure-actions{display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap;width:100%}
.vi-measure-btn{
  display:flex;align-items:center;justify-content:center;
  height:38px;padding:0 16px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  font:inherit;font-size:13px;font-weight:700;color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease,color 150ms ease,transform 160ms var(--ease-out);
}
.vi-measure-btn:active{transform:scale(.97)}
.vi-measure-btn.active{background:rgba(46,123,246,.18);border-color:rgba(46,123,246,.55);color:var(--blue)}
.vi-measure-btn.danger{background:rgba(255,90,110,.14);border-color:rgba(255,90,110,.35);color:var(--red)}
.vi-measure-btn.icon-only{width:auto;padding:0;overflow:hidden}
.vi-measure-icon{
  width:64px;height:64px;display:block;object-fit:contain;
  transform:scale(2.2);
  transform-origin:center;
}
@media(hover:hover)and(pointer:fine){
  .vi-measure-btn:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}
  .vi-measure-btn.danger:hover{background:rgba(255,90,110,.22);border-color:rgba(255,90,110,.5);color:var(--red)}
}

/* Tira de imágenes */
.vi-strip-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
.vi-strip-title{font-size:13px;font-weight:600}
.vi-strip-nav{display:flex;align-items:center;gap:4px}
.vi-strip-arrow{
  width:28px;height:28px;border-radius:7px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);
  transition:background-color 150ms ease,color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vi-strip-arrow:hover{background:rgba(110,160,255,.1);color:var(--txt)}}
.vi-strip{display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;scrollbar-width:thin;scrollbar-color:var(--stroke) transparent}
.vi-strip-item{
  flex:none;width:100px;cursor:pointer;
  border-radius:7px;overflow:hidden;border:2px solid transparent;
  transition:border-color 150ms ease,transform 150ms ease;position:relative;
}
.vi-strip-item:active{transform:scale(.96)}
.vi-strip-item.sel{border-color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vi-strip-item:not(.sel):hover{border-color:rgba(46,123,246,.45)}}
.vi-strip-thumb{
  width:100%;aspect-ratio:4/3;display:grid;place-items:center;position:relative;
}
.vi-strip-num{
  position:absolute;top:4px;left:5px;
  width:18px;height:18px;border-radius:5px;
  background:rgba(0,0,0,.6);display:grid;place-items:center;
  font-size:9px;font-weight:700;color:#fff;
}
.vi-strip-del{
  position:absolute;top:4px;right:4px;
  width:18px;height:18px;border-radius:50%;
  background:rgba(0,0,0,.55);display:grid;place-items:center;
  color:rgba(255,255,255,.6);cursor:pointer;font-size:11px;font-weight:700;
  transition:background-color 150ms ease,color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-strip-del:hover{background:rgba(255,90,110,.6);color:#fff}}
.vi-strip-item.sel .vi-strip-del{background:rgba(46,123,246,.6);color:#fff}
.vi-strip-ts{font-size:9.5px;color:var(--txt-soft);text-align:center;padding:3px 0 2px}

/* Panel de Filtros */
.vi-filters-panel{
  margin:0;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-lg);padding:16px;
  display:none;
}
.vi-filters-panel.open{display:block}
.vi-filters-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700;margin-bottom:14px}

/* Canvas de filtros */
.vi-canvas-wrap{
  position:relative;width:100%;border-radius:10px;
  overflow:hidden;background:#000;aspect-ratio:4/3;
  display:flex;align-items:center;justify-content:center;
  margin-bottom:14px;
}
.vi-canvas-wrap canvas{
  max-width:100%;max-height:100%;display:block;
}
.vi-canvas-placeholder{
  display:flex;flex-direction:column;align-items:center;gap:8px;
  color:rgba(255,255,255,.3);font-size:12px;text-align:center;padding:20px;
}

/* Botones de filtro de color */
.vi-filter-label{font-size:11.5px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--txt-soft);margin-bottom:8px}
.vi-filter-btns{
  display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;
}
.vi-filter-btn{
  display:flex;align-items:center;gap:6px;
  height:34px;padding:0 14px;border-radius:var(--r-md);
  font:inherit;font-size:12.5px;font-weight:600;color:var(--txt-soft);
  background:var(--panel-2);border:1px solid var(--stroke);
  transition:all 150ms ease;cursor:pointer;
}
.vi-filter-btn.active{background:rgba(46,123,246,.15);border-color:rgba(46,123,246,.5);color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vi-filter-btn:hover{background:rgba(46,123,246,.08);border-color:rgba(46,123,246,.3);color:var(--blue)}}

/* Sliders de ajuste */
.vi-slider-group{margin-bottom:12px}
.vi-slider-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:5px}
.vi-slider-name{font-size:12px;font-weight:600;color:var(--txt-soft)}
.vi-slider-val{font-size:12px;font-weight:700;color:var(--blue);min-width:36px;text-align:right}
.vi-slider{
  width:100%;height:4px;border-radius:2px;
  accent-color:var(--blue);cursor:pointer;
  appearance:auto;
}

/* Botones acción filtros */
.vi-filter-actions{display:flex;gap:8px;margin-top:14px;padding-top:14px;border-top:1px solid var(--stroke)}
.vi-filter-apply{
  flex:1;display:flex;align-items:center;justify-content:center;gap:7px;
  height:36px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:700;
  background:var(--blue);border:none;color:#fff;
  transition:opacity 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-filter-apply:hover{opacity:.88}}
.vi-filter-reset{
  display:flex;align-items:center;justify-content:center;gap:7px;
  height:36px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:12.5px;font-weight:600;color:var(--txt-soft);
  background:transparent;border:1px solid var(--stroke);
  transition:all 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-filter-reset:hover{background:rgba(255,90,110,.1);border-color:rgba(255,90,110,.4);color:var(--red)}}

/* Sidebar */
.vi-side{display:flex;flex-direction:column;gap:14px}
.vi-card{background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:16px}
.vi-card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.vi-card-title{font-family:'Sora',sans-serif;font-size:13px;font-weight:700}
.vi-edit-ic{color:var(--txt-soft);cursor:pointer;transition:color 150ms ease}
@media(hover:hover)and(pointer:fine){.vi-edit-ic:hover{color:var(--blue)}}

/* Info tabla */
.vi-info-table{display:grid;grid-template-columns:auto 1fr;gap:4px 14px}
.vi-it-lbl{font-size:11.5px;color:var(--txt-soft);padding:1px 0}
.vi-it-val{font-size:12.5px;font-weight:600;color:var(--txt);padding:1px 0}

/* IA Hallazgos */
.vi-ia-badge{
  display:inline-flex;align-items:center;gap:5px;
  padding:2px 9px;border-radius:99px;font-size:11px;font-weight:700;
  background:rgba(56,199,244,.12);color:var(--cyan);border:1px solid rgba(56,199,244,.3);
  margin-left:8px;
}
.vi-ia-row{display:flex;align-items:center;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--stroke)}
.vi-ia-row:last-of-type{border-bottom:none}
.vi-ia-dot{width:8px;height:8px;border-radius:50%;background:var(--green);flex:none}
.vi-ia-name{font-size:12.5px;font-weight:600;flex:1;margin-left:7px}
.vi-ia-conf{font-size:11.5px;color:var(--txt-soft)}
.vi-ia-analyze{
  width:100%;height:36px;margin-top:10px;border-radius:var(--r-md);
  border:1px solid rgba(56,199,244,.35);background:rgba(56,199,244,.08);
  font:inherit;font-size:12.5px;font-weight:600;color:var(--cyan);
  transition:background-color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vi-ia-analyze:hover{background:rgba(56,199,244,.18)}}

/* Tags */
.vi-tags{display:flex;flex-wrap:wrap;gap:7px}
.vi-tag{
  padding:4px 12px;border-radius:99px;font-size:12px;font-weight:600;
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vi-tag:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}}

@media(max-width:960px){.vi-wrap{grid-template-columns:1fr}}

/* ===== MODAL DESCARGA ===== */
.vi-dl-overlay{
  position:fixed;inset:0;z-index:900;
  background:rgba(0,0,0,.6);backdrop-filter:blur(4px);
  display:flex;align-items:center;justify-content:center;
  opacity:0;pointer-events:none;
  transition:opacity 200ms ease;
}
.vi-dl-overlay.open{opacity:1;pointer-events:auto}
.vi-dl-modal{
  background:var(--panel);border:1px solid var(--stroke);
  border-radius:18px;width:760px;max-width:95vw;
  box-shadow:0 24px 64px rgba(0,0,0,.5);
  transform:scale(.94);transition:transform 200ms var(--ease-out);
  overflow:hidden;
}
.vi-dl-overlay.open .vi-dl-modal{transform:scale(1)}
.vi-dl-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:18px 22px 0;
}
.vi-dl-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700;display:flex;align-items:center;gap:10px}
.vi-dl-title svg{color:var(--blue)}
.vi-dl-sub{font-size:12.5px;color:var(--txt-soft);margin-top:2px}
.vi-dl-close{
  width:32px;height:32px;border-radius:8px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);
  transition:background-color 150ms ease,color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vi-dl-close:hover{background:rgba(255,90,110,.12);color:var(--red)}}
.vi-dl-body{
  display:grid;grid-template-columns:1fr 1fr;gap:0;
  padding:18px 22px 20px;
}
.vi-dl-preview{
  padding-right:18px;border-right:1px solid var(--stroke);
}
.vi-dl-preview-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:10px}
.vi-dl-thumb{
  width:100%;aspect-ratio:4/3;border-radius:10px;overflow:hidden;
  position:relative;
  background:radial-gradient(ellipse at 50% 45%,#6a1a10 0%,#3a0808 35%,#0a0410 70%,#06081c 100%);
  display:grid;place-items:center;margin-bottom:10px;
}
.vi-dl-thumb-badge{
  position:absolute;top:8px;right:8px;
  background:rgba(0,0,0,.6);backdrop-filter:blur(6px);
  border:1px solid rgba(255,255,255,.12);border-radius:6px;
  padding:3px 8px;font-size:10.5px;font-weight:700;color:rgba(255,255,255,.8);
}
.vi-dl-watermark{
  position:absolute;bottom:10px;left:50%;translate:-50% 0;
  display:none;align-items:center;gap:6px;
  background:rgba(0,0,0,.5);backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.15);border-radius:8px;
  padding:5px 12px;
  pointer-events:none;
}
.vi-dl-watermark.show{display:flex}
.vi-dl-watermark-logo{
  font-family:'Sora',sans-serif;font-size:11px;font-weight:900;
  letter-spacing:.08em;color:#fff;
}
.vi-dl-watermark-logo span{color:var(--blue)}
.vi-dl-watermark-dot{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.35)}
.vi-dl-watermark-sub{font-size:9.5px;color:rgba(255,255,255,.5);font-weight:600;letter-spacing:.04em}
.vi-dl-thumb-meta{
  font-size:11.5px;color:var(--txt-soft);
  display:flex;align-items:center;gap:8px;
}
.vi-dl-thumb-meta span{color:var(--txt);font-weight:600}
.vi-dl-opts{padding-left:18px}
.vi-dl-opts-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:10px}
/* Formatos */
.vi-fmt-list{display:flex;flex-direction:column;gap:7px;margin-bottom:16px}
.vi-fmt-item{
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  cursor:pointer;transition:border-color 150ms ease,background-color 150ms ease;
}
.vi-fmt-item.sel{border-color:var(--blue);background:rgba(46,123,246,.1)}
@media(hover:hover)and(pointer:fine){.vi-fmt-item:not(.sel):hover{border-color:rgba(46,123,246,.4);background:rgba(46,123,246,.06)}}
.vi-fmt-left{display:flex;align-items:center;gap:10px}
.vi-fmt-ic{width:30px;height:30px;border-radius:7px;background:rgba(255,255,255,.07);display:grid;place-items:center;color:var(--txt-soft);flex:none}
.vi-fmt-name{font-size:13px;font-weight:700}
.vi-fmt-desc{font-size:11px;color:var(--txt-soft)}
.vi-fmt-badge{
  font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;
  background:rgba(61,220,151,.12);color:var(--green);border:1px solid rgba(61,220,151,.3);
}
.vi-fmt-tag{
  font-size:10px;font-weight:600;color:var(--txt-soft);
  padding:2px 8px;border-radius:99px;
  border:1px solid var(--stroke);background:var(--panel-2);
}
.vi-fmt-check{width:18px;height:18px;border-radius:50%;border:2px solid var(--stroke);display:grid;place-items:center;flex:none;transition:background-color 150ms ease,border-color 150ms ease}
.vi-fmt-item.sel .vi-fmt-check{background:var(--blue);border-color:var(--blue)}
/* Calidad */
.vi-qual-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:7px}
.vi-qual-select{
  width:100%;height:38px;background:var(--card);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 12px;font:inherit;font-size:13px;
  color:var(--txt);outline:none;cursor:pointer;margin-bottom:14px;
  transition:border-color 150ms ease;
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2.5' stroke-linecap='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 12px center;
  padding-right:32px;
}
.vi-qual-select:focus{border-color:var(--blue)}
/* Incluir */
.vi-inc-lbl{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:8px}
.vi-inc-row{display:flex;align-items:center;gap:9px;margin-bottom:7px;cursor:pointer}
.vi-inc-row:last-child{margin-bottom:0}
.vi-inc-cb{
  width:18px;height:18px;border-radius:5px;flex:none;
  border:2px solid var(--stroke);display:grid;place-items:center;
  transition:background-color 150ms ease,border-color 150ms ease;
}
.vi-inc-row.checked .vi-inc-cb{background:var(--blue);border-color:var(--blue)}
.vi-inc-label{font-size:13px;color:var(--txt)}
/* Footer */
.vi-dl-footer{
  padding:14px 22px 18px;
  border-top:1px solid var(--stroke);
  display:flex;align-items:center;justify-content:space-between;
  gap:10px;
}
.vi-dl-footer-note{font-size:12px;color:var(--txt-soft);display:flex;align-items:center;gap:6px}
.vi-dl-footer-btns{display:flex;align-items:center;gap:8px}
.vi-dl-cancel{
  height:38px;padding:0 18px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;
  font:inherit;font-size:13px;font-weight:600;color:var(--txt-soft);
  transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vi-dl-cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vi-dl-confirm{
  height:38px;padding:0 22px;border-radius:var(--r-md);
  border:none;background:var(--blue);
  font:inherit;font-size:13px;font-weight:700;color:#fff;
  display:flex;align-items:center;gap:8px;
  transition:opacity 150ms ease,transform 160ms var(--ease-out);
}
.vi-dl-confirm:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.vi-dl-confirm:hover{opacity:.88}}
</style>
@endpush
