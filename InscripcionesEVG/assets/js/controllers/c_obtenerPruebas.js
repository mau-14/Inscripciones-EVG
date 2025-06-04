import M_obtenerPruebas from "/InscripcionesEVG/assets/js/models/m_obtenerPruebas.js";
import { Loader } from "/InscripcionesEVG/assets/js/utils/loader.js";
import { obtenerExceldePruebas } from "/InscripcionesEVG/assets/js/controllers/c_obtenerAlumnos.js";

/**
 * Controlador que obtiene las pruebas desde el modelo y las renderiza en la vista.
 * Agrupa las pruebas masculinas y busca su equivalente femenina por nombre.
 * Crea elementos HTML dinámicamente y añade eventos a botones de edición y borrado.
 * También añade un botón para insertar nuevas pruebas.
 *
 * @async
 * @function renderizarPruebas
 * @returns {Promise<void>}
 */
async function renderizarPruebas() {
	try {
		const modelo = new M_obtenerPruebas();
		const pruebas = await modelo.obtenerPruebas();
		const grid = document.querySelector("section.grid");
		grid.innerHTML = "";

		// Filtrar las pruebas por categoría M y agrupar por nombre
		const pruebasFiltradas = pruebas.filter((p) => p.categoria === "M");

		pruebasFiltradas.forEach((pruebaM) => {
			const pruebaF = pruebas.find(
				(p) => p.nombre === pruebaM.nombre && p.categoria === "F",
			);

			const div = document.createElement("div");
			div.classList.add("prueba");

			div.innerHTML = `
		<h3>${pruebaM.nombre}</h3>
		<p><strong>Fecha:</strong> ${pruebaM.fecha ? formatearFecha(pruebaM.fecha) : "Sin fecha"}</p>
    <p><strong>Hora:</strong> ${pruebaM.hora ? pruebaM.hora.slice(0, 5) : "Sin hora"}</p>
		<p><strong>Descripción:</strong> ${pruebaM.bases}</p>
		<div class="acciones">
			<button class="btn-editar">✏️</button>
			${pruebaM.tipo !== "C" ? '<button class="btn-borrar">🗑️</button>' : ""}
		</div>
	`;

			div
				.querySelector(".btn-editar")
				.addEventListener("click", () =>
					abrirModal("editar", pruebaM.idPrueba, pruebaM, pruebaF?.idPrueba),
				);

			if (pruebaM.tipo !== "C") {
				div
					.querySelector(".btn-borrar")
					.addEventListener("click", () =>
						abrirModal("borrar", pruebaM.idPrueba, pruebaM, pruebaF?.idPrueba),
					);
			}

			grid.appendChild(div);
		});
		// Botón "Añadir prueba"
		const addPruebaBtn = document.createElement("button");
		addPruebaBtn.classList.add("prueba");
		addPruebaBtn.id = "prueba-unica"; // Aquí el id
		addPruebaBtn.type = "button";
		addPruebaBtn.innerHTML = `
    <h3>AÑADIR PRUEBA</h3>
    <div class="acciones">
      <span class="btn-mas">➕</span>
    </div>
    <div style="visibility:hidden; height: 100px;">&nbsp;</div>
  `;
		addPruebaBtn.onclick = () => abrirModal("añadir");
		// Añades el evento onclick directamente al botón:
		addPruebaBtn.onclick = () => abrirModal("añadir");
		grid.appendChild(addPruebaBtn);
	} catch (error) {
		console.error("Error al renderizar las pruebas:", error);
	}
}

/**
 * Crea campos dinámicos para cada prueba, separados por categoría,
 * y tantos select como maxParticipantes tenga la prueba.
 *
 * @async
 * @function crearCamposPorPrueba
 * @returns {Promise<void>}
 */
async function crearCamposPorPrueba() {
	try {
		const modelo = new M_obtenerPruebas();
		const pruebas = await modelo.obtenerPruebas();

		const contenedorMasculino = document.getElementById(
			"camposPruebasMasculina",
		);
		const contenedorFemenino = document.getElementById("camposPruebasFemenina");

		if (!contenedorMasculino || !contenedorFemenino) {
			console.warn(
				"No se encontraron los contenedores para pruebas por categoría.",
			);
			return;
		}

		// Limpiamos el contenido anterior
		contenedorMasculino.innerHTML = "";
		contenedorFemenino.innerHTML = "";

		pruebas.forEach((prueba) => {
			const divCampo = document.createElement("div");
			divCampo.classList.add("campo");

			const label = document.createElement("label");
			label.textContent = prueba.nombre;
			label.id = prueba.idPrueba;
			divCampo.appendChild(label);

			// Crear los selects según la cantidad máxima de participantes
			for (let i = 1; i <= prueba.maxParticipantes; i++) {
				const select = document.createElement("select");
				select.name = `${prueba.tipo}`;
				select.setAttribute("data-idprueba", prueba.idPrueba);
				select.setAttribute("data-index", i);
				select.innerHTML = `<option value="">Selecciona</option>`;
				divCampo.appendChild(select);
			}

			// Insertar en la categoría correspondiente
			if (prueba.categoria === "M") {
				contenedorMasculino.appendChild(divCampo);
			} else if (prueba.categoria === "F") {
				contenedorFemenino.appendChild(divCampo);
			}
		});
	} catch (error) {
		console.error("Error al crear campos por prueba:", error);
	}
}

