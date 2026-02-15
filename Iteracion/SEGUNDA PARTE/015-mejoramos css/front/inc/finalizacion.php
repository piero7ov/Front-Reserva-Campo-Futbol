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
    <a class="btn btn--secondary" href="?">⬅ Volver al catálogo</a>
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
    <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
  </section>
<?php
  return;
}

if (count($_SESSION["carrito"]) === 0) {
?>
  <section class="finalizacion">
    <h3>Carrito vacío</h3>
    <p>No hay reservas para confirmar.</p>
    <a class="btn btn--secondary" href="?">⬅ Volver al catálogo</a>
  </section>
<?php
  return;
}

/* ============================
   Helpers de hora (H:i)
   ============================ */
function nextHour(string $hora): string {
  $dt = DateTime::createFromFormat("H:i", $hora);
  if (!$dt) return "";
  $dt->modify("+1 hour");
  return $dt->format("H:i");
}

function horaEnRango(string $hora): bool {
  // horario permitido: 09:00 a 21:00 (inicio)
  // permitimos empezar hasta 21:00 si duración=1
  return preg_match('/^\d{2}:\d{2}$/', $hora) === 1
      && $hora >= "09:00"
      && $hora <= "21:00";
}

try {
  $conexion->begin_transaction();

  /* ====================================================
     VALIDACIÓN ANTI DOBLE-RESERVA + REGLAS DURACIÓN=2
     ----------------------------------------------------
     Reglas:
     1) Siempre: (campo_id + dia + hora) debe estar libre.
     2) Si duracion=2:
        - no se puede empezar a las 21:00
        - y (hora+1) también debe estar libre
     ==================================================== */

  // Chequeo de una hora exacta
  $stmtCheck = $conexion->prepare(
    "SELECT id FROM lineareserva WHERE campo_id = ? AND dia = ? AND hora = ? LIMIT 1"
  );

  foreach ($_SESSION["carrito"] as $item) {
    $campoId  = (int)($item["campo_id"] ?? 0);
    $dia      = (string)($item["dia"] ?? "");
    $hora     = (string)($item["hora"] ?? "");
    $duracion = (int)($item["duracion"] ?? 1);

    // Validación mínima
    if ($campoId <= 0 || $dia === "" || $hora === "" || $duracion < 1 || $duracion > 2) {
      $conexion->rollback();
?>
      <section class="finalizacion">
        <h3>Error</h3>
        <p>Hay datos inválidos en el carrito.</p>
        <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
      </section>
<?php
      return;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dia)) {
      $conexion->rollback();
?>
      <section class="finalizacion">
        <h3>Error</h3>
        <p>La fecha del carrito no es válida.</p>
        <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
      </section>
<?php
      return;
    }

    if (!horaEnRango($hora)) {
      $conexion->rollback();
?>
      <section class="finalizacion">
        <h3>Error</h3>
        <p>La hora seleccionada no es válida.</p>
        <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
      </section>
<?php
      return;
    }

    // Regla: si es 2h, no puede empezar a las 21:00
    if ($duracion === 2 && $hora === "21:00") {
      $conexion->rollback();
?>
      <section class="finalizacion">
        <h3>Duración no válida</h3>
        <p>No puedes reservar 2 horas empezando a las 21:00.</p>
        <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
      </section>
<?php
      return;
    }

    // 1) Chequeo de la hora principal
    $stmtCheck->bind_param("iss", $campoId, $dia, $hora);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result();

    if ($resCheck && $resCheck->num_rows > 0) {
      $conexion->rollback();
?>
      <section class="finalizacion">
        <h3>Hora no disponible</h3>
        <p>Alguien reservó esa hora antes que tú. Vuelve al carrito y elige otra.</p>
        <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
      </section>
<?php
      return;
    }

    // 2) Si duración=2, chequeo de la hora siguiente
    if ($duracion === 2) {
      $hora2 = nextHour($hora);

      // Si por algún motivo queda fuera de rango, la bloqueamos
      if ($hora2 === "" || $hora2 > "21:00") {
        $conexion->rollback();
?>
        <section class="finalizacion">
          <h3>Duración no válida</h3>
          <p>La reserva de 2 horas se sale del horario.</p>
          <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
        </section>
<?php
        return;
      }

      $stmtCheck->bind_param("iss", $campoId, $dia, $hora2);
      $stmtCheck->execute();
      $resCheck2 = $stmtCheck->get_result();

      if ($resCheck2 && $resCheck2->num_rows > 0) {
        $conexion->rollback();
?>
        <section class="finalizacion">
          <h3>Hora no disponible</h3>
          <p>No puedes reservar 2 horas porque la siguiente hora está ocupada.</p>
          <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
        </section>
<?php
        return;
      }
    }
  }

  /* ==========================================
     INSERTAR EN BD
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

  $_SESSION["carrito"] = [];
?>
  <section class="finalizacion">
    <h3>Reserva finalizada</h3>
    <p>Muchas gracias por tu reserva</p>
    <a class="btn btn--secondary" href="?">⬅ Volver al catálogo</a>
  </section>
<?php

} catch (Throwable $e) {
  $conexion->rollback();
?>
  <section class="finalizacion">
    <h3>Error</h3>
    <p>No se pudo guardar la reserva. Inténtalo de nuevo.</p>
    <a class="btn btn--secondary" href="?operacion=carrito">⬅ Volver al carrito</a>
  </section>
<?php
}
?>
