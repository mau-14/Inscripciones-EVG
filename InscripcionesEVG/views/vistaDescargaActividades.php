<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
require_once $navBar;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Actividades</title>

  <base href="/InscripcionesEVG/assets/">
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="stylesheet" href="css/general.css" />
  <link rel="stylesheet" href="css/descargas.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <main>
    <header>
      <h1>Descargas Actividades</h1>
    </header>
    <section class="select-momento-container">
      <label for="select-momento">Selecciona un momento:</label>
      <select id="select-momento">
        <!-- Opciones insertadas por JS -->
      </select>
    </section>
    <section class="pruebasExcel">
      <section class="pruebasExcel">
        <table class="tabla-pruebas">
          <thead>
            <tr>
              <th>Actividad</th>
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
      cargarActividadesConDescarga
    } from "./js/controllers/c_obtenerActividades.js";
    import M_obtenerMomentos from "./js/models/m_obtenerMomentos.js";
    import {
      Loader
    } from "./js/utils/loader.js";

    document.addEventListener('DOMContentLoaded', async () => {
      const loader = new Loader('Cargando Momentos...');
      const select = document.getElementById("select-momento");

      try {
        const modelo = new M_obtenerMomentos();
        const momentos = await modelo.obtenerMomentos();

        // Agregar opción por defecto
        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "Todos los momentos";
        select.appendChild(defaultOption);

        // Filtrar momentos para excluir los que tengan idMomento === 0
        const momentosFiltrados = momentos.filter(m => m.idMomento !== 0);

        // Agregar momentos al select
        momentosFiltrados.forEach(momento => {
          const option = document.createElement("option");
          option.value = momento.idMomento;
          option.textContent = momento.nombre;
          select.appendChild(option);
        }); // Mostrar todas las actividades por defecto
        await cargarActividadesConDescarga();

        // Escuchar cambios en el select
        select.addEventListener("change", async (e) => {
          const idMomento = e.target.value || null; // null si está vacío

          const loaderActividades = new Loader('Cargando Actividades...');
          try {
            await cargarActividadesConDescarga(idMomento);
          } catch (error) {
            console.error("Error al cargar las actividades:", error);
          } finally {
            loaderActividades.ocultar();
          }
        });
      } catch (error) {
        console.error("Error al cargar los momentos:", error);
      } finally {
        loader.ocultar();
      }
    });
  </script>

  <a href="/InscripcionesEVG/views/menuDescargas.php" class="boton-volver">Volver</a>
</body>

</html>
