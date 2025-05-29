<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
include $navBar;

// Obtener fecha actual y mes para estación
$fecha_actual = new DateTime();
$mes = (int)$fecha_actual->format('m');

if ($mes >= 6 && $mes <= 8) {
  $estacion = "Verano";
  $fecha_inicio = "21 de junio";
  $fecha_fin = "23 de septiembre";
} elseif ($mes >= 9 && $mes <= 11) {
  $estacion = "Otoño";
  $fecha_inicio = "23 de septiembre";
  $fecha_fin = "21 de diciembre";
} elseif ($mes >= 12 || $mes <= 2) {
  $estacion = "Invierno";
  $fecha_inicio = "21 de diciembre";
  $fecha_fin = "20 de marzo";
} else {
  $estacion = "Primavera";
  $fecha_inicio = "20 de marzo";
  $fecha_fin = "21 de junio";
}
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inscripciones - Panel de <?php echo $estacion; ?></title>

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
    <h1>Inscripciones de <?php echo $estacion; ?></h1>
    <p class="subtitle-strong">Periodo activo: <?php echo $fecha_inicio . " a " . $fecha_fin; ?></p>

    <section class="grid">
      <a href="/InscripcionesEVG/views/inscripcionesTO.php" class="menu-option" aria-label="Inscripción Torneo Olímpico">
        <i class="fa-solid fa-trophy"></i>
        Inscripción Torneo Olímpico
      </a>
      <a href="/InscripcionesEVG/actividades/inscripcion.php" class="menu-option" aria-label="Inscripción Actividades">
        <i class="fa-solid fa-calendar-check"></i>
        Inscripción Actividades
      </a>
      <a href="/InscripcionesEVG/actividades-clase/inscripcion.php" class="menu-option" aria-label="Inscripciones Actividades de Clase">
        <i class="fa-solid fa-chalkboard-user"></i>
        Inscripciones Actividades de Clase
      </a>
    </section>
  </main>

</body>

</html>
