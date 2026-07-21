<script>
(function () {
  /* Seleccionar captura → actualizar vista previa */
  const items   = document.querySelectorAll('.cap-item');
  const prevImg = document.getElementById('prevImg');

  items.forEach((item, idx) => {
    item.addEventListener('click', () => {
      items.forEach(i => i.classList.remove('active'));
      item.classList.add('active');

      const d = item.dataset;
      prevImg.src = '<?php echo e(asset('images/captura1.jpg')); ?>';
      document.getElementById('pifecha').textContent   = d.fecha + ' ' + d.hora;
      document.getElementById('pidesc').textContent    = d.nombre;
      document.getElementById('piestudio').textContent = d.estudio;
      document.getElementById('pitipo').textContent    = d.tipoEstudio;
      document.getElementById('piimagen').textContent  = (idx + 1) + ' de ' + items.length;
    });
  });

  /* Activar primera captura por defecto */
  if (items.length) items[0].click();

  /* Buscador */
  const searchInput = document.getElementById('capSearch');
  searchInput.addEventListener('input', () => {
    const q = searchInput.value.toLowerCase().trim();
    let visible = 0;
    items.forEach(item => {
      const match = item.dataset.nombre.toLowerCase().includes(q);
      item.style.display = match ? '' : 'none';
      if (match) visible++;
    });
    document.getElementById('capFooter').textContent =
      `Mostrando ${visible} de ${items.length}`;
  });

  /* Botón agregar capturas → input file */
  document.getElementById('btnAgregarCap').addEventListener('click', () => {
    document.getElementById('capInput').click();
  });

  document.getElementById('capInput').addEventListener('change', function () {
    const files = Array.from(this.files);
    if (!files.length) return;
    alert(`${files.length} captura(s) agregada(s) correctamente.`);
  });

  /* Funciones de acciones */
  function getCurrentId() {
    const active = document.querySelector('.cap-item.active');
    return active ? parseInt(active.dataset.id) : null;
  }

  document.getElementById('btnEditar').addEventListener('click', () => {
    const id = getCurrentId();
    if (!id) return;
    const active = document.querySelector('.cap-item.active');
    const nuevo = prompt('Editar descripcion:', active.dataset.nombre);
    if (nuevo && nuevo.trim()) {
      active.dataset.nombre = nuevo.trim();
      active.querySelector('.cap-nombre').textContent = nuevo.trim();
      document.getElementById('pidesc').textContent = nuevo.trim();
    }
  });

  document.getElementById('btnExportar').addEventListener('click', () => {
    const src = prevImg.src;
    const a = document.createElement('a');
    a.href = src;
    a.download = 'captura_' + getCurrentId() + '.jpg';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  });

  document.getElementById('btnImprimir').addEventListener('click', () => {
    const w = window.open('', '_blank');
    if (!w) return;
    w.document.write(`<img src="${prevImg.src}" style="width:100%;max-width:600px;display:block;margin:auto;">`);
    w.document.close();
    w.focus();
    w.print();
  });

  document.getElementById('btnEliminar').addEventListener('click', () => {
    const id = getCurrentId();
    if (!id) return;
    if (!confirm('¿Eliminar esta captura?')) return;
    const active = document.querySelector('.cap-item.active');
    active.remove();
    const remaining = document.querySelectorAll('.cap-item');
    if (remaining.length) {
      remaining[0].click();
    } else {
      document.getElementById('previewCard').style.display = 'none';
    }
    document.getElementById('capFooter').textContent = `Mostrando ${remaining.length} de ${remaining.length}`;
  });
})();
</script>
<?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\caputras\capturas-js.blade.php ENDPATH**/ ?>