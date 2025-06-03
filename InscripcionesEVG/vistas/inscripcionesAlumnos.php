<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción de Actividad</title>
    <link rel="stylesheet" href="<?php echo CSS; ?>nav.css">
    <link rel="stylesheet" href="<?php echo CSS; ?>estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <main>
        <h1>Inscripción de Actividad</h1>
        <form action="index.php?controlador=inscripcionesActividades&accion=cInscribirAlumnos" method="POST" class="form-container">
            <div class="selects-grid">
                <div class="form-group">
                    <label for="alumno_1">
                        <span class="badge">1</span>
                        Seleccionar alumno
                    </label>
                    <input type="hidden" name="idActividad" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                    <select id="alumno_1" name="alumnos[]" class="form-control">
                        <option value="" disabled selected>Seleccione un alumno</option>
                        <?php foreach ($dataToView["data"] as $alumno): ?>
                            <option value="<?php echo $alumno['idAlumno']; ?>">
                                <?php echo htmlspecialchars($alumno['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="botones-container">
                <button type="button" class="btn btn-cancel" onclick="window.history.back()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Inscribir Participantes
                </button>
            </div>
        </form>
        <script src="<?php echo JS ?>inscripciones.js"></script>
    </main>
</body>
</html>