async function cargarPruebasConDescarga() {
	try {
		const modelo = new M_obtenerPruebas();
		const pruebas = await modelo.obtenerPruebas();

		const pruebasMasculinas = pruebas.filter((p) => p.categoria === "M");
		const contenedor = document.querySelector("section.pruebasExcel");
		contenedor.innerHTML = "";

		for (const pruebaM of pruebasMasculinas) {
			const pruebaF = pruebas.find(
				(p) => p.nombre === pruebaM.nombre && p.categoria === "F",
			);

			// Crear div contenedor
			const div = document.createElement("div");
			div.classList.add("prueba-item");

			// Título con nombre prueba
			// Título con nombre prueba
			const titulo = document.createElement("h3");
			titulo.textContent = pruebaM.nombre;
			div.appendChild(titulo);

			// Fecha y hora
			// Fecha y hora (formateada)
			const fechaHora = document.createElement("p");
			const hora = pruebaM.hora ? pruebaM.hora.slice(0, 5) : "Sin hora";
			fechaHora.textContent = `Hora: ${hora}`;
			div.appendChild(fechaHora);
			// Inputs hidden para idPrueba masculino y femenino
			const inputMasculino = document.createElement("input");
			inputMasculino.type = "hidden";
			inputMasculino.name = "idPruebaMasculino";
			inputMasculino.value = pruebaM.idPrueba;
			div.appendChild(inputMasculino);

			const inputFemenino = document.createElement("input");
			inputFemenino.type = "hidden";
			inputFemenino.name = "idPruebaFemenino";
			inputFemenino.value = pruebaF ? pruebaF.idPrueba : "";
			div.appendChild(inputFemenino);

			// Botón para descargar Excel
			// Botón para descargar Excel
			// Botón para descargar Excel
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

			btnDescargar.addEventListener("click", async () => {
				const loader = new Loader("Generando excel...");
				try {
					const idPruebaM = inputMasculino.value;
					const idPruebaF = inputFemenino.value;
					await obtenerExceldePruebas(idPruebaM, idPruebaF);
				} catch (error) {
					console.error("Error al cargar los campos o alumnos:", error);
				} finally {
					loader.ocultar();
				}
			});

			div.appendChild(btnDescargar);

			// Botón para descargar PDF
			const btnPDF = document.createElement("button");
			btnPDF.classList.add("btn-descargar-pdf");

			const textoPDF = document.createElement("span");
			textoPDF.textContent = "Descargar ";
			textoPDF.style.marginRight = "6px";
			textoPDF.style.color = "white";

			const imgPDF = document.createElement("img");
			imgPDF.src = "/InscripcionesEVG/assets/img/pdf.png";
			imgPDF.alt = "Descargar PDF";
			imgPDF.style.width = "25px";
			imgPDF.style.height = "25px";

			btnPDF.appendChild(textoPDF);
			btnPDF.appendChild(imgPDF);

			btnPDF.addEventListener("click", async () => {
				const loader = new Loader("Generando PDF...");
				try {
					const idPruebaM = inputMasculino.value;
					const idPruebaF = inputFemenino.value;
					console.log("Descargar PDF para:", idPruebaM, idPruebaF);
					// Aquí iría la lógica de generación
				} catch (error) {
					console.error("Error al generar PDF:", error);
				} finally {
					loader.ocultar();
				}
			});

			div.appendChild(btnPDF);

			contenedor.appendChild(div);
		}
	} catch (error) {
		console.error("Error al cargar las pruebas con descarga:", error);
	}
}
/**
 * Formatea una fecha en formato 'YYYY-MM-DD' a 'DD/MM/YYYY'.
 *
 * @function formatearFecha
 * @param {string} fecha - La fecha en formato ISO (YYYY-MM-DD).
 * @returns {string} La fecha formateada como 'DD/MM/YYYY'.
 */
function formatearFecha(fecha) {
	try {
		const [year, month, day] = fecha.split("-");
		return `${day}/${month}/${year}`;
	} catch (error) {
		console.error("Error al formatear la fecha:", error);
		return fecha;
	}
}

// // Ejecutar al cargar la página
// document.addEventListener("DOMContentLoaded", async () => {
// 	try {
// 		await renderizarPruebas();
// 		await crearCamposPorPrueba();
// 	} catch (error) {
// 		console.error("Error al iniciar la página:", error);
// 	}
// });

export { renderizarPruebas, crearCamposPorPrueba, cargarPruebasConDescarga };
