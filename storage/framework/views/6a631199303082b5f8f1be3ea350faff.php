<?php $__env->startSection('active', 'soporte'); ?>
<?php $__env->startSection('title', 'Soporte '); ?>
<?php $__env->startSection('header-title', 'Soporte '); ?>
<?php $__env->startSection('header-sub', 'Estamos aquí para ayudarte rápido.'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Soporte layout ── */
.sop-grid{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
.sop-main{display:flex;flex-direction:column;gap:24px}

/* Sidebar derecho */
.sop-side{display:flex;flex-direction:column;gap:20px}

/* Card base */
.sop-card{
  background:var(--panel-2);
  border:1px solid var(--stroke);
  border-radius:var(--r-lg);
  padding:24px;
  box-shadow:0 1px 3px rgba(0,0,0,.04),0 1px 2px rgba(0,0,0,.02);
  transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease;
}
.sop-card:hover{
  transform:translateY(-1px);
  box-shadow:0 8px 24px rgba(0,0,0,.08),0 2px 6px rgba(0,0,0,.04);
  border-color:rgba(110,160,255,.25);
}
.sop-card h2{font-size:16px;font-weight:700;margin-bottom:4px}
.sop-card .sub{font-size:13px;color:var(--txt-soft);margin-bottom:16px}
.sop-card-head{display:flex;align-items:flex-start;gap:14px;margin-bottom:10px}
.sop-card-head-ico{
  width:44px;height:44px;border-radius:12px;flex-shrink:0;
  background:rgba(16,185,129,.18);display:grid;place-items:center;
  margin-top:2px;
}
.sop-card-head-ico svg{color:#10b981}
.sop-card-head h2{font-size:16px;font-weight:700;margin:0;line-height:1.3}

/* Canales */
.sop-canal{
  display:flex;align-items:center;gap:12px;
  padding:12px 14px;border-radius:var(--r-md);
  border:1px solid var(--stroke);margin-bottom:10px;
  transition:background .15s;cursor:pointer;
}
.sop-canal:hover{background:rgba(110,160,255,.06)}
.sop-canal .icon-wrap{
  width:40px;height:40px;border-radius:50%;
  display:grid;place-items:center;flex-shrink:0;
}
.sop-canal .icon-wrap.wa{background:rgba(16,185,129,.15)}
.sop-canal .icon-wrap.wa svg{color:#10b981}
.sop-canal .icon-wrap.phone{background:rgba(139,92,246,.15)}
.sop-canal .icon-wrap.phone svg{color:#a78bfa}
.sop-canal .canal-info{flex:1}
.sop-canal .canal-info strong{font-size:13px;display:block}
.sop-canal .canal-info span{font-size:12px;color:var(--txt-soft)}
.sop-canal .canal-arrow{color:var(--txt-soft);font-size:18px;font-weight:300}
.sop-canal:focus-visible{outline:2px solid var(--blue);outline-offset:2px}

/* Canal de chat de soporte - acción primaria */
#btnCanalChatSoporte.sop-canal{
  width:100%;text-align:left;
  background:linear-gradient(135deg,rgba(59,130,246,.10),rgba(6,182,212,.08));
  border-color:rgba(59,130,246,.40);
  color:inherit;
  position:relative;
  overflow:hidden;
}
#btnCanalChatSoporte.sop-canal::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(59,130,246,.08),rgba(6,182,212,.06));
  opacity:0;transition:opacity .2s ease;
}
#btnCanalChatSoporte.sop-canal:hover{
  background:linear-gradient(135deg,rgba(59,130,246,.16),rgba(6,182,212,.12));
  border-color:rgba(59,130,246,.65);
  transform:translateY(-2px);
  box-shadow:0 8px 22px rgba(59,130,246,.18);
}
#btnCanalChatSoporte.sop-canal:hover::before{opacity:1}
#btnCanalChatSoporte.sop-canal:active{transform:translateY(0)}
#btnCanalChatSoporte.sop-canal .icon-wrap{
  background:linear-gradient(135deg,rgba(59,130,246,.20),rgba(6,182,212,.16));
  transition:transform .25s ease,background .25s ease;
}
#btnCanalChatSoporte.sop-canal:hover .icon-wrap{
  transform:scale(1.1) rotate(6deg);
  background:linear-gradient(135deg,rgba(59,130,246,.28),rgba(6,182,212,.22));
}
#btnCanalChatSoporte.sop-canal .icon-wrap svg{
  color:var(--blue);
  transition:color .2s ease;
}
#btnCanalChatSoporte.sop-canal:hover .icon-wrap svg{color:var(--cyan)}
#btnCanalChatSoporte.sop-canal .canal-arrow{
  color:var(--blue);
  transition:transform .2s ease,color .2s ease;
}
#btnCanalChatSoporte.sop-canal:hover .canal-arrow{
  color:var(--cyan);
  transform:translateX(4px);
}
#btnCanalChatSoporte.sop-canal .canal-info strong{
  color:var(--txt);
  transition:color .2s ease;
}
#btnCanalChatSoporte.sop-canal:hover .canal-info strong{color:var(--blue)}
#btnCanalChatSoporte.sop-canal .canal-info span{
  transition:color .2s ease;
}
#btnCanalChatSoporte.sop-canal:hover .canal-info span{color:var(--cyan)}
#btnCanalChatSoporte.sop-canal::after{
  content:'';position:absolute;top:10px;right:10px;width:8px;height:8px;border-radius:50%;
  background:#22c55e;box-shadow:0 0 0 2px var(--panel-2);
  animation:pulseDot 1.6s ease-in-out infinite;
}

