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
  <h1>Elige Momento</h1>
  <div class="formulario-momentos">
    <form action="index.php?controlador=actividades&accion=cMostrarActividadesporIdMomento" method="POST">
      <label for="momento">Elige Momento :</label>
      <select name="momento" id="momento">
          <?php foreach ($dataToView["data"] as $momento) { ?>
              <option value="<?php echo $momento['idMomento']; ?>"><?php echo $momento['nombre']; ?></option>
          <?php } ?>
      </select>
      <button type="submit">Enviar</button>
    </form>
  </div>
</body>
</html>