<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inicio</title>

  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/index.css" rel="stylesheet" />
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

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
?>

<body>
  <main>
    <section id="sec1">
      <div class="ventana-imagen">
        <img src="img/EVG.jpg" alt="Logo Olimpiadas" />
        <h1 class="titulo-imagen">ACTIVIDADES</h1>
      </div>

      <div class="normas">
        <h2>Bienvenido, Profesor</h2>
        <p>
          Esta plataforma le permite gestionar de forma eficiente las actividades escolares.
          Aquí podrá revisar, organizar y supervisar las tareas asignadas a sus estudiantes.
        </p>
        <ul class="normas-lista">
          <li>
            📋 <strong>Consultar actividades:</strong> Revise todas las actividades programadas por asignatura o fecha.
          </li>
          <li>
            📝 <strong>Crear nueva actividad:</strong> Programe tareas o eventos personalizados para sus clases.
          </li>
          <li>
            ✏️ <strong>Editar o eliminar actividades:</strong> Modifique o retire actividades ya existentes.
          </li>
          <li>
            🧑‍🏫 <strong>Asignar tareas por grupo:</strong> Seleccione cursos específicos para cada actividad.
          </li>
          <li>
            📊 <strong>Ver estadísticas:</strong> Revise el estado de cumplimiento y participación del alumnado.
          </li>
        </ul>
      </div>

      <h2 style="text-align: center; margin-top: 2rem;">Seleccione una categoría</h2>
      <section class="grid" style="margin-bottom: 3rem;">
        <a href="/InscripcionesEVG/views/listadoTO.php" class="menu-option" aria-label="Listado Torneo Olímpico">
          <i class="fa-solid fa-trophy"></i>
          Listado Torneo Olímpico
        </a>
        <a href="/InscripcionesEVG/views/listadoActividades.php" class="menu-option" aria-label="Listado Actividades">
          <i class="fa-solid fa-calendar-check"></i>
          Listado Actividades
        </a>
      </section>
    </section>
  </main>
</body>

</html>
