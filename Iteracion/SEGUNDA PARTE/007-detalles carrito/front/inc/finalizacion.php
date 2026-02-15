<?php
if (!isset($_SESSION["carrito"])) {
  $_SESSION["carrito"] = [];
}

$conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");
$conexion->set_charset("utf8mb4");

/* Solo guardamos si hay carrito y vienen datos del cliente por POST */
if (count($_SESSION["carrito"]) > 0 && isset($_POST["nombre"], $_POST["apellidos"], $_POST["email"], $_POST["telefono"])) {

  $nombre = (string)$_POST["nombre"];
  $apellidos = (string)$_POST["apellidos"];
  $email = (string)$_POST["email"];
  $telefono = (string)$_POST["telefono"];

  /* 1) Insertar CLIENTE */
  $stmtCliente = $conexion->prepare("INSERT INTO cliente (nombre, apellidos, email, telefono) VALUES (?, ?, ?, ?)");
  $stmtCliente->bind_param("ssss", $nombre, $apellidos, $email, $telefono);
  $stmtCliente->execute();
  $cliente_id = (int)$conexion->insert_id;

  /* 2) Insertar RESERVA */
  $fecha = date("Y-m-d");
  $stmtReserva = $conexion->prepare("INSERT INTO reserva (fecha, cliente_id) VALUES (?, ?)");
  $stmtReserva->bind_param("si", $fecha, $cliente_id);
  $stmtReserva->execute();
  $reserva_id = (int)$conexion->insert_id;

  /* 3) Insertar LINEAS */
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

  /* 4) Vaciar carrito */
  $_SESSION["carrito"] = [];
}
?>

<!-- FINALIZACIÓN -->
<section class="finalizacion">
  <h3>Reserva finalizada</h3>
  <p>Muchas gracias por tu reserva</p>
  <a href="?">⬅ Volver al catálogo</a>
</section>
