<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
include $navBar;
function fechaAFrase($fechaStr)
{
  $meses = [
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
  ];

  $timestamp = strtotime($fechaStr);
  $dia = date('j', $timestamp);    // día sin cero a la izquierda
  $mes = (int)date('n', $timestamp); // número de mes sin cero

  return $dia . ' de ' . $meses[$mes];
}

?>
<!-- Inyección de variable de sesión -->
<script type="text/javascript">
  window.MOMENTO_ACTUAL = <?= json_encode($momentoActual) ?>;
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const grid = document.querySelector('.grid');
    const items = grid.querySelectorAll('.menu-option');
    if (items.length === 1) {
      grid.style.maxWidth = '800px';
      grid.style.margin = '0 auto';
      items[0].style.maxWidth = '600px';
      items[0].style.margin = '0 auto';
      items[0].style.fontSize = '3em';
    }
  });
</script>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inscripciones - Panel de <?php echo $momentoActual['nombre']; ?></title>

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
    <h1>
      Periodo activo:
      <?php
      echo fechaAFrase($momentoActual['fecha_inicio']) . " a " . fechaAFrase($momentoActual['fecha_fin']);
      ?>
    </h1>
    <section class="grid">
      <?php if ($momentoTorneo): ?>
        <a href="/InscripcionesEVG/views/inscripcionesTO.php" class="menu-option" aria-label="Inscripción Torneo Olímpico">
          <i class="fa-solid fa-trophy"></i>
          Inscripción Torneo Olímpico
        </a>
      <?php endif; ?>
      <a href="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cMostrarActividades" class="menu-option" aria-label="Inscripción Actividades">
        <i class="fa-solid fa-calendar-check"></i>
        Inscripción Actividades </br><?php echo $momentoActual['nombre']; ?>
      </a>

    </section>
  </main>

</body>

</html>
