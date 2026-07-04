<style>
/* Widget IA Predictiva - estilo minimalista */
.card-minimal-risk {
  justify-content: flex-start !important;
  gap: 0.5em !important;
  padding: 1em 1.25em !important;
  overflow: hidden !important;
  container-type: inline-size;
}
.card-minimal-risk .risk-grid { width: 100%; }
.card-minimal-risk .risk-brain { transition: transform .15s ease, box-shadow .15s ease; }
.card-minimal-risk .risk-brain:hover { transform: scale(1.04); box-shadow: 0 0 16px rgba(178,99,255,.25); }
.card-minimal-risk .risk-brain > svg { width: 100%; height: 100%; max-width: 100%; max-height: 100%; }
@container (max-width: 240px) {
  .card-minimal-risk .risk-grid { grid-template-columns: 1fr !important; gap: 0.6em !important; }
  .card-minimal-risk .risk-divider { display: none; }
  .card-minimal-risk .risk-right { display: none; }
}
</style>
