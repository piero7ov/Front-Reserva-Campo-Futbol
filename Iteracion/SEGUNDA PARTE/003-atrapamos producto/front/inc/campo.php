<!-- DETALLE (campo) -->
<section class="campo">
  <?php 
    $conexion = new mysqli("localhost", "reserva_empresa", "Reservaempresa123_", "reserva_empresa");

    $campoId = (int)$_GET['campo'];
    $resultado = $conexion->query("SELECT * FROM campo WHERE id = ".$campoId);

    while($fila = $resultado->fetch_assoc()){
  ?>

  <form method="post" action="">
    <input type="hidden" name="operacion" value="carrito">
    <input type="hidden" name="campo" value="<?php echo (int)$fila['id']; ?>">

    <div class="izquierda">
      <img src="img/<?php echo $fila['imagen'] ?>">

      <label for="hora">Elige una hora</label>
      <select id="hora" name="hora" required>
        <option value="">-- Selecciona --</option>
        <option value="09:00">09:00</option>
        <option value="10:00">10:00</option>
        <option value="11:00">11:00</option>
        <option value="12:00">12:00</option>
        <option value="13:00">13:00</option>
        <option value="14:00">14:00</option>
        <option value="15:00">15:00</option>
        <option value="16:00">16:00</option>
        <option value="17:00">17:00</option>
        <option value="18:00">18:00</option>
        <option value="19:00">19:00</option>
        <option value="20:00">20:00</option>
        <option value="21:00">21:00</option>
      </select>

      <button type="submit" style="background:green;color:white;border:none;padding:10px;border-radius:10px;cursor:pointer;">
        📌 Añadir al carrito
      </button>
    </div>

    <div class="derecha">
      <h3><?php echo $fila['nombre'] ?></h3>
      <p><?php echo $fila['descripcion'] ?></p>
      <p><?php echo $fila['precio_hora'] ?>€/hora</p>

      <label for="duracion">Duración (máx. 2 horas)</label>
      <select id="duracion" name="duracion" required>
        <option value="1">1 hora</option>
        <option value="2">2 horas</option>
      </select>
    </div>
  </form>

  <?php } ?>
</section>
