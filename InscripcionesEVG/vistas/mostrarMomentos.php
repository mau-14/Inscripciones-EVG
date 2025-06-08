<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gestión Momentos</title>
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
  <h1>Gestión Momentos</h1>
  <h2>Seleccione un Momento</h2>
  <!-- Leyenda de iconos explicativos -->
  <div class="leyenda-iconos">
    <strong>Leyenda de iconos:</strong>
    <span><i class="fas fa-bookmark"></i> Nombre del momento</span>
    <span><i class="fas fa-calendar-alt"></i> Fecha de inicio</span>
    <span><i class="fas fa-calendar-check"></i> Fecha de fin</span>
  </div>

  <div class="contenedor-momentos">
    <!--Lista de Momentos-->
    <?php foreach ($dataToView["data"] as $momento){ ?>
      <div class="carta">
        <div class="carta-header">
          <h3><i class="fas fa-bookmark"></i> <?php echo htmlspecialchars($momento['nombre']); ?></h3>
        </div>
        <div class="carta-body">
          <div class="dato-carta">
            <i class="fas fa-calendar-alt"></i>
            <?php
              if (!empty($momento['fecha_inicio'])) {
                  echo htmlspecialchars($momento['fecha_inicio']);
              } else {
                  echo '<em>Sin fecha inicio</em>';
              }
            ?>
          </div>
          <div class="dato-carta">
            <i class="fas fa-calendar-check"></i>
            <?php
              if (!empty($momento['fecha_fin'])) {
                  echo htmlspecialchars($momento['fecha_fin']);
              } else {
                  echo '<em>Sin fecha fin</em>';
              }
            ?>
          </div>
        </div>
        <div class="carta-acciones">
          <button class="editar" data-id="<?php echo $momento['idMomento']; ?>" data-nombre="<?php echo htmlspecialchars($momento['nombre']); ?>" data-fecha-inicio="<?php echo $momento['fecha_inicio']; ?>" data-fecha-fin="<?php echo $momento['fecha_fin']; ?>"><i class="fas fa-pen"></i></button>
          <?php if ($momento['idMomento']!= 0) { ?>
          <button class="eliminar" data-id="<?php echo $momento['idMomento']; ?>"><i class="fas fa-trash"></i></button>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
    <!-- Botón para abrir el formulario -->
    <div class="momento añadir" id="btnAbrirModal">
      <i class="fas fa-plus"></i>
      <p>Añadir Momento</p>
    </div>
  </div>

  <!-- Modal para añadir Momento -->
  <div class="modal" id="modal">
    <div class="modal-contenido">
      <span class="cerrar" id="btnCerrarModal">&times;</span>
      <h2>Nuevo Momento</h2>
      <form id="formMomento">
        <label for="nombre">Nombre del momento:</label>
        <input type="text" id="nombre" name="nombre">

        <label for="fechaInicio">Fecha de inicio:</label>
        <input type="date" id="fechaInicio" name="fechaInicio"/>

        <label for="fechaFin">Fecha de fin:</label>
        <input type="date" id="fechaFin" name="fechaFin"/>

        <button type="submit">Guardar</button>
      </form>
    </div>
  </div>

  <!-- Modal para editar momento -->
<div class="modal" id="modalEditar">
    <div class="modal-contenido">
      <span class="cerrar" id="btnCerrarEditar">&times;</span>
      <h2>Editar Momento</h2>
      <form id="formEditarMomento">
        <input type="hidden" id="editarId" name="idMomento">
        
        <label for="editarNombre">Nombre del momento:</label>
        <input type="text" id="editarNombre" name="nombre">

        <label for="editarFechaInicio">Fecha de inicio del momento:</label>
        <input type="date" id="editarFechaInicio" name="fechaInicio"/>

        <label for="editarFechaFin">Fecha de fin del momento:</label>
        <input type="date" id="editarFechaFin" name="fechaFin"/>

        <button type="submit">Actualizar</button>
      </form>

    </div>
  </div>
  
<!-- Modal de confirmación -->
<div class="modal" id="modalConfirmar">
  <div class="modal-contenido">
    <span class="cerrar" id="btnCerrarConfirmar">&times;</span>
    <h2>¿Estás seguro?</h2>
    <div class="botones-confirmacion">
      <a id="btnConfirmarEliminar" class="btn-confirmar">Sí</a>
      <button id="btnCancelarEliminar" class="btn-cancelar">No</button>
    </div>
  </div>
</div>


  <script src="<?php echo JS ?>modelos/momentosModel.js"></script>
  <script src="<?php echo JS ?>controladores/momentosController.js"></script>
  <script src="<?php echo JS ?>gestionMomentos.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      new MomentosController();
    });
  </script>
</body>
</html>