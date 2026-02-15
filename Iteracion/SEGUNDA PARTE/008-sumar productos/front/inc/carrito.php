<?php
if (!isset($_SESSION["carrito"])) {
  $_SESSION["carrito"] = [];
}

/* =========================
   1) ELIMINAR ITEM (por índice)
   ========================= */
if (isset($_GET["del"])) {
  $idx = (int)$_GET["del"];

  if (isset($_SESSION["carrito"][$idx])) {
    array_splice($_SESSION["carrito"], $idx, 1);
  }

  header("Location: ?operacion=carrito");
  exit;
}

/* =========================
   2) SUMAR DURACIÓN (+) (máx 2 horas)
   ========================= */
if (isset($_GET["inc"])) {
  $idx = (int)$_GET["inc"];

  if (isset($_SESSION["carrito"][$idx])) {
    $dur = (int)$_SESSION["carrito"][$idx]["duracion"];
    if ($dur < 2) {
      $dur++;
      $_SESSION["carrito"][$idx]["duracion"] = (string)$dur; // tu modelo lo tiene como VARCHAR
    }
  }

  header("Location: ?operacion=carrito");
  exit;
}

/* =========================
   3) AÑADIR ITEM (POST desde campo.php)
   ========================= */
if (isset($_POST["campo"], $_POST["dia"], $_POST["hora"], $_POST["duracion"])) {

  $campo_id = (int)$_POST["campo"];
  $dia = (string)$_POST["dia"];
  $hora = (string)$_POST["hora"];
  $duracion = (string)$_POST["duracion"];

  $_SESSION["carrito"][] = [
    "campo_id" => $campo_id,
    "dia" => $dia,
    "hora" => $hora,
    "duracion" => $duracion
  ];

  header("Location: ?operacion=carrito");
  exit;
}

$conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");
$conexion->set_charset("utf8mb4");

$total = 0;
?>

<section class="finalizacion">
  <div style="display:flex;gap:18px;align-items:flex-start;">

    <!-- TABLA (izquierda) -->
    <div style="flex:2;">
      <table>
        <thead>
          <tr>
            <th>Campo</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Duración</th>
            <th>Precio</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <?php if (count($_SESSION["carrito"]) == 0) { ?>
            <tr>
              <td colspan="6">Carrito vacío</td>
            </tr>
          <?php } else { ?>

            <?php foreach ($_SESSION["carrito"] as $i => $item) {

              $campo_id = (int)$item["campo_id"];
              $dia = $item["dia"];
              $hora = $item["hora"];
              $duracion = (int)$item["duracion"];

              $res = $conexion->query("SELECT nombre, precio_hora FROM campo WHERE id = ".$campo_id);
              $campo = $res ? $res->fetch_assoc() : null;

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
                <td>
                  <?php if ($duracion < 2) { ?>
                    <a class="btn-mas" href="?operacion=carrito&inc=<?php echo $i; ?>">+</a>
                  <?php } else { ?>
                    <span class="btn-mas-disabled">+</span>
                  <?php } ?>

                  &nbsp;

                  <a class="btn-eliminar" href="?operacion=carrito&del=<?php echo $i; ?>">🗑 Eliminar</a>
                </td>
              </tr>
            <?php } ?>

            <tr>
              <td colspan="4">Total</td>
              <td><?php echo (int)$total; ?>€</td>
              <td></td>
            </tr>

          <?php } ?>
        </tbody>
      </table>

      <?php if (count($_SESSION["carrito"]) == 0) { ?>
        <div style="margin-top:12px;">
          <a href="?">⬅ Volver al catálogo</a>
        </div>
      <?php } ?>
    </div>

    <!-- FORM CLIENTE (derecha) -->
    <div style="flex:1;text-align:left;">
      <form method="post" action="?operacion=finalizacion">

        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" type="text" required>

        <label for="apellidos">Apellidos</label>
        <input id="apellidos" name="apellidos" type="text" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>

        <label for="telefono">Teléfono</label>
        <input id="telefono" name="telefono" type="text" required>

        <button type="submit" <?php echo (count($_SESSION["carrito"])==0) ? 'disabled style="opacity:.5;cursor:not-allowed;"' : ''; ?>>
          ✅ Confirmar reserva
        </button>

      </form>
    </div>

  </div>
</section>
