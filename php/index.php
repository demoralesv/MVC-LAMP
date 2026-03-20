<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Acortador de URLs</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="/estilos/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    var currentPage = 1;
    var selectedId = "";
    var datesChartInstance = null;
    var countriesChartInstance = null;

    function isValidUrl(value) {
      try {
        var url = new URL(value);
        return url.protocol === "http:" || url.protocol === "https:";
      } catch (error) {
        return false;
      }
    }

    function showErrorMessage(text) {
      var message = document.getElementById("errorMessage");
      if (!message) {
        return;
      }

      message.textContent = text;
      message.classList.add("mostrar");
    }

    function hideErrorMessage() {
      var message = document.getElementById("errorMessage");
      if (!message) {
        return;
      }

      message.textContent = "";
      message.classList.remove("mostrar");
    }

    function showSuccessMessage(text) {
      var message = document.getElementById("successMessage");
      if (!message) {
        return;
      }

      message.innerHTML = "URL Acortada con éxito: ";

      var link = document.createElement("a");
      link.href = text;
      link.textContent = text;
      link.target = "_blank";
      link.rel = "noopener noreferrer";

      message.appendChild(link);
      message.classList.add("mostrar");
    }

    function hideSuccessMessage() {
      var message = document.getElementById("successMessage");
      if (!message) {
        return;
      }

      message.textContent = "";
      message.classList.remove("mostrar");
    }

    function sendData() {
      var urlInput = document.getElementById("urlInput");

      if (!urlInput) {
        return;
      }

      var urlValue = urlInput.value.trim();

      hideErrorMessage();
      hideSuccessMessage();
      urlInput.classList.remove("campoInvalido");

      if (urlValue === "") {
        showErrorMessage("Por favor ingresa una URL.");
        urlInput.classList.add("campoInvalido");
        urlInput.focus();
        return;
      }

      if (!isValidUrl(urlValue)) {
        showErrorMessage("Ingresa una URL válida que empiece con http:// o https://");
        urlInput.classList.add("campoInvalido");
        urlInput.focus();
        return;
      }

      var requestData = {
        url: urlValue
      };

      var xhr = new XMLHttpRequest();
      var requestJson = JSON.stringify(requestData);

      xhr.onreadystatechange = function () {
        if (this.readyState !== 4) {
          return;
        }

        if (this.status === 200) {
          hideErrorMessage();
          showSuccessMessage(this.responseText);
        } else {
          showErrorMessage("Ocurrió un error al procesar la solicitud.");
        }
      };

      xhr.open("POST", "urlcontroller.php", true);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.send(requestJson);
    }

    function destroyCharts() {
      if (datesChartInstance) {
        datesChartInstance.destroy();
        datesChartInstance = null;
      }

      if (countriesChartInstance) {
        countriesChartInstance.destroy();
        countriesChartInstance = null;
      }
    }

    function loadSection(event, sectionName) {
      if (event) {
        event.preventDefault();
      }

      destroyCharts();

      var sectionUrl = "";

      if (sectionName === "shortener") {
        sectionUrl = "sections/shortener.php";
        document.getElementById("pageTitle").textContent = "Acortador de URLs";
      }

      if (sectionName === "stats") {
        sectionUrl = "sections/stats.php";
        document.getElementById("pageTitle").textContent = "Estadísticas de URLs";
      }

      var xhr = new XMLHttpRequest();

      xhr.onreadystatechange = function () {
        if (this.readyState !== 4) {
          return;
        }

        if (this.status === 200) {
          document.getElementById("sectionContent").innerHTML = this.responseText;
          updateActiveTab(sectionName);

          if (sectionName === "stats") {
            initStatsSection();
          }
        } else {
          document.getElementById("sectionContent").innerHTML =
            '<div class="tarjetaFormulario"><div class="campoUrl detalleVacio">Error al cargar la sección.</div></div>';
        }
      };

      xhr.open("GET", sectionUrl, true);
      xhr.send();
    }

    function updateActiveTab(sectionName) {
      var shortenerTab = document.getElementById("shortenerTab");
      var statsTab = document.getElementById("statsTab");

      shortenerTab.classList.remove("tabActiva");
      statsTab.classList.remove("tabActiva");

      if (sectionName === "shortener") {
        shortenerTab.classList.add("tabActiva");
      }

      if (sectionName === "stats") {
        statsTab.classList.add("tabActiva");
      }
    }

    function initStatsSection() {
      currentPage = 1;
      selectedId = "";
      loadStats(1, "");
    }

    function escapeHtml(text) {
      if (text === null || text === undefined) {
        return "";
      }

      return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }

    function loadStats(page, id) {
      if (!page) {
        page = 1;
      }

      if (!id) {
        id = "";
      }

      var xhr = new XMLHttpRequest();

      xhr.onreadystatechange = function () {
        if (this.readyState !== 4) {
          return;
        }

        if (this.status === 200) {
          var data = JSON.parse(this.responseText);

          currentPage = data.currentPage;
          selectedId = data.selectedId ? data.selectedId : "";

          renderUrlList(data);
          renderPagination(data);
          renderDetail(data);
          renderCharts(data);
        } else {
          var urlList = document.getElementById("urlList");
          if (urlList) {
            urlList.innerHTML = '<div class="campoUrl detalleVacio">Error al cargar las estadísticas.</div>';
          }
        }
      };

      xhr.open(
        "GET",
        "estadisticas_data.php?page=" + encodeURIComponent(page) + "&id=" + encodeURIComponent(id),
        true
      );
      xhr.send();
    }

    function renderUrlList(data) {
      var urlList = document.getElementById("urlList");

      if (!urlList) {
        return;
      }

      if (!data.pagedStats || data.pagedStats.length === 0) {
        urlList.innerHTML = '<div class="campoUrl detalleVacio">No hay URLs guardadas.</div>';
        return;
      }

      var html = "";

      for (var i = 0; i < data.pagedStats.length; i++) {
        var item = data.pagedStats[i];

        html += '<div class="campoUrl filaUrl">';
        html += '<span class="textoUrl">' + escapeHtml(item.urloriginal) + '</span>';
        html += '<button type="button" class="botonVer" onclick="viewDetail(' + item.id + ')">Ver</button>';
        html += '</div>';
      }

      urlList.innerHTML = html;
    }

    function renderPagination(data) {
      var pagination = document.getElementById("pagination");

      if (!pagination) {
        return;
      }

      if (!data.totalPages || data.totalPages <= 1) {
        pagination.innerHTML = "";
        return;
      }

      var html = "";

      if (data.currentPage > 1) {
        html += '<button type="button" class="paginaBoton" onclick="changePage(' + (data.currentPage - 1) + ')">Anterior</button>';
      }

      for (var i = 1; i <= data.totalPages; i++) {
        if (i === data.currentPage) {
          html += '<button type="button" class="paginaBoton paginaActiva" onclick="changePage(' + i + ')">' + i + '</button>';
        } else {
          html += '<button type="button" class="paginaBoton" onclick="changePage(' + i + ')">' + i + '</button>';
        }
      }

      if (data.currentPage < data.totalPages) {
        html += '<button type="button" class="paginaBoton" onclick="changePage(' + (data.currentPage + 1) + ')">Siguiente</button>';
      }

      pagination.innerHTML = html;
    }

    function renderDetail(data) {
      var emptyDetail = document.getElementById("emptyDetail");
      var detailContent = document.getElementById("detailContent");

      if (!emptyDetail || !detailContent) {
        return;
      }

      var originalUrlLink = document.getElementById("originalUrlLink");
      var shortUrlLink = document.getElementById("shortUrlLink");
      var createdAtText = document.getElementById("createdAtText");
      var countryList = document.getElementById("countryList");
      var dateList = document.getElementById("dateList");

      var detail = data.selectedDetail;

      if (!detail) {
        emptyDetail.style.display = "block";
        detailContent.style.display = "none";

        originalUrlLink.textContent = "";
        originalUrlLink.href = "#";
        shortUrlLink.textContent = "";
        shortUrlLink.href = "#";
        createdAtText.textContent = "";
        countryList.innerHTML = "Sin datos.";
        dateList.innerHTML = "Sin datos.";
        return;
      }

      emptyDetail.style.display = "none";
      detailContent.style.display = "block";

      originalUrlLink.href = detail.urloriginal;
      originalUrlLink.textContent = detail.urloriginal;

      shortUrlLink.href = detail.shortUrl;
      shortUrlLink.textContent = detail.shortUrl;

      createdAtText.textContent = detail.createdAt;

      if (detail.countries && detail.countries.length > 0) {
        var countriesHtml = "<ul>";

        for (var i = 0; i < detail.countries.length; i++) {
          countriesHtml += "<li>" +
            escapeHtml(detail.countries[i].name) +
            ": " +
            escapeHtml(detail.countries[i].frequency) +
            "</li>";
        }

        countriesHtml += "</ul>";
        countryList.innerHTML = countriesHtml;
      } else {
        countryList.innerHTML = "Sin datos.";
      }

      if (detail.dates && detail.dates.length > 0) {
        var datesHtml = "<ul>";

        for (var j = 0; j < detail.dates.length; j++) {
          datesHtml += "<li>" +
            escapeHtml(detail.dates[j].date) +
            ": " +
            escapeHtml(detail.dates[j].frequency) +
            "</li>";
        }

        datesHtml += "</ul>";
        dateList.innerHTML = datesHtml;
      } else {
        dateList.innerHTML = "Sin datos.";
      }
    }

    function renderCharts(data) {
      destroyCharts();

      var chartsSection = document.getElementById("chartsSection");
      var datesChartBlock = document.getElementById("datesChartBlock");
      var countriesChartBlock = document.getElementById("countriesChartBlock");

      if (!chartsSection || !datesChartBlock || !countriesChartBlock) {
        return;
      }

      var charts = data.charts || {};
      var dateLabels = charts.dateLabels || [];
      var dateFrequencies = charts.dateFrequencies || [];
      var countryLabels = charts.countryLabels || [];
      var countryFrequencies = charts.countryFrequencies || [];

      if (!data.selectedDetail) {
        chartsSection.style.display = "none";
        datesChartBlock.style.display = "none";
        countriesChartBlock.style.display = "none";
        return;
      }

      var hasDateChart = dateLabels.length > 0;
      var hasCountryChart = countryLabels.length > 0;

      if (!hasDateChart && !hasCountryChart) {
        chartsSection.style.display = "none";
        datesChartBlock.style.display = "none";
        countriesChartBlock.style.display = "none";
        return;
      }

      chartsSection.style.display = "block";

      if (hasDateChart) {
        datesChartBlock.style.display = "block";

        var datesChartCanvas = document.getElementById("datesChart");

        datesChartInstance = new Chart(datesChartCanvas, {
          type: "bar",
          data: {
            labels: dateLabels,
            datasets: [{
              label: "Clicks por fecha",
              data: dateFrequencies,
              borderWidth: 1,
              backgroundColor: "#4a776682",
              borderColor: "#4a7766"
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                }
              }
            }
          }
        });
      } else {
        datesChartBlock.style.display = "none";
      }

      if (hasCountryChart) {
        countriesChartBlock.style.display = "block";

        var countriesChartCanvas = document.getElementById("countriesChart");

        countriesChartInstance = new Chart(countriesChartCanvas, {
          type: "bar",
          data: {
            labels: countryLabels,
            datasets: [{
              label: "Clicks por país",
              data: countryFrequencies,
              borderWidth: 1,
              backgroundColor: "#4a776682",
              borderColor: "#4a7766"
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                }
              }
            }
          }
        });
      } else {
        countriesChartBlock.style.display = "none";
      }
    }

    function viewDetail(id) {
      loadStats(currentPage, id);
    }

    function changePage(page) {
      loadStats(page, selectedId);
    }
  </script>
</head>
<body>
  <div class="contenedorPrincipal">
    <img src="/favicon.png" alt="Logo acortador" class="logo">

    <h1 id="pageTitle" class="tituloPrincipal">Acortador de URLs</h1>

    <nav class="navegacionTabs">
      <a
        href="index.php"
        id="shortenerTab"
        class="tabLink tabActiva"
        onclick="loadSection(event, 'shortener')"
      >
        Acortar
      </a>

      <a
        href="estadisticas.php"
        id="statsTab"
        class="tabLink"
        onclick="loadSection(event, 'stats')"
      >
        Estadísticas
      </a>
    </nav>

    <div id="sectionContent">
      <?php include __DIR__ . "/sections/shortener.php"; ?>
    </div>
  </div>
</body>
</html>
