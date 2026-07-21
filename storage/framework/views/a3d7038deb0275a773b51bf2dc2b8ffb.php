<?php $__env->startSection('title', 'Estudios del Paciente'); ?>
<?php $__env->startSection('active', 'pacientes'); ?>
<?php $__env->startSection('header-title', 'Estudios del paciente'); ?>
<?php $__env->startSection('header-sub'); ?> Gestiona los estudios, imágenes, videos y reportes <?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Back link styling */
.back-link{display:inline-flex;align-items:center;gap:8px;margin-bottom:20px;padding:0;font-size:14px;font-weight:600;color:var(--blue);text-decoration:none;transition:all 150ms ease;background:transparent;border:none;cursor:pointer;}
.back-link:hover{color:var(--cyan);}
.back-link svg{width:18px;height:18px;flex:none;stroke:var(--blue);}
.back-link:hover svg{stroke:var(--cyan);}

.estudios-tabs{display:flex;gap:8px;margin-bottom:24px;border-bottom:1px solid var(--stroke);padding-bottom:12px;}
.estudios-tab{padding:10px 20px;border-radius:var(--r-md);border:none;background:transparent;color:var(--txt-soft);font-size:14px;font-weight:600;cursor:pointer;transition:all 150ms ease;}
.estudios-tab:hover{background:var(--panel-2);color:var(--txt);}
.estudios-tab.active{background:var(--panel-2);color:var(--green);box-shadow:0 2px 0 var(--green);}
.tab-content{display:none;}
.tab-content.active{display:block;}
.section-card{background:var(--panel);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:24px;margin-bottom:20px;}
.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;}
.section-title{font-family:'Sora',sans-serif;font-size:18px;font-weight:700;color:var(--txt);}
.section-count{display:inline-flex;align-items:center;justify-content:center;min-width:24px;height:24px;border-radius:12px;background:var(--green);color:#fff;font-size:12px;font-weight:700;margin-left:8px;padding:0 8px;}
.ver-todas{font-size:13px;color:var(--blue);font-weight:600;text-decoration:none;}
.ver-todas:hover{color:var(--cyan);}
.estudios-table{width:100%;border-collapse:collapse;}
.estudios-table th{text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:var(--txt-soft);text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid var(--stroke);}
.estudios-table td{padding:16px;font-size:13px;color:var(--txt);border-bottom:1px solid var(--stroke);}
.estudios-table tr:last-child td{border-bottom:none;}
.estudios-table tr:hover{background:rgba(46,123,246,.05);}
.tipo-estudio{color:var(--cyan);font-weight:600;}
.desc-estudio{color:var(--txt-soft);}
.acciones-cell{display:flex;gap:8px;justify-content:flex-end;}
.btn-icon{width:32px;height:32px;border-radius:8px;border:none;background:transparent;color:var(--txt-soft);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 150ms ease;}
.btn-icon:hover{background:var(--panel-2);color:var(--txt);}
.btn-icon.edit:hover{color:var(--blue);}
.btn-icon.delete:hover{color:#ef4444;}
.btn-icon svg{width:16px;height:16px;}
.media-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px;}
.media-item{aspect-ratio:1;border-radius:var(--r-md);overflow:hidden;position:relative;cursor:pointer;background:var(--panel-2);border:1px solid var(--stroke);}
.media-item .placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:24px;}
.media-item .play-overlay{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.4);}
.media-item .delete-btn{position:absolute;top:4px;right:4px;width:24px;height:24px;border-radius:6px;border:none;background:rgba(0,0,0,.5);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 150ms ease;}
.media-item:hover .delete-btn{opacity:1;}
.media-sections{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
.media-section{background:var(--panel);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:20px;}
.media-section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.media-section-title{display:flex;align-items:center;gap:8px;font-size:14px;font-weight:700;color:var(--txt);}
.media-section-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;}
.media-section-icon.images{background:rgba(124,58,237,.15);color:#a78bfa;}
.media-section-icon.videos{background:rgba(245,158,45,.15);color:#fbbf24;}
.media-section-icon.reports{background:rgba(46,123,246,.15);color:#60a5fa;}
.dropzone{border:2px dashed var(--stroke-strong);border-radius:var(--r-md);padding:20px;text-align:center;cursor:pointer;transition:all 150ms ease;background:var(--panel-2);}
.dropzone:hover{border-color:var(--blue);background:rgba(46,123,246,.05);}
.dropzone-text{font-size:14px;font-weight:600;color:var(--blue);margin-bottom:4px;}
.dropzone-hint{font-size:12px;color:var(--txt-soft);}
.reportes-list{display:flex;flex-direction:column;gap:12px;}
.reporte-item{display:flex;align-items:center;gap:12px;padding:12px 16px;background:var(--panel-2);border:1px solid var(--stroke);border-radius:var(--r-md);}
.reporte-icon{width:40px;height:40px;border-radius:10px;background:rgba(239,68,68,.15);display:flex;align-items:center;justify-content:center;color:#ef4444;flex:none;}
.reporte-info{flex:1;min-width:0;}
.reporte-name{font-size:13px;font-weight:600;color:var(--txt);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.reporte-meta{font-size:11px;color:var(--txt-soft);}
.reporte-actions{display:flex;gap:6px;}
.form-footer{display:flex;justify-content:space-between;align-items:center;margin-top:28px;padding-top:20px;border-top:1px solid var(--stroke);flex-wrap:wrap;gap:12px;}
.btn-save{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--r-md);border:none;background:var(--green);color:#fff;font-size:14px;font-weight:600;cursor:pointer;transition:all 150ms ease;text-decoration:none;}
.btn-save:hover{opacity:0.9;transform:translateY(-1px);}
.btn-cancel{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--r-md);border:1px solid var(--stroke);background:transparent;color:var(--txt-soft);font-size:14px;font-weight:600;text-decoration:none;transition:all 150ms ease;}
.btn-cancel:hover{background:var(--panel-2);}
@media (max-width:1024px){.media-sections{grid-template-columns:1fr;}}
@media (max-width:768px){.estudios-tabs{overflow-x:auto;}.estudios-tab{white-space:nowrap;}.media-grid{grid-template-columns:repeat(2,1fr);}.form-footer{flex-direction:column;}.btn-cancel,.btn-save{width:100%;justify-content:center;}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<a href="<?php echo e(route('pacientes.edit')); ?>" class="back-link rise d1">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
  Volver a editar paciente
</a>

<div class="estudios-tabs rise d2">
  <button class="estudios-tab active" onclick="showTab('estudios')">Estudios</button>
  <button class="estudios-tab" onclick="showTab('imagenes')">Imágenes</button>
  <button class="estudios-tab" onclick="showTab('videos')">Videos</button>
  <button class="estudios-tab" onclick="showTab('reportes')">Reportes</button>
</div>


<div id="tab-estudios" class="tab-content active rise d3">
  <div class="section-card">
    <div class="section-header">
      <h3 class="section-title">Listado de estudios</h3>
      <button class="btn-save" style="padding:8px 16px;font-size:13px;" onclick="agregarEstudio()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo estudio
      </button>
    </div>
    <table class="estudios-table">
      <thead>
        <tr><th>Fecha</th><th>Tipo de estudio</th><th>Descripción</th><th style="text-align:right">Acciones</th></tr>
      </thead>
      <tbody>
        <tr data-id="1"><td>10/09/2024</td><td class="tipo-estudio">Colonoscopia</td><td class="desc-estudio">Estudio colonoscópico completo sin complicaciones.</td><td class="acciones-cell"><button class="btn-icon edit" onclick="editarEstudio(1)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/></svg></button><button class="btn-icon delete" onclick="eliminarEstudio(1)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></td></tr>
        <tr data-id="2"><td>12/03/2024</td><td class="tipo-estudio">Endoscopia digestiva alta</td><td class="desc-estudio">Se observa gastritis leve.</td><td class="acciones-cell"><button class="btn-icon edit" onclick="editarEstudio(2)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/></svg></button><button class="btn-icon delete" onclick="eliminarEstudio(2)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></td></tr>
        <tr data-id="3"><td>05/11/2023</td><td class="tipo-estudio">Colonoscopia</td><td class="desc-estudio">Pólipos pequeños removidos.</td><td class="acciones-cell"><button class="btn-icon edit" onclick="editarEstudio(3)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/></svg></button><button class="btn-icon delete" onclick="eliminarEstudio(3)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></td></tr>
      </tbody>
    </table>
  </div>

  
  <div class="media-sections" style="margin-top:24px;">
    
    <div class="media-section">
      <div class="media-section-header">
        <div class="media-section-title">
          <div class="media-section-icon images"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><circle cx="12" cy="12" r="4"/></svg></div>
          Imágenes <span class="section-count">4</span>
        </div>
        <a href="#" class="ver-todas" onclick="showTab('imagenes'); return false;">Ver todas</a>
      </div>
      <div class="media-grid" style="grid-template-columns:repeat(6,1fr);gap:8px;">
        <div class="media-item" style="height:80px;"><img src="https://picsum.photos/seed/endo1/150/150" alt="Endoscopia 1" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"></div>
        <div class="media-item" style="height:80px;"><img src="https://picsum.photos/seed/endo2/150/150" alt="Endoscopia 2" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"></div>
        <div class="media-item" style="height:80px;"><img src="https://picsum.photos/seed/endo3/150/150" alt="Endoscopia 3" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"></div>
        <div class="media-item" style="height:80px;"><img src="https://picsum.photos/seed/endo4/150/150" alt="Endoscopia 4" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"></div>
      </div>
    </div>

    
    <div class="media-section">
      <div class="media-section-header">
        <div class="media-section-title">
          <div class="media-section-icon videos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><circle cx="12" cy="12" r="4"/></svg></div>
          Videos <span class="section-count">3</span>
        </div>
        <a href="#" class="ver-todas" onclick="showTab('videos'); return false;">Ver todas</a>
      </div>
      <div class="media-grid" style="grid-template-columns:repeat(6,1fr);gap:8px;">
        <div class="media-item" style="height:80px;position:relative;"><img src="https://picsum.photos/seed/video1/150/150" alt="Video 1" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><div class="play-overlay" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.4);"><svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="color:#fff;"><polygon points="5 3 19 12 5 21 5 3"/></svg></div></div>
        <div class="media-item" style="height:80px;position:relative;"><img src="https://picsum.photos/seed/video2/150/150" alt="Video 2" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><div class="play-overlay" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.4);"><svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="color:#fff;"><polygon points="5 3 19 12 5 21 5 3"/></svg></div></div>
        <div class="media-item" style="height:80px;position:relative;"><img src="https://picsum.photos/seed/video3/150/150" alt="Video 3" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><div class="play-overlay" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.4);"><svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24" style="color:#fff;"><polygon points="5 3 19 12 5 21 5 3"/></svg></div></div>
      </div>
    </div>

    
    <div class="media-section">
      <div class="media-section-header">
        <div class="media-section-title">
          <div class="media-section-icon reports"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
          Reportes <span class="section-count">3</span>
        </div>
        <a href="#" class="ver-todas" onclick="showTab('reportes'); return false;">Ver todas</a>
      </div>
      <div class="reportes-list" style="max-height:200px;overflow-y:auto;">
        <div class="reporte-item" style="padding:10px 12px;">
          <div class="reporte-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
          <div class="reporte-info"><div class="reporte-name">Reporte_Colonoscopia_10_09_2024.pdf</div><div class="reporte-meta">10/09/2024 - 2.4 MB</div></div>
        </div>
        <div class="reporte-item" style="padding:10px 12px;">
          <div class="reporte-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
          <div class="reporte-info"><div class="reporte-name">Reporte_Endoscopia_12_03_2024.pdf</div><div class="reporte-meta">12/03/2024 - 1.8 MB</div></div>
        </div>
        <div class="reporte-item" style="padding:10px 12px;">
          <div class="reporte-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
          <div class="reporte-info"><div class="reporte-name">Reporte_Colonoscopia_05_11_2023.pdf</div><div class="reporte-meta">05/11/2023 - 2.1 MB</div></div>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="tab-imagenes" class="tab-content rise d3">
  <div class="section-card" style="border:2px solid var(--purple);">
    <div class="section-header">
      <h3 class="section-title" style="color:var(--purple);">Imágenes <span class="section-count" style="background:var(--purple);">4</span></h3>
      <button style="padding:8px 16px;font-size:13px;border:1px solid var(--purple);color:var(--purple);background:transparent;border-radius:var(--r-md);cursor:pointer;transition:all 150ms ease;" onmouseover="this.style.background='var(--purple)';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='var(--purple)'">+ Agregar</button>
    </div>
    <div class="media-grid" style="grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px;">
      <div class="media-item"><img src="https://picsum.photos/seed/endo1/300/300" alt="Endoscopia 1" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><button class="delete-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></div>
      <div class="media-item"><img src="https://picsum.photos/seed/endo2/300/300" alt="Endoscopia 2" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><button class="delete-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></div>
      <div class="media-item"><img src="https://picsum.photos/seed/endo3/300/300" alt="Endoscopia 3" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><button class="delete-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></div>
      <div class="media-item"><img src="https://picsum.photos/seed/endo4/300/300" alt="Endoscopia 4" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><button class="delete-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button></div>
    </div>
    <div class="dropzone">
      <div class="dropzone-text">+ Arrastra imágenes aquí</div>
      <div class="dropzone-hint">JPG, PNG (Máx. 10MB)</div>
    </div>
  </div>
</div>


<div id="tab-videos" class="tab-content rise d3">
  <div class="section-card">
    <div class="section-header">
      <h3 class="section-title">Videos <span class="section-count">3</span></h3>
      <button class="btn-save" style="padding:8px 16px;font-size:13px;">+ Agregar</button>
    </div>
    <div class="media-grid" style="grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px;">
      <div class="media-item" style="position:relative;"><img src="https://picsum.photos/seed/video1/300/300" alt="Video 1" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><div class="play-overlay" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.4);"><svg viewBox="0 0 24 24" fill="currentColor" width="40" height="40" style="color:#fff;"><polygon points="5 3 19 12 5 21 5 3"/></svg></div><button class="delete-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg></button></div>
      <div class="media-item" style="position:relative;"><img src="https://picsum.photos/seed/video2/300/300" alt="Video 2" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><div class="play-overlay" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.4);"><svg viewBox="0 0 24 24" fill="currentColor" width="40" height="40" style="color:#fff;"><polygon points="5 3 19 12 5 21 5 3"/></svg></div><button class="delete-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg></button></div>
      <div class="media-item" style="position:relative;"><img src="https://picsum.photos/seed/video3/300/300" alt="Video 3" style="width:100%;height:100%;object-fit:cover;border-radius:var(--r-md);"><div class="play-overlay" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.4);"><svg viewBox="0 0 24 24" fill="currentColor" width="40" height="40" style="color:#fff;"><polygon points="5 3 19 12 5 21 5 3"/></svg></div><button class="delete-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg></button></div>
    </div>
    <div class="dropzone">
      <div class="dropzone-text">+ Arrastra videos aquí</div>
      <div class="dropzone-hint">MP4, MOV (Máx. 100MB)</div>
    </div>
  </div>
