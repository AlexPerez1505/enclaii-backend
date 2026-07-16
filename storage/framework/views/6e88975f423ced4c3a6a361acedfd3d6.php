<?php if($paginator->hasPages()): ?>
<nav class="paginacion-laravel" role="navigation">

  
  <div class="paginacion-mobile">
    <?php if($paginator->onFirstPage()): ?>
      <span class="paginacion-item disabled">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
      </span>
    <?php else: ?>
      <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="paginacion-item">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
      </a>
    <?php endif; ?>

    <?php if($paginator->hasMorePages()): ?>
      <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="paginacion-item">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
      </a>
    <?php else: ?>
      <span class="paginacion-item disabled">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
      </span>
    <?php endif; ?>
  </div>

  
  <div class="paginacion-desktop">
    <div class="paginacion-info">
      <?php if($paginator->firstItem()): ?>
        Mostrando <strong><?php echo e($paginator->firstItem()); ?></strong> – <strong><?php echo e($paginator->lastItem()); ?></strong> de <strong><?php echo e($paginator->total()); ?></strong> registros
      <?php else: ?>
        <?php echo e($paginator->count()); ?> registros
      <?php endif; ?>
    </div>

    <div class="paginacion-links">
      
      <?php if($paginator->onFirstPage()): ?>
        <span class="paginacion-item disabled" aria-disabled="true">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </span>
      <?php else: ?>
        <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="paginacion-item" aria-label="Anterior">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </a>
      <?php endif; ?>

      
      <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(is_string($element)): ?>
          <span class="paginacion-item dots">…</span>
        <?php endif; ?>
        <?php if(is_array($element)): ?>
          <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($page == $paginator->currentPage()): ?>
              <span class="paginacion-item active" aria-current="page"><?php echo e($page); ?></span>
            <?php else: ?>
              <a href="<?php echo e($url); ?>" class="paginacion-item" aria-label="Ir a página <?php echo e($page); ?>"><?php echo e($page); ?></a>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      
      <?php if($paginator->hasMorePages()): ?>
        <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="paginacion-item" aria-label="Siguiente">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </a>
      <?php else: ?>
        <span class="paginacion-item disabled" aria-disabled="true">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </span>
      <?php endif; ?>
    </div>
  </div>

</nav>
<?php endif; ?>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>