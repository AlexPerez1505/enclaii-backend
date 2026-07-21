<?php $__env->startSection('title', 'Finanzas'); ?>
<?php $__env->startSection('active', 'finanzas'); ?>
<?php $__env->startSection('header-title', 'Finanzas'); ?>
<?php $__env->startSection('header-sub'); ?>
  Control financiero de la clinica
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.fin-shell{display:grid;gap:18px}
.fin-hero{border:1px solid var(--stroke);background:var(--panel);border-radius:16px;padding:28px;box-shadow:var(--shadow)}
.fin-kicker{display:flex;align-items:center;gap:10px;font-family:'Sora',sans-serif;font-size:13px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--txt)}
.fin-kicker span{font-size:20px}
.fin-hero p{margin:14px 0 0;color:var(--txt);font-size:17px;line-height:1.5}
.fin-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
.fin-card{background:var(--panel);border:1px solid var(--stroke);border-radius:14px;padding:18px;box-shadow:var(--shadow)}
.fin-card small{display:block;color:var(--txt-soft);font-size:12px;margin-bottom:8px}
.fin-card strong{display:block;color:var(--txt);font-family:'Sora',sans-serif;font-size:24px;line-height:1.1}
.fin-card span{display:block;margin-top:8px;color:var(--green);font-size:12px;font-weight:700}
.fin-panel{background:var(--panel);border:1px solid var(--stroke);border-radius:14px;padding:18px}
.fin-panel h2{margin:0 0 14px;font-size:17px;color:var(--txt)}
.fin-table{width:100%;border-collapse:collapse}
.fin-table th,.fin-table td{padding:13px 10px;border-bottom:1px solid var(--stroke);text-align:left;font-size:13px}
.fin-table th{color:var(--txt-soft);font-weight:700}
.fin-table td{color:var(--txt)}
.fin-status{display:inline-flex;padding:4px 10px;border-radius:999px;background:rgba(63,209,140,.12);color:var(--green);font-size:11px;font-weight:800}
@media(max-width:980px){.fin-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:620px){.fin-grid{grid-template-columns:1fr}.fin-hero{padding:22px}.fin-table{min-width:620px}.fin-panel{overflow:auto}}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section class="fin-shell">
  <div class="fin-hero rise d1">
    <div class="fin-kicker"><span>$</span> Finanzas</div>
    <p>Control financiero de la clinica</p>
  </div>

  <div class="fin-grid">
    <article class="fin-card rise d2">
      <small>Ingresos del mes</small>
      <strong>$128,400</strong>
      <span>+12.5% vs mes anterior</span>
    </article>
    <article class="fin-card rise d3">
      <small>Cuentas por cobrar</small>
      <strong>$34,800</strong>
      <span>8 facturas pendientes</span>
    </article>
    <article class="fin-card rise d4">
      <small>Gastos operativos</small>
      <strong>$42,150</strong>
      <span>Dentro del presupuesto</span>
    </article>
    <article class="fin-card rise d5">
      <small>Utilidad estimada</small>
      <strong>$86,250</strong>
      <span>Margen 67.1%</span>
    </article>
  </div>

  <div class="fin-panel rise d6">
    <h2>Movimientos recientes</h2>
    <table class="fin-table">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Concepto</th>
          <th>Paciente</th>
          <th>Monto</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>20/06/2026</td>
          <td>Colonoscopia</td>
          <td>Maria Gonzalez</td>
          <td>$8,500</td>
          <td><span class="fin-status">Pagado</span></td>
        </tr>
        <tr>
          <td>19/06/2026</td>
          <td>Endoscopia diagnostica</td>
          <td>Jose Ramirez</td>
          <td>$6,200</td>
          <td><span class="fin-status">Pagado</span></td>
        </tr>
        <tr>
          <td>18/06/2026</td>
          <td>Consulta y reporte</td>
          <td>Laura Perez</td>
          <td>$2,400</td>
          <td><span class="fin-status">Pagado</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\finanzas\index.blade.php ENDPATH**/ ?>