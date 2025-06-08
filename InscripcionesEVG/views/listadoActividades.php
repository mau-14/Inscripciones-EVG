<!DOCTYPE html>
<html lang="en">
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
                <th>Bases</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataToView["data"] as $actividad): ?>
            <tr>
                <td><?= $actividad['nombre'] ?></td>
                <td><?= $actividad['fecha'] ?></td>
                <td><?= $actividad['hora'] ?></td>
                <td><?= $actividad['tipo'] === 'V' ? 'Alumnos' : 'Clase' ?></td>
                <td><?= $actividad['bases'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>