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
  <div class="modal error-modal" id="modalError" style="display:none;">
    <div class="modal-contenido">
      <span class="cerrar" id="btnCerrarError">&times;</span>
      <h2>Error</h2>
      <p id="errorMsgText"></p>
    </div>
  </div>
  <h1>Gestión <?php echo htmlspecialchars($dataToView['data'][0]['nombre_momento']); ?></h1>
  <h2>Seleccione una Actividad</h2>
  <!-- Leyenda de iconos explicativos -->
  <div class="leyenda-iconos">
    <strong>Leyenda de iconos:</strong>
    <span><i class="fas fa-user-graduate"></i> Actividad de alumnos</span>
    <span><i class="fas fa-school"></i> Actividad de clase</span>
    <span><i class="fas fa-users"></i> Numero Maximo de Participantes</span>
    <span><i class="fas fa-calendar-alt"></i> Fecha </span>
    <span><i class="fas fa-clock"></i> Hora</span>
    <span><i class="fas fa-file-alt"></i> Bases</span>
  </div>
  <div class="contenedor-momentos">
     <!--Lista de Actividades-->
    <?php 
      foreach ($dataToView["data"] as $actividad) { ?>
        <div class="carta">
          <div class="carta-header">
            <h3><?php echo htmlspecialchars($actividad['nombre']); ?></h3>
            <span class="tipo-actividad tipo-<?php echo strtolower($actividad['tipo']); ?>">
              <?php if ($actividad['tipo'] === 'V') { ?>
                <i class="fas fa-user-graduate"></i>
              <?php } else { ?>
                <i class="fas fa-school"></i>
              <?php } ?>
            </span>
          </div>
          <div class="carta-body">
            <div class="dato-carta">
              <i class="fas fa-users"></i>
              <?php
                if (!empty($actividad['maxParticipantes'])) {
                    echo htmlspecialchars($actividad['maxParticipantes']);
                } else {
                    echo '<em>Sin participantes</em>';
                }
              ?>
            </div>
            <div class="dato-carta">
              <i class="fas fa-calendar-alt"></i>
              <?php
                if (!empty($actividad['fecha'])) {
                    echo htmlspecialchars($actividad['fecha']);
                } else {
                    echo '<em>Sin fecha</em>';
                }
              ?>
            </div>
            <div class="dato-carta">
              <i class="fas fa-clock"></i>
              <?php
                if (!empty($actividad['hora'])) {
                    echo htmlspecialchars($actividad['hora']);
                } else {
                    echo '<em>Sin hora</em>';
                }
              ?>
            </div>
            <div class="dato-carta">
              <i class="fas fa-file-alt"></i>
              <?php
                if (!empty($actividad['bases'])) {
                    echo htmlspecialchars($actividad['bases']);
                } else {
                    echo '<em>Sin bases</em>';
                }
              ?>
            </div>
          </div>
          <div class="carta-acciones">
            <button class="editar" 
                    data-id="<?php echo $actividad['idActividad']; ?>" 
                    data-nombre="<?php echo htmlspecialchars($actividad['nombre']); ?>"
                    data-max="<?php echo $actividad['maxParticipantes']; ?>"
                    data-fecha="<?php echo $actividad['fecha']; ?>"
                    data-hora="<?php echo $actividad['hora']; ?>"
                    data-bases="<?php echo htmlspecialchars($actividad['bases'] ?? ''); ?>">
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
        <input type="text" id="nombre" name="nombre">

        <label for="maxParticipantes">Número máximo de Participantes:</label>
        <input type="number" id="maxParticipantes" name="maxParticipantes" >

        

        <label for="tipo">Tipo de la Actividad:</label>
        <select id="tipo" name="tipo">
          <option value="" selected disabled>Seleccione un tipo</option>
          <option value="V">Alumnos</option>
          <option value="C">Clases</option>
        </select>

        <label for="fecha">Fecha de la actividad:</label>
        <input type="date" id="fecha" name="fecha"/>

        <label for="hora">Hora de la actividad:</label>
        <input type="time" id="hora" name="hora"/>

        <label for="bases">Bases de la Actividad:</label>
        <textarea id="bases" name="bases"></textarea>
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
        <input type="text" id="editarNombre" name="editarNombre">

        <label for="editarMaxParticipantes">Nº máximo de Participantes:</label>
        <input type="number" id="editarMaxParticipantes" name="editarMaxParticipantes">
        
        <label for="editarFecha">Fecha de la actividad:</label>
        <input type="date" id="editarFecha" name="editarFecha"/>

        <label for="editarHora">Hora de la actividad:</label>
        <input type="time" id="editarHora" name="editarHora"/>

        <label for="editarBases">Bases de la Actividad:</label>
        <textarea id="editarBases" name="editarBases"></textarea>

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