/* Canal de llamada - acción secundaria destacada */
#btnLlamar.sop-canal{
  width:100%;text-align:left;
  background:linear-gradient(135deg,rgba(16,185,129,.08),rgba(139,92,246,.06));
  border-color:rgba(16,185,129,.35);
  color:inherit;
  position:relative;
  overflow:hidden;
}
#btnLlamar.sop-canal::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(16,185,129,.06),rgba(139,92,246,.04));
  opacity:0;transition:opacity .2s ease;
}
#btnLlamar.sop-canal:hover{
  background:linear-gradient(135deg,rgba(16,185,129,.14),rgba(139,92,246,.10));
  border-color:rgba(16,185,129,.55);
  transform:translateY(-1px);
  box-shadow:0 4px 14px rgba(16,185,129,.12);
}
#btnLlamar.sop-canal:hover::before{opacity:1}
#btnLlamar.sop-canal:active{transform:translateY(0)}
#btnLlamar.sop-canal .icon-wrap{
  background:linear-gradient(135deg,rgba(16,185,129,.18),rgba(139,92,246,.15));
  transition:transform .2s ease,background .2s ease;
}
#btnLlamar.sop-canal:hover .icon-wrap{
  transform:scale(1.08) rotate(-6deg);
  background:linear-gradient(135deg,rgba(16,185,129,.24),rgba(139,92,246,.20));
}
#btnLlamar.sop-canal .icon-wrap svg{
  color:#10b981;
  transition:color .2s ease;
}
#btnLlamar.sop-canal:hover .icon-wrap svg{color:#059669}
#btnLlamar.sop-canal .canal-arrow{
  color:#10b981;
  transition:transform .2s ease,color .2s ease;
}
#btnLlamar.sop-canal:hover .canal-arrow{
  color:#059669;
  transform:translateX(3px);
}
#btnLlamar.sop-canal .canal-info strong{
  color:var(--txt);
  transition:color .2s ease;
}
#btnLlamar.sop-canal:hover .canal-info strong{color:#059669}
#btnLlamar.sop-canal::after{
  content:'';position:absolute;top:10px;right:10px;width:7px;height:7px;border-radius:50%;
  background:#22c55e;box-shadow:0 0 0 2px var(--panel-2);
  animation:pulseDot 1.8s ease-in-out infinite;
}
@keyframes pulseDot{
  0%,100%{opacity:1;transform:scale(1)}
  50%{opacity:.55;transform:scale(1.35)}
}

.sop-schedule{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--txt-soft);margin-top:4px}

