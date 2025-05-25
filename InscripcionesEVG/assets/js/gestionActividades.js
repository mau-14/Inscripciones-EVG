// Modal de añadir actividad
document.getElementById("btnAbrirModal").addEventListener("click", function () {
  document.getElementById("modal").style.display = "block";
});

document.getElementById("btnCerrarModal").addEventListener("click", function () {
  document.getElementById("modal").style.display = "none";
});

// Modal de editar actividad
const modalEditar = document.getElementById("modalEditar");
const btnCerrarEditar = document.getElementById("btnCerrarEditar");

document.querySelectorAll(".editar").forEach((boton) => {
  boton.addEventListener("click", () => {
    const id = boton.dataset.id;
    const nombre = boton.dataset.nombre;
    const maxParticipantes = boton.dataset.max;
    const fecha = boton.dataset.fecha;
    const hora = boton.dataset.hora;
    const bases = boton.dataset.bases || '';

    // Asignar los valores a los inputs del formulario
    document.getElementById("editarId").value = id;
    document.getElementById("editarNombre").value = nombre;
    document.getElementById("editarMaxParticipantes").value = maxParticipantes;
    document.getElementById("editarFecha").value = fecha;
    document.getElementById("editarHora").value = hora;
    document.getElementById("editarBases").value = bases;

    // Mostrar modal
    modalEditar.style.display = "block";
  });
});

btnCerrarEditar.addEventListener("click", () => {
  modalEditar.style.display = "none";
});

// Modal de confirmación
const modalConfirmar = document.getElementById("modalConfirmar");
const btnCerrarConfirmar = document.getElementById("btnCerrarConfirmar");
const btnCancelarEliminar = document.getElementById("btnCancelarEliminar");
const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");

// Mostrar el modal de confirmación al hacer clic en el botón de eliminar
document.querySelectorAll(".eliminar").forEach((boton) => {
  boton.addEventListener("click", () => {
    const idActividad = boton.getAttribute('data-id');
    const modalConfirmar = document.getElementById("modalConfirmar");
    const confirmarBtn = document.getElementById("btnConfirmarEliminar");

    // Configurar el botón de confirmación
    confirmarBtn.onclick = function() {
      window.location.href = `./index.php?controlador=actividades&accion=cEliminarActividad&idActividad=${idActividad}`;
    };

    // Muestra el modal
    modalConfirmar.style.display = "block";
  });
});

// Cierra el modal al hacer clic en la X
document.getElementById("btnCerrarConfirmar").addEventListener("click", () => {
  document.getElementById("modalConfirmar").style.display = "none";
});

// Cierra el modal al hacer clic en "No"
document.getElementById("btnCancelarEliminar").addEventListener("click", () => {
  document.getElementById("modalConfirmar").style.display = "none";
});

// Cerrar modales al hacer clic fuera
window.addEventListener("click", function (event) {
  if (event.target === document.getElementById("modal")) {
    document.getElementById("modal").style.display = "none";
  }
  if (event.target === modalEditar) {
    modalEditar.style.display = "none";
  }
  if (event.target === modalConfirmar) {
    modalConfirmar.style.display = "none";
  }
});

window.addEventListener('DOMContentLoaded', function() {
  const params = new URLSearchParams(window.location.search);
  const errorMsg = params.get('errorMsg');
  if (errorMsg && document.getElementById('modalError')) {
    document.getElementById('errorMsgText').textContent = decodeURIComponent(errorMsg.replace(/\+/g, ' '));
    document.getElementById('modalError').style.display = 'block';
  }
  const btnCerrar = document.getElementById('btnCerrarError');
  if (btnCerrar) {
    btnCerrar.onclick = function() {
      document.getElementById('modalError').style.display = 'none';
    }
  }
}); 