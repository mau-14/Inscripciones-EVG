import M_crudPruebasTO from "/InscripcionesEVG/assets/js/models/m_crudPruebasTO.js";
import { renderizarPruebas } from "/InscripcionesEVG/assets/js/controllers/c_obtenerPruebas.js";
import { ErrorDialog } from "/InscripcionesEVG/assets/js/utils/errorHandler.js";
import { Loader } from "/InscripcionesEVG/assets/js/utils/loader.js";
import M_obtenerPruebas from "/InscripcionesEVG/assets/js/models/m_obtenerPruebas.js";

/** @type {ErrorDialog} */
const errorDialog = new ErrorDialog();

const btnAceptar = document.getElementById("aceptar");
const btnConfirmar = document.getElementById("btnConfirmar");

/**
 * Evento para añadir o editar una prueba.
 * @param {MouseEvent} event - Evento del clic en el botón aceptar.
 */
btnAceptar?.addEventListener("click", async function (event) {
	event.preventDefault();

	const tipoAccion = btnAceptar.getAttribute("data-tipo");

	const modelo = new M_crudPruebasTO();

	switch (tipoAccion) {
		case "añadir":
		case "editar":
			/** @type {string} */
			const idPruebaM = document.getElementById("idPruebaM")?.value ?? "";

			/** @type {string} */
			const idPruebaF = document.getElementById("idPruebaF")?.value ?? "";

			const nombrePrueba = document.getElementById("nombrePrueba").value;
			const bases = document.getElementById("bases").value;
			const maxParticipantes =
				document.getElementById("maxParticipantes").value;
			const fechaPrueba = document.getElementById("fechaPrueba").value ?? "";
			const horaPrueba = document.getElementById("horaPrueba").value ?? "";

			if (
				!nombrePrueba ||
				!bases ||
				!maxParticipantes ||
				maxParticipantes === "0"
			) {
				errorDialog.show("Faltan campos por rellenar.");
				return;
			}
			if (
				contieneCaracteresPeligrosos(nombrePrueba) ||
				contieneTagsHTML(nombrePrueba) ||
				contieneCaracteresPeligrosos(bases) ||
				contieneTagsHTML(bases)
			) {
				errorDialog.show("No se permiten caracteres o etiquetas HTML.");
				return;
			}
			/**
			 * Objeto que representa una prueba.
			 * @type {{ idPruebaM: string, idPruebaF: string, nombre: string, bases: string, tipo: string, maxParticipantes: string, fecha: string, hora: string }}
			 */
			const prueba = {
				idPruebaM,
				idPruebaF,
				nombre: nombrePrueba,
				bases,
				maxParticipantes,
				fecha: fechaPrueba,
				hora: horaPrueba,
			};

			if (bases.length > 255) {
				errorDialog.show(
					"La descripción no puede tener más de 255 caracteres.",
				);
				return;
			} else if (nombrePrueba.length > 50) {
				errorDialog.show("El nombre no puede tener más de 50 caracteres.");
				return;
			}

			console.log(prueba);
			const loader = new Loader("Cargando...");

			// Validación de hora: debe estar entre 09:00 y 15:00
			const horaMinima = "09:00";
			const horaMaxima = "15:00";

			if (!horaPrueba || horaPrueba < horaMinima || horaPrueba > horaMaxima) {
				errorDialog.show("La hora debe estar entre las 09:00 y las 15:00.");
				loader.ocultar();
				return;
			}

			// Validación de duplicados por hora (independientemente de la fecha)
			const modeloPruebas = new M_obtenerPruebas();
			const pruebas = await modeloPruebas.obtenerPruebas();
			const existeMismaHora = pruebas.some((p) => {
				console.log(p);
				if (p.categoria !== "M") {
					return false;
				}

				if (
					tipoAccion === "editar" &&
					String(p.idPrueba) === String(idPruebaM)
				) {
					return false; // Ignorar la prueba que estamos editando
				}

				return p.hora === horaPrueba;
			});
			if (existeMismaHora) {
				errorDialog.show("Ya existe una prueba programada a esa hora.");
				loader.ocultar();
				return;
			}

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
				loader.ocultar();
				cerrarModal();
			}
			break;

		default:
			errorDialog.show("Acción desconocida");
			break;
	}
});

/**
 * Evento para confirmar el borrado de una prueba.
 * @param {MouseEvent} event - Evento del clic en el botón confirmar.
 */
btnConfirmar?.addEventListener("click", async function (event) {
	event.preventDefault();
	console.log("Borrar confirmado");

	/** @type {string} */
	const idPruebaM = document.getElementById("idPruebaM")?.value ?? "";

	/** @type {string} */
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

/**
 * Muestra el modal con el loader (indicador de carga).
 * @function
 */
function mostrarLoaderModal() {
	document.getElementById("loader-modal").style.display = "flex";
}

/**
 * Oculta el modal con el loader.
 * @function
 */
function ocultarLoaderModal() {
	document.getElementById("loader-modal").style.display = "none";
}

/**
 * Cierra el modal principal.
 * @function
 */
function cerrarModal() {
	document.getElementById("modal").style.display = "none";
}
function contieneCaracteresPeligrosos(texto) {
	const regex = /[<>{}"'`´\\]/;
	return regex.test(texto);
}
function contieneTagsHTML(texto) {
	const tagRegex = /<\/?[a-z][\s\S]*>/i; // detecta <algo> o </algo>
	return tagRegex.test(texto);
}
/**
 * Cierra el modal de confirmación.
 * @function
 */
function cerrarModalConfirmacion() {
	document.getElementById("modalConfirmacion").style.display = "none";
}
