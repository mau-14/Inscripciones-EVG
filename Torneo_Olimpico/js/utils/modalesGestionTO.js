const modal = document.getElementById("modal");
const modalConfirmacion = document.getElementById("modalConfirmacion");
const modalTitle = document.getElementById("modal-title");
const confirmTitle = document.getElementById("modalConfirmacion-title");

function abrirModal(tipo, id, prueba) {
	try {
		if (tipo === "borrar") {
			modalConfirmacion.style.display = "flex";
			confirmTitle.textContent = `¿Desea eliminar la prueba ${prueba.nombre}?`;
		} else {
			modal.style.display = "flex";
			if (tipo === "editar") {
				document.querySelector(".aceptar").setAttribute("data-tipo", "editar");
				modalTitle.textContent = "Editar Prueba";
				document.getElementById("nombrePrueba").value = prueba.nombre;
				document.getElementById("bases").value = prueba.bases;
				document.getElementById("fechaPrueba").value = prueba.fecha;
				document.getElementById("horaPrueba").value = prueba.hora;
				const selectMaxParticipantes =
					document.getElementById("maxParticipantes");
				selectMaxParticipantes.value = prueba.maxParticipantes;
			} else {
				document.querySelector(".aceptar").setAttribute("data-tipo", "añadir");
				modalTitle.textContent = "Añadir Prueba";
				document.getElementById("nombrePrueba").value = "";
				document.getElementById("bases").value = "";
				document.getElementById("fechaPrueba").value = "";
				document.getElementById("horaPrueba").value = "";
				document.getElementById("maxParticipantes").value = "";
			}
		}
	} catch (error) {
		console.error("Error al abrir el modal:", error);
	}
}

function cerrarModal() {
	try {
		modal.style.display = "none";
	} catch (error) {
		console.error("Error al cerrar el modal:", error);
	}
}

function cerrarModalConfirmacion() {
	try {
		modalConfirmacion.style.display = "none";
	} catch (error) {
		console.error("Error al cerrar el modal de confirmación:", error);
	}
}

window.onclick = function (e) {
	try {
		if (e.target === modal) cerrarModal();
		if (e.target === modalConfirmacion) cerrarModalConfirmacion();
	} catch (error) {
		console.error("Error al hacer clic en el modal:", error);
	}
};

// Botón "Cancelar" del moda
document.getElementById("btnCancelar").addEventListener("click", cerrarModal);

// si se usa type module las funciones no están disponibles globalmente y hay que usar window
window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;
