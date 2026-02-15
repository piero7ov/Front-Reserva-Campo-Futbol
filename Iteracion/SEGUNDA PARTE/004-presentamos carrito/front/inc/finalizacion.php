<?php
if (!isset($_SESSION["carrito"])) {
  $_SESSION["carrito"] = [];
}

$conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");

if (count($_SESSION["carrito"]) > 0) {

  // 1) Crear RESERVA (cliente_id NULL por ahora)
  $fecha = date("Y-m-d");
  $stmtReserva = $conexion->prepare("INSERT INTO reserva (fecha, cliente_id) VALUES (?, NULL)");
  $stmtReserva->bind_param("s", $fecha);
  $stmtReserva->execute();
  $reserva_id = (int)$conexion->insert_id;

  // 2) Insertar LINEAS de la reserva
  $stmtLinea = $conexion->prepare(
    "INSERT INTO lineareserva (reserva_id, campo_id, dia, hora, duracion)
     VALUES (?, ?, ?, ?, ?)"
  );

  foreach ($_SESSION["carrito"] as $item) {
    $campo_id = (int)$item["campo_id"];
    $dia = (string)$item["dia"];
    $hora = (string)$item["hora"];
    $duracion = (string)$item["duracion"];

    $stmtLinea->bind_param("iisss", $reserva_id, $campo_id, $dia, $hora, $duracion);
    $stmtLinea->execute();
  }

  // 3) Vaciar carrito
  $_SESSION["carrito"] = [];
}
?>

<!-- FINALIZACIÓN -->
<section class="finalizacion">
  <h3>Reserva finalizada</h3>
  <p>Muchas gracias por tu reserva</p>
  <a href="?">⬅ Volver al catálogo</a>
</section>
