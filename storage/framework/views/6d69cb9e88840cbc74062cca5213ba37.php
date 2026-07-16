<?php $__env->startSection('title', 'Soporte — Chats'); ?>
<?php $__env->startSection('active', 'customer-success-soporte'); ?>
<?php $__env->startSection('header-title', 'Soporte'); ?>
<?php $__env->startSection('header-sub', 'Chats pendientes y activos de usuarios.'); ?>

<?php $__env->startSection('sidebar'); ?>
  <?php echo $__env->make('customer-success.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.ag-grid{display:grid;grid-template-columns:1fr 1.16fr;grid-template-rows:minmax(260px,1fr) minmax(150px,auto);gap:16px;width:100%;max-width:none;min-height:calc(100vh - 175px)}
.ag-card{min-height:0;background:linear-gradient(145deg,#08162c 0%,#071225 100%);border:1px solid rgba(59,130,246,.16);border-radius:16px;padding:0;overflow:hidden;box-shadow:0 18px 35px rgba(0,0,0,.18)}
.ag-card:first-child{background:radial-gradient(circle at 50% 42%,rgba(107,45,210,.18),transparent 30%),linear-gradient(145deg,#140a2d 0%,#0c1030 100%);border-color:rgba(168,85,247,.52);box-shadow:0 18px 35px rgba(63,23,116,.22),inset 0 1px 0 rgba(255,255,255,.04)}
.ag-card:nth-child(2){border-color:rgba(34,197,94,.4);background:linear-gradient(145deg,#071d27 0%,#071225 64%)}
.ag-card--resolved{grid-column:1/-1;min-height:150px;border-color:rgba(34,197,94,.22);background:linear-gradient(100deg,#071c2b 0%,#08152b 74%)}
.ag-card-header{padding:22px 20px 12px;border:0;display:flex;align-items:center;justify-content:space-between}
.ag-card-header h2{font-size:15px;font-weight:700;margin:0;color:#eef4ff;display:flex;align-items:center;gap:10px}
.ag-card-header svg{width:18px;height:18px;padding:11px;border-radius:12px;box-sizing:content-box;background:linear-gradient(135deg,rgba(168,85,247,.3),rgba(109,40,217,.14))}
.ag-card:nth-child(2) .ag-card-header svg,.ag-card--resolved .ag-card-header svg{background:linear-gradient(135deg,rgba(34,197,94,.3),rgba(5,150,105,.13))}
.ag-badge{display:inline-flex;align-items:center;justify-content:center;min-width:20px;height:20px;border-radius:99px;font-size:11px;font-weight:700;padding:0 5px}
.ag-badge.pending{background:rgba(168,85,247,.22);color:#d8b4fe;box-shadow:0 0 14px rgba(168,85,247,.2)}
.ag-badge.active{background:rgba(34,197,94,.18);color:#4ade80;box-shadow:0 0 14px rgba(34,197,94,.16)}
.ag-empty{min-height:205px;display:flex;align-items:center;justify-content:center;padding:32px 20px;text-align:center;color:#9daac6;font-size:13px}
.ag-card:first-child .ag-empty{color:#c4b5fd}
.ag-conv-item{margin:10px 16px;display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid rgba(59,130,246,.1);border-radius:14px;background:rgba(19,37,70,.5);transition:background .15s,border-color .15s,transform .15s;cursor:pointer;text-decoration:none;color:inherit}
.ag-conv-item:last-child{border-bottom:1px solid rgba(59,130,246,.1)}
.ag-conv-item:hover{background:rgba(34,62,108,.6);border-color:rgba(96,165,250,.3);transform:translateY(-1px)}
.ag-conv-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#2563eb);box-shadow:0 0 16px rgba(99,102,241,.34);display:grid;place-items:center;flex-shrink:0;font-size:13px;font-weight:700;color:#fff}
.ag-conv-info{flex:1;min-width:0}
.ag-conv-info strong{display:block;font-size:13px;font-weight:600;color:#edf3ff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ag-conv-info span{display:block;font-size:12px;color:#91a0bd;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ag-conv-time{font-size:11px;color:#a9b7d0;flex-shrink:0}
.ag-resolved-link{display:flex;align-items:center;gap:12px;flex:1;min-width:0;color:inherit;text-decoration:none}
.ag-delete-resolved{width:28px;height:28px;border:1px solid rgba(248,113,113,.28);border-radius:8px;background:rgba(127,29,29,.18);color:#fca5a5;cursor:pointer;display:grid;place-items:center;flex-shrink:0}
.ag-delete-resolved:hover{background:rgba(185,28,28,.35);color:#fff}

html[data-theme="light"] .ag-card{
  background:linear-gradient(145deg,#fff,#f8fbff);border-color:#dbe5f5;box-shadow:0 10px 28px rgba(15,23,42,.06)
}
html[data-theme="light"] .ag-card:first-child{
  background:radial-gradient(circle at 50% 42%,rgba(139,92,246,.12),transparent 34%),linear-gradient(145deg,#fdfaff,#f5f3ff);border-color:#c4b5fd;box-shadow:0 10px 28px rgba(109,40,217,.08)
}
html[data-theme="light"] .ag-card:nth-child(2){
  background:linear-gradient(145deg,#f5fffb,#f8fbff);border-color:#86efac
}
html[data-theme="light"] .ag-card--resolved{
  background:linear-gradient(100deg,#f0fdf4,#f8fbff);border-color:#a7f3d0
}
html[data-theme="light"] .ag-card-header h2{color:#1e293b}
html[data-theme="light"] .ag-card-header svg{background:linear-gradient(135deg,#f3e8ff,#ede9fe)}
html[data-theme="light"] .ag-card:nth-child(2) .ag-card-header svg,
html[data-theme="light"] .ag-card--resolved .ag-card-header svg{background:linear-gradient(135deg,#dcfce7,#d1fae5)}
html[data-theme="light"] .ag-badge.pending{background:#f3e8ff;color:#7e22ce;box-shadow:none}
html[data-theme="light"] .ag-badge.active{background:#dcfce7;color:#15803d;box-shadow:none}
html[data-theme="light"] .ag-empty{color:#64748b}
html[data-theme="light"] .ag-card:first-child .ag-empty{color:#7e22ce}
html[data-theme="light"] .ag-conv-item{background:#fff;border-color:#e2e8f0;box-shadow:0 1px 2px rgba(15,23,42,.03)}
html[data-theme="light"] .ag-conv-item:last-child{border-color:#e2e8f0}
html[data-theme="light"] .ag-conv-item:hover{background:#f8fbff;border-color:#93c5fd;box-shadow:0 5px 14px rgba(59,130,246,.09)}
html[data-theme="light"] .ag-conv-avatar{box-shadow:0 0 14px rgba(99,102,241,.2)}
html[data-theme="light"] .ag-conv-info strong{color:#1e293b}
html[data-theme="light"] .ag-conv-info span{color:#64748b}
html[data-theme="light"] .ag-conv-time{color:#64748b}
html[data-theme="light"] .ag-delete-resolved{background:#fff1f2;border-color:#fecdd3;color:#e11d48}
html[data-theme="light"] .ag-delete-resolved:hover{background:#e11d48;color:#fff}
@media(max-width:760px){.ag-grid{grid-template-columns:1fr;grid-template-rows:auto;min-height:0}.ag-card{min-height:0}.ag-card--resolved{min-height:0}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="ag-grid">

  
  <div class="ag-card">
    <div class="ag-card-header">
      <h2>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#d946ef" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Pendientes
        <span class="ag-badge pending" id="badgePending"><?php echo e($pending->count()); ?></span>
      </h2>
    </div>

    <?php if($pending->isEmpty()): ?>
      <div class="ag-empty">No hay chats pendientes </div>
    <?php else: ?>
      <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a class="ag-conv-item" href="<?php echo e(route('customer-success.soporte.chat', $conv)); ?>">
          <div class="ag-conv-avatar"><?php echo e(mb_strtoupper(mb_substr($conv->user?->name ?? '?', 0, 1))); ?></div>
          <div class="ag-conv-info">
            <strong><?php echo e($conv->user?->name); ?> <?php echo e($conv->user?->apellido_paterno); ?></strong>
            <span><?php echo e($conv->latestMessage?->content ?? $conv->title); ?></span>
          </div>
          <span class="ag-conv-time"><?php echo e($conv->last_message_at?->diffForHumans()); ?></span>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
  </div>

  
  <div class="ag-card">
    <div class="ag-card-header">
      <h2>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Mis chats activos
        <span class="ag-badge active"><?php echo e($active->count()); ?></span>
      </h2>
    </div>

    <?php if($active->isEmpty()): ?>
      <div class="ag-empty">No tienes chats activos</div>
    <?php else: ?>
      <?php $__currentLoopData = $active; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a class="ag-conv-item" href="<?php echo e(route('customer-success.soporte.chat', $conv)); ?>">
          <div class="ag-conv-avatar"><?php echo e(mb_strtoupper(mb_substr($conv->user?->name ?? '?', 0, 1))); ?></div>
          <div class="ag-conv-info">
            <strong><?php echo e($conv->user?->name); ?> <?php echo e($conv->user?->apellido_paterno); ?></strong>
            <span><?php echo e($conv->latestMessage?->content ?? $conv->title); ?></span>
          </div>
          <span class="ag-conv-time"><?php echo e($conv->last_message_at?->diffForHumans()); ?></span>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
  </div>

  <div class="ag-card ag-card--resolved">
    <div class="ag-card-header">
      <h2>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        Usuarios atendidos
        <span class="ag-badge active"><?php echo e($resolved->count()); ?></span>
      </h2>
    </div>

    <?php if($resolved->isEmpty()): ?>
      <div class="ag-empty">Aún no tienes chats resueltos.</div>
    <?php else: ?>
      <?php $__currentLoopData = $resolved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="ag-conv-item">
          <a class="ag-resolved-link" href="<?php echo e(route('customer-success.soporte.chat', $conv)); ?>">
            <div class="ag-conv-avatar"><?php echo e(mb_strtoupper(mb_substr($conv->user?->name ?? '?', 0, 1))); ?></div>
            <div class="ag-conv-info">
              <strong><?php echo e($conv->user?->name); ?> <?php echo e($conv->user?->apellido_paterno); ?></strong>
              <span><?php echo e($conv->latestMessage?->content ?? $conv->title); ?></span>
            </div>
            <span class="ag-conv-time">Resuelto <?php echo e($conv->closed_at?->diffForHumans()); ?></span>
          </a>
          <button class="ag-delete-resolved" type="button" data-delete-url="<?php echo e(route('customer-success.api.soporte.destroy', $conv)); ?>" title="Eliminar conversación resuelta">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="m19 6-1 14H6L5 6m4 0V4h6v2"/></svg>
          </button>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  var pendingList  = document.querySelector('.ag-card:first-child');
  var badgePending = document.getElementById('badgePending');
  var pollUrl      = "<?php echo e(route('customer-success.api.soporte.pending')); ?>";
  var prevCount    = <?php echo e($pending->count()); ?>;

  function renderItem(conv){
    var initial = conv.user_name ? conv.user_name.trim().charAt(0).toUpperCase() : '?';
    var url = '/customer-success/soporte/' + conv.id;
    return '<a class="ag-conv-item" href="' + url + '">'
      + '<div class="ag-conv-avatar">' + initial + '</div>'
      + '<div class="ag-conv-info">'
      + '<strong>' + (conv.user_name || 'Usuario') + '</strong>'
      + '<span>' + (conv.last_message || conv.title || '') + '</span>'
      + '</div>'
      + '<span class="ag-conv-time">' + (conv.last_message_at || '') + '</span>'
      + '</a>';
  }

  setInterval(async function(){
    try {
      var r    = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
      var data = await r.json();
      if (!data.ok) return;

      var convs  = data.conversations;
      var count  = convs.length;

      badgePending.textContent = count;

      var existingItems = pendingList.querySelectorAll('.ag-conv-item');
      existingItems.forEach(function(el){ el.remove(); });

      var emptyEl = pendingList.querySelector('.ag-empty');
      if(emptyEl) emptyEl.remove();

      if(count === 0){
        var empty = document.createElement('div');
        empty.className = 'ag-empty';
        empty.textContent = 'No hay chats pendientes 🎉';
        pendingList.appendChild(empty);
      } else {
        convs.forEach(function(conv){
          pendingList.insertAdjacentHTML('beforeend', renderItem(conv));
        });
      }

      if(count > prevCount){
        badgePending.style.transform = 'scale(1.4)';
        setTimeout(function(){ badgePending.style.transform = ''; }, 400);
        try {
          var ctx = new (window.AudioContext || window.webkitAudioContext)();
          var osc = ctx.createOscillator();
          var gain = ctx.createGain();
          osc.connect(gain); gain.connect(ctx.destination);
          osc.frequency.value = 880;
          gain.gain.setValueAtTime(0.15, ctx.currentTime);
          gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
          osc.start(); osc.stop(ctx.currentTime + 0.3);
        } catch(e){}
      }

      prevCount = count;
    } catch(e){}
  }, 5000);

  document.querySelectorAll('.ag-delete-resolved').forEach(function(button){
    button.addEventListener('click', async function(){
      if (!confirm('¿Eliminar esta conversación resuelta? Esta acción no se puede deshacer.')) return;
      button.disabled = true;
      try {
        var response = await fetch(button.dataset.deleteUrl, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
          }
        });
        var data = await response.json();
        if (response.ok && data.ok) window.location.reload();
        else button.disabled = false;
      } catch(e) {
        button.disabled = false;
      }
    });
  });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views-Tec/customer-success/soporte/index.blade.php ENDPATH**/ ?>