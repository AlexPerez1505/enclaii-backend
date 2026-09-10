<?php $__env->startPush('styles'); ?>
<style>
/* ===== VER VIDEO ===== */
.vv-wrap{display:grid;grid-template-columns:1fr 280px;gap:18px;align-items:start}

/* Acción superior */
.vv-topbar{display:flex;align-items:center;justify-content:flex-end;gap:10px;margin-bottom:14px}
.vv-btn{
  display:flex;align-items:center;gap:7px;
  height:38px;padding:0 16px;border-radius:var(--r-md);
  font:inherit;font-size:13px;font-weight:600;
  transition:background-color 150ms ease,transform 160ms var(--ease-out);
}
.vv-btn:active{transform:scale(.97)}
.vv-btn.cancel{background:transparent;border:1px solid var(--stroke);color:var(--txt-soft)}
@media(hover:hover)and(pointer:fine){.vv-btn.cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vv-btn.edit{background:rgba(46,123,246,.14);border:1px solid rgba(46,123,246,.35);color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vv-btn.edit:hover{background:rgba(46,123,246,.25)}}
.vv-btn.dl{background:rgba(61,220,151,.12);border:1px solid rgba(61,220,151,.35);color:var(--green)}
@media(hover:hover)and(pointer:fine){.vv-btn.dl:hover{background:rgba(61,220,151,.22)}}

/* Player */
.vv-player-box{
  background:#000;border-radius:14px;overflow:hidden;
  position:relative;aspect-ratio:16/9;
  display:flex;align-items:center;justify-content:center;
}
.vv-player-bg{
  position:absolute;inset:0;
  background:radial-gradient(ellipse at 50% 50%,#5a1a10 0%,#2a0808 40%,#060810 100%);
}
.vv-video-el{
  position:absolute;inset:0;z-index:1;
  width:100%;height:100%;object-fit:contain;background:#000;
}
.vv-player-icon{
  position:absolute;inset:0;z-index:2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;
  color:rgba(255,255,255,.5);font-size:13px;
}
.vv-play-big{
  width:64px;height:64px;border-radius:50%;
  background:rgba(255,255,255,.18);backdrop-filter:blur(8px);
  display:grid;place-items:center;cursor:pointer;
  transition:background-color 150ms ease,transform 150ms ease;
}
.vv-play-big:hover{background:rgba(46,123,246,.6);transform:scale(1.08)}
.vv-play-big.playing svg.play-icon{display:none}
.vv-play-big.playing svg.pause-icon{display:block}
.vv-play-big svg.pause-icon{display:none}

/* Controles */
.vv-controls{
  position:absolute;bottom:0;left:0;right:0;
  z-index:3;padding:32px 16px 14px;
  background:linear-gradient(0deg,rgba(0,0,0,.82) 0%,transparent 100%);
}
.vv-prog-wrap{position:relative;height:4px;background:rgba(255,255,255,.2);border-radius:4px;cursor:pointer;margin-bottom:10px}
.vv-prog-fill{height:100%;background:var(--blue);border-radius:4px;width:15%}
.vv-prog-thumb{
  position:absolute;top:50%;translate:0 -50%;
  width:12px;height:12px;border-radius:50%;background:#fff;
  left:15%;margin-left:-6px;cursor:grab;
}
.vv-ctrl-row{display:flex;align-items:center;gap:8px}
.vv-ctrl-btn{
  width:32px;height:32px;border-radius:8px;display:grid;place-items:center;
  color:rgba(255,255,255,.8);flex:none;
  transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vv-ctrl-btn:hover{background:rgba(255,255,255,.12)}}
.vv-time{font-size:12px;color:rgba(255,255,255,.65);flex:none;margin:0 4px}
.vv-vol-wrap{display:flex;align-items:center;gap:6px;margin-left:auto}
.vv-vol-bar{width:72px;height:4px;background:rgba(255,255,255,.2);border-radius:4px;cursor:pointer}
.vv-vol-fill{height:100%;background:rgba(255,255,255,.7);border-radius:4px;width:70%}
.vv-speed{
  font-size:12px;font-weight:700;color:rgba(255,255,255,.8);
  padding:3px 8px;border-radius:6px;border:1px solid rgba(255,255,255,.2);
  cursor:pointer;transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vv-speed:hover{background:rgba(255,255,255,.1)}}
.vv-fullscreen{margin-left:6px}

/* Acciones bajo el player */
.vv-actions{
  display:flex;align-items:center;gap:8px;flex-wrap:wrap;
  padding:12px 0;border-bottom:1px solid var(--stroke);margin-bottom:14px;
}
.vv-act-btn{
  display:flex;align-items:center;gap:6px;
  height:36px;padding:0 14px;border-radius:var(--r-md);
  font:inherit;font-size:12.5px;font-weight:600;
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
  text-decoration:none;
  cursor:pointer;
  transition:background-color 150ms ease,border-color 150ms ease,transform 160ms var(--ease-out);
  white-space:nowrap;
}
.vv-act-btn:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.vv-act-btn:hover{background:rgba(46,123,246,.1);border-color:rgba(46,123,246,.4);color:var(--blue)}}
.vv-act-btn.ia{color:var(--cyan);border-color:rgba(56,199,244,.35);background:rgba(56,199,244,.08)}
@media(hover:hover)and(pointer:fine){.vv-act-btn.ia:hover{background:rgba(56,199,244,.18)}}
.vv-act-btn.wa{color:var(--green);border-color:rgba(61,220,151,.35);background:rgba(61,220,151,.08)}
@media(hover:hover)and(pointer:fine){.vv-act-btn.wa:hover{background:rgba(61,220,151,.18)}}
.vv-act-btn.email{color:var(--blue);border-color:rgba(46,123,246,.35);background:rgba(46,123,246,.08)}
@media(hover:hover)and(pointer:fine){.vv-act-btn.email:hover{background:rgba(46,123,246,.16)}}

/* Miniaturas */
.vv-caps-title{font-size:13px;font-weight:600;margin-bottom:10px}
.vv-caps-strip{display:flex;gap:10px;overflow-x:auto;padding-bottom:6px;scrollbar-width:thin;scrollbar-color:var(--stroke) transparent}
.vv-cap-item{
  flex:none;width:100px;cursor:pointer;
  border-radius:8px;overflow:hidden;border:2px solid transparent;
  transition:border-color 150ms ease,transform 150ms ease;
}
.vv-cap-item:active{transform:scale(.96)}
.vv-cap-item.sel{border-color:var(--blue)}
@media(hover:hover)and(pointer:fine){.vv-cap-item:hover{border-color:rgba(46,123,246,.5)}}
.vv-cap-thumb{
  width:100%;aspect-ratio:4/3;
  display:grid;place-items:center;position:relative;
  background:radial-gradient(ellipse at 50% 50%,#3a1208 0%,#0a060e 100%);
}
.vv-cap-thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.vv-cap-num{
  position:absolute;top:4px;left:5px;
  width:18px;height:18px;border-radius:6px;
  background:rgba(0,0,0,.6);display:grid;place-items:center;
  font-size:9px;font-weight:700;color:#fff;
}
.vv-cap-check{
  position:absolute;top:4px;right:4px;
  width:18px;height:18px;border-radius:50%;
  background:var(--blue);display:none;place-items:center;
}
.vv-cap-item.sel .vv-cap-check{display:grid}
.vv-cap-ts{font-size:10px;color:var(--txt-soft);text-align:center;padding:4px 0 2px}
.vv-empty-caps{padding:16px;color:var(--txt-soft);font-size:12.5px;border:1px dashed var(--stroke);border-radius:var(--r-md)}

/* Sidebar info */
.vv-side{display:flex;flex-direction:column;gap:14px}
.vv-info-card{
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:var(--r-lg);padding:16px;
}
.vv-info-row{margin-bottom:12px}
.vv-info-row:last-child{margin-bottom:0}
.vv-info-lbl{font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:2px}
.vv-info-val{font-size:13.5px;font-weight:600;color:var(--txt)}
.vv-status{
  display:inline-flex;align-items:center;gap:6px;
  padding:4px 12px;border-radius:99px;
  font-size:12.5px;font-weight:700;
  background:rgba(61,220,151,.14);color:var(--green);
  border:1px solid rgba(61,220,151,.35);
}
.vv-diag-row{display:flex;align-items:center;justify-content:space-between}
.vv-diag-txt{font-size:13.5px;font-weight:600}
.vv-diag-av{
  width:36px;height:36px;border-radius:50%;flex:none;
  background:var(--cyan);display:grid;place-items:center;
  font-family:'Sora',sans-serif;font-size:14px;font-weight:700;color:#06081c;
}
.vv-obs-txt{font-size:13px;color:var(--txt-soft);line-height:1.6}
.vv-edit-ic{
  color:var(--txt-soft);cursor:pointer;transition:color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vv-edit-ic:hover{color:var(--blue)}}
.vv-section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.vv-section-lbl{font-family:'Sora',sans-serif;font-size:13px;font-weight:700}
.vv-tags{display:flex;flex-wrap:wrap;gap:7px}
.vv-tag{
  padding:4px 12px;border-radius:99px;
  font-size:12px;font-weight:600;
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
  transition:background-color 150ms ease,border-color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vv-tag:hover{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.4);color:var(--blue)}}

@media(max-width:960px){.vv-wrap{grid-template-columns:1fr}}

/* ===== MODAL DESCARGA VIDEO ===== */
.vv-dl-overlay{
  position:fixed;inset:0;z-index:900;
  background:rgba(0,0,0,.6);backdrop-filter:blur(4px);
  display:flex;align-items:center;justify-content:center;
  opacity:0;pointer-events:none;transition:opacity 200ms ease;
}
.vv-dl-overlay.open{opacity:1;pointer-events:auto}
.vv-dl-modal{
  background:var(--panel);border:1px solid var(--stroke);
  border-radius:18px;width:520px;max-width:95vw;
  box-shadow:0 24px 64px rgba(0,0,0,.5);
  transform:scale(.94);transition:transform 200ms var(--ease-out);overflow:hidden;
}
.vv-dl-overlay.open .vv-dl-modal{transform:scale(1)}
.vv-dl-hdr{
  display:flex;align-items:flex-start;justify-content:space-between;
  padding:18px 20px 0;
}
.vv-dl-title{font-family:'Sora',sans-serif;font-size:15px;font-weight:700;display:flex;align-items:center;gap:9px}
.vv-dl-title svg{color:var(--blue)}
.vv-dl-sub{font-size:12px;color:var(--txt-soft);margin-top:2px}
.vv-dl-x{
  width:30px;height:30px;border-radius:8px;border:1px solid var(--stroke);
  display:grid;place-items:center;color:var(--txt-soft);flex:none;
  transition:background-color 150ms ease,color 150ms ease;cursor:pointer;
}
@media(hover:hover)and(pointer:fine){.vv-dl-x:hover{background:rgba(255,90,110,.12);color:var(--red)}}
.vv-dl-body{padding:16px 20px 18px;display:flex;flex-direction:column;gap:14px}
/* Sección */
.vv-dl-sec-lbl{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--txt-soft);margin-bottom:8px}
/* Rango radio */
.vv-rng-list{display:flex;flex-direction:column;gap:6px}
.vv-rng-row{
  display:flex;align-items:center;gap:10px;
  padding:9px 13px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  cursor:pointer;transition:border-color 150ms ease,background-color 150ms ease;
}
.vv-rng-row.sel{border-color:var(--blue);background:rgba(46,123,246,.1)}
@media(hover:hover)and(pointer:fine){.vv-rng-row:not(.sel):hover{border-color:rgba(46,123,246,.35)}}
.vv-rng-radio{
  width:16px;height:16px;border-radius:50%;border:2px solid var(--stroke);flex:none;
  display:grid;place-items:center;
  transition:border-color 150ms ease,background-color 150ms ease;
}
.vv-rng-row.sel .vv-rng-radio{border-color:var(--blue);background:var(--blue)}
.vv-rng-row.sel .vv-rng-radio::after{content:'';width:6px;height:6px;border-radius:50%;background:#fff}
.vv-rng-label{font-size:13px;font-weight:600;flex:1}
.vv-rng-ts{font-size:12px;color:var(--txt-soft);font-weight:600}
/* Rango personalizado inputs */
.vv-rng-custom{
  display:none;align-items:center;gap:8px;
  padding:8px 13px;background:var(--card);
  border:1px solid rgba(46,123,246,.4);border-top:none;
  border-radius:0 0 var(--r-md) var(--r-md);
}
.vv-rng-custom.show{display:flex}
.vv-rng-input{
  height:32px;width:90px;padding:0 10px;
  background:var(--panel-2);border:1px solid var(--stroke);
  border-radius:8px;font:inherit;font-size:12px;color:var(--txt);
  outline:none;transition:border-color 150ms ease;text-align:center;
}
.vv-rng-input:focus{border-color:var(--blue)}
.vv-rng-a{font-size:12px;color:var(--txt-soft)}
.vv-rng-dur{font-size:11.5px;color:var(--txt-soft);margin-left:auto}
.vv-rng-dur span{color:var(--blue);font-weight:700}
/* Calidad */
.vv-dl-qual{
  width:100%;height:38px;background:var(--card);border:1px solid var(--stroke);
  border-radius:var(--r-md);padding:0 12px;font:inherit;font-size:13px;
  color:var(--txt);outline:none;cursor:pointer;
  transition:border-color 150ms ease;
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238FA3CF' stroke-width='2.5' stroke-linecap='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 12px center;padding-right:32px;
  margin-bottom:4px;
}
.vv-dl-qual:focus{border-color:var(--blue)}
.vv-dl-qual-res{font-size:11px;color:var(--txt-soft)}
/* Formatos */
.vv-fmt-row{display:flex;gap:8px}
.vv-fmt-card{
  flex:1;padding:10px 12px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:var(--card);
  cursor:pointer;text-align:center;
  transition:border-color 150ms ease,background-color 150ms ease;
}
.vv-fmt-card.sel{border-color:var(--blue);background:rgba(46,123,246,.1)}
@media(hover:hover)and(pointer:fine){.vv-fmt-card:not(.sel):hover{border-color:rgba(46,123,246,.35)}}
.vv-fmt-ext{font-size:13px;font-weight:700}
.vv-fmt-sub{font-size:10.5px;color:var(--txt-soft);margin-top:1px}
/* Incluir */
.vv-inc-row{
  display:flex;align-items:center;gap:9px;
  margin-bottom:7px;cursor:pointer;
}
.vv-inc-row:last-child{margin-bottom:0}
.vv-inc-cb{
  width:17px;height:17px;border-radius:5px;flex:none;
  border:2px solid var(--stroke);display:grid;place-items:center;
  transition:background-color 150ms ease,border-color 150ms ease;
}
.vv-inc-row.checked .vv-inc-cb{background:var(--blue);border-color:var(--blue)}
.vv-inc-lbl{font-size:13px;color:var(--txt)}
/* Footer */
.vv-dl-footer{
  padding:12px 20px 16px;border-top:1px solid var(--stroke);
  display:flex;align-items:center;justify-content:space-between;gap:8px;
}
.vv-dl-note{font-size:11.5px;color:var(--txt-soft);display:flex;align-items:center;gap:5px}
.vv-dl-footer-btns{display:flex;gap:8px}
.vv-dl-cancel{
  height:37px;padding:0 16px;border-radius:var(--r-md);
  border:1px solid var(--stroke);background:transparent;
  font:inherit;font-size:13px;font-weight:600;color:var(--txt-soft);
  transition:background-color 150ms ease;
}
@media(hover:hover)and(pointer:fine){.vv-dl-cancel:hover{background:rgba(110,160,255,.08);color:var(--txt)}}
.vv-dl-confirm{
  height:37px;padding:0 20px;border-radius:var(--r-md);
  border:none;background:var(--blue);
  font:inherit;font-size:13px;font-weight:700;color:#fff;
  display:flex;align-items:center;gap:7px;
  transition:opacity 150ms ease,transform 160ms var(--ease-out);
}
.vv-dl-confirm:active{transform:scale(.97)}
@media(hover:hover)and(pointer:fine){.vv-dl-confirm:hover{opacity:.88}}
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/galeria/vervideo/_styles.blade.php ENDPATH**/ ?>