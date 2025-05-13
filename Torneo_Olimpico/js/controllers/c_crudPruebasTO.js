import M_crudPruebasTO from "/Torneo_Olimpico/js/models/m_crudPruebasTO.js";
import { renderizarPruebas } from "/Torneo_Olimpico/js/controllers/c_obtenerPruebas.js";
import { ErrorDialog } from "/Torneo_Olimpico/js/utils/errorHandler.js";

const errorDialog = new ErrorDialog();

const btnAceptar = document.getElementById("aceptar");
const btnConfirmar = document.getElementById("btnConfirmar");

// AÑADIR / EDITAR
btnAceptar?.addEventListener("click", async function (event) {
	event.preventDefault();

	const tipoAccion = btnAceptar.getAttribute("data-tipo");

	const modelo = new M_crudPruebasTO();

	switch (tipoAccion) {
		case "añadir":
		case "editar":
			const idPruebaM = document.getElementById("idPruebaM")?.value ?? "";
			const idPruebaF = document.getElementById("idPruebaF")?.value ?? "";

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
				idPruebaM: idPruebaM,
				idPruebaF: idPruebaF,
				nombre: nombrePrueba,
				bases: bases,
				maxParticipantes: maxParticipantes,
				fecha: fechaPrueba,
				hora: horaPrueba,
			};
			console.log(prueba);

			mostrarLoaderModal();

			try {
				if (tipoAccion === "añadir") {
					const resultado = await modelo.insertPrueba(JSON.stringify(prueba));
					if (!resultado.error) {
						await renderizarPruebas();
					}
				} else if (tipoAccion === "editar") {
					const resultado = await modelo.modificarPrueba(
						JSON.stringify(prueba),
					);
					if (!resultado.error) {
						await renderizarPruebas();
					}
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
	const idPruebaM = document.getElementById("idPruebaM")?.value ?? "";
	const idPruebaF = document.getElementById("idPruebaF")?.value ?? "";
	const jsonIds = JSON.stringify({ idPruebaM, idPruebaF });
	console.log(jsonIds);
	try {
		const modelo = new M_crudPruebasTO();
		const resultado = await modelo.borrarPrueba(jsonIds);
		if (!resultado.error) {
			await renderizarPruebas();
		}
	} catch (error) {
		errorDialog.show(error);
	} finally {
		cerrarModalConfirmacion();
	}
});

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
