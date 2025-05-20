<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu Principal</title>
  <link rel="stylesheet" href="<?php echo CSS ?>estilo.css">
   <link rel="stylesheet" href="<?php echo CSS ?>nav.css">
</head>
<body>
  <main>
    <h1>Menu Principal</h1>

    <section class="section">
        
      <div class="card">
        <h3>Momentos</h3>
        <p>Gestiona los momentos del torneo olímpico y de las actividades.</p>
        <a href="index.php?controlador=momentos&accion=cMostrarMomentos"><button>Acceder</button></a>
      </div>

      <div class="card">
        <h3>Torneo Olímpico</h3>
        <p>Gestiona Pruebas del Torneo Olímpico.</p>
        <button>Acceder</button>
      </div>

    </section>

    <section class="section">
      <div class="card">
        <h3>Alumno</h3>
        <p>Gestiona Actividades de Alumnos.</p>
        <a href="index.php?controlador=momentos&accion=cMostrarMomentosActividades"><button>Acceder</button></a>
      </div>
      <div class="card">
        <h3>De Clase</h3>
        <p>Gestiona Actividades de Clases.</p>
        <button>Acceder</button>
      </div>
    </section>

  </main>
</body>
</html>
