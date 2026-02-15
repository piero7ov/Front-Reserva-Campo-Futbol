<!-- DETALLE (campo) -->
<section class="campo">
  <?php
    $conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");
    $conexion->set_charset("utf8mb4");

    $campoId = (int)($_GET['campo'] ?? 0);

    // Día seleccionado (por GET). Si no viene, hoy.
    $diaSel = isset($_GET["dia"]) ? (string)$_GET["dia"] : date("Y-m-d");

    // Validación simple de formato YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $diaSel)) {
      $diaSel = date("Y-m-d");
    }

    // Horario del local: 09:00 a 21:00
    $horas = [];
    for ($h = 9; $h <= 21; $h++) {
      $horas[] = sprintf("%02d:00", $h);
    }

    // Obtiene horas ocupadas del día (incluye el "siguiente tramo" si duracion=2)
    function horasOcupadas(mysqli $conexion, int $campoId, string $diaSel): array {
      $ocup = [];

      $diaEsc = $conexion->real_escape_string($diaSel);
      $sql = "SELECT hora, duracion FROM lineareserva
              WHERE campo_id = $campoId AND dia = '$diaEsc'";

      $res = $conexion->query($sql);
      if ($res) {
        while ($r = $res->fetch_assoc()) {
          $hora = (string)$r["hora"];
          $dur  = (int)$r["duracion"];

          $ocup[$hora] = true;

          // Si dura 2 horas, también ocupa la siguiente hora
          if ($dur >= 2) {
            $dt = new DateTime($diaSel . " " . $hora);
            $dt->modify("+1 hour");
            $ocup[$dt->format("H:i")] = true;
          }
        }
      }

      return $ocup;
    }

    // ✅ Busca el siguiente día con al menos 1 hueco
    function siguienteDisponible(mysqli $conexion, int $campoId, string $diaBase, array $horas, int $maxDias = 30): ?array {
      for ($i = 1; $i <= $maxDias; $i++) {
        $dia = date("Y-m-d", strtotime($diaBase . " +$i day"));
        $ocup = horasOcupadas($conexion, $campoId, $dia);

        foreach ($horas as $h) {
          if (!isset($ocup[$h])) {
            return ["dia" => $dia, "hora" => $h];
          }
        }
      }
      return null;
    }

    // Campo
    $resultado = $conexion->query("SELECT * FROM campo WHERE id = ".$campoId);

    while ($fila = $resultado->fetch_assoc()) {

      // Ocupadas para el día seleccionado
      $ocupadas = horasOcupadas($conexion, $campoId, $diaSel);

      // ¿Está completo el día?
      $diaCompleto = true;
      foreach ($horas as $h) {
        if (!isset($ocupadas[$h])) {
          $diaCompleto = false;
          break;
        }
      }

      $next = null;
      if ($diaCompleto) {
        $next = siguienteDisponible($conexion, $campoId, $diaSel, $horas, 30);
      }
  ?>

  <form method="post" action="?operacion=carrito">
    <input type="hidden" name="campo" value="<?php echo (int)$fila['id']; ?>">

    <div class="izquierda">
      <img src="img/<?php echo htmlspecialchars($fila['imagen']); ?>">

      <label for="dia">Elige una fecha</label>
      <!-- al cambiar la fecha, recarga el detalle con ?dia=YYYY-MM-DD -->
      <input
        id="dia"
        name="dia"
        type="date"
        value="<?php echo htmlspecialchars($diaSel); ?>"
        required
        onchange="window.location='?operacion=campo&campo=<?php echo (int)$fila['id']; ?>&dia='+this.value"
        style="width:100%;padding:10px 12px;border-radius:12px;border:1px solid #d1d5db;background:#fff;outline:none;box-sizing:border-box;margin-bottom:10px;"
      >

      <?php if ($diaCompleto) { ?>
        <p style="margin:10px 0;color:#b91c1c;font-weight:700;">
          Este día está completo.
        </p>

        <?php if ($next) { ?>
          <p style="margin:0 0 10px 0;color:#111;">
            Siguiente disponible:
            <strong><?php echo htmlspecialchars($next["dia"]); ?></strong>
            a las
            <strong><?php echo htmlspecialchars($next["hora"]); ?></strong>
          </p>
          <a href="?operacion=campo&campo=<?php echo (int)$fila['id']; ?>&dia=<?php echo htmlspecialchars($next["dia"]); ?>"
             style="background:green;color:white;text-decoration:none;padding:10px;border-radius:10px;display:inline-block;">
            Ver ese día
          </a>
        <?php } ?>
      <?php } ?>

      <label for="hora">Elige una hora</label>
      <select id="hora" name="hora" required>
        <option value="">-- Selecciona --</option>

        <?php foreach ($horas as $h) {
          $ocup = isset($ocupadas[$h]);
        ?>
          <option value="<?php echo htmlspecialchars($h); ?>" <?php echo $ocup ? "disabled" : ""; ?>>
            <?php echo htmlspecialchars($h); ?><?php echo $ocup ? " (ocupado)" : ""; ?>
          </option>
        <?php } ?>

      </select>

      <button type="submit" class="btn">📌 Añadir al carrito</button>
    </div>

    <div class="derecha">
      <h3><?php echo htmlspecialchars($fila['nombre']); ?></h3>
      <p><?php echo htmlspecialchars($fila['descripcion']); ?></p>
      <p><?php echo htmlspecialchars($fila['precio_hora']); ?>€/hora</p>

      <label for="duracion">Duración (máx. 2 horas)</label>
      <select id="duracion" name="duracion" required>
        <option value="1">1 hora</option>
        <option value="2">2 horas</option>
      </select>
    </div>
  </form>

  <?php } ?>
</section>
