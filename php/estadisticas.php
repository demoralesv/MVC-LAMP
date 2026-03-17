<?php
	include "functions.php";
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Acortador de URLs</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="/estilos/styles.css">
</head>
<body>
  <div class="contenedorPrincipal">
    <img class="logo" src="/favicon.png" alt="Logo acortador">
    <h1 class="tituloPrincipal">Estadísticas de URLs</h1>

    <nav class="navegacionTabs">
      <a href="index.php" class="tabLink">Acortar</a>
      <a href="estadisticas.php" class="tabLink tabActiva">Estadísticas</a>
    </nav>

    <div class="estadisticasLayout">
      <div class="tarjetaFormulario panelUrls">
        <label class="etiquetaCampo">URLs guardadas</label>

        <div class="listaUrls">
          <?php if (!empty($urls)): ?>
            <?php foreach ($urls as $url): ?>
              <div class="campoUrl filaUrl">
                <span class="textoUrl">
                  <?= htmlspecialchars($url["originalUrl"]) ?>
                </span>

                <a href="estadisticas.php?id=<?= urlencode($url["id"]) ?>" class="botonVer">
                  Ver
                </a>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="campoUrl detalleVacio">
              No hay URLs guardadas todavía.
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="tarjetaFormulario panelDetalle">
        <label class="etiquetaCampo">Detalle</label>

        <?php if ($urlSeleccionada): ?>
          <div class="campoUrl detalleVacio detalleTexto">
            <div>
              <strong>Original:</strong>
              <?= htmlspecialchars($urlSeleccionada["originalUrl"]) ?>
            </div>

            <div>
              <strong>Corta:</strong>
              <?= htmlspecialchars($urlSeleccionada["shortUrl"]) ?>
            </div>

            <div>
              <strong>Fecha:</strong>
              <?= htmlspecialchars($urlSeleccionada["createdAt"]) ?>
            </div>
          </div>
        <?php else: ?>
          <div class="campoUrl detalleVacio">
            Elegí una URL y presioná <strong>Ver</strong>.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
