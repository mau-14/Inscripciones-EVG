<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gestión de pruebas</title>
  <link href="css/navbar.css" rel="stylesheet" />
  <link href="css/general.css" rel="stylesheet" />
  <link href="css/gestionPruebasTO.css" rel="stylesheet" />
  <link href="css/footer.css" rel="stylesheet" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<?php
include 'entorno/variables.php';
include $navBar;

?>

<body>
  <main>
    <h1>Seleccione una prueba</h1>
    <section class="grid">
      <div class="prueba">
        <h3>50 metros</h3>
        <p><strong>Fecha:</strong> 15/05/2025</p>
        <p><strong>Hora:</strong> 10:00</p>
        <p><strong>Descripción:</strong> Carrera de velocidad individual.</p>
        <div class="acciones">
          <button onclick="abrirModal('editar')">✏️</button>
          <button onclick="abrirModal('borrar')">🗑️</button>
        </div>
      </div>

      <div class="prueba">
        <h3>800 metros</h3>
        <p><strong>Fecha:</strong> 15/05/2025</p>
        <p><strong>Hora:</strong> 11:00</p>
        <p><strong>Descripción:</strong> Prueba de resistencia de media distancia.</p>
        <div class="acciones">
          <button onclick="abrirModal('editar')">✏️</button>
          <button onclick="abrirModal('borrar')">🗑️</button>
        </div>
      </div>

      <div class="prueba">
        <h3>4 × 100 metros</h3>
        <p><strong>Fecha:</strong> 15/05/2025</p>
        <p><strong>Hora:</strong> 12:00</p>
        <p><strong>Descripción:</strong> Carrera de relevos por equipos.</p>
        <div class="acciones">
          <button onclick="abrirModal('editar')">✏️</button>
          <button onclick="abrirModal('borrar')">🗑️</button>
        </div>
      </div>

      <div class="prueba">
        <h3>Peso</h3>
        <p><strong>Fecha:</strong> 16/05/2025</p>
        <p><strong>Hora:</strong> 09:30</p>
        <p><strong>Descripción:</strong> Lanzamiento de peso con técnica de rotación.</p>
        <div class="acciones">
          <button onclick="abrirModal('editar')">✏️</button>
          <button onclick="abrirModal('borrar')">🗑️</button>
        </div>
      </div>

      <div class="prueba">
        <h3>Jabalina</h3>
        <p><strong>Fecha:</strong> 16/05/2025</p>
        <p><strong>Hora:</strong> 10:45</p>
        <p><strong>Descripción:</strong> Lanzamiento de jabalina con técnica libre.</p>
        <div class="acciones">
          <button onclick="abrirModal('editar')">✏️</button>
          <button onclick="abrirModal('borrar')">🗑️</button>
        </div>
      </div>

      <div class="prueba">
        <h3>Longitud</h3>
        <p><strong>Fecha:</strong> 16/05/2025</p>
        <p><strong>Hora:</strong> 12:00</p>
        <p><strong>Descripción:</strong> Salto de longitud desde zona delimitada.</p>
        <div class="acciones">
          <button onclick="abrirModal('editar')">✏️</button>
          <button onclick="abrirModal('borrar')">🗑️</button>
        </div>
      </div>

      <div class="prueba">
        <h3>Añadir una prueba</h3>
        <button class="btn-mas" onclick="abrirModal('añadir')">➕</button>
      </div>
    </section>
  </main>
  <!-- MODAL -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <h3 id="modal-title">Añadir Prueba</h3>
      <form id="modal-form">
        <label>Nombre:
          <input type="text" placeholder="Nombre de la prueba" required />
        </label>
        <label>Descripción y normas:
          <textarea rows="3" placeholder="Detalles..."></textarea>
        </label>
        <div class="inputEspeciales">
          <label>Participantes:
            <select>
              <option selected>0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="4">4</option>
              <option value="8">8</option>
            </select>
          </label>
          <label>Fecha:
            <input type="date" />
          </label>
          <label>Hora:
            <input type="time" />
          </label>
        </div>
        <div class="botones">
          <button type="submit" class="aceptar">Aceptar</button>
          <button type="button" class="cancelar" onclick="cerrarModal()">
            Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL BORRAR-->
  <div class="modal" id="modalConfirmacion">
    <div class="modal-content">
      <h3>Eliminar Prueba</h3>
      <p>¿Estás seguro que quieres eliminarla?</p>
      <div class="botones">
        <button class="aceptar" id="btnConfirmar">Sí, borrar</button>
        <button class="cancelar" id="btnCancelar">Cancelar</button>
      </div>
    </div>
  </div>

  <script>
    const modal = document.getElementById("modal");
    const modalConfirmacion = document.getElementById("modalConfirmacion");
    const modalTitle = document.getElementById("modal-title");
    const confirmTitle = document.getElementById("modalConfirmacion-title");

    function abrirModal(tipo) {
      if (tipo === "borrar") {
        modalConfirmacion.style.display = "flex";
        confirmTitle.textContent = "¿Desea eliminar esta prueba?";
      } else {
        modal.style.display = "flex";
        if (tipo === "editar") {
          modalTitle.textContent = "Editar Prueba";
        } else {
          modalTitle.textContent = "Añadir Prueba";
        }
      }
    }

    function cerrarModal() {
      modal.style.display = "none";
    }

    function cerrarModalConfirmacion() {
      modalConfirmacion.style.display = "none";
    }

    // Cerrar si hacen clic fuera del contenido de cada modal
    window.onclick = function(e) {
      if (e.target === modal) cerrarModal();
      if (e.target === modalConfirmacion) cerrarModalConfirmacion();
    };

    // Botón "Cancelar" del modal de confirmación
    document
      .getElementById("btnCancelar")
      .addEventListener("click", cerrarModalConfirmacion);
  </script>


  <?php include $footer; ?>

</body>

</html>
