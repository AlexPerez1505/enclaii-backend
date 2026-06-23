{{-- Widget: IA Predictiva --}}
<div class="widget rise d8" data-widget-id="ia-risk" data-w="13">
  <span class="widget-drag-handle" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="19" r="1"/></svg>
  </span>
  <article class="card card-pred">
    <div>
      <div class="pred-head">
        <div class="orb">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 2.4-1.2 4.5-3 5.7V17a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2.3C6.2 13.5 5 11.4 5 9a7 7 0 0 1 7-7z"/><line x1="9" y1="22" x2="15" y2="22"/></svg>
        </div>
        <div>
          <h3>IA Predictiva</h3>
          <p>Análisis inteligente basado en el historial clínico</p>
        </div>
      </div>
      <div class="pred-note">
        Tu próximo paciente presenta antecedentes de <b>gastritis crónica</b> y <b>riesgo moderado</b> de úlceras pépticas.
      </div>
    </div>
    <div class="gauge-box">
      <h4>Nivel de riesgo</h4>
      <div class="gauge">
        <svg viewBox="0 0 120 120">
          <circle class="track" cx="60" cy="60" r="50"/>
          <circle class="val" cx="60" cy="60" r="50" stroke-dasharray="314.16" stroke-dashoffset="314.16" data-pct="65"/>
        </svg>
        <div class="gauge-center">
          <div>
            <div class="lvl">Moderado</div>
            <div class="pct"><span id="numRiesgo" data-target="65">0</span>%</div>
          </div>
        </div>
      </div>
    </div>
    <div class="recs">
      <h4>Recomendaciones IA</h4>
      <ul>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Revisar historial de biopsias previas
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Considerar toma de muestra
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Monitorear signos vitales
        </li>
      </ul>
    </div>
    <div class="hist">
      <h4>Historial relevante</h4>
      <div class="hist-item">Gastritis crónica <span>2024</span></div>
      <div class="hist-item">Reflujo gastroesofágico <span>2023</span></div>
      <div class="hist-item">Colonoscopia normal <span>2022</span></div>
      <a class="tbl-link" href="#">
        Ver historial completo
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </article>
  <span class="widget-resize-handle"></span>
</div>
