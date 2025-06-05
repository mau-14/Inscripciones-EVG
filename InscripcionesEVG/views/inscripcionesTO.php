<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
include $navBar;

$isCoordinador = isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'Coordinador';
$isTutor = isset($_SESSION['usuario']) && $_SESSION['usuario'] === 'Tutor';


if (!$isCoordinador && !$isTutor) {
  header("Location: /InscripcionesEVG/views/menuInscripciones.php");
  exit();
}

// if ($momentoActual['idMomento'] != $MOMENTO_TORNEO_ID) {
//   header("Location: /InscripcionesEVG/views/menuInscripciones.php");
//   exit();
// }
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inscripciones Torneo Olímpico</title>
  <base href="/InscripcionesEVG/assets/">
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/inscripcionesTO.css" rel="stylesheet" />
  <link href="css/footer.css" rel="stylesheet" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

</head>


<script>
  window.configUsuario = {
    esCoordinador: <?= json_encode($isCoordinador); ?>,
    esTutor: <?= json_encode($isTutor); ?>
  };
</script>

<script type="module" src="js/controllers/c_setUpInscripciones.js"></script>
<script type="module">
  import {
    Loader
  } from "/InscripcionesEVG/assets/js/utils/loader.js";
  import {
    crearCamposPorPrueba
  } from "./js/controllers/c_obtenerPruebas.js";
  import {
    rellenarSelectsConAlumnos,
    rellenarSelectsConSeleccionados
  } from "./js/controllers/c_obtenerAlumnos.js";
  import {
    setUpInscripciones
  } from "./js/controllers/c_setUpInscripciones.js"

  (async () => {
    try {
      // Primero se crean los campos de las pruebas
      await crearCamposPorPrueba();
      // Luego se rellenan los selects con los alumnos
      const esCoordinador = window.configUsuario?.esCoordinador;
      const esTutor = window.configUsuario?.esTutor;
      if (esCoordinador) {
        await setUpInscripciones();
      } else if (esTutor) {

        const loader = new Loader('Cargando..');
        const form = document.getElementById("formIns");

        const h2 = document.createElement("h2");
        h2.style.display = "block";

        h2.innerHTML = `Clase seleccionada: <span class="claseTexto">1º DAW CF</span>`;
        form.parentNode.insertBefore(h2, form);
        await rellenarSelectsConSeleccionados(1);

        document.getElementById("ContenidoPrincipal").style.display = "block";
        loader.ocultar();

      }
    } catch (error) {
      console.error("Error al cargar los campos o alumnos:", error);
    }
  })();
  document.addEventListener("DOMContentLoaded", function() {
    const grid = document.querySelector('.grid');
    const items = grid.querySelectorAll('.menu-option');
    if (items.length === 1) {
      items[0].style.maxWidth = '50%';
      items[0].style.margin = '0 auto';
    }
  });
</script>

<script type="module" src="js/controllers/c_inscribirAlumnosTO.js" defer></script>

<body>

  <main id="ContenidoPrincipal" style="display: none;">

    <h1 id="inscripcionesTitulo">Panel de Inscripciones</h1>
    <section id="secInscripcion">

      <form id="formIns" class="formulario-inscripciones">
        <div class="categoria-container">
          <!-- Categoría Masculina -->

          <div class="categoria">
            <h2>Categoria Masculina</h2>
            <div class="grid-pruebas" id="camposPruebasMasculina">
            </div>
          </div>
          <div class="linea-separadora"></div>
          <!-- Categoría Femenina -->
          <div class="categoria">

            <h2>Categoria Femenina</h2>
            <div class="grid-pruebas" id="camposPruebasFemenina">
            </div>
          </div>
        </div>
        </div>

        <!-- Botones -->
        <div class="botones-formulario">
          <button type="submit" id="inscripcionAlumnos">Inscribir</button>
        </div>
      </form>
    </section>

    <a href="/InscripcionesEVG/views/menuInscripciones.php" class="boton-volver">Volver</a>
  </main>
</body>

</html>
