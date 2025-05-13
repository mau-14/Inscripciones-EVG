document.getElementById("btnAbrirModal").addEventListener("click", function () {
    document.getElementById("modal").style.display = "block";
  });
  
  document.getElementById("btnCerrarModal").addEventListener("click", function () {
    document.getElementById("modal").style.display = "none";
  });
  
  const modalEditar = document.getElementById("modalEditar");
  const btnCerrarEditar = document.getElementById("btnCerrarEditar");
  
  document.querySelectorAll(".editar").forEach((boton) => {
    boton.addEventListener("click", () => {
      const momento = boton.closest(".momento");
      const nombre = momento.querySelector("p").textContent;
  
      // Asignar el valor del nombre al input del modal
      document.getElementById("editarNombre").value = nombre;
  
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
  
  document.querySelectorAll(".eliminar").forEach((boton) => {
    boton.addEventListener("click", () => {
      modalConfirmar.style.display = "block";
    });
  });
  
  btnCerrarConfirmar.addEventListener("click", () => {
    modalConfirmar.style.display = "none";
  });
  
  btnCancelarEliminar.addEventListener("click", () => {
    modalConfirmar.style.display = "none";
  });
  
  btnConfirmarEliminar.addEventListener("click", () => {
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
  