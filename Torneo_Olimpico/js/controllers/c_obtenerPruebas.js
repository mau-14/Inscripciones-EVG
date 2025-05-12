import M_obtenerPruebas from "/Torneo_Olimpico/js/models/m_obtenerPruebas.js";

// LÓGICA DE LOS MODALES

const modal = document.getElementById("modal");
const modalConfirmacion = document.getElementById("modalConfirmacion");
const modalTitle = document.getElementById("modal-title");
const confirmTitle = document.getElementById("modalConfirmacion-title");

function abrirModal(tipo, id, prueba) {
	try {
		if (tipo === "borrar") {
			modalConfirmacion.style.display = "flex";
			confirmTitle.textContent = `¿Desea eliminar la prueba ${prueba.nombre}?`;
		} else {
			modal.style.display = "flex";
			if (tipo === "editar") {
				modalTitle.textContent = "Editar Prueba";

				document.getElementById("nombrePrueba").value = prueba.nombre;
				document.getElementById("fechaPrueba").value = prueba.fecha;
				document.getElementById("horaPrueba").value = prueba.hora;
				const selectMaxParticipantes =
					document.getElementById("maxParticipantes");
				selectMaxParticipantes.value = prueba.maxParticipantes;
			} else {
				// Si hay más lógica para 'añadir', puede ir aquí.
			}
		}
	} catch (error) {
		console.error("Error al abrir el modal:", error);
	}
}

function cerrarModal() {
	try {
		console.log("cerraraar");
		modal.style.display = "none";
	} catch (error) {
		console.error("Error al cerrar el modal:", error);
	}
}

function cerrarModalConfirmacion() {
	try {
		modalConfirmacion.style.display = "none";
	} catch (error) {
		console.error("Error al cerrar el modal de confirmación:", error);
	}
}

window.onclick = function (e) {
	try {
		if (e.target === modal) cerrarModal();
		if (e.target === modalConfirmacion) cerrarModalConfirmacion();
	} catch (error) {
		console.error("Error al hacer clic en el modal:", error);
	}
};

// Botón "Cancelar" del moda
document.getElementById("btnCancelar").addEventListener("click", cerrarModal);

// SI SE USA TYPE MODULE LAS FUNCIONES NO ESTÁN DISPONIBLES GLOBALMENTE Y HAY QUE USAR WINDOW
window.abrirModal = abrirModal;
window.abrirModal = cerrarModal;

// CONTROLADOR PARA CREAR DIVS DE LAS PRUEBAS

async function renderizarPruebas() {
	try {
		const modelo = new M_obtenerPruebas();
		const pruebas = await modelo.obtenerPruebas();
		const grid = document.querySelector("section.grid");

		// Crear las pruebas
		pruebas.forEach((prueba) => {
			const div = document.createElement("div");
			div.classList.add("prueba");

			div.innerHTML = `
  <h3>${prueba.nombre}</h3>
  <p><strong>Fecha:</strong> ${formatearFecha(prueba.fecha)}</p>
  <p><strong>Hora:</strong> ${prueba.hora.slice(0, 5)}</p>
  <p><strong>Descripción:</strong> ${obtenerDescripcion(prueba.nombre)}</p>
  <div class="acciones">
    <button class="btn-editar">✏️</button>
    <button class="btn-borrar">🗑️</button>
  </div>
`;

			div
				.querySelector(".btn-editar")
				.addEventListener("click", () =>
					abrirModal("editar", prueba.idPrueba, prueba),
				);
			div
				.querySelector(".btn-borrar")
				.addEventListener("click", () => abrirModal("borrar", prueba.idPrueba));

			grid.appendChild(div);
		});

		// Añadir el botón "Añadir prueba" al final
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

function obtenerDescripcion(nombre) {
	try {
		const descripciones = {
			"50 metros": "Carrera de velocidad individual.",
			"800 metros": "Prueba de resistencia de media distancia.",
			"4*100 metros": "Carrera de relevos por equipos.",
			Peso: "Lanzamiento de peso con técnica de rotación.",
			Jabalina: "Lanzamiento de jabalina con técnica libre.",
			Longitud: "Salto de longitud desde zona delimitada.",
		};
		return descripciones[nombre] || "Sin descripción disponible.";
	} catch (error) {
		console.error("Error al obtener la descripción:", error);
		return "Descripción no disponible"; // Retornar un mensaje por defecto en caso de error.
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
