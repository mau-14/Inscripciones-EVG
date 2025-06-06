import M_obtenerPruebas from "/InscripcionesEVG/assets/js/models/m_obtenerPruebas.js";
import { Loader } from "/InscripcionesEVG/assets/js/utils/loader.js";
import {
	obtenerExceldePruebas,
	obtenerZipDePruebas,
} from "/InscripcionesEVG/assets/js/controllers/c_obtenerAlumnos.js";

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
    ${pruebaM.tipo !== "C" ? '<button class="btn-editar">✏️</button>' : ""}
    ${pruebaM.tipo !== "C" ? '<button class="btn-borrar">🗑️</button>' : ""}
  </div>
`;

			if (pruebaM.tipo !== "C") {
				div
					.querySelector(".btn-borrar")
					.addEventListener("click", () =>
						abrirModal("borrar", pruebaM.idPrueba, pruebaM, pruebaF?.idPrueba),
					);

				div
					.querySelector(".btn-editar")
					.addEventListener("click", () =>
						abrirModal("editar", pruebaM.idPrueba, pruebaM, pruebaF?.idPrueba),
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

		const tbody = document.querySelector(".tabla-pruebas tbody");
		tbody.innerHTML = "";

		// Almacenar pares de IDs (M y F)
		const paresDePruebas = [];

		for (const pruebaM of pruebasMasculinas) {
			const pruebaF = pruebas.find(
				(p) => p.nombre === pruebaM.nombre && p.categoria === "F",
			);

			const tr = document.createElement("tr");

			// Columna: Nombre
			const tdNombre = document.createElement("td");
			tdNombre.textContent = pruebaM.nombre;
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

			const inputM = pruebaM.idPrueba;
			const inputF = pruebaF ? pruebaF.idPrueba : "";

			// Guardar en lista para el botón global
			paresDePruebas.push({ inputM, inputF });

			btnDescargar.addEventListener("click", async () => {
				const loader = new Loader("Generando excel...");
				try {
					await obtenerExceldePruebas(inputM, inputF);
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
			document.querySelector(".tabla-pruebas").after(contenedor);
		} else {
			contenedor.innerHTML = "";
		}

		const btnDescargarTodos = document.createElement("button");
		btnDescargarTodos.innerHTML = "Descargar carpeta comprimida <i>.zip</i>";
		btnDescargarTodos.classList.add("btn-descargar-todos");

		btnDescargarTodos.addEventListener("click", async () => {
			const loader = new Loader("Generando zip...");
			try {
				await obtenerZipDePruebas(paresDePruebas);
			} catch (error) {
				console.error("Error al generar todos los Excel:", error);
			} finally {
				loader.ocultar();
			}
		});

		contenedor.appendChild(btnDescargarTodos);
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
