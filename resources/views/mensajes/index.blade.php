@extends('layouts.app')

@section('title', 'Mensajes')
@section('active', 'mensajes')
@section('header-title', 'Mensajes')
@section('header-sub')
  Comunicaciones con pacientes
@endsection

@push('styles')
<style>
/* ============ MENSAJES ============ */

.msg-root {
  display: grid;
  grid-template-columns: 480px 1fr;
  gap: 0;
  height: calc(100vh - 130px);
  background: var(--card);
  border: 1px solid var(--stroke);
  border-radius: var(--r-lg);
  overflow: hidden;
}

/* ---- Panel izquierdo ---- */
.msg-left {
  display: flex;
  flex-direction: column;
  border-right: 1px solid var(--stroke);
  background: var(--panel);
  min-width: 0;
}

/* Tabs WhatsApp / Correo */
.msg-tabs {
  display: grid;
  grid-template-columns: 1fr 1fr;
  padding: 14px 14px 0;
  gap: 8px;
}
.msg-tab {
  display: flex; align-items: center; justify-content: center; gap: 9px;
  padding: 13px 10px; border-radius: 12px 12px 0 0;
  font: inherit; font-size: 14px; font-weight: 700; cursor: pointer;
  border: 1px solid var(--stroke); border-bottom: none;
  background: var(--panel-2); color: var(--txt-soft);
  transition: background 150ms, color 150ms;
  position: relative;
}
.msg-tab.active-wa  { background: #1a3a1a; color: #25d366; border-color: rgba(37,211,102,.35); }
.msg-tab.active-mail{ background: #3a1a1a; color: #f06060; border-color: rgba(240,96,96,.35); }
.msg-tab:not(.active-wa):not(.active-mail):hover { background: var(--card); color: var(--txt); }

.msg-tab .tab-ico-wa   { color: #25d366; }
.msg-tab .tab-ico-mail { color: #f06060; }

/* Buscador */
.msg-search-bar {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 14px;
  border-bottom: 1px solid var(--stroke);
}
.msg-search-wrap {
  position: relative; flex: 1;
}
.msg-search-wrap svg {
  position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
  color: var(--txt-soft); pointer-events: none;
}
.msg-search {
  width: 100%; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: 10px;
  padding: 9px 12px 9px 34px; font: inherit; font-size: 13px;
  color: var(--txt); outline: none; transition: border-color 150ms;
}
.msg-search::placeholder { color: var(--off); }
.msg-search:focus { border-color: var(--blue); }
.msg-filter-ico {
  width: 34px; height: 34px; border-radius: 9px;
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  display: grid; place-items: center; cursor: pointer; color: var(--txt-soft);
  transition: background 150ms, color 150ms;
}
.msg-filter-ico:hover { background: var(--card); color: var(--cyan); }

/* Sub-tabs: Todos / No leidos / Archivados */
.msg-subtabs {
  display: flex; gap: 0;
  padding: 0 14px;
  border-bottom: 1px solid var(--stroke);
}
.msg-subtab {
  padding: 10px 14px; font-size: 13px; font-weight: 600;
  color: var(--txt-soft); cursor: pointer; border: none; background: none;
  border-bottom: 2px solid transparent; transition: color 150ms, border-color 150ms;
  font: inherit; font-size: 13px; font-weight: 600;
}
.msg-subtab.active { color: var(--cyan); border-bottom-color: var(--cyan); }
.msg-subtab:hover:not(.active) { color: var(--txt); }

/* Lista de conversaciones */
.msg-list {
  flex: 1; overflow-y: auto; padding: 6px 0;
}
.msg-list::-webkit-scrollbar { width: 4px; }
.msg-list::-webkit-scrollbar-track { background: transparent; }
.msg-list::-webkit-scrollbar-thumb { background: var(--stroke-strong); border-radius: 4px; }

.msg-conv {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 16px; cursor: pointer;
  border-bottom: 1px solid var(--stroke);
  transition: background 120ms;
  position: relative;
}
.msg-conv:last-child { border-bottom: none; }
.msg-conv:hover { background: rgba(46,123,246,.06); }
.msg-conv.active { background: rgba(46,123,246,.12); }

.msg-av {
  width: 42px; height: 42px; border-radius: 50%;
  background: linear-gradient(135deg, var(--blue), var(--cyan));
  display: grid; place-items: center;
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: 13px;
  flex: none; color: #fff;
}
.msg-av.av-green { background: linear-gradient(135deg,#1a8c4e,#25d366); }
.msg-av.av-orange { background: linear-gradient(135deg,#b55a00,#f59e2d); }
.msg-av.av-purple { background: linear-gradient(135deg,#5b21b6,#8b5cf6); }
.msg-av.av-red    { background: linear-gradient(135deg,#991b1b,#ef4444); }

.msg-conv-body { flex: 1; min-width: 0; }
.msg-conv-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 3px; }
.msg-conv-name { font-size: 14px; font-weight: 700; color: var(--txt); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.msg-conv-time { font-size: 11px; color: var(--txt-soft); flex: none; margin-left: 8px; }
.msg-conv-preview { font-size: 12.5px; color: var(--txt-soft); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.msg-badge {
  min-width: 20px; height: 20px; border-radius: 99px;
  background: var(--blue); color: #fff;
  font-size: 11px; font-weight: 700;
  display: grid; place-items: center;
  padding: 0 5px; flex: none;
}
.msg-badge.green { background: #25d366; }

/* Footer conexion */
.msg-conn {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 16px;
  border-top: 1px solid var(--stroke);
  font-size: 12.5px; color: var(--txt-soft);
}
.msg-conn-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: #25d366; flex: none;
  box-shadow: 0 0 6px rgba(37,211,102,.7);
}
.msg-conn-dot.red { background: var(--red); box-shadow: 0 0 6px rgba(255,90,110,.7); }
.msg-conn strong { color: var(--txt); font-size: 13px; }
.msg-conn-change {
  margin-left: auto; font-size: 12px; font-weight: 600; color: var(--blue); cursor: pointer;
  background: none; border: none; font: inherit; font-size: 12px; font-weight: 600; color: var(--blue);
}
.msg-conn-change:hover { color: var(--cyan); }

/* ---- Panel derecho: WhatsApp chat ---- */
.msg-right {
  display: flex; flex-direction: column;
  background: var(--panel-2);
  min-width: 0;
}

/* Header del chat */
.chat-head {
  display: flex; align-items: center; gap: 12px;
  padding: 14px 20px;
  border-bottom: 1px solid var(--stroke);
  background: var(--panel);
}
.chat-head-av {
  width: 40px; height: 40px; border-radius: 50%;
  background: linear-gradient(135deg, var(--blue), var(--cyan));
  display: grid; place-items: center;
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: 13px;
  flex: none; color: #fff;
}
.chat-head-info { flex: 1; }
.chat-head-name { font-size: 15px; font-weight: 700; }
.chat-head-sub  { font-size: 12px; color: var(--txt-soft); }
.chat-head-actions { display: flex; gap: 6px; }
.chat-head-btn {
  width: 36px; height: 36px; border-radius: 9px;
  border: 1px solid var(--stroke); background: var(--panel-2);
  display: grid; place-items: center; cursor: pointer; color: var(--txt-soft);
  transition: background 150ms, color 150ms;
}
.chat-head-btn:hover { background: var(--card); color: var(--cyan); }

/* Mensajes */
.chat-messages {
  flex: 1; overflow-y: auto;
  padding: 20px 20px 12px;
  display: flex; flex-direction: column; gap: 10px;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: var(--stroke-strong); border-radius: 4px; }

.chat-date-label {
  text-align: center;
  margin: 6px 0;
}
.chat-date-label span {
  background: var(--panel); border: 1px solid var(--stroke);
  border-radius: 99px; padding: 4px 14px;
  font-size: 12px; font-weight: 600; color: var(--txt-soft);
}

.chat-bubble-wrap {
  display: flex;
  align-items: flex-end; gap: 8px;
}
.chat-bubble-wrap.mine { flex-direction: row-reverse; }

.chat-bubble {
  max-width: 68%; padding: 11px 14px;
  border-radius: 16px; font-size: 13.5px; line-height: 1.5;
  position: relative;
}
.chat-bubble.theirs {
  background: var(--card);
  border: 1px solid var(--stroke);
  border-bottom-left-radius: 4px;
  color: var(--txt);
}
.chat-bubble.mine {
  background: linear-gradient(135deg,#1668D9,var(--blue));
  border-bottom-right-radius: 4px;
  color: #fff;
}
.chat-bubble-time {
  font-size: 10.5px; margin-top: 4px; text-align: right;
  opacity: .65;
}

/* Input de mensaje WA */
.chat-input-bar {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 16px;
  border-top: 1px solid var(--stroke);
  background: var(--panel);
}
.chat-input {
  flex: 1; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: 24px;
  padding: 11px 18px; font: inherit; font-size: 13.5px;
  color: var(--txt); outline: none; transition: border-color 150ms;
}
.chat-input::placeholder { color: var(--off); }
.chat-input:focus { border-color: var(--blue); }
.chat-input-btn {
  width: 38px; height: 38px; border-radius: 50%;
  border: none; background: none; cursor: pointer;
  display: grid; place-items: center;
  color: var(--txt-soft); transition: color 150ms;
}
.chat-input-btn:hover { color: var(--cyan); }
.chat-send-btn {
  width: 40px; height: 40px; border-radius: 50%;
  background: linear-gradient(135deg,#1668D9,var(--blue));
  border: none; cursor: pointer; display: grid; place-items: center;
  color: #fff; box-shadow: 0 4px 14px -4px rgba(46,123,246,.6);
  transition: opacity 150ms, transform 150ms;
}
.chat-send-btn:hover { opacity: .9; }
.chat-send-btn:active { transform: scale(.94); }

/* ---- Panel derecho: Correo ---- */
.mail-head-bar {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 16px;
  border-bottom: 1px solid var(--stroke);
  background: var(--panel);
  flex-wrap: wrap;
}
.mail-head-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 12px; border-radius: 8px;
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  font: inherit; font-size: 12.5px; font-weight: 600; color: var(--txt-soft);
  cursor: pointer; transition: background 150ms, color 150ms;
}
.mail-head-btn:hover { background: var(--card); color: var(--txt); }
.mail-head-btn.danger:hover { color: var(--red); border-color: rgba(255,90,110,.4); }
.mail-head-btn svg { flex: none; }

.mail-body {
  flex: 1; overflow-y: auto; padding: 20px 24px;
  display: flex; flex-direction: column; gap: 16px;
}
.mail-body::-webkit-scrollbar { width: 4px; }
.mail-body::-webkit-scrollbar-track { background: transparent; }
.mail-body::-webkit-scrollbar-thumb { background: var(--stroke-strong); border-radius: 4px; }

.mail-from {
  display: flex; align-items: center; gap: 12px;
}
.mail-from-av {
  width: 44px; height: 44px; border-radius: 50%;
  background: linear-gradient(135deg, var(--blue), var(--cyan));
  display: grid; place-items: center;
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: 14px;
  flex: none; color: #fff;
}
.mail-from-info { flex: 1; }
.mail-from-name { font-size: 15px; font-weight: 700; }
.mail-from-meta { font-size: 12px; color: var(--txt-soft); }
.mail-from-time { font-size: 12px; color: var(--txt-soft); display: flex; align-items: center; gap: 8px; }
.mail-star { color: var(--txt-soft); cursor: pointer; background: none; border: none; transition: color 150ms; }
.mail-star:hover, .mail-star.active { color: var(--orange); }

.mail-subject { font-size: 18px; font-weight: 700; }
.mail-content { font-size: 14px; line-height: 1.75; color: var(--txt-soft); }
.mail-content strong { color: var(--txt); }

.mail-attachments-title {
  font-size: 12px; font-weight: 700; text-transform: uppercase;
  letter-spacing: .06em; color: var(--txt-soft); margin-bottom: 8px;
}
.mail-attachments { display: flex; gap: 10px; flex-wrap: wrap; }
.mail-attach-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px; border-radius: 10px;
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  cursor: pointer; transition: background 150ms, border-color 150ms;
}
.mail-attach-item:hover { background: var(--card); border-color: var(--blue); }
.mail-attach-ico { color: var(--blue); flex: none; }
.mail-attach-info .name { font-size: 13px; font-weight: 600; }
.mail-attach-info .size { font-size: 11px; color: var(--txt-soft); }
.mail-attach-dl { color: var(--txt-soft); cursor: pointer; background: none; border: none; margin-left: 6px; transition: color 150ms; }
.mail-attach-dl:hover { color: var(--cyan); }

/* Input correo */
.mail-reply-bar {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 16px;
  border-top: 1px solid var(--stroke);
  background: var(--panel);
}
.mail-reply-input {
  flex: 1; background: var(--panel-2);
  border: 1px solid var(--stroke-strong); border-radius: 10px;
  padding: 11px 16px; font: inherit; font-size: 13.5px;
  color: var(--txt); outline: none; transition: border-color 150ms;
}
.mail-reply-input::placeholder { color: var(--off); }
.mail-reply-input:focus { border-color: var(--blue); }
.mail-reply-btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 10px 16px; border-radius: 10px;
  background: linear-gradient(135deg,#1668D9,var(--blue));
  color: #fff; font: inherit; font-size: 13px; font-weight: 700;
  border: none; cursor: pointer;
  transition: opacity 150ms, transform 150ms;
}
.mail-reply-btn:hover { opacity: .9; }
.mail-reply-btn:active { transform: scale(.97); }
.mail-reply-extra {
  width: 36px; height: 36px; border-radius: 9px;
  border: 1px solid var(--stroke-strong); background: var(--panel-2);
  display: grid; place-items: center; cursor: pointer; color: var(--txt-soft);
  transition: background 150ms, color 150ms;
}
.mail-reply-extra:hover { background: var(--card); color: var(--cyan); }

/* Estado vacio del panel derecho */
.msg-empty-right {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 12px; color: var(--txt-soft);
}
.msg-empty-right svg { opacity: .18; }
.msg-empty-right p { font-size: 15px; font-weight: 600; }
.msg-empty-right span { font-size: 13px; color: var(--off); }

@media (max-width:1100px) {
  .msg-root { grid-template-columns: 1fr; height: auto; }
  .msg-right { display: none; }
  .msg-root.chat-open .msg-left  { display: none; }
  .msg-root.chat-open .msg-right { display: flex; min-height: 500px; }
}
</style>
@endpush

@section('content')

<div class="msg-root rise d1" id="msgRoot">

  {{-- ===== PANEL IZQUIERDO ===== --}}
  <div class="msg-left">

    {{-- Tabs --}}
    <div class="msg-tabs">
      <button class="msg-tab active-wa" id="tabWa" onclick="switchTab('wa')">
        <svg class="tab-ico-wa" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.558 4.122 1.528 5.857L0 24l6.336-1.506A11.946 11.946 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.891 0-3.659-.5-5.192-1.371l-.37-.22-3.763.895.952-3.665-.242-.376A9.942 9.942 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
        WhatsApp
      </button>
      <button class="msg-tab" id="tabMail" onclick="switchTab('mail')">
        <svg class="tab-ico-mail" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        Correo Electronico
      </button>
    </div>

    {{-- Buscador --}}
    <div class="msg-search-bar">
      <div class="msg-search-wrap">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="msg-search" type="text" placeholder="Buscar Conversaciones" id="msgSearch">
      </div>
      <button class="msg-filter-ico">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      </button>
    </div>

    {{-- Sub-tabs --}}
    <div class="msg-subtabs">
      <button class="msg-subtab active" onclick="filterConvs('all',this)">Todas</button>
      <button class="msg-subtab" onclick="filterConvs('unread',this)">No leidos</button>
      <button class="msg-subtab" onclick="filterConvs('archived',this)">Archivados</button>
    </div>

    {{-- Lista conversaciones --}}
    <div class="msg-list" id="msgList">

      <div class="msg-conv active" data-id="1" data-unread="0" onclick="openConv(1)">
        <div class="msg-av av-green">MG</div>
        <div class="msg-conv-body">
          <div class="msg-conv-top">
            <span class="msg-conv-name">Maria Gonzalez</span>
            <span class="msg-conv-time">10:30</span>
          </div>
          <div class="msg-conv-preview">Hola Dr. Victor, tengo una duda</div>
        </div>
      </div>

      <div class="msg-conv" data-id="2" data-unread="2" onclick="openConv(2)">
        <div class="msg-av av-purple">JP</div>
        <div class="msg-conv-body">
          <div class="msg-conv-top">
            <span class="msg-conv-name">Juan Perez</span>
            <span class="msg-conv-time">9:25</span>
          </div>
          <div class="msg-conv-preview">Hola Dr. Victor, tengo una duda</div>
        </div>
        <div class="msg-badge green">2</div>
      </div>

      <div class="msg-conv" data-id="3" data-unread="1" onclick="openConv(3)">
        <div class="msg-av av-orange">LC</div>
        <div class="msg-conv-body">
          <div class="msg-conv-top">
            <span class="msg-conv-name">Luis Cortez</span>
            <span class="msg-conv-time">Ayer</span>
          </div>
          <div class="msg-conv-preview">Hola Dr. Victor, tengo una duda</div>
        </div>
        <div class="msg-badge">1</div>
      </div>

      <div class="msg-conv" data-id="4" data-unread="1" onclick="openConv(4)">
        <div class="msg-av av-red">AS</div>
        <div class="msg-conv-body">
          <div class="msg-conv-top">
            <span class="msg-conv-name">Ana Sanchez</span>
            <span class="msg-conv-time">Ayer</span>
          </div>
          <div class="msg-conv-preview">Hola Dr. Victor, tengo una duda</div>
        </div>
        <div class="msg-badge">1</div>
      </div>

      <div class="msg-conv" data-id="5" data-unread="0" onclick="openConv(5)">
        <div class="msg-av">CR</div>
        <div class="msg-conv-body">
          <div class="msg-conv-top">
            <span class="msg-conv-name">Carlos Ramirez</span>
            <span class="msg-conv-time">31/05/26</span>
          </div>
          <div class="msg-conv-preview">Hola Dr. Victor, tengo una duda</div>
        </div>
      </div>

      <div class="msg-conv" data-id="6" data-unread="0" onclick="openConv(6)">
        <div class="msg-av">CR</div>
        <div class="msg-conv-body">
          <div class="msg-conv-top">
            <span class="msg-conv-name">Carla Rodriguez</span>
            <span class="msg-conv-time">28/05/26</span>
          </div>
          <div class="msg-conv-preview">Hola Dr. Victor, tengo una duda</div>
        </div>
      </div>

    </div>

    {{-- Footer conexion --}}
    <div class="msg-conn" id="msgConn">
      <span class="msg-conn-dot" id="connDot"></span>
      <div>
        <strong id="connTitle">WhatsApp Conectado</strong>
        <div id="connSub">+52 722 485 2145</div>
      </div>
      <button class="msg-conn-change">Cambiar cuenta</button>
    </div>

  </div>

  {{-- ===== PANEL DERECHO ===== --}}
  <div class="msg-right" id="msgRight">

    {{-- Vista WhatsApp --}}
    <div id="viewWa" style="display:flex;flex-direction:column;flex:1;min-height:0">

      <div class="chat-head">
        <div class="chat-head-av" id="chatAv">MG</div>
        <div class="chat-head-info">
          <div class="chat-head-name" id="chatName">Maria Gonzalez</div>
          <div class="chat-head-sub">Paciente</div>
        </div>
        <div class="chat-head-actions">
          <button class="chat-head-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </button>
          <button class="chat-head-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.6 3.35 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.07 6.07l1.06-1.06a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </button>
          <button class="chat-head-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
          </button>
        </div>
      </div>

      <div class="chat-messages" id="chatMessages">
        <div class="chat-date-label"><span>Hoy</span></div>

        <div class="chat-bubble-wrap">
          <div class="chat-head-av" style="width:30px;height:30px;font-size:11px;flex:none">MG</div>
          <div class="chat-bubble theirs">
            Hola Dr. Victor, tengo una duda, sobre los resultados de mi estudio.
            <div class="chat-bubble-time">10:05</div>
          </div>
        </div>

        <div class="chat-bubble-wrap mine">
          <div class="chat-bubble mine">
            Hola Maria, claro, con gusto te ayudo. &iquest;Podrias especificar a que parte te refieres?
            <div class="chat-bubble-time">10:20</div>
          </div>
        </div>

        <div class="chat-bubble-wrap">
          <div class="chat-head-av" style="width:30px;height:30px;font-size:11px;flex:none">MG</div>
          <div class="chat-bubble theirs">
            Es sobre el valor del colesterol. No sientiendo si esta alto o bajo.
            <div class="chat-bubble-time">10:30</div>
          </div>
        </div>

        <div class="chat-bubble-wrap mine">
          <div class="chat-bubble mine">
            Segun tu resultado, el rango esta dentro de lo normal
            <div class="chat-bubble-time">10:20</div>
          </div>
        </div>
      </div>

      <div class="chat-input-bar">
        <button class="chat-input-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
        </button>
        <input class="chat-input" type="text" placeholder="Escribe un mensaje..." id="chatInput">
        <button class="chat-send-btn" onclick="sendMessage()">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
      </div>
    </div>

    {{-- Vista Correo --}}
    <div id="viewMail" style="display:none;flex-direction:column;flex:1;min-height:0">

      <div class="mail-head-bar">
        <button class="mail-head-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        </button>
        <button class="mail-head-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/></svg>
          Responder
        </button>
        <button class="mail-head-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 14 20 9 15 4"/><path d="M4 20v-7a4 4 0 0 1 4-4h12"/></svg>
          Responder a todos
        </button>
        <button class="mail-head-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
          Reenviar
        </button>
        <button class="mail-head-btn">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          Archivados
        </button>
        <button class="mail-head-btn danger">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
          Eliminar
        </button>
        <button class="mail-head-btn" style="margin-left:auto">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
        </button>
      </div>

      <div class="mail-body">

        <div class="mail-from">
          <div class="mail-from-av">MG</div>
          <div class="mail-from-info">
            <div class="mail-from-name">Maria Gonzalez</div>
            <div class="mail-from-meta">Para Dr. Victor &nbsp;&lt;mariagonz@gmail.com&gt;</div>
          </div>
          <div class="mail-from-time">
            10:30
            <button class="mail-star" id="mailStar" onclick="this.classList.toggle('active')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </button>
          </div>
        </div>

        <div class="mail-subject">Resultados de estudios</div>

        <div class="mail-content">
          Hola Dr. Victor,<br><br>
          le envio los resultados de mis estudios de laboratorio que me indico en la ultima cita.<br>
          Quedo atenta a sus comentarios.<br><br>
          Saludos,<br>
          <strong>Maria Gonzalez</strong>
        </div>

        <div>
          <div class="mail-attachments-title">2 archivos adjuntos</div>
          <div class="mail-attachments">
            <div class="mail-attach-item">
              <svg class="mail-attach-ico" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <div class="mail-attach-info">
                <div class="name">Resultados_Lab.pdf</div>
                <div class="size">1.2 MB</div>
              </div>
              <button class="mail-attach-dl">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              </button>
            </div>
            <div class="mail-attach-item">
              <svg class="mail-attach-ico" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              <div class="mail-attach-info">
                <div class="name">Perfil_Lipidico.png</div>
                <div class="size">840 KB</div>
              </div>
              <button class="mail-attach-dl">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              </button>
            </div>
          </div>
        </div>

      </div>

      <div class="mail-reply-bar">
        <button class="mail-reply-extra">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
        </button>
        <button class="mail-reply-extra">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
        </button>
        <input class="mail-reply-input" type="text" placeholder="Escribe un mensaje...">
        <button class="mail-reply-btn">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          Enviar
        </button>
        <button class="mail-reply-extra">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
      </div>

    </div>

  </div>

</div>

@endsection

@push('scripts')
<script>
(function(){

  var currentTab = 'wa';

  function switchTab(tab) {
    currentTab = tab;
    var tabWa   = document.getElementById('tabWa');
    var tabMail = document.getElementById('tabMail');
    var viewWa  = document.getElementById('viewWa');
    var viewMail= document.getElementById('viewMail');
    var connDot = document.getElementById('connDot');
    var connTitle= document.getElementById('connTitle');
    var connSub = document.getElementById('connSub');

    if (tab === 'wa') {
      tabWa.className   = 'msg-tab active-wa';
      tabMail.className = 'msg-tab';
      viewWa.style.display   = 'flex';
      viewMail.style.display = 'none';
      connDot.className  = 'msg-conn-dot';
      connTitle.textContent = 'WhatsApp Conectado';
      connSub.textContent   = '+52 722 485 2145';
    } else {
      tabWa.className   = 'msg-tab';
      tabMail.className = 'msg-tab active-mail';
      viewWa.style.display   = 'none';
      viewMail.style.display = 'flex';
      connDot.className  = 'msg-conn-dot';
      connTitle.textContent = 'Correo conectado';
      connSub.textContent   = 'medvictor@gmail.com';
    }
  }
  window.switchTab = switchTab;

  function openConv(id) {
    document.querySelectorAll('.msg-conv').forEach(function(el){
      el.classList.remove('active');
    });
    var el = document.querySelector('[data-id="'+id+'"]');
    if (el) el.classList.add('active');
    document.getElementById('msgRoot').classList.add('chat-open');
  }
  window.openConv = openConv;

  function filterConvs(type, btn) {
    document.querySelectorAll('.msg-subtab').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    var convs = document.querySelectorAll('.msg-conv');
    convs.forEach(function(c){
      if (type === 'all')      { c.style.display = ''; }
      else if (type === 'unread')   { c.style.display = parseInt(c.dataset.unread) > 0 ? '' : 'none'; }
      else if (type === 'archived') { c.style.display = 'none'; }
    });
  }
  window.filterConvs = filterConvs;

  /* Busqueda en tiempo real */
  document.getElementById('msgSearch').addEventListener('input', function(){
    var q = this.value.trim().toLowerCase();
    document.querySelectorAll('.msg-conv').forEach(function(c){
      var name = c.querySelector('.msg-conv-name').textContent.toLowerCase();
      c.style.display = (!q || name.includes(q)) ? '' : 'none';
    });
  });

  /* Enviar mensaje WA */
  function sendMessage() {
    var input = document.getElementById('chatInput');
    var text  = input.value.trim();
    if (!text) return;
    var wrap = document.createElement('div');
    wrap.className = 'chat-bubble-wrap mine';
    wrap.innerHTML = '<div class="chat-bubble mine">' + text + '<div class="chat-bubble-time">' + new Date().toLocaleTimeString('es-MX',{hour:'2-digit',minute:'2-digit'}) + '</div></div>';
    var msgs = document.getElementById('chatMessages');
    msgs.appendChild(wrap);
    msgs.scrollTop = msgs.scrollHeight;
    input.value = '';
  }
  window.sendMessage = sendMessage;

  document.getElementById('chatInput').addEventListener('keydown', function(e){
    if (e.key === 'Enter') sendMessage();
  });

})();
</script>
@endpush
