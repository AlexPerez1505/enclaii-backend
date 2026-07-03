{{-- ============ PANEL: INTEGRACIONES ============ --}}
@push('styles')
<style>
.int-head h2{font-family:'Sora',sans-serif;font-size:18px;font-weight:700}
.int-head p{font-size:13px;color:var(--txt-soft);margin:3px 0 18px}

/* Acciones rápidas superiores */
.int-actions{display:grid;grid-template-columns:minmax(0,2fr) minmax(250px,1fr);gap:16px;margin-bottom:18px}
@media (max-width:900px){.int-actions{grid-template-columns:1fr}}
.int-act{display:flex;flex-direction:column}
.int-act .ia-top{display:flex;gap:13px;margin-bottom:14px}
.int-act .ia-ico{width:42px;height:42px;flex:none;border-radius:11px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1);border:1px solid rgba(56,199,244,.22)}
.int-act .ia-ico svg{width:21px;height:21px}
.int-act .ia-t{font-size:14px;font-weight:700}
.int-act .ia-d{font-size:11.5px;color:var(--txt-soft);margin-top:3px;line-height:1.45}
.int-act .ia-btn{margin-top:auto;display:block;text-align:center;padding:9px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);font-size:12.5px;font-weight:700;color:var(--cyan);transition:background-color .15s}
@media (hover:hover){.int-act .ia-btn:hover{background:rgba(56,199,244,.1)}}

/* Centro de copias */
.int-backup-center{padding:20px}
.int-backup-head{display:flex;align-items:flex-start;gap:13px}
.int-backup-head .ia-top{margin:0;flex:1}
.int-backup-create{
  display:inline-flex;align-items:center;justify-content:center;gap:7px;flex:none;
  min-height:38px;padding:0 16px;border-radius:var(--r-md);
  color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan));
  font-size:12.5px;font-weight:700;transition:transform .15s,opacity .15s;
}
.int-backup-create:active{transform:scale(.97)}
@media (hover:hover){.int-backup-create:hover{opacity:.88}}
.int-backup-summary{
  display:flex;align-items:center;justify-content:space-between;gap:12px;
  margin:17px 0 12px;padding:11px 13px;border:1px solid var(--stroke);
  border-radius:var(--r-md);background:rgba(46,123,246,.055);
}
.int-backup-summary-main{display:flex;align-items:center;gap:9px;min-width:0}
.int-backup-ok{width:27px;height:27px;border-radius:50%;display:grid;place-items:center;flex:none;color:var(--green);background:rgba(61,220,151,.13)}
.int-backup-summary strong{display:block;font-size:12.5px}
.int-backup-summary span{display:block;font-size:10.5px;color:var(--txt-soft);margin-top:2px}
.int-backup-count{font-size:11px!important;font-weight:700;color:var(--cyan)!important;white-space:nowrap}
.int-backup-list-head{display:flex;align-items:center;justify-content:space-between;margin:0 2px 7px}
.int-backup-list-head strong{font-size:11.5px}
.int-backup-list-head span{font-size:10.5px;color:var(--txt-soft)}
.int-backup-list{display:flex;flex-direction:column;gap:6px}
.int-backup-row{
  display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:center;gap:10px;
  padding:9px 11px;border-radius:10px;background:var(--panel-2);border:1px solid rgba(110,160,255,.09);
}
.int-backup-info{display:flex;align-items:center;gap:9px;min-width:0}
.int-backup-file{width:30px;height:30px;border-radius:8px;display:grid;place-items:center;flex:none;color:var(--cyan);background:rgba(56,199,244,.09)}
.int-backup-file.auto{color:var(--orange);background:rgba(245,158,45,.1)}
.int-backup-file svg{width:15px;height:15px}
.int-backup-name{font-size:11.5px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.int-backup-meta{font-size:9.8px;color:var(--txt-soft);margin-top:2px}
.int-backup-actions{display:flex;align-items:center;gap:5px}
.int-backup-action{
  width:28px;height:28px;border-radius:8px;display:grid;place-items:center;
  color:var(--txt-soft);border:1px solid var(--stroke);transition:color .15s,background .15s;
}
.int-backup-action svg{width:13px;height:13px}
.int-backup-action.restore{color:var(--cyan)}
.int-backup-action.delete{color:var(--red)}
@media (hover:hover){.int-backup-action:hover{background:rgba(46,123,246,.1);color:var(--txt)}}
.int-backup-empty{padding:17px;text-align:center;border:1px dashed var(--stroke);border-radius:10px;color:var(--txt-soft);font-size:11.5px}
@media (max-width:620px){
  .int-backup-head{flex-wrap:wrap}
  .int-backup-create{width:100%}
  .int-backup-summary{align-items:flex-start}
  .int-backup-row{grid-template-columns:1fr}
  .int-backup-actions{justify-content:flex-end}
}

/* Modal crear copia */
.int-bk-overlay{
  position:fixed;inset:0;z-index:950;display:flex;align-items:center;justify-content:center;
  padding:20px;background:rgba(0,0,0,.66);backdrop-filter:blur(5px);
  opacity:0;visibility:hidden;transition:opacity .18s,visibility .18s;
}
.int-bk-overlay.open{opacity:1;visibility:visible}
.int-bk-modal{
  width:min(520px,100%);max-height:calc(100vh - 40px);overflow:auto;
  background:var(--card);border:1px solid var(--stroke-strong);border-radius:18px;
  box-shadow:0 26px 70px rgba(0,0,0,.55);transform:translateY(10px) scale(.98);
  transition:transform .18s var(--ease-out);
}
.int-bk-overlay.open .int-bk-modal{transform:none}
.int-bk-hdr{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding:20px 20px 0}
.int-bk-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.int-bk-sub{font-size:11.5px;color:var(--txt-soft);margin-top:4px}
.int-bk-close{width:31px;height:31px;display:grid;place-items:center;border-radius:8px;border:1px solid var(--stroke);color:var(--txt-soft)}
.int-bk-close svg{width:14px;height:14px}
.int-bk-body{padding:18px 20px}
.int-bk-label{display:block;font-size:11.5px;font-weight:700;margin-bottom:7px}
.int-bk-input{
  width:100%;height:40px;padding:0 12px;border-radius:10px;border:1px solid var(--stroke-strong);
  color:var(--txt);background:var(--panel-2);font:inherit;font-size:12.5px;outline:none;
}
.int-bk-input:focus{border-color:var(--cyan)}
.int-bk-modes{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:15px}
.int-bk-mode{
  display:flex;align-items:flex-start;gap:9px;padding:11px;border-radius:11px;
  border:1px solid var(--stroke);background:var(--panel-2);cursor:pointer;
}
.int-bk-mode:has(input:checked){border-color:var(--cyan);background:rgba(56,199,244,.08)}
.int-bk-mode input{accent-color:var(--cyan);margin-top:2px}
.int-bk-mode strong{display:block;font-size:11.5px}
.int-bk-mode span{display:block;font-size:9.8px;color:var(--txt-soft);margin-top:2px}
.int-bk-scopes{display:none;margin-top:13px;padding:12px;border:1px solid var(--stroke);border-radius:11px}
.int-bk-scopes.show{display:block}
.int-bk-scope{display:flex;align-items:flex-start;gap:9px;padding:7px 2px;cursor:pointer}
.int-bk-scope input{accent-color:var(--cyan);margin-top:2px}
.int-bk-scope strong{display:block;font-size:11.5px}
.int-bk-scope span{display:block;font-size:9.8px;color:var(--txt-soft);margin-top:2px}
.int-bk-safe{display:flex;gap:8px;margin-top:14px;padding:10px 11px;border-radius:10px;color:var(--txt-soft);background:rgba(61,220,151,.07);font-size:10.5px;line-height:1.45}
.int-bk-safe svg{width:14px;height:14px;flex:none;color:var(--green);margin-top:1px}
.int-bk-footer{display:flex;justify-content:flex-end;gap:8px;padding:13px 20px 17px;border-top:1px solid var(--stroke)}
.int-bk-btn{height:38px;padding:0 16px;border-radius:10px;font:inherit;font-size:12.5px;font-weight:700}
.int-bk-btn.cancel{border:1px solid var(--stroke);color:var(--txt-soft)}
.int-bk-btn.submit{color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan))}
.int-bk-btn:disabled{opacity:.55;cursor:wait}
@media (max-width:520px){.int-bk-modes{grid-template-columns:1fr}}

