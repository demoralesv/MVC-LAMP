<div class="estadisticasLayout">
  <div class="tarjetaFormulario panelUrls">
    <label class="etiquetaCampo">URLs guardadas</label>

    <div class="listaUrls" id="urlList">
      <div class="campoUrl detalleVacio">Cargando...</div>
    </div>

    <div class="paginacion" id="pagination"></div>
  </div>

  <div class="tarjetaFormulario panelDetalle">
    <label class="etiquetaCampo">Detalle</label>

    <div id="emptyDetail" class="campoUrl detalleVacio">
      Elegí una URL y presioná <strong>Ver</strong>.
    </div>

    <div id="detailContent" class="campoUrl detalleVacio detalleTexto" style="display: none;">
      <div>
        <strong>URL Original:</strong>
        <a id="originalUrlLink" href="#" target="_blank" rel="noopener noreferrer"></a>
      </div>

      <div>
        <strong>URL Corta:</strong>
        <a id="shortUrlLink" href="#" target="_blank" rel="noopener noreferrer"></a>
      </div>

      <div>
        <strong>Fecha de Creación:</strong>
        <p id="createdAtText"></p>
      </div>

      <div>
        <strong>Países:</strong>
        <div id="countryList">Sin datos.</div>
      </div>

      <div>
        <strong>Fechas:</strong>
        <div id="dateList">Sin datos.</div>
      </div>

      <div class="graficosDetalle" id="chartsSection" style="display: none;">
        <div class="graficoBloque" id="datesChartBlock" style="display: none;">
          <strong>Gráfico por fecha:</strong>
          <canvas id="datesChart"></canvas>
        </div>

        <div class="graficoBloque" id="countriesChartBlock" style="display: none;">
          <strong>Gráfico por país:</strong>
          <canvas id="countriesChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
