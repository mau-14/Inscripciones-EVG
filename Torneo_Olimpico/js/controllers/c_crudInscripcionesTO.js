import M_crudInscripcionesTO from "/Torneo_Olimpico/js/models/m_crudInscripcionesTO.js";
import { renderizarPruebas } from "/Torneo_Olimpico/js/controllers/c_obtenerPruebas.js";
import { ErrorDialog } from "/Torneo_Olimpico/js/utils/errorHandler.js";

const errorDialog = new ErrorDialog();

document
	.querySelector(".aceptar")
	.addEventListener("click", async function (event) {
		event.preventDefault();

		// Recoger los datos del formulario
		const nombrePrueba = document.getElementById("nombrePrueba").value;
		const bases = document.getElementById("bases").value;
		const maxParticipantes = document.getElementById("maxParticipantes").value;
		const fechaPrueba = document.getElementById("fechaPrueba").value;
		const horaPrueba = document.getElementById("horaPrueba").value;

		if (
			!nombrePrueba ||
			!bases ||
			!maxParticipantes ||
			maxParticipantes === "0" ||
			!fechaPrueba ||
			!horaPrueba
		) {
			errorDialog.show("Faltan campos por rellenar.");
			return;
		}

		mostrarLoaderModal();
		// Crear un objeto con los datos del formulario
		const prueba = {
			nombre: nombrePrueba,
			bases: bases,
			maxParticipantes: maxParticipantes,
			fecha: fechaPrueba,
			hora: horaPrueba,
		};

		console.log(prueba);

		const pruebaJson = JSON.stringify(prueba);

		const tipoAccion = document
			.querySelector(".aceptar")
			.getAttribute("data-tipo");

		try {
			if (tipoAccion === "editar") {
				ocultarLoaderModal();
				errorDialog.show("No se ha podido editar");
			} else {
				await addInscripciones(pruebaJson);
				await renderizarPruebas();
				ocultarLoaderModal();
			}
		} catch (error) {
			console.error("Error al insertar/editar las inscripciones", error);
		}

		// Cerrar el modal
		cerrarModal();
	});

async function addInscripciones(data) {
	try {
		const modelo = new M_crudInscripcionesTO();
		await modelo.insertInscripciones(data); // Esperar que la inserción se complete
	} catch (error) {
		console.error(error);
	}
}

function mostrarLoaderModal() {
	document.getElementById("loader-modal").style.display = "flex"; // Mostrar modal
}

function ocultarLoaderModal() {
	document.getElementById("loader-modal").style.display = "none"; // Ocultar modal
}
