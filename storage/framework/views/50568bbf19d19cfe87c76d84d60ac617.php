<?php $__env->startSection('title', 'Redactar reporte'); ?>
<?php $__env->startSection('active', 'ia-reportes'); ?>
<?php $__env->startSection('header-title', 'Redactar reporte'); ?>
<?php $__env->startSection('header-sub'); ?>
  Escribe tu propio reporte con el apoyo del asistente IA
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ============ EDITOR DE INFORME (manual) ============ */
.ed-actions{display:flex;justify-content:flex-end;gap:10px;margin-bottom:14px;flex-wrap:wrap}
.ed-btn{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:var(--r-md);font-weight:600;font-size:13.5px;border:1px solid var(--stroke-strong);background:var(--panel-2);transition:background-color .15s}
.ed-btn svg{width:16px;height:16px}
@media (hover:hover){.ed-btn:hover{background:rgba(110,160,255,.1)}}
.ed-btn.primary{border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;padding-right:10px}
.ed-btn.primary .div{width:1px;height:18px;background:rgba(255,255,255,.35);margin:0 2px}

.ed-meta{display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;margin-bottom:14px}
.ed-meta .f{display:flex;flex-direction:column;gap:6px}
.ed-meta .f.grow{flex:1;min-width:220px}
.ed-meta label{font-size:11.5px;color:var(--txt-soft)}
.ed-ctrl{display:flex;align-items:center;gap:8px;padding:9px 12px;border-radius:10px;border:1px solid var(--stroke);background:var(--panel);font-size:13.5px;color:var(--txt);min-height:40px}
.ed-ctrl svg{width:15px;height:15px;color:var(--txt-soft);flex:none}
select.ed-ctrl{appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2390a0c0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'/></svg>");background-repeat:no-repeat;background-position:right 12px center;padding-right:34px}
.ed-status{padding:9px 14px;border-radius:10px;font-size:12.5px;font-weight:700;min-height:40px;display:flex;align-items:center}
.ed-status.borrador{color:var(--orange);background:rgba(245,158,45,.14);border:1px solid rgba(245,158,45,.3)}
.ed-status.guardado{color:#16a34a;background:rgba(22,163,74,.14);border:1px solid rgba(22,163,74,.3)}

.ed-toolbar{display:flex;align-items:center;gap:4px;flex-wrap:wrap;padding:8px 12px;border:1px solid var(--stroke);border-radius:var(--r-md);background:var(--panel-2);margin-bottom:16px}
.ed-toolbar .sel{display:flex;align-items:center;gap:6px;padding:6px 10px;border-radius:8px;border:1px solid var(--stroke);background:var(--panel);font-size:12.5px;color:var(--txt-soft);margin-right:4px}
.ed-toolbar .sel svg{width:13px;height:13px}
.ed-toolbar .sep{width:1px;height:22px;background:var(--stroke);margin:0 4px}
.ed-tb{width:30px;height:30px;display:grid;place-items:center;border-radius:7px;color:var(--txt-soft);font-size:13px;font-weight:700;transition:color .15s,background-color .15s}
@media (hover:hover){.ed-tb:hover{color:var(--cyan);background:rgba(56,199,244,.1)}}
.ed-tb svg{width:16px;height:16px}
.ed-tb.active{color:var(--cyan);background:rgba(56,199,244,.18);box-shadow:inset 0 0 0 1px rgba(56,199,244,.45)}
.ed-color-wrap{position:relative;display:inline-grid;place-items:center;width:30px;height:30px}
.ed-color-btn{position:relative}
.ed-color-btn .ed-color-letter{font-size:14px;line-height:1}
.ed-color-btn .ed-color-swatch{position:absolute;left:7px;right:7px;bottom:5px;height:3px;border-radius:999px;background:#111827;box-shadow:0 0 0 1px rgba(255,255,255,.35)}
.ed-color-input{position:absolute;width:1px;height:1px;opacity:0;pointer-events:none}
.ed-tb[type="number"]::-webkit-outer-spin-button,
.ed-tb[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none !important;
  margin: 0 !important;
}
.ed-tb[type="number"] {
  -moz-appearance: textfield !important;
}

.ed-body{display:grid;grid-template-columns:minmax(0,1fr) minmax(280px,320px);gap:28px;align-items:start}
@media (max-width:1100px){.ed-body{grid-template-columns:1fr}}
.ed-main{display:flex;flex-direction:column;gap:16px;min-width:0}

/* Documento editable */
.ed-doc{padding:30px 34px;line-height:1.55;flex:1;min-width:0;min-height:calc(100vh - 300px);display:flex;flex-direction:column;overflow:hidden}
.ed-doc #docSections{flex:1}
.ed-doc .doc-h{text-align:center;margin-bottom:22px}
.ed-doc .doc-h h2{font-family:'Sora',sans-serif;font-size:21px;font-weight:800;letter-spacing:.01em}
.ed-doc .doc-h p{font-size:12.5px;color:var(--txt-soft);letter-spacing:.06em;margin-top:4px}
.ed-doc .doc-meta{display:grid;grid-template-columns:150px 1fr;gap:5px 16px;font-size:13px;margin-bottom:20px}
.ed-doc .doc-meta .k{color:var(--txt-soft)}
.ed-doc h4{font-size:13px;font-weight:700;letter-spacing:.04em;margin:18px 0 6px;color:var(--cyan);position:relative}
.ed-doc h4 .sec-hide{position:absolute;right:0;top:50%;transform:translateY(-50%);width:20px;height:20px;border:none;background:rgba(56,199,244,.1);color:var(--cyan);border-radius:4px;font-size:12px;cursor:pointer;opacity:0;transition:opacity .15s}
.ed-doc h4:hover .sec-hide{opacity:1}
@media(hover:hover){.ed-doc h4 .sec-hide:hover{background:rgba(56,199,244,.2)}}
.ed-doc h4 .sec-delete{position:absolute;right:28px;top:50%;transform:translateY(-50%);width:20px;height:20px;border:none;background:rgba(239,68,68,.1);color:#ef4444;border-radius:4px;font-size:12px;cursor:pointer;opacity:0;transition:opacity .15s}
.ed-doc h4:hover .sec-delete{opacity:1}
@media(hover:hover){.ed-doc h4 .sec-delete:hover{background:rgba(239,68,68,.2)}}
.ed-doc .sec-add{margin-top:20px;padding:8px 16px;border:1px dashed var(--stroke);background:var(--panel-2);color:var(--txt-soft);border-radius:8px;font-size:12.5px;cursor:pointer;transition:all .15s}
@media(hover:hover){.ed-doc .sec-add:hover{border-color:var(--cyan);color:var(--cyan);background:rgba(56,199,244,.05)}}
@media print{.ed-doc .sec-add{display:none}}
.ed-doc p,.ed-doc ul{font-size:13px}
.ed-doc ul{list-style:disc;padding-left:20px;display:flex;flex-direction:column;gap:4px;margin-top:4px}

/* Campos editables */
[contenteditable]{outline:none}
[contenteditable]:focus{background:rgba(56,199,244,.06);border-radius:5px;box-shadow:0 0 0 2px rgba(56,199,244,.25)}
.ed-doc [contenteditable]{padding:1px 4px;transition:background-color .15s}
[contenteditable][data-ph]:empty:before{content:attr(data-ph);color:var(--off);pointer-events:none}
.ed-doc li[contenteditable]{margin-left:0}

/* Panel lateral */
.ed-side{display:flex;flex-direction:column;gap:16px;min-width:0}
.ed-panel{padding:15px 16px}
.ed-panel h3{font-size:14px;font-weight:700;margin-bottom:3px}
.ed-panel .ph-sub{font-size:11.5px;color:var(--txt-soft);margin-bottom:12px}
.cap-panel{padding:15px 16px}
.cap-panel.is-collapsed{padding-bottom:15px}
.cap-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px}
.cap-panel.is-collapsed .cap-head{margin-bottom:0}
.cap-head h3{font-size:14px;font-weight:700;margin:0}
.cap-actions{display:flex;align-items:center;gap:8px}
.cap-count{font-size:11.5px;color:var(--txt-soft)}
.cap-reset,.cap-toggle{width:28px;height:28px;display:grid;place-items:center;border-radius:8px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);transition:color .15s,background-color .15s,border-color .15s}
.cap-reset svg,.cap-toggle svg{width:14px;height:14px}
.cap-toggle svg{transition:transform .18s ease}
.cap-panel.is-collapsed .cap-toggle svg{transform:rotate(180deg)}
@media(hover:hover){.cap-reset:hover,.cap-toggle:hover{color:var(--cyan);border-color:rgba(56,199,244,.45);background:rgba(56,199,244,.1)}}
.cap-body{overflow:hidden;transition:grid-template-rows .2s ease,opacity .18s ease;display:grid;grid-template-rows:1fr;opacity:1}
.cap-body-inner{min-height:0}
.cap-panel.is-collapsed .cap-body{grid-template-rows:0fr;opacity:0;pointer-events:none}
.cap-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:9px}
.cap-thumb-wrap{position:relative;min-width:0}
.cap-thumb{display:block;width:100%;aspect-ratio:4/3;border-radius:8px;border:1px solid var(--stroke);overflow:hidden;background:#020714;transition:border-color .15s,transform .15s,opacity .15s;cursor:pointer;padding:0;position:relative}
.cap-thumb img{width:100%;height:100%;object-fit:contain;object-position:center;display:block;background:#020714}
.cap-thumb:active{transform:scale(.98)}
@media(hover:hover){.cap-thumb:hover{border-color:rgba(56,199,244,.55)}}
.cap-thumb.off{opacity:.48;border-style:dashed}
.cap-thumb.off img{filter:grayscale(.45)}
.cap-state{position:absolute;left:5px;bottom:5px;max-width:calc(100% - 10px);padding:3px 6px;border-radius:6px;background:rgba(2,7,20,.82);color:#d8e5ff;font-size:10px;font-weight:700;line-height:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cap-thumb.off .cap-state{color:#ffb4b4}
.cap-open{position:absolute;right:5px;top:5px;width:24px;height:24px;display:grid;place-items:center;border-radius:7px;background:rgba(2,7,20,.82);border:1px solid rgba(255,255,255,.16);color:#d8e5ff;opacity:0;transition:opacity .15s,color .15s}
.cap-open svg{width:13px;height:13px}
.cap-thumb-wrap:hover .cap-open{opacity:1}
@media(hover:hover){.cap-open:hover{color:var(--cyan)}}
.cap-thumb.img-missing,.rep-imgs .cell.img-missing{background:linear-gradient(160deg,#1c2435,#10151f)}
.cap-empty{font-size:12.5px;color:var(--txt-soft);margin:0}

/* Chat IA */
.ed-chat{display:flex;flex-direction:column;padding:0;overflow:hidden}
.chat-head{display:flex;align-items:center;gap:11px;padding:14px 16px;border-bottom:1px solid var(--stroke)}
.chat-orb{width:38px;height:38px;flex:none;border-radius:11px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.12);border:1px solid rgba(56,199,244,.3)}
.chat-orb svg{width:19px;height:19px}
.chat-head h3{font-size:14px;font-weight:700}
.chat-on{font-size:11px;color:var(--green);display:flex;align-items:center;gap:5px}
.chat-on::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 0 0 rgba(61,220,151,.5);animation:chOn 1.6s ease-in-out infinite}
@keyframes chOn{0%,100%{box-shadow:0 0 0 0 rgba(61,220,151,.5)}50%{box-shadow:0 0 0 5px rgba(61,220,151,0)}}
.chat-msgs{display:flex;flex-direction:column;gap:10px;padding:16px;height:300px;overflow-y:auto}
.chat-msg{max-width:88%;padding:10px 13px;border-radius:14px;font-size:12.8px;line-height:1.5;white-space:pre-wrap;word-wrap:break-word}
.chat-msg.ai{align-self:flex-start;background:var(--panel-2);border:1px solid var(--stroke);border-bottom-left-radius:5px}
.chat-msg.me{align-self:flex-end;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;border-bottom-right-radius:5px}
.chat-msg .caret{display:inline-block;width:7px;height:14px;background:var(--cyan);margin-left:2px;vertical-align:-2px;animation:chBlink .8s steps(1) infinite}
@keyframes chBlink{50%{opacity:0}}
.chat-typing{display:inline-flex;gap:4px;align-items:center}
.chat-typing i{width:6px;height:6px;border-radius:50%;background:var(--txt-soft);animation:chDot 1.2s ease-in-out infinite}
.chat-typing i:nth-child(2){animation-delay:.2s}
.chat-typing i:nth-child(3){animation-delay:.4s}
@keyframes chDot{0%,60%,100%{opacity:.3;transform:translateY(0)}30%{opacity:1;transform:translateY(-3px)}}
.chat-chips{display:flex;flex-wrap:wrap;gap:7px;padding:0 16px 12px}
.chat-chip{font-size:11.5px;padding:6px 11px;border-radius:99px;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt-soft);transition:.15s}
@media (hover:hover){.chat-chip:hover{color:var(--cyan);border-color:rgba(56,199,244,.4);background:rgba(56,199,244,.08)}}
.chat-input{display:flex;gap:8px;padding:12px 14px;border-top:1px solid var(--stroke)}
.chat-input input{flex:1;min-width:0;padding:10px 13px;border-radius:99px;border:1px solid var(--stroke);background:var(--panel);color:var(--txt);font:inherit;font-size:13px}
.chat-input input::placeholder{color:var(--off)}
.chat-input button{width:40px;height:40px;flex:none;border-radius:50%;border:0;display:grid;place-items:center;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;transition:filter .15s,transform .15s}
.chat-input button svg{width:17px;height:17px}
@media (hover:hover){.chat-input button:hover{filter:brightness(1.1)}}
.chat-input button:active{transform:scale(.94)}

/* Hallazgos del doctor */
.hz-panel{display:flex;flex-direction:column;gap:10px}
.hz-chips{display:flex;flex-wrap:wrap;gap:7px;max-height:200px;overflow-y:auto;padding:2px}
.hz-chip{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:99px;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt-soft);font-size:12.5px;font-weight:600;cursor:pointer;transition:.15s;user-select:none}
@media(hover:hover){.hz-chip:hover{border-color:rgba(56,199,244,.4);color:var(--cyan);background:rgba(56,199,244,.08)}}
.hz-chip.selected{border-color:var(--cyan);background:rgba(56,199,244,.15);color:var(--cyan)}
.hz-chip.selected .hz-dot{background:var(--cyan);border-color:var(--cyan)}
.hz-chip .hz-dot{width:8px;height:8px;border-radius:50%;border:1.5px solid var(--stroke-strong);background:transparent;transition:.15s;flex:none}
.hz-chip.critico{border-color:rgba(245,158,45,.4)}
.hz-chip.critico.selected{border-color:var(--orange);background:rgba(245,158,45,.15);color:var(--orange)}
.hz-chip.critico.selected .hz-dot{background:var(--orange);border-color:var(--orange)}
.hz-add{display:flex;gap:8px;margin-top:4px}
.hz-add input{flex:1;min-width:0;padding:9px 12px;border-radius:9px;border:1px solid var(--stroke);background:var(--panel);color:var(--txt);font:inherit;font-size:13px}
.hz-add input::placeholder{color:var(--off)}
.hz-add button{flex:none;padding:9px 14px;border-radius:9px;border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font:inherit;font-size:12.5px;font-weight:700;cursor:pointer;transition:filter .15s}
@media(hover:hover){.hz-add button:hover{filter:brightness(1.1)}}
.hz-count{font-size:11.5px;color:var(--txt-soft);margin-top:2px}
.hz-count strong{color:var(--cyan)}

/* Plantillas */
.tpl-list{display:flex;flex-direction:column;gap:9px}
.tpl-item{display:flex;align-items:center;gap:4px;border-radius:10px;border:1px solid var(--stroke);background:var(--panel-2);transition:border-color .15s,background-color .15s}
@media (hover:hover){.tpl-item:hover{border-color:rgba(56,199,244,.45);background:rgba(56,199,244,.07)}}
.tpl-main{display:flex;align-items:center;gap:11px;flex:1;min-width:0;text-align:left;padding:11px 12px;color:var(--txt);background:none;border:0;transition:transform .15s}
.tpl-main:active{transform:scale(.98)}
.tpl-ico{width:34px;height:34px;flex:none;border-radius:9px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.12);border:1px solid rgba(56,199,244,.28);overflow:hidden}
.tpl-ico svg{width:17px;height:17px}
.tpl-ico img{width:100%;height:100%;object-fit:cover;display:block}
.tpl-tx{min-width:0}
.tpl-t{font-size:13px;font-weight:700}
.tpl-d{font-size:11px;color:var(--txt-soft);margin-top:1px}
.tpl-cfg{flex:none;width:32px;height:32px;margin-right:8px;display:grid;place-items:center;border-radius:8px;border:1px solid transparent;color:var(--txt-soft);background:none;transition:color .15s,background-color .15s,transform .15s}
.tpl-cfg:active{transform:scale(.92)}
@media (hover:hover){.tpl-cfg:hover{color:var(--cyan);background:rgba(56,199,244,.14)}}
.tpl-cfg svg{width:16px;height:16px}

/* Pestañas de plantillas (Informe / Imágenes) */
.tpl-tabs{display:flex;gap:6px;margin:4px 0 12px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:10px;padding:4px}
.tpl-tab{flex:1;padding:8px 10px;border:0;border-radius:7px;background:none;color:var(--txt-soft);font:inherit;font-size:13px;font-weight:600;cursor:pointer;transition:background-color .15s,color .15s}
.tpl-tab.active{background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff}
.tpl-pane{display:none}
.tpl-pane.active{display:block}
.img-grid{display:grid;grid-template-columns:1fr 1fr;gap:9px}
.img-tpl{display:flex;flex-direction:column;align-items:flex-start;gap:8px;padding:12px;border-radius:10px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt);text-align:left;cursor:pointer;transition:border-color .15s,background-color .15s,transform .15s}
.img-tpl:active{transform:scale(.98)}
@media (hover:hover){.img-tpl:hover{border-color:rgba(56,199,244,.45);background:rgba(56,199,244,.07)}}
.img-tpl.active{border-color:var(--cyan);background:rgba(56,199,244,.1)}
.img-prev{width:100%;display:grid;gap:3px}
.img-prev span{aspect-ratio:4/3;background:rgba(56,199,244,.18);border:1px solid rgba(56,199,244,.3);border-radius:3px}
.img-tpl .img-t{font-size:12.5px;font-weight:700}

/* ===== Encabezado tipo informe clínico (posicionamiento libre) ===== */
.rep-header{position:relative;margin-bottom:16px}
.rep-header>div{position:absolute;top:0;left:0;box-sizing:border-box}
.rep-logo{display:grid;place-items:center;border-radius:8px;overflow:hidden}
.rep-logo img{width:100%;height:100%;object-fit:contain}
.rep-logo .logo-ph{width:100%;height:100%;display:grid;place-items:center;text-align:center;font-size:10px;line-height:1.25;color:var(--txt-soft);border:1px dashed var(--stroke-strong);border-radius:8px;padding:4px}
.rep-clinic{background:#cfe6e4;border-radius:4px;text-align:center;display:flex;align-items:center;justify-content:center;overflow:hidden}
.rep-clinic [contenteditable]{font-family:'Sora',sans-serif;font-weight:700;color:#143036;outline:none;width:100%}
.rep-anat{color:var(--txt-soft);display:grid;place-items:center;overflow:hidden}
.rep-anat svg{width:100%;height:100%;object-fit:contain;display:block}
.rep-anat img{width:100%;height:100%;object-fit:contain;display:block}

/* Rejilla de imágenes del estudio */
.rep-imgs{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin:14px 0 20px}
.rep-imgs .cell{display:block;min-width:0;aspect-ratio:4/3;background:#020714;border:1px solid var(--stroke);border-radius:4px;overflow:hidden;position:relative}
.rep-imgs .cell img{width:100%;height:100%;object-fit:contain;object-position:center;display:block;background:#020714}
.rep-img-tools{position:absolute;right:5px;top:5px;display:flex;gap:4px;z-index:3;opacity:0;transition:opacity .15s}
.rep-imgs .cell:hover .rep-img-tools,.rep-imgs .cell:focus-within .rep-img-tools{opacity:1}
.rep-img-tools button{width:23px;height:23px;display:grid;place-items:center;border-radius:6px;border:1px solid rgba(255,255,255,.16);background:rgba(2,7,20,.84);color:#d8e5ff;font-size:14px;font-weight:800;line-height:1;transition:color .15s,background-color .15s,transform .15s}
.rep-img-tools button:active{transform:scale(.92)}
.rep-img-tools button svg{width:12px;height:12px}
@media(hover:hover){.rep-img-tools button:hover{color:var(--cyan);background:rgba(2,7,20,.96)}.rep-img-tools button[data-img-action="remove"]:hover{color:#ff8b8b}}
.rep-img-size{position:absolute;left:5px;bottom:5px;padding:3px 6px;border-radius:6px;background:rgba(2,7,20,.82);color:#d8e5ff;font-size:10px;font-weight:700;line-height:1;opacity:0;transition:opacity .15s}
.rep-imgs .cell:hover .rep-img-size,.rep-imgs .cell:focus-within .rep-img-size{opacity:1}

/* Firma */
.rep-sign{margin-top:38px;display:flex}
.rep-sign[data-pos="left"]{justify-content:flex-start}
.rep-sign[data-pos="center"]{justify-content:center}
.rep-sign[data-pos="right"]{justify-content:flex-end}
.rep-sign .sign-box{min-width:250px;text-align:center}
.rep-sign .sign-image{display:block;max-width:230px;max-height:72px;object-fit:contain;margin:0 auto 5px}
.rep-sign .sign-line{padding-top:8px;border-top:1px solid var(--txt)}
.rep-sign .sign-box [contenteditable]{font-size:13px;outline:none}

/* ===== Modal de Configuración ===== */
.cfg-ov{position:fixed;inset:0;background:rgba(5,8,16,.62);display:none;align-items:center;justify-content:center;z-index:90;padding:20px}
.cfg-ov.open{display:flex}
.cfg-modal{width:100%;max-width:620px;max-height:92vh;overflow-y:auto;background:var(--panel);border:1px solid var(--stroke-strong);border-radius:var(--r-lg);padding:22px;box-shadow:0 24px 70px -24px rgba(0,0,0,.75)}
.cfg-modal h3{font-size:16px;font-weight:700;margin-bottom:3px}
.cfg-modal .cfg-sub{font-size:12px;color:var(--txt-soft);margin-bottom:16px}

/* Vista previa: la hoja completa con elementos arrastrables y redimensionables */
.cfg-pv-tag{font-size:10.5px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:var(--txt-soft);margin-bottom:4px}
.cfg-pv-hint{font-size:11.5px;color:var(--txt-soft);margin-bottom:10px}
.cfg-sheet{position:relative;width:100%;background:#fff;color:#143036;border:1px solid var(--stroke);border-radius:6px;box-shadow:0 12px 36px -16px rgba(0,0,0,.6);overflow:hidden;margin-bottom:18px;user-select:none;-webkit-user-select:none}
.cfg-head{position:relative;width:100%;border-bottom:1px dashed #dde4ea}
.cfg-el{position:absolute;box-sizing:border-box;cursor:grab;touch-action:none}
.cfg-el:active{cursor:grabbing}
.cfg-el.sel{outline:2px solid #38c7f4;outline-offset:1px}
.cfg-el .rz{position:absolute;right:-7px;bottom:-7px;width:15px;height:15px;border-radius:4px;background:#38c7f4;border:2px solid #fff;cursor:nwse-resize;box-shadow:0 1px 5px rgba(0,0,0,.45);opacity:0;transition:opacity .12s;touch-action:none}
.cfg-el.sel .rz,.cfg-el:hover .rz{opacity:1}
.cfg-el .e-logo{width:100%;height:100%;display:grid;place-items:center;border:1px dashed #c4d0da;border-radius:6px;overflow:hidden;font-size:9px;line-height:1.2;text-align:center;color:#7c8a98;background:#fff}
.cfg-el .e-logo img{width:100%;height:100%;object-fit:contain}
.cfg-el .e-name{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#cfe6e4;border-radius:4px;overflow:hidden;padding:2px 6px}
.cfg-el .e-name span{font-family:'Sora',sans-serif;font-weight:700;color:#143036;line-height:1.1;text-align:center}
.cfg-el .e-anat{width:100%;height:100%;display:grid;place-items:center;color:#5a6b7a;overflow:hidden}
.cfg-el .e-anat svg,.cfg-el .e-anat img{width:100%;height:100%;object-fit:contain}
/* cuerpo estático de la hoja (solo contexto visual) */
.cfg-body{padding:12px 16px 18px}
.cfg-body .b-meta{display:grid;grid-template-columns:1fr 1fr;gap:5px 16px;margin-bottom:13px}
.cfg-body .b-meta i{height:7px;border-radius:3px;background:#eef2f5}
.cfg-body .b-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:4px;margin-bottom:13px}
.cfg-body .b-grid i{aspect-ratio:4/3;border-radius:3px;background:#e7edf2}
.cfg-body .b-lines{display:flex;flex-direction:column;gap:6px}
.cfg-body .b-lines i{height:6px;border-radius:3px;background:#eef2f5}
.cfg-body .b-lines i:nth-child(3n){width:62%}
.cfg-body .b-sign{margin-top:16px;display:flex}
.cfg-body .b-sign[data-pos=left]{justify-content:flex-start}
.cfg-body .b-sign[data-pos=center]{justify-content:center}
.cfg-body .b-sign[data-pos=right]{justify-content:flex-end}
.cfg-body .b-sign u{width:130px;border-top:1px solid #143036;padding-top:5px;font-size:9px;color:#143036;text-align:center;text-decoration:none;display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}
.cfg-field{display:flex;flex-direction:column;gap:6px;margin-bottom:15px}
.cfg-field label{font-size:12px;color:var(--txt-soft)}
.cfg-field input[type=text],.cfg-field select{padding:9px 12px;border-radius:9px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt);font:inherit;font-size:13.5px}
.cfg-logo-row{display:flex;align-items:center;gap:12px}
.cfg-logo-prev{width:56px;height:56px;flex:none;border-radius:8px;border:1px solid var(--stroke);object-fit:contain;background:var(--panel-2)}
.cfg-file{display:inline-flex;align-items:center;gap:7px;padding:8px 13px;border-radius:9px;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--cyan);font-size:12.5px;font-weight:600;cursor:pointer}
.cfg-file svg{width:15px;height:15px}
@media (hover:hover){.cfg-file:hover{background:rgba(56,199,244,.1)}}
.cfg-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:8px}
.cfg-btn{padding:9px 16px;border-radius:9px;font-size:13px;font-weight:600;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt)}
.cfg-btn.primary{border:0;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff}
@media (hover:hover){.cfg-btn:hover{filter:brightness(1.08)}}

/* Editar plantilla de imágenes */
.img-item{position:relative}
.img-cfg{position:absolute;top:6px;right:6px;width:26px;height:26px;display:grid;place-items:center;border-radius:7px;border:1px solid var(--stroke);color:var(--txt-soft);background:var(--panel);transition:color .15s,background-color .15s,transform .15s;z-index:2}
.img-cfg:active{transform:scale(.92)}
@media (hover:hover){.img-cfg:hover{color:var(--cyan);background:rgba(56,199,244,.14)}}
.img-cfg svg{width:14px;height:14px}
.cfg-modal--sm{max-width:380px}
.cfg-row{display:flex;gap:12px}
.cfg-row .cfg-field{flex:1}
.cfg-field input[type=number]{padding:9px 12px;border-radius:9px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt);font:inherit;font-size:13.5px}

/* Vista previa del reporte (pantalla completa) */
.pv-ov{position:fixed;inset:0;z-index:3000;background:rgba(8,12,18,.78);backdrop-filter:blur(4px);display:none;flex-direction:column}
.pv-ov.open{display:flex}
.pv-bar{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:13px 20px;background:var(--panel);border-bottom:1px solid var(--stroke);flex:none}
.pv-title{font-size:14px;font-weight:700;color:var(--txt)}
.pv-bar-actions{display:flex;gap:10px}
.pv-bar-actions .cfg-btn{display:inline-flex;align-items:center;gap:7px}
.pv-bar-actions .cfg-btn svg{width:15px;height:15px}
.pv-scroll{flex:1;overflow:auto;padding:20px;display:flex;justify-content:center;align-items:flex-start}
.pv-paper{position:relative;width:8.5in;height:11in;max-width:none;background:#fff;color:#143036;border-radius:6px;box-shadow:0 18px 50px -18px rgba(0,0,0,.7);padding:.4in;box-sizing:border-box;overflow:hidden;flex:none;transform-origin:top center}
.pv-paper .rep-header{overflow:hidden}
.pv-paper .ed-doc{box-sizing:border-box}
.pv-paper .rep-imgs{max-width:100%;overflow:hidden}
.pv-paper [contenteditable]{outline:none}
.pv-paper [data-ph]:empty::before{content:'';}
/* Ocultar botones de edición de secciones en vista previa */
.pv-paper .sec-add,
.pv-paper .sec-hide,
.pv-paper .sec-delete,
.pv-paper .rep-img-tools,
.pv-paper .rep-img-size{display:none!important}

/* Aviso flotante */
.ed-toast{position:fixed;left:50%;bottom:28px;transform:translateX(-50%) translateY(20px);z-index:3100;display:flex;align-items:center;gap:9px;padding:12px 18px;border-radius:11px;background:linear-gradient(135deg,#1f9d57,#13c47e);color:#fff;font-size:13.5px;font-weight:600;box-shadow:0 14px 36px -14px rgba(0,0,0,.6);opacity:0;pointer-events:none;transition:opacity .25s,transform .25s}
.ed-toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
.ed-toast.err{background:linear-gradient(135deg,#d3443f,#f0635c)}

@media print{
  @page{size:letter;margin:0}
  html,body{width:8.5in!important;height:11in!important;min-height:0!important;margin:0!important;padding:0!important;overflow:hidden!important;background:#fff!important}
  body>:not(.dash){display:none!important}
  .dash{display:block!important;width:8.5in!important;height:11in!important;min-height:0!important;margin:0!important;padding:0!important;overflow:hidden!important;background:#fff!important}
  .dash>:not(.main){display:none!important}
  .main{display:block!important;width:8.5in!important;height:11in!important;margin:0!important;padding:0!important;overflow:hidden!important;background:#fff!important}
  .main>:not(#previewModal){display:none!important}
  #previewModal{display:block!important;position:static!important;width:8.5in!important;height:11in!important;margin:0!important;padding:0!important;overflow:hidden!important;background:#fff!important;backdrop-filter:none!important;z-index:auto!important}
  #previewModal .pv-bar{display:none!important}
  #previewModal .pv-scroll{display:block!important;width:8.5in!important;height:11in!important;margin:0!important;padding:0!important;overflow:visible!important;background:#fff!important}
  #previewModal .pv-paper{display:block!important;position:relative!important;width:8.5in!important;height:11in!important;max-width:none!important;margin:0!important;padding:.4in!important;box-sizing:border-box!important;overflow:hidden!important;background:#fff!important;color:#111!important;border:0!important;border-radius:0!important;box-shadow:none!important;zoom:1!important;transform:none!important}
  #previewModal .pv-paper>.ed-doc{display:flex!important;flex-direction:column!important;width:var(--report-print-width,7.7in)!important;min-width:0!important;max-width:none!important;min-height:10.2in!important;height:auto!important;margin:0!important;padding:0!important;overflow:visible!important;background:#fff!important;color:#111!important;border:0!important;border-radius:0!important;box-shadow:none!important;box-sizing:border-box!important;font-size:10.5px!important;line-height:1.22!important;transform:scale(var(--report-print-scale,1))!important;transform-origin:top left!important}
  #previewModal .sec-add,
  #previewModal .sec-hide,
  #previewModal .sec-delete,
  #previewModal .rep-img-tools,
  #previewModal .rep-img-size{display:none!important}
  .pv-paper .rep-header{margin-bottom:8px!important}
  .pv-paper .doc-h{margin-bottom:10px!important}
  .pv-paper .doc-h h2{font-size:14.5px!important;margin:0 0 4px!important}
  .pv-paper .doc-h p{font-size:8.5px!important;margin:0!important}
  .pv-paper .doc-meta{font-size:9px!important;gap:2px 9px!important;margin-bottom:8px!important;grid-template-columns:115px 1fr!important}
  .pv-paper .rep-imgs{gap:4px!important;margin:7px 0 10px!important;max-width:100%!important;overflow:hidden!important}
  .pv-paper .rep-imgs .cell{border-radius:3px!important}
  .pv-paper h4{font-size:10px!important;margin:8px 0 3px!important;color:#0596d8!important}
  .pv-paper p,.pv-paper ul{font-size:9.8px!important;line-height:1.22!important;margin:2px 0!important}
  .pv-paper ul{gap:2px!important;padding-left:16px!important}
  .pv-paper .rep-sign{margin-top:12px!important}
  .pv-paper h4,.pv-paper p,.pv-paper ul,.pv-paper .doc-h,.pv-paper .doc-meta,.pv-paper .rep-imgs{page-break-inside:avoid!important}
}
.ed-tmp-select{background:rgba(110,160,255,.35);border-radius:2px}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="ed-actions">
    <a class="ed-btn" href="<?php echo e(route('ia-reportes')); ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Salir
    </a>
    <button class="ed-btn" type="button" id="btnPreview">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Vista Previa
    </button>
    <button class="ed-btn primary" type="button" id="btnGuardar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
      Guardar reporte
      <span class="div"></span>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </button>
  </div>

  
  <div class="ed-meta">
    <div class="f grow">
      <label>Estudio</label>
      <select class="ed-ctrl" id="edEstudioSel"
              onchange="if(this.value){ window.location.href='<?php echo e(route('ia-reportes.redactar')); ?>?estudio='+this.value }">
        <option value="">Selecciona un estudio sin reporte…</option>
        <?php $__currentLoopData = ($estudiosLista ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($e['id']); ?>" <?php if(optional($estudio)->id == $e['id']): echo 'selected'; endif; ?>><?php echo e($e['label']); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>
    <div class="f grow">
      <label>Tipo de estudio</label>
      <select class="ed-ctrl" id="edTipo">
        <option>Colonoscopia</option>
        <option>Gastroscopia</option>
        <option>Duodenoscopia</option>
        <option>Broncoscopia</option>
      </select>
    </div>
    <div class="f">
      <label>Fecha del reporte</label>
      <div class="ed-ctrl">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span contenteditable="true" data-ph="<?php echo e(str_replace(['d','m','Y'], ['dd','mm','aaaa'], user_date_format())); ?>"><?php echo e(now()->format(user_date_format())); ?></span>
      </div>
    </div>
    <div class="f">
      <label>Estado</label>
      <span class="ed-status borrador" id="edStatus">Borrador</span>
    </div>
    <div class="f">
      <label>Ajuste de página</label>
      <span class="ed-status" id="pageFitIndicator" style="font-size: 11px;">Verificando...</span>
    </div>
  </div>

  
  <div class="ed-toolbar">
    <span class="sep"></span>
    <button class="ed-tb" data-cmd="undo" aria-label="Deshacer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg></button>
    <button class="ed-tb" data-cmd="redo" aria-label="Rehacer"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7v6h-6"/><path d="M3 17a9 9 0 0 1 9-9 9 9 0 0 1 6 2.3L21 13"/></svg></button>
    <span class="sep"></span>
    <button class="ed-tb" data-cmd="bold"><b>B</b></button>
    <button class="ed-tb" data-cmd="italic"><i>I</i></button>
    <button class="ed-tb" data-cmd="underline"><u>U</u></button>
    <button class="ed-tb" data-cmd="strikeThrough"><s>S</s></button>
    <span class="sep"></span>
    <input type="text" class="ed-tb" id="fontSizeInput" aria-label="Tamaño de letra" inputmode="numeric" pattern="[0-9]*" min="8" max="72" value="14" style="width: 60px; padding: 4px 8px; background: var(--panel); color: var(--txt); border: 1px solid var(--stroke);">
    <span class="ed-color-wrap">
      <button type="button" class="ed-tb ed-color-btn" id="textColorBtn" aria-label="Color de letra" title="Color de letra">
        <span class="ed-color-letter">A</span>
        <span class="ed-color-swatch" id="textColorSwatch"></span>
      </button>
      <input type="color" class="ed-color-input" id="textColorInput" aria-label="Elegir color de letra" value="#111827">
    </span>
    <span class="sep"></span>
    <button class="ed-tb" id="underlineColorBtn" aria-label="Subrayado con color" title="Subrayado con color">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 17c4 0 7-2 7-5V4H5v8c0 3 3 5 7 5z"/><line x1="7" y1="21" x2="17" y2="21"/></svg>
    </button>
    <button class="ed-tb" data-cmd="removeFormat" aria-label="Quitar formato" title="Quitar formato">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <span class="sep"></span>
    <button class="ed-tb" data-cmd="justifyLeft" aria-label="Alinear izquierda"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg></button>
    <button class="ed-tb" data-cmd="justifyCenter" aria-label="Centrar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="10" x2="6" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="18" y1="18" x2="6" y2="18"/></svg></button>
    <button class="ed-tb" data-cmd="justifyFull" aria-label="Justificar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="3" y2="18"/></svg></button>
    <span class="sep"></span>
    <button class="ed-tb" data-cmd="insertUnorderedList" aria-label="Lista"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></button>
    <button class="ed-tb" data-cmd="insertOrderedList" aria-label="Lista numerada"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg></button>
  </div>

  
  <div class="ed-body">

    <div class="ed-main">

      
      <article class="card ed-doc rise d2">

        
        <div class="rep-header">
          <div class="rep-logo" id="repLogo">
            <span class="logo-ph">Logo de<br>la clínica</span>
          </div>
          <div class="rep-clinic" id="repClinicBox">
            <span contenteditable="true" id="repClinicName" data-ph="Nombre de la clínica">Nombre de la clínica</span>
          </div>
          <div class="rep-anat" id="repAnat" aria-hidden="true">
            <svg viewBox="0 0 80 110" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M30 8c-6 6-10 14-10 22 0 6 2 11 6 16 4 5 6 9 6 15 0 10-8 14-8 24 0 8 6 13 14 13s14-6 14-15c0-12-12-16-12-26 0-7 5-11 9-17 3-5 5-10 5-16C58 22 50 12 42 8"/><path d="M30 8c4-3 8-3 12 0"/></svg>
          </div>
        </div>

        <div class="doc-meta">
          <span class="k">Paciente:</span><span contenteditable="true" data-ph="Nombre del paciente"><?php echo e($datosEstudio['paciente'] ?? ''); ?></span>
          <span class="k">Edad:</span><span contenteditable="true" data-ph="—"><?php echo e($datosEstudio['edad'] ?? ''); ?></span>
          <span class="k">Sexo:</span><span contenteditable="true" data-ph="—"><?php echo e($datosEstudio['sexo'] ?? ''); ?></span>
          <span class="k">Fecha de Nac.:</span><span contenteditable="true" data-ph="dd/mm/aaaa"><?php echo e($datosEstudio['nacimiento'] ?? ''); ?></span>
          <span class="k">Fecha del Estudio:</span><span contenteditable="true" data-ph="dd/mm/aaaa"><?php echo e($datosEstudio['fecha_estudio'] ?? ''); ?></span>
          <span class="k">Procedimiento:</span><span contenteditable="true" data-ph="Tipo de procedimiento"><?php echo e($datosEstudio['procedimiento'] ?? ''); ?></span>
        </div>

        
        <div class="rep-imgs" id="repImgs">
          <span class="cell"></span><span class="cell"></span><span class="cell"></span><span class="cell"></span>
          <span class="cell"></span><span class="cell"></span><span class="cell"></span><span class="cell"></span>
        </div>

        <div id="docSections"><?php if($reporte?->contenido_html): ?><?php echo $reporte->contenido_html; ?><?php elseif($reporte?->contenido_texto): ?><div contenteditable="true" style="min-height:120px;outline:none"><?php echo e($reporte->contenido_texto); ?></div><?php endif; ?></div>
        <input type="hidden" id="existingReporteId" value="<?php echo e($reporte?->id); ?>">

        
        <div class="rep-sign" id="repSign" data-pos="center">
          <div class="sign-box">
            <?php if(auth()->user()?->signature_path): ?>
              <img
                class="sign-image"
                src="<?php echo e(route('configuracion.signature.show', ['v' => auth()->user()->signature_updated_at?->timestamp])); ?>"
                alt="Firma digital de <?php echo e(auth()->user()->name); ?>"
              >
            <?php endif; ?>
            <div class="sign-line">
              <span contenteditable="true" id="repSignName" data-ph="Dr. Nombre del médico"><?php echo e(($datosEstudio['medico'] ?? '') ?: 'Dr. Nombre del médico'); ?></span>
            </div>
          </div>
        </div>
      </article>

    </div>

    
    <aside class="ed-side">

      <article class="card cap-panel rise d3">
        <div class="cap-head">
          <h3>Capturas del estudio</h3>
          <div class="cap-actions">
            <span class="cap-count" id="capIncludedCount"><?php echo e(($estudioImagenes ?? collect())->count()); ?>/<?php echo e(($estudioImagenes ?? collect())->count()); ?></span>
            <button type="button" class="cap-reset" id="imgRestoreAll" aria-label="Mostrar todas las capturas" title="Mostrar todas">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 15.5-6.2"/><path d="M21 12a9 9 0 0 1-15.5 6.2"/><path d="M18 3v5h-5"/><path d="M6 21v-5h5"/></svg>
            </button>
            <button type="button" class="cap-toggle" id="capPanelToggle" aria-label="Contraer capturas del estudio" aria-expanded="true" aria-controls="capPanelBody" title="Contraer capturas">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="m6 15 6-6 6 6"/></svg>
            </button>
          </div>
        </div>
        <div class="cap-body" id="capPanelBody">
          <div class="cap-body-inner">
            <?php if(($estudioImagenes ?? collect())->count()): ?>
              <div class="cap-grid">
                <?php $__currentLoopData = ($estudioImagenes ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="cap-thumb-wrap">
                  <button type="button" class="cap-thumb" data-img-index="<?php echo e($i); ?>" title="Agregar o quitar del reporte">
                    <img src="<?php echo e($img['url']); ?>" alt="" loading="lazy" onerror="this.parentElement.classList.add('img-missing');this.remove()">
                    <span class="cap-state">En reporte</span>
                  </button>
                  <a class="cap-open" href="<?php echo e($img['show_url'] ?? $img['url']); ?>" target="_blank" rel="noopener" aria-label="Abrir captura completa" title="Abrir captura completa">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/></svg>
                  </a>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            <?php else: ?>
              <p class="cap-empty">Sin capturas asociadas.</p>
            <?php endif; ?>
          </div>
        </div>
      </article>

      <article class="card ed-chat rise d3">
        <div class="chat-head">
          <span class="chat-orb">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
          </span>
          <div>
            <h3>ENCLAII</h3>
            <span class="chat-on">En línea</span>
          </div>
        </div>

        <div class="chat-msgs" id="chatMsgs"></div>

        <div class="chat-chips" id="chatChips">
          <button type="button" class="chat-chip">Redacta los hallazgos</button>
          <button type="button" class="chat-chip">Sugiere recomendaciones</button>
          <button type="button" class="chat-chip">Sugiere una impresión diagnóstica</button>
          <button type="button" class="chat-chip">Mejora la redacción</button>
        </div>

        <form class="chat-input" id="chatForm">
          <input type="text" id="chatText" placeholder="Escribe un mensaje a la IA..." autocomplete="off">
          <button type="submit" aria-label="Enviar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </form>
      </article>

      
      <article class="card ed-panel rise d4" id="hzPanel">
        <h3>Hallazgos</h3>
        <div class="ph-sub">Click para insertar en el reporte o escribe uno nuevo</div>
        <div class="hz-panel">
          <div class="hz-chips" id="hzChips"></div>
          <div class="hz-add">
            <input type="text" id="hzNewInput" placeholder="Escribir hallazgo nuevo..." maxlength="255">
            <button type="button" id="hzAddBtn">Añadir</button>
          </div>
          <div class="hz-count" id="hzCount"><strong>0</strong> en el reporte</div>
        </div>
      </article>

      
      <article class="card ed-panel rise d5">
        <h3>Plantillas</h3>
        <div class="ph-sub">Elige una estructura base para tu reporte</div>
        <div class="tpl-tabs">
          <button type="button" class="tpl-tab active" data-tab="informe">Informe</button>
          <button type="button" class="tpl-tab" data-tab="imagenes">Imágenes</button>
        </div>
        <div class="tpl-pane active" id="paneInforme">
        <div class="tpl-list" id="tplList">
          <?php
            $gear = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>';
            $fileIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
          ?>

          <div class="tpl-item">
            <button type="button" class="tpl-main" data-tpl="colonoscopia">
              <span class="tpl-ico"><?php echo $fileIco; ?></span>
              <span class="tpl-tx"><span class="tpl-t">Colonoscopia</span><span class="tpl-d">Preparación, hallazgos por segmento…</span></span>
            </button>
            <button type="button" class="tpl-cfg" data-tpl-cfg="colonoscopia" aria-label="Editar plantilla" title="Editar plantilla"><?php echo $gear; ?></button>
          </div>

          <div class="tpl-item">
            <button type="button" class="tpl-main" data-tpl="gastroscopia">
              <span class="tpl-ico"><?php echo $fileIco; ?></span>
              <span class="tpl-tx"><span class="tpl-t">Gastroscopia</span><span class="tpl-d">Esófago, estómago, duodeno…</span></span>
            </button>
            <button type="button" class="tpl-cfg" data-tpl-cfg="gastroscopia" aria-label="Editar plantilla" title="Editar plantilla"><?php echo $gear; ?></button>
          </div>

          <div class="tpl-item">
            <button type="button" class="tpl-main" data-tpl="duodenoscopia">
              <span class="tpl-ico"><?php echo $fileIco; ?></span>
              <span class="tpl-tx"><span class="tpl-t">Duodenoscopia</span><span class="tpl-d">Duodeno, papila, vía biliar…</span></span>
            </button>
            <button type="button" class="tpl-cfg" data-tpl-cfg="duodenoscopia" aria-label="Editar plantilla" title="Editar plantilla"><?php echo $gear; ?></button>
          </div>

          <div class="tpl-item">
            <button type="button" class="tpl-main" data-tpl="broncoscopia">
              <span class="tpl-ico"><?php echo $fileIco; ?></span>
              <span class="tpl-tx"><span class="tpl-t">Broncoscopia</span><span class="tpl-d">Árbol bronquial, tráquea, carina…</span></span>
            </button>
            <button type="button" class="tpl-cfg" data-tpl-cfg="broncoscopia" aria-label="Editar plantilla" title="Editar plantilla"><?php echo $gear; ?></button>
          </div>

          <div class="tpl-item">
            <button type="button" class="tpl-main" data-tpl="blanco">
              <span class="tpl-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
              <span class="tpl-tx"><span class="tpl-t">En blanco</span><span class="tpl-d">Empieza desde cero</span></span>
            </button>
            <button type="button" class="tpl-cfg" data-tpl-cfg="blanco" aria-label="Editar plantilla" title="Editar plantilla"><?php echo $gear; ?></button>
          </div>
        </div>
        </div>

        <div class="tpl-pane" id="paneImagenes">
          <div class="ph-sub" style="margin-bottom:10px">Define cuántas imágenes del estudio se incluyen</div>
          <div class="img-grid">
            <div class="img-item">
              <button type="button" class="img-tpl active" data-tpl="img2">
                <span class="img-prev" style="grid-template-columns:repeat(2,1fr)"><span></span><span></span><span></span><span></span></span>
              </button>
              <button type="button" class="img-cfg" data-tpl-cfg="img2" aria-label="Editar plantilla" title="Editar plantilla"><?php echo $gear; ?></button>
            </div>
            <div class="img-item">
              <button type="button" class="img-tpl" data-tpl="img3">
                <span class="img-prev" style="grid-template-columns:repeat(3,1fr)"><span></span><span></span><span></span><span></span><span></span><span></span></span>
              </button>
              <button type="button" class="img-cfg" data-tpl-cfg="img3" aria-label="Editar plantilla" title="Editar plantilla"><?php echo $gear; ?></button>
            </div>
            <div class="img-item">
              <button type="button" class="img-tpl" data-tpl="img4">
                <span class="img-prev" style="grid-template-columns:repeat(4,1fr)"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span>
              </button>
              <button type="button" class="img-cfg" data-tpl-cfg="img4" aria-label="Editar plantilla" title="Editar plantilla"><?php echo $gear; ?></button>
            </div>
            <div class="img-item">
              <button type="button" class="img-tpl" data-tpl="imgNone">
                <span class="img-prev" style="grid-template-columns:1fr"><span style="background:none;border:1px dashed var(--stroke-strong)"></span></span>
              </button>
            </div>
          </div>
        </div>
      </article>

    </aside>

  </div>

  
  <div class="cfg-ov" id="cfgModal">
    <div class="cfg-modal" role="dialog" aria-modal="true" aria-labelledby="cfgTitle">
      <h3 id="cfgTitle">Configurar plantilla</h3>
      <div class="cfg-sub" id="cfgSub">Así se verá el encabezado del reporte</div>

      
      <div class="cfg-pv-tag">Vista previa de la hoja</div>
      <div class="cfg-pv-hint">Arrastra el logo, el nombre o la imagen para moverlos. Usa la esquina azul para hacerlos más grandes o pequeños.</div>
      <div class="cfg-sheet" id="cfgSheet">
        <div class="cfg-head" id="cfgHead">
          <div class="cfg-el" id="elLogo" data-el="logo">
            <div class="e-logo" id="elLogoIn">Logo de<br>la clínica</div>
            <span class="rz" data-rz="logo"></span>
          </div>
          <div class="cfg-el" id="elName" data-el="name">
            <div class="e-name"><span id="elNameTx">Nombre de la clínica</span></div>
            <span class="rz" data-rz="name"></span>
          </div>
          <div class="cfg-el" id="elAnat" data-el="anat">
            <div class="e-anat" id="elAnatIn"><svg viewBox="0 0 80 110" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M30 8c-6 6-10 14-10 22 0 6 2 11 6 16 4 5 6 9 6 15 0 10-8 14-8 24 0 8 6 13 14 13s14-6 14-15c0-12-12-16-12-26 0-7 5-11 9-17 3-5 5-10 5-16C58 22 50 12 42 8"/><path d="M30 8c4-3 8-3 12 0"/></svg></div>
            <span class="rz" data-rz="anat"></span>
          </div>
        </div>
        <div class="cfg-body">
          <div class="b-meta"><i></i><i></i><i></i><i></i><i></i><i></i></div>
          <div class="b-grid"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
          <div class="b-lines"><i></i><i></i><i></i><i></i><i></i><i></i></div>
          <div class="b-sign" id="cfgSignPv" data-pos="center"><u id="cfgSignPvTx">Dr. Nombre del médico</u></div>
        </div>
      </div>

      <div class="cfg-field">
        <label>Logo de la clínica (esquina superior izquierda)</label>
        <div class="cfg-logo-row">
          <label class="cfg-file">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Subir logo
            <input type="file" id="cfgLogoInput" accept="image/*" hidden>
          </label>
        </div>
      </div>

      <div class="cfg-field">
        <label>Nombre de la clínica</label>
        <input type="text" id="cfgClinic" placeholder="Ej. Sanatorio Santa María">
      </div>

      <div class="cfg-field">
        <label>Imagen lateral (ilustración)</label>
        <div class="cfg-logo-row">
          <label class="cfg-file">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Subir imagen
            <input type="file" id="cfgAnatInput" accept="image/*" hidden>
          </label>
        </div>
      </div>

      <div class="cfg-field">
        <label>Nombre en la firma</label>
        <input type="text" id="cfgSignName" placeholder="Ej. Dr. Faustino Juan Rueda Domínguez">
      </div>

      <div class="cfg-field">
        <label>Posición de la firma</label>
        <select id="cfgSignPos">
          <option value="left">Izquierda</option>
          <option value="center" selected>Centro</option>
          <option value="right">Derecha</option>
        </select>
      </div>

      <div id="imgCfgFields" style="display:none;margin-bottom:15px">
        <div class="cfg-row">
          <div class="cfg-field" style="margin-bottom:0">
            <label>Columnas de imágenes</label>
            <input type="number" id="cfgImgCols" min="1" max="6" value="2">
          </div>
          <div class="cfg-field" style="margin-bottom:0">
            <label>Número de imágenes</label>
            <input type="number" id="cfgImgCount" min="1" max="24" value="4">
          </div>
        </div>
      </div>

      <div class="cfg-actions">
        <button type="button" class="cfg-btn" id="cfgCancel">Cerrar</button>
        <button type="button" class="cfg-btn primary" id="cfgApply">Aplicar</button>
      </div>
    </div>
  </div>

  
  <div class="pv-ov" id="previewModal">
    <div class="pv-bar">
      <span class="pv-title">Vista previa del reporte</span>
      <div class="pv-bar-actions">
        <button type="button" class="cfg-btn" id="pvPrint">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          Imprimir / PDF
        </button>
        <button type="button" class="cfg-btn primary" id="pvClose">Cerrar</button>
      </div>
    </div>
    <div class="pv-scroll">
      <div class="pv-paper" id="pvPaper"></div>
    </div>
  </div>

  
  <div class="ed-toast" id="edToast" role="status" aria-live="polite"></div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ===== Contraer/expandir capturas del estudio ===== */
(function(){
  const panel = document.querySelector('.cap-panel');
  const toggle = document.getElementById('capPanelToggle');
  if (!panel || !toggle) return;

  toggle.addEventListener('click', () => {
    const collapsed = panel.classList.toggle('is-collapsed');
    toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    toggle.setAttribute('aria-label', collapsed ? 'Expandir capturas del estudio' : 'Contraer capturas del estudio');
    toggle.setAttribute('title', collapsed ? 'Expandir capturas' : 'Contraer capturas');
  });
})();

/* ===== Plantillas + configuración (logo, clínica, imagen, firma) ===== */
(function(){
  const cont = document.getElementById('docSections');
  const list = document.getElementById('tplList');
  const tipoSel = document.getElementById('edTipo');
  if (!cont) return;

  // Imagen lateral (anatómica) según el tipo de estudio seleccionado
  const STUDY_IMG = {
    'Colonoscopia':  '/images/Colonoscopia.png',
    'Gastroscopia':  '/images/Gastroscopia.png',
    'Duodenoscopia': '/images/Duodenoscopia.png',
    'Broncoscopia':  '/images/Broncoscopia.png',
  };

  // Elementos del documento que se personalizan
  const repHeader    = document.querySelector('.ed-doc .rep-header');
  const repLogo      = document.getElementById('repLogo');
  const repAnat      = document.getElementById('repAnat');
  const repClinicBox = document.getElementById('repClinicBox');
  const repClinic    = document.getElementById('repClinicName');
  const repSign      = document.getElementById('repSign');
  const repSignNm    = document.getElementById('repSignName');

  // HTML por defecto de los recuadros con imagen (para poder restaurar)
  const repImgs      = document.getElementById('repImgs');
  const logoDefault = repLogo ? repLogo.innerHTML : '';
  const anatDefault = repAnat ? repAnat.innerHTML : '';
  const imgsDefault = repImgs ? repImgs.innerHTML : '';

  // Imágenes reales del estudio inyectadas desde el backend
  const STUDY_IMAGES = <?php echo json_encode($estudioImagenes ?? [], 15, 512) ?>;

  // Datos del estudio/paciente precargados desde el backend
  const PRELOAD = <?php echo json_encode($datosEstudio ?? [], 15, 512) ?>;
  // Reporte ya guardado (si existe) para restaurar su plantilla
  const REPORTE_DB = <?php echo json_encode($reporte ?? null, 15, 512) ?>;
  const SAVED_IMAGE_CONFIG = (REPORTE_DB && REPORTE_DB.imagenes_config && typeof REPORTE_DB.imagenes_config === 'object')
    ? REPORTE_DB.imagenes_config
    : {};
  const clampInt = (value, min, max, fallback) => {
    const parsed = parseInt(value, 10);
    if (Number.isNaN(parsed)) return fallback;
    return Math.max(min, Math.min(max, parsed));
  };
  // Mapea el procedimiento del estudio a la clave de plantilla correspondiente
  const tipoToKey = (t) => {
    t = (t || '').toLowerCase();
    if (t.includes('colono')) return 'colonoscopia';
    if (t.includes('gastro')) return 'gastroscopia';
    if (t.includes('duodeno')) return 'duodenoscopia';
    if (t.includes('bronco')) return 'broncoscopia';
    return null;
  };

  // Celda del grid: imagen real del estudio o recuadro vacío
  const imageKey = (img, index) => String(img && img.id ? img.id : index);
  const imageState = new Map();
  STUDY_IMAGES.forEach((img, index) => {
    const key = imageKey(img, index);
    const saved = (SAVED_IMAGE_CONFIG.items && SAVED_IMAGE_CONFIG.items[key]) || {};
    imageState.set(key, {
      visible: saved.visible !== false,
      size: clampInt(saved.size, 1, 8, 1),
    });
  });

  let currentImagesEnabled = SAVED_IMAGE_CONFIG.enabled !== false;
  let currentImgCols = currentImagesEnabled ? clampInt(SAVED_IMAGE_CONFIG.cols, 1, 8, 4) : 0;
  let currentImgCount = 8;

  const imageConfigPayload = () => {
    const items = {};
    STUDY_IMAGES.forEach((img, index) => {
      const key = imageKey(img, index);
      const state = imageState.get(key) || { visible: true, size: 1 };
      items[key] = {
        visible: state.visible !== false,
        size: clampInt(state.size, 1, currentImgCols || 8, 1),
      };
    });

    return { version: 1, enabled: currentImagesEnabled, cols: currentImagesEnabled ? currentImgCols : 0, items };
  };
  window.getReportImagesConfig = imageConfigPayload;

  const visibleImageCount = () => STUDY_IMAGES.reduce((total, img, index) => {
    const state = imageState.get(imageKey(img, index));
    return total + (state && state.visible === false ? 0 : 1);
  }, 0);

  const syncCapThumbs = () => {
    const countEl = document.getElementById('capIncludedCount');
    if (countEl) countEl.textContent = visibleImageCount() + '/' + STUDY_IMAGES.length;

    document.querySelectorAll('.cap-thumb[data-img-index]').forEach(btn => {
      const index = parseInt(btn.dataset.imgIndex, 10);
      const img = STUDY_IMAGES[index];
      const state = imageState.get(imageKey(img, index)) || { visible: true, size: 1 };
      const visible = state.visible !== false;
      btn.classList.toggle('off', !visible);
      btn.setAttribute('aria-pressed', visible ? 'true' : 'false');
      const label = btn.querySelector('.cap-state');
      if (label) label.textContent = visible ? 'En reporte' : 'Oculta';
    });
  };

  const notifyImageChange = () => {
    syncCapThumbs();
    window.dispatchEvent(new CustomEvent('enclaii:report-images-updated'));
    window.dispatchEvent(new CustomEvent('enclaii:report-dirty'));
  };

  const escAttr = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const imgCell = (entry) => {
    if (!entry || !entry.img) return '';
    const state = imageState.get(imageKey(entry.img, entry.index)) || { visible: true, size: 1 };
    const span = clampInt(state.size, 1, currentImgCols || 1, 1);

    return '<span class="cell" data-img-index="' + entry.index + '" style="grid-column:span ' + span + '">' +
      '<img src="' + escAttr(entry.img.url) + '" alt="" title="' + escAttr(entry.img.titulo || 'Captura') + '" loading="lazy" onerror="var c=this.parentElement;if(c)c.remove();else this.remove()">' +
      '<span class="rep-img-tools" aria-hidden="false">' +
        '<button type="button" data-img-action="smaller" aria-label="Reducir captura" title="Reducir">-</button>' +
        '<button type="button" data-img-action="larger" aria-label="Agrandar captura" title="Agrandar">+</button>' +
        '<button type="button" data-img-action="remove" aria-label="Quitar captura del reporte" title="Quitar">x</button>' +
      '</span>' +
      '<span class="rep-img-size">' + span + 'x</span>' +
    '</span>';
  };

  // Rellena el grid de imágenes según la plantilla (columnas + nº de celdas)
  // Solo muestra celdas con imágenes reales del estudio; no muestra celdas vacías
  const fillImgs = (cols, count) => {
    if (!repImgs) return;
    const rawCols = parseInt(cols || 0, 10) || 0;
    const requested = parseInt(count || 0, 10) || 0;
    const safeCols = clampInt(cols, 1, 8, 4);
    currentImgCount = Math.max(0, requested);

    if (rawCols <= 0 && currentImgCount <= 0) {
      currentImagesEnabled = false;
      currentImgCols = 0;
      repImgs.style.display = 'none';
      repImgs.innerHTML = '';
      notifyImageChange();
      return;
    }

    currentImagesEnabled = true;
    currentImgCols = safeCols;

    const entries = STUDY_IMAGES
      .map((img, index) => ({ img, index }))
      .filter(entry => {
        const state = imageState.get(imageKey(entry.img, entry.index));
        return !state || state.visible !== false;
      });

    repImgs.style.display = entries.length ? 'grid' : 'none';
    repImgs.style.gridTemplateColumns = 'repeat(' + safeCols + ',1fr)';
    repImgs.innerHTML = entries.map(imgCell).join('');
    notifyImageChange();
  };

  const TEMPLATES = {
    colonoscopia: {
      titulo: 'INFORME DE COLONOSCOPIA', sub: 'COLONOSCOPIA', tipo: 'Colonoscopia',
      secciones: [
        {h: 'INDICACIÓN', tipo: 'p', ph: 'Motivo del estudio…'},
        {h: 'PREPARACIÓN', tipo: 'p', ph: 'Calidad de la preparación…'},
        {h: 'SEDACIÓN', tipo: 'p', ph: 'Tipo y nivel de sedación…'},
        {h: 'HALLAZGOS', tipo: 'ul', ph: 'Hallazgo por segmento (recto, sigmoides, colon…)'},
        {h: 'IMPRESIÓN DIAGNÓSTICA', tipo: 'p', ph: 'Diagnóstico…'},
        {h: 'PLAN Y RECOMENDACIONES', tipo: 'ul', ph: 'Recomendación…'},
        {h: 'OBSERVACIONES', tipo: 'p', ph: 'Observaciones adicionales…'},
      ],
    },
    gastroscopia: {
      titulo: 'INFORME DE GASTROSCOPIA', sub: 'GASTROSCOPIA', tipo: 'Gastroscopia',
      secciones: [
        {h: 'INDICACIÓN', tipo: 'p', ph: 'Motivo del estudio…'},
        {h: 'SEDACIÓN', tipo: 'p', ph: 'Tipo y nivel de sedación…'},
        {h: 'HALLAZGOS', tipo: 'ul', ph: 'Esófago / estómago / duodeno…'},
        {h: 'IMPRESIÓN DIAGNÓSTICA', tipo: 'p', ph: 'Diagnóstico…'},
        {h: 'PLAN Y RECOMENDACIONES', tipo: 'ul', ph: 'Recomendación…'},
        {h: 'OBSERVACIONES', tipo: 'p', ph: 'Observaciones adicionales…'},
      ],
    },
    duodenoscopia: {
      titulo: 'INFORME DE DUODENOSCOPIA', sub: 'DUODENOSCOPIA', tipo: 'Duodenoscopia',
      secciones: [
        {h: 'INDICACIÓN', tipo: 'p', ph: 'Motivo del estudio…'},
        {h: 'SEDACIÓN', tipo: 'p', ph: 'Tipo y nivel de sedación…'},
        {h: 'HALLAZGOS', tipo: 'ul', ph: 'Duodeno / papila / vía biliar…'},
        {h: 'IMPRESIÓN DIAGNÓSTICA', tipo: 'p', ph: 'Diagnóstico…'},
        {h: 'PLAN Y RECOMENDACIONES', tipo: 'ul', ph: 'Recomendación…'},
        {h: 'OBSERVACIONES', tipo: 'p', ph: 'Observaciones adicionales…'},
      ],
    },
    broncoscopia: {
      titulo: 'INFORME DE BRONCOSCOPIA', sub: 'BRONCOSCOPIA', tipo: 'Broncoscopia',
      secciones: [
        {h: 'INDICACIÓN', tipo: 'p', ph: 'Motivo del estudio…'},
        {h: 'SEDACIÓN', tipo: 'p', ph: 'Tipo y nivel de sedación…'},
        {h: 'HALLAZGOS', tipo: 'ul', ph: 'Árbol bronquial / tráquea / carina…'},
        {h: 'IMPRESIÓN DIAGNÓSTICA', tipo: 'p', ph: 'Diagnóstico…'},
        {h: 'PLAN Y RECOMENDACIONES', tipo: 'ul', ph: 'Recomendación…'},
        {h: 'OBSERVACIONES', tipo: 'p', ph: 'Observaciones adicionales…'},
      ],
    },
    blanco: {
      titulo: 'NUEVO REPORTE', sub: '', tipo: null,
      secciones: [
        {h: 'INTRODUCCIÓN', tipo: 'p', ph: 'Escribe aquí…'},
        {h: 'DESARROLLO', tipo: 'p', ph: 'Escribe aquí…'},
        {h: 'CONCLUSIÓN', tipo: 'p', ph: 'Escribe aquí…'},
      ],
    },
    // Plantillas de solo imágenes (sin texto en el cuerpo)
    img2:    { imgOnly: true, cols: 2, count: 4, tipo: null, secciones: [] },
    img3:    { imgOnly: true, cols: 3, count: 6, tipo: null, secciones: [] },
    img4:    { imgOnly: true, cols: 4, count: 8, tipo: null, secciones: [] },
    imgNone: { imgOnly: true, cols: 0, count: 0, tipo: null, secciones: [] },
  };

  // Lienzo lógico del encabezado (px). Las posiciones se guardan a esta escala
  // y se reescalan al ancho real de la hoja para que se vea idéntico (WYSIWYG).
  const PAGE_W = 760;
  const clamp = (v, a, b) => Math.max(a, Math.min(b, v));

  // Configuración por defecto: posición {x,y,w,h} de cada elemento del encabezado
  const defaultCfg = () => ({
    logoImg: null, anatImg: null, clinic: '', signName: '', signPos: 'center',
    headH: 121,
    logo: { x: 0,   y: 17, w: 86,  h: 86  },
    name: { x: 100, y: 28, w: 466, h: 64, fontSize: 21 },
    anat: { x: 672, y: 5,  w: 88,  h: 110 },
  });

  // Configuración persistida de cada plantilla (desde la base de datos)
  const PLANTILLAS_DB = <?php echo json_encode($plantillasDb ?? [], 15, 512) ?>;
  window.PLANTILLAS_DB = PLANTILLAS_DB;

  // Cada plantilla parte de su configuración por defecto y, si existe en BD,
  // se sobreescribe con la configuración / columnas guardadas.
  Object.keys(TEMPLATES).forEach(k => {
    TEMPLATES[k].cfg = defaultCfg();
    const db = PLANTILLAS_DB[k];
    if (db) {
      if (db.configuracion && typeof db.configuracion === 'object') {
        TEMPLATES[k].cfg = Object.assign(defaultCfg(), db.configuracion);
      }
      if (TEMPLATES[k].imgOnly) {
        if (db.columnas !== null && db.columnas !== undefined) TEMPLATES[k].cols = db.columnas;
        if (db.num_imagenes !== null && db.num_imagenes !== undefined) TEMPLATES[k].count = db.num_imagenes;
      }
    }
  });

  let currentKey = null;

  // Aplica la configuración de una plantilla al documento (reescalando al ancho real)
  const applyConfig = (cfg) => {
    if (!cfg || !repHeader) return;
    const s = (repHeader.clientWidth || PAGE_W) / PAGE_W;
    repHeader.style.height = (cfg.headH * s) + 'px';
    const place = (el, box) => {
      if (!el) return;
      el.style.left   = (box.x * s) + 'px';
      el.style.top    = (box.y * s) + 'px';
      el.style.width  = (box.w * s) + 'px';
      el.style.height = (box.h * s) + 'px';
    };
    if (repLogo) {
      repLogo.innerHTML = cfg.logoImg ? '<img src="' + cfg.logoImg + '" alt="Logo de la clínica">' : logoDefault;
      place(repLogo, cfg.logo);
    }
    if (repAnat) {
      // Prioridad: imagen propia subida por el usuario; si no, la del tipo de estudio
      const studyImg = tipoSel ? STUDY_IMG[tipoSel.value] : null;
      const anatSrc = cfg.anatImg || studyImg || null;
      repAnat.innerHTML = anatSrc ? '<img src="' + anatSrc + '" alt="Imagen lateral">' : anatDefault;
      place(repAnat, cfg.anat);
    }
    if (repClinicBox) place(repClinicBox, cfg.name);
    if (repClinic) {
      if (cfg.clinic) repClinic.textContent = cfg.clinic;
      repClinic.style.fontSize = (cfg.name.fontSize * s) + 'px';
    }
    if (repSignNm && cfg.signName) repSignNm.textContent = cfg.signName;
    if (repSign) repSign.setAttribute('data-pos', cfg.signPos || 'center');
  };

  const applyConfigToReportRoot = (root, cfg, targetWidthPx) => {
    if (!root || !cfg) return;
    const s = (targetWidthPx || PAGE_W) / PAGE_W;
    const header = root.querySelector('.rep-header');
    if (header) header.style.height = (cfg.headH * s) + 'px';
    const place = (selector, box) => {
      const el = root.querySelector(selector);
      if (!el || !box) return;
      el.style.left   = (box.x * s) + 'px';
      el.style.top    = (box.y * s) + 'px';
      el.style.width  = (box.w * s) + 'px';
      el.style.height = (box.h * s) + 'px';
    };
    place('#repLogo', cfg.logo);
    place('#repClinicBox', cfg.name);
    place('#repAnat', cfg.anat);

    const clinic = root.querySelector('#repClinicName');
    if (clinic) clinic.style.fontSize = (cfg.name.fontSize * s) + 'px';

    const anat = root.querySelector('#repAnat');
    if (anat) {
      const studyImg = tipoSel ? STUDY_IMG[tipoSel.value] : null;
      const anatSrc = cfg.anatImg || studyImg || null;
      if (anatSrc) {
        anat.innerHTML = '<img src="' + anatSrc + '" alt="Imagen lateral">';
      } else if (!anat.innerHTML.trim()) {
        anat.innerHTML = anatDefault;
      }
    }
  };

  window.getCurrentTemplateKey = () => currentKey;
  window.applyReportHeaderForPaper = (root, targetWidthPx) => {
    const tpl = currentKey ? TEMPLATES[currentKey] : null;
    if (!tpl || !tpl.cfg) return;
    applyConfigToReportRoot(root, tpl.cfg, targetWidthPx || (7.7 * 96));
  };

  // Reescalar el encabezado del documento si cambia el ancho de la ventana
  window.addEventListener('resize', () => { if (currentKey) applyConfig(TEMPLATES[currentKey].cfg); });

  // Al cambiar el tipo de estudio, actualizar la imagen lateral
  if (tipoSel) tipoSel.addEventListener('change', () => {
    if (currentKey) applyConfig(TEMPLATES[currentKey].cfg);
  });

  const render = (key, preserveContent) => {
    const tpl = TEMPLATES[key];
    if (!tpl) return;
    currentKey = key;
    if (tpl.tipo && tipoSel) {
      const opt = Array.from(tipoSel.options).find(o => o.text === tpl.tipo);
      if (opt) tipoSel.value = opt.value;
    }
    if (tpl.imgOnly) {
      // Documento de solo imágenes (sin texto en el cuerpo)
      cont.innerHTML = '';
      // Carga las imágenes reales del estudio según las columnas/celdas de la plantilla
      fillImgs(tpl.cols, tpl.count);
    } else {
      // Plantilla de informe: rejilla de 4 columnas con las imágenes reales del estudio
      // (si no hay imágenes, conserva la cuadrícula vacía por defecto)
      fillImgs(4, 8);
      // Si viene de un reporte guardado, conservar el contenido editado
      if (!preserveContent) {
        const ocultas = (tpl.cfg.secciones_ocultas || []);
        const borradas = (tpl.cfg.secciones_borradas || []);
        const nuevas = (tpl.cfg.secciones_nuevas || []);
        const seccionesFiltradas = tpl.secciones.filter(s => !ocultas.includes(s.h) && !borradas.includes(s.h));
        const todasSecciones = [...seccionesFiltradas, ...nuevas];
        cont.innerHTML = todasSecciones.map(s => {
          const head = '<h4 data-section="' + s.h + '">' + s.h + '<button type="button" class="sec-hide" title="Ocultar sección">×</button><button type="button" class="sec-delete" title="Borrar sección">Borrar</button></h4>';
          if (s.tipo === 'ul') {
            return head + '<ul><li contenteditable="true" data-ph="' + s.ph + '"></li></ul>';
          }
          return head + '<p contenteditable="true" data-ph="' + s.ph + '"></p>';
        }).join('') + '<button type="button" class="sec-add" id="secAddBtn">+ Añadir sección</button>';
      }
    }
    applyConfig(tpl.cfg);
  };

  // Plantilla inicial: la del reporte guardado, o la que corresponda al procedimiento del estudio
  let initialKey = tipoToKey(PRELOAD.tipo) || 'colonoscopia';
  if (REPORTE_DB && REPORTE_DB.plantilla_id) {
    const dbKey = Object.keys(PLANTILLAS_DB).find(k => PLANTILLAS_DB[k].id === REPORTE_DB.plantilla_id);
    if (dbKey) initialKey = dbKey;
  }
  // Si el reporte ya tiene contenido, solo aplica la plantilla sin borrar lo escrito
  render(initialKey, !!(REPORTE_DB && REPORTE_DB.contenido_html));
  // Si el procedimiento no coincide con una plantilla, reflejarlo igual en el selector
  if (PRELOAD.tipo && tipoSel) {
    const opt = Array.from(tipoSel.options).find(o => o.text.toLowerCase() === String(PRELOAD.tipo).toLowerCase());
    if (opt) tipoSel.value = opt.value;
  }

  // Manejar clic en botón de ocultar sección
  if (repImgs) {
    repImgs.addEventListener('click', (e) => {
      const actionBtn = e.target.closest('[data-img-action]');
      if (!actionBtn) return;
      e.preventDefault();
      const cell = actionBtn.closest('[data-img-index]');
      if (!cell) return;

      const index = parseInt(cell.dataset.imgIndex, 10);
      const img = STUDY_IMAGES[index];
      const key = imageKey(img, index);
      const state = imageState.get(key) || { visible: true, size: 1 };
      const action = actionBtn.dataset.imgAction;

      if (action === 'remove') {
        state.visible = false;
      } else if (action === 'larger') {
        state.size = clampInt((state.size || 1) + 1, 1, currentImgCols || 1, 1);
      } else if (action === 'smaller') {
        state.size = clampInt((state.size || 1) - 1, 1, currentImgCols || 1, 1);
      }

      imageState.set(key, state);
      fillImgs(currentImgCols, currentImgCount);
    });
  }

  document.querySelectorAll('.cap-thumb[data-img-index]').forEach(btn => {
    btn.addEventListener('click', () => {
      const index = parseInt(btn.dataset.imgIndex, 10);
      const img = STUDY_IMAGES[index];
      const key = imageKey(img, index);
      const state = imageState.get(key) || { visible: true, size: 1 };
      state.visible = state.visible === false;
      imageState.set(key, state);
      fillImgs(currentImgCols, currentImgCount);
    });
  });

  const restoreAllBtn = document.getElementById('imgRestoreAll');
  if (restoreAllBtn) {
    restoreAllBtn.addEventListener('click', () => {
      STUDY_IMAGES.forEach((img, index) => {
        const key = imageKey(img, index);
        const state = imageState.get(key) || { visible: true, size: 1 };
        state.visible = true;
        imageState.set(key, state);
      });
      fillImgs(currentImgCols, currentImgCount);
    });
  }
  syncCapThumbs();

  document.addEventListener('click', (e) => {
    const hideBtn = e.target.closest('.sec-hide');
    if (!hideBtn) return;
    const h4 = hideBtn.closest('h4');
    if (!h4) return;
    const sectionName = h4.dataset.section;
    if (!sectionName || !currentKey) return;

    const tpl = TEMPLATES[currentKey];
    if (!tpl || tpl.imgOnly) return;

    if (!tpl.cfg.secciones_ocultas) tpl.cfg.secciones_ocultas = [];
    if (!tpl.cfg.secciones_ocultas.includes(sectionName)) {
      tpl.cfg.secciones_ocultas.push(sectionName);
    }

    // Guardar la configuración en la BD
    fetch('<?php echo e(url('/plantillas')); ?>/' + encodeURIComponent(currentKey), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>, 'Accept': 'application/json' },
      body: JSON.stringify({ configuracion: tpl.cfg }),
    })
      .then(r => r.json())
      .then(d => {
        if (d.ok) {
          // Re-renderizar sin la sección oculta
          render(currentKey, false);
        }
      })
      .catch(() => {});
  });

  // Manejar clic en botón de borrar sección
  document.addEventListener('click', (e) => {
    const deleteBtn = e.target.closest('.sec-delete');
    if (!deleteBtn) return;
    const h4 = deleteBtn.closest('h4');
    if (!h4) return;
    const sectionName = h4.dataset.section;
    if (!sectionName || !currentKey) return;

    const tpl = TEMPLATES[currentKey];
    if (!tpl || tpl.imgOnly) return;

    if (!tpl.cfg.secciones_borradas) tpl.cfg.secciones_borradas = [];
    if (!tpl.cfg.secciones_borradas.includes(sectionName)) {
      tpl.cfg.secciones_borradas.push(sectionName);
    }

    // Guardar la configuración en la BD
    fetch('<?php echo e(url('/plantillas')); ?>/' + encodeURIComponent(currentKey), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>, 'Accept': 'application/json' },
      body: JSON.stringify({ configuracion: tpl.cfg }),
    })
      .then(r => r.json())
      .then(d => {
        if (d.ok) {
          // Re-renderizar sin la sección borrada
          render(currentKey, false);
        }
      })
      .catch(() => {});
  });

  // Manejar clic en botón de añadir sección
  document.addEventListener('click', (e) => {
    const addBtn = e.target.closest('.sec-add');
    if (!addBtn) return;
    if (!currentKey) return;

    const tpl = TEMPLATES[currentKey];
    if (!tpl || tpl.imgOnly) return;

    const sectionName = prompt('Nombre de la nueva sección:');
    if (!sectionName || !sectionName.trim()) return;

    if (!tpl.cfg.secciones_nuevas) tpl.cfg.secciones_nuevas = [];
    tpl.cfg.secciones_nuevas.push({
      h: sectionName.trim(),
      ph: 'Escribe aquí...',
      tipo: 'p'
    });

    // Guardar la configuración en la BD
    fetch('<?php echo e(url('/plantillas')); ?>/' + encodeURIComponent(currentKey), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>, 'Accept': 'application/json' },
      body: JSON.stringify({ configuracion: tpl.cfg }),
    })
      .then(r => r.json())
      .then(d => {
        if (d.ok) {
          // Re-renderizar con la nueva sección
          render(currentKey, false);
        }
      })
      .catch(() => {});
  });

  /* ===== Modal de configuración: editor visual de la hoja (arrastrar + redimensionar) ===== */
  const modal    = document.getElementById('cfgModal');
  const cfgSub   = document.getElementById('cfgSub');
  const cancel   = document.getElementById('cfgCancel');
  const applyBtn = document.getElementById('cfgApply');
  const logoIn   = document.getElementById('cfgLogoInput');
  const anatIn   = document.getElementById('cfgAnatInput');
  const clinicIn = document.getElementById('cfgClinic');
  const signIn   = document.getElementById('cfgSignName');
  const posSel   = document.getElementById('cfgSignPos');
  const imgCfgFields = document.getElementById('imgCfgFields');
  const imgColsIn    = document.getElementById('cfgImgCols');
  const imgCountIn   = document.getElementById('cfgImgCount');
  // Hoja de vista previa
  const sheet    = document.getElementById('cfgSheet');
  const headPv   = document.getElementById('cfgHead');
  const elLogo   = document.getElementById('elLogo');
  const elName   = document.getElementById('elName');
  const elAnat   = document.getElementById('elAnat');
  const elLogoIn = document.getElementById('elLogoIn');
  const elNameTx = document.getElementById('elNameTx');
  const elAnatIn = document.getElementById('elAnatIn');
  const signPv   = document.getElementById('cfgSignPv');
  const signPvTx = document.getElementById('cfgSignPvTx');
  const ANAT_SVG = elAnatIn ? elAnatIn.innerHTML : '';
  const LOGO_PH  = 'Logo de<br>la clínica';

  const ELS = { logo: elLogo, name: elName, anat: elAnat };
  let editingKey = null;     // plantilla que se está configurando
  let work = null;           // copia editable de la configuración
  let scale = 1;             // escala de la hoja (ancho real / PAGE_W)

  // El alto del encabezado siempre abarca el elemento más bajo
  const recomputeHead = () => {
    let maxB = 0;
    ['logo','name','anat'].forEach(k => { maxB = Math.max(maxB, work[k].y + work[k].h); });
    work.headH = Math.max(96, Math.ceil(maxB) + 6);
  };

  const drawEl = (el, box) => {
    el.style.left   = (box.x * scale) + 'px';
    el.style.top    = (box.y * scale) + 'px';
    el.style.width  = (box.w * scale) + 'px';
    el.style.height = (box.h * scale) + 'px';
  };

  // Pinta la hoja completa según `work`
  const renderWork = () => {
    if (!work || !sheet) return;
    recomputeHead();
    scale = (sheet.clientWidth || PAGE_W) / PAGE_W;
    headPv.style.height = (work.headH * scale) + 'px';
    drawEl(elLogo, work.logo);
    drawEl(elName, work.name);
    drawEl(elAnat, work.anat);
    elLogoIn.innerHTML = work.logoImg ? '<img src="' + work.logoImg + '" alt="Logo">' : LOGO_PH;
    // Imagen lateral: la propia subida o, si no, la del tipo de estudio de la plantilla
    const tplStudyImg = (editingKey && TEMPLATES[editingKey]) ? STUDY_IMG[TEMPLATES[editingKey].tipo] : null;
    const anatPv = work.anatImg || tplStudyImg || null;
    elAnatIn.innerHTML = anatPv ? '<img src="' + anatPv + '" alt="">' : ANAT_SVG;
    elNameTx.textContent = (work.clinic || '').trim() || 'Nombre de la clínica';
    elNameTx.style.fontSize = (work.name.fontSize * scale) + 'px';
    if (signPv)   signPv.setAttribute('data-pos', work.signPos || 'center');
    if (signPvTx) signPvTx.textContent = (work.signName || '').trim() || 'Dr. Nombre del médico';
  };

  // Selección visual
  const selectEl = (key) => {
    [elLogo, elName, elAnat].forEach(el => el.classList.remove('sel'));
    if (ELS[key]) ELS[key].classList.add('sel');
  };

  // Arrastrar / redimensionar con puntero
  let drag = null;
  const onMove = (e) => {
    if (!drag) return;
    const dx = (e.clientX - drag.sx) / scale;
    const dy = (e.clientY - drag.sy) / scale;
    const box = work[drag.key];
    if (drag.mode === 'move') {
      box.x = clamp(drag.ox + dx, 0, PAGE_W - box.w);
      box.y = Math.max(0, drag.oy + dy);
    } else {
      box.w = clamp(drag.ow + dx, 24, PAGE_W - box.x);
      box.h = Math.max(20, drag.oh + dy);
      if (drag.key === 'name') box.fontSize = Math.max(10, Math.round(box.h * 0.32));
    }
    renderWork();
  };
  const onUp = () => {
    drag = null;
    window.removeEventListener('pointermove', onMove);
    window.removeEventListener('pointerup', onUp);
  };
  const startDrag = (e, key, mode) => {
    e.preventDefault();
    const box = work[key];
    drag = { key, mode, sx: e.clientX, sy: e.clientY, ox: box.x, oy: box.y, ow: box.w, oh: box.h };
    selectEl(key);
    window.addEventListener('pointermove', onMove);
    window.addEventListener('pointerup', onUp);
  };
  Object.keys(ELS).forEach(key => {
    const el = ELS[key];
    if (!el) return;
    el.addEventListener('pointerdown', (e) => {
      const mode = e.target.classList.contains('rz') ? 'resize' : 'move';
      startDrag(e, key, mode);
    });
  });

  const openConfig = (key) => {
    const tpl = TEMPLATES[key];
    if (!tpl || !modal) return;
    editingKey = key;
    work = JSON.parse(JSON.stringify(tpl.cfg)); // copia profunda editable
    if (tpl.imgOnly) {
      if (cfgSub) cfgSub.textContent = 'Plantilla de imágenes';
      if (imgCfgFields) imgCfgFields.style.display = '';
      if (imgColsIn)  imgColsIn.value  = tpl.cols  || 2;
      if (imgCountIn) imgCountIn.value = tpl.count || 4;
    } else {
      const nombre = (list.querySelector('.tpl-main[data-tpl="' + key + '"] .tpl-t') || {}).textContent || '';
      if (cfgSub) cfgSub.textContent = 'Plantilla: ' + nombre.trim();
      if (imgCfgFields) imgCfgFields.style.display = 'none';
    }
    clinicIn.value = work.clinic || '';
    signIn.value   = work.signName || '';
    posSel.value   = work.signPos || 'center';
    if (logoIn) logoIn.value = '';
    if (anatIn) anatIn.value = '';
    selectEl('logo');
    modal.classList.add('open');
    requestAnimationFrame(renderWork); // la hoja ya tiene ancho cuando es visible
  };
  const closeConfig = () => modal && modal.classList.remove('open');

  const readImage = (input, set) => {
    const file = input.files && input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => set(reader.result);
    reader.readAsDataURL(file);
  };

  if (logoIn) logoIn.addEventListener('change', () => readImage(logoIn, d => { work.logoImg = d; renderWork(); }));
  if (anatIn) anatIn.addEventListener('change', () => readImage(anatIn, d => { work.anatImg = d; renderWork(); }));
  if (clinicIn) clinicIn.addEventListener('input', () => { work.clinic = clinicIn.value; renderWork(); });
  if (signIn)   signIn.addEventListener('input', () => { work.signName = signIn.value; renderWork(); });
  if (posSel)   posSel.addEventListener('change', () => { work.signPos = posSel.value; renderWork(); });
  if (cancel) cancel.addEventListener('click', closeConfig);
  if (modal) modal.addEventListener('click', (e) => { if (e.target === modal) closeConfig(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeConfig(); });
  window.addEventListener('resize', () => { if (modal && modal.classList.contains('open')) renderWork(); });

  if (applyBtn) applyBtn.addEventListener('click', () => {
    if (!editingKey || !work) return;
    work.clinic   = clinicIn.value.trim();
    work.signName = signIn.value.trim();
    work.signPos  = posSel.value;
    recomputeHead();
    const tpl = TEMPLATES[editingKey];
    tpl.cfg = work;
    if (tpl.imgOnly) {
      tpl.cols  = Math.max(1, Math.min(6,  parseInt(imgColsIn.value, 10)  || 2));
      tpl.count = Math.max(1, Math.min(24, parseInt(imgCountIn.value, 10) || tpl.cols));
      const btn = document.querySelector('.img-tpl[data-tpl="' + editingKey + '"]');
      if (btn) renderImgPreviewBtn(btn, tpl.cols, tpl.count);
    }
    if (currentKey === editingKey) {
      if (tpl.imgOnly) render(editingKey); else applyConfig(work);
    }
    // Persistir los cambios de la plantilla en la base de datos
    const payload = { configuracion: tpl.cfg };
    if (tpl.imgOnly) { payload.columnas = tpl.cols; payload.num_imagenes = tpl.count; }
    fetch('<?php echo e(url('/plantillas')); ?>/' + encodeURIComponent(editingKey), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': <?php echo json_encode(csrf_token(), 15, 512) ?>, 'Accept': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(r => r.json().catch(() => ({})).then(d => ({ ok: r.ok && d.ok, d })))
      .then(({ ok, d }) => {
        if (ok) {
          showTpltoast('Plantilla guardada');
        } else {
          showTpltoast('Error al guardar plantilla: ' + (d?.message || 'intenta de nuevo'), true);
        }
      })
      .catch(() => { showTpltoast('Error de red al guardar plantilla', true); });
    closeConfig();
  });

  // Aviso reutilizable (usa el toast de la página si existe)
  const showTpltoast = (msg, err) => {
    const t = document.getElementById('edToast');
    if (!t) return;
    t.textContent = msg;
    if (err) t.classList.add('err'); else t.classList.remove('err');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2200);
  };

  if (list) {
    list.addEventListener('click', (e) => {
      const cfgBtn = e.target.closest('.tpl-cfg');
      if (cfgBtn) { openConfig(cfgBtn.dataset.tplCfg); return; }
      const main = e.target.closest('.tpl-main');
      if (main) render(main.dataset.tpl);
    });
  }

  // Actualiza la mini-vista previa de una plantilla de imágenes
  const renderImgPreviewBtn = (btn, cols, count) => {
    const prev = btn.querySelector('.img-prev');
    if (!prev) return;
    if (!count) {
      prev.style.gridTemplateColumns = '1fr';
      prev.innerHTML = '<span style="background:none;border:1px dashed var(--stroke-strong)"></span>';
      return;
    }
    prev.style.gridTemplateColumns = 'repeat(' + cols + ',1fr)';
    prev.innerHTML = Array.from({ length: count }, () => '<span></span>').join('');
  };

  // Selección y edición de plantillas de imágenes (mismo editor visual)
  const imgGrid = document.querySelector('.img-grid');
  if (imgGrid) {
    imgGrid.addEventListener('click', (e) => {
      const cfgBtn = e.target.closest('.img-cfg');
      if (cfgBtn) { openConfig(cfgBtn.dataset.tplCfg); return; }
      const main = e.target.closest('.img-tpl');
      if (main) {
        document.querySelectorAll('.img-tpl').forEach(b => b.classList.remove('active'));
        main.classList.add('active');
        render(main.dataset.tpl);
      }
    });
  }

  // Toolbar de formato (execCommand sobre la selección del documento)
  const fmtButtons = Array.from(document.querySelectorAll('.ed-tb[data-cmd]'));
  // Comandos que tienen estado on/off (se pueden iluminar)
  const stateCmds = ['bold','italic','underline','strikeThrough','justifyLeft','justifyCenter','justifyRight','justifyFull','insertUnorderedList','insertOrderedList'];
  const docEl = document.querySelector('.ed-doc');
  const fontSizeInput = document.getElementById('fontSizeInput');
  const textColorBtn = document.getElementById('textColorBtn');
  const textColorInput = document.getElementById('textColorInput');
  const textColorSwatch = document.getElementById('textColorSwatch');
  const DEFAULT_TEXT_COLOR = '#111827';
  let savedDocRange = null;

  const elementFromNode = (node) => {
    if (!node) return null;
    return node.nodeType === Node.ELEMENT_NODE ? node : node.parentElement;
  };

  const rangeInsideDoc = (range) => {
    return !!(range && docEl && docEl.contains(range.commonAncestorContainer));
  };

  const selectionInsideDoc = () => {
    const sel = document.getSelection();
    return !!(sel && sel.rangeCount && rangeInsideDoc(sel.getRangeAt(0)));
  };

  const saveDocSelection = () => {
    const sel = document.getSelection();
    if (!sel || !sel.rangeCount || !selectionInsideDoc()) return;
    savedDocRange = sel.getRangeAt(0).cloneRange();
  };

  const restoreDocSelection = () => {
    if (!savedDocRange || !rangeInsideDoc(savedDocRange)) return false;
    const sel = document.getSelection();
    if (!sel) return false;
    sel.removeAllRanges();
    sel.addRange(savedDocRange);
    const editable = elementFromNode(savedDocRange.startContainer)?.closest('[contenteditable="true"]');
    if (editable) editable.focus({ preventScroll: true });
    return true;
  };

  const currentEditableTarget = () => {
    const sel = document.getSelection();
    let node = null;
    if (sel && sel.rangeCount && selectionInsideDoc()) {
      node = sel.getRangeAt(0).startContainer;
    } else if (savedDocRange && rangeInsideDoc(savedDocRange)) {
      node = savedDocRange.startContainer;
    }
    const el = elementFromNode(node);
    if (!el || !docEl || !docEl.contains(el)) return null;
    return el.closest('[contenteditable="true"], p, li, h4, h2, .doc-meta span') || el;
  };

  const inlineFontSizeTargetFromRange = (range) => {
    if (!range || !docEl) return null;
    const candidates = [range.startContainer, range.commonAncestorContainer, range.endContainer];

    for (const node of candidates) {
      let el = elementFromNode(node);
      while (el && el !== docEl) {
        if (el.style?.fontSize || el.getAttribute?.('size')) return el;
        el = el.parentElement;
      }
    }

    return null;
  };

  const currentFontSizeTarget = () => {
    const sel = document.getSelection();
    let range = null;
    if (sel && sel.rangeCount && selectionInsideDoc()) {
      range = sel.getRangeAt(0);
    } else if (savedDocRange && rangeInsideDoc(savedDocRange)) {
      range = savedDocRange;
    }

    return inlineFontSizeTargetFromRange(range) || currentEditableTarget();
  };

  const syncFontSizeInput = () => {
    if (!fontSizeInput || document.activeElement === fontSizeInput) return;
    const target = currentFontSizeTarget();
    if (!target) return;
    const size = Math.round(parseFloat(getComputedStyle(target).fontSize));
    if (size) fontSizeInput.value = String(size);
  };

  const colorToHex = (value) => {
    const raw = String(value || '').trim();
    if (/^#[0-9a-f]{6}$/i.test(raw)) return raw.toLowerCase();
    const rgb = raw.match(/^rgba?\(\s*(\d+),\s*(\d+),\s*(\d+)/i);
    if (!rgb) return null;
    return '#' + [1, 2, 3].map(i => {
      const part = Math.max(0, Math.min(255, parseInt(rgb[i], 10) || 0));
      return part.toString(16).padStart(2, '0');
    }).join('');
  };

  const currentTextColor = () => {
    let value = null;
    try { value = document.queryCommandValue('foreColor'); } catch (e) {}
    const commandColor = colorToHex(value);
    if (commandColor) return commandColor;

    const target = currentEditableTarget();
    if (!target) return DEFAULT_TEXT_COLOR;
    return colorToHex(getComputedStyle(target).color) || DEFAULT_TEXT_COLOR;
  };

  const syncTextColorInput = () => {
    if (!textColorInput || document.activeElement === textColorInput) return;
    const color = currentTextColor();
    textColorInput.value = color;
    if (textColorSwatch) textColorSwatch.style.backgroundColor = color;
  };

  const runDocCommand = (cmd, value = null) => {
    restoreDocSelection();
    if (!selectionInsideDoc()) return false;
    document.execCommand(cmd, false, value);
    saveDocSelection();
    refreshToolbar();
    if (docEl) docEl.dispatchEvent(new Event('input', { bubbles: true }));
    return true;
  };

  const refreshToolbar = () => {
    fmtButtons.forEach(btn => {
      const cmd = btn.dataset.cmd;
      if (!stateCmds.includes(cmd)) return;
      let on = false;
      try { on = document.queryCommandState(cmd); } catch (e) {}
      btn.classList.toggle('active', on);
    });
    syncFontSizeInput();
    syncTextColorInput();
  };

  fmtButtons.forEach(btn => {
    btn.addEventListener('mousedown', (e) => {
      e.preventDefault();
      runDocCommand(btn.dataset.cmd);
    });
  });

  // Mantener iluminados los botones según dónde esté el cursor/selección
  document.addEventListener('selectionchange', () => {
    if (selectionInsideDoc()) {
      saveDocSelection();
      refreshToolbar();
    }
  });
  if (docEl) {
    docEl.addEventListener('keyup', () => { saveDocSelection(); refreshToolbar(); });
    docEl.addEventListener('mouseup', () => { saveDocSelection(); refreshToolbar(); });
    docEl.addEventListener('focusout', () => fmtButtons.forEach(b => b.classList.remove('active')));
  }

  // Selector de tamaño de letra (en píxeles)
  if (fontSizeInput) {
    let savedRange = null;
    let savedMark = null;
    let activeTypingFontSize = null;

    const selectionInsideDoc = () => {
      const sel = document.getSelection();
      return sel && sel.anchorNode && docEl && docEl.contains(sel.anchorNode);
    };

    const findEditableTarget = (range) => {
      if (!range) return null;
      let node = range.commonAncestorContainer;
      if (node.nodeType === Node.TEXT_NODE) node = node.parentElement;
      return node.closest ? node.closest('[contenteditable="true"]') : null;
    };

    const unwrapIfEmpty = (el) => {
      const style = (el.getAttribute('style') || '').replace(/font-size:\s*[^;]+;?/gi, '').trim();
      const hasClass = el.className && el.className !== 'ed-tmp-select';
      if (!style && !hasClass && !el.getAttribute('size')) {
        const parent = el.parentNode;
        while (el.firstChild) parent.insertBefore(el.firstChild, el);
        parent.removeChild(el);
      } else {
        el.removeAttribute('size');
        el.setAttribute('style', style);
      }
    };

    const releaseSavedMark = () => {
      if (savedMark) {
        savedMark.classList.remove('ed-tmp-select');
        unwrapIfEmpty(savedMark);
        savedMark = null;
      }
      savedRange = null;
    };

    const markSelection = () => {
      const sel = document.getSelection();
      savedRange = null;
      savedMark = null;
      if (sel && sel.rangeCount && !sel.isCollapsed && selectionInsideDoc()) {
        try {
          const range = sel.getRangeAt(0);
          const mark = document.createElement('span');
          mark.className = 'ed-tmp-select';
          range.surroundContents(mark);
          savedMark = mark;
          const newRange = document.createRange();
          newRange.selectNodeContents(mark);
          sel.removeAllRanges();
          sel.addRange(newRange);
          savedRange = newRange.cloneRange();
        } catch (e) {
          savedRange = sel.getRangeAt(0).cloneRange();
        }
      } else if (sel && sel.rangeCount) {
        savedRange = sel.getRangeAt(0).cloneRange();
      }
    };

    const applyFontSize = (px) => {
      const size = parseInt(px, 10);
      if (isNaN(size) || size < 8 || size > 72) {
        fontSizeInput.value = String(activeTypingFontSize || 14);
        releaseSavedMark();
        return;
      }
      activeTypingFontSize = size;
      fontSizeInput.value = String(size);

      if (savedMark) {
        savedMark.querySelectorAll('span, font').forEach(unwrapIfEmpty);
        savedMark.classList.remove('ed-tmp-select');
        savedMark.style.fontSize = size + 'px';
        const sel = document.getSelection();
        const range = document.createRange();
        range.selectNodeContents(savedMark);
        sel.removeAllRanges();
        sel.addRange(range);
        savedMark = null;
        savedRange = null;
        saveDocSelection();
        refreshToolbar();
        if (docEl) docEl.dispatchEvent(new Event('input', { bubbles: true }));
        return;
      }

      const target = findEditableTarget(savedRange) || docEl.querySelector('[contenteditable="true"]');
      if (!target) return;
      target.focus();

      const sel = document.getSelection();
      if (savedRange) {
        sel.removeAllRanges();
        sel.addRange(savedRange);
      }

      const range = sel.rangeCount ? sel.getRangeAt(0) : null;
      if (!range) return;

      if (range.collapsed) {
        saveDocSelection();
        refreshToolbar();
        if (docEl) docEl.dispatchEvent(new Event('input', { bubbles: true }));
        return;
      } else {
        try {
          const span = document.createElement('span');
          span.style.fontSize = size + 'px';
          range.surroundContents(span);
        } catch (err) {
          const fragment = range.extractContents();
          fragment.querySelectorAll('span, font').forEach(unwrapIfEmpty);
          const span = document.createElement('span');
          span.style.fontSize = size + 'px';
          while (fragment.firstChild) span.appendChild(fragment.firstChild);
          range.insertNode(span);
        }
      }
      saveDocSelection();
      refreshToolbar();
      if (docEl) docEl.dispatchEvent(new Event('input', { bubbles: true }));
    };

    // Solo permitir números
    const insertTextWithFontSize = (text) => {
      if (!text || !selectionInsideDoc()) return false;
      const size = parseInt(activeTypingFontSize, 10);
      if (isNaN(size) || size < 8 || size > 72) return false;

      const sel = document.getSelection();
      if (!sel || !sel.rangeCount) return false;

      const range = sel.getRangeAt(0);
      if (!range.collapsed) range.deleteContents();

      const span = document.createElement('span');
      span.style.fontSize = size + 'px';
      span.textContent = text;
      range.insertNode(span);

      const nextRange = document.createRange();
      nextRange.setStartAfter(span);
      nextRange.collapse(true);
      sel.removeAllRanges();
      sel.addRange(nextRange);
      saveDocSelection();
      refreshToolbar();
      if (docEl) docEl.dispatchEvent(new Event('input', { bubbles: true }));
      return true;
    };

    fontSizeInput.addEventListener('input', (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });

    // Guardar y marcar la selección antes de que el input robe el foco
    fontSizeInput.addEventListener('mousedown', markSelection);
    fontSizeInput.addEventListener('focus', () => {
      if (!savedRange && !savedMark) markSelection();
    });

    fontSizeInput.addEventListener('change', (e) => applyFontSize(e.target.value));
    fontSizeInput.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter') return;
      e.preventDefault();
      applyFontSize(e.target.value);
      const target = findEditableTarget(savedRange) || docEl.querySelector('[contenteditable="true"]');
      if (target) target.focus();
    });

    if (docEl) {
      docEl.addEventListener('beforeinput', (e) => {
        if (e.inputType !== 'insertText' || e.isComposing) return;
        if (!activeTypingFontSize || document.activeElement === fontSizeInput) return;
        e.preventDefault();
        insertTextWithFontSize(e.data);
      });
    }
  }

  const applyTextColor = (color) => {
    const nextColor = colorToHex(color);
    if (!nextColor) return;
    restoreDocSelection();
    if (!selectionInsideDoc()) return;
    document.execCommand('styleWithCSS', false, true);
    document.execCommand('foreColor', false, nextColor);
    saveDocSelection();
    if (textColorInput) textColorInput.value = nextColor;
    if (textColorSwatch) textColorSwatch.style.backgroundColor = nextColor;
    refreshToolbar();
    if (docEl) docEl.dispatchEvent(new Event('input', { bubbles: true }));
  };

  if (textColorBtn && textColorInput) {
    textColorBtn.addEventListener('mousedown', (e) => {
      e.preventDefault();
      saveDocSelection();
      textColorInput.click();
    });
    textColorInput.addEventListener('input', (e) => applyTextColor(e.target.value));
    textColorInput.addEventListener('change', (e) => applyTextColor(e.target.value));
  }

  // Highlight (fondo amarillo)
  const underlineColorBtn = document.getElementById('underlineColorBtn');
  if (underlineColorBtn) {
    underlineColorBtn.addEventListener('mousedown', (e) => {
      e.preventDefault();
      restoreDocSelection();
      if (!selectionInsideDoc()) return;
      document.execCommand('styleWithCSS', false, true);
      document.execCommand('hiliteColor', false, '#ffff00');
      saveDocSelection();
      refreshToolbar();
      if (docEl) docEl.dispatchEvent(new Event('input', { bubbles: true }));
    });
  }
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const form = document.getElementById('chatForm');
  const input = document.getElementById('chatText');
  const msgs = document.getElementById('chatMsgs');
  const chips = document.getElementById('chatChips');
  if (!form || !msgs) return;

  const chatUrl = "<?php echo e(route('ia-reportes.chat.post')); ?>";
  const csrf = "<?php echo e(csrf_token()); ?>";
  const docEl = document.querySelector('.ed-doc');

  /* ===== Aplicar ediciones de la IA directamente en el reporte ===== */
  const escDoc = s => String(s ?? '').replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]));
  const norm = s => (s || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
  const SECC = {
    indicacion:           { kw: 'INDICACION',    tipo: 'p'  },
    sedacion:             { kw: 'SEDACION',      tipo: 'p'  },
    hallazgos:            { kw: 'HALLAZGOS',     tipo: 'ul' },
    impresion_diagnostica:{ kw: 'IMPRESION',     tipo: 'p'  },
    plan_recomendaciones: { kw: 'PLAN',          tipo: 'ul' },
    observaciones:        { kw: 'OBSERVACIONES', tipo: 'p'  },
  };
  const findHeading = kw => Array.from(docEl ? docEl.querySelectorAll('h4') : []).find(h => norm(h.textContent).includes(kw));
  const nextOfType = (h4, tag) => {
    let n = h4 ? h4.nextElementSibling : null;
    while (n && n.tagName !== 'H4') { if (n.tagName === tag.toUpperCase()) return n; n = n.nextElementSibling; }
    return null;
  };
  const flash = el => {
    if (!el) return;
    el.style.transition = 'background-color .3s';
    el.style.backgroundColor = 'rgba(56,199,244,.2)';
    setTimeout(() => { el.style.backgroundColor = ''; }, 1300);
  };
  const applyAccion = a => {
    const cfg = SECC[a.seccion];
    if (!cfg || !docEl) return false;
    const h4 = findHeading(cfg.kw);
    if (!h4) return false;
    if (cfg.tipo === 'ul') {
      const ul = nextOfType(h4, 'ul');
      if (!ul) return false;
      const items = Array.isArray(a.contenido) ? a.contenido : [a.contenido];
      const lis = items.filter(x => String(x).trim()).map(x => '<li contenteditable="true">' + escDoc(x) + '</li>').join('');
      if (a.operacion === 'agregar') ul.insertAdjacentHTML('beforeend', lis);
      else ul.innerHTML = lis;
      flash(ul);
    } else {
      const p = nextOfType(h4, 'p');
      if (!p) return false;
      const txt = Array.isArray(a.contenido) ? a.contenido.join('. ') : a.contenido;
      if (a.operacion === 'agregar') p.textContent = (p.textContent + ' ' + txt).trim();
      else p.textContent = txt;
      flash(p);
    }
    return true;
  };
  const applyAcciones = acciones => (acciones || []).reduce((n, a) => n + (applyAccion(a) ? 1 : 0), 0);

  const scrollDown = () => { msgs.scrollTop = msgs.scrollHeight; };

  const addMsg = (text, who) => {
    const el = document.createElement('div');
    el.className = 'chat-msg ' + who;
    el.textContent = text;
    msgs.appendChild(el);
    scrollDown();
    return el;
  };

  const showTyping = () => {
    const el = document.createElement('div');
    el.className = 'chat-msg ai';
    el.innerHTML = '<span class="chat-typing"><i></i><i></i><i></i></span>';
    msgs.appendChild(el);
    scrollDown();
    return el;
  };

  const typeInto = (el, text, done) => {
    el.textContent = '';
    const caret = document.createElement('span');
    caret.className = 'caret';
    el.appendChild(caret);
    let i = 0;
    const tick = () => {
      if (i < text.length) {
        caret.insertAdjacentText('beforebegin', text.charAt(i));
        i++;
        scrollDown();
        setTimeout(tick, 14 + Math.random() * 28);
      } else {
        caret.remove();
        if (done) done();
      }
    };
    tick();
  };

  const replyFor = (q) => {
    const t = q.toLowerCase();
    if (t.includes('hallazgo'))
      return 'Propuesta de hallazgos: describe el aspecto de la mucosa y cualquier lesión por segmento. Puedo redactarlos si me das los datos del estudio.';
    if (t.includes('recomend'))
      return 'Recomendaciones sugeridas: tratamiento, pruebas complementarias y control endoscópico según hallazgos.';
    if (t.includes('diagn'))
      return 'Para sugerir una impresión diagnóstica necesito los hallazgos principales. ¿Quieres que la redacte con lo que ya escribiste?';
    if (t.includes('redacc') || t.includes('mejora'))
      return 'Puedo mejorar la redacción de cualquier sección: dime cuál y la reescribo en un tono clínico claro.';
    return 'Entendido. Preparé una propuesta para "' + q + '". Cuando conectes los datos del estudio la integraré en el reporte.';
  };

  const send = async (text) => {
    if (!text.trim()) return;
    addMsg(text, 'me');
    const typingEl = showTyping();
    try {
      const res = await fetch(chatUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf,
        },
        body: JSON.stringify({
          message: text,
          contexto: docEl ? docEl.innerText.slice(0, 6000) : '',
        }),
      });
      const data = await res.json();
      typingEl.remove();
      const aiEl = addMsg('', 'ai');
      if (!res.ok || !data.ok) {
        typeInto(aiEl, 'No pude responder: ' + (data.message || 'error de conexión.'));
      } else {
        const cambios = applyAcciones(data.acciones);
        let respuesta = data.respuesta || '...';
        if (cambios > 0) {
          respuesta += '\n\nActualicé ' + cambios + (cambios === 1 ? ' sección' : ' secciones') + ' del reporte.';
        }
        typeInto(aiEl, respuesta);
      }
    } catch (e) {
      typingEl.remove();
      typeInto(addMsg('', 'ai'), replyFor(text));
    }
  };

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const v = input.value;
    input.value = '';
    send(v);
  });

  if (chips) {
    chips.addEventListener('click', (e) => {
      const b = e.target.closest('.chat-chip');
      if (b) send(b.textContent.trim());
    });
  }

  const greeting = 'Hola, soy ENCLAII. Tú redactas el reporte y yo te ayudo: puedo proponer hallazgos, recomendaciones o mejorar la redacción de cualquier sección. ¿Empezamos?';
  const greetEl = addMsg('', 'ai');
  setTimeout(() => typeInto(greetEl, greeting), 400);
})();
</script>

<script>
/* ===== Pestañas de Plantillas (Informe / Imágenes) + layout de imágenes ===== */
(function(){
  const tabs = document.querySelectorAll('.tpl-tab');
  const panes = { informe: document.getElementById('paneInforme'), imagenes: document.getElementById('paneImagenes') };
  tabs.forEach(tab => tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    Object.values(panes).forEach(p => p && p.classList.remove('active'));
    const target = panes[tab.dataset.tab];
    if (target) target.classList.add('active');
  }));
})();
</script>

<script>
/* ===== Hallazgos del doctor: cargar lista, insertar en sección HALLAZGOS del reporte ===== */
(function(){
  const chipsEl   = document.getElementById('hzChips');
  const newInput  = document.getElementById('hzNewInput');
  const addBtn    = document.getElementById('hzAddBtn');
  const countEl   = document.getElementById('hzCount');
  if (!chipsEl) return;

  const LIST_URL   = "<?php echo e(route('ia-reportes.hallazgos-lista')); ?>";
  const CREATE_URL = "<?php echo e(route('ia-reportes.hallazgos-crear')); ?>";
  const CSRF       = "<?php echo e(csrf_token()); ?>";
  const ESTUDIO_ID = <?php echo json_encode($estudio?->id, 15, 512) ?>;

  let allHallazgos = [];

  /* --- Buscar la sección HALLAZGOS dentro del documento --- */
  function findHallazgosUl() {
    const docEl = document.querySelector('.ed-doc');
    if (!docEl) return null;
    const headings = docEl.querySelectorAll('h4');
    for (const h of headings) {
      const txt = (h.textContent || '').toUpperCase().replace(/Á/g,'A').replace(/Ó/g,'O');
      if (txt.includes('HALLAZGO')) {
        let next = h.nextElementSibling;
        while (next && next.tagName !== 'H4') {
          if (next.tagName === 'UL') return next;
          next = next.nextElementSibling;
        }
      }
    }
    return null;
  }

  /* --- Agregar un <li> con el nombre del hallazgo a la sección HALLAZGOS --- */
  function addToReport(nombre) {
    const ul = findHallazgosUl();
    if (!ul) return;
    const items = Array.from(ul.querySelectorAll('li')).map(li => (li.textContent || '').trim());
    if (items.includes(nombre)) return;
    const placeholder = ul.querySelector('li[data-ph]');
    if (placeholder && !placeholder.textContent.trim()) {
      placeholder.textContent = nombre;
    } else {
      const li = document.createElement('li');
      li.setAttribute('contenteditable', 'true');
      li.textContent = nombre;
      ul.appendChild(li);
    }
    flash(ul);
  }

  function flash(el) {
    if (!el) return;
    el.style.transition = 'background-color .3s';
    el.style.backgroundColor = 'rgba(56,199,244,.2)';
    setTimeout(() => { el.style.backgroundColor = ''; }, 1300);
  }

  function updateCount() {
    if (!countEl) return;
    const ul = findHallazgosUl();
    const n = ul ? Array.from(ul.querySelectorAll('li')).filter(li => (li.textContent || '').trim()).length : 0;
    const strong = countEl.querySelector('strong');
    if (strong) strong.textContent = n;
  }

  function renderChips() {
    chipsEl.innerHTML = '';
    allHallazgos.forEach(h => {
      const chip = document.createElement('span');
      chip.className = 'hz-chip' + (h.es_critico ? ' critico' : '');
      chip.dataset.id = h.id;
      chip.innerHTML = '<span class="hz-dot"></span>' + h.nombre;
      chip.addEventListener('click', () => {
        addToReport(h.nombre);
        updateCount();
      });
      chipsEl.appendChild(chip);
    });
  }

  async function loadHallazgos() {
    try {
      const url = ESTUDIO_ID ? LIST_URL + '?estudio_id=' + ESTUDIO_ID : LIST_URL;
      const res = await fetch(url, { headers: { Accept: 'application/json' } });
      const data = await res.json();
      if (!data.ok) return;
      allHallazgos = data.data.todos;
      renderChips();
      updateCount();
    } catch (e) {
      console.error('Error cargando hallazgos:', e);
    }
  }

  async function crearHallazgo() {
    const nombre = (newInput.value || '').trim();
    if (!nombre) return;

    addBtn.disabled = true;
    try {
      const res = await fetch(CREATE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify({ nombre: nombre }),
      });
      const data = await res.json();
      if (data.ok) {
        const h = data.data;
        if (!allHallazgos.find(x => x.id === h.id)) {
          allHallazgos.push(h);
          allHallazgos.sort((a, b) => a.nombre.localeCompare(b.nombre));
          renderChips();
        }
        addToReport(h.nombre);
        updateCount();
        newInput.value = '';
      }
    } catch (e) {
      console.error('Error creando hallazgo:', e);
    } finally {
      addBtn.disabled = false;
    }
  }

  if (addBtn) addBtn.addEventListener('click', crearHallazgo);
  if (newInput) newInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); crearHallazgo(); }
  });

  /* --- Extraer hallazgos de la sección HALLAZGOS del reporte al guardar --- */
  window.getSelectedHallazgos = () => {
    const ul = findHallazgosUl();
    if (!ul) return [];
    return Array.from(ul.querySelectorAll('li'))
      .map(li => (li.textContent || '').trim())
      .filter(t => t.length > 0);
  };

  /* --- Actualizar contador cuando el doctor edita la lista --- */
  document.addEventListener('keyup', (e) => {
    if (e.target.closest && e.target.closest('.ed-doc ul li')) updateCount();
  });

  loadHallazgos();
})();
</script>

<script>
/* ===== Vista Previa + Guardar reporte ===== */
(function(){
  const doc      = document.querySelector('.ed-doc');
  const btnPv    = document.getElementById('btnPreview');
  const btnSave  = document.getElementById('btnGuardar');
  const pvModal  = document.getElementById('previewModal');
  const pvPaper  = document.getElementById('pvPaper');
  const pvClose  = document.getElementById('pvClose');
  const pvPrint  = document.getElementById('pvPrint');
  const statusEl = document.getElementById('edStatus');
  const toast    = document.getElementById('edToast');
  const STORAGE_KEY = 'enclaii_reporte_borrador';

  // Función para cambiar estado a borrador cuando hay cambios
  const markAsBorrador = () => {
    if (statusEl && statusEl.classList.contains('guardado')) {
      statusEl.textContent = 'Borrador';
      statusEl.classList.remove('guardado');
      statusEl.classList.add('borrador');
    }
  };
  window.addEventListener('enclaii:report-dirty', markAsBorrador);

  // Aviso flotante
  let toastTimer = null;
  const showToast = (msg, isErr) => {
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.toggle('err', !!isErr);
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 2600);
  };

  const fitPreviewPaper = () => {
    if (!pvModal || !pvPaper || !pvModal.classList.contains('open')) return;
    const scroll = pvModal.querySelector('.pv-scroll');
    if (!scroll) return;

    pvPaper.style.zoom = '1';
    pvPaper.style.transform = 'none';
    pvPaper.style.width = '8.5in';
    pvPaper.style.height = '11in';
    pvPaper.style.maxWidth = 'none';
    pvPaper.style.padding = '.4in';

    const availableW = Math.max(280, scroll.clientWidth - 40);
    const availableH = Math.max(220, scroll.clientHeight - 40);
    const naturalW = pvPaper.offsetWidth || pvPaper.scrollWidth || 1;
    const naturalH = pvPaper.offsetHeight || pvPaper.scrollHeight || 1;
    const scale = Math.min(1, availableW / naturalW, availableH / naturalH);

    if ('zoom' in pvPaper.style) {
      pvPaper.style.zoom = String(scale);
    } else {
      pvPaper.style.transform = 'scale(' + scale + ')';
    }
  };

  const resetPreviewPrint = () => {
    document.documentElement.style.removeProperty('--report-print-scale');
    document.documentElement.style.removeProperty('--report-print-width');
    requestAnimationFrame(fitPreviewPaper);
  };

  const preparePreviewPrint = () => {
    if (!pvPaper || !pvPaper.firstElementChild) return false;

    pvPaper.style.zoom = '1';
    pvPaper.style.transform = 'none';
    pvPaper.style.width = '8.5in';
    pvPaper.style.height = '11in';
    pvPaper.style.maxWidth = 'none';
    pvPaper.style.padding = '.4in';

    const printable = pvPaper.firstElementChild;
    printable.style.transform = 'none';
    printable.style.transformOrigin = 'top left';
    printable.style.width = '7.7in';
    printable.style.padding = '0';
    printable.style.display = 'flex';
    printable.style.flexDirection = 'column';
    printable.style.minHeight = '10.2in';

    const targetW = 7.7 * 96;
    const targetH = 10.2 * 96;
    const naturalW = Math.max(targetW, printable.scrollWidth || targetW);
    const naturalH = Math.max(1, printable.scrollHeight || targetH);
    const scale = Math.max(0.35, Math.min(1, targetW / naturalW, targetH / naturalH));
    const scaledWidth = 7.7 / scale;

    document.documentElement.style.setProperty('--report-print-scale', String(scale));
    document.documentElement.style.setProperty('--report-print-width', scaledWidth + 'in');
    printable.style.width = scaledWidth + 'in';
    printable.style.transform = 'scale(' + scale + ')';

    return true;
  };

  const printPreview = () => {
    if (!pvModal || !pvModal.classList.contains('open')) {
      openPreview();
    }

    requestAnimationFrame(() => {
      preparePreviewPrint();
      setTimeout(() => window.print(), 80);
    });
  };

  /* ---- Vista Previa ---- */
  const openPreview = () => {
    if (!doc || !pvPaper || !pvModal) return;
    pvPaper.style.zoom = '1';
    pvPaper.style.transform = 'none';
    pvPaper.style.width = '8.5in';
    pvPaper.style.height = '11in';
    pvPaper.style.maxWidth = 'none';
    pvPaper.style.padding = '.4in';
    
    // Construir HTML limpio sin botones de edición
    const clone = doc.cloneNode(true);
    clone.classList.remove('rise', 'd2', 'card');
    
    // Remover contenteditable y campos vacíos
    clone.querySelectorAll('[contenteditable]').forEach(el => {
      el.removeAttribute('contenteditable');
      const txt = (el.textContent || '').trim();
      if (!txt && el.closest('.doc-meta')) {
        el.textContent = '-';
      } else if (!txt && el.closest('#docSections')) {
        el.remove();
      }
    });
    
    // Remover TODOS los botones (enfoque más amplio)
    clone.querySelectorAll('button, .rep-img-tools, .rep-img-size').forEach(el => el.remove());
    
    // Aplicar estilos de impresión
    clone.style.position = 'relative';
    clone.style.width = '7.7in';
    clone.style.minHeight = '10.2in';
    clone.style.height = 'auto';
    clone.style.display = 'flex';
    clone.style.flexDirection = 'column';
    clone.style.transform = 'none';
    clone.style.background = '#fff';
    clone.style.color = '#000';
    clone.style.padding = '0';
    clone.style.margin = '0';
    clone.style.maxWidth = 'none';
    clone.style.boxShadow = 'none';
    clone.style.border = '0';
    clone.style.boxSizing = 'border-box';
    clone.style.fontSize = '11px';
    clone.style.lineHeight = '1.3';
    if (window.applyReportHeaderForPaper) {
      window.applyReportHeaderForPaper(clone, 7.7 * 96);
    }
    
    pvPaper.innerHTML = '';
    pvPaper.appendChild(clone);
    pvModal.classList.add('open');
    document.body.style.overflow = 'hidden';
    preparePreviewPrint();
    requestAnimationFrame(fitPreviewPaper);
  };
  const closePreview = () => {
    if (!pvModal) return;
    pvModal.classList.remove('open');
    document.body.style.overflow = '';
  };
  if (btnPv)   btnPv.addEventListener('click', openPreview);
  if (pvClose) pvClose.addEventListener('click', closePreview);
  if (pvPrint) pvPrint.addEventListener('click', printPreview);
  if (pvModal) pvModal.addEventListener('click', (e) => { if (e.target === pvModal) closePreview(); });
  window.addEventListener('resize', () => requestAnimationFrame(fitPreviewPaper));
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && pvModal && pvModal.classList.contains('open')) closePreview();
  });

  // Sistema de escalado para impresión en una sola hoja
  let originalTransform = '';
  let originalWidth = '';
  const fitToOnePage = () => {
    if (pvPaper) {
      if (!pvPaper.firstElementChild) openPreview();
      if (pvModal) pvModal.classList.add('open');
    }

    if (preparePreviewPrint()) {
      return;
    }

    if (!doc) return;
    originalTransform = doc.style.transform || '';
    originalWidth = doc.style.width || '';
    doc.style.transform = '';
    const targetHeight = 10.2 * 96; // 10.2in a 96dpi
    const actualHeight = doc.scrollHeight;
    if (actualHeight > targetHeight) {
      const scale = targetHeight / actualHeight;
      doc.style.transform = 'scale(' + scale + ')';
      doc.style.transformOrigin = 'top left';
      doc.style.width = (7.7 / scale) + 'in';
    }
  };
  const restore = () => {
    resetPreviewPrint();

    if (!doc) return;
    doc.style.transform = originalTransform;
    doc.style.transformOrigin = '';
    doc.style.width = originalWidth;
  };
  window.addEventListener('beforeprint', fitToOnePage);
  window.addEventListener('afterprint', restore);

  // Indicador en tiempo real de si cabe en una hoja.
  // El ajuste de escala queda solo para vista previa/impresion, no para el editor.
  const checkPageFit = () => {
    if (!doc) return;
    const targetHeight = 10.2 * 96; // 10.2in a 96dpi
    doc.style.transform = '';
    doc.style.transformOrigin = '';
    doc.style.width = '';
    const actualHeight = doc.scrollHeight;
    const indicator = document.getElementById('pageFitIndicator');
    if (indicator) {
      if (actualHeight > targetHeight) {
        indicator.textContent = 'Excede 1 hoja';
        indicator.style.color = '#ef4444';
      } else {
        indicator.textContent = 'Cabe en 1 hoja';
        indicator.style.color = '#16a34a';
      }
    }
  };
  // Verificar cuando el usuario escribe
  if (doc) {
    doc.addEventListener('input', checkPageFit);
    doc.addEventListener('keyup', checkPageFit);
  }
  window.addEventListener('enclaii:report-images-updated', () => setTimeout(checkPageFit, 60));
  // Verificar inicialmente
  setTimeout(checkPageFit, 500);

  /* ---- Guardar reporte (persistido en BD y ligado al estudio) ---- */
  const ESTUDIO_ID = <?php echo json_encode($estudio?->id, 15, 512) ?>;
  const SAVE_URL   = <?php echo json_encode(route('ia-reportes.guardar'), 15, 512) ?>;
  const CSRF       = <?php echo json_encode(csrf_token(), 15, 512) ?>;
  let savedReporteId = <?php echo json_encode($reporte?->id, 15, 512) ?>;

  const collectData = () => ({
    tipo:   (document.getElementById('edTipo')?.value || ''),
    htmlDoc: doc ? doc.innerHTML : '',
    guardadoEn: new Date().toISOString(),
  });

  // Contenido del reporte: HTML para preservar formato y texto plano para búsquedas
  const collectContenido = () => {
    const sec = document.getElementById('docSections');
    let html = '';
    let txt = '';
    if (sec) {
      // Clonar y remover botones de edición antes de guardar
      const secClone = sec.cloneNode(true);
      secClone.querySelectorAll('.sec-add, .sec-hide, .sec-delete, .rep-img-tools, .rep-img-size').forEach(el => el.remove());
      html = (secClone.innerHTML || '').trim();
      txt = (secClone.innerText || '').trim();
    }
    if (!html && doc) {
      const docClone = doc.cloneNode(true);
      docClone.querySelectorAll('.sec-add, .sec-hide, .sec-delete, .rep-img-tools, .rep-img-size').forEach(el => el.remove());
      html = (docClone.innerHTML || '').trim();
      txt = (docClone.innerText || '').trim();
    }
    return { html, txt };
  };

  const saveReport = () => {
    // Respaldo local del borrador
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(collectData())); } catch (e) {}

    if (!ESTUDIO_ID) {
      showToast('No hay un estudio asociado; el reporte no se guardó en el sistema', true);
      return;
    }
    const contenido = collectContenido();
    if (!contenido.txt) {
      showToast('Escribe el contenido del reporte antes de guardar', true);
      return;
    }

    const tplKey = window.getCurrentTemplateKey ? window.getCurrentTemplateKey() : null;
    const db = window.PLANTILLAS_DB || {};
    const plantillaId = tplKey && db[tplKey] ? db[tplKey].id : null;
    const hallazgosSeleccionados = window.getSelectedHallazgos ? window.getSelectedHallazgos() : [];
    const imagenesConfig = window.getReportImagesConfig ? window.getReportImagesConfig() : null;

    if (btnSave) btnSave.disabled = true;
    fetch(SAVE_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({
        estudio_id: ESTUDIO_ID,
        reporte_id: savedReporteId,
        contenido_texto: contenido.txt,
        contenido_html: contenido.html,
        imagenes_config: imagenesConfig,
        plantilla_id: plantillaId,
        hallazgos: hallazgosSeleccionados,
      }),
    })
      .then(r => r.json())
      .then(d => {
        if (d && d.ok) {
          savedReporteId = d.reporte_id;
          if (statusEl) {
            statusEl.textContent = 'Guardado';
            statusEl.classList.remove('borrador');
            statusEl.classList.add('guardado');
          }
          showToast(d.message || 'Reporte guardado');
        } else {
          showToast((d && d.message) || 'No se pudo guardar el reporte', true);
        }
      })
      .catch(() => showToast('No se pudo guardar el reporte', true))
      .finally(() => { if (btnSave) btnSave.disabled = false; });
  };
  if (btnSave) btnSave.addEventListener('click', saveReport);

  /* Auto-abrir selector de estudios al llegar desde el widget del dashboard */
  if (new URLSearchParams(window.location.search).get('from') === 'widget') {
    const edEstudioSel = document.getElementById('edEstudioSel');
    if (edEstudioSel) {
      setTimeout(() => {
        edEstudioSel.focus();
        if (typeof edEstudioSel.showPicker === 'function') {
          try { edEstudioSel.showPicker(); } catch (e) {}
        }
      }, 200);
    }
  }
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\gmedi\enclaii-backend\resources\views/ia-reportes/redactar.blade.php ENDPATH**/ ?>