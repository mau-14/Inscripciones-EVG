import M_obtenerPruebas from "/Torneo_Olimpico/js/models/m_obtenerPruebas.js";

// CONTROLADOR PARA CREAR DIVS DE LAS PRUEBAS

async function renderizarPruebas() {
	const modelo = new M_obtenerPruebas();
	const pruebas = await modelo.obtenerPruebas();

	const grid = document.querySelector("section.grid");
	grid.innerHTML = "";

	pruebas.forEach((prueba) => {
		const div = document.createElement("div");
		div.classList.add("prueba");

		div.innerHTML = `
			<h3>${prueba.nombre}</h3>
			<p><strong>Fecha:</strong> ${formatearFecha(prueba.fecha)}</p>
			<p><strong>Hora:</strong> ${prueba.hora.slice(0, 5)}</p>
			<p><strong>Descripción:</strong> ${obtenerDescripcion(prueba.nombre)}</p>
			<div class="acciones">
				<button onclick="abrirModal('editar', ${prueba.idPrueba})">✏️</button>
				<button onclick="abrirModal('borrar', ${prueba.idPrueba})">🗑️</button>
			</div>
		`;

		grid.appendChild(div);
	});
}

function formatearFecha(fecha) {
	const [year, month, day] = fecha.split("-");
	return `${day}/${month}/${year}`;
}

function obtenerDescripcion(nombre) {
	const descripciones = {
		"50 metros": "Carrera de velocidad individual.",
		"800 metros": "Prueba de resistencia de media distancia.",
		"4*100 metros": "Carrera de relevos por equipos.",
		Peso: "Lanzamiento de peso con técnica de rotación.",
		Jabalina: "Lanzamiento de jabalina con técnica libre.",
		Longitud: "Salto de longitud desde zona delimitada.",
	};
	return descripciones[nombre] || "Sin descripción disponible.";
}

// Ejecutar al cargar
document.addEventListener("DOMContentLoaded", renderizarPruebas);
