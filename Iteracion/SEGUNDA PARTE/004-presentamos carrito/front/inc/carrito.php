<?php
if (!isset($_SESSION["carrito"])) {
  $_SESSION["carrito"] = [];
}

/* Si llegamos desde campo.php (POST), añadimos al carrito */
if (isset($_POST["campo"], $_POST["dia"], $_POST["hora"], $_POST["duracion"])) {

  $campo_id = (int)$_POST["campo"];
  $dia = (string)$_POST["dia"];
  $hora = (string)$_POST["hora"];
  $duracion = (string)$_POST["duracion"]; // tu modelo lo tiene como VARCHAR

  $_SESSION["carrito"][] = [
    "campo_id" => $campo_id,
    "dia" => $dia,
    "hora" => $hora,
    "duracion" => $duracion
  ];

  /* Evita duplicar al refrescar */
  header("Location: ?operacion=carrito");
  exit;
}

$conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");

$total = 0;
?>

<!-- CARRITO -->
<section class="finalizacion">
  <table>
    <thead>
      <tr>
        <th>Campo</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Duración</th>
        <th>Precio</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($_SESSION["carrito"] as $item) { 
        $campo_id = (int)$item["campo_id"];
        $dia = $item["dia"];
        $hora = $item["hora"];
        $duracion = (int)$item["duracion"];

        $res = $conexion->query("SELECT nombre, precio_hora FROM campo WHERE id = ".$campo_id);
        $campo = $res->fetch_assoc();

        $nombre = $campo ? $campo["nombre"] : "Campo desconocido";
        $precio_hora = $campo ? (float)$campo["precio_hora"] : 0;

        $precio = $precio_hora * $duracion;
        $total += $precio;
      ?>
        <tr>
          <td><?php echo htmlspecialchars($nombre); ?></td>
          <td><?php echo htmlspecialchars($dia); ?></td>
          <td><?php echo htmlspecialchars($hora); ?></td>
          <td><?php echo $duracion; ?> hora(s)</td>
          <td><?php echo (int)$precio; ?>€</td>
        </tr>
      <?php } ?>

      <tr>
        <td colspan="4">Total</td>
        <td><?php echo (int)$total; ?>€</td>
      </tr>
    </tbody>
  </table>

  <a href="?operacion=finalizacion">✅ Confirmar reserva</a>
</section>
