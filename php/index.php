<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Acortador de URLs</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="/estilos/styles.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background-color: #4a7766;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 30px;
    }

    .contenedorPrincipal {
      width: 100%;
      max-width: 900px;
      text-align: center;
    }

    .logo {
      width: 110px;
      height: 110px;
      object-fit: contain;
      margin-bottom: 25px;
    }

    .tituloPrincipal {
      font-size: 30px;
      font-weight: 700;
      color: #ece7e2;
      margin-bottom: 35px;
    }

    .tarjetaFormulario {
      background-color: #ece7e2;
      border: 1px solid #d8d8d8;
      border-radius: 16px;
      padding: 22px 18px 18px;
      text-align: left;
    }

    .etiquetaCampo {
      display: block;
      font-size: 16px;
      font-weight: 700;
      color: #4f4f4f;
      margin-bottom: 12px;
    }

    .campoUrl {
      width: 100%;
      height: 62px;
      border: 1px solid #cfc7bc;
      border-radius: 32px;
      padding: 0 20px;
      font-size: 15px;
      outline: none;
      background-color: #eee3d9;
      margin-bottom: 22px;
    }

    .botonCrear {
      background-color: #4a7766;
      color: #ece7e2;
      border: none;
      border-radius: 26px;
      padding: 14px 26px;
      font-size: 20px;
      font-weight: 700;
      cursor: pointer;
    }
  </style>

  <script>
	function sendData() {
	var data = {url: document.getElementById("idUrl").value}
	const xhttp = new XMLHttpRequest();
	var json = JSON.stringify(data);
	xhttp.open("POST","urlcontroller.php");
	xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function(){
	if (this.readyState == 4 && this.status == 200) {
		alert(this.responseText);
	}
	}
	xhttp.send("json=" + json);
	}
 </script>
</head>
<body>
<div class="contenedorPrincipal">
    <img src="/favicon.png" alt="Logo acortador" class="logo">

    <h1 class="tituloPrincipal">Acortador de URLs</h1>

    <div class="tarjetaFormulario">
      <label for="idUrl" class="etiquetaCampo">URL original</label>
      <input
        type="text"
        id="idUrl"
        name="direccion"
        class="campoUrl"
        placeholder="https://..."
      >
      <button class="botonCrear" onclick="sendData()">Crear</button>
    </div>
  </div>
</body>
