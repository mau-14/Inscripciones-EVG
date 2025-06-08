<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscripción de Clase</title>
  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/estilo.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
  <main>
    <h1>Inscripción de Clase</h1>
    <?php if ($_SESSION['usuario'] === 'Coordinador') { ?>
    <form action="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cInscribirClase" method="POST" class="form-container">
      <div class="selects-grid">
        <div class="form-group">
          <label for="idClase">
            <span class="badge">1</span>
            Seleccionar clase
          </label>
          <input type="hidden" name="idActividad" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
          <select id="idClase" name="idClase" class="form-control">
            <option value="" disabled selected>Seleccione una clase</option>
            <?php foreach ($dataToView["data"] as $clase): ?>
              <option value="<?php echo $clase['idClase']; ?>">
                <?php echo htmlspecialchars($clase['nombre']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button type="button" class="btn btn-cancel" onclick="window.history.back()">
          <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Guardar Inscripción
        </button>
      </div>
    </form>
    <?php } ?>
    <?php if ($_SESSION['usuario'] === 'Tutor') { ?>

    <a href="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cInscribirClaseTutor&idActividad=<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>&idClase=1" class="actividad-enlace">
      <button type="button" class="btn btn-primary">
        <i class="fas fa-plus"></i> Inscribir Clase
      </button>
    </a>
    <a href="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cCancelarInscripcionClaseTutor&idActividad=<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>&idClase=1" class="actividad-enlace">
      <button type="button" class="btn btn-primary">
        <i class="fas fa-times"></i> Cancelar Inscripción Clase
      </button>
    </a>
    <a href="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cMostrarActividades" class="actividad-enlace">
      <button type="button" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Volver
      </button>
    </a>
    <?php } ?>
  </main>
</body>

</html>
