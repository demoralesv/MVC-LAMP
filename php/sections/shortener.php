<div class="tarjetaFormulario">
  <label for="urlInput" class="etiquetaCampo">URL original</label>
  <input
    type="url"
    id="urlInput"
    name="urlInput"
    class="campoUrl"
    required
    placeholder="https://..."
  >
  <div id="errorMessage" class="mensajeError"></div>
  <div id="successMessage" class="mensajeExito"></div>
  <button type="button" class="botonCrear" onclick="sendData()">Crear</button>
</div>
