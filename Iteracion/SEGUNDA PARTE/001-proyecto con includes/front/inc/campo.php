          <!-- DETALLE (campo) -->
          <section class="campo">
            <div class="izquierda">
              <img src="img/campo.png">
              <a href="?operacion=carrito&campo=1">📌 Añadir al carrito</a>
            </div>

            <div class="derecha">
              <h3>Nombre del campo</h3>
              <p>Breve descripción del campo</p>
              <p>70€/hora</p>

              <label for="hora">Elige una hora</label>
              <select id="hora" name="hora">
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
                <option value="22:00">22:00</option>
              </select>

              <label for="duracion">Duración (máx. 2 horas)</label>
              <select id="duracion" name="duracion">
                <option value="1">1 hora</option>
                <option value="2">2 horas</option>
              </select>
            </div>
          </section>