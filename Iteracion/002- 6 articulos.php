<!doctype html>
<html lang="es">
  <head>
    <title>Reserva de campos de futbol</title>
    <meta charset="utf-8">
  </head>
  <body>
    <header>
      <h1>Reserva de campos de futbol</h1>
    </header>
    <main>
      <?php for($i = 0;$i<5;$i++){ ?>
        <article>
          <div class="imagen" style="background:url(img/campo.png);"></div>
          <h3>Nombre del campo</h3>
          <p>Breve descripción del campo</p>
          <p>70€/hora</p>
          <a href="reservar.php"></a>
        </article>
      <?php } ?>
    </main>
    <footer>
    </footer>
  </body>
</html>
