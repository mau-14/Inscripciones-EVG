import M_crudInscripcionesTO from "/Torneo_Olimpico/js/models/m_crudInscripcionesTO.js";
import { renderizarPruebas } from "/Torneo_Olimpico/js/controllers/c_obtenerPruebas.js";

document
	.querySelector(".aceptar")
	.addEventListener("click", async function (event) {
		event.preventDefault();

		// Mostrar el modal de carga (spinner)
		mostrarLoaderModal();

		// Recoger los datos del formulario
		const nombrePrueba = document.getElementById("nombrePrueba").value;
		const bases = document.getElementById("bases").value;
		const maxParticipantes = document.getElementById("maxParticipantes").value;
		const fechaPrueba = document.getElementById("fechaPrueba").value;
		const horaPrueba = document.getElementById("horaPrueba").value;

		// Crear un objeto con los datos del formulario
		const prueba = {
			nombre: nombrePrueba,
			bases: bases,
			maxParticipantes: maxParticipantes,
			fecha: fechaPrueba,
			hora: horaPrueba,
		};

		console.log(prueba);

		// Convertir el objeto a JSON
		const pruebaJson = JSON.stringify(prueba);

		try {
			// Esperar a que se complete la inserción de datos
			await addInscripciones(pruebaJson);
			await renderizarPruebas();
		} catch (error) {
			console.error("Error al insertar las inscripciones", error);
		}

		// Ocultar el modal de carga (spinner) una vez completado
		ocultarLoaderModal();

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
