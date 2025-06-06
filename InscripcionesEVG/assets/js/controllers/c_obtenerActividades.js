import M_obtenerActividades from "/InscripcionesEVG/assets/js/models/m_obtenerActividades.js";
import { ModalConfirmacion } from "/InscripcionesEVG/assets/js/utils/modalConfirmacion.js";
import M_obtenerInscripcionesActividad from "/InscripcionesEVG/assets/js/models/m_obtenerInscripcionesActividad.js";
import { Loader } from "/InscripcionesEVG/assets/js/utils/loader.js";

async function cargarActividadesConDescarga(idMomento) {
	try {
		const modelo = new M_obtenerActividades();
		const actividades = await modelo.obtenerActividades();

		// Filtrar por momento si se proporciona uno
		const actividadesFiltradas = idMomento
			? actividades.filter((act) => act.idMomento == idMomento)
			: actividades;

		const tabla = document.querySelector(".tabla-pruebas");
		const tbody = tabla.querySelector("tbody");
		const contenedorDescargarTodos = document.querySelector(
			".descargar-todos-container",
		);

		// Limpiar contenido previo
		tbody.innerHTML = "";

		if (actividadesFiltradas.length === 0) {
			// No hay actividades: ocultar tabla y botón, mostrar mensaje
			tabla.style.display = "none";

			// Crear o mostrar mensaje
			let mensaje = document.querySelector(".mensaje-sin-actividades");
			if (!mensaje) {
				mensaje = document.createElement("p");
				mensaje.classList.add("mensaje-sin-actividades");
				mensaje.style.color = "#666";
				mensaje.style.fontStyle = "italic";
				mensaje.textContent = "No hay actividades disponibles.";
				tabla.after(mensaje);
			}
			mensaje.style.display = "block";

			// Ocultar contenedor de botón "Descargar todos" si existe
			if (contenedorDescargarTodos)
				contenedorDescargarTodos.style.display = "none";

			return;
		}

		// Hay actividades: mostrar tabla y ocultar mensaje si existe
		tabla.style.display = "table";

		let mensaje = document.querySelector(".mensaje-sin-actividades");
		if (mensaje) {
			mensaje.style.display = "none";
		}

		if (contenedorDescargarTodos)
			contenedorDescargarTodos.style.display = "block";

		const actividadesParaZip = [];

		for (const actividad of actividadesFiltradas) {
			const tr = document.createElement("tr");

			// Columna: Nombre
			const tdNombre = document.createElement("td");
			tdNombre.textContent = actividad.nombre;
			tr.appendChild(tdNombre);

			// Columna: Botón de descarga individual
			const tdDescargar = document.createElement("td");
			const btnDescargar = document.createElement("button");
			btnDescargar.classList.add("btn-descargar-excel");

			const textoExcel = document.createElement("span");
			textoExcel.textContent = "Descargar ";
			textoExcel.style.marginRight = "6px";
			textoExcel.style.color = "white";

			const imgExcel = document.createElement("img");
			imgExcel.src = "/InscripcionesEVG/assets/img/excel.png";
			imgExcel.alt = "Descargar Excel";
			imgExcel.style.width = "25px";
			imgExcel.style.height = "25px";

			btnDescargar.appendChild(textoExcel);
			btnDescargar.appendChild(imgExcel);

			const idActividad = actividad.idActividad;

			actividadesParaZip.push({ idActividad });

			btnDescargar.addEventListener("click", async () => {
				const loader = new Loader("Generando Excel...");
				try {
					await obtenerExceldeActividades(idActividad);
				} catch (error) {
					console.error("Error al generar Excel:", error);
				} finally {
					loader.ocultar();
				}
			});

			tdDescargar.appendChild(btnDescargar);
			tr.appendChild(tdDescargar);
			tbody.appendChild(tr);
		}

		// Botón "Descargar todos"
		let contenedor = document.querySelector(".descargar-todos-container");
		if (!contenedor) {
			contenedor = document.createElement("div");
			contenedor.classList.add("descargar-todos-container");
			tabla.after(contenedor);
		} else {
			contenedor.innerHTML = "";
		}

		const btnDescargarTodos = document.createElement("button");
		btnDescargarTodos.innerHTML = "Descargar carpeta comprimida <i>.zip</i>";
		btnDescargarTodos.classList.add("btn-descargar-todos");

		btnDescargarTodos.addEventListener("click", async () => {
			const loader = new Loader("Generando zip...");
			try {
				const idsActividades = actividadesFiltradas.map(
					(act) => act.idActividad,
				);
				await obtenerZipActividades(idsActividades);
			} catch (error) {
				console.error("Error al generar todos los Excel:", error);
			} finally {
				loader.ocultar();
			}
		});

		contenedor.appendChild(btnDescargarTodos);
	} catch (error) {
		console.error("Error al cargar las actividades con descarga:", error);
	}
}

async function obtenerExceldeActividades(idActividad) {
	const modelo = new M_obtenerInscripcionesActividad();
	const excels = await modelo.obtenerAlumnosInscripcionesActividad(idActividad);

	if (
		!excels ||
		(Array.isArray(excels) && excels.length === 0) || // si es un array
		(typeof excels === "object" && Object.keys(excels).length === 0) // si es objeto sin propiedades
	) {
		new ModalConfirmacion({
			titulo: "No disponible",
			mensaje: "Esta prueba no tiene inscripciones",
			onAceptar: () => {},
			onCancelar: () => {},
		});
		return; // no continuar
	}
	const response = await fetch(
		"/InscripcionesEVG/controllers/generar_excelActividades.php",
		{
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(excels),
		},
	);

	if (!response.ok) {
		alert("Error al generar el Excel");
		return;
	}

	// Obtener el nombre del archivo desde la cabecera
	let filename = "archivo.xlsx"; // por defecto
	const disposition = response.headers.get("Content-Disposition");
	if (disposition && disposition.includes("filename=")) {
		const match = disposition.match(/filename="?([^"]+)"?/);
		if (match && match[1]) {
			filename = match[1];
		}
	}

	const blob = await response.blob();
	const url = window.URL.createObjectURL(blob);
	const a = document.createElement("a");
	a.href = url;
	a.download = filename;
	document.body.appendChild(a);
	a.click();
	document.body.removeChild(a);
	window.URL.revokeObjectURL(url);
}

async function obtenerZipActividades(idsActividades) {
	const modelo = new M_obtenerInscripcionesActividad();
	const datosParaExcel = [];

	for (const idActividad of idsActividades) {
		const excels =
			await modelo.obtenerAlumnosInscripcionesActividad(idActividad);

		if (excels && Array.isArray(excels) && excels.length > 0) {
			datosParaExcel.push(excels);
		}
	}

	if (datosParaExcel.length === 0) {
		new ModalConfirmacion({
			titulo: "No disponible",
			mensaje: "Ninguna de las pruebas tiene inscripciones",
			onAceptar: () => {},
			onCancelar: () => {},
		});
		return;
	}

	const response = await fetch(
		"/InscripcionesEVG/controllers/generar_zipActividades.php",
		{
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify(datosParaExcel), // ENVIAMOS ARRAY DE ARRAYS
		},
	);

	if (!response.ok) {
		alert("Error al generar el ZIP");
		return;
	}

	const blob = await response.blob();
	const url = window.URL.createObjectURL(blob);
	const a = document.createElement("a");
	a.href = url;
	a.download = "actividades_excel.zip";
	document.body.appendChild(a);
	a.click();
	document.body.removeChild(a);
	window.URL.revokeObjectURL(url);
}

export { cargarActividadesConDescarga };
