<!doctype html>
<html lang="es">
  <head>
    <title>Reserva de campos de futbol</title>
    <meta charset="utf-8">
    <style>
      header,main,footer{
        width:800px;
        margin:auto;
        text-align:center;
        font-family:sans-serif;
      }
      main{
        display:grid;
        grid-template-columns:repeat(3,100fr);
        gap:20px;
      }
      main article .imagen{
        height:100px;
      }
      main article a{
        background:green;
        color:white;
        text-decoration:none;
        padding:10px;
        border-radius:10px;
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Reserva de campos de futbol</h1>
    </header>
    <main>
      <?php for($i = 0;$i<6;$i++){ ?>
        <article>
          <div class="imagen" style="background:url(img/campo.png);background-size:cover;background-position:center center;"></div>
          <h3>Nombre del campo</h3>
          <p>Breve descripción del campo</p>
          <p>70€/hora</p>
          <a href="reservar.php">Reservar</a>
        </article>
      <?php } ?>
    </main>
    <footer>
    </footer>
  </body>
</html>
