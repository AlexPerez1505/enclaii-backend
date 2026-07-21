<style>
/* Widget Próximo Paciente - estilo minimalista */
.card-minimal-next {
  justify-content: flex-start !important;
  gap: 0.5em !important;
  padding: 1em 1.25em !important;
  overflow: hidden !important;
  container-type: inline-size;
}
.card-minimal-next .min-icon { margin: 0 auto; width: 100%; max-height: 60%; }
.card-minimal-next .min-icon > svg { width: 100%; height: 100%; max-height: 100%; filter: drop-shadow(0 0 6px rgba(56,199,244,.18)) drop-shadow(0 0 14px rgba(56,199,244,.08)); transition: filter .2s ease; }
.card-minimal-next .min-icon:hover > svg { filter: drop-shadow(0 0 8px rgba(56,199,244,.22)) drop-shadow(0 0 18px rgba(56,199,244,.12)); }
.card-minimal-next .min-value { color: var(--cyan); word-break: break-word; }
.card-minimal-next .min-meta { word-break: break-word; }
.card-minimal-next .min-text { width: 100%; }
</style>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views/dashboard/widgets/next-patient/styles.blade.php ENDPATH**/ ?>