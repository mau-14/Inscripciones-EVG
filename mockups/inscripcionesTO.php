<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inscripciones Torneo Olímpico</title>
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/inscripcionesTO.css" rel="stylesheet" />
  <link href="css/footer.css" rel="stylesheet" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <script>
    window.addEventListener("scroll", function() {
      const imagen = document.querySelector(".ventana-imagen img");
      const scrolled = window.scrollY;
      imagen.style.transform = `translateY(${-scrolled * 0.3}px)`;
    });
  </script>
</head>

<?php
include 'entorno/variables.php';
include $navBar;

?>

<body>

  <main>
    <section id="secInscripcion">
      <h1>Panel de Inscripciones</h1>

      <form action="procesar_inscripcion.php" method="POST" class="formulario-inscripciones">
        <div class="categoria-container">
          <!-- Categoría Masculina -->
          <div class="categoria">
            <h2>Categoría Masculina</h2>
            <div class="grid-pruebas">
              <div class="campo">
                <label>50 metros</label>
                <select>
                  <option>High-speed</option>
                </select>
              </div>
              <div class="campo">
                <label>Jabalina</label>
                <select>
                  <option>Selecciona</option>
                </select>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
              <div class="campo">
                <label>800 metros</label>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
              <div class="campo">
                <label>Longitud</label>
                <select>
                  <option>Selecciona</option>
                </select>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>

              <div class="campo">
                <label>Peso</label>
                <select>
                  <option>Selecciona</option>
                </select>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
              <div class="campo">
                <label>100 metros</label>
                <select>
                  <option>Selecciona</option>
                </select>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
            </div>
          </div>
          <div class="linea-separadora"></div>
          <!-- Categoría Femenina -->
          <div class="categoria">
            <h2>Categoría Femenina</h2>
            <div class="grid-pruebas">
              <div class="campo">
                <label>50 metros</label>
                <select>
                  <option>Input prueba</option>
                </select>
              </div>
              <div class="campo">
                <label>Jabalina</label>
                <select>
                  <option>Selecciona</option>
                </select>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
              <div class="campo">
                <label>800 metros</label>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
              <div class="campo">
                <label>Longitud</label>
                <select>
                  <option>Selecciona</option>
                </select>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
              <div class="campo">
                <label>Peso</label>
                <select>
                  <option>Selecciona</option>
                </select>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
              <div class="campo">
                <label>100 metros</label>
                <select>
                  <option>Selecciona</option>
                </select>
                <select>
                  <option>Selecciona</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="categoriaMixta">
          <h2>Categoría Mixta</h2>
          <div class="campo">
            <label>4 * 100 metros</label>
            <select>
              <option>Selecciona</option>
            </select>
            <select>
              <option>Selecciona</option>
            </select>
            <select>
              <option>Selecciona</option>
            </select>
            <select>
              <option>Selecciona</option>
            </select>
          </div>
        </div>

        <!-- Botones -->
        <div class="botones-formulario">
          <button type="submit">Inscribir</button>
          <button type="reset">Limpiar</button>
        </div>
      </form>
    </section>
  </main>

  <?php include $footer; ?>

</body>

</html>
