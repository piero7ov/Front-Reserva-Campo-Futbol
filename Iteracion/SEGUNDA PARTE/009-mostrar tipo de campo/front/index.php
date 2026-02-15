<?php session_start(); ?>
<!doctype html>
<html lang="es">

<head>
  <title>Reserva de campos de futbol</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/estilo.css">
</head>

<body>

  <a href="?" style="color:inherit;text-decoration:none;display:block;">
    <header>
      <h1>Reserva de campos de futbol</h1>
      <h2>Reserva en 1 minuto y juega hoy con tus amigos</h2>
    </header>
  </a>

  <main>
    <?php
    if (isset($_GET['operacion'])) {
      if ($_GET['operacion'] == "campo") {
        include "inc/campo.php";
      } else if ($_GET['operacion'] == "carrito") {
        include "inc/carrito.php";
      } else if ($_GET['operacion'] == "finalizacion") {
        include "inc/finalizacion.php";
      }
    } else {
      include "inc/catalogo.php";
    }
    ?>
  </main>

  <footer>
    (c) PieroDev 2026
  </footer>
</body>
</html>
