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
  <h1>Gestión Actividades</h1>
  <h2>Seleccione una Actividad</h2>

  <div class="contenedor-momentos">
     <!--Lista de Actividades-->
    <?php 
      foreach ($dataToView["data"] as $actividad) { ?>
        <div class="momento">
          <p><?php echo $actividad['nombre']; ?></p>
          <div class="acciones">
            <button class="editar" data-id="<?php echo $actividad['idActividad']; ?>" 
                    data-nombre="<?php echo $actividad['nombre']; ?>"
                    data-max="<?php echo $actividad['maxParticipantes']; ?>"
                    data-fecha="<?php echo $actividad['fecha']; ?>"
                    data-hora="<?php echo $actividad['hora']; ?>">
              <i class="fas fa-pen"></i>
            </button>
            <button class="eliminar" data-id="<?php echo $actividad['idActividad']; ?>"><i class="fas fa-trash"></i></button>
          </div>
        </div>
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
      <h2>Nueva Actividad</h2>
      <form id="formActividad" action="./index.php?controlador=actividades&accion=cInsertarActividad" method="POST">
        <input type="hidden" name="idMomento" value="<?php echo isset($_POST['momento']) ? $_POST['momento'] : $_GET['momento']; ?>">
        
        <label for="nombre">Nombre de la Actividad:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="maxParticipantes">Número máximo de Participantes:</label>
        <input type="number" id="maxParticipantes" name="maxParticipantes" required>

        <label for="fecha">Fecha de la actividad:</label>
        <input type="date" id="fecha" name="fecha" required/>

        <label for="hora">Hora de la actividad:</label>
        <input type="time" id="hora" name="hora" required/>

        <button type="submit">Guardar</button>
      </form>
    </div>
  </div>

  <!-- Modal para editar Actividad -->
  <div class="modal" id="modalEditar">
    <div class="modal-contenido">
      <span class="cerrar" id="btnCerrarEditar">&times;</span>
      <h2>Editar Actividad</h2>
      <form id="formEditarActividad" action="./index.php?controlador=actividades&accion=cEditarActividad" method="POST">
        <input type="hidden" name="idActividad" id="editarId">
        <input type="hidden" name="idMomento" value="<?php echo isset($_POST['momento']) ? $_POST['momento'] : $_GET['momento']; ?>">
        
        <label for="editarNombre">Nombre de la actividad:</label>
        <input type="text" id="editarNombre" name="editarNombre" required>

        <label for="editarMaxParticipantes">Nº máximo de Participantes:</label>
        <input type="number" id="editarMaxParticipantes" name="editarMaxParticipantes" required>
        
        <label for="editarFecha">Fecha de la actividad:</label>
        <input type="date" id="editarFecha" name="editarFecha" required/>
        
        <label for="editarHora">Hora de la actividad:</label>
        <input type="time" id="editarHora" name="editarHora" required/>
  
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

  <a href="index.php?controlador=momentos&accion=cMostrarMomentosActividades"><button class="volver">Volver</button></a>

  <script src="<?php echo JS ?>gestionActividades.js"></script>
</body>
</html>