/* Firma digital */
.int-sign-status{display:flex;align-items:center;gap:6px;margin-top:5px;font-size:10px;color:var(--txt-soft)}
.int-sign-status::before{content:"";width:7px;height:7px;border-radius:50%;background:var(--txt-soft)}
.int-sign-status.ready{color:var(--green)}
.int-sign-status.ready::before{background:var(--green)}
.int-dev-btn[disabled]{opacity:.45;cursor:not-allowed}
.int-sig-overlay{
  position:fixed;inset:0;z-index:960;display:flex;align-items:center;justify-content:center;
  padding:20px;background:rgba(0,0,0,.68);backdrop-filter:blur(5px);
  opacity:0;visibility:hidden;transition:opacity .18s,visibility .18s;
}
.int-sig-overlay.open{opacity:1;visibility:visible}
.int-sig-modal{
  width:min(680px,100%);max-height:calc(100vh - 40px);overflow:auto;
  border:1px solid var(--stroke-strong);border-radius:18px;background:var(--card);
  box-shadow:0 28px 75px rgba(0,0,0,.55);transform:translateY(10px) scale(.98);
  transition:transform .18s var(--ease-out);
}
.int-sig-overlay.open .int-sig-modal{transform:none}
.int-sig-head{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;padding:20px 20px 0}
.int-sig-title{font-family:'Sora',sans-serif;font-size:16px;font-weight:700}
.int-sig-sub{font-size:11.5px;color:var(--txt-soft);margin-top:4px}
.int-sig-close{width:31px;height:31px;display:grid;place-items:center;flex:none;border:1px solid var(--stroke);border-radius:8px;color:var(--txt-soft)}
.int-sig-close svg{width:14px;height:14px}
.int-sig-body{padding:18px 20px}
.int-sig-current{padding:13px;border-radius:12px;border:1px solid var(--stroke);background:var(--panel-2)}
.int-sig-current-label{font-size:10.5px;font-weight:700;color:var(--txt-soft);margin-bottom:8px}
.int-sig-preview{
  min-height:150px;display:flex;align-items:center;justify-content:center;padding:14px;
  border-radius:10px;background:#fff;overflow:hidden;
}
.int-sig-preview img{display:block;max-width:100%;max-height:145px;object-fit:contain}
.int-sig-empty{display:flex;flex-direction:column;align-items:center;gap:7px;color:#789;font-size:11.5px;text-align:center}
.int-sig-empty svg{width:34px;height:34px}
.int-sig-editor{display:none;margin-top:15px}
.int-sig-editor.open{display:block}
.int-sig-tabs{display:flex;gap:7px;margin-bottom:10px}
.int-sig-tab{
  flex:1;height:38px;border:1px solid var(--stroke);border-radius:9px;
  color:var(--txt-soft);font:inherit;font-size:11.5px;font-weight:700;
}
.int-sig-tab.active{color:var(--cyan);border-color:rgba(56,199,244,.45);background:rgba(56,199,244,.08)}
.int-sig-panel{display:none}
.int-sig-panel.active{display:block}
.int-sig-canvas-wrap{
  position:relative;border:1px dashed var(--stroke-strong);border-radius:11px;
  background:#fff;overflow:hidden;touch-action:none;
}
#intSignatureCanvas{display:block;width:100%;height:220px;cursor:crosshair;touch-action:none}
.int-sig-canvas-hint{position:absolute;left:0;right:0;bottom:10px;text-align:center;color:#94a3b8;font-size:10.5px;pointer-events:none}
.int-sig-tools{display:flex;justify-content:flex-end;margin-top:8px}
.int-sig-clear{padding:7px 11px;border:1px solid var(--stroke);border-radius:8px;color:var(--txt-soft);font-size:10.5px;font-weight:700}
.int-sig-upload{
  min-height:220px;display:flex;flex-direction:column;align-items:center;justify-content:center;
  padding:22px;border:1px dashed var(--stroke-strong);border-radius:11px;text-align:center;cursor:pointer;
}
.int-sig-upload svg{width:32px;height:32px;color:var(--cyan);margin-bottom:9px}
.int-sig-upload strong{font-size:12px}
.int-sig-upload span{font-size:10.5px;color:var(--txt-soft);margin-top:4px}
.int-sig-upload-preview{display:none;max-width:100%;max-height:150px;margin-top:12px;object-fit:contain}
.int-sig-note{margin-top:11px;font-size:10.5px;color:var(--txt-soft);line-height:1.45}
.int-sig-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:13px 20px 17px;border-top:1px solid var(--stroke)}
.int-sig-footer-main{display:flex;gap:8px;margin-left:auto}
.int-sig-btn{height:38px;padding:0 15px;border-radius:10px;font:inherit;font-size:11.5px;font-weight:700}
.int-sig-btn.cancel{border:1px solid var(--stroke);color:var(--txt-soft)}
.int-sig-btn.primary{color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan))}
.int-sig-btn.delete{color:var(--red);border:1px solid rgba(255,90,110,.35);background:rgba(255,90,110,.07)}
.int-sig-btn:disabled{opacity:.5;cursor:wait}
@media(max-width:560px){
  .int-sig-footer{align-items:stretch;flex-direction:column}
  .int-sig-footer-main{width:100%;margin-left:0}
  .int-sig-footer-main .int-sig-btn{flex:1}
  .int-sig-btn.delete{width:100%}
}

