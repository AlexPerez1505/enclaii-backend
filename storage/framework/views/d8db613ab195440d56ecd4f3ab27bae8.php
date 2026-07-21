<?php $__env->startSection('title', 'Nuevo Estudio'); ?>
<?php $__env->startSection('active', 'nuevo-estudio'); ?>
<?php $__env->startSection('header-title', 'Nuevo Estudio'); ?>
<?php $__env->startSection('header-sub'); ?>
  Datos nuevos
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<?php echo $__env->make('estudios.dashboard.dashboard-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

  
  <?php
  $pacientes = [
    ['ini'=>'MG','nombre'=>'María González','meta'=>'45 años · Femenino','folio'=>'00045','nac'=>'16/04/1979','fecha'=>'22 Mayo 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'completado'],
    ['ini'=>'JL','nombre'=>'Jorge López','meta'=>'38 años · Masculino','folio'=>'00046','nac'=>'05/11/1985','fecha'=>'18 Mayo 2024','tipo'=>'Colonoscopia','estado'=>'espera'],
    ['ini'=>'AR','nombre'=>'Ana Ramírez','meta'=>'52 años · Femenino','folio'=>'00047','nac'=>'30/07/1971','fecha'=>'10 Mayo 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'completado'],
    ['ini'=>'PT','nombre'=>'Pedro Torres','meta'=>'61 años · Masculino','folio'=>'00048','nac'=>'12/03/1963','fecha'=>'05 Mayo 2024','tipo'=>'Gastroscopia','estado'=>'completado'],
    ['ini'=>'LM','nombre'=>'Lucía Mendoza','meta'=>'29 años · Femenino','folio'=>'00049','nac'=>'22/09/1994','fecha'=>'28 Abr 2024','tipo'=>'Sigmoidoscopia','estado'=>'cancelado'],
    ['ini'=>'CR','nombre'=>'Carlos Reyes','meta'=>'47 años · Masculino','folio'=>'00050','nac'=>'14/06/1976','fecha'=>'20 Abr 2024','tipo'=>'CPRE','estado'=>'completado'],
    ['ini'=>'SO','nombre'=>'Sofía Ortega','meta'=>'33 años · Femenino','folio'=>'00051','nac'=>'08/01/1991','fecha'=>'15 Abr 2024','tipo'=>'Colonoscopia','estado'=>'espera'],
    ['ini'=>'RM','nombre'=>'Roberto Morales','meta'=>'55 años · Masculino','folio'=>'00052','nac'=>'27/12/1968','fecha'=>'08 Abr 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'completado'],
    ['ini'=>'VH','nombre'=>'Valentina Herrera','meta'=>'41 años · Femenino','folio'=>'00053','nac'=>'19/05/1982','fecha'=>'01 Abr 2024','tipo'=>'Gastroscopia','estado'=>'completado'],
    ['ini'=>'DC','nombre'=>'Diego Castillo','meta'=>'36 años · Masculino','folio'=>'00054','nac'=>'03/08/1987','fecha'=>'25 Mar 2024','tipo'=>'Colonoscopia','estado'=>'completado'],
    ['ini'=>'IF','nombre'=>'Isabella Flores','meta'=>'27 años · Femenino','folio'=>'00055','nac'=>'11/02/1997','fecha'=>'20 Mar 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'espera'],
    ['ini'=>'RA','nombre'=>'Rodrigo Aguilar','meta'=>'58 años · Masculino','folio'=>'00056','nac'=>'29/10/1965','fecha'=>'14 Mar 2024','tipo'=>'CPRE','estado'=>'completado'],
    ['ini'=>'NV','nombre'=>'Natalia Vargas','meta'=>'44 años · Femenino','folio'=>'00057','nac'=>'07/06/1979','fecha'=>'08 Mar 2024','tipo'=>'Gastroscopia','estado'=>'cancelado'],
    ['ini'=>'EM','nombre'=>'Eduardo Medina','meta'=>'50 años · Masculino','folio'=>'00058','nac'=>'25/01/1974','fecha'=>'01 Mar 2024','tipo'=>'Sigmoidoscopia','estado'=>'completado'],
    ['ini'=>'CP','nombre'=>'Camila Pedraza','meta'=>'31 años · Femenino','folio'=>'00059','nac'=>'18/07/1992','fecha'=>'22 Feb 2024','tipo'=>'Colonoscopia','estado'=>'completado'],
    ['ini'=>'HS','nombre'=>'Héctor Salinas','meta'=>'63 años · Masculino','folio'=>'00060','nac'=>'04/12/1960','fecha'=>'15 Feb 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'espera'],
    ['ini'=>'MR','nombre'=>'Mariana Ríos','meta'=>'39 años · Femenino','folio'=>'00061','nac'=>'21/04/1984','fecha'=>'10 Feb 2024','tipo'=>'Gastroscopia','estado'=>'completado'],
    ['ini'=>'FN','nombre'=>'Fernando Navarro','meta'=>'54 años · Masculino','folio'=>'00062','nac'=>'09/09/1969','fecha'=>'05 Feb 2024','tipo'=>'CPRE','estado'=>'completado'],
    ['ini'=>'GE','nombre'=>'Gabriela Espinoza','meta'=>'48 años · Femenino','folio'=>'00063','nac'=>'13/03/1976','fecha'=>'28 Ene 2024','tipo'=>'Colonoscopia','estado'=>'cancelado'],
    ['ini'=>'AJ','nombre'=>'Alejandro Jiménez','meta'=>'42 años · Masculino','folio'=>'00064','nac'=>'30/11/1981','fecha'=>'20 Ene 2024','tipo'=>'Endoscopia diagnóstica','estado'=>'completado'],
  ];
  ?>

  

  
  <div class="crear-toolbar rise d1" style="justify-content:flex-end">
    <a class="btn-back" href="<?php echo e(route('dashboard')); ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Volver
    </a>
  </div>

  
  <div class="paciente-busqueda-wrapper" id="pacienteBusquedaWrapper">
    <div class="paciente-busqueda-controls">
      <div class="paciente-search-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="pacienteSearchInput" placeholder="Buscar paciente por nombre..." autocomplete="off">
      </div>
      <div class="filtrar-dropdown-wrapper">
        <button class="btn-filtrar" id="btnFiltrarPacientes">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Filtrar
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:4px"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="filtrar-menu" id="filtrarMenu">
          <div class="filtrar-grupo">
            <div class="filtrar-grupo-titulo">Fecha</div>
            <button class="filtrar-opcion" data-filtro="todos">Todos</button>
            <button class="filtrar-opcion" data-filtro="hoy">Hoy</button>
            <button class="filtrar-opcion" data-filtro="semana">Esta semana</button>
            <button class="filtrar-opcion" data-filtro="mes">Este mes</button>
          </div>
          <div class="filtrar-divider"></div>
          <div class="filtrar-grupo">
            <div class="filtrar-grupo-titulo">Estado</div>
            <button class="filtrar-opcion" data-filtro="espera">En espera</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="crear-layout">

    
    <form class="crear-card rise d2" method="POST" action="#" id="formCrear">
      <?php echo csrf_field(); ?>

      <h2 class="sec-title">Información del siguiente</h2>

      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" placeholder="María Fernanda López Ruiz" autocomplete="off">
        </div>
        <div class="field">
          <label for="identificacion">Identificación</label>
          <input type="text" id="identificacion" name="identificacion" placeholder="0256987450" autocomplete="off">
        </div>
      </div>

      <div class="fields-grid cols-4" style="margin-bottom:18px">
        <div class="field">
          <label for="fecha_nac">Fecha de nacimiento</label>
          <div class="input-icon">
            <input type="date" id="fecha_nac" name="fecha_nac">
            <span class="ico"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
          </div>
        </div>
        <div class="field">
          <label for="edad">Edad</label>
          <input type="text" id="edad" name="edad" placeholder="28 años" autocomplete="off">
        </div>
        <div class="field">
          <label for="peso">Peso</label>
          <input type="text" id="peso" name="peso" placeholder="30 kg" autocomplete="off">
        </div>
        <div class="field">
          <label for="altura">Altura</label>
          <input type="text" id="altura" name="altura" placeholder="1.75 m" autocomplete="off">
        </div>
      </div>

      <div class="fields-grid cols-3" style="margin-bottom:18px">
        <div class="field">
          <label for="sexo">Sexo</label>
          <select id="sexo" name="sexo">
            <option value="" disabled selected>Elegir</option>
            <option value="F">Femenino</option>
            <option value="M">Masculino</option>
          </select>
        </div>
        <div class="field">
          <label for="nss">N.S.S</label>
          <input type="text" id="nss" name="nss" placeholder="25849563-9" autocomplete="off">
        </div>
        <div class="field">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" placeholder="CALLE, CP" autocomplete="off">
        </div>
      </div>

      <div class="fields-grid cols-2" style="margin-bottom:0">
        <div class="field">
          <label for="telefono">Teléfono</label>
          <input type="tel" id="telefono" name="telefono" placeholder="722 162 0815" autocomplete="off">
        </div>
        <div class="field">
          <label for="email">e-mail</label>
          <input type="email" id="email" name="email" placeholder="@gmail.com" autocomplete="off">
        </div>
      </div>

      <hr class="sec-divider">

      <h2 class="sec-title">Información médica</h2>

      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="procedimiento">Procedimiento</label>
          <select id="procedimiento" name="procedimiento">
            <option value="" disabled selected>Seleccione</option>
            <option value="endoscopia">Endoscopia diagnóstica</option>
            <option value="colonoscopia">Colonoscopia</option>
            <option value="gastroscopia">Gastroscopia</option>
            <option value="sigmoidoscopia">Sigmoidoscopia</option>
            <option value="cpre">CPRE</option>
            <option value="ecoendoscopia">Ecoendoscopia</option>
          </select>
        </div>
        <div class="field">
          <label for="fecha_hora">Fecha y hora</label>
          <div class="input-icon">
            <input type="datetime-local" id="fecha_hora" name="fecha_hora">
            <span class="ico"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
          </div>
        </div>
      </div>

      <div class="fields-grid cols-2" style="margin-bottom:18px">
        <div class="field">
          <label for="medico">Médico</label>
          <select id="medico" name="medico">
            <option value="" disabled selected>Seleccione</option>
            <option value="dr_victor" selected>Dr. Victor</option>
            <option value="dr_ricardo">Dr. Ricardo</option>
          </select>
        </div>
        <div class="field">
          <label for="referido">Referido por</label>
          <select id="referido" name="referido">
            <option value="" disabled selected>Seleccione</option>
            <option value="externo">Médico externo</option>
            <option value="propio">Médico propio</option>
            <option value="paciente">Paciente directo</option>
          </select>
        </div>
      </div>

      <div class="field" style="margin-bottom:0">
        <label for="diagnostico">Diagnostico Preliminar</label>
        <textarea id="diagnostico" name="diagnostico" placeholder="Define lo que podría tener"></textarea>
      </div>

      <div class="crear-footer">
        <button type="submit" class="btn-save">
          Guardar paciente
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
      </div>

    </form>

    
    <div class="side-panel rise d3">

      <div class="foto-box">
        <div class="foto-circle" id="fotoCircle" onclick="document.getElementById('fotoInput').click()">
          <img id="fotoPreview" src="" alt="Foto del paciente">
          <div class="foto-placeholder" id="fotoPlaceholder">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Foto del<br>paciente</span>
          </div>
        </div>
        <input type="file" id="fotoInput" accept="image/*" style="display:none">
        <input type="file" id="fotoCamera" accept="image/*" capture="environment" style="display:none">
        <div style="position:relative;width:100%">
          <button class="btn-add-foto" type="button" id="btnFotoMenu">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <span id="btnFotoTxt">Agregar foto</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:2px"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div id="fotoMenu" style="display:none;position:absolute;bottom:calc(100% + 6px);left:50%;transform:translateX(-50%);background:var(--panel);border:1px solid var(--stroke-strong);border-radius:var(--r-md);overflow:hidden;min-width:170px;z-index:50;box-shadow:0 8px 24px rgba(0,0,0,.35)">
            <button type="button" id="btnGaleria" style="display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:none;border:none;border-bottom:1px solid var(--stroke);font:inherit;font-size:13.5px;font-weight:600;color:var(--txt);cursor:pointer;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
              Abrir galería
            </button>
            <button type="button" id="btnCamara" style="display:flex;align-items:center;gap:10px;width:100%;padding:11px 14px;background:none;border:none;font:inherit;font-size:13.5px;font-weight:600;color:var(--txt);cursor:pointer;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              Tomar foto
            </button>
          </div>
        </div>
      </div>

      <div class="action-btns">
        <a class="action-btn" href="<?php echo e(route('nuevo-estudio.grabando')); ?>">
          <span class="ab-icon" style="background:rgba(255,59,59,.12);border-color:rgba(255,90,110,.4)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#ff5a6e"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="#ff5a6e" stroke="none"/></svg>
          </span>
          Iniciar Grabación
        </a>
        <a class="action-btn" href="<?php echo e(route('nuevo-estudio.capturas')); ?>">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          </span>
          Agregar Capturas
        </a>
        <a class="action-btn" href="<?php echo e(route('nuevo-estudio.importar')); ?>">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          </span>
          Importar Imágenes
        </a>
        <a class="action-btn" href="<?php echo e(route('nuevo-estudio.configuracion')); ?>">
          <span class="ab-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87 1.7 1.7 0 0 0 1.55 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1z"/></svg>
          </span>
          Configuración de Grabacion
        </a>
      </div>

    </div>

  </div>

  
  <div id="capturasView" style="display:none">

    <div class="cap-header">
      <h1 class="cap-header-title">Capturas</h1>
      <button class="btn-volver-capturas" id="btnVolverCapturas">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </button>
    </div>

    
    <div id="capListaView">
      <div class="cap-view-layout">

        <div class="cap-card-list">
          <div class="cap-search-bar-v">
            <div class="cap-search-box-v">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              <input type="text" id="capSearchV" placeholder="Buscar Capturas...">
            </div>
            <div class="cap-sort-wrap">
              Ordenar por:
              <div class="cap-sort-select-wrap">
                <select class="cap-sort-select">
                  <option>Fecha (más reciente)</option>
                  <option>Fecha (más antigua)</option>
                  <option>Nombre</option>
                </select>
                <svg class="cap-sort-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </div>
          </div>

          <div class="cap-list-v" id="capListV">
            <?php
            $capturasV = [
              ['id'=>1,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva'],
              ['id'=>2,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva'],
              ['id'=>3,'nombre'=>'Lesion en esófago distal','fecha'=>'24/05/2026','hora'=>'10:30','tipo'=>'Imagen','estudio'=>'EST-2024-0587','tipo_estudio'=>'Endoscopia digestiva'],
            ];
            ?>

            <?php $__currentLoopData = $capturasV; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="cap-item-v"
              data-id="<?php echo e($cv['id']); ?>"
              data-nombre="<?php echo e($cv['nombre']); ?>"
              data-fecha="<?php echo e($cv['fecha']); ?>"
              data-hora="<?php echo e($cv['hora']); ?>"
              data-estudio="<?php echo e($cv['estudio']); ?>"
              data-tipo-estudio="<?php echo e($cv['tipo_estudio']); ?>">
              <div class="cap-thumb-v">
                <img src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="captura">
                <div class="cap-thumb-expand" title="Ver imagen">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
                </div>
              </div>
              <div class="cap-info-v">
                <div class="cap-nombre-v"><?php echo e($cv['nombre']); ?></div>
                <div class="cap-date-v">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  <?php echo e($cv['fecha']); ?> <?php echo e($cv['hora']); ?>

                </div>
                <div class="cap-tipo-v"><?php echo e($cv['tipo']); ?></div>
              </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>

          <div class="cap-footer-v" id="capFooterV">Mostrando 3 de 3</div>
        </div>

        <div class="cap-preview-panel" id="capPreviewPanel">
          <div class="cap-prev-title">Vista Previa</div>
          <div class="cap-prev-img-box">
            <img id="capPrevImg" src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="Vista previa">
          </div>
          <div>
            <div class="cap-info-sec-title">Información de la captura</div>
            <div class="cap-info-row-v"><span class="lbl">Fecha y hora</span>    <span class="val" id="capPiFecha">—</span></div>
            <div class="cap-info-row-v"><span class="lbl">Descripción</span>     <span class="val" id="capPiDesc">—</span></div>
            <div class="cap-info-row-v"><span class="lbl">Estudio</span>         <span class="val" id="capPiEstudio">—</span></div>
            <div class="cap-info-row-v"><span class="lbl">Tipo de estudio</span> <span class="val" id="capPiTipo">—</span></div>
            <div class="cap-info-row-v"><span class="lbl">Imagen</span>          <span class="val" id="capPiImagen">—</span></div>
          </div>
          <div>
            <div class="cap-accs-title">Acciones</div>
            <div class="cap-accs-grid">
              <button class="cap-acc-btn" id="btnAbrirEditor">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
              </button>
              <button class="cap-acc-btn" id="btnExportarCaptura">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exportar
              </button>
              <button class="cap-acc-btn" id="btnImprimirCaptura">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Imprimir
              </button>
              <button class="cap-acc-btn danger">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                Eliminar
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>

    
    <div id="capEditorView" style="display:none">
      <div style="margin-bottom:16px">
        <h2 style="font-family:'Sora',sans-serif;font-size:18px;font-weight:700;margin:0">Editor de Captura</h2>
      </div>
      <div class="cap-layout-wrapper">
        <div class="cap-toolbar-left">
          <button class="cap-tool-btn active" title="Seleccionar">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><polyline points="8 12 11 15 16 9"/></svg>
          </button>
          <button class="cap-tool-btn" title="Eliminar">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h18M5 6v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
          </button>
          <button class="cap-tool-btn" title="Imprimir">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="12" width="16" height="8" rx="2"/><rect x="6" y="4" width="12" height="8" rx="2"/></svg>
          </button>
          <button class="cap-tool-btn" title="Exportar PDF">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          </button>
          <button class="cap-tool-btn" title="Guardar">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          </button>
          <button class="cap-tool-btn" title="Adelante">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><polyline points="10 8 14 12 10 16"/></svg>
          </button>
          <button class="cap-tool-btn" title="Atrás">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><polyline points="14 8 10 12 14 16"/></svg>
          </button>
          <button class="cap-tool-btn" title="Cerrar editor" id="btnCerrarEditor" style="margin-top:auto">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>

        <div class="cap-fotos-grid" id="capFotosGrid">
          <?php
          $fotosCapturas = [
            ['num'=>1,'img'=>'captura1.jpg'],
            ['num'=>2,'img'=>'captura1.jpg'],
            ['num'=>3,'img'=>'captura1.jpg'],
          ];
          ?>

          <?php $__currentLoopData = $fotosCapturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="cap-foto-card" data-foto="<?php echo e($foto['num']); ?>">
            <div class="cap-foto-img">
              <img src="<?php echo e(asset('images/'.$foto['img'])); ?>" alt="Captura <?php echo e($foto['num']); ?>">
              <div class="cap-foto-overlay">
                <button class="cap-foto-action" title="Ver">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
                <button class="cap-foto-action" title="Ampliar">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
                <button class="cap-foto-action" title="Descargar">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </button>
              </div>
            </div>
            <div class="cap-foto-footer">
              <span class="cap-foto-num"><?php echo e($foto['num']); ?></span>
              <div class="cap-foto-actions">
                <button class="cap-foto-btn" title="Ajustes de color">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                </button>
                <button class="cap-foto-btn" title="Agregar a informe">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="4" width="16" height="20" rx="2"/><path d="M12 10v8"/><path d="M8 14h8"/></svg>
                </button>
                <button class="cap-foto-btn" title="Zoom">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                </button>
                <button class="cap-foto-btn" title="Balance de color">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/></svg>
                </button>
              </div>
            </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>

    
    <button class="print-exit-fullscreen" id="printExitFs">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="8 3 3 3 3 8"/><polyline points="21 8 21 3 16 3"/><polyline points="3 16 3 21 8 21"/><polyline points="16 21 21 21 21 16"/></svg>
      Salir de pantalla completa
    </button>

    
    <div class="print-panel" id="printPanel">
      <div class="print-preview-area">
        <div class="print-preview-toolbar">
          <div class="print-nav">
            <button class="print-nav-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
            <span class="print-page-label">Página 1 de 1</span>
            <button class="print-nav-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
          <div class="print-zoom">
            <button class="print-zoom-btn" id="printZoomIn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
            <span id="printZoomVal">100%</span>
            <button class="print-zoom-btn" id="printZoomOut"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
          </div>
          <button class="print-fit-btn" id="printFitBtn" title="Ajustar a pantalla">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
          </button>
        </div>
        <div class="print-sheet-wrap">
          <div class="print-sheet" id="printSheet">
            <div class="print-sheet-header">
              <div class="print-sheet-paciente">Maria Gonzalez</div>
              <div class="print-sheet-meta"><span>Fecha de estudio</span><span>05/mayo/2026</span></div>
            </div>
            <div class="print-img-row">
              <div class="print-img-thumb"><img src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="Imagen 1"></div>
              <div class="print-img-info">
                <div class="print-img-title">Imagen 1</div>
                <div class="print-img-desc-label">Descripción:</div>
                <div class="print-img-desc">Lesión observada en estudio</div>
                <div class="print-img-date">Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="print-img-row">
              <div class="print-img-thumb"><img src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="Imagen 2"></div>
              <div class="print-img-info">
                <div class="print-img-title">Imagen 2</div>
                <div class="print-img-desc-label">Descripción:</div>
                <div class="print-img-desc">Lesión observada en estudio</div>
                <div class="print-img-date">Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="print-img-row">
              <div class="print-img-thumb"><img src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="Imagen 3"></div>
              <div class="print-img-info">
                <div class="print-img-title">Imagen 3</div>
                <div class="print-img-desc-label">Descripción:</div>
                <div class="print-img-desc">Lesión observada en estudio</div>
                <div class="print-img-date">Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="print-sheet-footer">Página 1 de 1</div>
          </div>
        </div>
      </div>
      <div class="print-config-sidebar">
        <div class="print-config-title">Configuración de Impresión</div>
        <div class="print-config-group">
          <div class="print-config-label">Impresora</div>
          <div class="print-select-wrap">
            <svg class="print-select-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            <select class="print-config-select">
              <option>HP Laser Jet Pro M404dn</option>
              <option>PDF (Guardar como PDF)</option>
              <option>Microsoft Print to PDF</option>
            </select>
            <svg class="print-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>
        <div class="print-config-group">
          <div class="print-config-label">Copias</div>
          <div class="print-copies-row">
            <button class="print-copies-btn" id="printCopiesDown">−</button>
            <span class="print-copies-val" id="printCopiesVal">1</span>
            <button class="print-copies-btn" id="printCopiesUp">+</button>
          </div>
        </div>
        <div class="print-config-group">
          <div class="print-config-label">Orientación</div>
          <div class="print-orient-row">
            <button class="print-orient-btn active" id="printOrientV">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/></svg> Vertical
            </button>
            <button class="print-orient-btn" id="printOrientH">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/></svg> Horizontal
            </button>
          </div>
        </div>
        <div class="print-config-group">
          <div class="print-config-label">Tamaño del Papel</div>
          <div class="print-select-wrap">
            <select class="print-config-select" style="padding-left:12px">
              <option>A4 (210 x 297 mm)</option>
              <option>Carta (216 x 279 mm)</option>
              <option>Legal (216 x 356 mm)</option>
            </select>
            <svg class="print-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>
        <div class="print-config-group">
          <div class="print-config-label">Escala</div>
          <div class="print-select-wrap">
            <select class="print-config-select" style="padding-left:12px">
              <option>Ajustar a Página</option><option>100%</option><option>75%</option><option>50%</option>
            </select>
            <svg class="print-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>
        <div class="print-config-group">
          <div class="print-config-label">Incluir en la impresión</div>
          <label class="print-check-row"><input type="checkbox" checked> Encabezado con datos del paciente</label>
          <label class="print-check-row"><input type="checkbox" checked> Fecha del estudio</label>
          <label class="print-check-row"><input type="checkbox" checked> Descripciones</label>
          <label class="print-check-row"><input type="checkbox" checked> Numero de Imágenes</label>
        </div>
        <div class="print-divider"></div>
        <div class="print-actions">
          <button class="print-cancel-btn" id="btnCancelarImpresion">Cancelar</button>
          <button class="print-confirm-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Imprimir
          </button>
        </div>
      </div>
    </div>

    
    <div class="export-panel" id="exportPanel">
      <div class="export-preview-area">
        <div class="export-preview-toolbar">
          <div class="print-nav">
            <button class="print-nav-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
            <span class="print-page-label">Página 1 de 1</span>
            <button class="print-nav-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
          <div class="print-zoom">
            <button class="print-zoom-btn" id="exportZoomIn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
            <span id="exportZoomVal">68%</span>
            <button class="print-zoom-btn" id="exportZoomOut"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
          </div>
        </div>
        <div class="export-sheet-wrap">
          <div class="export-sheet" id="exportSheet">
            <div class="export-sheet-header" id="exportSheetHeader">
              <div class="export-sheet-paciente">Maria Gonzalez</div>
              <div class="export-sheet-meta"><span>Fecha de estudio</span><span>05/mayo/2026</span></div>
            </div>
            <div class="export-img-row" data-export-row>
              <div class="export-img-thumb"><img src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="Imagen 1"></div>
              <div class="export-img-info">
                <div class="export-img-title"><span data-export-num>Imagen 1 — </span>Lesión del Esófago</div>
                <div class="export-img-desc-label" data-export-desc-label>Descripción:</div>
                <div class="export-img-desc" data-export-desc>Lesión observada en estudio</div>
                <div class="export-img-date" data-export-date>Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="export-img-row" data-export-row>
              <div class="export-img-thumb"><img src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="Imagen 2"></div>
              <div class="export-img-info">
                <div class="export-img-title"><span data-export-num>Imagen 2 — </span>Lesión del Esófago</div>
                <div class="export-img-desc-label" data-export-desc-label>Descripción:</div>
                <div class="export-img-desc" data-export-desc>Lesión observada en estudio</div>
                <div class="export-img-date" data-export-date>Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="export-img-row" data-export-row>
              <div class="export-img-thumb"><img src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="Imagen 3"></div>
              <div class="export-img-info">
                <div class="export-img-title"><span data-export-num>Imagen 3 — </span>Lesión del Esófago</div>
                <div class="export-img-desc-label" data-export-desc-label>Descripción:</div>
                <div class="export-img-desc" data-export-desc>Lesión observada en estudio</div>
                <div class="export-img-date" data-export-date>Fecha: 05/mayo/2026</div>
              </div>
            </div>
            <div class="export-sheet-footer" id="exportSheetFooter">Página 1 de 1</div>
          </div>
        </div>
      </div>
      <div class="export-config-sidebar">
        <div class="export-config-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Opciones del PDF
        </div>
        <div class="export-config-group">
          <div class="export-config-label">Incluir del PDF</div>
          <label class="export-check-row"><input type="checkbox" id="expChkHeader" checked> Encabezado con datos del paciente</label>
          <label class="export-check-row"><input type="checkbox" id="expChkFecha" checked> Fecha del estudio</label>
          <label class="export-check-row"><input type="checkbox" id="expChkDesc" checked> Descripciones</label>
          <label class="export-check-row"><input type="checkbox" id="expChkNum" checked> Numero de Imágenes</label>
        </div>
        <div class="export-config-group">
          <div class="export-config-label">Orientación</div>
          <div class="export-orient-row">
            <button class="export-orient-btn active" id="expOrientV">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/></svg> Vertical
            </button>
            <button class="export-orient-btn" id="expOrientH">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/></svg> Horizontal
            </button>
          </div>
        </div>
        <div class="export-config-group">
          <div class="export-config-label">Tamaño del Papel</div>
          <div class="export-select-wrap">
            <select class="export-config-select"><option>A4 (210 x 297 mm)</option><option>Carta (216 x 279 mm)</option><option>Legal (216 x 356 mm)</option></select>
            <svg class="export-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>
        <div class="export-config-group">
          <div class="export-config-label">Calidad</div>
          <div class="export-select-wrap">
            <select class="export-config-select"><option>Alta</option><option>Media</option><option>Baja</option></select>
            <svg class="export-select-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
          </div>
        </div>
        <div class="export-config-group">
          <div class="export-config-label">Protección (Opcional)</div>
          <label class="export-protect-row">
            <input type="checkbox" id="expChkProtect">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Proteger con contraseña
          </label>
        </div>
        <div class="export-divider"></div>
        <div class="export-actions">
          <button class="export-preview-btn" id="btnVistaPrevia">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            Vista Previa
          </button>
          <button class="export-download-btn" id="btnDescargarPDF">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Descargar PDF
          </button>
        </div>
      </div>
    </div>

    
    <div class="export-carousel-overlay" id="exportCarousel">
      <div class="export-carousel-header">
        <div class="export-carousel-title">Vista Previa de Imágenes</div>
        <button class="export-carousel-close" id="exportCarouselClose">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="export-carousel-stage">
        <button class="export-carousel-arrow" id="carouselPrev">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="export-carousel-img-wrap">
          <img id="carouselImg" src="<?php echo e(asset('images/captura1.jpg')); ?>" alt="Preview">
          <div class="carousel-img-info-overlay">
            <div class="carousel-info-name" id="carouselInfoName">Imagen 1</div>
            <div class="carousel-info-desc" id="carouselInfoDesc">Lesión observada en estudio</div>
            <div class="carousel-info-date" id="carouselInfoDate">Fecha: 05/mayo/2026</div>
          </div>
        </div>
        <button class="export-carousel-arrow" id="carouselNext">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
      <div class="export-carousel-footer">
        <div class="export-carousel-dots" id="carouselDots"></div>
        <span class="export-carousel-counter" id="carouselCounter">1 / 3</span>
        <button class="export-carousel-remove" id="carouselRemove">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
          Quitar imagen
        </button>
        <button class="export-carousel-add" id="carouselAdd">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Agregar imagen
        </button>
      </div>
    </div>

    
    <div class="print-modal-overlay" id="carouselRemoveOverlay" style="z-index:700">
      <div class="print-modal">
        <div class="print-modal-icon warning">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="print-modal-title">¿Desea quitar la captura?</div>
        <div class="print-modal-sub">Esta imagen será removida de la vista previa del PDF.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn secondary" id="carouselRemoveNo">No</button>
          <button class="print-modal-btn danger" id="carouselRemoveSi">Sí, quitar</button>
        </div>
      </div>
    </div>

    
    <div class="print-modal-overlay" id="carouselRemovedOverlay" style="z-index:700">
      <div class="print-modal">
        <div class="print-modal-icon success">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="print-modal-title">Captura eliminada</div>
        <div class="print-modal-sub">La imagen fue removida de la vista previa.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn primary" id="carouselRemovedOk">Aceptar</button>
        </div>
      </div>
    </div>

    
    <div class="print-modal-overlay" id="protectPasswordOverlay" style="z-index:700">
      <div class="print-modal" style="max-width:380px">
        <div class="print-modal-icon success">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2b7bf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <div class="print-modal-title">Verificar identidad</div>
        <div class="print-modal-sub">Ingresa tu contraseña de acceso para habilitar la protección del PDF.</div>
        <div style="width:100%;position:relative;margin-top:4px">
          <input type="password" id="protectPasswordInput" placeholder="Contraseña"
            style="width:100%;box-sizing:border-box;padding:11px 42px 11px 14px;border-radius:var(--r-md);border:1px solid var(--stroke-strong);background:var(--panel-2);color:var(--txt);font:inherit;font-size:14px;outline:none;">
          <button id="protectToggleVisibility" type="button"
            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--txt-soft);display:flex;align-items:center;">
            <svg id="protectEyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <div id="protectPasswordError" style="width:100%;font-size:12px;color:#f87171;display:none;text-align:left;margin-top:-4px">
          Contraseña incorrecta. Protección desactivada.
        </div>
        <div class="print-modal-actions" style="margin-top:4px">
          <button class="print-modal-btn secondary" id="protectPasswordCancel">Cancelar</button>
          <button class="print-modal-btn primary" id="protectPasswordConfirm">Confirmar</button>
        </div>
      </div>
    </div>

    
    <div class="print-modal-overlay" id="carouselNoCaptOverlay" style="z-index:700">
      <div class="print-modal">
        <div class="print-modal-icon warning">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="print-modal-title">No hay capturas disponibles</div>
        <div class="print-modal-sub">Todas las imágenes ya están incluidas en la vista previa.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn primary" id="carouselNoCaptOk">Aceptar</button>
        </div>
      </div>
    </div>

    
    <div class="print-modal-overlay" id="carouselAddOverlay" style="z-index:700">
      <div class="print-modal" style="max-width:480px">
        <div class="print-modal-title" style="width:100%;text-align:left;font-size:16px">Agregar imágenes</div>
        <div class="print-modal-sub" style="width:100%;text-align:left;margin-bottom:4px">Selecciona las imágenes que deseas agregar a la vista previa:</div>
        <div id="carouselAddList" style="width:100%;display:flex;flex-direction:column;gap:10px;max-height:260px;overflow-y:auto"></div>
        <div class="print-modal-actions" style="margin-top:8px">
          <button class="print-modal-btn secondary" id="carouselAddCancel">Cancelar</button>
          <button class="print-modal-btn primary" id="carouselAddConfirm">Agregar seleccionadas</button>
        </div>
      </div>
    </div>

    
    <div class="print-modal-overlay" id="pdfDownloadedOverlay">
      <div class="print-modal">
        <div class="print-modal-icon success">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </div>
        <div class="print-modal-title">¡PDF Descargado!</div>
        <div class="print-modal-sub">El archivo se ha guardado correctamente en tu dispositivo.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn primary" id="pdfDownloadedOk">Aceptar</button>
        </div>
      </div>
    </div>

    
    <div class="print-modal-overlay" id="printSuccessOverlay">
      <div class="print-modal">
        <div class="print-modal-icon success">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="print-modal-title">¡Listo!</div>
        <div class="print-modal-sub">La impresión se ha enviado correctamente.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn primary" id="printSuccessOk">Volver a Capturas</button>
        </div>
      </div>
    </div>

    
    <div class="print-modal-overlay" id="printCancelOverlay">
      <div class="print-modal">
        <div class="print-modal-icon warning">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="print-modal-title">¿Desea volver?</div>
        <div class="print-modal-sub">Si vuelve, perderá la configuración de impresión actual.</div>
        <div class="print-modal-actions">
          <button class="print-modal-btn secondary" id="printCancelNo">No</button>
          <button class="print-modal-btn danger" id="printCancelSi">Sí, volver</button>
        </div>
      </div>
    </div>

    
    <div class="cap-lightbox" id="capLightbox">
      <button class="cap-lightbox-close" id="capLightboxClose">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <img id="capLightboxImg" src="" alt="Vista ampliada">
    </div>

  </div>

  
  <div class="nota-modal-overlay" id="notaModalOverlay"></div>
  <div class="nota-modal" id="notaModal">
    <div class="nota-modal-header">
      <h3 class="nota-modal-title">Agregar Nueva Nota</h3>
    </div>
    <div class="nota-modal-body">
      <textarea class="nota-modal-textarea" id="notaTextarea" placeholder="Escribe tu nota aquí..."></textarea>
    </div>
    <div class="nota-modal-footer">
      <button class="btn-nota-volver" id="btnNotaVolver">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Volver
      </button>
      <button class="btn-nota-guardar" id="btnNotaGuardar">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Guardar Nota
      </button>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('estudios.dashboard.dashboard-js', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\enclaii-backend\resources\views\estudios\dashboard\index.blade.php ENDPATH**/ ?>