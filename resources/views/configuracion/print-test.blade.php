<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prueba de impresión — ENCLAII</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
  @php
    $isLandscape = $orientation === 'landscape';
    $paperWidth = $pageSize === 'a4' ? ($isLandscape ? '297mm' : '210mm') : ($isLandscape ? '11in' : '8.5in');
    $paperHeight = $pageSize === 'a4' ? ($isLandscape ? '210mm' : '297mm') : ($isLandscape ? '8.5in' : '11in');
    $paperLabel = $pageSize === 'a4' ? 'A4' : 'Carta';
    $orientationLabel = $isLandscape ? 'Horizontal' : 'Vertical';
  @endphp
  <style>
    *{box-sizing:border-box}
    html,body{margin:0;min-height:100%;font-family:'Hanken Grotesk',Arial,sans-serif;background:#dbe3ef;color:#172033}
    body{padding:76px 24px 35px}
    .print-toolbar{
      position:fixed;z-index:20;left:0;right:0;top:0;height:58px;
      display:flex;align-items:center;justify-content:space-between;gap:16px;padding:0 22px;
      color:#eaf2ff;background:#0d1738;border-bottom:1px solid rgba(255,255,255,.12);
      box-shadow:0 8px 24px rgba(13,23,56,.22)
    }
    .print-toolbar strong{font-family:'Sora',sans-serif;font-size:14px}
    .print-toolbar span{font-size:11px;color:#9fb0d1;margin-left:8px}
    .toolbar-actions{display:flex;gap:8px}
    .toolbar-btn{
      height:36px;padding:0 14px;border:1px solid rgba(138,169,229,.35);border-radius:9px;
      color:#d9e7ff;background:transparent;font:inherit;font-size:11.5px;font-weight:700;cursor:pointer
    }
    .toolbar-btn.primary{color:#fff;border-color:transparent;background:linear-gradient(135deg,#2e7bf6,#18aee5)}
    .sheet{
      position:relative;width:{{ $paperWidth }};min-height:{{ $paperHeight }};margin:0 auto;padding:.38in;
      background:#fff;color:#172033;box-shadow:0 24px 60px rgba(27,39,66,.24);overflow:hidden;
      {{ $useColor ? '' : 'filter:grayscale(1);' }}
    }
    .safe-area{position:absolute;inset:.25in;border:1px dashed #a9b6c9;pointer-events:none}
    .margin-label{position:absolute;top:.27in;right:.31in;padding:2px 5px;color:#7b8799;background:#fff;font-size:7px;letter-spacing:.04em}
    .watermark{
      position:absolute;left:50%;top:50%;z-index:0;transform:translate(-50%,-50%) rotate(-28deg);
      color:rgba(37,73,135,.065);font-family:'Sora',sans-serif;font-size:46px;font-weight:800;
      letter-spacing:.08em;white-space:nowrap;pointer-events:none
    }
    .content{position:relative;z-index:1}
    .doc-header{display:flex;align-items:center;gap:15px;padding-bottom:12px;border-bottom:2px solid #1f6fda}
    .doc-logo{width:70px;height:54px;display:grid;place-items:center;overflow:hidden}
    .doc-logo img{max-width:100%;max-height:100%;object-fit:contain}
    .doc-brand{flex:1}
    .doc-brand h1{margin:0;font-family:'Sora',sans-serif;font-size:18px;color:#154d9a}
    .doc-brand p{margin:3px 0 0;font-size:9.5px;color:#63708a}
    .doc-badge{padding:6px 10px;border:1px solid #f2b84b;border-radius:7px;color:#955e00;background:#fff8e8;font-size:8px;font-weight:700}
    .document-title{text-align:center;margin:13px 0 10px}
    .document-title h2{margin:0;font-family:'Sora',sans-serif;font-size:14px}
    .document-title p{margin:3px 0 0;color:#66738a;font-size:8.5px}
    .meta-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin-bottom:10px}
    .meta-item{padding:6px 7px;border:1px solid #d9e0ea;border-radius:6px;background:#f8fafd}
    .meta-item span{display:block;color:#758197;font-size:6.8px;text-transform:uppercase;letter-spacing:.05em}
    .meta-item strong{display:block;margin-top:2px;font-size:8.5px}
    .test-columns{display:grid;grid-template-columns:1.1fr .9fr;gap:10px}
    .test-card{border:1px solid #d6deea;border-radius:7px;padding:8px;background:rgba(255,255,255,.92)}
    .test-card h3{margin:0 0 7px;font-family:'Sora',sans-serif;font-size:9px;color:#234a85}
    .color-strip{display:grid;grid-template-columns:repeat(7,1fr);height:30px;border-radius:5px;overflow:hidden}
    .color-strip i:nth-child(1){background:#ef4444}.color-strip i:nth-child(2){background:#f59e0b}
    .color-strip i:nth-child(3){background:#facc15}.color-strip i:nth-child(4){background:#22c55e}
    .color-strip i:nth-child(5){background:#06b6d4}.color-strip i:nth-child(6){background:#3b82f6}
    .color-strip i:nth-child(7){background:#8b5cf6}
    .gray-strip{display:grid;grid-template-columns:repeat(10,1fr);height:20px;margin-top:6px;border:1px solid #c9d2df;border-radius:4px;overflow:hidden}
    .gray-strip i:nth-child(1){background:#000}.gray-strip i:nth-child(2){background:#1c1c1c}.gray-strip i:nth-child(3){background:#383838}
    .gray-strip i:nth-child(4){background:#555}.gray-strip i:nth-child(5){background:#717171}.gray-strip i:nth-child(6){background:#8d8d8d}
    .gray-strip i:nth-child(7){background:#aaa}.gray-strip i:nth-child(8){background:#c6c6c6}.gray-strip i:nth-child(9){background:#e2e2e2}
    .gray-strip i:nth-child(10){background:#fff}
    .type-samples{display:flex;flex-direction:column;gap:2px}
    .type-samples div:nth-child(1){font-size:15px;font-weight:700}.type-samples div:nth-child(2){font-size:12px;font-weight:600}
    .type-samples div:nth-child(3){font-size:10px}.type-samples div:nth-child(4){font-size:8px}.type-samples div:nth-child(5){font-size:6px}
    .alignment-grid{
      position:relative;height:64px;border:1px solid #b8c3d2;
      background-image:linear-gradient(#e4e9f0 1px,transparent 1px),linear-gradient(90deg,#e4e9f0 1px,transparent 1px);
      background-size:10px 10px
    }
    .alignment-grid::before,.alignment-grid::after{content:"";position:absolute;background:#e23f50}
    .alignment-grid::before{left:50%;top:0;bottom:0;width:1px}.alignment-grid::after{top:50%;left:0;right:0;height:1px}
    .alignment-dot{position:absolute;left:50%;top:50%;width:9px;height:9px;border:2px solid #e23f50;border-radius:50%;transform:translate(-50%,-50%);background:#fff}
    .ruler{display:flex;justify-content:space-between;margin-top:4px;color:#6f7b90;font-size:6px}
    .sample-report{margin-top:10px;border:1px solid #d6deea;border-radius:7px;padding:9px;background:rgba(255,255,255,.94)}
    .sample-report h3{margin:0 0 6px;font-family:'Sora',sans-serif;font-size:9px;color:#234a85}
    .sample-table{width:100%;border-collapse:collapse;font-size:7.5px}
    .sample-table th,.sample-table td{padding:5px;border:1px solid #d8e0eb;text-align:left}
    .sample-table th{width:20%;background:#eef4fc;color:#31527d}
    .sample-text{margin:7px 0 0;font-size:7.5px;line-height:1.45;color:#3f4b5e}
    .footer-row{display:flex;align-items:flex-end;justify-content:space-between;gap:18px;margin-top:12px}
    .signature{width:220px;text-align:center}
    .signature img{display:block;max-width:190px;max-height:44px;object-fit:contain;margin:0 auto 3px}
    .signature-line{padding-top:4px;border-top:1px solid #253047;font-size:8px;font-weight:700}
    .signature-line span{display:block;margin-top:2px;color:#6f7b90;font-size:6.5px;font-weight:400}
    .verification{font-size:6.5px;line-height:1.5;color:#738096;text-align:right}
    .verification strong{display:block;color:#334158;font-size:7px}
    .page-footer{margin-top:10px;padding-top:5px;border-top:1px solid #d7dee8;text-align:center;color:#7a8699;font-size:6.5px}
    @media(max-width:900px){
      body{padding-left:8px;padding-right:8px}
      .sheet{transform-origin:top center}
      .print-toolbar span{display:none}
    }
    @media print{
      @page{size:{{ $pageSize === 'a4' ? 'A4' : 'letter' }} {{ $orientation }};margin:0}
      html,body{width:auto;height:auto;background:#fff}
      body{padding:0}
      .print-toolbar{display:none!important}
      .sheet{width:{{ $paperWidth }};min-height:{{ $paperHeight }};margin:0;box-shadow:none;page-break-after:avoid}
    }
  </style>
</head>
<body>
  <header class="print-toolbar">
    <div>
      <strong>Vista previa de impresión</strong>
      <span>{{ $paperLabel }} · {{ $orientationLabel }} · {{ $useColor ? 'Color' : 'Escala de grises' }}</span>
    </div>
    <div class="toolbar-actions">
      <button class="toolbar-btn" type="button" onclick="window.close()">Cerrar</button>
      <button class="toolbar-btn" type="button" onclick="window.print()">Imprimir</button>
      <button class="toolbar-btn primary" type="button" onclick="downloadPrintTest()">Descargar PDF</button>
    </div>
  </header>

  <main class="sheet" id="printTestSheet">
    <div class="safe-area"></div>
    <span class="margin-label">Margen seguro</span>
    <div class="watermark">DOCUMENTO DE PRUEBA</div>

    <div class="content">
      @if($showHeader)
        <header class="doc-header">
          @if($showLogo)
            <div class="doc-logo"><img src="{{ asset('images/logo-dark.png') }}" alt="ENCLAII"></div>
          @endif
          <div class="doc-brand">
            <h1>ENCLAII — Centro de Endoscopia</h1>
            <p>Hoja de calibración para reportes médicos</p>
          </div>
          <div class="doc-badge">SIN VALIDEZ CLÍNICA</div>
        </header>
      @endif

      <section class="document-title">
        <h2>PRUEBA DE IMPRESIÓN Y CALIBRACIÓN</h2>
        <p>Este documento utiliza información ficticia y sirve únicamente para comprobar la salida de impresión.</p>
      </section>

      <section class="meta-grid">
        <div class="meta-item"><span>Usuario</span><strong>{{ $user->name }}</strong></div>
        <div class="meta-item"><span>Fecha y hora</span><strong>{{ now()->format('d/m/Y H:i') }}</strong></div>
        <div class="meta-item"><span>Papel</span><strong>{{ $paperLabel }}</strong></div>
        <div class="meta-item"><span>Orientación</span><strong>{{ $orientationLabel }}</strong></div>
      </section>

      <div class="test-columns">
        <section class="test-card">
          <h3>Reproducción de color</h3>
          <div class="color-strip"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
          <div class="gray-strip"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
        </section>

        <section class="test-card">
          <h3>Legibilidad de texto</h3>
          <div class="type-samples">
            <div>Texto de 15 px — Encabezado</div>
            <div>Texto de 12 px — Subtítulo</div>
            <div>Texto de 10 px — Contenido</div>
            <div>Texto de 8 px — Información secundaria</div>
            <div>Texto de 6 px — Nota mínima legible</div>
          </div>
        </section>

        <section class="test-card">
          <h3>Alineación y cuadrícula</h3>
          <div class="alignment-grid"><span class="alignment-dot"></span></div>
          <div class="ruler"><span>0 mm</span><span>25 mm</span><span>50 mm</span><span>75 mm</span><span>100 mm</span></div>
        </section>

        <section class="test-card">
          <h3>Elementos gráficos</h3>
          <svg width="100%" height="76" viewBox="0 0 240 76" aria-label="Formas de calibración">
            <circle cx="32" cy="38" r="24" fill="#dbeafe" stroke="#2563eb" stroke-width="2"/>
            <rect x="76" y="14" width="48" height="48" rx="5" fill="#dcfce7" stroke="#16a34a" stroke-width="2"/>
            <polygon points="170,12 199,62 141,62" fill="#fef3c7" stroke="#d97706" stroke-width="2"/>
            <path d="M214 18v40M204 28h20M204 48h20" stroke="#db2777" stroke-width="3" stroke-linecap="round"/>
          </svg>
        </section>
      </div>

      <section class="sample-report">
        <h3>Reporte clínico de ejemplo</h3>
        <table class="sample-table">
          <tr><th>Paciente</th><td>PACIENTE DE PRUEBA</td><th>Folio</th><td>TEST-000001</td></tr>
          <tr><th>Estudio</th><td>Procedimiento de demostración</td><th>Fecha</th><td>{{ now()->format('d/m/Y') }}</td></tr>
          <tr><th>Médico</th><td>{{ $user->name }}</td><th>Estado</th><td>Documento simulado</td></tr>
        </table>
        <p class="sample-text">
          Hallazgos de demostración: este texto permite comprobar la nitidez, el espaciado y la alineación del contenido.
          No contiene datos personales ni representa un diagnóstico médico real.
        </p>
      </section>

      <div class="footer-row">
        @if($showSignature)
          <div class="signature">
            @if($signatureData)
              <img src="{{ $signatureData }}" alt="Firma digital de prueba">
            @endif
            <div class="signature-line">
              {{ $user->name }}
              <span>{{ $signatureData ? 'Firma digital configurada' : 'Espacio reservado para firma digital' }}</span>
            </div>
          </div>
        @else
          <div></div>
        @endif
        <div class="verification">
          <strong>Comprobaciones recomendadas</strong>
          Márgenes completos · Colores uniformes · Texto legible<br>
          Cuadrícula centrada · Firma y logo sin recortes
        </div>
      </div>

      <footer class="page-footer">
        DOCUMENTO DE PRUEBA — SIN VALIDEZ CLÍNICA · Generado por ENCLAII
      </footer>
    </div>
  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    function downloadPrintTest() {
      if (typeof html2pdf === 'undefined') {
        alert('No se pudo cargar el generador PDF. Usa Imprimir y selecciona “Guardar como PDF”.');
        return;
      }

      const sheet = document.getElementById('printTestSheet');
      const options = {
        margin: 0,
        filename: 'Prueba_impresion_ENCLAII_{{ now()->format('Ymd_His') }}.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, backgroundColor: '#ffffff' },
        jsPDF: {
          unit: 'in',
          format: '{{ $pageSize === 'a4' ? 'a4' : 'letter' }}',
          orientation: '{{ $orientation }}'
        },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
      };

      html2pdf().set(options).from(sheet).save();
    }

    window.addEventListener('load', () => {
      const mode = @json($mode);
      if (mode === 'print') setTimeout(() => window.print(), 350);
      if (mode === 'pdf') setTimeout(downloadPrintTest, 500);
    });
  </script>
</body>
</html>
