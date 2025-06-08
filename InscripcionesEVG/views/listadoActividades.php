<?php
include $_SERVER['DOCUMENT_ROOT'] . '/InscripcionesEVG/config/entorno/variables.php';
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Actividades</title>
  <link rel="stylesheet" href="<?php echo CSS ?>estilo.css">
  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
</head>

<body>
  <h1>Listado de Actividades</h1>
  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Tipo</th>
        <th>Momento</th>
        <th>Bases</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($dataToView["data"])): ?>
        <tr>
          <td colspan="6" style="text-align: center; padding: 20px; font-style: italic; color: #666;">
            No hay actividades disponibles en este momento.
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($dataToView["data"] as $actividad): ?>
          <tr>
            <td><?= htmlspecialchars($actividad['nombre']) ?></td>
            <td><?= htmlspecialchars($actividad['fecha']) ?></td>
            <td><?= htmlspecialchars($actividad['hora']) ?></td>
            <td><?= $actividad['tipo'] === 'V' ? 'Alumnos' : 'Clase' ?></td>
            <td><?= htmlspecialchars($actividad['nombre_momento']) ?></td>
            <td><?= htmlspecialchars($actividad['bases']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="/InscripcionesEVG/index.php" class="boton-volver">Volver</a>
</body>

</html>
