// Referencias a los elementos modales del DOM
const modal = document.getElementById("modal");
const modalConfirmacion = document.getElementById("modalConfirmacion");
const modalTitle = document.getElementById("modal-title");
const confirmTitle = document.getElementById("modalConfirmacion-title");

/**
 * Abre un modal dependiendo del tipo: editar, añadir o borrar.
 *
 * @param {"añadir" | "editar" | "borrar"} tipo - Tipo de acción a realizar.
 * @param {string | number} idM - ID masculino de la prueba.
 * @param {Object} prueba - Objeto con los datos de la prueba.
 * @param {string | number} idF - ID femenino de la prueba.
 */
function abrirModal(tipo, idM, prueba, idF) {
	try {
		// Eliminar inputs ocultos anteriores (para evitar duplicados)
		modal.querySelectorAll('input[type="hidden"]').forEach((el) => el.remove());
		modalConfirmacion
			.querySelectorAll('input[type="hidden"]')
			.forEach((el) => el.remove());

		if (tipo === "borrar") {
			// Mostrar el modal de confirmación para eliminar
			modalConfirmacion.style.display = "flex";
			modalConfirmacion.querySelector("#modalConfirmacion-title").textContent =
				"Eliminar Prueba";
			modalConfirmacion.querySelector("#modalConfirmacion-text").textContent =
				`¿Desea eliminar la prueba ${prueba.nombre}?`;

			// Crear inputs ocultos con los IDs necesarios
			const hiddenIdM = document.createElement("input");
			hiddenIdM.type = "hidden";
			hiddenIdM.id = "idPruebaM";
			hiddenIdM.name = "idPruebaM";
			hiddenIdM.value = idM;

			const hiddenIdF = document.createElement("input");
			hiddenIdF.type = "hidden";
			hiddenIdF.id = "idPruebaF";
			hiddenIdF.name = "idPruebaF";
			hiddenIdF.value = idF;

			modalConfirmacion.appendChild(hiddenIdM);
			modalConfirmacion.appendChild(hiddenIdF);
		} else {
			// Mostrar el modal para añadir o editar
			modal.style.display = "flex";
			const aceptarBtn = document.querySelector(".aceptar");

			if (tipo === "editar") {
				aceptarBtn.setAttribute("data-tipo", "editar");
				modalTitle.textContent = "Editar Prueba";

				// Rellenar campos del formulario con los datos de la prueba
				document.getElementById("nombrePrueba").value = prueba.nombre;
				document.getElementById("bases").value = prueba.bases;
				document.getElementById("fechaPrueba").value = prueba.fecha;
				document.getElementById("horaPrueba").value = prueba.hora;
				document.getElementById("maxParticipantes").value =
					prueba.maxParticipantes;
				document.getElementById("tipoPrueba").value = prueba.tipo;

				// Añadir inputs ocultos con los IDs
				const hiddenIdM = document.createElement("input");
				hiddenIdM.type = "hidden";
				hiddenIdM.id = "idPruebaM";
				hiddenIdM.name = "idPruebaM";
				hiddenIdM.value = idM;

				const hiddenIdF = document.createElement("input");
				hiddenIdF.type = "hidden";
				hiddenIdF.id = "idPruebaF";
				hiddenIdF.name = "idPruebaF";
				hiddenIdF.value = idF;

				modal.appendChild(hiddenIdM);
				modal.appendChild(hiddenIdF);
			} else {
				// Para añadir, limpiar los campos
				aceptarBtn.setAttribute("data-tipo", "añadir");
				modalTitle.textContent = "Añadir Prueba";

				document.getElementById("nombrePrueba").value = "";
				document.getElementById("bases").value = "";
				document.getElementById("fechaPrueba").value = "";
				document.getElementById("horaPrueba").value = "";
				document.getElementById("maxParticipantes").value = "";
				document.getElementById("tipoPrueba").value = "";
			}
		}
	} catch (error) {
		console.error("Error al abrir el modal:", error);
	}
}

/**
 * Cierra ambos modales (editar/añadir y confirmación).
 */
function cerrarModal() {
	try {
		modal.style.display = "none";
		modalConfirmacion.style.display = "none";
	} catch (error) {
		console.error("Error al cerrar el modal:", error);
	}
}

/**
 * Cierra el modal si se hace clic fuera del contenido del modal.
 * @param {MouseEvent} e - Evento del clic.
 */
window.onclick = function (e) {
	try {
		if (e.target === modal) cerrarModal();
		if (e.target === modalConfirmacion) cerrarModal();
	} catch (error) {
		console.error("Error al hacer clic en el modal:", error);
	}
};

// Asignar evento a todos los botones con clase "cancelar"
document.querySelectorAll(".cancelar").forEach((btn) => {
	btn.addEventListener("click", function () {
		console.log("Cerrar modal");
		modalConfirmacion.style.display = "none";
		modal.style.display = "none";
	});
});

// Hacer que las funciones estén disponibles globalmente si se usa type="module"
window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;
