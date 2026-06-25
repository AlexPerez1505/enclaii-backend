<style>
/* Widget Resumen de estudios */
.donut-box{display:flex;align-items:center;gap:1.1em;margin-bottom:1.1em;flex:1;min-height:0}
.donut{position:relative;width:8em;height:8em;flex:none}
.donut svg{width:100%;height:100%;transform:rotate(-90deg)}
.donut circle{fill:none;stroke-width:14;stroke-linecap:round}
.donut .track{stroke:rgba(110,160,255,.12)}
.donut-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center}
.donut-center .n{font-family:'Sora',sans-serif;font-size:1.75em;font-weight:800;line-height:1}
.donut-center .l{font-size:0.66em;color:var(--txt-soft);line-height:1.2;margin-top:0.2em}
.legend{display:flex;flex-direction:column;gap:0.55em;font-size:0.85em}
.legend i{display:inline-block;width:0.55em;height:0.55em;border-radius:50%;margin-right:0.5em}
.legend .b i{background:var(--blue)}
.legend .g i{background:var(--green)}
.legend .r i{background:var(--red)}
.next-list h4{font-size:0.78em;font-weight:600;color:var(--txt-soft);margin-bottom:0.75em}
.next-item{display:flex;align-items:center;justify-content:space-between;gap:0.5em;padding:0.5em 0;font-size:0.85em;border-bottom:1px solid rgba(110,160,255,.08)}
.next-item:last-child{border-bottom:0}
.next-item .t{color:var(--txt-soft);font-size:0.75em;flex:none}
.next-item .n{font-weight:600;flex:1}

.widget:not(.widget-minimal)[data-widget-id="agenda-summary"] .next-list{
  display:flex;flex-direction:column;flex:1;min-height:0;
}
.widget:not(.widget-minimal)[data-widget-id="agenda-summary"] .next-list .next-item{
  flex:1;display:flex;align-items:center;
}

/* Widget Resumen de estudios - estilo minimalista */
.card-minimal-summary .min-value { color: var(--cyan); }
</style>
