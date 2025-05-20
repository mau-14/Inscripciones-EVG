import M_obtenerPruebas from "/InscripcionesEVG/assets/js/models/m_obtenerPruebas.js";

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
		<p><strong>Fecha:</strong> ${formatearFecha(pruebaM.fecha)}</p>
		<p><strong>Hora:</strong> ${pruebaM.hora.slice(0, 5)}</p>
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
		const addPruebaDiv = document.createElement("div");
		addPruebaDiv.classList.add("prueba");
		addPruebaDiv.innerHTML = `
			<h3>AÑADIR PRUEBA</h3>
			<div class="acciones">
				<button class="btn-mas" onclick="abrirModal('añadir')">➕</button>
			</div>
		`;
		grid.appendChild(addPruebaDiv);
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

export { renderizarPruebas, crearCamposPorPrueba };
