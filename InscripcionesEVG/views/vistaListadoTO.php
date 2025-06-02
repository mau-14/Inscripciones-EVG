<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
include $navBar;
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Listado de pruebas - Torneo Olímpico</title>

  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/gestionPruebasTO.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<script type="module">
  import {
    Loader
  } from "./js/utils/loader.js";
  import {
    obtenerExceldePruebas
  } from "./js/controllers/c_obtenerAlumnos.js";
  (async () => {
    try {
      await obtenerExceldePruebas(94, 93);
    } catch (error) {
      console.error("Error al cargar los campos o alumnos:", error);
    }
  })();
</script>

<body>
  <main>
    <h1>Listado de pruebas - Torneo Olímpico</h1>
    <section class="grid" id="pruebas-listado">
      <!-- Aquí se llenarán las pruebas dinámicamente -->
    </section>


    <style>
      .btn-descarga {
        display: inline-block;
        padding: 0.3em 0.7em;
        background-color: #2a7ae2;
        color: white;
        text-decoration: none;
        border-radius: 4px;
      }

      .btn-descarga:hover {
        background-color: #1a5dcc;
      }

      .prueba-item {
        border: 1px solid #ccc;
        padding: 1em;
        margin-bottom: 1em;
        border-radius: 6px;
        background: #f9f9f9;
      }
    </style>
  </main>
</body>

</html>
