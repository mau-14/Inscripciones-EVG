<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
include $navBar;
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Gestión Aplicación</title>

  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/menus.css" rel="stylesheet" />
  <link href="css/footer.css" rel="stylesheet" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <main>
    <h1>Panel Gestión Aplicación</h1>

    <section class="grid">
      <a href="/InscripcionesEVG/views/gestionPruebasTO.php" class="menu-option" aria-label="Gestión de Torneo Olímpico">
        <i class="fa-solid fa-trophy"></i>
        Gestión de Torneo Olímpico
      </a>
      <a href="/InscripcionesEVG/admin/actividades.php" class="menu-option" aria-label="Gestión de Actividades">
        <i class="fa-solid fa-calendar-check"></i>
        Gestión de Actividades
      </a>
      <a href="/InscripcionesEVG/admin/momentos.php" class="menu-option" aria-label="Gestión de Momentos">
        <i class="fa-solid fa-clock"></i>
        Gestión de Momentos
      </a>
    </section>
  </main>
</body>

</html>
