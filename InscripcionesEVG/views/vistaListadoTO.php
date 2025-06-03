<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
require_once $navBar;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Listado de pruebas - Torneo Olímpico</title>

  <base href="/InscripcionesEVG/assets/">
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/general.css" />
  <link rel="stylesheet" href="css/vistaListadoTO.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <main>
    <header>
      <h1>Listado de pruebas - Torneo Olímpico</h1>
    </header>

    <section class="pruebasExcel">
      <!-- Las tarjetas se insertan dinámicamente -->
    </section>
  </main>

  <script type="module">
    import {
      cargarPruebasConDescarga
    } from "./js/controllers/c_obtenerPruebas.js";
    import {
      Loader
    } from "./js/utils/loader.js";

    document.addEventListener('DOMContentLoaded', async () => {
      const loader = new Loader('Cargando pruebas...');
      try {
        await cargarPruebasConDescarga();
      } catch (error) {
        console.error("Error al cargar las pruebas:", error);
      } finally {
        loader.ocultar();
      }
    });
  </script>
</body>

</html>
