<style>
/* Widget Pacientes pendientes hoy - estilo minimalista */
.card-minimal-list {
  justify-content: flex-start !important;
  gap: 0.5em !important;
  padding: 1em 1.25em !important;
  overflow: hidden !important;
  container-type: inline-size;
}
.card-minimal-list .min-list { list-style: none; padding: 0; margin: 0; width: 100%; }
.card-minimal-list .min-list-row { transition: background .15s ease; border-radius: 8px; padding: 0.35em 0.25em; }
.card-minimal-list .min-list-row:hover { background: rgba(46,123,246,.08); }
.card-minimal-list .min-list-avatar { transition: transform .15s ease, box-shadow .15s ease; }
.card-minimal-list .min-list-row:hover .min-list-avatar { transform: scale(1.05); box-shadow: 0 0 10px rgba(46,123,246,.25); }
.card-minimal-list .min-list-arrow { transition: transform .15s ease; }
.card-minimal-list .min-list-row:hover .min-list-arrow { transform: translateX(3px); }
</style>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\dashboard\widgets\next-list\styles.blade.php ENDPATH**/ ?>