<!-- CATÁLOGO -->
<section class="catalogo">
  <?php 
    $conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");
    $conexion->set_charset("utf8mb4");

    // ✅ helper simple para mostrar "tipo" bonito
    function tipoBonito($tipo){
      $t = strtolower(trim((string)$tipo));

      if ($t === "futbol_sala" || $t === "futbol sala") return "Fútbol sala";
      if ($t === "futbol7a" || $t === "futbol7" || $t === "futbol 7") return "Fútbol 7";

      // fallback: convierte _ y - en espacios y capitaliza
      $t = str_replace(["_", "-"], " ", $t);
      return mb_convert_case($t, MB_CASE_TITLE, "UTF-8");
    }

    $resultado = $conexion->query("SELECT * FROM campo");

    while($fila = $resultado->fetch_assoc()){
  ?>
    <article>
      <div class="imagen" style="background:url(img/<?php echo htmlspecialchars($fila['imagen']); ?>);background-size:cover;background-position:center center;"></div>

      <h3><?php echo htmlspecialchars($fila['nombre']); ?></h3>

      <!-- ✅ Tipo formateado -->
      <p><strong><?php echo htmlspecialchars(tipoBonito($fila['tipo'])); ?></strong></p>

      <p><?php echo htmlspecialchars($fila['descripcion']); ?></p>
      <p><?php echo htmlspecialchars($fila['precio_hora']); ?>€/hora</p>

      <a href="?operacion=campo&campo=<?php echo (int)$fila['id']; ?>">📌 Reservar</a>
    </article>
  <?php } ?>
</section>
