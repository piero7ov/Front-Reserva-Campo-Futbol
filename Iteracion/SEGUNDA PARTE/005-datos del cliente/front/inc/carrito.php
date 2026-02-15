<?php
if (!isset($_SESSION["carrito"])) {
  $_SESSION["carrito"] = [];
}

/* Si viene del campo.php, añadimos una línea al carrito */
if (isset($_POST["campo"], $_POST["dia"], $_POST["hora"], $_POST["duracion"])) {

  $campo_id = (int)$_POST["campo"];
  $dia = (string)$_POST["dia"];
  $hora = (string)$_POST["hora"];
  $duracion = (string)$_POST["duracion"]; // tu tabla es VARCHAR

  $_SESSION["carrito"][] = [
    "campo_id" => $campo_id,
    "dia" => $dia,
    "hora" => $hora,
    "duracion" => $duracion
  ];
}

$conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");
$conexion->set_charset("utf8mb4");

$total = 0;
?>

<!-- CARRITO -->
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
          </tr>
        </thead>

        <tbody>
          <?php foreach ($_SESSION["carrito"] as $item) {

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
            </tr>
          <?php } ?>

          <tr>
            <td colspan="4">Total</td>
            <td><?php echo (int)$total; ?>€</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- FORM CLIENTE (derecha) -->
    <div style="flex:1;text-align:left;">
      <form method="post" action="?operacion=finalizacion">
        <label for="nombre" style="display:block;margin:0 0 6px 0;font-weight:600;">Nombre</label>
        <input id="nombre" name="nombre" type="text" required
          style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid #d1d5db;box-sizing:border-box;margin-bottom:10px;">

        <label for="apellidos" style="display:block;margin:0 0 6px 0;font-weight:600;">Apellidos</label>
        <input id="apellidos" name="apellidos" type="text" required
          style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid #d1d5db;box-sizing:border-box;margin-bottom:10px;">

        <label for="email" style="display:block;margin:0 0 6px 0;font-weight:600;">Email</label>
        <input id="email" name="email" type="email" required
          style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid #d1d5db;box-sizing:border-box;margin-bottom:10px;">

        <label for="telefono" style="display:block;margin:0 0 6px 0;font-weight:600;">Teléfono</label>
        <input id="telefono" name="telefono" type="text" required
          style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid #d1d5db;box-sizing:border-box;margin-bottom:12px;">

        <button type="submit" style="background:green;color:white;border:none;padding:10px;border-radius:10px;cursor:pointer;width:100%;">
          ✅ Confirmar reserva
        </button>
      </form>
    </div>

  </div>
</section>
