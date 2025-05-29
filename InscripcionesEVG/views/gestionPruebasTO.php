<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
include $navBar;
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gestión de pruebas</title>

  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/gestionPruebasTO.css" rel="stylesheet" />
  <link href="css/footer.css" rel="stylesheet" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>



<script type="module">
  import {
    renderizarPruebas
  } from "./js/controllers/c_obtenerPruebas.js";

  await renderizarPruebas();
</script>

<body>
  <main>
    <h1>Panel gestión de pruebas</h1>
    <section class="grid">

    </section>
  </main>

  <!-- MODAL -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <h3 id="modal-title">Añadir Prueba</h3>
      <form id="modal-form">
        <div id="struct">
          <!-- Primer bloque: Nombre y Descripción -->
          <div>
            <div class="form-group">
              <label for="nombrePrueba">Nombre:</label>
              <input type="text" id="nombrePrueba" placeholder="Nombre de la prueba" required />
            </div>

            <div class="form-group">
              <label for="bases">Descripción:</label>
              <textarea rows="3" placeholder="Detalles..." id="bases"></textarea>
            </div>
          </div>

          <!-- Segundo bloque: Participantes, Fecha y Hora -->
          <div class="inputEspeciales">
            <div class="form-group">
              <label for="maxParticipantes">Participantes:</label>
              <select id="maxParticipantes">
                <option value="0" selected>0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
              </select>
            </div>

            <div class="form-group">
              <label for="fechaPrueba">Fecha:</label>
              <input type="date" id="fechaPrueba" />
            </div>

            <div class="form-group">
              <label for="horaPrueba">Hora:</label>
              <input type="time" id="horaPrueba" />
            </div>
          </div>

          <!-- Loader -->
          <div id="loader-modal" class="loader-modal" style="display: none;">
            <div class="loader-content">
              <div class="spinner"></div>
              <span class="spinner-text">Enviando datos...</span>
            </div>
          </div>
        </div>

        <!-- Errores -->
        <span id="error-mensajes" class="error"></span>

        <!-- Botones -->
        <div class="botones">
          <button type="submit" class="aceptarGP" id="aceptar">Aceptar</button>
          <button type="button" class="cancelarGP" id="btnCancelar">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
  <!-- MODAL BORRAR -->
  <div class="modal" id="modalConfirmacion">
    <div class="modal-content">
      <h3 id="modalConfirmacion-title">Eliminar Prueba</h3>
      <p id="modalConfirmacion-text">¿Estás seguro que quieres eliminarla?</p>
      <div class="botones">
        <button type="submit" class="aceptarGP" id="btnConfirmar">Borrar</button>
        <button class="cancelarGP" id="btnCancelar">Cancelar</button>
      </div>
    </div>
  </div>

  <a href="/InscripcionesEVG/views/menuGestiones.php" class="boton-volver">Volver</a>
  <script src="js/utils/modalesGestionTO.js" defer></script>
  <script type="module" src="js/controllers/c_crudPruebasTO.js" defer></script>
</body>

</html>
