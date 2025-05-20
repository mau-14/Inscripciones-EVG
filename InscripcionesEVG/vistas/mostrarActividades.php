<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestión Actividades</title>
  <link rel="stylesheet" href="<?php echo CSS ?>estilo.css">
  <link rel="stylesheet" href="<?php echo CSS ?>nav.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <h1>Actividades</h1>
  <h2>Seleccione una Actividad</h2>

  <div class="contenedor-momentos">
     <!--Lista de Actividades-->
    <?php 
    if (!empty($dataToView["data"])) {
      foreach ($dataToView["data"] as $actividad) { ?>
        <div class="momento">
          <p><?php echo $actividad['nombre']; ?></p>
          <div class="acciones">
            <button class="editar" data-id="<?php echo $actividad['idActividad']; ?>"><i class="fas fa-pen"></i></button>
            <button class="eliminar" data-id="<?php echo $actividad['idActividad']; ?>"><i class="fas fa-trash"></i></button>
          </div>
        </div>
      <?php }
    } else { ?>
      <p>No hay actividades disponibles para este momento.</p>
    <?php } ?>

    <!-- Botón para abrir el formulario -->
    <div class="momento añadir" id="btnAbrirModal">
      <i class="fas fa-plus"></i>
      <p>Añadir Actividad</p>
    </div>
  </div>

  <!-- Modal para añadir Actvidad -->
  <div class="modal" id="modal">
    <div class="modal-contenido">
      <span class="cerrar" id="btnCerrarModal">&times;</span>
      <h2>Nuevo Actividad</h2>
      <form id="formMomento">
        <label for="nombre">Nombre de la Actividad:</label>
        <input type="text" id="nombre" name="nombre">

        <label for="NmaximoParticipantes">Numero maximo de Participantes:</label>
        <input type="number" id="NmaximoParticipantes" name="NmaximoParticipantes">

        <label for="fechaInicio">Fecha y hora donde se lleva a cabo la actividad:</label>
        <input type="date" id="fechaInicio" name="fechaInicio"/>
        <input type="time" id="fechaFin" name="fechaFin"/>

        <button type="submit">Guardar</button>
      </form>
    </div>
  </div>
  <a href="./index.php?controlador=momentos&accion=cMostrarMomentos"><button class="volver">Volver</button></a>

  <!-- Modal para editar Actividad -->
<div class="modal" id="modalEditar">
    <div class="modal-contenido">
      <span class="cerrar" id="btnCerrarEditar">&times;</span>
      <h2>Editar Actividad</h2>
      <form id="formEditarMomento">
        <label for="editarNombre">Nombre de la actividad:</label>
        <input type="text" id="editarNombre" name="editarNombre">

        <label for="editarNmaximoParticipantes">Nº maximo de Participantes:</label>
        <input type="number" id="editarNmaximoParticipantes" name="editarNmaximoParticipantes">
        
        <label for="editarFechaInicio">Fecha y hora donde se lleva a cabo la actividad:</label>
        <input type="date" id="editarFechaInicio" name="editarFechaInicio"/>
        <input type="time" id="editarFechaFin" name="editarFechaFin"/>
  
        <button type="submit">Actualizar</button>
      </form>
    </div>
  </div>
  
  <!-- Modal de confirmación para eliminar -->
<div class="modal" id="modalConfirmar">
    <div class="modal-contenido">
      <span class="cerrar" id="btnCerrarConfirmar">&times;</span>
      <h2>¿Estás seguro?</h2>
      <div class="botones-confirmacion">
        <button id="btnConfirmarEliminar" class="btn-confirmar">Sí</button>
        <button id="btnCancelarEliminar" class="btn-cancelar">No</button>
      </div>
    </div>
  </div>

  <script src="js/gestionActividades.js"></script>
</body>
</html>
