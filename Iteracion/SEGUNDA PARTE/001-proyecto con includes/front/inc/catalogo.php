 <!-- CATÁLOGO -->
        <section class="catalogo">
          <?php for($i = 0;$i<6;$i++){ ?>
            <article>
              <div class="imagen" style="background:url(img/campo.png);background-size:cover;background-position:center center;"></div>
              <h3>Nombre del campo</h3>
              <p>Breve descripción del campo</p>
              <p>70€/hora</p>
              <a href="?operacion=campo&campo=1">📌 Reservar</a>
            </article>
          <?php } ?>
        </section>