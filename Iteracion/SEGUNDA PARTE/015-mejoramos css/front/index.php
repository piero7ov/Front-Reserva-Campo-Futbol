<?php
session_start();
$carritoCount = isset($_SESSION["carrito"]) ? count($_SESSION["carrito"]) : 0;
?>
<!doctype html>
<html lang="es">

<head>
  <title>Reserva de campos de futbol</title>
  <meta charset="utf-8">

  <link rel="stylesheet" href="css/estilo.css">
</head>

<body>

  <header>
    <a class="hero-link" href="?" aria-label="Volver al catálogo"></a>

    <div class="hero-content">
      <h1>Reserva de campos de futbol</h1>
      <h2>Reserva en 1 minuto y juega hoy con tus amigos</h2>
    </div>

    <a class="cart-link" href="?operacion=carrito">🛒 Carrito (<?php echo $carritoCount; ?>)</a>
  </header>

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
