@extends('layouts.app')
@section('title','Mensajes')
@section('active','mensajes')
@section('header-title','Mensajes')
@section('header-sub') Gestiona tus conversaciones con pacientes @endsection

@section('header-extra')
@if(request()->query('desde') === 'estudio_terminado' && request()->integer('estudio_id') > 0)
<a class="btn-return-study" href="{{ route('nuevo-estudio.grabando', ['estudio_id' => request()->integer('estudio_id'), 'vista' => 'finalizado']) }}">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <line x1="19" y1="12" x2="5" y2="12"/>
    <polyline points="12 19 5 12 12 5"/>
  </svg>
  Volver al estudio terminado
</a>
@endif
<button class="btn-ch-toggle" onclick="toggleChPanel()">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
  Canales de comunicacion
  <svg class="arrow" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
</button>
@endsection

@push('styles') 
<style>
.msg-page{display:grid;grid-template-columns:255px 1fr 0px;height:calc(100vh - 126px);border-radius:14px;overflow:hidden;border:1px solid var(--stroke);box-shadow:0 4px 32px rgba(0,0,0,.2);transition:grid-template-columns 280ms cubic-bezier(.4,0,.2,1);}
.msg-page.ch-open{grid-template-columns:255px 1fr 215px;}
.msg-sidebar{display:flex;flex-direction:column;background:var(--panel);border-right:1px solid var(--stroke);overflow:hidden;}
.msg-search-row{display:flex;align-items:center;gap:8px;padding:13px 12px 10px;}
.msg-search{flex:1;display:flex;align-items:center;gap:8px;padding:8px 11px;border-radius:10px;border:1px solid var(--stroke);background:var(--panel-2);transition:border-color 150ms;}
.msg-search:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.1);}
.msg-search input{flex:1;background:none;border:none;outline:none;color:var(--txt);font:inherit;font-size:12px;}
.msg-search input::placeholder{color:var(--txt-soft);}
.msg-search svg{color:var(--txt-soft);flex:none;}
.btn-filter{width:32px;height:32px;border-radius:9px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);display:grid;place-items:center;cursor:pointer;flex:none;transition:all 150ms;}
.btn-filter:hover{border-color:var(--stroke-strong);color:var(--txt);}
.msg-tabs{display:flex;border-bottom:1px solid var(--stroke);padding:0 10px;}
.msg-tab{padding:7px 9px;font-size:11.5px;font-weight:700;color:var(--txt-soft);cursor:pointer;border-bottom:2px solid transparent;transition:all 150ms;white-space:nowrap;}
.msg-tab.active{color:var(--blue);border-bottom-color:var(--blue);}
.msg-list{flex:1;overflow-y:auto;padding:4px 0;}
.msg-list::-webkit-scrollbar{width:3px;}
.msg-list::-webkit-scrollbar-thumb{background:var(--stroke);border-radius:4px;}
.conv-item{display:flex;align-items:center;gap:10px;padding:9px 12px;cursor:pointer;border-left:3px solid transparent;transition:background 150ms,border-color 150ms;}
.conv-item:hover{background:var(--panel-2);}
.conv-item.active{background:rgba(46,123,246,.08);border-left-color:var(--blue);}
.conv-avatar{width:38px;height:38px;border-radius:50%;display:grid;place-items:center;font-size:12px;font-weight:700;color:#fff;flex:none;}
.conv-avatar.blue{background:linear-gradient(135deg,#2e7bf6,#60a5fa);}
.conv-avatar.green{background:linear-gradient(135deg,#16a34a,#4ade80);}
.conv-avatar.orange{background:linear-gradient(135deg,#d97706,#fbbf24);}
.conv-avatar.purple{background:linear-gradient(135deg,#7c3aed,#a78bfa);}
.conv-avatar.red{background:linear-gradient(135deg,#dc2626,#f87171);}
.conv-avatar.teal{background:linear-gradient(135deg,#0891b2,#22d3ee);}
.conv-body{flex:1;min-width:0;}
.conv-name{font-size:12.5px;font-weight:700;color:var(--txt);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.conv-preview{font-size:11px;color:var(--txt-soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px;}
.conv-meta{display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex:none;}
.conv-time{font-size:10px;color:var(--txt-soft);}
.conv-badge{min-width:18px;height:18px;border-radius:10px;background:var(--blue);color:#fff;font-size:9.5px;font-weight:800;display:grid;place-items:center;padding:0 4px;}
/* Indicador de canal en sidebar */
.conv-ch-dot{width:7px;height:7px;border-radius:50%;flex:none;margin-top:1px;}
.conv-ch-dot.wa{background:#25d366;}
.conv-ch-dot.email{background:var(--blue);}
/* Activo según canal */
.conv-item[data-type="wa"].active{border-left-color:#25d366;background:rgba(37,211,102,.06);}
.conv-item[data-type="email"].active{border-left-color:var(--blue);background:rgba(46,123,246,.08);}
.msg-load-more{padding:8px 12px 12px;text-align:center;}
.btn-load-more{display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:600;color:var(--txt-soft);background:none;border:none;cursor:pointer;transition:color 150ms;}
.btn-load-more:hover{color:var(--blue);}
.msg-main{display:flex;flex-direction:column;flex:1;min-width:0;background:var(--bg);overflow:hidden;border-right:1px solid var(--stroke);position:relative;transition:background 250ms;}
/* Fondo WA: patrón de puntos verde muy sutil */
.msg-main.mode-wa::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle,rgba(37,211,102,.04) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;z-index:0;}
/* Fondo Email: limpio sin patrón */
.msg-main.mode-email::before{display:none;}
.msg-empty{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;color:var(--txt-soft);position:relative;z-index:1;}
.msg-empty-ico{width:72px;height:72px;border-radius:50%;background:var(--panel);border:1px solid var(--stroke);display:grid;place-items:center;opacity:.4;}
.msg-empty p{font-size:15px;font-weight:700;margin:0;color:var(--txt);}
.msg-empty span{font-size:12px;margin:0;}
#msgContent{flex-direction:column;flex:1;overflow:hidden;position:relative;z-index:1;}
.mch{display:flex;align-items:center;gap:12px;padding:11px 16px;background:var(--panel);border-bottom:1px solid var(--stroke);flex:none;}
.mch-av{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;font-size:13px;font-weight:700;color:#fff;flex:none;position:relative;}
.mch-av.blue{background:linear-gradient(135deg,#2e7bf6,#60a5fa);}
.mch-av.green{background:linear-gradient(135deg,#16a34a,#4ade80);}
.mch-av.orange{background:linear-gradient(135deg,#d97706,#fbbf24);}
.mch-av.purple{background:linear-gradient(135deg,#7c3aed,#a78bfa);}
.mch-av.red{background:linear-gradient(135deg,#dc2626,#f87171);}
.mch-av.teal{background:linear-gradient(135deg,#0891b2,#22d3ee);}
.mch-online{position:absolute;bottom:1px;right:1px;width:10px;height:10px;border-radius:50%;background:#25d366;border:2px solid var(--panel);}
.mch-info{flex:1;min-width:0;}
.mch-name{font-size:14px;font-weight:700;color:var(--txt);}
.mch-sub{font-size:11px;color:var(--txt-soft);margin-top:2px;display:flex;align-items:center;gap:5px;}
.mch-sub .dot{width:6px;height:6px;border-radius:50%;background:#25d366;}
/* Badge de canal en header */
.mch-channel-badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;margin-left:4px;}
.mch-channel-badge.wa{background:rgba(37,211,102,.12);color:#25d366;}
.mch-channel-badge.email{background:rgba(46,123,246,.1);color:var(--blue);}
.mch-channel-badge svg{flex:none;}
.mch-actions{display:flex;align-items:center;gap:5px;}
.mch-btn{width:32px;height:32px;border-radius:9px;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);display:grid;place-items:center;cursor:pointer;transition:all 150ms;}
.mch-btn:hover{border-color:var(--blue);color:var(--blue);background:rgba(46,123,246,.08);}
.email-toolbar{display:flex;align-items:center;gap:3px;padding:8px 14px;border-bottom:1px solid var(--stroke);background:var(--panel);flex:none;}
.etb-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;border-radius:8px;border:1px solid var(--stroke);background:transparent;color:var(--txt-soft);font-size:11.5px;font-weight:600;cursor:pointer;white-space:nowrap;font:inherit;transition:all 140ms;}
.etb-btn:hover{background:var(--panel-2);color:var(--txt);border-color:var(--stroke-strong);}
.etb-btn.icon-only{padding:5px 7px;}
.etb-btn.danger:hover{color:var(--red);border-color:rgba(239,68,68,.4);background:rgba(239,68,68,.06);}
.etb-sep{width:1px;height:20px;background:var(--stroke);margin:0 2px;}
.etb-spacer{flex:1;}
/* Helper para ocultar/mostrar elementos de forma confiable */
.hidden{display:none !important;}
.chat-messages{flex:1;overflow-y:auto;padding:18px 20px;display:flex;flex-direction:column;gap:3px;}
.chat-messages::-webkit-scrollbar{width:4px;}
.chat-messages::-webkit-scrollbar-thumb{background:var(--stroke);border-radius:4px;}
.chat-date-div{text-align:center;margin:12px 0;}
.chat-date-div span{background:var(--panel);border:1px solid var(--stroke);border-radius:20px;padding:3px 13px;font-size:10.5px;color:var(--txt-soft);font-weight:600;}
.bubble-row{display:flex;align-items:flex-end;gap:7px;margin-bottom:2px;}
.bubble-row.sent{justify-content:flex-end;}
.bubble-mini-av{width:26px;height:26px;border-radius:50%;font-size:9.5px;font-weight:700;color:#fff;display:grid;place-items:center;flex:none;margin-bottom:2px;}
.bubble-mini-av.blue{background:linear-gradient(135deg,#2e7bf6,#60a5fa);}
.bubble-mini-av.green{background:linear-gradient(135deg,#16a34a,#4ade80);}
.bubble-mini-av.orange{background:linear-gradient(135deg,#d97706,#fbbf24);}
.bubble-mini-av.purple{background:linear-gradient(135deg,#7c3aed,#a78bfa);}
.bubble-mini-av.red{background:linear-gradient(135deg,#dc2626,#f87171);}
.bubble-mini-av.teal{background:linear-gradient(135deg,#0891b2,#22d3ee);}
.bubble{max-width:64%;padding:10px 14px 7px;border-radius:16px;font-size:13px;line-height:1.55;}
.bubble.received{background:var(--panel);border:1px solid var(--stroke);color:var(--txt);border-bottom-left-radius:4px;box-shadow:0 1px 4px rgba(0,0,0,.07);}
.bubble.sent{background:linear-gradient(135deg,#2e7bf6,#4f9cf7);color:#fff;border-bottom-right-radius:4px;box-shadow:0 2px 10px rgba(46,123,246,.3);}
.bubble-time{font-size:10px;opacity:.6;display:flex;align-items:center;justify-content:flex-end;gap:3px;margin-top:4px;}
.email-body-wrap{flex:1;overflow-y:auto;padding:18px 22px 10px;}
.email-body-wrap::-webkit-scrollbar{width:4px;}
.email-body-wrap::-webkit-scrollbar-thumb{background:var(--stroke);border-radius:4px;}
.email-subject{font-size:20px;font-weight:800;color:var(--txt);margin-bottom:16px;letter-spacing:-.01em;}
.email-sender-card{display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-radius:12px;border:1px solid var(--stroke);background:var(--panel);margin-bottom:18px;}
.esc-av{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;font-size:13px;font-weight:700;color:#fff;flex:none;}
.esc-av.blue{background:linear-gradient(135deg,#2e7bf6,#60a5fa);}
.esc-av.green{background:linear-gradient(135deg,#16a34a,#4ade80);}
.esc-av.orange{background:linear-gradient(135deg,#d97706,#fbbf24);}
.esc-av.purple{background:linear-gradient(135deg,#7c3aed,#a78bfa);}
.esc-av.red{background:linear-gradient(135deg,#dc2626,#f87171);}
.esc-av.teal{background:linear-gradient(135deg,#0891b2,#22d3ee);}
.esc-info{flex:1;min-width:0;}
.esc-name{font-size:14px;font-weight:800;color:var(--txt);}
.esc-addr{font-size:12px;color:var(--txt-soft);margin-top:2px;}
.esc-to{font-size:12px;color:var(--txt-soft);margin-top:1px;display:none;}
.esc-meta{display:flex;align-items:center;gap:6px;flex:none;}
.esc-time{font-size:11px;color:var(--txt-soft);}
.btn-star{width:26px;height:26px;border-radius:50%;border:none;background:transparent;color:var(--txt-soft);cursor:pointer;display:grid;place-items:center;transition:color 150ms;}
.btn-star:hover,.btn-star.starred{color:#f59e0b;}
.email-text-body{font-size:14px;line-height:2;color:var(--txt);white-space:pre-line;margin-bottom:20px;}
.att-title{font-size:11px;font-weight:800;color:var(--txt-soft);text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;}
.att-list{display:flex;gap:9px;flex-wrap:wrap;margin-bottom:14px;}
.att-card{display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:10px;border:1px solid var(--stroke);background:var(--panel);min-width:165px;max-width:220px;flex:none;cursor:pointer;transition:border-color 150ms,box-shadow 150ms;}
.att-card:hover{border-color:var(--blue);box-shadow:0 2px 12px rgba(46,123,246,.12);}
.att-icon{width:32px;height:32px;border-radius:8px;display:grid;place-items:center;flex:none;}
.att-icon.pdf{background:rgba(239,68,68,.12);color:var(--red);}
.att-icon.img{background:rgba(46,123,246,.12);color:var(--blue);}
.att-info{flex:1;min-width:0;}
.att-name{font-size:11.5px;font-weight:700;color:var(--txt);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.att-size{font-size:10px;color:var(--txt-soft);margin-top:1px;}
.btn-att-dl{width:25px;height:25px;border-radius:50%;border:1px solid var(--stroke);background:transparent;color:var(--txt-soft);display:grid;place-items:center;cursor:pointer;transition:all 150ms;flex:none;}
.btn-att-dl:hover{border-color:var(--blue);color:var(--blue);}
.msg-input-bar{display:flex;align-items:center;gap:8px;padding:11px 14px 13px;border-top:1px solid var(--stroke);background:var(--panel);flex:none;}
.msg-input-wrap{flex:1;display:flex;align-items:center;gap:5px;padding:9px 12px;border-radius:12px;border:1.5px solid var(--stroke);background:var(--panel-2);transition:border-color 150ms;}
.msg-input-wrap:focus-within{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.1);}
.msg-input-wrap input{flex:1;background:none;border:none;outline:none;color:var(--txt);font:inherit;font-size:13px;}
.msg-input-wrap input::placeholder{color:var(--txt-soft);}
.reply-ico{width:27px;height:27px;border-radius:50%;border:none;background:transparent;color:var(--txt-soft);display:grid;place-items:center;cursor:pointer;transition:color 150ms;flex:none;}
.reply-ico:hover{color:var(--txt);}
.btn-send{width:40px;height:40px;border-radius:12px;border:none;background:var(--blue);color:#fff;display:grid;place-items:center;cursor:pointer;box-shadow:0 3px 14px rgba(46,123,246,.4);transition:opacity 150ms,transform 150ms;flex:none;}
.btn-send:hover{opacity:.9;transform:scale(1.05);}
.channels-panel{display:flex;flex-direction:column;background:var(--panel);overflow:hidden;min-width:0;}
.ch-panel-head{padding:13px 13px 9px;border-bottom:1px solid var(--stroke);}
.ch-panel-head h4{font-size:10.5px;font-weight:800;color:var(--txt-soft);margin:0;text-transform:uppercase;letter-spacing:.08em;}
.ch-scroll{flex:1;overflow-y:auto;}
.ch-scroll::-webkit-scrollbar{width:3px;}
.ch-scroll::-webkit-scrollbar-thumb{background:var(--stroke);border-radius:4px;}
.ch-group{padding:10px 10px 4px;}
.ch-group-title{display:flex;align-items:center;gap:7px;font-size:12px;font-weight:700;color:var(--txt);margin-bottom:6px;}
.ch-icon{width:24px;height:24px;border-radius:7px;display:grid;place-items:center;flex:none;}
.ch-icon.wa{background:rgba(37,211,102,.15);}
.ch-icon.em{background:rgba(46,123,246,.12);}
.ch-item{display:flex;align-items:center;gap:8px;padding:7px;border-radius:9px;cursor:pointer;transition:background 150ms;margin-bottom:2px;}
.ch-item:hover{background:var(--panel-2);}
.ch-item.active{background:rgba(46,123,246,.09);}
.ch-item.active-wa{background:rgba(37,211,102,.07);}
.ch-av{width:29px;height:29px;border-radius:8px;display:grid;place-items:center;font-size:11.5px;font-weight:800;flex:none;}
.ch-av.wa-av{background:rgba(37,211,102,.15);color:#25d366;}
.ch-av.gm-av{background:rgba(234,67,53,.12);color:#ea4335;}
.ch-av.cl-av{background:rgba(0,114,240,.12);color:#0072f0;}
.ch-info{flex:1;min-width:0;}
.ch-name{font-size:11.5px;font-weight:700;color:var(--txt);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.ch-sub{font-size:10px;color:var(--txt-soft);margin-top:1px;}
.ch-badge{min-width:19px;height:19px;border-radius:10px;background:var(--blue);color:#fff;font-size:9.5px;font-weight:800;display:grid;place-items:center;padding:0 4px;flex:none;}
.ch-badge.green{background:#25d366;}
.ch-panel-foot{padding:10px 11px 13px;border-top:1px solid var(--stroke);}
.btn-add-account{display:flex;align-items:center;gap:7px;width:100%;padding:8px 10px;border-radius:9px;border:1.5px dashed var(--stroke);background:transparent;color:var(--blue);font-size:11.5px;font-weight:700;cursor:pointer;font:inherit;transition:background 150ms,border-color 150ms;}
.btn-add-account:hover{background:rgba(46,123,246,.07);border-color:var(--blue);}
.btn-return-study{display:inline-flex;align-items:center;gap:7px;padding:8px 14px;border-radius:10px;border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt);font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;transition:background 150ms,border-color 150ms,color 150ms;}
.btn-return-study:hover{background:var(--hover-bg);border-color:var(--blue);color:var(--blue);}
.btn-ch-toggle{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;border:none;background:var(--blue);color:#fff;font-size:13px;font-weight:700;cursor:pointer;font:inherit;box-shadow:0 3px 14px rgba(46,123,246,.45);transition:all 150ms;white-space:nowrap;}
.btn-ch-toggle:hover{background:#1a6be0;transform:translateY(-1px);}
.btn-ch-toggle.active{background:#1a6be0;box-shadow:0 2px 8px rgba(46,123,246,.35);}
.btn-ch-toggle svg{flex:none;}
.btn-ch-toggle .arrow{transition:transform 250ms;}
.btn-ch-toggle.active .arrow{transform:rotate(180deg);}
.add-acc-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:200;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity 200ms;}
.add-acc-overlay.show{opacity:1;pointer-events:all;}
.add-acc-modal{background:var(--panel);border:1px solid var(--stroke);border-radius:18px;width:420px;max-width:calc(100vw - 32px);box-shadow:0 20px 60px rgba(0,0,0,.4);position:relative;overflow:hidden;transform:translateY(16px) scale(.97);transition:transform 200ms;}
.add-acc-overlay.show .add-acc-modal{transform:translateY(0) scale(1);}
.add-acc-step{padding:28px 28px 24px;}
.add-acc-close{position:absolute;top:14px;right:14px;width:30px;height:30px;border-radius:50%;border:1px solid var(--stroke);background:var(--panel-2);color:var(--txt-soft);display:grid;place-items:center;cursor:pointer;transition:all 140ms;}
.add-acc-close:hover{border-color:var(--red);color:var(--red);}
.add-acc-back{display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:700;color:var(--txt-soft);background:none;border:none;cursor:pointer;margin-bottom:16px;padding:0;transition:color 140ms;}
.add-acc-back:hover{color:var(--blue);}
.add-acc-header{text-align:center;margin-bottom:22px;}
.add-acc-icon-wrap{width:52px;height:52px;border-radius:14px;background:var(--panel-2);border:1px solid var(--stroke);display:grid;place-items:center;margin:0 auto 14px;color:var(--txt-soft);}
.add-acc-icon-wrap.wa-c{background:rgba(37,211,102,.12);border-color:rgba(37,211,102,.3);}
.add-acc-icon-wrap.em-c{background:rgba(46,123,246,.12);border-color:rgba(46,123,246,.3);}
.add-acc-header h3{font-size:17px;font-weight:800;color:var(--txt);margin-bottom:6px;}
.add-acc-header p{font-size:13px;color:var(--txt-soft);}
.add-acc-options{display:flex;flex-direction:column;gap:10px;}
.add-acc-option{display:flex;align-items:center;gap:14px;padding:14px 16px;border-radius:12px;border:1.5px solid var(--stroke);background:var(--panel-2);cursor:pointer;text-align:left;transition:all 150ms;font:inherit;}
.add-acc-option:hover{border-color:var(--stroke-strong);transform:translateX(3px);}
.add-acc-opt-icon{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;flex:none;}
.add-acc-opt-icon.wa{background:rgba(37,211,102,.12);}
.add-acc-opt-icon.em{background:rgba(46,123,246,.12);}
.add-acc-opt-info{flex:1;min-width:0;}
.add-acc-opt-info strong{display:block;font-size:13.5px;font-weight:700;color:var(--txt);}
.add-acc-opt-info span{font-size:11.5px;color:var(--txt-soft);margin-top:2px;display:block;}
.qr-wrap{display:flex;flex-direction:column;align-items:center;gap:10px;margin-bottom:18px;}
.qr-box{width:192px;height:192px;border-radius:14px;border:2px solid var(--stroke);background:#fff;display:grid;place-items:center;position:relative;overflow:hidden;padding:6px;}
.qr-overlay{position:absolute;inset:0;background:rgba(10,15,46,.82);backdrop-filter:blur(4px);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;color:var(--txt-soft);font-size:13px;font-weight:600;border-radius:12px;}
.qr-timer{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--txt-soft);font-weight:600;}
.qr-instructions{display:flex;flex-direction:column;gap:8px;margin-bottom:16px;}
.qr-step{display:flex;align-items:center;gap:10px;font-size:12.5px;color:var(--txt);}
.qr-step span{width:22px;height:22px;border-radius:50%;background:rgba(37,211,102,.15);color:#25d366;font-size:11px;font-weight:800;display:grid;place-items:center;flex:none;}
.btn-refresh-qr{width:100%;padding:10px;border-radius:10px;border:1.5px dashed rgba(37,211,102,.4);background:rgba(37,211,102,.06);color:#25d366;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:7px;cursor:pointer;font:inherit;transition:background 150ms;}
.btn-refresh-qr:hover{background:rgba(37,211,102,.12);}
.email-providers{display:flex;flex-direction:column;gap:10px;}
.email-provider-btn{display:flex;align-items:center;gap:12px;padding:13px 16px;border-radius:12px;border:1.5px solid var(--stroke);background:var(--panel-2);cursor:pointer;text-align:left;transition:all 150ms;font:inherit;}
.email-provider-btn:hover{border-color:var(--blue);background:rgba(46,123,246,.06);transform:translateX(3px);}
.email-provider-btn>svg:first-child{flex:none;}
.email-provider-btn>svg:last-child{color:var(--txt-soft);flex:none;}
.provider-info{flex:1;}
.provider-info strong{display:block;font-size:13px;font-weight:700;color:var(--txt);}
.provider-info span{font-size:11.5px;color:var(--txt-soft);}
.email-divider{display:flex;align-items:center;gap:10px;color:var(--txt-soft);font-size:11.5px;margin:4px 0 2px;}
.email-divider::before,.email-divider::after{content:'';flex:1;height:1px;background:var(--stroke);}
.email-manual{display:flex;gap:8px;}
.email-manual-input{flex:1;padding:9px 13px;border-radius:10px;border:1.5px solid var(--stroke);background:var(--panel-2);color:var(--txt);font:inherit;font-size:13px;outline:none;transition:border-color 150ms;}
.email-manual-input:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(46,123,246,.1);}
.email-manual-input::placeholder{color:var(--txt-soft);}
.btn-manual-connect{display:flex;align-items:center;gap:5px;padding:9px 14px;border-radius:10px;border:none;background:var(--blue);color:#fff;font-size:13px;font-weight:700;cursor:pointer;font:inherit;transition:opacity 150ms;}
.btn-manual-connect:hover{opacity:.88;}
@media(max-width:900px){.msg-page{grid-template-columns:230px 1fr 0;}.channels-panel{display:none;}}
@media(max-width:600px){.msg-page{grid-template-columns:1fr;}.msg-sidebar,.channels-panel{display:none;}}
</style>
@endpush

@section('content')
<div class="msg-page" id="msgPage">

  {{-- SIDEBAR --}}
  <div class="msg-sidebar">
    <div class="msg-search-row">
      <div class="msg-search">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Buscar conversaciones..." id="searchConv" oninput="filterConvs(this.value)">
      </div>
      <button class="btn-filter" title="Filtrar">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      </button>
    </div>
    <div class="msg-tabs">
      <div class="msg-tab active" onclick="switchTab(this,'todas')">Todas</div>
      <div class="msg-tab" onclick="switchTab(this,'noleidos')">No leidos</div>
      <div class="msg-tab" onclick="switchTab(this,'favoritos')">Favoritos</div>
      <div class="msg-tab" onclick="switchTab(this,'archivados')">Archivados</div>
    </div>
    <div class="msg-list" id="convList">
      @php($contactColors = ['blue', 'purple', 'teal', 'orange', 'red', 'green'])
      @forelse($whatsappContacts as $contact)
        @php($contactColor = $contactColors[$loop->index % count($contactColors)])
        <div
          class="conv-item"
          data-tab="{{ $contact['unread'] > 0 ? 'todas noleidos' : 'todas' }}"
          data-type="wa"
          data-patient-id="{{ $contact['id'] }}"
          data-name="{{ $contact['name'] }}"
          data-initials="{{ $contact['initials'] }}"
          data-color="{{ $contactColor }}"
          data-messages-url="{{ route('mensajes.whatsapp.messages', $contact['id']) }}"
          onclick="openWhatsAppPatient(this)">
          <div class="conv-avatar {{ $contactColor }}">{{ $contact['initials'] }}</div>
          <div class="conv-body">
            <div class="conv-name">{{ $contact['name'] }}</div>
            <div class="conv-preview">{{ Str::limit($contact['preview'], 46) }}</div>
          </div>
          <div class="conv-meta">
            <div class="conv-time">{{ $contact['time'] }}</div>
            @if($contact['unread'] > 0)
              <div class="conv-badge">{{ min($contact['unread'], 99) }}</div>
            @endif
            <span class="conv-ch-dot wa" title="WhatsApp"></span>
          </div>
        </div>
      @empty
        <div style="padding:18px;color:var(--txt-soft);font-size:13px;line-height:1.5">
          No hay pacientes con teléfono registrado.
        </div>
      @endforelse
    </div>
    <div class="msg-load-more">
      <button class="btn-load-more">Cargar mas
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
    </div>
  </div>

  {{-- PANEL CENTRAL --}}
  <div class="msg-main">

    <div class="msg-empty" id="msgEmpty">
      <div class="msg-empty-ico">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
      <p>Selecciona una conversacion</p>
      <span>para leer o responder mensajes</span>
    </div>

    <div id="msgContent" style="display:none;">

      {{-- Header --}}
      <div class="mch">
        <div class="mch-av blue" id="mchAv">PX
          <span class="mch-online" id="mchOnline"></span>
        </div>
        <div class="mch-info">
          <div class="mch-name" id="mchName">Selecciona un paciente</div>
          <div class="mch-sub" id="mchSub"><span class="dot"></span><span>En linea</span> <span class="mch-channel-badge wa" id="mchBadge"><svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="#25d366"/></svg>WhatsApp</span></div>
        </div>
        <div class="mch-actions">
          <button class="mch-btn" title="Buscar">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </button>
          <button class="mch-btn" title="Llamada">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.13 11.9a19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 3 1.07h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </button>
          <button class="mch-btn" title="Mas opciones">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
          </button>
        </div>
      </div>

      {{-- Toolbar email --}}
      <div class="email-toolbar" id="emailToolbar" style="display:none;">
        <button class="etb-btn icon-only" onclick="closeMsg()" title="Volver">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="etb-sep"></div>
        <button class="etb-btn">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><polyline points="20 17 15 12 20 7"/></svg>
          Responder
        </button>
        <button class="etb-btn">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 17 20 12 15 7"/><line x1="4" y1="12" x2="20" y2="12"/></svg>
          Reenviar
        </button>
        <button class="etb-btn">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          Archivar
        </button>
        <div class="etb-sep"></div>
        <button class="etb-btn danger">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
          Eliminar
        </button>
        <div class="etb-spacer"></div>
        <button class="etb-btn icon-only">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
        </button>
      </div>

      {{-- Mensajes WhatsApp --}}
      <div class="chat-messages" id="chatMessages" style="display:flex;">
        <div style="margin:auto;color:var(--txt-soft);font-size:13px">Selecciona un paciente para cargar sus mensajes.</div>
      </div>

      {{-- Cuerpo email --}}
      <div class="email-body-wrap" id="emailBody" style="display:none;">
        <div class="email-sender-card">
          <div class="esc-av blue" id="escAv">MG</div>
          <div class="esc-info">
            <div class="esc-name" id="escName">Ana Sanchez</div>
            <div class="esc-addr" id="escAddr">Para Dr. Victor &lt;anas@gmail.com&gt;</div>
          </div>
          <div class="esc-meta">
            <span class="esc-time" id="escTime">Ayer</span>
            <button class="btn-star" id="btnStar" onclick="toggleStar(this)">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </button>
          </div>
        </div>
        <div class="email-subject" id="emailSubject">Resultados de estudios</div>
        <div class="email-text-body" id="emailText">Selecciona un correo para leerlo.</div>
        <div id="attSection" style="display:none;">
          <div class="att-title" id="attTitle">Archivos adjuntos</div>
          <div class="att-list" id="attList"></div>
        </div>
      </div>

      {{-- Input bar --}}
      <div class="msg-input-bar">
        <div class="msg-input-wrap">
          <button class="reply-ico" title="Emoji">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 13s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
          </button>
          <button class="reply-ico" title="Adjuntar">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
          </button>
          <button class="reply-ico wa-only" id="btnAudio" title="Audio">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/></svg>
          </button>
          <input type="text" placeholder="Escribe un mensaje..." id="msgInput" onkeydown="if(event.key==='Enter')sendMsg()">
        </div>
        <button class="btn-send" onclick="sendMsg()" title="Enviar">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
      </div>

    </div>
  </div>

  {{-- PANEL CANALES --}}
  <div class="channels-panel" id="channelsPanel">
    <div class="ch-panel-head">
      <h4>Selecciona un canal</h4>
    </div>
    <div class="ch-scroll">

      <div class="ch-group">
        <div class="ch-group-title">
          <div class="ch-icon wa">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="#25d366"/></svg>
          </div>
          WhatsApp
        </div>
        <div class="ch-item active-wa">
          <div class="ch-av wa-av">W</div>
          <div class="ch-info">
            <div class="ch-name">WhatsApp Cloud API</div>
            <div class="ch-sub">{{ $whatsappBusinessPhone }}</div>
          </div>
          <div class="ch-badge {{ $whatsappConfigured && $whatsappWebhookConfigured ? 'green' : '' }}">
            {{ ! $whatsappConfigured ? 'Falta configurar' : ($whatsappWebhookConfigured ? 'Activo' : 'Falta App Secret') }}
          </div>
        </div>
      </div>

    </div>
    <div class="ch-panel-foot">
      <button class="btn-add-account" type="button" disabled>
        Administrado desde Meta
      </button>
    </div>
  </div>

</div>

{{-- MODAL AGREGAR CUENTA --}}
<div class="add-acc-overlay" id="addAccOverlay" onclick="if(event.target===this)closeModal()">
  <div class="add-acc-modal">
    <button class="add-acc-close" onclick="closeModal()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    {{-- Paso 1: elegir canal --}}
    <div class="add-acc-step" id="modalStep1">
      <div class="add-acc-header">
        <div class="add-acc-icon-wrap">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <h3>Agregar cuenta</h3>
        <p>Conecta un nuevo canal de comunicacion</p>
      </div>
      <div class="add-acc-options">
        <button class="add-acc-option" onclick="goModalStep('wa')">
          <div class="add-acc-opt-icon wa">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="#25d366"/><path d="M12.004 2C6.477 2 2 6.477 2 12.004c0 1.765.46 3.424 1.268 4.868L2 22l5.234-1.237A9.957 9.957 0 0 0 12.004 22C17.523 22 22 17.523 22 12.004 22 6.477 17.523 2 12.004 2z" stroke="#25d366" stroke-width="1.5" fill="none"/></svg>
          </div>
          <div class="add-acc-opt-info">
            <strong>WhatsApp</strong>
            <span>Conecta mediante codigo QR</span>
          </div>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
        <button class="add-acc-option" onclick="goModalStep('email')">
          <div class="add-acc-opt-icon em">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div class="add-acc-opt-info">
            <strong>Correo Electronico</strong>
            <span>Gmail, Outlook o IMAP manual</span>
          </div>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
    </div>

    {{-- Paso 2a: WhatsApp QR --}}
    <div class="add-acc-step" id="modalStepWa" style="display:none;">
      <button class="add-acc-back" onclick="goModalStep('main')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Volver
      </button>
      <div class="add-acc-header">
        <div class="add-acc-icon-wrap wa-c">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="#25d366"/><path d="M12.004 2C6.477 2 2 6.477 2 12.004c0 1.765.46 3.424 1.268 4.868L2 22l5.234-1.237A9.957 9.957 0 0 0 12.004 22C17.523 22 22 17.523 22 12.004 22 6.477 17.523 2 12.004 2z" stroke="#25d366" stroke-width="1.5" fill="none"/></svg>
        </div>
        <h3>Conectar WhatsApp</h3>
        <p>Escanea el codigo QR desde tu telefono</p>
      </div>
      <div class="qr-wrap">
        <div class="qr-box">
          <canvas id="qrCanvas" width="180" height="180"></canvas>
          <div class="qr-overlay" id="qrOverlay" style="display:none;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Codigo expirado
          </div>
        </div>
        <div class="qr-timer">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Expira en <strong id="qrCountdown">60</strong>s
        </div>
      </div>
      <div class="qr-instructions">
        <div class="qr-step"><span>1</span>Abre WhatsApp en tu telefono</div>
        <div class="qr-step"><span>2</span>Ve a Dispositivos vinculados</div>
        <div class="qr-step"><span>3</span>Escanea este codigo QR</div>
      </div>
      <button class="btn-refresh-qr" onclick="refreshQR()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
        Actualizar codigo
      </button>
    </div>

    {{-- Paso 2b: Email --}}
    <div class="add-acc-step" id="modalStepEmail" style="display:none;">
      <button class="add-acc-back" onclick="goModalStep('main')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Volver
      </button>
      <div class="add-acc-header">
        <div class="add-acc-icon-wrap em-c">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <h3>Conectar Correo</h3>
        <p>Selecciona tu proveedor o ingresa manualmente</p>
      </div>
      <div class="email-providers">
        <button class="email-provider-btn" onclick="connectEmail('google')">
          <svg width="20" height="20" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          <div class="provider-info">
            <strong>Continuar con Google</strong>
            <span>Conecta tu cuenta de Gmail</span>
          </div>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
        <button class="email-provider-btn" onclick="connectEmail('outlook')">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#0072C6"/><path d="M13 5h8v14h-8V5z" fill="#fff" opacity=".2"/><path d="M3 7l10-2v14L3 17V7z" fill="#fff"/><ellipse cx="8" cy="12" rx="3" ry="3.5" fill="#0072C6"/></svg>
          <div class="provider-info">
            <strong>Continuar con Outlook</strong>
            <span>Conecta tu cuenta de Microsoft</span>
          </div>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
      <div class="email-divider">o ingresa manualmente</div>
      <div class="email-manual">
        <input type="email" class="email-manual-input" id="manualEmail" placeholder="correo@ejemplo.com">
        <button class="btn-manual-connect" onclick="connectEmail('manual')">Conectar</button>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  let activeTab = 'todas';
  let activeType = 'wa';
  let activePatientId = null;
  let activePatientElement = null;
  const loadedMessageIds = new Set();
  const launchContext = @json($whatsappLaunchContext);
  const csrfToken = @json(csrf_token());
  const whatsappSendUrl = @json(route('mensajes.whatsapp.send'));
  const whatsappConfigured = @json($whatsappConfigured);

  window.toggleChPanel = function() {
    const page = document.getElementById('msgPage');
    const btn  = document.querySelector('.btn-ch-toggle');
    page.classList.toggle('ch-open');
    btn.classList.toggle('active');
  };

  window.switchTab = function(tab, name) {
    document.querySelectorAll('.msg-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    activeTab = name;
    filterConvs(document.getElementById('searchConv').value);
  };

  window.filterConvs = function(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.conv-item').forEach(item => {
      const name = item.querySelector('.conv-name').textContent.toLowerCase();
      const tabs = item.dataset.tab || '';
      const matchTab = activeTab === 'todas' ? true : tabs.includes(activeTab);
      const matchQuery = name.includes(q);
      item.style.display = (matchTab && matchQuery) ? '' : 'none';
    });
  };

  window.openConv = function(el, initials, name, color, type, text, time, attachments) {
    console.log('openConv called:', {name, type, text: text?.substring(0, 30)});
    
    document.querySelectorAll('.conv-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    activeType = type;

    document.getElementById('msgEmpty').style.display = 'none';
    const content = document.getElementById('msgContent');
    content.style.display = 'flex';

    const av = document.getElementById('mchAv');
    av.textContent = initials;
    av.className = 'mch-av ' + color;
    // Limpiar dot online anterior si existe
    const existingOnline = av.querySelector('.mch-online');
    if (existingOnline) existingOnline.remove();
    
    if (type === 'wa') {
      const online = document.createElement('span');
      online.className = 'mch-online';
      av.appendChild(online);
    }

    document.getElementById('mchName').textContent = name;

    const badge = document.getElementById('mchBadge');
    if (type === 'wa') {
      document.getElementById('mchSub').innerHTML = '<span class="dot"></span><span>En linea</span>';
      badge.className = 'mch-channel-badge wa';
      badge.innerHTML = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="#25d366"/></svg>WhatsApp';
    } else {
      document.getElementById('mchSub').innerHTML = '<span style="width:6px;height:6px;border-radius:50%;background:var(--blue);display:inline-block;"></span><span>Correo</span>';
      badge.className = 'mch-channel-badge email';
      badge.innerHTML = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Correo';
    }

    const convBadge = el.querySelector('.conv-badge');
    if (convBadge) convBadge.remove();

    const mainPanel = document.querySelector('.msg-main');
    const mchHeader  = document.querySelector('.mch');
    const audioBtn   = document.getElementById('btnAudio');
    const msgInput   = document.getElementById('msgInput');

    console.log('Switching to type:', type);
    
    if (type === 'wa') {
      console.log('Showing WhatsApp view');
      mainPanel.className = 'msg-main mode-wa';
      mchHeader.className = 'mch mode-wa';
      audioBtn.style.display = '';
      msgInput.placeholder = 'Escribe un mensaje...';
      document.getElementById('chatMessages').style.display = 'flex';
      document.getElementById('emailBody').style.display = 'none';
      document.getElementById('emailToolbar').style.display = 'none';
      document.querySelectorAll('.bubble-mini-av').forEach(av => {
        av.textContent = initials;
        av.className = 'bubble-mini-av ' + color;
      });
      // Actualizar contenido del mensaje de WhatsApp
      const waMessageText = document.getElementById('waMessageText');
      const waMessageTime = document.getElementById('waMessageTime');
      if (waMessageText && text) {
        // Mantener el div de tiempo pero cambiar el texto
        const timeDiv = waMessageText.querySelector('.bubble-time');
        waMessageText.innerHTML = text.replace(/\n/g, '<br>') + '<div class="bubble-time" id="waMessageTime">' + (timeDiv ? timeDiv.textContent : time) + '</div>';
      }
    } else {
      console.log('Showing Email view');
      mainPanel.className = 'msg-main mode-email';
      mchHeader.className = 'mch mode-email';
      audioBtn.style.display = 'none';
      msgInput.placeholder = 'Escribe tu respuesta al correo...';
      document.getElementById('chatMessages').style.display = 'none';
      document.getElementById('emailBody').style.display = 'block';
      document.getElementById('emailToolbar').style.display = 'flex';

      document.getElementById('escAv').textContent = initials;
      document.getElementById('escAv').className = 'esc-av ' + color;
      document.getElementById('escName').textContent = name;
      const emailAddr = name.toLowerCase().replace(/\s+/g, '') + '@gmail.com';
      document.getElementById('escAddr').innerHTML = 'Para Dr. Victor &lt;' + emailAddr + '&gt;';
      document.getElementById('escTime').textContent = time;
      // Generar asunto dinámico según el contenido
      let subject = 'Mensaje de ' + name;
      const lowerText = text.toLowerCase();
      if (lowerText.includes('estudio') || lowerText.includes('resultado') || lowerText.includes('laboratorio')) {
        subject = 'Resultados de estudios';
      } else if (lowerText.includes('cita') || lowerText.includes('agendar') || lowerText.includes('próxima')) {
        subject = 'Agendamiento de cita';
      } else if (lowerText.includes('procedimiento') || lowerText.includes('operación') || lowerText.includes('cirugía')) {
        subject = 'Seguimiento de procedimiento';
      } else if (lowerText.includes('receta') || lowerText.includes('medicamento') || lowerText.includes('fármaco')) {
        subject = 'Consulta sobre medicamentos';
      }
      document.getElementById('emailSubject').textContent = subject;
      document.getElementById('emailText').innerHTML = text.replace(/\n/g, '<br>');

      const attSec = document.getElementById('attSection');
      const attList = document.getElementById('attList');
      attList.innerHTML = '';
      if (attachments && attachments.length > 0) {
        attSec.style.display = 'block';
        document.getElementById('attTitle').textContent = attachments.length + ' archivo(s) adjunto(s)';
        attachments.forEach(a => {
          const parts = a.split('|');
          const fname = parts[0], fsize = parts[1], ftype = parts[2];
          const icon = ftype === 'pdf'
            ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>'
            : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';
          attList.innerHTML += `<div class="att-card"><div class="att-icon ${ftype}">${icon}</div><div class="att-info"><div class="att-name">${fname}</div><div class="att-size">${fsize}</div></div><button class="btn-att-dl" title="Descargar"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button></div>`;
        });
      } else {
        attSec.style.display = 'none';
      }
    }
  };

  function appendWhatsAppMessage(message, initials, color) {
    const msgs = document.getElementById('chatMessages');
    const row = document.createElement('div');
    const received = message.direction === 'inbound';
    row.className = 'bubble-row ' + (received ? 'received' : 'sent');
    if (message.id !== undefined && message.id !== null) {
      loadedMessageIds.add(String(message.id));
      row.dataset.messageId = String(message.id);
    }

    if (received) {
      const miniAvatar = document.createElement('div');
      miniAvatar.className = 'bubble-mini-av ' + color;
      miniAvatar.textContent = initials;
      row.appendChild(miniAvatar);
    }

    const bubble = document.createElement('div');
    bubble.className = 'bubble ' + (received ? 'received' : 'sent');
    bubble.appendChild(document.createTextNode(message.body || ''));

    const time = document.createElement('div');
    time.className = 'bubble-time';
    time.textContent = message.time || '';

    if (!received) {
      const state = document.createElement('span');
      state.textContent = message.status === 'read' ? ' Leído' : ' Enviado';
      state.title = message.status || 'enviado';
      time.appendChild(state);
    }

    bubble.appendChild(time);
    row.appendChild(bubble);
    msgs.appendChild(row);
  }

  async function loadWhatsAppMessages(el, silent = false) {
    const msgs = document.getElementById('chatMessages');
    if (!silent) {
      loadedMessageIds.clear();
      msgs.innerHTML = '<div class="chat-placeholder" style="margin:auto;color:var(--txt-soft);font-size:13px">Cargando mensajes...</div>';
    }

    try {
      const response = await fetch(el.dataset.messagesUrl, {
        headers: {'Accept': 'application/json'}
      });
      const payload = await response.json();

      if (!response.ok) {
        throw new Error(payload.message || 'No se pudieron cargar los mensajes.');
      }

      if (activePatientElement !== el) return;

      if (!silent) {
        msgs.innerHTML = '';
      }

      if (!payload.messages.length) {
        if (!silent) {
          msgs.innerHTML = '<div class="chat-placeholder" style="margin:auto;color:var(--txt-soft);font-size:13px">Todavía no hay mensajes con este paciente.</div>';
        }
        return;
      }

      const newMessages = payload.messages.filter(message => !loadedMessageIds.has(String(message.id)));

      if (newMessages.length && msgs.querySelector('.chat-placeholder')) {
        msgs.innerHTML = '';
      }

      newMessages.forEach(message => {
        appendWhatsAppMessage(message, el.dataset.initials, el.dataset.color);
      });

      if (newMessages.length) {
        const latest = newMessages[newMessages.length - 1];
        const preview = el.querySelector('.conv-preview');
        if (preview) {
          preview.textContent = latest.body.length > 46 ? latest.body.slice(0, 46) + '…' : latest.body;
        }
        msgs.scrollTop = msgs.scrollHeight;
      }
    } catch (error) {
      if (silent || activePatientElement !== el) return;
      msgs.innerHTML = '';
      const notice = document.createElement('div');
      notice.style.cssText = 'margin:auto;color:var(--red);font-size:13px';
      notice.textContent = error.message;
      msgs.appendChild(notice);
    }
  }

  window.openWhatsAppPatient = function(el) {
    activePatientId = Number(el.dataset.patientId);
    activePatientElement = el;
    loadedMessageIds.clear();
    openConv(
      el,
      el.dataset.initials,
      el.dataset.name,
      el.dataset.color,
      'wa',
      '',
      '',
      []
    );
    loadWhatsAppMessages(el);
  };

  window.sendMsg = async function() {
    const input = document.getElementById('msgInput');
    const text = input.value.trim();
    if (!text) return;

    if (activeType === 'wa') {
      if (!whatsappConfigured) {
        alert('La cuenta de WhatsApp todavía no está configurada completamente.');
        return;
      }
      if (!activePatientId || !activePatientElement) {
        alert('Selecciona un paciente antes de enviar.');
        return;
      }

      const sendButton = document.querySelector('.btn-send');
      sendButton.disabled = true;
      input.disabled = true;

      try {
        const response = await fetch(whatsappSendUrl, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            paciente_id: activePatientId,
            message: text
          })
        });
        const payload = await response.json();

        if (!response.ok) {
          throw new Error(payload.message || 'WhatsApp rechazó el mensaje.');
        }

        const msgs = document.getElementById('chatMessages');
        if (msgs.querySelector('.chat-placeholder')) {
          msgs.innerHTML = '';
        }
        appendWhatsAppMessage(payload.data, activePatientElement.dataset.initials, activePatientElement.dataset.color);
        msgs.scrollTop = msgs.scrollHeight;
        input.value = '';

        const preview = activePatientElement.querySelector('.conv-preview');
        if (preview) {
          preview.textContent = text.length > 46 ? text.slice(0, 46) + '…' : text;
        }
      } catch (error) {
        alert(error.message);
      } finally {
        sendButton.disabled = false;
        input.disabled = false;
        input.focus();
      }
    }
  };

  window.closeMsg = function() {
    document.getElementById('msgContent').style.display = 'none';
    document.getElementById('msgEmpty').style.display = 'flex';
    document.querySelectorAll('.conv-item').forEach(i => i.classList.remove('active'));
  };

  window.toggleStar = function(btn) {
    btn.classList.toggle('starred');
  };

  window.selectChannel = function(el) {
    document.querySelectorAll('.ch-item').forEach(i => i.classList.remove('active','active-wa'));
    el.classList.add('active');
  };

  window.openModal = function() {
    goModalStep('main');
    document.getElementById('addAccOverlay').classList.add('show');
  };
  window.closeModal = function() {
    document.getElementById('addAccOverlay').classList.remove('show');
  };

  window.goModalStep = function(step) {
    document.getElementById('modalStep1').style.display = 'none';
    document.getElementById('modalStepWa').style.display = 'none';
    document.getElementById('modalStepEmail').style.display = 'none';
    if (step === 'main') document.getElementById('modalStep1').style.display = 'block';
    else if (step === 'wa') { document.getElementById('modalStepWa').style.display = 'block'; startQR(); }
    else if (step === 'email') document.getElementById('modalStepEmail').style.display = 'block';
  };

  let qrTimer;
  function startQR() {
    clearInterval(qrTimer);
    document.getElementById('qrOverlay').style.display = 'none';
    drawQR();
    let secs = 60;
    document.getElementById('qrCountdown').textContent = secs;
    qrTimer = setInterval(function() {
      secs--;
      document.getElementById('qrCountdown').textContent = secs;
      if (secs <= 0) { clearInterval(qrTimer); document.getElementById('qrOverlay').style.display = 'flex'; }
    }, 1000);
  }

  window.refreshQR = function() { startQR(); };

  function drawQR() {
    const canvas = document.getElementById('qrCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const size = 180, cell = size / 25;
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, size, size);
    ctx.fillStyle = '#111827';
    for (let r = 0; r < 25; r++) {
      for (let c = 0; c < 25; c++) {
        if (Math.random() > 0.5) { ctx.fillRect(c * cell, r * cell, cell - 1, cell - 1); }
      }
    }
    [[0,0],[18,0],[0,18]].forEach(([cx,cy]) => {
      ctx.fillStyle = '#111827';
      ctx.fillRect(cx*cell, cy*cell, 7*cell, 7*cell);
      ctx.fillStyle = '#ffffff';
      ctx.fillRect((cx+1)*cell, (cy+1)*cell, 5*cell, 5*cell);
      ctx.fillStyle = '#111827';
      ctx.fillRect((cx+2)*cell, (cy+2)*cell, 3*cell, 3*cell);
    });
  }

  window.connectEmail = function(provider) {
    if (provider === 'google') {
      alert('Redirigiendo a Google OAuth...\n(Implementa la ruta OAuth en tu backend Laravel)');
    } else if (provider === 'outlook') {
      alert('Redirigiendo a Microsoft OAuth...\n(Implementa la ruta OAuth en tu backend Laravel)');
    } else {
      const email = document.getElementById('manualEmail').value.trim();
      if (!email) { document.getElementById('manualEmail').focus(); return; }
      alert('Conectando: ' + email + '\n(Implementa IMAP/SMTP en tu backend Laravel)');
    }
  };

  function buildStudyDraft(data) {
    const patient = data.patient || 'paciente';
    const study = data.study || 'tu estudio';
    const mediaLabel = data.type === 'imagen' ? 'la imagen' : 'el video';
    const mediaId = data.type === 'imagen' ? data.image : data.video;
    const media = mediaId ? ` (${mediaId})` : '';
    const date = data.date ? ` del ${data.date}` : '';
    const frame = data.type === 'imagen' && data.frame ? ` Fotograma: ${data.frame}.` : '';
    const diagnosis = data.diagnosis ? ` Diagnostico: ${data.diagnosis}.` : '';

    return `Hola ${patient}, te comparto ${mediaLabel} de ${study}${media}${date}.${frame}${diagnosis}`;
  }

  function openWhatsAppLaunch(data) {
    const waConvs = Array.from(document.querySelectorAll('.conv-item[data-type="wa"]'));
    const patientId = Number(data.patient_id || 0);
    const patient = (data.patient || '').toLowerCase();
    const target = waConvs.find(item => {
      if (patientId && Number(item.dataset.patientId) === patientId) return true;
      const name = item.querySelector('.conv-name')?.textContent.toLowerCase() || '';
      return patient && (name === patient || name.includes(patient.split(' ')[0]));
    }) || (!patientId && !patient ? waConvs[0] : null);

    if (target) target.click();

    const input = document.getElementById('msgInput');
    const hasStudyDraft = data.study || data.video || data.image || data.type || data.date || data.diagnosis;
    if (input && hasStudyDraft) {
      input.value = buildStudyDraft(data);
      input.focus();
    } else if (input && target) {
      input.focus();
    }
  }

  if (launchContext.channel === 'whatsapp') {
    openWhatsAppLaunch(launchContext);
  } else {
    const first = document.querySelector('.conv-item[data-type="wa"]');
    if (first) first.click();
  }
  window.__createOrOpenChat = function(name, message) {
    const convList = document.getElementById('convList');
    if (!convList) return;
    const item = Array.from(convList.querySelectorAll('.conv-item')).find(item => {
      const n = item.querySelector('.conv-name');
      return n && n.textContent.trim().toLowerCase() === name.trim().toLowerCase();
    });

    if (!item) {
      alert('No se encontró un paciente con teléfono registrado para iniciar este chat.');
      return;
    }

    item.click();

    if (message) {
      setTimeout(() => {
        const input = document.getElementById('msgInput');
        input.value = message;
        input.focus();
      }, 100);
    }
  };

  // Abrir chat automáticamente si llega ?paciente= desde la agenda
  (function(){
    const p = new URLSearchParams(window.location.search).get('paciente');
    if (p) {
      window.__createOrOpenChat(p);
      if (window.history.replaceState) {
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    }
  })();

  // Crear chat pendiente desde agendar cita
  (function(){
    const raw = localStorage.getItem('pendingChat');
    if (!raw) return;
    try {
      const data = JSON.parse(raw);
      if (data && data.name) {
        window.__createOrOpenChat(data.name, data.message);
      }
      localStorage.removeItem('pendingChat');
    } catch(e) {
      localStorage.removeItem('pendingChat');
    }
  })();

  window.setInterval(function() {
    if (activePatientElement && document.visibilityState === 'visible') {
      loadWhatsAppMessages(activePatientElement, true);
    }
  }, 5000);

})();
</script>
@endpush
