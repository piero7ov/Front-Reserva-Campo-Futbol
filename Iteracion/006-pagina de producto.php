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

      /* =========================
         CATÁLOGO
         ========================= */
      .catalogo{
        display:grid;
        grid-template-columns:repeat(3,100fr);
        gap:20px;
      }

      main article .imagen{
        height:100px;
      }

      main a{
        background:green;
        color:white;
        text-decoration:none;
        padding:10px;
        border-radius:10px;
        display:inline-block;
      }

      /* =========================
         VISTA DETALLE
         ========================= */
      section{
        width:100%;
        display:flex;
        gap:18px;
        align-items:flex-start;
      }

      section .izquierda{flex:1;}
      section .derecha{
        flex:2;
        text-align:left;
        padding:8px 6px;
      }

      section .izquierda img{
        width:100%;
        border-radius:6px;
        display:block;
      }

      .derecha label{
        display:block;
        margin:12px 0 6px 0;
        font-weight:600;
        color:#111;
      }

      .derecha select{
        width:100%;
        padding:10px 12px;
        border-radius:12px;
        border:1px solid #d1d5db;
        background:#fff;
        outline:none;
        box-sizing:border-box;
        margin-bottom:10px;
      }

      section .izquierda a{
        margin-top:12px;
      }

      /* =========================
         HEADER (TU HERO)
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
        box-sizing:border-box;
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
      <?php
        if(isset($_GET['reservar'])){
      ?>

      <!-- VISTA DETALLE -->
      <section class="campo">
        <div class="izquierda">
          <img src="img/campo.png">
          <a href="?reservar=1">📌 Reservar</a>
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

      <?php }else{ ?>

      <!-- CATÁLOGO -->
      <section class="catalogo">
        <?php for($i = 0;$i<6;$i++){ ?>
          <article>
            <div class="imagen" style="background:url(img/campo.png);background-size:cover;background-position:center center;"></div>
            <h3>Nombre del campo</h3>
            <p>Breve descripción del campo</p>
            <p>70€/hora</p>
            <a href="?reservar=1">📌 Reservar</a>
          </article>
        <?php } ?>
      </section>

      <?php } ?>
    </main>

    <footer>
      (c) PieroDev 2026
    </footer>
  </body>
</html>
