<!-- CATÁLOGO -->
<section class="catalogo">
  <?php 
    $conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");
    $resultado = $conexion->query("SELECT * FROM campo");
    while($fila = $resultado->fetch_assoc()){
  ?>
            <article>
              <div class="imagen" style="background:url(img/<?php echo $fila['imagen'] ?>);background-size:cover;background-position:center center;"></div>
              <h3><?php echo $fila['nombre'] ?></h3>
              <p><?php echo $fila['descripcion'] ?></p>
              <p><?php echo $fila['precio_hora'] ?>€/hora</p>
              <a href="?operacion=campo&campo=<?php echo $fila['id'] ?>">📌 Reservar</a>
            </article>
          <?php } ?>
</section>