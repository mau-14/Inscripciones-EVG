// Modal de añadir momento
document.getElementById("btnAbrirModal").addEventListener("click", function () {
  document.getElementById("modal").style.display = "block";
});

document.getElementById("btnCerrarModal").addEventListener("click", function () {
  document.getElementById("modal").style.display = "none";
});


// Modal de editar momento
const modalEditar = document.getElementById("modalEditar");
const btnCerrarEditar = document.getElementById("btnCerrarEditar");

document.querySelectorAll(".editar").forEach((boton) => {
  boton.addEventListener("click", () => {
    // Extraer datos desde los atributos data-*
    const id = boton.dataset.id;
    const nombre = boton.dataset.nombre;
    const fechaInicio = boton.dataset.fechainicio;
    const fechaFin = boton.dataset.fechafin;

    // Asignar los valores a los inputs del formulario
    document.getElementById("editarId").value = id;
    document.getElementById("editarNombre").value = nombre;
    document.getElementById("editarFechaInicio").value = fechaInicio;
    document.getElementById("editarFechaFin").value = fechaFin;

    // Mostrar modal
    document.getElementById("modalEditar").style.display = "block";
  });
});


// Modal de confirmación
const modalConfirmar = document.getElementById("modalConfirmar");
const btnCerrarConfirmar = document.getElementById("btnCerrarConfirmar");
const btnCancelarEliminar = document.getElementById("btnCancelarEliminar");
const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");

// Mostrar el modal de confirmación al hacer clic en el botón de eliminar
document.querySelectorAll(".eliminar").forEach((boton) => {
  boton.addEventListener("click", () => {
    const idMomento = boton.dataset.id;
    const modalConfirmar = document.getElementById("modalConfirmar");
    const confirmarBtn = document.getElementById("btnConfirmarEliminar");

    // Establece el enlace de confirmación con el ID correspondiente
    confirmarBtn.href = `./index.php?controlador=momentos&accion=cEliminarMomento&idMomento=${idMomento}`;

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

// Cierra el modal si se hace clic fuera del contenido del modal
window.addEventListener("click", (e) => {
  const modal = document.getElementById("modalConfirmar");
  if (e.target === modal) {
    modal.style.display = "none";
  }
});

btnCerrarConfirmar.addEventListener("click", () => {
  modalConfirmar.style.display = "none";
});

btnCancelarEliminar.addEventListener("click", () => {
  modalConfirmar.style.display = "none";
});

btnConfirmarEliminar.addEventListener("click", () => {
  // Aquí podrías eliminar el momento en el futuro
  modalConfirmar.style.display = "none";
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