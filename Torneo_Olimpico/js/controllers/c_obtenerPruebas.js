import M_obtenerPruebas from "/Torneo_Olimpico/js/models/m_obtenerPruebas.js";

// CONTROLADOR PARA CREAR DIVS DE LAS PRUEBAS

async function renderizarPruebas() {
	try {
		const modelo = new M_obtenerPruebas();
		const pruebas = await modelo.obtenerPruebas();
		const grid = document.querySelector("section.grid");
		grid.innerHTML = "";

		// Filtrar las pruebas por categoría M y agrupar por nombre
		const pruebasFiltradas = pruebas.filter((p) => p.categoria === "M");

		pruebasFiltradas.forEach((pruebaM) => {
			// Buscar su equivalente femenina
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
    <button class="btn-borrar">🗑️</button>
  </div>
`;

			div
				.querySelector(".btn-editar")
				.addEventListener("click", () =>
					abrirModal("editar", pruebaM.idPrueba, pruebaM, pruebaF?.idPrueba),
				);

			div
				.querySelector(".btn-borrar")
				.addEventListener("click", () =>
					abrirModal("borrar", pruebaM.idPrueba, pruebaM, pruebaF?.idPrueba),
				);

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

function formatearFecha(fecha) {
	try {
		const [year, month, day] = fecha.split("-");
		return `${day}/${month}/${year}`;
	} catch (error) {
		console.error("Error al formatear la fecha:", error);
		return fecha; // Retornar la fecha original si ocurre un error.
	}
}

// Ejecutar al cargar la página
document.addEventListener("DOMContentLoaded", () => {
	try {
		renderizarPruebas();
	} catch (error) {
		console.error("Error al ejecutar renderizarPruebas:", error);
	}
});

export { renderizarPruebas };
