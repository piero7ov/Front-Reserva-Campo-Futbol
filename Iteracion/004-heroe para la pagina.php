<!doctype html>
<html lang="es">
  <head>
    <title>Reserva de campos de futbol</title>
    <meta charset="utf-8">

    <style>
      body{
        margin:0;
        padding:0;
      }

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
        display:inline-block;
      }

      /* =========================
         HEADER
         ========================= */
      header{
        width:100%;
        height:400px;
        background-image:
          linear-gradient(rgba(255,255,255,0.40), rgba(255,255,255,0.40)),
          url("img/heroe.png");
        background-size:cover;
        background-position:center center;
        padding:20px;
        margin-bottom:20px;
        display:flex;
        justify-content:center;
        align-items:center;
        filter:none;
        flex-direction:column;
        gap:10px;
      }

      header h1{
        margin:0;
        padding:12px 18px;
      }

      header h2{
        margin:0;
        padding:12px 18px;
        font-size:18px;
      }
    </style>
  </head>

  <body>
    <header>
      <h1>Reserva de campos de futbol</h1>
      <h2>Reserva en 1 minuto y juega hoy con tus amigos</h2>
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
