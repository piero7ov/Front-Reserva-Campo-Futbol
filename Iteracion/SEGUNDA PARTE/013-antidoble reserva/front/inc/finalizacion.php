<?php
if (!isset($_SESSION["carrito"])) {
  $_SESSION["carrito"] = [];
}

$conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");
$conexion->set_charset("utf8mb4");

// Si entran por GET sin confirmar, mostramos simple
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
?>
  <section class="finalizacion">
    <h3>Reserva finalizada</h3>
    <p>Muchas gracias por tu reserva</p>
    <a href="?">⬅ Volver al catálogo</a>
  </section>
<?php
  return;
}

// Datos cliente
$nombre    = trim((string)($_POST["nombre"] ?? ""));
$apellidos = trim((string)($_POST["apellidos"] ?? ""));
$email     = trim((string)($_POST["email"] ?? ""));
$telefono  = trim((string)($_POST["telefono"] ?? ""));

if ($nombre === "" || $apellidos === "" || $email === "" || $telefono === "") {
?>
  <section class="finalizacion">
    <h3>Error</h3>
    <p>Faltan datos del cliente. Vuelve al carrito y completa el formulario.</p>
    <a href="?operacion=carrito">⬅ Volver al carrito</a>
  </section>
<?php
  return;
}

if (count($_SESSION["carrito"]) === 0) {
?>
  <section class="finalizacion">
    <h3>Carrito vacío</h3>
    <p>No hay reservas para confirmar.</p>
    <a href="?">⬅ Volver al catálogo</a>
  </section>
<?php
  return;
}

try {
  $conexion->begin_transaction();

  /* ==========================================
     ✅ PASO 2/3: VALIDACIÓN ANTI DOBLE-RESERVA
     ------------------------------------------
     Antes de insertar, verificamos si ya existe
     una lineareserva con (campo_id + dia + hora)
     ========================================== */
  $stmtCheck = $conexion->prepare(
    "SELECT id FROM lineareserva WHERE campo_id = ? AND dia = ? AND hora = ? LIMIT 1"
  );

  foreach ($_SESSION["carrito"] as $item) {
    $campoId  = (int)($item["campo_id"] ?? 0);
    $dia      = (string)($item["dia"] ?? "");
    $hora     = (string)($item["hora"] ?? "");
    $duracion = (string)($item["duracion"] ?? "");

    if ($campoId <= 0 || $dia === "" || $hora === "" || $duracion === "") {
      $conexion->rollback();
?>
      <section class="finalizacion">
        <h3>Error</h3>
        <p>Hay datos incompletos en el carrito.</p>
        <a href="?operacion=carrito">⬅ Volver al carrito</a>
      </section>
<?php
      return;
    }

    $stmtCheck->bind_param("iss", $campoId, $dia, $hora);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result();

    // Si existe, ya está ocupado -> cancelamos todo
    if ($resCheck && $resCheck->num_rows > 0) {
      $conexion->rollback();
?>
      <section class="finalizacion">
        <h3>Hora no disponible</h3>
        <p>Alguien reservó esa hora antes que tú. Vuelve al carrito y elige otra.</p>
        <a href="?operacion=carrito">⬅ Volver al carrito</a>
      </section>
<?php
      return;
    }
  }

  /* ==========================================
     ✅ PASO 1/3 (ya lo tenías): INSERTAR EN BD
     ========================================== */

  // 1) Insertar cliente
  $stmtCliente = $conexion->prepare(
    "INSERT INTO cliente (nombre, apellidos, email, telefono) VALUES (?, ?, ?, ?)"
  );
  $stmtCliente->bind_param("ssss", $nombre, $apellidos, $email, $telefono);
  $stmtCliente->execute();
  $clienteId = $conexion->insert_id;

  // 2) Insertar reserva (cabecera)
  $fechaReserva = date("Y-m-d H:i:s");
  $stmtReserva = $conexion->prepare(
    "INSERT INTO reserva (fecha, cliente_id) VALUES (?, ?)"
  );
  $stmtReserva->bind_param("si", $fechaReserva, $clienteId);
  $stmtReserva->execute();
  $reservaId = $conexion->insert_id;

  // 3) Insertar líneas
  $stmtLinea = $conexion->prepare(
    "INSERT INTO lineareserva (reserva_id, campo_id, dia, hora, duracion) VALUES (?, ?, ?, ?, ?)"
  );

  foreach ($_SESSION["carrito"] as $item) {
    $campoId  = (int)$item["campo_id"];
    $dia      = (string)$item["dia"];
    $hora     = (string)$item["hora"];
    $duracion = (string)$item["duracion"];

    $stmtLinea->bind_param("iisss", $reservaId, $campoId, $dia, $hora, $duracion);
    $stmtLinea->execute();
  }

  $conexion->commit();

  // Vaciar carrito al finalizar
  $_SESSION["carrito"] = [];
?>
  <section class="finalizacion">
    <h3>Reserva finalizada</h3>
    <p>Muchas gracias por tu reserva</p>
    <a href="?">⬅ Volver al catálogo</a>
  </section>
<?php

} catch (Throwable $e) {
  $conexion->rollback();
?>
  <section class="finalizacion">
    <h3>Error</h3>
    <p>No se pudo guardar la reserva. Inténtalo de nuevo.</p>
    <a href="?operacion=carrito">⬅ Volver al carrito</a>
  </section>
<?php
}
?>