</div>


<div id="tab-reportes" class="tab-content rise d3">
  <div class="section-card">
    <div class="section-header">
      <h3 class="section-title">Reportes <span class="section-count">3</span></h3>
      <button class="btn-save" style="padding:8px 16px;font-size:13px;">+ Agregar</button>
    </div>
    <div class="reportes-list">
      <div class="reporte-item">
        <div class="reporte-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div class="reporte-info"><div class="reporte-name">Reporte_Colonoscopia_10_09_2024.pdf</div><div class="reporte-meta">10/09/2024 - 2.4 MB</div></div>
        <div class="reporte-actions"><button class="btn-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button><button class="btn-icon delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg></button></div>
      </div>
      <div class="reporte-item">
        <div class="reporte-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div class="reporte-info"><div class="reporte-name">Reporte_Endoscopia_12_03_2024.pdf</div><div class="reporte-meta">12/03/2024 - 1.8 MB</div></div>
        <div class="reporte-actions"><button class="btn-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button><button class="btn-icon delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg></button></div>
      </div>
      <div class="reporte-item">
        <div class="reporte-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div class="reporte-info"><div class="reporte-name">Reporte_Colonoscopia_05_11_2023.pdf</div><div class="reporte-meta">05/11/2023 - 2.1 MB</div></div>
        <div class="reporte-actions"><button class="btn-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></button><button class="btn-icon delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg></button></div>
      </div>
    </div>
    <div class="dropzone" style="margin-top:16px;">
      <div class="dropzone-text">+ Arrastra PDFs aquí</div>
      <div class="dropzone-hint">PDF (Máx. 20MB)</div>
    </div>
  </div>
