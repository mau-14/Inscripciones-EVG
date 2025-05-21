<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
include $navBar;
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Torneo Olímpico</title>
  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/torneoOlimpico.css" rel="stylesheet" />
  <link href="css/footer.css" rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script>
    window.addEventListener("scroll", function() {
      const imagen = document.querySelector(".ventana-imagen img");
      const scrolled = window.scrollY;
      imagen.style.transform = `translateY(${-scrolled * 0.3}px)`;
    });
  </script>
</head>



<body>
  <main>
    <section id="sec1">
      <div class="ventana-imagen">
        <img src="img/logoOlimpiadas.jpg" alt="Logo Olimpiadas" />
        <h1 class="titulo-imagen">TORNEO OLÍMPICO</h1>
      </div>
      <div class="normas">
        <h2>NORMAS DEL PROCESO DE INSCRIPCIONES</h2>
        <ul class="normas-lista">
          <li>
            Lorem ipsum dolor sit amet, qui minim labore adipisicing minim
            sint cillum sint consectetur cupidatat.
          </li>
          <li>
            Lorem ipsum dolor sit amet, qui minim labore adipisicing minim
            sint cillum sint consectetur cupidatat.
          </li>
          <li>
            Lorem ipsum dolor sit amet, qui minim labore adipisicing minim
            sint cillum sint consectetur cupidatat.
          </li>
        </ul>
      </div>
    </section>
  </main>



  <?php include $footer; ?>

</body>



</html>
