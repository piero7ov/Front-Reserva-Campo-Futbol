<!-- CATÁLOGO -->
<section class="catalogo">
  <?php 
    $conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");
    $conexion->set_charset("utf8mb4");

    function tipoBonito($tipo){
      $t = strtolower(trim((string)$tipo));

      if ($t === "futbol_sala" || $t === "futbol sala") return "Fútbol sala";
      if ($t === "futbol7a" || $t === "futbol7" || $t === "futbol 7") return "Fútbol 7";

      $t = str_replace(["_", "-"], " ", $t);
      return mb_convert_case($t, MB_CASE_TITLE, "UTF-8");
    }

    $resultado = $conexion->query("SELECT * FROM campo");

    while($fila = $resultado->fetch_assoc()){
  ?>
    <article>
      <div class="imagen" style="background:url(img/<?php echo htmlspecialchars($fila['imagen']); ?>);background-size:cover;background-position:center center;"></div>

      <h3><?php echo htmlspecialchars($fila['nombre']); ?></h3>

      <p class="tipo"><?php echo htmlspecialchars(tipoBonito($fila['tipo'])); ?></p>

      <p><?php echo htmlspecialchars($fila['descripcion']); ?></p>
      <p class="precio"><?php echo htmlspecialchars($fila['precio_hora']); ?>€/hora</p>

      <div class="cta">
        <a class="btn" href="?operacion=campo&campo=<?php echo (int)$fila['id']; ?>">📌 Reservar</a>
      </div>
    </article>
  <?php } ?>
</section>
