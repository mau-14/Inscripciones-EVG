<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
include $navBar;
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/menus.css" rel="stylesheet" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <main>

    <h1>Descargas</h1>
    <!-- <div class="bienvenida-card"> -->
    <!--   <h3 class="subtitle-strong">Bienvenido, <?= $usuarioTipo ?></h3> -->

    <!--   <p> -->
    <!--     Le damos la bienvenida a la plataforma de gestión de inscripciones, -->
    <!--     un espacio diseñado específicamente para facilitar el seguimiento y la organización de la participación del alumnado en las distintas propuestas del centro. -->
    <!--   </p> -->

    <!--   <p> -->
    <!--     Nuestro objetivo es brindarle un recurso eficaz que contribuya a mejorar la planificación docente, promover la participación activa del alumnado y fortalecer los valores de comunidad, esfuerzo y compromiso que guían nuestro proyecto educativo. -->
    <!--   </p> -->
    <!-- </div> -->
    <section class="grid">
      <a href="/InscripcionesEVG/views/vistaDescargaTO.php" class="menu-option" aria-label="Descargas Torneo Olímpico">
        <i class="fa-solid fa-trophy"></i>
        Descargas Torneo Olímpico
      </a>
      <a href="/InscripcionesEVG/views/vistaDescargaActividades.php" class="menu-option" aria-label="Descargas Actividades">
        <i class="fa-solid fa-calendar-check"></i>
        Descargas Actividades
      </a>
    </section>
  </main>

</body>

</html>
