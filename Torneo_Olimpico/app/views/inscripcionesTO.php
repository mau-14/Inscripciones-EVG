<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inscripciones Torneo Olímpico</title>
  <base href="/Torneo_Olimpico/">
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/inscripcionesTO.css" rel="stylesheet" />
  <link href="css/footer.css" rel="stylesheet" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />


  <script type="module">
    import {
      crearCamposPorPrueba
    } from "./js/controllers/c_obtenerPruebas.js";
    import {
      rellenarSelectsConAlumnos
    } from "./js/controllers/c_obtenerAlumnos.js";

    (async () => {
      try {
        // Primero se crean los campos de las pruebas
        await crearCamposPorPrueba();
        // Luego se rellenan los selects con los alumnos
        await rellenarSelectsConAlumnos();
      } catch (error) {
        console.error("Error al cargar los campos o alumnos:", error);
      }
    })();
  </script>

</head>

<?php

include $_SERVER['DOCUMENT_ROOT'] . '/Torneo_Olimpico/app/config/entorno/variables.php';
include $navBar;

?>

<body>

  <main>

    <section id="secInscripcion">
      <h1>Panel de Inscripciones</h1>




      <form action="procesar_inscripcion.php" method="POST" class="formulario-inscripciones">
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
          <button type="submit">Inscribir</button>
          <button type="reset">Limpiar</button>
        </div>
      </form>
    </section>
  </main>

  <?php include $footer; ?>

</body>

</html>
