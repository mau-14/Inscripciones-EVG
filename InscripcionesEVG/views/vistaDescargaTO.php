<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
require_once $navBar;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Torneo Olímpico</title>

  <base href="/InscripcionesEVG/assets/">
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/general.css" />
  <link rel="stylesheet" href="css/descargas.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <main>
    <header>
      <h1>Descargas Torneo Olímpico</h1>
    </header>

    <section class="pruebasExcel">
      <table class="tabla-pruebas">
        <thead>
          <tr>
            <th>Prueba</th>
            <th>Descarga</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

      <!-- Contenedor para el botón Descargar todos -->
      <div class="descargar-todos-container" style="margin-top: 1rem;"></div>
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

  <a href="/InscripcionesEVG/views/menuDescargas.php" class="boton-volver">Volver</a>
</body>

</html>
