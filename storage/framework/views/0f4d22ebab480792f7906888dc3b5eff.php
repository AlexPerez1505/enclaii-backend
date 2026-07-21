<style>
.ip-shell{max-width:1040px}
.ip-toolbar{display:flex;align-items:center;gap:12px;margin-bottom:18px;flex-wrap:wrap}
.ip-back{
  height:44px;display:inline-flex;align-items:center;gap:8px;padding:0 16px;
  border:1px solid var(--stroke);border-radius:10px;background:var(--panel-2);
  color:var(--txt);font-size:13px;font-weight:700;cursor:pointer;
  transition:background-color 150ms,border-color 150ms;
  text-decoration:none;
}
.ip-back:hover{background:var(--panel);border-color:var(--blue)}
.ip-title{font-size:18px;font-weight:700;color:var(--txt);margin-left:auto}

.ip-dropzone{
  border:2px dashed rgba(46,123,246,.35);border-radius:18px;
  background:rgba(46,123,246,.05);
  padding:48px 24px;text-align:center;cursor:pointer;
  transition:border-color 150ms,background-color 150ms,transform 150ms;
}
.ip-dropzone:hover,.ip-dropzone.dragover{
  border-color:var(--blue);background:rgba(46,123,246,.1);transform:translateY(-1px);
}
.ip-dropzone-icon{
  width:64px;height:64px;border-radius:18px;
  background:rgba(46,123,246,.12);color:var(--blue);
  display:grid;place-items:center;margin:0 auto 16px;
}
.ip-dropzone-title{font-size:16px;font-weight:700;color:var(--txt);margin-bottom:6px}
.ip-dropzone-desc{font-size:13px;color:var(--txt-soft);margin-bottom:18px}
.ip-dropzone-hint{font-size:12px;color:var(--txt-soft);opacity:.7}
.ip-dropzone input[type=file]{display:none}

.ip-preview-grid{
  display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px;
  margin-top:20px;
}
.ip-preview-item{
  position:relative;border-radius:12px;overflow:hidden;
  background:var(--panel-2);border:1px solid var(--stroke);aspect-ratio:1;
  display:grid;place-items:center;
}
.ip-preview-item img,.ip-preview-item video{width:100%;height:100%;object-fit:cover}
.ip-preview-item .ip-file-icon{
  display:flex;flex-direction:column;align-items:center;gap:6px;color:var(--txt-soft);
}
.ip-preview-item .ip-file-icon svg{color:var(--blue)}
.ip-preview-item .ip-file-name{
  position:absolute;bottom:0;left:0;right:0;padding:6px 8px;
  font-size:11px;color:var(--txt);background:linear-gradient(0deg,rgba(0,0,0,.8),transparent);
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.ip-preview-remove{
  position:absolute;top:6px;right:6px;width:24px;height:24px;border-radius:6px;
  background:rgba(0,0,0,.5);color:#fff;border:none;display:grid;place-items:center;
  cursor:pointer;opacity:0;transition:opacity 150ms;
}
.ip-preview-item:hover .ip-preview-remove{opacity:1}

.ip-actions{
  display:flex;align-items:center;justify-content:flex-end;gap:12px;
  margin-top:24px;padding-top:18px;border-top:1px solid var(--stroke);
}
.ip-btn{
  display:inline-flex;align-items:center;gap:8px;
  height:44px;padding:0 20px;border-radius:10px;
  font-size:13px;font-weight:700;cursor:pointer;transition:all 150ms;
}
.ip-btn-secondary{
  background:var(--panel-2);border:1px solid var(--stroke);color:var(--txt);
}
.ip-btn-secondary:hover{background:var(--panel);border-color:var(--blue)}
.ip-btn-primary{
  background:var(--blue);border:1px solid var(--blue);color:#fff;
}
.ip-btn-primary:hover{background:#2563eb;border-color:#2563eb;transform:translateY(-1px)}
.ip-btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none}

.ip-empty{
  text-align:center;padding:40px 0;color:var(--txt-soft);display:none;
}

/* ================= TEMA CLARO ================= */
html[data-theme="light"] .ip-dropzone{
  border-color:rgba(46,123,246,.35);
  background:rgba(46,123,246,.05);
}
html[data-theme="light"] .ip-dropzone:hover,
html[data-theme="light"] .ip-dropzone.dragover{
  background:rgba(46,123,246,.08);
}
html[data-theme="light"] .ip-dropzone-icon{background:rgba(46,123,246,.12)}
html[data-theme="light"] .ip-preview-item .ip-file-name{background:linear-gradient(0deg,rgba(0,0,0,.7),transparent)}
html[data-theme="light"] .ip-preview-remove{background:rgba(0,0,0,.45);color:#fff}
html[data-theme="light"] .ip-btn-primary{color:#fff}
html[data-theme="light"] .ip-btn-primary:hover{background:#2563eb;border-color:#2563eb}
</style>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\importar\importar-css.blade.php ENDPATH**/ ?>