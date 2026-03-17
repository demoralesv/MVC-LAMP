<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Acortador de URLs</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="/estilos/styles.css">
  <script>
        function esUrlValida(valor) {
          try {
             const url = new URL(valor);
        return url.protocol === "http:" || url.protocol === "https:";
          } catch {
        	return false;
          }
        }
        function mostrarMensaje(texto) {
          const mensaje = document.getElementById("mensajeError");
          mensaje.textContent = texto;
          mensaje.classList.add("mostrar");
        }
        function ocultarMensaje() {
          const mensaje = document.getElementById("mensajeError");
          mensaje.textContent = "";
          mensaje.classList.remove("mostrar");
        }
        function mostrarMensajeExito(texto) {
          const mensaje = document.getElementById("mensajeExito");
          mensaje.textContent = texto;
          mensaje.classList.add("mostrar");
        }
        function ocultarMensajeExito() {
          const mensaje = document.getElementById("mensajeExito");
          mensaje.textContent = "";
          mensaje.classList.remove("mostrar");
        }

	function sendData() {
        const inputUrl = document.getElementById("idUrl");
        const url = inputUrl.value.trim();
        ocultarMensaje();
        inputUrl.classList.remove("campoInvalido");

        if (url === "") {
          mostrarMensaje("Por favor ingresa una URL.");
          inputUrl.classList.add("campoInvalido");
          inputUrl.focus();
        	return;
        }

        if (!esUrlValida(url)) {
          mostrarMensaje("Ingresa una URL válida que empiece con http:// o https://");
          inputUrl.classList.add("campoInvalido");
          inputUrl.focus();
        return;
        }
        const data = { url: url };
	const xhttp = new XMLHttpRequest();
	var json = JSON.stringify(data);
	xhttp.open("POST","urlcontroller.php");
	xhttp.setRequestHeader("Content-Type","application/json");
	xhttp.onreadystatechange = function(){
	if (this.readyState == 4 && this.status == 200) {
         ocultarMensaje();
	 mostrarMensajeExito(this.responseText);
         
	} else {
	   mostrarMensaje("Ocurrió un error al procesar la solicitud.");
		}
	}
	xhttp.send(json);
	}
 </script>
</head>
<body>
<div class="contenedorPrincipal">
    <img src="/favicon.png" alt="Logo acortador" class="logo">

    <h1 class="tituloPrincipal">Acortador de URLs</h1>
    <nav class="navegacionTabs">
      <a href="index.php" class="tabLink tabActiva">Acortar</a>
      <a href="estadisticas.php" class="tabLink">Estadísticas</a>
    </nav>
    <div class="tarjetaFormulario">
      <label for="idUrl" class="etiquetaCampo">URL original</label>
      <input type="url" id="idUrl" name="direccion" class="campoUrl" required placeholder="https://...">
      <div id="mensajeError" class="mensajeError"></div>
      <div id="mensajeExito" class="mensajeExito"></div>
      <button class="botonCrear" onclick="sendData()">Crear</button>
    </div>
  </div>
</body>
</html>
