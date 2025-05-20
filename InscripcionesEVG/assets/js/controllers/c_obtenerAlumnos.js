import M_obtenerAlumnos from "/InscripcionesEVG/assets/js/models/m_obtenerAlumnos.js";

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
				const esTipoC = select.name === "C";

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
				const esTipoC = select.name === "C";

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

async function rellenarSelectsConSeleccionados(idClase) {
	try {
		const modelo = new M_obtenerAlumnos();
		const alumnos = await modelo.obtenerAlumnos();

		// Obtener alumnos ya seleccionados desde el controlador PHP
		const response = await fetch(
			"/InscripcionesEVG/index.php?controlador=alumnosSeleccionados&accion=extraer&j=1",
			{
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify({ idClase: idClase }),
			},
		);
		const seleccionados = await response.json();

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

		const seleccionadosGenerales = new Set(); // Para pruebas individuales (P)
		const seleccionadosTipoC = new Set(); // Para pruebas de relevos (C)
		const anteriores = new Map();

		// Obtener IDs de alumnos ya seleccionados según tipo
		function cargarSeleccionadosDesdeJSON() {
			for (const sexo in seleccionados) {
				for (const tipo in seleccionados[sexo]) {
					for (const idPrueba in seleccionados[sexo][tipo]) {
						for (const idAlumno of seleccionados[sexo][tipo][idPrueba]) {
							if (tipo === "P") {
								seleccionadosGenerales.add(idAlumno.toString());
							} else if (tipo === "C") {
								seleccionadosTipoC.add(idAlumno.toString());
							}
						}
					}
				}
			}
		}
		function rellenarSelect(select, sexo) {
			console.log("Rellenando select", select.name, "Sexo:", sexo);
			const tipo = select.name;
			const esTipoC = tipo === "C";

			const campo = select.closest(".campo"); // el div que contiene la prueba
			const label = campo?.querySelector("label");
			if (!label) return;

			const idPrueba = label.id;
			if (!idPrueba) return;

			// Alumnos preseleccionados
			let alumnosSeleccionados = new Set();
			if (
				seleccionados[sexo] &&
				seleccionados[sexo][tipo] &&
				seleccionados[sexo][tipo][idPrueba]
			) {
				alumnosSeleccionados = new Set(
					seleccionados[sexo][tipo][idPrueba].map(String),
				);
			}

			// Detectar selects en el mismo bloque de prueba
			const selectsMismaPrueba = campo.querySelectorAll(
				`select[name="${tipo}"]`,
			);

			console.log(selectsMismaPrueba.length, idPrueba);
			// Detectar qué alumnos ya están asignados en esos selects
			const yaAsignados = new Set();
			selectsMismaPrueba.forEach((otroSelect) => {
				const val = otroSelect.value;
				console.log("Valor de otro select:", val);
				if (val) yaAsignados.add(val);
			});
			console.log(
				"Alumnos ya asignados en esta prueba:",
				yaAsignados,
				idPrueba,
			);
			select.innerHTML = `<option value="">Selecciona</option>`;

			alumnos
				.filter((a) => a.sexo === sexo)
				.filter((a) => {
					const id = a.idAlumno.toString();
					console.log(
						"Alumno:",
						a.idAlumno.toString(),
						"yaAsignados.has(id):",
						yaAsignados.has(id),
						"alumnosSeleccionados.has(id):",
						alumnosSeleccionados.has(id),
						"seleccionadosTipoC.has(id):",
						seleccionadosTipoC.has(id),
						"seleccionadosGenerales.has(id):",
						seleccionadosGenerales.has(id),
						"Resultado filtro:",
						!yaAsignados.has(id) &&
							(esTipoC
								? !seleccionadosTipoC.has(id) || alumnosSeleccionados.has(id)
								: !seleccionadosGenerales.has(id) ||
									alumnosSeleccionados.has(id)),
					);
					return (
						!yaAsignados.has(id) &&
						(esTipoC
							? !seleccionadosTipoC.has(id) || alumnosSeleccionados.has(id)
							: !seleccionadosGenerales.has(id) || alumnosSeleccionados.has(id))
					);
				})
				.forEach((alumno) => {
					const seleccionado = alumnosSeleccionados.has(
						alumno.idAlumno.toString(),
					);
					const option = crearOption(alumno, seleccionado);
					select.appendChild(option);

					if (seleccionado && !anteriores.has(select)) {
						anteriores.set(select, alumno.idAlumno.toString());
					}
				});
		} // Desactiva opciones si ya están seleccionadas en otros selects
		function actualizarSelects() {
			[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
				const esTipoC = select.name === "C";
				select.querySelectorAll("option").forEach((option) => {
					const alumnoId = option.value;
					if (!alumnoId) return;
					const yaSeleccionado = esTipoC
						? seleccionadosTipoC.has(alumnoId) &&
							anteriores.get(select) !== alumnoId
						: seleccionadosGenerales.has(alumnoId) &&
							anteriores.get(select) !== alumnoId;
					option.disabled = yaSeleccionado;
				});
			});
		}

		// Función auxiliar para crear una <option>
		function crearOption(alumno, seleccionado) {
			const option = document.createElement("option");
			option.value = alumno.idAlumno;
			option.textContent = alumno.nombre;
			if (seleccionado) option.selected = true;
			return option;
		}

		cargarSeleccionadosDesdeJSON();

		selectsMasculinos.forEach((select) => rellenarSelect(select, "M"));
		selectsFemeninos.forEach((select) => rellenarSelect(select, "F"));

		[...selectsMasculinos, ...selectsFemeninos].forEach((select) => {
			if (!anteriores.has(select)) anteriores.set(select, "");

			select.addEventListener("change", (event) => {
				const actual = event.target.value;
				const anterior = anteriores.get(select);
				const esTipoC = select.name === "C";

				if (anterior) {
					if (esTipoC) {
						seleccionadosTipoC.delete(anterior);
					} else {
						seleccionadosGenerales.delete(anterior);
					}
				}

				if (actual) {
					if (esTipoC) {
						seleccionadosTipoC.add(actual);
					} else {
						seleccionadosGenerales.add(actual);
					}
				}

				anteriores.set(select, actual);

				// Recargar todos los selects
				selectsMasculinos.forEach((s) => rellenarSelect(s, "M"));
				selectsFemeninos.forEach((s) => rellenarSelect(s, "F"));
			});
		});

		actualizarSelects();
	} catch (error) {
		console.error(
			"Error al rellenar selects con alumnos seleccionados:",
			error,
		);
	}
}

export { rellenarSelectsConAlumnos, rellenarSelectsConSeleccionados };
