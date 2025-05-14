import M_obtenerAlumnos from "/Torneo_Olimpico/js/models/m_obtenerAlumnos.js";

async function rellenarSelectsConAlumnos() {
	try {
		const modelo = new M_obtenerAlumnos();
		const alumnos = await modelo.obtenerAlumnos();

		const contenedorMasculino = document.getElementById(
			"camposPruebasMasculina",
		);
		const contenedorFemenino = document.getElementById("camposPruebasFemenina");

		if (!contenedorMasculino || !contenedorFemenino) {
			console.warn("Contenedores no encontrados.");
			return;
		}

		const selectsMasculinos = contenedorMasculino.querySelectorAll("select");
		const selectsFemeninos = contenedorFemenino.querySelectorAll("select");

		const seleccionadosGenerales = new Set(); // Para selects normales
		const seleccionadosTipoC = new Set(); // Para selects con "-C"
		const anteriores = new Map(); // Guardamos el valor anterior de cada select

		// Función para actualizar los selects
		function actualizarSelects() {
			[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
				const options = select.querySelectorAll("option");
				const esTipoC = select.name.includes("-C");

				options.forEach((option) => {
					const alumnoId = option.value;
					if (!alumnoId) return;

					if (esTipoC) {
						option.disabled =
							seleccionadosTipoC.has(alumnoId) &&
							anteriores.get(select) !== alumnoId;
					} else {
						option.disabled =
							seleccionadosGenerales.has(alumnoId) &&
							anteriores.get(select) !== alumnoId;
					}
				});
			});
		}

		// Rellenar selects masculinos
		selectsMasculinos.forEach((select) => {
			select.innerHTML = `<option value="">Selecciona</option>`;
			alumnos
				.filter((a) => a.sexo === "M")
				.forEach((alumno) => {
					const option = document.createElement("option");
					option.value = alumno.idAlumno;
					option.textContent = alumno.nombre;
					select.appendChild(option);
				});
		});

		// Rellenar selects femeninos
		selectsFemeninos.forEach((select) => {
			select.innerHTML = `<option value="">Selecciona</option>`;
			alumnos
				.filter((a) => a.sexo === "F")
				.forEach((alumno) => {
					const option = document.createElement("option");
					option.value = alumno.idAlumno;
					option.textContent = alumno.nombre;
					select.appendChild(option);
				});
		});

		// Añadir eventos de cambio
		[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
			anteriores.set(select, ""); // valor inicial vacío

			select.addEventListener("change", (event) => {
				const actual = event.target.value;
				const anterior = anteriores.get(select);
				const esTipoC = select.name.includes("-C");

				// Eliminar anterior si existía
				if (anterior) {
					if (esTipoC) {
						seleccionadosTipoC.delete(anterior);
					} else {
						seleccionadosGenerales.delete(anterior);
					}
				}

				// Añadir actual si es válido
				if (actual) {
					if (esTipoC) {
						seleccionadosTipoC.add(actual);
					} else {
						seleccionadosGenerales.add(actual);
					}
				}

				anteriores.set(select, actual); // Actualizar el valor anterior
				actualizarSelects();
			});
		});

		actualizarSelects(); // Inicial
	} catch (error) {
		console.error("Error al rellenar los selects con alumnos:", error);
	}
}

export { rellenarSelectsConAlumnos };