</div>


<div class="form-footer rise d4">
  <a href="<?php echo e(route('pacientes.edit')); ?>" class="btn-cancel">Cancelar</a>
  <a href="<?php echo e(route('pacientes.edit')); ?>" class="btn-save">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    Guardar cambios
  </a>
</div>


<div id="modalNuevoEstudio" class="modal-estudio-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center;">
  <div class="modal-estudio" style="background:var(--panel);border:1px solid var(--stroke);border-radius:var(--r-lg);padding:28px 24px;width:420px;max-width:90%;box-shadow:0 20px 60px rgba(0,0,0,.5);">
    <h3 id="modalTitulo" style="margin:0 0 20px;font-size:18px;font-weight:700;color:var(--txt);">Nuevo Estudio</h3>
    <input type="hidden" id="estudioIdEditando" value="">
    
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;color:var(--txt);margin-bottom:6px;">Fecha</label>
      <input type="date" id="estudioFecha" style="width:100%;padding:10px 12px;border:1px solid var(--stroke);border-radius:8px;font-size:14px;background:var(--panel-2);color:var(--txt);">
    </div>
    
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;color:var(--txt);margin-bottom:6px;">Tipo de estudio</label>
      <div style="display:flex;gap:8px;align-items:center;">
        <select id="estudioTipo" style="flex:1;padding:10px 12px;border:1px solid var(--stroke);border-radius:8px;font-size:14px;background:var(--panel-2);color:var(--txt);">
          <option value="Colonoscopia">Colonoscopia</option>
          <option value="Endoscopia digestiva alta">Endoscopia digestiva alta</option>
          <option value="Gastroscopia">Gastroscopia</option>
          <option value="Rectosigmoidoscopia">Rectosigmoidoscopia</option>
          <option value="Esofagogastroduodenoscopia">Esofagogastroduodenoscopia</option>
        </select>
        <button type="button" onclick="agregarTipoEstudio()" style="width:36px;height:36px;border:none;border-radius:8px;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 150ms ease;" title="Agregar tipo de estudio">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
      </div>
    </div>
    
    <div style="margin-bottom:16px;">
      <label style="display:block;font-size:13px;font-weight:600;color:var(--txt);margin-bottom:6px;">Descripción</label>
      <textarea id="estudioDesc" rows="3" placeholder="Descripción del estudio..." style="width:100%;padding:10px 12px;border:1px solid var(--stroke);border-radius:8px;font-size:14px;resize:vertical;background:var(--panel-2);color:var(--txt);"></textarea>
    </div>
    
    <div style="margin-bottom:20px;">
      <label style="display:block;font-size:13px;font-weight:600;color:var(--txt);margin-bottom:6px;">Archivo</label>
      <div id="dropzoneArchivo" style="border:2px dashed var(--stroke);border-radius:8px;padding:16px;text-align:center;cursor:pointer;transition:all 150ms ease;background:var(--panel-2);" onmouseover="this.style.borderColor='var(--blue)'" onmouseout="this.style.borderColor='var(--stroke)'" onclick="document.getElementById('estudioArchivo').click()">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--txt-soft)" stroke-width="1.5" style="margin:0 auto 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        <div style="font-size:14px;color:var(--txt);font-weight:500;">Haz clic para seleccionar archivo</div>
        <div id="nombreArchivo" style="font-size:12px;color:var(--txt-soft);margin-top:4px;">PDF, JPG, PNG (Máx. 20MB)</div>
      </div>
      <input type="file" id="estudioArchivo" style="display:none;" accept=".pdf,.jpg,.jpeg,.png" onchange="mostrarNombreArchivo(this)">
    </div>
    
    <div style="display:flex;justify-content:flex-end;gap:10px;">
      <button onclick="cerrarModalEstudio()" style="padding:10px 18px;border:1px solid var(--stroke);border-radius:8px;background:var(--panel);color:var(--txt);font-size:14px;font-weight:500;cursor:pointer;transition:all 150ms ease;" onmouseover="this.style.background='var(--panel-2)'" onmouseout="this.style.background='var(--panel)'">Cancelar</button>
      <button id="btnGuardarEstudio" onclick="guardarEstudio()" style="padding:10px 18px;border:none;border-radius:8px;background:var(--green);color:#fff;font-size:14px;font-weight:600;cursor:pointer;transition:all 150ms ease;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Guardar estudio</button>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function mostrarNombreArchivo(input) {
  const nombre = input.files[0]?.name || 'PDF, JPG, PNG (Máx. 20MB)';
  document.getElementById('nombreArchivo').textContent = nombre;
}

