import M_crudInscripcionesTO from "/Torneo_Olimpico/js/models/m_crudInscripcionesTO.js";
import { renderizarPruebas } from "/Torneo_Olimpico/js/controllers/c_obtenerPruebas.js";
import { ErrorDialog } from "/Torneo_Olimpico/js/utils/errorHandler.js";

const errorDialog = new ErrorDialog();

const btnAceptar = document.getElementById("aceptar");
const btnConfirmar = document.getElementById("btnConfirmar");

// AÑADIR / EDITAR
btnAceptar?.addEventListener("click", async function (event) {
	event.preventDefault();

	const tipoAccion = btnAceptar.getAttribute("data-tipo");

	switch (tipoAccion) {
		case "añadir":
		case "editar":
			const nombrePrueba = document.getElementById("nombrePrueba").value;
			const bases = document.getElementById("bases").value;
			const maxParticipantes =
				document.getElementById("maxParticipantes").value;
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

			const prueba = {
				nombre: nombrePrueba,
				bases: bases,
				maxParticipantes: maxParticipantes,
				fecha: fechaPrueba,
				hora: horaPrueba,
			};

			mostrarLoaderModal();

			try {
				if (tipoAccion === "añadir") {
					await addInscripciones(JSON.stringify(prueba));
					await renderizarPruebas();
				} else if (tipoAccion === "editar") {
					errorDialog.show("No se ha podido editar");
				}
			} catch (error) {
				console.error("Error al insertar/editar las inscripciones", error);
			} finally {
				ocultarLoaderModal();
				cerrarModal();
			}
			break;

		default:
			errorDialog.show("Acción desconocida");
			break;
	}
});

// BORRAR
btnConfirmar?.addEventListener("click", async function (event) {
	event.preventDefault();
	console.log("Borrar confirmado");

	const idPrueba = btnConfirmar.getAttribute("data-id");
	const jsonId = JSON.stringify({ idPrueba: idPrueba });

	console.log(jsonId);
	try {
		const modelo = new M_crudInscripcionesTO();
		await modelo.borrarInscripcion(idPrueba);
		await renderizarPruebas();
	} catch (error) {
		console.error("Error al borrar la prueba", error);
		errorDialog.show("No se pudo borrar la prueba");
	} finally {
		cerrarModalConfirmacion();
	}
});

// FUNCIONES AUXILIARES
async function addInscripciones(data) {
	try {
		const modelo = new M_crudInscripcionesTO();
		await modelo.insertInscripciones(data);
	} catch (error) {
		console.error(error);
	}
}

function mostrarLoaderModal() {
	document.getElementById("loader-modal").style.display = "flex";
}

function ocultarLoaderModal() {
	document.getElementById("loader-modal").style.display = "none";
}

// Estas funciones deben estar definidas en tu archivo o importadas:
function cerrarModal() {
	document.getElementById("modal").style.display = "none";
}

function cerrarModalConfirmacion() {
	document.getElementById("modalConfirmacion").style.display = "none";
}