/* Prueba de impresión */
.int-print-options{padding:18px 20px;display:flex;flex-direction:column;gap:15px}
.int-print-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.int-print-field label,.int-print-section-title{display:block;margin-bottom:7px;font-size:11.5px;font-weight:700}
.int-print-select{
  width:100%;height:40px;padding:0 12px;border:1px solid var(--stroke-strong);
  border-radius:10px;color:var(--txt);background:var(--panel-2);font:inherit;font-size:12px;outline:none
}
.int-print-select:focus{border-color:var(--cyan)}
.int-print-orientation{display:grid;grid-template-columns:1fr 1fr;gap:7px}
.int-print-radio{
  display:flex;align-items:center;gap:7px;height:40px;padding:0 10px;
  border:1px solid var(--stroke);border-radius:10px;background:var(--panel-2);
  color:var(--txt-soft);font-size:11px;font-weight:700;cursor:pointer
}
.int-print-radio:has(input:checked){color:var(--cyan);border-color:rgba(56,199,244,.45);background:rgba(56,199,244,.08)}
.int-print-radio input{accent-color:var(--cyan)}
.int-print-checks{display:grid;grid-template-columns:1fr 1fr;gap:7px}
.int-print-check{
  display:flex;align-items:flex-start;gap:9px;padding:9px 10px;border:1px solid var(--stroke);
  border-radius:10px;background:var(--panel-2);cursor:pointer
}
.int-print-check input{accent-color:var(--cyan);margin-top:2px}
.int-print-check strong{display:block;font-size:11px}
.int-print-check span{display:block;margin-top:2px;color:var(--txt-soft);font-size:9.5px}
.int-print-preview-note{
  display:flex;gap:8px;padding:10px 11px;border-radius:10px;
  color:var(--txt-soft);background:rgba(46,123,246,.07);font-size:10.5px;line-height:1.45
}
.int-print-preview-note svg{width:14px;height:14px;flex:none;color:var(--cyan);margin-top:1px}
.int-print-footer{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:13px 20px 17px;border-top:1px solid var(--stroke)}
.int-print-main-actions{display:flex;gap:8px}
.int-print-btn{height:38px;padding:0 14px;border-radius:10px;font:inherit;font-size:11.5px;font-weight:700}
.int-print-btn.cancel,.int-print-btn.preview{border:1px solid var(--stroke);color:var(--txt-soft)}
.int-print-btn.pdf{color:var(--cyan);border:1px solid rgba(56,199,244,.35);background:rgba(56,199,244,.07)}
.int-print-btn.print{color:#fff;background:linear-gradient(135deg,var(--blue),var(--cyan))}
@media(max-width:580px){
  .int-print-grid,.int-print-checks{grid-template-columns:1fr}
  .int-print-footer{align-items:stretch;flex-direction:column}
  .int-print-main-actions{display:grid;grid-template-columns:1fr 1fr;width:100%}
  .int-print-btn.cancel{width:100%}
  .int-print-btn.print{grid-column:1/-1}
}

/* Información del sistema */
.int-main{display:grid;grid-template-columns:1fr;gap:18px;align-items:stretch;margin-bottom:18px}
.int-info-card{display:flex;flex-direction:column}
.int-info-card .pl-wide-btn{margin-top:auto}
.int-info-row{padding:15px 0;border-bottom:1px solid rgba(110,160,255,.08)}
.int-info-row:last-of-type{border-bottom:0}
.int-info-row .k{font-size:12.5px;color:var(--txt-soft)}
.int-info-row .v{font-size:13px;font-weight:600;float:right}
.int-info-row .on{color:var(--green);display:inline-flex;align-items:center;gap:6px}
.int-info-row .on::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--green)}