function showTab(tab) {
  document.querySelectorAll('.estudios-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  event.target.classList.add('active');
  document.getElementById('tab-' + tab).classList.add('active');
}

// Contador para nuevos estudios
let estudioIdCounter = 4;

// Función para agregar tipo de estudio personalizado
function agregarTipoEstudio() {
  const nuevoTipo = prompt('Ingrese el nombre del nuevo tipo de estudio:');
  if (!nuevoTipo || !nuevoTipo.trim()) return;
  
  const select = document.getElementById('estudioTipo');
  const option = document.createElement('option');
  option.value = nuevoTipo.trim();
  option.textContent = nuevoTipo.trim();
  option.selected = true;
  select.insertBefore(option, select.firstChild);
}

// Variable para saber si estamos editando
let estudioEditandoId = null;

// Función para abrir modal de nuevo estudio
function agregarEstudio() {
  estudioEditandoId = null;
  document.getElementById('modalTitulo').textContent = 'Nuevo Estudio';
  document.getElementById('btnGuardarEstudio').textContent = 'Guardar estudio';
  document.getElementById('estudioIdEditando').value = '';
  
  // Fecha por defecto: hoy
  const hoy = new Date().toISOString().split('T')[0];
  document.getElementById('estudioFecha').value = hoy;
  document.getElementById('estudioTipo').value = 'Colonoscopia';
  document.getElementById('estudioDesc').value = '';
  document.getElementById('estudioArchivo').value = '';
  document.getElementById('nombreArchivo').textContent = 'PDF, JPG, PNG (Máx. 20MB)';
  
  document.getElementById('modalNuevoEstudio').style.display = 'flex';
}

// Función para cerrar modal
function cerrarModalEstudio() {
  document.getElementById('modalNuevoEstudio').style.display = 'none';
}

// Función para guardar estudio desde modal (nuevo o editar)
function guardarEstudio() {
  const fecha = document.getElementById('estudioFecha').value;
  const tipo = document.getElementById('estudioTipo').value;
  const descripcion = document.getElementById('estudioDesc').value;
  const archivo = document.getElementById('estudioArchivo').files[0];
  const idEditando = estudioEditandoId;
  
  if (!fecha) {
    alert('Por favor selecciona una fecha');
    return;
  }
  if (!descripcion.trim()) {
    alert('Por favor ingresa una descripción');
    return;
  }
  
  // Formatear fecha
  const fechaObj = new Date(fecha);
  const fechaFormateada = fechaObj.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
  
  if (idEditando) {
    // Modo EDICIÓN: actualizar fila existente
    const tr = document.querySelector(`tr[data-id="${idEditando}"]`);
    if (tr) {
      tr.querySelector('td:first-child').textContent = fechaFormateada;
      tr.querySelector('.tipo-estudio').textContent = tipo;
      tr.querySelector('.desc-estudio').textContent = descripcion + (archivo ? ` <span style="color:var(--blue);">(${archivo.name})</span>` : '');
    }
    cerrarModalEstudio();
    alert('Estudio actualizado' + (archivo ? '\nArchivo: ' + archivo.name : ''));
  } else {
    // Modo NUEVO: crear nueva fila
    const tbody = document.querySelector('.estudios-table tbody');
    const tr = document.createElement('tr');
    tr.setAttribute('data-id', estudioIdCounter);
    tr.innerHTML = `
      <td>${fechaFormateada}</td>
      <td class="tipo-estudio">${tipo}</td>
      <td class="desc-estudio">${descripcion}${archivo ? ' <span style="color:var(--blue);">(' + archivo.name + ')</span>' : ''}</td>
      <td class="acciones-cell">
        <button class="btn-icon edit" onclick="editarEstudio(${estudioIdCounter})">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/>
          </svg>
        </button>
        <button class="btn-icon delete" onclick="eliminarEstudio(${estudioIdCounter})">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
            <path d="M3 6h18"/>
            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
          </svg>
        </button>
      </td>
    `;
    
    tbody.insertBefore(tr, tbody.firstChild);
    estudioIdCounter++;
    cerrarModalEstudio();
    alert('Estudio guardado' + (archivo ? '\nArchivo: ' + archivo.name : ''));
  }
}

// Función para eliminar estudio
function eliminarEstudio(id) {
  if (!confirm('¿Estás seguro de eliminar este estudio?')) return;
  
  const tr = document.querySelector(`tr[data-id="${id}"]`);
  if (tr) {
    tr.remove();
  }
}

// Función para editar estudio
function editarEstudio(id) {
  const tr = document.querySelector(`tr[data-id="${id}"]`);
  if (!tr) return;
  
  estudioEditandoId = id;
  
  const fechaCell = tr.querySelector('td:first-child');
  const tipoCell = tr.querySelector('.tipo-estudio');
  const descCell = tr.querySelector('.desc-estudio');
  
  // Convertir fecha dd/mm/yyyy a yyyy-mm-dd para el input date
  const fechaParts = fechaCell.textContent.split('/');
  const fechaISO = `${fechaParts[2]}-${fechaParts[1]}-${fechaParts[0]}`;
  
  document.getElementById('modalTitulo').textContent = 'Editar Estudio';
  document.getElementById('btnGuardarEstudio').textContent = 'Guardar cambios';
  document.getElementById('estudioIdEditando').value = id;
  
  document.getElementById('estudioFecha').value = fechaISO;
  document.getElementById('estudioTipo').value = tipoCell.textContent;
  document.getElementById('estudioDesc').value = descCell.textContent;
  document.getElementById('estudioArchivo').value = '';
  document.getElementById('nombreArchivo').textContent = 'PDF, JPG, PNG (Máx. 20MB) - Opcional para reemplazar';
  
  document.getElementById('modalNuevoEstudio').style.display = 'flex';
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalNuevoEstudio').addEventListener('click', function(e) {
  if (e.target === this) cerrarModalEstudio();
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
</file>
</invoke>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\pacientes\estudios.blade.php ENDPATH**/ ?>