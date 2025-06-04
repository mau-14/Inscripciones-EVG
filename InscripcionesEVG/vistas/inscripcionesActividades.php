<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones Actividades</title>
    <link rel="stylesheet" href="<?php echo CSS ?>estilo.css">
    <link rel="stylesheet" href="<?php echo CSS ?>nav.css">
</head>
<body>
    <main>
        <h1>Inscripción Actividades</h1>
        
        <div class="reglas-container">
            <h2>NORMAS DEL PROCESO DE INSCRIPCIONES</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nunc, vitae aliquam nisl nunc vitae nisl. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nunc, vitae aliquam nisl nunc vitae nisl.</p>
        </div>

        <div class="contenedor-actividades">
            <?php 
            $actividadesC = [];
            $actividadesV = [];
            
            // Primero separamos las actividades por tipo
            foreach ($dataToView["data"] as $actividad) {
                if ($actividad['tipo'] === 'C') {
                    $actividadesC[] = $actividad;
                } elseif ($actividad['tipo'] === 'V') {
                    $actividadesV[] = $actividad;
                }
            }
            
            // Mostrar actividades de Clase (C)
            if (!empty($actividadesC)): ?>
                <div class="seccion-actividades">
                    <h2 class="seccion-titulo">Actividades de Clase</h2>
                    <div class="actividades-grid">
                        <?php foreach ($actividadesC as $actividad): ?>
                            <a href="index.php?controlador=inscripcionesActividades&accion=cInscripcionesClase&id=<?php echo $actividad['idActividad']?>" class="actividad-enlace">
                                <div class="actividad-card">
                                    <div class="actividad-contenido">
                                        <div class="actividad-nombre"><?php echo htmlspecialchars($actividad['nombre']); ?></div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php 
            // Mostrar actividades de Alumnos (V)
            if (!empty($actividadesV)): ?>
                <div class="seccion-actividades">
                    <h2 class="seccion-titulo">Actividades de Alumnos</h2>
                    <div class="actividades-grid">
                        <?php foreach ($actividadesV as $actividad): ?>
                            <a href="index.php?controlador=inscripcionesActividades&accion=cInscripcionesAlumnos&id=<?php echo $actividad['idActividad']?>" class="actividad-enlace">
                                <div class="actividad-card">
                                    <div class="actividad-contenido">
                                        <div class="actividad-nombre"><?php echo htmlspecialchars($actividad['nombre']); ?></div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif;
            
            if (empty($actividadesC) && empty($actividadesV)) {
                echo '<p>No hay actividades disponibles</p>';
            }
            ?>
        </div>
    </main>
</body>
</html>