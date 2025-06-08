<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
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
<script type="text/javascript">
  window.MOMENTO_ACTUAL = <?= json_encode($momentoActual) ?>;
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const grid = document.querySelector('.grid');
    const items = grid.querySelectorAll('.menu-option');
    if (items.length === 1) {
      grid.style.maxWidth = '300px';
      grid.style.margin = '0 auto';
      items[0].style.maxWidth = '600px';
      items[0].style.margin = '0 auto';
      items[0].style.fontSize = '3em';
    }
  });
</script>

<body>
  <main>

    <h1>Listados</h1>

    <a href="/InscripcionesEVG/index.php?controlador=actividades&accion=cMostrarActividades" class="menu-option" aria-label="Descargas Actividades">
      <i class="fa-solid fa-calendar-check"></i>
      Listado de Actividades
    </a>
    </section>
  </main>

</body>

</html>
