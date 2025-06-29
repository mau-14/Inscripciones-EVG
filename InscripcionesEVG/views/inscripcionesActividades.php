<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscripciones Actividades</title>
  <link rel="stylesheet" href="<?php echo CSS ?>estilo.css">
  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
</head>

<body>
  <main>
    <h1>Inscripción Actividades</h1>

    <div class="modal error-modal" id="modalError" style="display:none;">
      <div class="modal-contenido">
        <span class="cerrar" id="btnCerrarError">&times;</span>
        <h2>Error</h2>
        <p id="errorMsgText"></p>
      </div>
    </div>

    <div class="contenedor-actividades">
      <?php
      $actividadesC = [];
      $actividadesV = [];

      // Primero separamos las actividades por tipo
      foreach ($dataToView["data"] as $actividad) {
        if ($actividad['tipo'] === 'C') {
          $actividadesC[] = $actividad;
        } elseif ($actividad['tipo'] === 'V') {
          $actividadesV[] = $actividad;
        }
      }

      // Mostrar actividades de Clase (C)
      if (!empty($actividadesC)): ?>
        <div class="seccion-actividades">
          <h2 class="seccion-titulo">Actividades de Clase</h2>
          <div class="actividades-grid">
            <?php foreach ($actividadesC as $actividad): ?>
              <a href="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cInscripcionesClase&id=<?php echo $actividad['idActividad'] ?>" class="actividad-enlace">
                <div class="actividad-card">
                  <div class="actividad-contenido">
                    <div class="actividad-nombre"><?php echo htmlspecialchars($actividad['nombre']); ?></div>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php
      // Mostrar actividades de Alumnos (V)
      if (!empty($actividadesV)): ?>
        <div class="seccion-actividades">
          <h2 class="seccion-titulo">Actividades de Alumnos</h2>
          <div class="actividades-grid">
            <?php foreach ($actividadesV as $actividad): ?>
              <?php if ($_SESSION['usuario'] === 'Coordinador') {
                echo '<a href="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cMostrarClasesElegir&id=' . $actividad['idActividad'] . '" class="actividad-enlace">';
              }
              if ($_SESSION['usuario'] === 'Tutor') {
                echo '<a href="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cInscripcionesAlumnos&id=' . $actividad['idActividad'] . '" class="actividad-enlace">';
              }
              ?>
              <div class="actividad-card">
                <div class="actividad-contenido">
                  <div class="actividad-nombre"><?php echo htmlspecialchars($actividad['nombre']); ?></div>
                </div>
              </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif;

      if (empty($actividadesC) && empty($actividadesV)) {
        echo '<p>No hay actividades disponibles</p>';
      }
      ?>
    </div>
  </main>

  <script>
    // Mostrar modal si hay un mensaje de error
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('modalError');
      const btnCerrar = document.getElementById('btnCerrarError');

      // Verificar si hay un mensaje de error en la URL
      const urlParams = new URLSearchParams(window.location.search);

      if (urlParams.has('error')) {
        const mensajeError = decodeURIComponent(urlParams.get('error'));
        const contenido = document.getElementById('errorMsgText');

        contenido.textContent = mensajeError;
        modal.style.display = 'block';

        // Limpiar el parámetro de error de la URL sin recargar la página
        const nuevaURL = window.location.pathname;
        window.history.replaceState({}, document.title, nuevaURL);
      }

      // Cerrar el modal al hacer clic en la X
      btnCerrar.onclick = function() {
        modal.style.display = 'none';
      };

      // Cerrar el modal al hacer clic fuera del contenido
      window.onclick = function(event) {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      };
    });
  </script>

  <a href="/InscripcionesEVG/views/menuInscripciones.php" class="boton-volver">Volver</a>
</body>

</html>
