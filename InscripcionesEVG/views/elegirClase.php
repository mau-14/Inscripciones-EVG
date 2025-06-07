<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión Actividades</title>
  <link rel="stylesheet" href="<?php echo CSS ?>estilo.css">
  <base href="/InscripcionesEVG/assets/">
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <h1>Elige Clase</h1>
  <div class="formulario-momentos">
    <form action="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cInscripcionesAlumnosCoordinador" method="POST">
      <label for="clase">Elige Clase :</label>
      <input type="hidden" name="idActividad" value="<?php echo $_GET["id"]; ?>">
      <?php echo $_GET["id"]; ?>
      <select name="clase" id="clase">
        <?php foreach ($dataToView["data"] as $clase) { ?>
          <option value="<?php echo $clase['idClase']; ?>"><?php echo $clase['nombre']; ?></option>
        <?php } ?>
      </select>
      <button type="submit">Enviar</button>
    </form>
  </div>

  <a href="/InscripcionesEVG/index.php?controlador=inscripcionesActividades&accion=cMostrarActividades" class="boton-volver">Volver</a>
</body>

</html>
