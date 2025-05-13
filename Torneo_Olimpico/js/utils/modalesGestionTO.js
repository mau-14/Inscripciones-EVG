const modal = document.getElementById("modal");
const modalConfirmacion = document.getElementById("modalConfirmacion");
const modalTitle = document.getElementById("modal-title");
const confirmTitle = document.getElementById("modalConfirmacion-title");

//EL QUERY SELECTOR SOLO ME COGE EL PRIMERO, O SEA EL MODAL DE EDITAR/AÑADIR
function abrirModal(tipo, idM, prueba, idF) {
	try {
		//Borrar los input anteriores
		modal.querySelectorAll('input[type="hidden"]').forEach((el) => el.remove());
		modalConfirmacion
			.querySelectorAll('input[type="hidden"]')
			.forEach((el) => el.remove());
		if (tipo === "borrar") {
			modalConfirmacion.style.display = "flex";
			modalConfirmacion.querySelector("#modalConfirmacion-title").textContent =
				"Eliminar Prueba";
			modalConfirmacion.querySelector("#modalConfirmacion-text").textContent =
				`¿Desea eliminar la prueba ${prueba.nombre}?`;
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
				const selectTipo = document.getElementById("tipoPrueba");
				selectTipo.value = prueba.tipo;
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
		modalConfirmacion.style.display = "none";
	} catch (error) {
		console.error("Error al cerrar el modal:", error);
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

document.querySelectorAll(".cancelar").forEach((btn) => {
	btn.addEventListener("click", function () {
		console.log("Cerrar modal");
		modalConfirmacion.style.display = "none";
		modal.style.display = "none";
	});
});

// si se usa type module las funciones no están disponibles globalmente y hay que usar window
window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;
