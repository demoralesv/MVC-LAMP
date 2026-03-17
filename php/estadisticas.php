<?php
require_once "functions.php";

$stats = json_decode(getStats($conn), true);

if (!is_array($stats)) {
    $stats = [];
}

$perPage = 5;
$totalUrls = count($stats);
$totalPages = max(1, ceil($totalUrls / $perPage));

$actualPage = isset($_GET["page"]) ? (int) $_GET["page"] : 1;

if ($actualPage < 1) {
    $actualPage = 1;
}

if ($actualPage > $totalPages) {
    $actualPage = $totalPages;
}

$startPage = ($actualPage - 1) * $perPage;
$pagedStats = array_slice($stats, $startPage, $perPage);

$selectedID = isset($_GET["id"]) ? (int) $_GET["id"] : null;
$selectedDetail = null;

if (!empty($stats) && $selectedID !== null) {
    foreach ($stats as $item) {
        if ((int) $item["id"] === $selectedID) {
            $selectedDetail = $item;
            break;
        }
    }
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Estadísticas de URLs</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="/estilos/styles.css">
</head>
<body>
  <div class="contenedorPrincipal">
    <img src="/favicon.png" alt="Logo acortador" class="logo">

    <h1 class="tituloPrincipal">Estadísticas</h1>

    <nav class="navegacionTabs">
      <a href="index.php" class="tabLink">Acortar</a>
      <a href="estadisticas.php" class="tabLink tabActiva">Estadísticas</a>
    </nav>

    <div class="estadisticasLayout">
      <div class="tarjetaFormulario panelUrls">
        <label class="etiquetaCampo">URLs guardadas</label>

        <div class="listaUrls">
          <?php if (!empty($pagedStats)): ?>
            <?php foreach ($pagedStats as $item): ?>
              <div class="campoUrl filaUrl">
                <span class="textoUrl">
                  <?= htmlspecialchars($item["urloriginal"]) ?>
                </span>

                <a
                  href="estadisticas.php?page=<?= $actualPage ?>&id=<?= urlencode($item["id"]) ?>"
                  class="botonVer"
                >
                  Ver
                </a>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="campoUrl detalleVacio">
              No hay URLs guardadas.
            </div>
          <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
          <div class="paginacion">
            <?php if ($actualPage > 1): ?>
              <a
                href="estadisticas.php?page=<?= $actualPage - 1 ?><?= $selectedID ? '&id=' . urlencode($selectedID) : '' ?>"
                class="paginaBoton"
              >
                Anterior
              </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a
                href="estadisticas.php?page=<?= $i ?><?= $selectedID ? '&id=' . urlencode($selectedID) : '' ?>"
                class="paginaBoton <?= $i === $actualPage ? 'paginaActiva' : '' ?>"
              >
                <?= $i ?>
              </a>
            <?php endfor; ?>

            <?php if ($actualPage < $totalPages): ?>
              <a
                href="estadisticas.php?page=<?= $actualPage + 1 ?><?= $selectedID ? '&id=' . urlencode($selectedID) : '' ?>"
                class="paginaBoton"
              >
                Siguiente
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="tarjetaFormulario panelDetalle">
        <label class="etiquetaCampo">Detalle</label>

        <?php if ($selectedDetail): ?>
          <div class="campoUrl detalleVacio detalleTexto">
            <div>
              <strong>URL:</strong>
              <?= htmlspecialchars($selectedDetail["urloriginal"]) ?>
            </div>

            <div>
              <strong>Países:</strong>
              <?php if (!empty($selectedDetail["countries"])): ?>
                <ul>
                  <?php foreach ($selectedDetail["countries"] as $country): ?>
                    <li>
                      <?= htmlspecialchars($country["name"]) ?>:
                      <?= htmlspecialchars($country["frequency"]) ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                Sin datos.
              <?php endif; ?>
            </div>

            <div>
              <strong>Fechas:</strong>
              <?php if (!empty($selectedDetail["dates"])): ?>
                <ul>
                  <?php foreach ($selectedDetail["dates"] as $date): ?>
                    <li>
                      <?= htmlspecialchars($date["date"]) ?>:
                      <?= htmlspecialchars($date["frequency"]) ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                Sin datos.
              <?php endif; ?>
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
