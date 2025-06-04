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
                <input type="hidden" name="idActividad" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
                <?php if (isset($dataToView["data"]['alumnos'])): ?>
                <input type="hidden" name="alumnos_data" value='<?php echo htmlspecialchars(json_encode($dataToView["data"]['alumnos']), ENT_QUOTES, 'UTF-8'); ?>'>
                <?php endif; ?>
                
                <?php if (isset($dataToView["data"]['alumnos'])): ?>
                    <?php 
                    // Obtener lista de nombres de alumnos inscritos
                    $nombresInscritos = $dataToView['data']['inscritos'] ?? [];
                    
                    // Si hay alumnos inscritos, mostrarlos primero
                    if (!empty($nombresInscritos)) {
                        foreach ($nombresInscritos as $index => $nombreInscrito): 
                            $alumnoInscrito = current(array_filter($dataToView["data"]['alumnos'], 
                                function($alumno) use ($nombreInscrito) {
                                    return $alumno['nombre'] === $nombreInscrito;
                                }
                            ));
                            ?>
                            <div class="form-group select-container" id="container_<?php echo $index; ?>">
                                <div class="select-wrapper">
                                    <label for="alumno_<?php echo $index; ?>">
                                        <span class="badge"><?php echo ($index + 1); ?></span>
                                        Alumno
                                    </label>
                                    <select id="alumno_<?php echo $index; ?>" name="alumnos[]" class="form-control">
                                        <?php foreach ($dataToView["data"]['alumnos'] as $alumno): ?>
                                            <option value="<?php echo $alumno['idAlumno']; ?>" 
                                                <?php echo ($alumno['nombre'] === $nombreInscrito) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($alumno['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if ($index > -1): ?>
                                        <button type="button" class="btn-remove" onclick="removeSelect(this)" title="Eliminar alumno">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; 
                    } 
                    
                    // Agregar un select vacío para nuevo alumno
                    ?>
                    
                <?php endif; ?>
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