/* Mis Tickets card */
.sop-tickets-btn{
  background:linear-gradient(145deg,#0b1120,#0f172a);
  border:1px solid rgba(59,130,246,.22);
  border-radius:var(--r-lg);
  padding:20px;
  display:flex;flex-direction:column;align-items:flex-start;gap:12px;
  box-shadow:0 4px 20px rgba(0,0,0,.25),inset 0 1px 0 rgba(255,255,255,.03);
  transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease;
}
.sop-tickets-btn:hover{
  transform:translateY(-2px);
  border-color:rgba(59,130,246,.38);
  box-shadow:0 8px 28px rgba(0,0,0,.32),0 0 18px rgba(59,130,246,.08),inset 0 1px 0 rgba(255,255,255,.04);
}
.sop-tickets-ico{
  width:48px;height:48px;border-radius:14px;flex-shrink:0;
  background:rgba(59,130,246,.16);
  border:1px solid rgba(59,130,246,.22);
  display:grid;place-items:center;
  box-shadow:0 2px 8px rgba(59,130,246,.12);
}
.sop-tickets-ico svg{color:#60a5fa}
.sop-tickets-btn h3{font-size:15px;font-weight:700;margin:0 0 2px;color:#e2e8f0}
.sop-tickets-btn p{font-size:12px;color:#94a3b8;margin:0}
.sop-tickets-btn .btn-tickets{
  width:100%;padding:11px;border-radius:var(--r-md);
  border:1px solid rgba(59,130,246,.45);
  background:rgba(59,130,246,.06);
  color:#60a5fa;
  font-size:13px;font-weight:600;text-decoration:none;
  display:flex;align-items:center;justify-content:center;gap:6px;
  transition:background .15s ease,color .15s ease,border-color .15s ease,box-shadow .15s ease;
  margin-top:4px;
  box-shadow:0 1px 2px rgba(0,0,0,.08);
}
.sop-tickets-btn .btn-tickets:hover{
  background:rgba(59,130,246,.14);
  border-color:rgba(96,165,250,.65);
  color:#93c5fd;
  box-shadow:0 4px 12px rgba(59,130,246,.18);
}

/* Temas de ayuda - Accordion */
.sop-temas h2{font-size:16px;font-weight:700;margin-bottom:16px}
.sop-accordion{border:1px solid var(--stroke);border-radius:var(--r-lg);overflow:hidden}
.sop-acc-item{border-bottom:1px solid var(--stroke)}
.sop-acc-item:last-child{border-bottom:0}
.sop-acc-btn{
  width:100%;display:flex;align-items:center;justify-content:space-between;
  padding:16px 20px;border:none;background:transparent;color:var(--txt);
  font-size:14px;font-weight:500;cursor:pointer;text-align:left;
  transition:background .15s;
}
.sop-acc-btn:hover{background:rgba(110,160,255,.04)}
.sop-acc-btn .acc-arrow{
  transition:transform .2s ease;color:var(--blue);flex-shrink:0;
}
.sop-acc-item.open .sop-acc-btn .acc-arrow{transform:rotate(180deg)}
.sop-acc-body{
  max-height:0;overflow:hidden;transition:max-height .25s ease;
  padding:0 20px;
}
.sop-acc-item.open .sop-acc-body{max-height:200px;padding:0 20px 16px}
.sop-acc-body p{font-size:13px;color:var(--txt-soft);line-height:1.6;margin:0}

/* Phone call card */
.call-overlay{
  display:none;position:fixed;inset:0;z-index:9000;
  background:rgba(0,0,0,.45);backdrop-filter:blur(4px);
  place-items:center;
}
.call-overlay.active{display:grid}
.call-card{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:16px;
  padding:32px 36px;box-shadow:0 16px 48px rgba(0,0,0,.2);
  text-align:center;min-width:320px;max-width:380px;
  animation:fbSlideUp .3s ease;position:relative;
}
.call-card .call-close{
  position:absolute;top:10px;right:14px;background:none;border:none;
  color:var(--txt-soft);cursor:pointer;font-size:18px;line-height:1;
}
.call-card .call-close:hover{color:var(--txt)}
.call-card .call-icon{
  width:56px;height:56px;border-radius:50%;margin:0 auto 16px;
  background:rgba(74,222,128,.15);display:grid;place-items:center;
}
.call-card .call-icon svg{color:#16a34a}
.call-card h3{font-size:16px;font-weight:700;margin:0 0 6px}
.call-card .call-number{font-size:22px;font-weight:800;color:var(--blue);margin:12px 0 6px;letter-spacing:1px}
.call-card .call-sub{font-size:12px;color:var(--txt-soft);margin:0 0 20px}
.call-card .call-btn{
  display:inline-flex;align-items:center;gap:8px;
  padding:12px 28px;border:none;border-radius:99px;
  background:linear-gradient(135deg,#16a34a,#4ade80);color:#fff;
  font-size:14px;font-weight:700;cursor:pointer;
  transition:opacity .15s,transform .15s;text-decoration:none;
}
.call-card .call-btn:hover{opacity:.9;transform:scale(1.03)}

@keyframes fbSlideUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}

/* Chat de soporte IA */
.sop-chat-btn{
  display:inline-flex;align-items:center;gap:8px;
  padding:10px 16px;border-radius:var(--r-md);border:none;
  background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;
  font-size:13px;font-weight:600;cursor:pointer;margin-bottom:18px;
  transition:opacity .15s;
}
.sop-chat-btn:hover{opacity:.9}
.sop-chat-panel{
  display:none;flex-direction:column;position:relative;
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-lg);
  height:420px;overflow:hidden;margin-bottom:24px;
}
.sop-chat-panel.open{display:flex}
.sop-chat-skeleton{position:absolute;inset:0;display:flex;flex-direction:column;gap:12px;padding:16px;background:var(--panel-2);z-index:10;justify-content:flex-end;border-radius:var(--r-lg)}
.sop-sk-row{display:flex;gap:8px;align-items:flex-end}
.sop-sk-row.right{flex-direction:row-reverse}
.sop-sk-bubble{border-radius:14px;background:var(--stroke);animation:skPulse 1.4s ease-in-out infinite}
.sop-sk-bubble.wide{width:62%;height:44px}
.sop-sk-bubble.mid{width:42%;height:38px}
.sop-sk-bubble.short{width:28%;height:38px}
.sop-sk-bubble.tall{width:55%;height:58px}
@keyframes skPulse{0%,100%{opacity:.3}50%{opacity:.8}}
.sop-chat-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:14px 16px;border-bottom:1px solid var(--stroke);background:rgba(110,160,255,.06)
}
.sop-chat-header h3{font-size:14px;font-weight:700;margin:0;display:flex;align-items:center;gap:8px}
.sop-chat-header .close{
  background:none;border:none;color:var(--txt-soft);font-size:18px;cursor:pointer;
}
.sop-chat-messages{
  position:relative;flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:12px;
}
.sop-chat-msg{max-width:80%;min-width:64px;padding:11px 14px;border-radius:14px;font-size:13px;line-height:1.5;word-break:break-word;overflow-wrap:break-word;white-space:pre-wrap}
.sop-chat-msg.user{align-self:flex-end;background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff}
.sop-chat-msg.assistant{align-self:flex-start;background:var(--panel);border:1px solid var(--stroke);color:var(--txt);min-width:120px}
.sop-chat-input{
  display:flex;gap:10px;padding:12px 16px;border-top:1px solid var(--stroke);background:var(--panel)
}
.sop-chat-input input{
  flex:1;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);
  padding:10px 12px;font-size:13px;color:var(--txt);outline:none;
}
.sop-chat-input button{
  padding:10px 14px;border-radius:var(--r-md);border:none;
  background:var(--blue);color:#fff;font-size:13px;font-weight:600;cursor:pointer;
}
.sop-chat-input button:disabled{opacity:.6;cursor:not-allowed}

/* Opciones rápidas */
.sop-quick-options{
  display:flex;flex-wrap:wrap;gap:8px;
  padding:10px 16px 4px;
  border-top:1px solid var(--stroke);
}
.sop-quick-options.hidden{display:none}
.sop-quick-opt{
  padding:7px 13px;border-radius:99px;
  border:1px solid var(--stroke);background:var(--panel-2);
  color:var(--txt);font-size:12px;font-weight:500;
  cursor:pointer;transition:background .15s,border-color .15s;
  white-space:nowrap;
}
.sop-quick-opt:hover{background:rgba(110,160,255,.12);border-color:var(--blue)}
.sop-quick-opt.agent{border-color:rgba(110,160,255,.4);color:var(--blue)}
.sop-quick-more{background:rgba(110,160,255,.1);border-color:rgba(110,160,255,.3);color:var(--blue)}
.sop-quick-more.open{background:rgba(110,160,255,.2)}
.sop-quick-extra{
  display:flex;flex-wrap:wrap;gap:8px;width:100%;
  padding-top:6px;
}
.sop-quick-extra.hidden{display:none}

html[data-theme="light"] .sop-card{
  background:#fff;border-color:#dbe5f5;box-shadow:0 2px 8px rgba(15,23,42,.05)
}
html[data-theme="light"] .sop-card:hover{
  border-color:rgba(59,130,246,.35);box-shadow:0 10px 26px rgba(15,23,42,.09)
}
html[data-theme="light"] .sop-canal{
  background:#fff;border-color:#dbe5f5
}
html[data-theme="light"] .sop-canal:hover{background:#f5f9ff}
html[data-theme="light"] #btnCanalChatSoporte.sop-canal{
  background:linear-gradient(135deg,#eff6ff,#ecfeff);border-color:#93c5fd
}
html[data-theme="light"] #btnCanalChatSoporte.sop-canal:hover{
  background:linear-gradient(135deg,#dbeafe,#cffafe);border-color:#60a5fa;box-shadow:0 8px 22px rgba(59,130,246,.14)
}
html[data-theme="light"] #btnLlamar.sop-canal{
  background:linear-gradient(135deg,#ecfdf5,#f5f3ff);border-color:#86efac
}
html[data-theme="light"] #btnLlamar.sop-canal:hover{
  background:linear-gradient(135deg,#d1fae5,#ede9fe);border-color:#4ade80;box-shadow:0 4px 14px rgba(16,185,129,.12)
}
html[data-theme="light"] .sop-tickets-btn{
  background:linear-gradient(145deg,#fff,#f5f9ff);border-color:#dbe5f5;box-shadow:0 4px 16px rgba(15,23,42,.06)
}
html[data-theme="light"] .sop-tickets-btn:hover{box-shadow:0 10px 26px rgba(15,23,42,.1),0 0 18px rgba(59,130,246,.07)}
html[data-theme="light"] .sop-tickets-btn h3{color:#172554}
html[data-theme="light"] .sop-tickets-btn p{color:#64748b}
html[data-theme="light"] .sop-tickets-btn .btn-tickets{background:#eff6ff;color:#2563eb}
html[data-theme="light"] .sop-chat-panel{background:#fff;border-color:#dbe5f5;box-shadow:0 12px 30px rgba(15,23,42,.08)}
html[data-theme="light"] .sop-chat-header{background:#f8fbff;border-color:#dbe5f5}
html[data-theme="light"] .sop-chat-msg.assistant{background:#f8fafc;border-color:#dbe5f5;color:#1e293b}
html[data-theme="light"] .sop-chat-input{background:#f8fbff;border-color:#dbe5f5}
html[data-theme="light"] .sop-chat-input input{background:#fff;border-color:#cbd5e1;color:#0f172a}
html[data-theme="light"] .sop-quick-options{border-color:#dbe5f5}
html[data-theme="light"] .sop-quick-opt{background:#fff;border-color:#cbd5e1;color:#334155}
html[data-theme="light"] .sop-quick-opt:hover{background:#eff6ff;border-color:#60a5fa}
html[data-theme="light"] .sop-quick-more{background:#eff6ff;color:#2563eb}
html[data-theme="light"] .call-card{background:#fff;border-color:#dbe5f5;box-shadow:0 16px 48px rgba(15,23,42,.18)}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="sop-grid">

  
  <div class="sop-main">

    
    <div class="sop-chat-panel" id="soporteChatPanel">
      <div class="sop-chat-header">
        <h3>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v14a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v4a7 7 0 0 1-7 7"/><path d="M5 10v4a7 7 0 0 0 7 7"/></svg>
          Asistente de soporte ENCLAII
        </h3>
        <button class="close" id="btnCloseSoporteChat" type="button">×</button>
      </div>
      <div class="sop-chat-messages" id="soporteChatMessages"></div>
      <div class="sop-quick-options" id="soporteQuickOptions">
        <button class="sop-quick-opt" data-key="subir_estudio" type="button">¿Cómo subo un estudio?</button>
        <button class="sop-quick-opt" data-key="problema_cuenta" type="button">Problema con mi cuenta</button>
        <button class="sop-quick-opt sop-quick-more" id="btnMoreOpts" type="button">Más ▾</button>
        <button class="sop-quick-opt agent" data-key="hablar_agente" type="button"> Hablar con un agente</button>
        <div class="sop-quick-extra hidden" id="soporteExtraOpts">
          <button class="sop-quick-opt" data-key="facturacion" type="button">Facturación</button>
          <button class="sop-quick-opt" data-key="suscripcion" type="button">Mi suscripción / cobros</button>
          <button class="sop-quick-opt" data-key="error_tecnico" type="button">Error técnico</button>
        </div>
      </div>
      <div class="sop-chat-input">
        <input type="text" id="soporteChatInput" placeholder="Describe tu problema..." autocomplete="off">
        <button type="button" id="btnSendSoporteChat">Enviar</button>
      </div>
    </div>

    
    <div class="sop-card sop-card-ticket">
      <h2>Crear ticket</h2>
      <p class="sub">Selecciona una categoría y proporciona los detalles de tu problema.</p>

      <?php if(!empty($perfilIncompleto)): ?>
        <div class="sop-alert" style="display:flex;align-items:center;gap:16px;justify-content:space-between;flex-wrap:wrap;background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.35);border-radius:var(--r-md);padding:16px 18px;margin-bottom:18px">
          <div style="display:flex;align-items:center;gap:12px">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <span style="font-size:13px;color:var(--txt)">Los campos "Datos del negocio" no se llenan automáticamente porque aún no has registrado los datos de tu clínica en el perfil.</span>
          </div>
          <a href="<?php echo e(route('configuracion')); ?>?tab=perfil" class="sop-btn" style="flex-shrink:0;padding:9px 16px;border-radius:var(--r-md);background:linear-gradient(135deg,var(--blue),var(--cyan));color:#fff;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:8px">Completar perfil →</a>
        </div>
      <?php endif; ?>

      <?php echo $__env->make('soporte._ticket_form', [
        'latestTicket' => $latestTicket ?? null,
        'clinicaData' => $clinicaData ?? '',
        'operationFolio' => $operationFolio ?? '',
        'operationDate' => $operationDate ?? '',
      ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="sop-card sop-temas">
      <h2>Consultar los temas de ayuda</h2>
      <div class="sop-accordion">

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Error al subir archivos</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Verifica que el archivo no supere los 10MB y sea de un formato compatible (JPG, PNG, PDF, MP4). Si el problema persiste, limpia la cache del navegador e intenta nuevamente.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>No puedo iniciar sesion</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>1. Verifica que tu correo y contrasena sean correctos.<br>2. Limpia la cache de tu navegador.<br>3. Usa la opcion "Recuperar contrasena" si la olvidaste.<br>4. Si el problema persiste, contacta a soporte tecnico.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Error al exportar datos</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Asegurate de tener al menos un estudio o reporte seleccionado. Intenta con un formato diferente (PDF o Excel). Si el archivo se descarga vacio, recarga la pagina y vuelve a intentar.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Problemas de conexion</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>1. Verifica tu conexion a internet.<br>2. Intenta recargar la pagina.<br>3. Limpia la cache del navegador.<br>4. Si usas VPN, intenta desactivarla temporalmente.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Como generar un reporte con IA</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Ve a la seccion "Reportes" en el menu lateral, selecciona "Generar reporte" y elige el estudio. Nuestro asistente de IA te ayudara a redactar el informe. Puedes editar y personalizar el reporte antes de guardarlo.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Administrar mi cuenta</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Accede a Configuracion desde el menu lateral para cambiar tu nombre, correo, contrasena, foto de perfil y preferencias de notificaciones.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Seguridad y privacidad</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Todos los datos medicos estan encriptados. Puedes activar la verificacion en dos pasos desde Configuracion > Seguridad. Nunca compartimos informacion de pacientes con terceros.</p>
          </div>
        </div>

        <div class="sop-acc-item">
          <button class="sop-acc-btn" type="button">
            <span>Facturacion y pagos</span>
            <svg class="acc-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sop-acc-body">
            <p>Consulta tu historial de pagos y descarga facturas desde Configuracion > Facturacion. Si necesitas cambiar tu metodo de pago o plan, contacta al equipo de ventas.</p>
          </div>
        </div>

      </div>
    </div>

  </div>

  
  <div class="sop-side">

    
    <div class="sop-tickets-btn">
      <div class="sop-tickets-ico">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H2V6h2a2 2 0 0 0 2-2V2"/><path d="M22 12h-2a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2V6h-2a2 2 0 0 1-2-2V2"/><path d="M7 2h10"/><path d="M7 22h10"/><rect x="7" y="6" width="10" height="12" rx="1"/></svg>
      </div>
      <div>
        <h3>Mis Tickets</h3>
        <p>Consulta el historial y estado de todos tus tickets de soporte.</p>
      </div>
      <a href="<?php echo e(route('soporte.tickets')); ?>" class="btn-tickets">Ver tickets →</a>
    </div>

    
    <div class="sop-card">
      <div class="sop-card-head">
        <div class="sop-card-head-ico">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
        </div>
        <h2>¿Es urgente? Contáctanos por otros canales</h2>
      </div>
      <p class="sub">Elige el medio que prefieras para obtener ayuda más rápido.</p>

      <div class="sop-canales">
        <button class="sop-canal" id="btnCanalChatSoporte" type="button">
          <div class="icon-wrap wa">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          </div>
          <div class="canal-info">
            <strong>Chat de soporte</strong>
            <span>Habla con el asistente de IA de soporte</span>
          </div>
          <span class="canal-arrow">›</span>
        </button>

        <div class="sop-canal" id="btnLlamar" style="cursor:pointer">
          <div class="icon-wrap phone">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div class="canal-info">
            <strong>Llamar desde tu telefono</strong>
            <span>+52 55 1234 5678</span>
          </div>
          <span class="canal-arrow">›</span>
        </div>

        <div class="sop-schedule">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Lun – Vie de 9am a 6pm
        </div>
      </div>
    </div>

  </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Phone call card */
.call-overlay{
  display:none;position:fixed;inset:0;z-index:9000;
  background:rgba(0,0,0,.45);backdrop-filter:blur(4px);
  place-items:center;
}
.call-overlay.active{display:grid}
.call-card{
  background:var(--panel-2);border:1px solid var(--stroke);border-radius:16px;
  padding:32px 36px;box-shadow:0 16px 48px rgba(0,0,0,.2);
  text-align:center;min-width:320px;max-width:380px;
  animation:fbSlideUp .3s ease;position:relative;
}
.call-card .call-close{
  position:absolute;top:10px;right:14px;background:none;border:none;
  color:var(--txt-soft);cursor:pointer;font-size:18px;line-height:1;
}
.call-card .call-close:hover{color:var(--txt)}
.call-card .call-icon{
  width:56px;height:56px;border-radius:50%;margin:0 auto 16px;
  background:rgba(74,222,128,.15);display:grid;place-items:center;
}
.call-card .call-icon svg{color:#16a34a}
.call-card h3{font-size:16px;font-weight:700;margin:0 0 6px}
.call-card .call-number{font-size:22px;font-weight:800;color:var(--blue);margin:12px 0 6px;letter-spacing:1px}
.call-card .call-sub{font-size:12px;color:var(--txt-soft);margin:0 0 20px}
.call-card .call-btn{
  display:inline-flex;align-items:center;gap:8px;
  padding:12px 28px;border:none;border-radius:99px;
  background:linear-gradient(135deg,#16a34a,#4ade80);color:#fff;
  font-size:14px;font-weight:700;cursor:pointer;
  transition:opacity .15s,transform .15s;text-decoration:none;
}
.call-card .call-btn:hover{opacity:.9;transform:scale(1.03)}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

<div class="call-overlay" id="callOverlay">
  <div class="call-card">
    <button class="call-close" id="callClose">&times;</button>
    <div class="call-icon">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    </div>
    <h3>Llamar desde tu telefono</h3>
    <div class="call-number">+52 55 1234 5678</div>
    <p class="call-sub">Lun - Vie de 8am a 6pm</p>
    <a href="tel:+525512345678" class="call-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
      Llamar ahora
    </a>
  </div>
</div>

<script>
(function(){
  // Accordion toggle
  document.querySelectorAll('.sop-acc-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      var item = btn.closest('.sop-acc-item');
      var isOpen = item.classList.contains('open');
      document.querySelectorAll('.sop-acc-item').forEach(function(i){ i.classList.remove('open'); });
      if(!isOpen) item.classList.add('open');
    });
  });

  // Phone call card
  var btnLlamar = document.getElementById('btnLlamar');
  var callOverlay = document.getElementById('callOverlay');
  var callClose = document.getElementById('callClose');

  if(btnLlamar) btnLlamar.addEventListener('click', function(){
    callOverlay.classList.add('active');
  });
  if(callClose) callClose.addEventListener('click', function(){
    callOverlay.classList.remove('active');
  });
  if(callOverlay) callOverlay.addEventListener('click', function(e){
    if(e.target === callOverlay) callOverlay.classList.remove('active');
  });

  // Chat de soporte IA
  var btnOpenChat = document.getElementById('btnOpenSoporteChat');
  var btnCanalChatSoporte = document.getElementById('btnCanalChatSoporte');
  var btnCloseChat = document.getElementById('btnCloseSoporteChat');
  var chatPanel = document.getElementById('soporteChatPanel');
  var chatMessages = document.getElementById('soporteChatMessages');
  var chatInput = document.getElementById('soporteChatInput');
  var btnSendChat = document.getElementById('btnSendSoporteChat');
  var chatConversationId = null;

  var quickOptionsEl = document.getElementById('soporteQuickOptions');

  var chatMode = 'bot';

  function updateQuickOptions(){
    if(!quickOptionsEl) return;
    if(chatMode === 'bot'){
      quickOptionsEl.classList.remove('hidden');
    } else {
      quickOptionsEl.classList.add('hidden');
    }
  }

  function hideQuickOptions(){
    if(quickOptionsEl) quickOptionsEl.classList.add('hidden');
  }

  function showQuickOptions(){
    if(quickOptionsEl) quickOptionsEl.classList.remove('hidden');
  }

  function buildChatMessage(role, text){
    if(role === 'system'){
      var sys = document.createElement('div');
      sys.style.cssText = 'text-align:center;font-size:11px;color:var(--txt-soft);padding:4px 0;font-style:italic;align-self:center';
      sys.textContent = text;
      return sys;
    }
    if(role === 'agent'){
      var wrap = document.createElement('div');
      wrap.style.cssText = 'display:flex;flex-direction:column;align-self:flex-start;align-items:flex-start;max-width:80%';
      var lbl = document.createElement('div');
      lbl.style.cssText = 'font-size:11px;color:var(--txt-soft);margin-bottom:3px';
      lbl.textContent = 'Agente ENCLAII';
      var div = document.createElement('div');
      div.className = 'sop-chat-msg assistant';
      div.style.borderLeft = '3px solid #16a34a';
      div.textContent = text;
      wrap.appendChild(lbl);
      wrap.appendChild(div);
      return wrap;
    }
    var div = document.createElement('div');
    div.className = 'sop-chat-msg ' + role;
    div.textContent = text;
    return div;
  }

  function addChatMessage(role, text){
    chatMessages.appendChild(buildChatMessage(role, text));
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  function addChatLoading(){
    var div = document.createElement('div');
    div.className = 'sop-chat-msg assistant';
    div.id = 'soporteChatLoading';
    div.textContent = 'Escribiendo...';
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  function removeChatLoading(){
    var el = document.getElementById('soporteChatLoading');
    if(el) el.remove();
  }

  async function sendChatMessage(text, quickOption){
    text = text || chatInput.value.trim();
    if(!text) return;
    addChatMessage('user', text);
    chatInput.value = '';
    if(chatMode === 'bot' && quickOption !== 'hablar_agente') addChatLoading();
    btnSendChat.disabled = true;

    var body = { message: text, conversation_id: chatConversationId };
    if(quickOption) body.quick_option = quickOption;

    try {
      var response = await fetch("<?php echo e(route('soporte.chat')); ?>", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
        },
        body: JSON.stringify(body)
      });
      var data = await response.json();
      removeChatLoading();
      if(data.conversation_id){
        chatConversationId = data.conversation_id;
        startPolling(data.conversation_id);
      }
      if(data.mode) chatMode = data.mode;
      updateQuickOptions();
      if(data.reply){
        addChatMessage('assistant', data.reply);
      }
    } catch(err){
      removeChatLoading();
      addChatMessage('assistant', 'Error de conexión. Intenta de nuevo.');
    } finally {
      btnSendChat.disabled = false;
      chatInput.focus();
    }
  }

  async function loadChatHistory(){
    var hasMessages = false;
    try {
      var response = await fetch("<?php echo e(route('soporte.chat.history')); ?>");
      var data = await response.json();
      if(data.conversation){
        chatConversationId = data.conversation.id;
        chatMode = data.conversation.mode || 'bot';
      }
      if(data.messages && data.messages.length){
        var frag = document.createDocumentFragment();
        data.messages.forEach(function(m){
          frag.appendChild(buildChatMessage(m.role, m.content));
        });
        chatMessages.appendChild(frag);
        updateQuickOptions();
        hasMessages = true;
      }
    } catch(e){}
    return hasMessages;
  }

  function showSkeleton(){
    var sk = document.createElement('div');
    sk.className = 'sop-chat-skeleton';
    sk.id = 'soporteSkeleton';
    sk.innerHTML = '<div class="sop-sk-row"><div class="sop-sk-bubble wide"></div></div>'
      + '<div class="sop-sk-row right"><div class="sop-sk-bubble mid"></div></div>'
      + '<div class="sop-sk-row"><div class="sop-sk-bubble tall"></div></div>'
      + '<div class="sop-sk-row right"><div class="sop-sk-bubble short"></div></div>'
      + '<div class="sop-sk-row"><div class="sop-sk-bubble mid"></div></div>'
      + '<div class="sop-sk-row right"><div class="sop-sk-bubble wide"></div></div>';
    chatPanel.appendChild(sk);
  }

  function hideSkeleton(){
    var sk = document.getElementById('soporteSkeleton');
    if(sk) sk.remove();
  }

  async function openSoporteChat(){
    if(chatPanel.classList.contains('open')) return;
    chatPanel.classList.add('open');
    void chatPanel.offsetHeight;
    try {
      if(chatMessages.children.length === 0){
        showSkeleton();
        var hadHistory = await loadChatHistory();
        if(!hadHistory){
          hideSkeleton();
          addChatMessage('assistant', 'Hola, soy el asistente de soporte de ENCLAII. ¿En qué puedo ayudarte?');
          chatMode = 'bot';
          updateQuickOptions();
        } else {
          if(chatConversationId){
            try {
              var pr = await fetch(pollUrl + '?conversation_id=' + chatConversationId + '&last_id=' + lastMessageId, {
                headers: { 'Accept': 'application/json' }
              });
              var pd = await pr.json();
              if(pd.ok && pd.messages && pd.messages.length){
                pd.messages.forEach(function(m){
                  if(m.id > lastMessageId) lastMessageId = m.id;
                  chatMessages.appendChild(buildChatMessage(m.role, m.content));
                });
              }
              if(pd.mode && pd.mode !== chatMode){
                chatMode = pd.mode;
                updateQuickOptions();
              }
            } catch(e){}
          }
          requestAnimationFrame(function(){
            chatMessages.scrollTop = chatMessages.scrollHeight;
            hideSkeleton();
            if(chatConversationId) startPolling(chatConversationId);
          });
        }
      }
    } finally {
      chatInput.focus();
    }
  }

  if(btnOpenChat && chatPanel){
    btnOpenChat.addEventListener('click', openSoporteChat);
  }
  if(btnCanalChatSoporte && chatPanel){
    btnCanalChatSoporte.addEventListener('click', openSoporteChat);
  }
  if(btnCloseChat && chatPanel){
    btnCloseChat.addEventListener('click', function(){
      chatPanel.classList.remove('open');
    });
  }
  if(btnSendChat && chatInput){
    btnSendChat.addEventListener('click', function(){ sendChatMessage(); });
    chatInput.addEventListener('keydown', function(e){
      if(e.key === 'Enter' && !e.shiftKey){
        e.preventDefault();
        sendChatMessage();
      }
    });
  }

  var btnMoreOpts = document.getElementById('btnMoreOpts');
  var extraOpts = document.getElementById('soporteExtraOpts');
  if(btnMoreOpts && extraOpts){
    btnMoreOpts.addEventListener('click', function(){
      var isOpen = !extraOpts.classList.contains('hidden');
      if(isOpen){
        extraOpts.classList.add('hidden');
        btnMoreOpts.classList.remove('open');
        btnMoreOpts.textContent = 'Más ▾';
      } else {
        extraOpts.classList.remove('hidden');
        btnMoreOpts.classList.add('open');
        btnMoreOpts.textContent = 'Menos ▴';
      }
    });
  }

  document.querySelectorAll('.sop-quick-opt[data-key]').forEach(function(btn){
    btn.addEventListener('click', function(){
      var key = btn.dataset.key;
      var label = btn.textContent.trim();
      if(extraOpts) extraOpts.classList.add('hidden');
      if(btnMoreOpts){ btnMoreOpts.classList.remove('open'); btnMoreOpts.textContent = 'Más ▾'; }
      sendChatMessage(label, key);
    });
  });

  var pollInterval = null;
  var lastMessageId = 0;
  var pollUrl = "<?php echo e(route('soporte.chat.poll')); ?>";

  function getPollDelay(){
    return (chatMode === 'with_agent' || chatMode === 'pending_agent') ? 1500 : 4000;
  }

  function startPolling(convId){
    if(pollInterval) return;
    function schedulePoll(){
      pollInterval = setTimeout(async function(){
        try {
          var r = await fetch(pollUrl + '?conversation_id=' + convId + '&last_id=' + lastMessageId, {
            headers: { 'Accept': 'application/json' }
          });
          var data = await r.json();
          if(data.ok){
            data.messages.forEach(function(m){
              if(m.id > lastMessageId) lastMessageId = m.id;
              chatMessages.appendChild(buildChatMessage(m.role, m.content));
              chatMessages.scrollTop = chatMessages.scrollHeight;
            });

            if(data.mode && data.mode !== chatMode){
              chatMode = data.mode;
              updateQuickOptions();
              if(data.mode === 'closed' || data.status === 'closed'){
                pollInterval = null;
                var resolved = document.createElement('div');
                resolved.style.cssText = 'text-align:center;padding:12px;font-size:12px;color:var(--txt-soft)';
                resolved.innerHTML = 'Conversaci\u00f3n resuelta.';
                chatMessages.appendChild(resolved);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                return;
              }
            }
          }
        } catch(e){}
        schedulePoll();
      }, getPollDelay());
    }
    schedulePoll();
  }

})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\soporte\index.blade.php ENDPATH**/ ?>