/* Tarjetas inferiores */
.int-bottom{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
@media (max-width:1100px){.int-bottom{grid-template-columns:repeat(2,1fr)}}
@media (max-width:560px){.int-bottom{grid-template-columns:1fr}}
.int-dev-head{display:flex;align-items:flex-start;gap:11px;margin-bottom:12px}
.int-dev-ico{width:40px;height:40px;flex:none;border-radius:10px;display:grid;place-items:center;color:var(--cyan);background:rgba(56,199,244,.1);border:1px solid rgba(56,199,244,.2)}
.int-dev-ico svg{width:20px;height:20px}
.int-dev-t{font-size:13.5px;font-weight:700}
.int-chip-on{display:inline-block;margin-top:5px;font-size:9.5px;font-weight:700;color:var(--green);background:rgba(61,220,151,.14);padding:2px 8px;border-radius:6px}
.int-dev-meta{font-size:11.5px;color:var(--txt-soft);margin-top:4px}
.int-dev-meta b{color:var(--txt);font-weight:600}

.int-checks{display:flex;flex-direction:column;gap:10px;margin-top:4px}
.int-check{display:flex;align-items:center;gap:9px;font-size:11.5px;color:var(--txt-soft);cursor:pointer}
.int-check input{appearance:none;-webkit-appearance:none;width:16px;height:16px;flex:none;border-radius:4px;border:1.5px solid var(--stroke-strong);background:var(--panel-2);position:relative;cursor:pointer;transition:background .15s,border-color .15s}
.int-check input:checked{background:linear-gradient(135deg,var(--blue),var(--cyan));border-color:transparent}
.int-check input:checked::after{content:"";position:absolute;left:5px;top:1.5px;width:4px;height:8px;border:solid #fff;border-width:0 2px 2px 0;transform:rotate(45deg)}

.int-dev-btns{display:flex;gap:8px;margin-top:13px}
.int-dev-btn{flex:1;text-align:center;padding:8px;border-radius:9px;border:1px solid var(--stroke-strong);font-size:11.5px;font-weight:700;color:var(--cyan);transition:background-color .15s}
@media (hover:hover){.int-dev-btn:hover{background:rgba(56,199,244,.1)}}
</style>
@endpush

<div class="cfg-panel" data-panel="integraciones">

  <div class="int-head">
    <h2>Integraciones</h2>
    <p>Administra dispositivos, servicios y configuración del sistema.</p>
  </div>

  {{-- Acciones superiores --}}
  <div class="int-actions">
    @php
      $configurationBackups = $configurationBackups ?? collect();
      $latestConfigurationBackup = $configurationBackups->first();
    @endphp

    <article class="card int-act int-backup-center rise d2">
      <div class="int-backup-head">
        <div class="ia-top">
          <span class="ia-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></span>
          <div>
            <div class="ia-t">Copias de configuración</div>
            <div class="ia-d">Protege tus preferencias y datos profesionales sin incluir información clínica ni credenciales.</div>
          </div>
        </div>
        <button type="button" class="int-backup-create" id="intBackupOpen">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Crear copia
        </button>
      </div>

      <div class="int-backup-summary">
        <div class="int-backup-summary-main">
          <span class="int-backup-ok">
            @if($latestConfigurationBackup)
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            @else
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            @endif
          </span>
          <div>
            <strong>{{ $latestConfigurationBackup ? 'Última copia completada' : 'Todavía no hay copias' }}</strong>
            <span>
              {{ $latestConfigurationBackup
                ? $latestConfigurationBackup->created_at->format('d/m/Y · H:i')
                : 'Crea la primera para proteger tu configuración actual.' }}
            </span>
          </div>
        </div>
        <span class="int-backup-count">{{ $configurationBackups->count() }} {{ $configurationBackups->count() === 1 ? 'copia' : 'copias' }}</span>
      </div>

      <div class="int-backup-list-head">
        <strong>Historial reciente</strong>
        <span>Hasta 10 registros visibles</span>
      </div>

      <div class="int-backup-list">
        @forelse($configurationBackups as $backup)
          @php
            $backupSize = $backup->size < 1024
              ? $backup->size.' B'
              : number_format($backup->size / 1024, 1).' KB';
          @endphp
          <div class="int-backup-row" data-backup-row="{{ $backup->id }}">
            <div class="int-backup-info">
              <span class="int-backup-file {{ $backup->type === 'automatic' ? 'auto' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><polyline points="9 15 11 17 15 13"/></svg>
              </span>
              <div style="min-width:0">
                <div class="int-backup-name">{{ $backup->name }}</div>
                <div class="int-backup-meta">
                  {{ $backup->created_at->format('d/m/Y · H:i') }} · {{ $backupSize }}
                  · {{ $backup->type === 'automatic' ? 'Automática' : 'Manual' }}
                </div>
              </div>
            </div>
            <div class="int-backup-actions">
              <button type="button" class="int-backup-action restore" data-backup-restore="{{ $backup->id }}" data-backup-name="{{ $backup->name }}" title="Restaurar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.5 15a9 9 0 1 0 .5-5"/></svg>
              </button>
              <a class="int-backup-action" href="{{ route('configuracion.backups.download', $backup->id) }}" title="Descargar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              </a>
              <button type="button" class="int-backup-action delete" data-backup-delete="{{ $backup->id }}" data-backup-name="{{ $backup->name }}" title="Eliminar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              </button>
            </div>
          </div>
        @empty
          <div class="int-backup-empty">
            Cuando crees una copia aparecerá aquí con opciones para restaurarla, descargarla o eliminarla.
          </div>
        @endforelse
      </div>
    </article>

    <article class="card int-act rise d4">
      <div class="ia-top">
        <span class="ia-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg></span>
        <div><div class="ia-t">Prueba de impresión</div><div class="ia-d">Imprime una página de prueba para verificar la configuración.</div></div>
      </div>
      <button type="button" class="ia-btn" id="intPrintTestOpen">Imprimir prueba</button>
    </article>
  </div>

  {{-- Información del sistema --}}
  <div class="int-main">
    <article class="card int-info-card rise d4">
      <div class="cfg-card-head"><h2>Información del sistema</h2></div>
      <div class="int-info-row"><span class="k">Versión actual</span><span class="v">2.41</span></div>
      <div class="int-info-row"><span class="k">Última actualización</span><span class="v">15/05/2025 10:30AM</span></div>
      <div class="int-info-row"><span class="k">Estado de la conexión</span><span class="v on">En línea</span></div>
      <a href="#" class="pl-wide-btn">Ver historial de cambios</a>
    </article>
  </div>

  {{-- Tarjetas de dispositivos / servicios --}}
  <div class="int-bottom">
    <article class="card rise d3">
      <div class="int-dev-head">
        <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/></svg></span>
        <div><div class="int-dev-t">Procesador de endoscopia</div><span class="int-chip-on">Conectado</span></div>
      </div>
      <div class="int-dev-meta"><b>Olympus EVIS X1</b></div>
      <div class="int-dev-meta">Número de serie: X1-24567</div>
    </article>

    <article class="card rise d3">
      <div class="int-dev-head">
        <span class="int-dev-ico" style="color:#a47bff;background:rgba(124,92,255,.12);border-color:rgba(124,92,255,.25)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></span>
        <div class="int-dev-t">Captura Multimedia</div>
      </div>
      <div class="int-checks">
        <label class="int-check"><input type="checkbox" data-setting="capture_auto_capture" checked> Autocapturas de imágenes</label>
        <label class="int-check"><input type="checkbox" data-setting="capture_auto_save" checked> Guardar imágenes automáticamente</label>
        <label class="int-check"><input type="checkbox" checked> Capturar imagen con pedal</label>
      </div>
      <div class="int-dev-meta" style="margin-top:10px">
        Intervalo de autocaptura:
        <select data-setting="capture_auto_interval" style="background:var(--panel-2);border:1px solid var(--stroke-strong);border-radius:8px;color:var(--txt);font:inherit;font-size:12px;padding:4px 8px;margin-left:4px">
          <option value="10">10 segundos</option>
          <option value="30">30 segundos</option>
          <option value="60">1 minuto</option>
          <option value="120">2 minutos</option>
        </select>
      </div>
    </article>

    <article class="card rise d4">
      <div class="int-dev-head">
        <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></span>
        <div><div class="int-dev-t">Correo electrónico <span class="int-chip-on">Conectado</span></div></div>
      </div>
      <div class="int-dev-meta">Usuario: David1212@gmail.com</div>
      <div class="int-dev-meta">Contacto: ENCLAII@gmail.com</div>
      <div class="int-dev-btns"><a href="#" class="int-dev-btn">Configurar correo</a></div>
    </article>

    <article class="card rise d4">
      <div class="int-dev-head">
        <span class="int-dev-ico" style="color:#25d366;background:rgba(37,211,102,.12);border-color:rgba(37,211,102,.25)"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.5A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.9.9-2.7-.2-.3A8 8 0 1 1 12 20zm4.4-5.9c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2a.5.5 0 0 0 0-.5c0-.1-.5-1.3-.7-1.8s-.4-.4-.5-.4h-.5a.9.9 0 0 0-.7.3 2.8 2.8 0 0 0-.9 2.1 5 5 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c1.6.7 1.9.6 2.3.5a2.3 2.3 0 0 0 1.5-1.1 1.9 1.9 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3z"/></svg></span>
        <div><div class="int-dev-t">WhatsApp Business <span class="int-chip-on">Conectado</span></div></div>
      </div>
      <div class="int-checks">
        <label class="int-check"><input type="checkbox" checked> Enviar informe PDF</label>
        <label class="int-check"><input type="checkbox" checked> Responder con AI</label>
        <label class="int-check"><input type="checkbox" checked> Enviar recordatorio de cita</label>
      </div>
      <div class="int-dev-btns"><a href="#" class="int-dev-btn">Configurar</a></div>
    </article>

    <article class="card rise d5">
      <div class="int-dev-head">
        <span class="int-dev-ico" style="color:var(--orange);background:rgba(245,158,45,.12);border-color:rgba(245,158,45,.25)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17c3-1 5-5 5-9 0 4 2 8 5 9-3 0-7 0-10 0z"/><path d="M14 11l6-6a1.5 1.5 0 0 0-2-2l-6 6"/></svg></span>
        <div>
          <div class="int-dev-t">Firma digital</div>
          <div class="int-dev-meta">Firma: {{ auth()->user()->name }}</div>
          <div class="int-sign-status {{ auth()->user()->signature_path ? 'ready' : '' }}">
            {{ auth()->user()->signature_path ? 'Firma configurada' : 'Sin firma registrada' }}
          </div>
        </div>
      </div>
      <div class="int-dev-meta">
        Actualizada:
        {{ auth()->user()->signature_updated_at?->format('d/m/Y H:i') ?? 'Nunca' }}
      </div>
      <div class="int-dev-btns">
        <button type="button" class="int-dev-btn" id="intSignatureView" @disabled(! auth()->user()->signature_path)>Ver firma</button>
        <button type="button" class="int-dev-btn" id="intSignatureEdit">
          {{ auth()->user()->signature_path ? 'Actualizar firma' : 'Crear firma' }}
        </button>
      </div>
    </article>

    <article class="card rise d5">
      <div class="int-dev-head">
        <span class="int-dev-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a4 4 0 0 0-4 4 4 4 0 0 0 0 8 4 4 0 0 0 8 0 4 4 0 0 0 0-8 4 4 0 0 0-4-4z"/><path d="M12 2v20"/></svg></span>
        <div class="int-dev-t">Integración de AI</div>
      </div>
      <div class="int-checks">
        <label class="int-check"><input type="checkbox" checked> Generar borradores automáticos</label>
        <label class="int-check"><input type="checkbox" checked> Analizar fotos</label>
        <label class="int-check"><input type="checkbox" checked> Sugerir diagnósticos</label>
        <label class="int-check"><input type="checkbox" checked> Recomendar procedimientos</label>
      </div>
    </article>
  </div>

  <div class="int-bk-overlay" id="intBackupModal" aria-hidden="true">
    <form
      class="int-bk-modal"
      id="intBackupForm"
      data-store-url="{{ route('configuracion.backups.store') }}"
      data-restore-url="{{ url('/configuracion/copias/__ID__/restaurar') }}"
      data-delete-url="{{ url('/configuracion/copias/__ID__') }}"
    >
      @csrf
      <div class="int-bk-hdr">
        <div>
          <div class="int-bk-title">Crear copia de configuración</div>
          <div class="int-bk-sub">Podrás restaurarla más adelante o descargarla como archivo JSON.</div>
        </div>
        <button type="button" class="int-bk-close" id="intBackupClose" aria-label="Cerrar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="int-bk-body">
        <label class="int-bk-label" for="intBackupName">Nombre de la copia</label>
        <input
          class="int-bk-input"
          id="intBackupName"
          name="name"
          type="text"
          maxlength="100"
          value="Configuración principal - {{ now()->format('d-m-Y H:i') }}"
          required
        >

        <div class="int-bk-modes">
          <label class="int-bk-mode">
            <input type="radio" name="mode" value="complete" checked>
            <span><strong>Copia completa</strong><span>Preferencias y perfil profesional.</span></span>
          </label>
          <label class="int-bk-mode">
            <input type="radio" name="mode" value="custom">
            <span><strong>Personalizada</strong><span>Elige las secciones que se guardarán.</span></span>
          </label>
        </div>

        <div class="int-bk-scopes" id="intBackupScopes">
          <label class="int-bk-scope">
            <input type="checkbox" name="scope[]" value="general" checked>
            <span><strong>Preferencias generales</strong><span>Formato, vista, accesibilidad y notificaciones.</span></span>
          </label>
          <label class="int-bk-scope">
            <input type="checkbox" name="scope[]" value="profile" checked>
            <span><strong>Perfil profesional</strong><span>Teléfono, especialidad, cédula, área médica y puesto.</span></span>
          </label>
        </div>

        <div class="int-bk-safe">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          La copia se cifra en la base de datos. Nunca incluye contraseñas, información de Stripe, pacientes, estudios, archivos ni tokens secretos.
        </div>
      </div>

      <div class="int-bk-footer">
        <button type="button" class="int-bk-btn cancel" id="intBackupCancel">Cancelar</button>
        <button type="submit" class="int-bk-btn submit" id="intBackupSubmit">Crear copia</button>
      </div>
    </form>
  </div>

  <div
    class="int-sig-overlay"
    id="intSignatureModal"
    aria-hidden="true"
    data-show-url="{{ route('configuracion.signature.show') }}"
    data-store-url="{{ route('configuracion.signature.store') }}"
    data-delete-url="{{ route('configuracion.signature.destroy') }}"
    data-has-signature="{{ auth()->user()->signature_path ? '1' : '0' }}"
  >
    <div class="int-sig-modal" role="dialog" aria-modal="true" aria-labelledby="intSignatureTitle">
      <div class="int-sig-head">
        <div>
          <div class="int-sig-title" id="intSignatureTitle">Firma digital</div>
          <div class="int-sig-sub">Dibuja tu firma o sube una imagen para utilizarla en tus documentos.</div>
        </div>
        <button type="button" class="int-sig-close" id="intSignatureClose" aria-label="Cerrar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="int-sig-body">
        <div class="int-sig-current">
          <div class="int-sig-current-label">Vista previa de la firma guardada</div>
          <div class="int-sig-preview">
            @if(auth()->user()->signature_path)
              <img
                id="intSignatureCurrent"
                src="{{ route('configuracion.signature.show', ['v' => auth()->user()->signature_updated_at?->timestamp]) }}"
                alt="Firma digital de {{ auth()->user()->name }}"
              >
            @else
              <div class="int-sig-empty" id="intSignatureEmpty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M3 17c3-1 5-5 5-9 0 4 2 8 5 9-3 0-7 0-10 0z"/><path d="M14 11l6-6a1.5 1.5 0 0 0-2-2l-6 6"/></svg>
                Aún no has registrado una firma.
              </div>
            @endif
          </div>
        </div>

        <div class="int-sig-editor" id="intSignatureEditor">
          <div class="int-sig-tabs">
            <button type="button" class="int-sig-tab active" data-signature-tab="draw">Dibujar firma</button>
            <button type="button" class="int-sig-tab" data-signature-tab="upload">Subir imagen</button>
          </div>

          <div class="int-sig-panel active" data-signature-panel="draw">
            <div class="int-sig-canvas-wrap">
              <canvas id="intSignatureCanvas" width="1200" height="440"></canvas>
              <div class="int-sig-canvas-hint" id="intSignatureHint">Firma dentro de este espacio</div>
            </div>
            <div class="int-sig-tools">
              <button type="button" class="int-sig-clear" id="intSignatureClear">Limpiar dibujo</button>
            </div>
          </div>

          <div class="int-sig-panel" data-signature-panel="upload">
            <label class="int-sig-upload" for="intSignatureFile">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              <strong>Seleccionar imagen de firma</strong>
              <span>PNG, JPG o WEBP · máximo 2 MB</span>
              <img class="int-sig-upload-preview" id="intSignatureUploadPreview" alt="Vista previa de la nueva firma">
            </label>
            <input type="file" id="intSignatureFile" accept="image/png,image/jpeg,image/webp" hidden>
          </div>

          <div class="int-sig-note">
            Para obtener mejores resultados utiliza fondo blanco o transparente. La firma se almacena en un espacio privado.
          </div>
        </div>
      </div>

      <div class="int-sig-footer">
        <button type="button" class="int-sig-btn delete" id="intSignatureDelete" @disabled(! auth()->user()->signature_path)>Eliminar firma</button>
        <div class="int-sig-footer-main">
          <button type="button" class="int-sig-btn cancel" id="intSignatureCancel">Cerrar</button>
          <button type="button" class="int-sig-btn primary" id="intSignatureStartEdit">
            {{ auth()->user()->signature_path ? 'Actualizar firma' : 'Crear firma' }}
          </button>
          <button type="button" class="int-sig-btn primary" id="intSignatureSave" style="display:none">Guardar firma</button>
        </div>
      </div>
    </div>
  </div>

  <div class="int-bk-overlay" id="intPrintTestModal" aria-hidden="true">
    <div
      class="int-bk-modal"
      role="dialog"
      aria-modal="true"
      aria-labelledby="intPrintTestTitle"
      data-preview-url="{{ route('configuracion.print-test') }}"
    >
      <div class="int-bk-hdr">
        <div>
          <div class="int-bk-title" id="intPrintTestTitle">Prueba de impresión</div>
          <div class="int-bk-sub">Configura una hoja de calibración sin información clínica real.</div>
        </div>
        <button type="button" class="int-bk-close" id="intPrintTestClose" aria-label="Cerrar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="int-print-options">
        <div class="int-print-grid">
          <div class="int-print-field">
            <label for="intPrintPageSize">Tamaño de papel</label>
            <select class="int-print-select" id="intPrintPageSize">
              <option value="letter">Carta</option>
              <option value="a4">A4</option>
            </select>
          </div>

          <div class="int-print-field">
            <span class="int-print-section-title">Orientación</span>
            <div class="int-print-orientation">
              <label class="int-print-radio"><input type="radio" name="intPrintOrientation" value="portrait" checked> Vertical</label>
              <label class="int-print-radio"><input type="radio" name="intPrintOrientation" value="landscape"> Horizontal</label>
            </div>
          </div>
        </div>

        <div>
          <span class="int-print-section-title">Elementos incluidos</span>
          <div class="int-print-checks">
            <label class="int-print-check">
              <input type="checkbox" id="intPrintHeader" checked>
              <span><strong>Encabezado</strong><span>Nombre y datos de la clínica.</span></span>
            </label>
            <label class="int-print-check">
              <input type="checkbox" id="intPrintLogo" checked>
              <span><strong>Logo</strong><span>Comprueba tamaño y nitidez.</span></span>
            </label>
            <label class="int-print-check">
              <input type="checkbox" id="intPrintSignature" checked>
              <span><strong>Firma digital</strong><span>Usa la firma configurada.</span></span>
            </label>
            <label class="int-print-check">
              <input type="checkbox" id="intPrintColor" checked>
              <span><strong>Impresión a color</strong><span>Desactiva para escala de grises.</span></span>
            </label>
          </div>
        </div>

        <div class="int-print-preview-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><line x1="12" y1="11" x2="12" y2="16"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          La impresora se selecciona en el diálogo de Windows. La hoja contiene colores, grises, cuadrícula, textos, firma y un reporte completamente ficticio.
        </div>
      </div>

      <div class="int-print-footer">
        <button type="button" class="int-print-btn cancel" id="intPrintTestCancel">Cancelar</button>
        <div class="int-print-main-actions">
          <button type="button" class="int-print-btn preview" data-print-test-action="preview">Vista previa</button>
          <button type="button" class="int-print-btn pdf" data-print-test-action="pdf">Descargar PDF</button>
          <button type="button" class="int-print-btn print" data-print-test-action="print">Imprimir</button>
        </div>
      </div>
    </div>
  </div>

</div>

@push('scripts')
<script>
(function(){
  const modal = document.getElementById('intBackupModal');
  const form = document.getElementById('intBackupForm');
  const openButton = document.getElementById('intBackupOpen');
  const closeButton = document.getElementById('intBackupClose');
  const cancelButton = document.getElementById('intBackupCancel');
  const submitButton = document.getElementById('intBackupSubmit');
  const scopes = document.getElementById('intBackupScopes');
  const csrf = form?.querySelector('input[name="_token"]')?.value;
  const configUrl = @json(route('configuracion'));

  if (!modal || !form || !csrf) return;

  function toast(message, isError = false) {
    let element = document.getElementById('intBackupToast');
    if (!element) {
      element = document.createElement('div');
      element.id = 'intBackupToast';
      element.style.cssText = 'position:fixed;left:50%;bottom:24px;z-index:9999;'
        + 'transform:translate(-50%,12px);padding:11px 17px;border-radius:11px;'
        + 'background:var(--card);border:1px solid var(--stroke-strong);color:var(--txt);'
        + 'font-size:12.5px;font-weight:700;box-shadow:0 16px 40px rgba(0,0,0,.4);'
        + 'opacity:0;transition:opacity .2s,transform .2s;pointer-events:none';
      document.body.appendChild(element);
    }
    element.textContent = message;
    element.style.borderColor = isError ? 'rgba(255,90,110,.45)' : 'rgba(61,220,151,.4)';
    requestAnimationFrame(() => {
      element.style.opacity = '1';
      element.style.transform = 'translate(-50%,0)';
    });
    clearTimeout(element._timer);
    element._timer = setTimeout(() => {
      element.style.opacity = '0';
      element.style.transform = 'translate(-50%,12px)';
    }, 2600);
  }

  function openModal() {
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('intBackupName')?.select(), 80);
  }

  function closeModal() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  function reloadIntegrations(message) {
    try { sessionStorage.setItem('enclaii-backup-message', message); } catch (error) {}
    window.location.assign(configUrl + '?tab=integraciones');
  }

  async function request(url, method, body = null) {
    const response = await fetch(url, {
      method,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: body ? JSON.stringify(body) : null,
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
      const validation = data.errors ? Object.values(data.errors).flat()[0] : null;
      throw new Error(validation || data.message || 'No se pudo completar la operación.');
    }
    return data;
  }

  openButton?.addEventListener('click', openModal);
  closeButton?.addEventListener('click', closeModal);
  cancelButton?.addEventListener('click', closeModal);
  modal.addEventListener('click', event => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && modal.classList.contains('open')) closeModal();
  });

  form.querySelectorAll('input[name="mode"]').forEach(input => {
    input.addEventListener('change', () => {
      scopes?.classList.toggle('show', input.value === 'custom' && input.checked);
    });
  });

  form.addEventListener('submit', async event => {
    event.preventDefault();
    const mode = form.querySelector('input[name="mode"]:checked')?.value || 'complete';
    const selectedScopes = [...form.querySelectorAll('input[name="scope[]"]:checked')]
      .map(input => input.value);

    if (mode === 'custom' && selectedScopes.length === 0) {
      toast('Selecciona al menos una sección.', true);
      return;
    }

    submitButton.disabled = true;
    submitButton.textContent = 'Creando...';

    try {
      const data = await request(form.dataset.storeUrl, 'POST', {
        name: document.getElementById('intBackupName').value.trim(),
        mode,
        scope: selectedScopes,
      });
      reloadIntegrations(data.message);
    } catch (error) {
      toast(error.message, true);
      submitButton.disabled = false;
      submitButton.textContent = 'Crear copia';
    }
  });

  document.querySelectorAll('[data-backup-restore]').forEach(button => {
    button.addEventListener('click', async () => {
      const name = button.dataset.backupName;
      const accepted = window.confirm(
        `¿Restaurar “${name}”? Antes de aplicar los cambios se creará una copia automática del estado actual.`
      );
      if (!accepted) return;

      button.disabled = true;
      try {
        const url = form.dataset.restoreUrl.replace('__ID__', button.dataset.backupRestore);
        const data = await request(url, 'POST');
        reloadIntegrations(data.message);
      } catch (error) {
        toast(error.message, true);
        button.disabled = false;
      }
    });
  });

  document.querySelectorAll('[data-backup-delete]').forEach(button => {
    button.addEventListener('click', async () => {
      const name = button.dataset.backupName;
      if (!window.confirm(`¿Eliminar definitivamente la copia “${name}”?`)) return;

      button.disabled = true;
      try {
        const url = form.dataset.deleteUrl.replace('__ID__', button.dataset.backupDelete);
        const data = await request(url, 'DELETE');
        reloadIntegrations(data.message);
      } catch (error) {
        toast(error.message, true);
        button.disabled = false;
      }
    });
  });

  try {
    const pendingMessage = sessionStorage.getItem('enclaii-backup-message');
    if (pendingMessage) {
      sessionStorage.removeItem('enclaii-backup-message');
      setTimeout(() => toast(pendingMessage), 120);
    }
  } catch (error) {}

  /* Firma digital */
  const signatureModal = document.getElementById('intSignatureModal');
  const signatureView = document.getElementById('intSignatureView');
  const signatureEdit = document.getElementById('intSignatureEdit');
  const signatureClose = document.getElementById('intSignatureClose');
  const signatureCancel = document.getElementById('intSignatureCancel');
  const signatureStartEdit = document.getElementById('intSignatureStartEdit');
  const signatureSave = document.getElementById('intSignatureSave');
  const signatureDelete = document.getElementById('intSignatureDelete');
  const signatureEditor = document.getElementById('intSignatureEditor');
  const signatureCanvas = document.getElementById('intSignatureCanvas');
  const signatureHint = document.getElementById('intSignatureHint');
  const signatureClear = document.getElementById('intSignatureClear');
  const signatureFile = document.getElementById('intSignatureFile');
  const signatureUploadPreview = document.getElementById('intSignatureUploadPreview');
  let signatureTab = 'draw';
  let signatureHasInk = false;
  let signatureDrawing = false;
  let signatureUploadUrl = null;

  function openSignatureModal(editing = false) {
    if (!signatureModal) return;
    signatureModal.classList.add('open');
    signatureModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setSignatureEditing(editing);
  }

  function closeSignatureModal() {
    if (!signatureModal) return;
    signatureModal.classList.remove('open');
    signatureModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    setSignatureEditing(false);
  }

  function setSignatureEditing(editing) {
    signatureEditor?.classList.toggle('open', editing);
    if (signatureStartEdit) signatureStartEdit.style.display = editing ? 'none' : '';
    if (signatureSave) signatureSave.style.display = editing ? '' : 'none';
    if (signatureCancel) signatureCancel.textContent = editing ? 'Cancelar' : 'Cerrar';
    if (editing) clearSignatureCanvas();
  }

  function signaturePoint(event) {
    const rect = signatureCanvas.getBoundingClientRect();
    return {
      x: (event.clientX - rect.left) * (signatureCanvas.width / rect.width),
      y: (event.clientY - rect.top) * (signatureCanvas.height / rect.height),
    };
  }

  function beginSignature(event) {
    if (!signatureCanvas || signatureTab !== 'draw') return;
    signatureDrawing = true;
    signatureCanvas.setPointerCapture(event.pointerId);
    const point = signaturePoint(event);
    const context = signatureCanvas.getContext('2d');
    context.beginPath();
    context.moveTo(point.x, point.y);
    event.preventDefault();
  }

  function drawSignature(event) {
    if (!signatureDrawing || !signatureCanvas) return;
    const point = signaturePoint(event);
    const context = signatureCanvas.getContext('2d');
    context.lineWidth = 7;
    context.lineCap = 'round';
    context.lineJoin = 'round';
    context.strokeStyle = '#111827';
    context.lineTo(point.x, point.y);
    context.stroke();
    signatureHasInk = true;
    if (signatureHint) signatureHint.style.display = 'none';
    event.preventDefault();
  }

  function endSignature(event) {
    if (!signatureDrawing) return;
    signatureDrawing = false;
    if (signatureCanvas?.hasPointerCapture(event.pointerId)) {
      signatureCanvas.releasePointerCapture(event.pointerId);
    }
  }

  function clearSignatureCanvas() {
    if (!signatureCanvas) return;
    signatureCanvas.getContext('2d').clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
    signatureHasInk = false;
    if (signatureHint) signatureHint.style.display = '';
  }

  signatureCanvas?.addEventListener('pointerdown', beginSignature);
  signatureCanvas?.addEventListener('pointermove', drawSignature);
  signatureCanvas?.addEventListener('pointerup', endSignature);
  signatureCanvas?.addEventListener('pointercancel', endSignature);
  signatureCanvas?.addEventListener('pointerleave', endSignature);
  signatureClear?.addEventListener('click', clearSignatureCanvas);

  document.querySelectorAll('[data-signature-tab]').forEach(tab => {
    tab.addEventListener('click', () => {
      signatureTab = tab.dataset.signatureTab;
      document.querySelectorAll('[data-signature-tab]').forEach(item => item.classList.toggle('active', item === tab));
      document.querySelectorAll('[data-signature-panel]').forEach(panel => {
        panel.classList.toggle('active', panel.dataset.signaturePanel === signatureTab);
      });
    });
  });

  signatureFile?.addEventListener('change', () => {
    const file = signatureFile.files?.[0];
    if (!file) return;
    if (!['image/png', 'image/jpeg', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) {
      signatureFile.value = '';
      toast('Selecciona una imagen PNG, JPG o WEBP de máximo 2 MB.', true);
      return;
    }
    if (signatureUploadUrl) URL.revokeObjectURL(signatureUploadUrl);
    signatureUploadUrl = URL.createObjectURL(file);
    signatureUploadPreview.src = signatureUploadUrl;
    signatureUploadPreview.style.display = 'block';
  });

  signatureView?.addEventListener('click', () => openSignatureModal(false));
  signatureEdit?.addEventListener('click', () => openSignatureModal(true));
  signatureStartEdit?.addEventListener('click', () => setSignatureEditing(true));
  signatureClose?.addEventListener('click', closeSignatureModal);
  signatureCancel?.addEventListener('click', () => {
    if (signatureEditor?.classList.contains('open')) setSignatureEditing(false);
    else closeSignatureModal();
  });
  signatureModal?.addEventListener('click', event => {
    if (event.target === signatureModal) closeSignatureModal();
  });

  signatureSave?.addEventListener('click', async () => {
    let file = null;
    let filename = 'firma.png';

    if (signatureTab === 'draw') {
      if (!signatureHasInk) {
        toast('Dibuja tu firma antes de guardarla.', true);
        return;
      }
      file = await new Promise(resolve => signatureCanvas.toBlob(resolve, 'image/png'));
    } else {
      file = signatureFile?.files?.[0] || null;
      filename = file?.name || filename;
      if (!file) {
        toast('Selecciona una imagen de firma.', true);
        return;
      }
    }

    const data = new FormData();
    data.append('signature', file, filename);
    signatureSave.disabled = true;
    signatureSave.textContent = 'Guardando...';

    try {
      const response = await fetch(signatureModal.dataset.storeUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: data,
      });
      const result = await response.json().catch(() => ({}));
      if (!response.ok) {
        const validation = result.errors ? Object.values(result.errors).flat()[0] : null;
        throw new Error(validation || result.message || 'No se pudo guardar la firma.');
      }
      reloadIntegrations(result.message);
    } catch (error) {
      toast(error.message, true);
      signatureSave.disabled = false;
      signatureSave.textContent = 'Guardar firma';
    }
  });

  signatureDelete?.addEventListener('click', async () => {
    if (!window.confirm('¿Eliminar definitivamente tu firma digital?')) return;
    signatureDelete.disabled = true;

    try {
      const result = await request(signatureModal.dataset.deleteUrl, 'DELETE');
      reloadIntegrations(result.message);
    } catch (error) {
      toast(error.message, true);
      signatureDelete.disabled = false;
    }
  });

  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && signatureModal?.classList.contains('open')) {
      closeSignatureModal();
    }
  });

  /* Prueba de impresión */
  const printTestModal = document.getElementById('intPrintTestModal');
  const printTestDialog = printTestModal?.querySelector('.int-bk-modal');
  const printTestOpen = document.getElementById('intPrintTestOpen');
  const printTestClose = document.getElementById('intPrintTestClose');
  const printTestCancel = document.getElementById('intPrintTestCancel');
  const printHeader = document.getElementById('intPrintHeader');
  const printLogo = document.getElementById('intPrintLogo');

  function openPrintTest() {
    if (!printTestModal) return;
    printTestModal.classList.add('open');
    printTestModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closePrintTest() {
    if (!printTestModal) return;
    printTestModal.classList.remove('open');
    printTestModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  function syncPrintOptions() {
    if (!printLogo || !printHeader) return;
    printLogo.disabled = !printHeader.checked;
    if (!printHeader.checked) printLogo.checked = false;
  }

  function printTestUrl(mode) {
    const params = new URLSearchParams({
      page_size: document.getElementById('intPrintPageSize')?.value || 'letter',
      orientation: document.querySelector('input[name="intPrintOrientation"]:checked')?.value || 'portrait',
      show_header: printHeader?.checked ? '1' : '0',
      show_logo: printLogo?.checked ? '1' : '0',
      show_signature: document.getElementById('intPrintSignature')?.checked ? '1' : '0',
      use_color: document.getElementById('intPrintColor')?.checked ? '1' : '0',
      mode,
    });
    return printTestDialog.dataset.previewUrl + '?' + params.toString();
  }

  printTestOpen?.addEventListener('click', openPrintTest);
  printTestClose?.addEventListener('click', closePrintTest);
  printTestCancel?.addEventListener('click', closePrintTest);
  printHeader?.addEventListener('change', syncPrintOptions);
  printTestModal?.addEventListener('click', event => {
    if (event.target === printTestModal) closePrintTest();
  });
  document.querySelectorAll('[data-print-test-action]').forEach(button => {
    button.addEventListener('click', () => {
      window.open(printTestUrl(button.dataset.printTestAction), '_blank', 'noopener');
    });
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && printTestModal?.classList.contains('open')) closePrintTest();
  });
})();
</script>
@endpush
