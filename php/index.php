<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Acortador de URLs</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="/estilos/styles.css">
<body>
  <h1>Acortador de URLs</h1>
  <form action="/acortar" method="post">
    <label for="url">URL a acortar:</label>
    <input type="text" id="url" name="url" required>
    <button type="submit">Acortar</button>
  </form>
</body>
</head>
